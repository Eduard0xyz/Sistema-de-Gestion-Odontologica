<?php

require_once __DIR__ . '/../config/conexion.php';

class Odontologo {

    private $conexion;

    public $id_odontologo;
    public $nombres;
    public $apellidos;
    public $especialidad;
    public $telefono;
    public $correo;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

  
    public function registrarOdontologo() {
        $sql = "INSERT INTO odontologo (nombres, apellidos, especialidad, telefono, correo)
                VALUES (:nombres, :apellidos, :especialidad, :telefono, :correo)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':especialidad', $this->especialidad);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':correo', $this->correo);

        return $stmt->execute();
    }

    
    public function consultarAgenda($id_odontologo, $fecha_inicio, $fecha_fin) {
        $sql = "SELECT c.*, p.nombres, p.apellidos
                FROM cita c
                INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                WHERE c.id_odontologo = :id_odontologo
                AND c.fecha BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY c.fecha, c.hora";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_odontologo', $id_odontologo);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();

        return $stmt->fetchAll();
    }

   
    public function listarOdontologos() {
        $sql = "SELECT * FROM odontologo ORDER BY apellidos ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}