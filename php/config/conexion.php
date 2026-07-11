<?php
class Conexion {

    private $host = "localhost";
    private $nombre_bd = "clinica_dental22";
    private $usuario = "root";
    private $password = "";
    private $charset = "utf8mb4";

    private $pdo;

    public function conectar() {
        $dsn = "mysql:host={$this->host};dbname={$this->nombre_bd};charset={$this->charset}";
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->usuario, $this->password, $opciones);
            return $this->pdo;
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
