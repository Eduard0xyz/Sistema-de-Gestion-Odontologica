<?php
require_once __DIR__ . '/../config/conexion.php';

class Paciente {

    private $conexion;


    public $id_paciente;
    public $dni;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $correo;
    public $direccion;
    public $fecha_nacimiento;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }


    public function registrarPaciente() {
        $sql = "INSERT INTO paciente (dni, nombres, apellidos, telefono, correo, direccion, fecha_nacimiento)
                VALUES (:dni, :nombres, :apellidos, :telefono, :correo, :direccion, :fecha_nacimiento)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);

        return $stmt->execute();
    }


    public function buscarPaciente($criterio) {
        $sql = "SELECT * FROM paciente WHERE dni = :criterio OR apellidos LIKE :criterio2";
        $stmt = $this->conexion->prepare($sql);
        $like = "%" . $criterio . "%";
        $stmt->bindParam(':criterio', $criterio);
        $stmt->bindParam(':criterio2', $like);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function actualizarPaciente() {
        $sql = "UPDATE paciente SET nombres = :nombres, apellidos = :apellidos,
                telefono = :telefono, correo = :correo, direccion = :direccion
                WHERE id_paciente = :id_paciente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':id_paciente', $this->id_paciente);

        return $stmt->execute();
    }


    public function listarPacientes() {
        $sql = "SELECT * FROM paciente ORDER BY apellidos ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}