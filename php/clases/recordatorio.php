<?php
require_once _DIR_ . '/../config/conexion.php';

class Recordatorio {

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

    
    public function obtenerCitasProximas() {
        $sql = "SELECT c.id_cita, c.fecha, c.hora, p.nombres, p.apellidos, p.correo, p.telefono
                FROM cita c
                INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                WHERE c.fecha = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
                AND c.estado = 'Programada'";
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
}