<?php
$paginaActiva = 'citas';

require_once __DIR__ . '/../php/clases/paciente.php';
require_once __DIR__ . '/../php/clases/Odontologo.php';
require_once __DIR__ . '/../php/clases/Cita.php';

$pacienteObj = new Paciente();
$pacientes = $pacienteObj->listarPacientes();

$odontologoObj = new Odontologo();
$odontologos = $odontologoObj->listarOdontologos();

$citaObj = new Cita();
$citas = $citaObj->listarCitas();

$mensajeHtml = '';
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'exito_agendar':
            $mensajeHtml = '<div class="mensaje-exito">Cita agendada correctamente.</div>';
            break;
        case 'exito_reprogramar':
            $mensajeHtml = '<div class="mensaje-exito">Cita reprogramada correctamente.</div>';
            break;
        case 'exito_cancelar':
            $mensajeHtml = '<div class="mensaje-exito">Cita cancelada correctamente.</div>';
            break;
        case 'error_campos':
            $mensajeHtml = '<div class="mensaje-error">Complete paciente, odontólogo, fecha y hora para agendar la cita.</div>';
            break;
        case 'error_campos_reprogramar':
            $mensajeHtml = '<div class="mensaje-error">Ingrese la nueva fecha y hora para reprogramar la cita.</div>';
            break;
        case 'error_id_cita':
            $mensajeHtml = '<div class="mensaje-error">Ingrese el número de cita a reprogramar o cancelar.</div>';
            break;
        case 'cita_no_encontrada':
            $mensajeHtml = '<div class="mensaje-error">No se encontró ninguna cita con ese número.</div>';
            break;
        case 'error_choque_horario':
            $mensajeHtml = '<div class="mensaje-error">El odontólogo ya tiene otra cita activa en esa fecha y hora.</div>';
            break;
        case 'error_guardar':
            $mensajeHtml = '<div class="mensaje-error">Ocurrió un error al guardar los cambios en la base de datos.</div>';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/citas.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main class="pagina-citas">

        <?php echo $mensajeHtml; ?>

        
        <section class="seccion-cita">
            <h2>Agendar Cita</h2>
            <div class="linea-decorativa"></div>

            <form class="form-cita" action="../php/procesar/procesar_cita.php" method="POST">
                <div class="grupo-campo">
                    <label for="id_paciente">Paciente</label>
                    <select id="id_paciente" name="id_paciente" required>
                        <option value="">Seleccione un paciente</option>
                        <?php foreach ($pacientes as $pac): ?>
                            <option value="<?php echo $pac['id_paciente']; ?>">
                                <?php echo htmlspecialchars($pac['nombres'] . ' ' . $pac['apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grupo-campo">
                    <label for="id_odontologo">Odontólogo</label>
                    <select id="id_odontologo" name="id_odontologo" required>
                        <option value="">Seleccione un odontólogo</option>
                        <?php foreach ($odontologos as $odo): ?>
                            <option value="<?php echo $odo['id_odontologo']; ?>">
                                Dr(a). <?php echo htmlspecialchars($odo['nombres'] . ' ' . $odo['apellidos']); ?>
                                <?php if (!empty($odo['especialidad'])): ?>
                                    (<?php echo htmlspecialchars($odo['especialidad']); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grupo-campo">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="grupo-campo">
                    <label for="hora">Hora</label>
                    <input type="time" id="hora" name="hora" required>
                </div>

                <div class="grupo-campo campo-ancho">
                    <label for="motivo">Motivo de la cita</label>
                    <textarea id="motivo" name="motivo" rows="3" placeholder="Ej. Control, limpieza, dolor dental..."></textarea>
                </div>

                <div class="grupo-campo campo-boton">
                    <button type="submit" class="btn-agendar">
                        <span></span> Agendar Cita
                    </button>
                </div>
            </form>
        </section>

        
        <section class="seccion-cita seccion-gestion">
            <h2>Reprogramar / Cancelar Cita</h2>
            <div class="linea-decorativa"></div>

            <form class="form-gestion" action="../php/procesar/procesar_cita.php" method="POST">
                <div class="grupo-campo">
                    <label for="id_cita">N° de Cita</label>
                    <input type="number" id="id_cita" name="id_cita" placeholder="ID de la cita" min="1" required>
                </div>

                <div class="grupo-campo">
                    <label for="nueva_fecha">Nueva fecha</label>
                    <input type="date" id="nueva_fecha" name="nueva_fecha" min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="grupo-campo">
                    <label for="nueva_hora">Nueva hora</label>
                    <input type="time" id="nueva_hora" name="nueva_hora">
                </div>

                <div class="grupo-campo campo-boton">
                    <button type="submit" name="accion" value="reprogramar" class="btn-reprogramar">
                        <span></span> Reprogramar
                    </button>
                </div>

                <div class="grupo-campo campo-boton">
                    <button type="submit" name="accion" value="cancelar" class="btn-cancelar"
                            onclick="return confirm('¿Seguro que desea cancelar esta cita?');">
                        <span>✕</span> Cancelar Cita
                    </button>
                </div>
            </form>
        </section>

        <!-- SECCIÓN: LISTA -->
        <section class="seccion-cita">
            <h2>Lista de Citas</h2>
            <div class="linea-decorativa"></div>

            <div class="contenedor-tabla">
                <table class="tabla-citas">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Paciente</th>
                            <th>Odontólogo</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($citas)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:#78909c;">Todavía no hay citas registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($citas as $c): ?>
                                <?php
                                $estadoClase = strtolower($c['estado']); // programada | reprogramada | cancelada | atendida
                                $fechaFmt = date('d/m/Y', strtotime($c['fecha']));
                                $horaFmt  = date('h:i A', strtotime($c['hora']));
                                ?>
                                <tr>
                                    <td class="num-cita"><?php echo $c['id_cita']; ?></td>
                                    <td><?php echo htmlspecialchars($c['paciente_nombres'] . ' ' . $c['paciente_apellidos']); ?></td>
                                    <td>Dr(a). <?php echo htmlspecialchars($c['odontologo_nombres'] . ' ' . $c['odontologo_apellidos']); ?></td>
                                    <td><?php echo $fechaFmt; ?></td>
                                    <td><?php echo $horaFmt; ?></td>
                                    <td><span class="estado <?php echo $estadoClase; ?>"><?php echo htmlspecialchars($c['estado']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>