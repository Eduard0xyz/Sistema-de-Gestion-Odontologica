<?php
/**
 * Clase Cita
 * Gestiona el ciclo de vida de una cita odontológica: agendar,
 * listar, reprogramar y cancelar. Sigue el mismo patrón que las
 * demás clases del sistema (Paciente, Odontologo, HistorialClinico).
 */
require_once __DIR__ . '/../config/conexion.php';

class Cita {

    private $conexion;

    public $id_cita;
    public $id_paciente;
    public $id_odontologo;
    public $fecha;
    public $hora;
    public $motivo;
    public $estado;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    /**
     * Registra una nueva cita con estado 'Programada'.
     */
    public function registrarCita() {
        $sql = "INSERT INTO cita (id_paciente, id_odontologo, fecha, hora, motivo, estado)
                VALUES (:id_paciente, :id_odontologo, :fecha, :hora, :motivo, 'Programada')";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_paciente', $this->id_paciente, PDO::PARAM_INT);
        $stmt->bindParam(':id_odontologo', $this->id_odontologo, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':hora', $this->hora);
        $stmt->bindParam(':motivo', $this->motivo);

        return $stmt->execute();
    }

    /**
     * Verifica si el odontólogo ya tiene una cita activa (Programada o
     * Reprogramada) en esa fecha y hora, para evitar choques de agenda.
     */
    public function existeChoqueDeHorario($id_odontologo, $fecha, $hora, $id_cita_excluir = 0) {
        $sql = "SELECT COUNT(*) FROM cita
                WHERE id_odontologo = :id_odontologo
                AND fecha = :fecha
                AND hora = :hora
                AND estado IN ('Programada', 'Reprogramada')
                AND id_cita != :id_cita_excluir";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_odontologo', $id_odontologo, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':id_cita_excluir', $id_cita_excluir, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Lista todas las citas con los datos de paciente y odontólogo,
     * ordenadas de la más próxima/reciente a la más antigua.
     */
    public function listarCitas() {
        $sql = "SELECT c.id_cita, c.fecha, c.hora, c.motivo, c.estado,
                       p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
                       o.nombres AS odontologo_nombres, o.apellidos AS odontologo_apellidos
                FROM cita c
                INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                INNER JOIN odontologo o ON c.id_odontologo = o.id_odontologo
                ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene una cita puntual por su ID (para validar antes de
     * reprogramar o cancelar).
     */
    public function obtenerCitaPorId($id_cita) {
        $sql = "SELECT * FROM cita WHERE id_cita = :id_cita";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Reprograma una cita existente: cambia fecha/hora y marca el
     * estado como 'Reprogramada'.
     */
    public function reprogramarCita($id_cita, $nueva_fecha, $nueva_hora) {
        $sql = "UPDATE cita SET fecha = :fecha, hora = :hora, estado = 'Reprogramada'
                WHERE id_cita = :id_cita";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':fecha', $nueva_fecha);
        $stmt->bindParam(':hora', $nueva_hora);
        $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Cancela una cita existente.
     */
    public function cancelarCita($id_cita) {
        $sql = "UPDATE cita SET estado = 'Cancelada' WHERE id_cita = :id_cita";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
