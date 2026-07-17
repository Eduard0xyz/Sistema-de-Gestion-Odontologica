<?php
class EnviadorSMTP {

    private $host;
    private $puerto;
    private $usuario;
    private $password;
    private $nombreRemitente;
    private $timeout = 15;

    
    private $socket;

    public $ultimoError = '';
    public function __construct($host, $puerto, $usuario, $password, $nombreRemitente = '') {
        $this->host            = $host;
        $this->puerto          = $puerto;
        $this->usuario         = $usuario;
        $this->password        = $password;
        $this->nombreRemitente = $nombreRemitente ?: $usuario;
    }

    
    public function enviar($correoDestino, $nombreDestino, $asunto, $cuerpoHtml) {
        $this->ultimoError = '';

        if (empty($correoDestino) || !filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
            $this->ultimoError = "Direccion de correo destino invalida: {$correoDestino}";
            return false;
        }

        try {
            $this->conectar();
            $this->leerRespuesta(220);

            $this->saludarEhlo();
            $this->comando("STARTTLS", 220);
            $this->activarTls();

           
            $this->saludarEhlo();

            $this->autenticar();

            $this->comando("MAIL FROM:<{$this->usuario}>", 250);
            $this->comando("RCPT TO:<{$correoDestino}>", 250);
            $this->comando("DATA", 354);

            $mensaje = $this->construirMensaje($correoDestino, $nombreDestino, $asunto, $cuerpoHtml);
            $this->enviarCrudo($mensaje . "\r\n.\r\n");
            $this->leerRespuesta(250);

            $this->comando("QUIT", 221);
            $this->cerrar();

            return true;
        } catch (Exception $e) {
            $this->ultimoError = $e->getMessage();
            $this->cerrar();
            return false;
        }
    }

    private function conectar() {
        $contexto = stream_context_create();
        $this->socket = @stream_socket_client(
            "tcp://{$this->host}:{$this->puerto}",
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $contexto
        );

        if (!$this->socket) {
            throw new Exception("No se pudo conectar a {$this->host}:{$this->puerto} ({$errstr})");
        }

        stream_set_timeout($this->socket, $this->timeout);
    }

    private function saludarEhlo() {
        $dominioLocal = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        $this->comando("EHLO {$dominioLocal}", 250);
    }

    private function activarTls() {
        $metodo = STREAM_CRYPTO_METHOD_TLS_CLIENT;
        if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
            $metodo = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLS_CLIENT;
        }

        $ok = @stream_socket_enable_crypto($this->socket, true, $metodo);
        if (!$ok) {
            throw new Exception("No se pudo establecer la conexion segura TLS con el servidor SMTP.");
        }
    }

    private function autenticar() {
        $this->comando("AUTH LOGIN", 334);
        $this->comando(base64_encode($this->usuario), 334);
        $this->comando(base64_encode($this->password), 235);
    }

    private function construirMensaje($correoDestino, $nombreDestino, $asunto, $cuerpoHtml) {
        $fecha = date('r');
        $asuntoCodificado = '=?UTF-8?B?' . base64_encode($asunto) . '?=';
        $nombreRemitenteCodificado = '=?UTF-8?B?' . base64_encode($this->nombreRemitente) . '?=';
        $nombreDestinoCodificado = '=?UTF-8?B?' . base64_encode($nombreDestino) . '?=';

        $cabeceras = [];
        $cabeceras[] = "Date: {$fecha}";
        $cabeceras[] = "From: {$nombreRemitenteCodificado} <{$this->usuario}>";
        $cabeceras[] = "To: {$nombreDestinoCodificado} <{$correoDestino}>";
        $cabeceras[] = "Subject: {$asuntoCodificado}";
        $cabeceras[] = "MIME-Version: 1.0";
        $cabeceras[] = "Content-Type: text/html; charset=UTF-8";
        $cabeceras[] = "Content-Transfer-Encoding: 8bit";
        $cabeceras[] = "X-Mailer: EnviadorSMTP-PHP-Puro";
        $cuerpoEscapado = preg_replace('/^\./m', '..', $cuerpoHtml);

        return implode("\r\n", $cabeceras) . "\r\n\r\n" . $cuerpoEscapado;
    }

    private function comando($texto, $codigoEsperado) {
        $this->enviarCrudo($texto . "\r\n");
        $this->leerRespuesta($codigoEsperado, $texto);
    }

    private function enviarCrudo($texto) {
        if (@fwrite($this->socket, $texto) === false) {
            throw new Exception("No se pudo escribir en el socket SMTP.");
        }
    }

    private function leerRespuesta($codigoEsperado, $comandoOrigen = '') {
        $respuesta = '';
        while (!feof($this->socket)) {
            $linea = fgets($this->socket, 515);
            if ($linea === false) {
                break;
            }
            $respuesta .= $linea;
            
            if (isset($linea[3]) && $linea[3] === ' ') {
                break;
            }
        }

        if ($respuesta === '') {
            throw new Exception("El servidor SMTP no respondio" . ($comandoOrigen ? " al comando: {$comandoOrigen}" : "."));
        }

        $codigo = intval(substr($respuesta, 0, 3));
        if ($codigo !== $codigoEsperado) {
            throw new Exception("Respuesta SMTP inesperada" . ($comandoOrigen ? " para '{$comandoOrigen}'" : "") . ": {$respuesta}");
        }

        return $respuesta;
    }

    private function cerrar() {
        if ($this->socket) {
            @fclose($this->socket);
            $this->socket = null;
        }
    }
}