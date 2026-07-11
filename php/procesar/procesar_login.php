<?php
session_start();
require_once __DIR__ . '/../clases/Usuario.php';
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../vistas/login.php');
    exit;
}
 
$usuarioInput = trim($_POST['usuario'] ?? '');
$passwordInput = $_POST['password'] ?? '';
 
if ($usuarioInput === '' || $passwordInput === '') {
    header('Location: ../../vistas/login.php?error=vacio');
    exit;
}
 
$usuario = new Usuario();
$datos = $usuario->autenticar($usuarioInput, $passwordInput);
 
if ($datos) {
    $_SESSION['id_usuario'] = $datos['id_usuario'];
    $_SESSION['usuario']    = $datos['usuario'];
    $_SESSION['rol']        = $datos['rol'];
 
    header('Location: ../../vistas/index.php');
    exit;
} else {
    header('Location: ../../vistas/login.php?error=credenciales');
    exit;
}
 