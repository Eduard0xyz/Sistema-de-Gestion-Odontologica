<?php
session_start();
require_once __DIR__ . '/../clases/HistorialClinico.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registro de una nueva atención en el historial
    $id_paciente = intval($_POST['id_paciente'] ?? 0);
    $id_odontologo = intval($_POST['id_odontologo'] ?? 0);
    
    $tipo_fecha = trim($_POST['tipo_fecha'] ?? 'hoy');
    if ($tipo_fecha === 'hoy') {
        $fecha_atencion = date('Y-m-d');
    } else {
        $fecha_atencion = trim($_POST['fecha_atencion'] ?? '');
    }

    $tratamiento = trim($_POST['tratamiento'] ?? '');
    $diagnostico = trim($_POST['diagnostico'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    // Validación básica de campos obligatorios
    if ($id_paciente <= 0 || $id_odontologo <= 0 || empty($fecha_atencion) || empty($tratamiento)) {
        header("Location: ../../vistas/historial.php?id_paciente={$id_paciente}&mensaje=error_campos");
        exit;
    }

    $historial = new HistorialClinico();
    $historial->id_paciente = $id_paciente;
    $historial->id_odontologo = $id_odontologo;
    $historial->fecha_atencion = $fecha_atencion;
    $historial->tratamiento = $tratamiento;
    $historial->diagnostico = $diagnostico;
    $historial->observaciones = $observaciones;

    if ($historial->registrarHistorial()) {
        header("Location: ../../vistas/historial.php?id_paciente={$id_paciente}&mensaje=exito");
        exit;
    } else {
        header("Location: ../../vistas/historial.php?id_paciente={$id_paciente}&mensaje=error_guardar");
        exit;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Redirección de búsqueda de historial de un paciente específico
    $dni = trim($_GET['dni'] ?? '');
    $id_paciente = intval($_GET['id_paciente'] ?? 0);

    if (!empty($dni)) {
        $historialObj = new HistorialClinico();
        $pac = $historialObj->obtenerPacientePorDni($dni);
        if ($pac) {
            header("Location: ../../vistas/historial.php?id_paciente=" . $pac['id_paciente']);
            exit;
        } else {
            header("Location: ../../vistas/historial.php?mensaje=dni_no_encontrado&dni_buscado=" . urlencode($dni));
            exit;
        }
    } elseif ($id_paciente > 0) {
        header("Location: ../../vistas/historial.php?id_paciente=" . $id_paciente);
        exit;
    } else {
        header("Location: ../../vistas/historial.php?mensaje=campos_vacios");
        exit;
    }
} else {
    header("Location: ../../vistas/historial.php");
    exit;
}
