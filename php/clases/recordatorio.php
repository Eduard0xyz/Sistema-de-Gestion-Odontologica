<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/correo.php';
require_once __DIR__ . '/EnviadorSMTP.php';

class Recordatorio{

    private $conexion;

    public $id_recordatorio;
    public $id_cita;
    public $medio;
    public $fecha_envio;
    public $estado;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    /**
     * Obtiene las citas (Programadas o Reprogramadas) que caen dentro de
     * "$diasAntes" dias a partir de hoy (por defecto 1 dia = "manana"),
     * junto a los datos de contacto del paciente y del odontologo asignado.
     */
    public function obtenerCitasProximas($diasAntes = 1) {
        $sql = "SELECT c.id_cita, c.fecha, c.hora,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       p.correo, p.telefono,
                       o.nombres AS odontologo_nombres, o.apellidos AS odontologo_apellidos
                FROM cita c
                INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                INNER JOIN odontologo o ON c.id_odontologo = o.id_odontologo
                WHERE c.fecha = DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                AND c.estado IN ('Programada', 'Reprogramada')
                ORDER BY c.hora ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':dias', $diasAntes, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Verifica si una cita ya tiene un recordatorio marcado como "Enviado",
     * para no notificar dos veces al paciente si el script se ejecuta
     * mas de una vez el mismo dia (por ejemplo, si la tarea programada
     * corre de nuevo o alguien pulsa el boton dos veces).
     */
    public function yaFueEnviado($id_cita) {
        $sql = "SELECT COUNT(*) FROM recordatorio WHERE id_cita = :id_cita AND estado = 'Enviado'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Lista el historial de recordatorios ya registrados (enviados o
     * fallidos), con los datos de la cita y del paciente, del más
     * reciente al más antiguo. Se usa en la pantalla de recordatorios.
     */
    public function listarHistorial($limite = 50) {
        $limite = intval($limite);
        $sql = "SELECT r.id_recordatorio, r.medio, r.fecha_envio, r.estado,
                       c.fecha AS fecha_cita, c.hora AS hora_cita,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       p.correo
                FROM recordatorio r
                INNER JOIN cita c ON r.id_cita = c.id_cita
                INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                ORDER BY r.fecha_envio DESC
                LIMIT {$limite}";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function registrarEnvio() {
        $sql = "INSERT INTO recordatorio (id_cita, medio, fecha_envio, estado)
                VALUES (:id_cita, :medio, :fecha_envio, :estado)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_cita', $this->id_cita);
        $stmt->bindParam(':medio', $this->medio);
        $stmt->bindParam(':fecha_envio', $this->fecha_envio);
        $stmt->bindParam(':estado', $this->estado);

        return $stmt->execute();
    }

    /**
     * Arma y envia, por correo electronico, el recordatorio de una cita
     * puntual. El envio lo hace EnviadorSMTP (php/clases/EnviadorSMTP.php),
     * un cliente SMTP propio en PHP puro: sin Composer, sin PHPMailer, y
     * sin necesidad de instalar ni configurar nada en el servidor.
     * Devuelve true si el servidor de correo confirmo la entrega, false
     * si algo fallo.
     */
    public function enviarCorreo($cita) {
        $nombrePaciente   = htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']);
        $nombreOdontologo = htmlspecialchars($cita['odontologo_nombres'] . ' ' . $cita['odontologo_apellidos']);

        $fecha = date('d/m/Y', strtotime($cita['fecha']));
        $hora  = date('h:i A', strtotime($cita['hora']));

        $asunto = 'Recordatorio de tu cita - Clinica Dental';

        $cuerpo = "
            <html>
            <body style='font-family: Arial, sans-serif; color:#263544; line-height:1.5;'>
                <h2 style='color:#1b7f9e; margin-bottom:5px;'>Recordatorio de tu cita</h2>
                <p>Hola <strong>{$nombrePaciente}</strong>,</p>
                <p>Te recordamos que tienes una cita programada en la Clinica Dental:</p>
                <ul>
                    <li><strong>Fecha:</strong> {$fecha}</li>
                    <li><strong>Hora:</strong> {$hora}</li>
                    <li><strong>Odontologo:</strong> Dr(a). {$nombreOdontologo}</li>
                </ul>
                <p>Si necesitas reprogramar o cancelar, comunicate con la clinica con anticipacion.</p>
                <p>Te esperamos.</p>
            </body>
            </html>
        ";

        $enviador  = new EnviadorSMTP(SMTP_HOST, SMTP_PUERTO, SMTP_USUARIO, SMTP_PASSWORD, SMTP_NOMBRE_REMITENTE);
        $enviadoOk = $enviador->enviar($cita['correo'], $nombrePaciente, $asunto, $cuerpo);

        if (!$enviadoOk) {
            error_log('No se pudo enviar el recordatorio de la cita #' . $cita['id_cita'] . ' a ' . $cita['correo'] . ': ' . $enviador->ultimoError);
        }

        return $enviadoOk;
    }

    /**
     * Recorre las citas proximas, envia el correo a cada paciente que tenga
     * un correo registrado y aun no haya recibido su recordatorio, y deja
     * constancia del resultado en la tabla "recordatorio".
     *
     * @return array ['enviados' => int, 'fallidos' => int, 'omitidos' => int, 'detalle' => string[]]
     */
    public function enviarRecordatorios($diasAntes = 1) {
        $resumen = ['enviados' => 0, 'fallidos' => 0, 'omitidos' => 0, 'detalle' => []];
        $citas = $this->obtenerCitasProximas($diasAntes);

        foreach ($citas as $cita) {
            $nombreCompleto = $cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos'];

            if (empty($cita['correo'])) {
                $resumen['omitidos']++;
                $resumen['detalle'][] = "Cita #{$cita['id_cita']} ({$nombreCompleto}): sin correo registrado, se omite.";
                continue;
            }

            if ($this->yaFueEnviado($cita['id_cita'])) {
                $resumen['omitidos']++;
                $resumen['detalle'][] = "Cita #{$cita['id_cita']} ({$nombreCompleto}): el recordatorio ya se habia enviado.";
                continue;
            }

            $enviadoOk = $this->enviarCorreo($cita);

            $this->id_cita     = $cita['id_cita'];
            $this->medio       = 'Correo';
            $this->fecha_envio = date('Y-m-d H:i:s');
            $this->estado      = $enviadoOk ? 'Enviado' : 'Fallido';
            $this->registrarEnvio();

            if ($enviadoOk) {
                $resumen['enviados']++;
                $resumen['detalle'][] = "Cita #{$cita['id_cita']} ({$nombreCompleto}): recordatorio enviado a {$cita['correo']}.";
            } else {
                $resumen['fallidos']++;
                $resumen['detalle'][] = "Cita #{$cita['id_cita']} ({$nombreCompleto}): fallo al enviar el correo a {$cita['correo']}.";
            }
        }

        return $resumen;
    }
}