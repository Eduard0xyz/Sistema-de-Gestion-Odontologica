<?php
require_once __DIR__ . '/../clases/Horario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horario = new Horario();
    
    $horario->id_odontologo = $_POST['id_odontologo'];
    $horario->dia_semana = $_POST['dia_semana'];
    $horario->hora_inicio = $_POST['hora_inicio'];
    $horario->hora_fin = $_POST['hora_fin'];
    $horario->tipo_turno = $_POST['tipo_turno'];
    
    if ($horario->registrarHorario()) {
        header('Location: ../../vistas/odontologo.php?mensaje=horario_exito');
    } else {
        header('Location: ../../vistas/odontologo.php?mensaje=horario_error');
    }
}
?>