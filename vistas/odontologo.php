<?php 
$paginaActiva = 'odontologos'; 

require_once __DIR__ . '/../php/clases/Odontologo.php';

$odontologo = new Odontologo();
$odontologos = $odontologo->listarOdontologos();

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
        

        <h2>Consultar Agenda del Odontólogo</h2>

        <form action="../php/procesar/controladorOdontologo.php" method="GET" style="grid-template-columns: 1fr 1fr 1fr;">
            <div>
                <label for="id_odontologo">Odontólogo</label>
                <select id="id_odontologo" name="id_odontologo">
                    <?php if (empty($odontologos)): ?>
                        <option value="">No hay odontólogos registrados</option>
                    <?php else: ?>
                        <?php foreach ($odontologos as $odo): ?>
                            <option value="<?php echo $odo['id_odontologo']; ?>">
                                Dr./Dra. <?php echo htmlspecialchars($odo['nombres'] . ' ' . $odo['apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label for="fecha_inicio">Desde</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio">
            </div>
            <div>
                <label for="fecha_fin">Hasta</label>
                <input type="date" id="fecha_fin" name="fecha_fin">
            </div>
            <button type="submit" style="grid-column: 1 / span 3;">Consultar Agenda</button>
        </form>

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