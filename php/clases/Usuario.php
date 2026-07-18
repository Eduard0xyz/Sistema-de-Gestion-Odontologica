<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {

    private $conexion;

    public $id_usuario;
    public $usuario;
    public $password;
    public $rol;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }


    public function autenticar($usuarioInput, $passwordInput) {
        $sql = "SELECT id_usuario, usuario, password, rol FROM usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuarioInput);
        $stmt->execute();

        $datos = $stmt->fetch();

        if ($datos && password_verify($passwordInput, $datos['password'])) {
            return $datos;
        }

        return false;
    }
}
