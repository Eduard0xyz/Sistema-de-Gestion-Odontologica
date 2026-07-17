<?php
require_once __DIR__ . '/../clases/paciente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dni'])) {

    $dni = trim($_POST['dni']);

    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        header('Location: ../../vistas/pacientes.php?msg=error');
        exit;
    }

    if (!empty($_POST['telefono']) && !preg_match('/^9[0-9]{8}$/', trim($_POST['telefono']))) {
        header('Location: ../../vistas/pacientes.php?msg=error');
        exit;
    }

    $paciente = new Paciente();
    $paciente->dni              = $dni;
    $paciente->nombres          = trim($_POST['nombres']);
    $paciente->apellidos        = trim($_POST['apellidos']);
    $paciente->telefono         = trim($_POST['telefono']);
    $paciente->correo           = trim($_POST['correo']);
    $paciente->direccion        = trim($_POST['direccion']);
    $paciente->fecha_nacimiento = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;

    $existente = $paciente->buscarPaciente($dni);
    if (count($existente) > 0) {
        header('Location: ../../vistas/pacientes.php?msg=dni_duplicado');
        exit;
    }

    if ($paciente->registrarPaciente()) {
        header('Location: ../../vistas/pacientes.php?msg=ok');
    } else {
        header('Location: ../../vistas/pacientes.php?msg=error');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['criterio'])) {
    $criterio = trim($_GET['criterio']);
    header('Location: ../../vistas/pacientes.php?criterio=' . urlencode($criterio));
    exit;
}

header('Location: ../../vistas/pacientes.php');
exit;