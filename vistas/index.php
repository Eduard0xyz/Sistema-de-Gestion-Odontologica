<?php
$paginaActiva = 'index';
require_once __DIR__ . '/incluir/auth.php';

require_once __DIR__ . '/../php/clases/Cita.php';
require_once __DIR__ . '/../php/clases/paciente.php';
require_once __DIR__ . '/../php/clases/Odontologo.php';

$rol = $_SESSION['rol'] ?? '';
$usuarioSesion = $_SESSION['usuario'] ?? '';

$cita = new Cita();
$pacienteObj = new Paciente();
$odontologo = new Odontologo();

if ($rol === 'Administrador' || $rol === 'Recepcionista') {
    $totalPacientes = count($pacienteObj->listarPacientes());
    $totalOdontologos = count($odontologo->listarOdontologos());

    $todasLasCitas = $cita->listarCitas();
    $hoy = date('Y-m-d');

    $citasHoy = 0;
    $citasProgramadas = 0;
    $citasAtendidas = 0;
    $citasCanceladas = 0;

    foreach ($todasLasCitas as $c) {
        if ($c['fecha'] === $hoy) {
            $citasHoy++;
        }
        if ($c['estado'] === 'Programada') {
            $citasProgramadas++;
        } elseif ($c['estado'] === 'Atendida') {
            $citasAtendidas++;
        } elseif ($c['estado'] === 'Cancelada') {
            $citasCanceladas++;
        }
    }

    $ultimasCitas = array_slice($todasLasCitas, 0, 5);
}

if ($rol === 'Odontologo') {
    $todosOdontologos = $odontologo->listarOdontologos();
    $id_odontologo = 0;
    $odontologoActual = null;

    foreach ($todosOdontologos as $o) {
        $correo_base = explode('@', $o['correo'])[0];
        if ($correo_base === $usuarioSesion || strpos($o['correo'], $usuarioSesion) !== false) {
            $id_odontologo = $o['id_odontologo'];
            $odontologoActual = $o;
            break;
        }
    }

    if (!$id_odontologo && !empty($todosOdontologos)) {
        $id_odontologo = $todosOdontologos[0]['id_odontologo'];
        $odontologoActual = $todosOdontologos[0];
    }

    $citasHoyOdontologo = $odontologo->consultarAgenda($id_odontologo, date('Y-m-d'), date('Y-m-d'));
    $citasSemana = $odontologo->consultarAgenda($id_odontologo, date('Y-m-d'), date('Y-m-d', strtotime('+7 days')));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main>
        <?php if ($rol === 'Administrador' || $rol === 'Recepcionista'): ?>

            <h2>Dashboard - Panel de Control</h2>
            <p style="margin-bottom:20px;color:#6c8291;">Bienvenido, <?= htmlspecialchars($usuarioSesion) ?>. Resumen del sistema.</p>

            <div class="metricas" style="display:flex;flex-wrap:wrap;gap:15px;margin-bottom:25px;">
                <div class="tarjeta-metrica" style="background:#1b7f9e;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $totalPacientes ?></span>
                    <span class="etiqueta">Pacientes Registrados</span>
                </div>
                <div class="tarjeta-metrica" style="background:#2f9e6c;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $totalOdontologos ?></span>
                    <span class="etiqueta">Odontólogos</span>
                </div>
                <div class="tarjeta-metrica" style="background:#d68910;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $citasHoy ?></span>
                    <span class="etiqueta">Citas para Hoy</span>
                </div>
                <div class="tarjeta-metrica" style="background:#2980b9;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $citasProgramadas ?></span>
                    <span class="etiqueta">Citas Programadas</span>
                </div>
                <div class="tarjeta-metrica" style="background:#8e44ad;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $citasAtendidas ?></span>
                    <span class="etiqueta">Citas Atendidas</span>
                </div>
                <div class="tarjeta-metrica" style="background:#c0392b;color:#fff;padding:20px;border-radius:8px;min-width:150px;flex:1;">
                    <span class="numero" style="display:block;font-size:28px;font-weight:bold;"><?= $citasCanceladas ?></span>
                    <span class="etiqueta">Citas Canceladas</span>
                </div>
            </div>

            <h2 style="margin-top:30px;">Últimas Citas Registradas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Odontólogo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimasCitas as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['paciente_nombres'] . ' ' . $c['paciente_apellidos']) ?></td>
                        <td><?= htmlspecialchars($c['odontologo_nombres'] . ' ' . $c['odontologo_apellidos']) ?></td>
                        <td><?= htmlspecialchars($c['fecha']) ?></td>
                        <td><?= htmlspecialchars($c['hora']) ?></td>
                        <td><?= htmlspecialchars($c['estado']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ultimasCitas)): ?>
                    <tr><td colspan="5" style="text-align:center;color:#999;">No hay citas registradas</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php elseif ($rol === 'Odontologo' && $odontologoActual): ?>

            <h2>Mi Agenda - Dr(a). <?= htmlspecialchars($odontologoActual['nombres'] . ' ' . $odontologoActual['apellidos']) ?></h2>
            <p style="margin-bottom:20px;color:#6c8291;">Especialidad: <?= htmlspecialchars($odontologoActual['especialidad']) ?></p>

            <h3>Citas para Hoy (<?= date('d/m/Y') ?>)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citasHoyOdontologo as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?></td>
                        <td><?= htmlspecialchars($c['hora']) ?></td>
                        <td><?= htmlspecialchars($c['motivo']) ?></td>
                        <td><?= htmlspecialchars($c['estado']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($citasHoyOdontologo)): ?>
                    <tr><td colspan="4" style="text-align:center;color:#999;">No tiene citas programadas para hoy</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h3 style="margin-top:25px;">Próximas Citas (7 días)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citasSemana as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?></td>
                        <td><?= htmlspecialchars($c['fecha']) ?></td>
                        <td><?= htmlspecialchars($c['hora']) ?></td>
                        <td><?= htmlspecialchars($c['motivo']) ?></td>
                        <td><?= htmlspecialchars($c['estado']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($citasSemana)): ?>
                    <tr><td colspan="5" style="text-align:center;color:#999;">No hay citas programadas para los próximos 7 días</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php else: ?>

            <h2>Bienvenido al Sistema de Gestión Odontológica</h2>
            <p style="margin-bottom:15px;">
                Este sistema permite centralizar la gestión de citas, historiales clínicos y
                recordatorios automáticos de la Clínica Dental, digitalizando los procesos
                que actualmente se realizan en fichas físicas.
            </p>
            <p><strong>Módulos disponibles:</strong></p>
            <ul style="margin:15px 0 0 20px; line-height: 1.8;">
                <li>Gestión de Pacientes</li>
                <li>Gestión de Odontólogos</li>
                <li>Gestión de Citas (agendar, reprogramar, cancelar)</li>
                <li>Historial Clínico Digital</li>
                <li>Recordatorios Automáticos</li>
            </ul>

        <?php endif; ?>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>