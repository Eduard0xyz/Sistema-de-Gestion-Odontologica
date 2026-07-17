<?php
require_once __DIR__ . '/../clases/Odontologo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $odontologo = new Odontologo();
    
    $odontologo->nombres = $_POST['nombres'];
    $odontologo->apellidos = $_POST['apellidos'];
    $odontologo->especialidad = $_POST['especialidad'];
    $odontologo->telefono = $_POST['telefono'];
    $odontologo->correo = $_POST['correo'];
    
    if ($odontologo->registrarOdontologo()) {
        header('Location: ../../vistas/odontologo.php?mensaje=exito');
    } else {
        header('Location: ../../vistas/odontologo.php?mensaje=error');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
}
?>