<?php
session_start();
require_once __DIR__ . '/../clases/Cita.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = trim($_POST['accion'] ?? '');

    if ($accion === 'reprogramar' || $accion === 'cancelar') {
        // -------- Reprogramar / Cancelar cita --------
        $id_cita = intval($_POST['id_cita'] ?? 0);

        if ($id_cita <= 0) {
            header("Location: ../../vistas/citas.php?mensaje=error_id_cita");
            exit;
        }

        $citaObj = new Cita();
        $citaExistente = $citaObj->obtenerCitaPorId($id_cita);

        if (!$citaExistente) {
            header("Location: ../../vistas/citas.php?mensaje=cita_no_encontrada");
            exit;
        }

        if ($accion === 'cancelar') {
            if ($citaObj->cancelarCita($id_cita)) {
                header("Location: ../../vistas/citas.php?mensaje=exito_cancelar");
            } else {
                header("Location: ../../vistas/citas.php?mensaje=error_guardar");
            }
            exit;
        }

        // accion === 'reprogramar'
        $nueva_fecha = trim($_POST['nueva_fecha'] ?? '');
        $nueva_hora  = trim($_POST['nueva_hora'] ?? '');

        if (empty($nueva_fecha) || empty($nueva_hora)) {
            header("Location: ../../vistas/citas.php?mensaje=error_campos_reprogramar");
            exit;
        }

        if ($citaObj->existeChoqueDeHorario($citaExistente['id_odontologo'], $nueva_fecha, $nueva_hora, $id_cita)) {
            header("Location: ../../vistas/citas.php?mensaje=error_choque_horario");
            exit;
        }

        if ($citaObj->reprogramarCita($id_cita, $nueva_fecha, $nueva_hora)) {
            header("Location: ../../vistas/citas.php?mensaje=exito_reprogramar");
        } else {
            header("Location: ../../vistas/citas.php?mensaje=error_guardar");
        }
        exit;

    } else {
        // -------- Agendar nueva cita --------
        $id_paciente   = intval($_POST['id_paciente'] ?? 0);
        $id_odontologo = intval($_POST['id_odontologo'] ?? 0);
        $fecha         = trim($_POST['fecha'] ?? '');
        $hora          = trim($_POST['hora'] ?? '');
        $motivo        = trim($_POST['motivo'] ?? '');

        if ($id_paciente <= 0 || $id_odontologo <= 0 || empty($fecha) || empty($hora)) {
            header("Location: ../../vistas/citas.php?mensaje=error_campos");
            exit;
        }

        $citaObj = new Cita();

        if ($citaObj->existeChoqueDeHorario($id_odontologo, $fecha, $hora)) {
            header("Location: ../../vistas/citas.php?mensaje=error_choque_horario");
            exit;
        }

        $citaObj->id_paciente   = $id_paciente;
        $citaObj->id_odontologo = $id_odontologo;
        $citaObj->fecha         = $fecha;
        $citaObj->hora          = $hora;
        $citaObj->motivo        = $motivo;

        if ($citaObj->registrarCita()) {
            header("Location: ../../vistas/citas.php?mensaje=exito_agendar");
        } else {
            header("Location: ../../vistas/citas.php?mensaje=error_guardar");
        }
        exit;
    }

} else {
    header("Location: ../../vistas/citas.php");
    exit;
}
