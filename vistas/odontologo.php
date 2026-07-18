<?php 
$paginaActiva = 'odontologos'; 

require_once __DIR__ . '/../php/clases/Odontologo.php';
require_once __DIR__ . '/../php/clases/Horario.php';

$odontologo = new Odontologo();
$odontologos = $odontologo->listarOdontologos();
// variables
$horarios_mostrados = [];
$nombre_doctor_mostrado = '';
$horario_agrupado = []; 



$dias_semana_nombres = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];

if (isset($_GET['ver_id_odontologo']) && !empty($_GET['ver_id_odontologo'])) {
    $horarioObj = new Horario();
    $horarios_mostrados = $horarioObj->obtenerHorarioPorOdontologo($_GET['ver_id_odontologo']);
    
    foreach ($odontologos as $odo) {
        if ($odo['id_odontologo'] == $_GET['ver_id_odontologo']) {
            $nombre_doctor_mostrado = $odo['nombres'] . ' ' . $odo['apellidos'];
            break;
        }
    }

    foreach ($horarios_mostrados as $turno) {
        $horario_agrupado[$turno['dia_semana']][] = $turno; 
    }
}



$mensaje = '';
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] === 'exito') {
        $mensaje = '<div class="mensaje-exito">Odontólogo registrado correctamente</div>';
    } elseif ($_GET['mensaje'] === 'error') {
        $mensaje = '<div class="mensaje-error">Error al registrar el odontólogo</div>';
    }elseif ($_GET['mensaje'] === 'horario_exito') {
        $mensaje = '<div class="mensaje-exito">Horario asignado correctamente</div>';
    } elseif ($_GET['mensaje'] === 'horario_error') {
        $mensaje = '<div class="mensaje-error">Error al asignar el horario. Verifica que no se esté duplicando un turno exacto.</div>';
    }

        
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Odontólogos - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/odontologos.css">
    <link rel="stylesheet" href="../css/horario.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main>
        <?php echo $mensaje; ?>

        <h2>Registrar Odontólogo</h2>

        <form action="../php/procesar/controladorOdontologo.php" method="POST">
            <div>
                <label for="nombres">Nombres</label>
                <input type="text" id="nombres" name="nombres" required>
            </div>
            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>
            <div>
                <label for="especialidad">Especialidad</label>
                <select id="especialidad" name="especialidad">
                    <option value="Odontología general">Odontología general</option>
                    <option value="Ortodoncia">Ortodoncia</option>
                    <option value="Endodoncia">Endodoncia</option>
                    <option value="Cirugía maxilofacial">Cirugía maxilofacial</option>
                </select>
            </div>
            <div>
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono">
            </div>
            <div class="campo-completo">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo">
            </div>
            <button type="submit">Registrar Odontólogo</button>
        </form>

        <?php echo $mensaje; ?>
        
       <h2>Asignar Horario a Odontólogo</h2>
        <form action="../php/procesar/procesar_horario.php" method="POST" style="grid-template-columns: 1fr 1fr;">
            <div>
                <label for="id_odontologo_horario">Odontólogo</label>
                <select id="id_odontologo_horario" name="id_odontologo" required>
                    <option value="">Seleccione un odontólogo...</option>
                    <?php foreach ($odontologos as $odo): ?>
                        <option value="<?php echo $odo['id_odontologo']; ?>">
                            Dr./Dra. <?php echo htmlspecialchars($odo['nombres'] . ' ' . $odo['apellidos']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="dia_semana">Día de la semana</label>
                <select id="dia_semana" name="dia_semana" required>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">Sábado</option>
                    <option value="7">Domingo</option>
                </select>
            </div>
            <div>
                <label for="hora_inicio">Hora de inicio</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required>
            </div>
            <div>
                <label for="hora_fin">Hora de fin</label>
                <input type="time" id="hora_fin" name="hora_fin" required>
            </div>
            <div class="campo-completo">
                <label for="tipo_turno">Tipo de Turno (Ej. Consulta general, Cirugía)</label>
                <input type="text" id="tipo_turno" name="tipo_turno" value="Consulta general" required>
            </div>
            <button type="submit" style="grid-column: 1 / span 2;">Guardar Horario</button>
        </form>



        <h2>Ver Horario Semanal</h2>
        <!-- Formulario para seleccionar doctor -->
        <form action="odontologo.php" method="GET" style="grid-template-columns: 1fr auto;">
            <div>
                <label for="ver_id_odontologo">Seleccionar Odontólogo</label>
                <select id="ver_id_odontologo" name="ver_id_odontologo" required>
                    <option value="">Seleccione un odontólogo...</option>
                    <?php foreach ($odontologos as $odo): ?>
                        <option value="<?php echo $odo['id_odontologo']; ?>" <?php echo (isset($_GET['ver_id_odontologo']) && $_GET['ver_id_odontologo'] == $odo['id_odontologo']) ? 'selected' : ''; ?>>
                            Dr./Dra. <?php echo htmlspecialchars($odo['nombres'] . ' ' . $odo['apellidos']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" style="align-self: end; margin-bottom: 5px;">Ver Horario</button>
        </form>


        <?php if (isset($_GET['ver_id_odontologo'])): ?>
            <h3>Horario de: Dr./Dra. <?php echo htmlspecialchars($nombre_doctor_mostrado); ?></h3>
            
            <div class="horario-timeline-grid">
                
                <?php for ($i = 1; $i <= 7; $i++): ?>
                    <div class="dia-columna">
                        <div class="dia-header"><?php echo $dias_semana_nombres[$i]; ?></div>
                        
                        <?php if (isset($horario_agrupado[$i]) && !empty($horario_agrupado[$i])): ?>
                            
                            <?php foreach ($horario_agrupado[$i] as $h): ?>
                                <div class="bloque-turno">
                                    <span class="turno-hora">
                                        <?php 
                                            echo date('H:i', strtotime($h['hora_inicio'])) . ' - ' . date('H:i', strtotime($h['hora_fin'])); 
                                        ?>
                                    </span>
                                    <span class="turno-tipo">
                                        <?php echo htmlspecialchars($h['tipo_turno']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <div class="dia-vacio">Sin turnos</div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

            </div> 
        <?php endif; ?>


        <h2>Lista de Odontólogos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($odontologos)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay odontólogos registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($odontologos as $odo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($odo['id_odontologo']); ?></td>
                            <td><?php echo htmlspecialchars($odo['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($odo['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($odo['especialidad']); ?></td>
                            <td><?php echo htmlspecialchars($odo['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($odo['correo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>