<?php
require_once __DIR__ . '/../config/conexion.php';

class Horario {
    private $conexion;

    public $id_odontologo;
    public $dia_semana;
    public $hora_inicio;
    public $hora_fin;
    public $tipo_turno;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    public function registrarHorario() {
        try {
            $sql = "INSERT INTO horarios_odontologos (id_odontologo, dia_semana, hora_inicio, hora_fin, tipo_turno)
                    VALUES (:id_odontologo, :dia_semana, :hora_inicio, :hora_fin, :tipo_turno)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_odontologo', $this->id_odontologo);
            $stmt->bindParam(':dia_semana', $this->dia_semana);
            $stmt->bindParam(':hora_inicio', $this->hora_inicio);
            $stmt->bindParam(':hora_fin', $this->hora_fin);
            $stmt->bindParam(':tipo_turno', $this->tipo_turno);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Para la restricción UNIQUE de la BD
            return false; 
        }
    }

    public function obtenerHorarioPorOdontologo($id_odontologo) {
        $sql = "SELECT id_odontologo, dia_semana, hora_inicio, hora_fin, tipo_turno 
                FROM horarios_odontologos 
                WHERE id_odontologo = :id_odontologo AND activo = TRUE 
                ORDER BY dia_semana ASC, hora_inicio ASC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_odontologo', $id_odontologo);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>