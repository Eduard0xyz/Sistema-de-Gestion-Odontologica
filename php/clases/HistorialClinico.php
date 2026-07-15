<?php
/**
 * Clase HistorialClinico
 * Gestiona el registro y consulta del historial clínico digital
 * del paciente, según el diagrama de secuencia "Registrar Historial Clínico".
 */
require_once __DIR__ . '/../config/conexion.php';

class HistorialClinico {

    private $conexion;

    public $id_historial;
    public $id_paciente;
    public $id_odontologo;
    public $fecha_atencion;
    public $diagnostico;
    public $observaciones;
    public $tratamiento;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    /**
     * Registra una nueva entrada en el historial clínico del paciente.
     */
    public function registrarHistorial() {
        $sql = "INSERT INTO historial_clinico
                (id_paciente, id_odontologo, fecha_atencion, diagnostico, observaciones, tratamiento)
                VALUES (:id_paciente, :id_odontologo, :fecha_atencion, :diagnostico, :observaciones, :tratamiento)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_paciente', $this->id_paciente);
        $stmt->bindParam(':id_odontologo', $this->id_odontologo);
        $stmt->bindParam(':fecha_atencion', $this->fecha_atencion);
        $stmt->bindParam(':diagnostico', $this->diagnostico);
        $stmt->bindParam(':observaciones', $this->observaciones);
        $stmt->bindParam(':tratamiento', $this->tratamiento);

        return $stmt->execute();
    }

    /**
     * Consulta el historial clínico completo de un paciente específico.
     */
    public function consultarHistorialPorPaciente($id_paciente) {
        $sql = "SELECT h.*, o.nombres AS odontologo_nombres, o.apellidos AS odontologo_apellidos
                FROM historial_clinico h
                INNER JOIN odontologo o ON h.id_odontologo = o.id_odontologo
                WHERE h.id_paciente = :id_paciente
                ORDER BY h.fecha_atencion DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Actualiza una entrada del historial clínico (por ejemplo, para
     * completar observaciones tras una revisión posterior).
     */
    public function actualizarHistorial() {
        $sql = "UPDATE historial_clinico
                SET diagnostico = :diagnostico, observaciones = :observaciones, tratamiento = :tratamiento
                WHERE id_historial = :id_historial";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':diagnostico', $this->diagnostico);
        $stmt->bindParam(':observaciones', $this->observaciones);
        $stmt->bindParam(':tratamiento', $this->tratamiento);
        $stmt->bindParam(':id_historial', $this->id_historial);

        return $stmt->execute();
    }

    /**
     * Obtiene los datos de un paciente por su ID.
     */
    public function obtenerPacientePorId($id_paciente) {
        $sql = "SELECT * FROM paciente WHERE id_paciente = :id_paciente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los datos de un paciente por su DNI.
     */
    public function obtenerPacientePorDni($dni) {
        $sql = "SELECT * FROM paciente WHERE dni = :dni";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}