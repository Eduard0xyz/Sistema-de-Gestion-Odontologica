<?php 
$paginaActiva = 'historial'; 

require_once __DIR__ . '/../php/clases/paciente.php';
require_once __DIR__ . '/../php/clases/Odontologo.php';
require_once __DIR__ . '/../php/clases/HistorialClinico.php';

$pacienteObj = new Paciente();
$pacientes = $pacienteObj->listarPacientes();

$odontologoObj = new Odontologo();
$odontologos = $odontologoObj->listarOdontologos();

$id_paciente = intval($_GET['id_paciente'] ?? 0);
$pacienteSeleccionado = null;
$historiales = [];

if ($id_paciente > 0) {
    $historialObj = new HistorialClinico();
    $pacienteSeleccionado = $historialObj->obtenerPacientePorId($id_paciente);
    if ($pacienteSeleccionado) {
        $historiales = $historialObj->consultarHistorialPorPaciente($id_paciente);
    }
}

// Mensajes de respuesta
$mensajeHtml = '';
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] === 'exito') {
        $mensajeHtml = '<div class="mensaje-exito">Atención registrada correctamente en el historial clínico.</div>';
    } elseif ($_GET['mensaje'] === 'error_campos') {
        $mensajeHtml = '<div class="mensaje-error">Por favor, complete todos los campos obligatorios del formulario.</div>';
    } elseif ($_GET['mensaje'] === 'error_guardar') {
        $mensajeHtml = '<div class="mensaje-error">Ocurrió un error al registrar la atención en la base de datos.</div>';
    } elseif ($_GET['mensaje'] === 'dni_no_encontrado') {
        $dniBuscado = htmlspecialchars($_GET['dni_buscado'] ?? '');
        $mensajeHtml = "<div class='mensaje-error'>No se encontró ningún paciente con el DNI: <strong>{$dniBuscado}</strong>.</div>";
    } elseif ($_GET['mensaje'] === 'campos_vacios') {
        $mensajeHtml = '<div class="mensaje-error">Por favor, ingrese un DNI o seleccione un paciente para consultar.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/historial.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main>
        <?php echo $mensajeHtml; ?>

        <h2>Registrar Atención en Historial Clínico</h2>
        <form action="../php/procesar/procesar_historial.php" method="POST">
            <div>
                <label for="id_paciente">Paciente *</label>
                <select id="id_paciente" name="id_paciente" required>
                    <option value="">Seleccione un paciente</option>
                    <?php foreach ($pacientes as $pac): ?>
                        <option value="<?php echo $pac['id_paciente']; ?>" <?php echo ($id_paciente === intval($pac['id_paciente'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pac['nombres'] . ' ' . $pac['apellidos']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="id_odontologo">Odontólogo *</label>
                <select id="id_odontologo" name="id_odontologo" required>
                    <option value="">Seleccione un odontólogo</option>
                    <?php foreach ($odontologos as $odo): ?>
                        <option value="<?php echo $odo['id_odontologo']; ?>">
                            Dr./Dra. <?php echo htmlspecialchars($odo['nombres'] . ' ' . $odo['apellidos']); ?> (<?php echo htmlspecialchars($odo['especialidad']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Fecha de atención *</label>
                <div style="display: flex; gap: 20px; margin-top: 8px;">
                    <label style="font-weight: normal; color: #263544; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="tipo_fecha" value="hoy" checked id="radio_fecha_hoy"> Hoy (<?php echo date('d/m/Y'); ?>)
                    </label>
                    <label style="font-weight: normal; color: #263544; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="tipo_fecha" value="otra" id="radio_fecha_otra"> Elegir otra fecha
                    </label>
                </div>
                <div id="contenedorFechaAtencion" class="contenedor-fecha-custom">
                    <input type="date" id="fecha_atencion" name="fecha_atencion" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div>
                <label for="tratamiento">Tratamiento *</label>
                <input type="text" id="tratamiento" name="tratamiento" placeholder="Ej. Limpieza dental, extracción..." required>
            </div>
            <div class="campo-completo">
                <label for="diagnostico">Diagnóstico</label>
                <textarea id="diagnostico" name="diagnostico" placeholder="Diagnóstico del odontólogo"></textarea>
            </div>
            <div class="campo-completo">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" placeholder="Observaciones adicionales"></textarea>
            </div>
            <button type="submit">Guardar en Historial</button>
        </form>

        <h2>Buscar Historial por Paciente</h2>
        <form action="../php/procesar/procesar_historial.php" method="GET" style="grid-template-columns: 2fr 2fr auto; align-items: end;">
            <div>
                <label for="buscar_dni">Buscar por DNI</label>
                <input type="text" id="buscar_dni" name="dni" placeholder="Ej. 71234567" maxlength="8">
            </div>
            <div>
                <label for="buscar_paciente">O seleccionar de la Lista</label>
                <select id="buscar_paciente" name="id_paciente">
                    <option value="">Seleccione un paciente para consultar</option>
                    <?php foreach ($pacientes as $pac): ?>
                        <option value="<?php echo $pac['id_paciente']; ?>" <?php echo ($id_paciente === intval($pac['id_paciente'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pac['nombres'] . ' ' . $pac['apellidos']); ?> (DNI: <?php echo htmlspecialchars($pac['dni']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" style="grid-column:auto;">Buscar</button>
        </form>

        <?php if ($pacienteSeleccionado): ?>
            <h2>Historial Clínico - <?php echo htmlspecialchars($pacienteSeleccionado['nombres'] . ' ' . $pacienteSeleccionado['apellidos']); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Fecha</th>
                        <th style="width: 30%;">Odontólogo</th>
                        <th style="width: 40%;">Tratamiento</th>
                        <th style="width: 15%; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historiales)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #78909c;">No se encontraron registros de atención para este paciente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historiales as $h): ?>
                            <?php 
                            $fechaFmt = date('d/m/Y', strtotime($h['fecha_atencion']));
                            $odoNombre = 'Dr./Dra. ' . htmlspecialchars($h['odontologo_nombres'] . ' ' . $h['odontologo_apellidos']);
                            $pacNombre = htmlspecialchars($pacienteSeleccionado['nombres'] . ' ' . $pacienteSeleccionado['apellidos']);
                            ?>
                            <tr>
                                <td><?php echo $fechaFmt; ?></td>
                                <td><?php echo $odoNombre; ?></td>
                                <td><?php echo htmlspecialchars($h['tratamiento']); ?></td>
                                <td style="text-align: center;">
                                    <button class="btn-ver-detalle" 
                                            data-fecha="<?php echo $fechaFmt; ?>"
                                            data-paciente="<?php echo $pacNombre; ?>"
                                            data-odontologo="<?php echo $odoNombre; ?>"
                                            data-tratamiento="<?php echo htmlspecialchars($h['tratamiento']); ?>"
                                            data-diagnostico="<?php echo htmlspecialchars($h['diagnostico'] ?? 'Sin diagnóstico'); ?>"
                                            data-observaciones="<?php echo htmlspecialchars($h['observaciones'] ?? 'Sin observaciones'); ?>">
                                        Ver Detalle
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="background: #ffffff; padding: 25px; text-align: center; border: 1px solid #d6e4f0; border-radius: 8px; color: #78909c;">
                <p>Seleccione un paciente arriba para visualizar su historial clínico dental completo.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal Ver Detalle Historial Clínico -->
    <div id="modalHistorial" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Detalle de la Atención Dental</h3>
                <button type="button" class="modal-close" id="btnCerrarX">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-field">
                    <label>Paciente</label>
                    <div id="modalPaciente"></div>
                </div>
                <div class="modal-field">
                    <label>Fecha de Atención</label>
                    <div id="modalFecha"></div>
                </div>
                <div class="modal-field full-width">
                    <label>Odontólogo Tratante</label>
                    <div id="modalOdontologo"></div>
                </div>
                <div class="modal-field full-width">
                    <label>Tratamiento Realizado</label>
                    <div id="modalTratamiento"></div>
                </div>
                <div class="modal-field full-width">
                    <label>Diagnóstico</label>
                    <div id="modalDiagnostico"></div>
                </div>
                <div class="modal-field full-width">
                    <label>Observaciones</label>
                    <div id="modalObservaciones"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cerrar-modal" id="btnCerrarFooter">Cerrar</button>
            </div>
        </div>
    </div>

    <?php include 'incluir/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalHistorial');
            const btnCerrarX = document.getElementById('btnCerrarX');
            const btnCerrarFooter = document.getElementById('btnCerrarFooter');
            
            // Modal fields
            const mPaciente = document.getElementById('modalPaciente');
            const mFecha = document.getElementById('modalFecha');
            const mOdontologo = document.getElementById('modalOdontologo');
            const mTratamiento = document.getElementById('modalTratamiento');
            const mDiagnostico = document.getElementById('modalDiagnostico');
            const mObservaciones = document.getElementById('modalObservaciones');

            // Open modal and populate
            document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
                btn.addEventListener('click', () => {
                    mPaciente.textContent = btn.getAttribute('data-paciente');
                    mFecha.textContent = btn.getAttribute('data-fecha');
                    mOdontologo.textContent = btn.getAttribute('data-odontologo');
                    mTratamiento.textContent = btn.getAttribute('data-tratamiento');
                    mDiagnostico.textContent = btn.getAttribute('data-diagnostico');
                    mObservaciones.textContent = btn.getAttribute('data-observaciones');

                    modal.classList.add('active');
                });
            });

            // Close modal functions
            const closeModal = () => {
                modal.classList.remove('active');
            };

            btnCerrarX.addEventListener('click', closeModal);
            btnCerrarFooter.addEventListener('click', closeModal);
            
            // Close when clicking outside modal container
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Toggle custom date field in register form
            const radioHoy = document.getElementById('radio_fecha_hoy');
            const radioOtra = document.getElementById('radio_fecha_otra');
            const contenedorFecha = document.getElementById('contenedorFechaAtencion');
            const inputFecha = document.getElementById('fecha_atencion');

            if (radioHoy && radioOtra && contenedorFecha && inputFecha) {
                const toggleFecha = () => {
                    if (radioOtra.checked) {
                        contenedorFecha.classList.add('visible');
                        inputFecha.setAttribute('required', 'required');
                        inputFecha.focus();
                    } else {
                        contenedorFecha.classList.remove('visible');
                        inputFecha.removeAttribute('required');
                        // Reset to today
                        inputFecha.value = '<?php echo date('Y-m-d'); ?>';
                    }
                };

                radioHoy.addEventListener('change', toggleFecha);
                radioOtra.addEventListener('change', toggleFecha);
            }
        });
    </script>
</body>
</html>