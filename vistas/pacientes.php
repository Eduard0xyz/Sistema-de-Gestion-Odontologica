<?php 
$paginaActiva = 'pacientes';
require_once '../php/clases/paciente.php';

$paciente = new Paciente();

$criterioBusqueda = isset($_GET['criterio']) ? trim($_GET['criterio']) : '';

if ($criterioBusqueda !== '') {
    $listaPacientes = $paciente->buscarPaciente($criterioBusqueda);
} else {
    $listaPacientes = $paciente->listarPacientes();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/pacientes.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main>
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'ok'): ?>
                <p class="mensaje mensaje-exito">Paciente registrado correctamente.</p>
            <?php elseif ($_GET['msg'] === 'dni_duplicado'): ?>
                <p class="mensaje mensaje-error">Ya existe un paciente registrado con ese DNI.</p>
            <?php elseif ($_GET['msg'] === 'error'): ?>
                <p class="mensaje mensaje-error">Ocurrió un error al registrar el paciente. Intenta nuevamente.</p>
            <?php endif; ?>
        <?php endif; ?>

        <h2>Buscar Paciente</h2>
        <form action="../php/procesar/procesar_paciente.php" method="GET" style="grid-template-columns: 3fr 1fr;">
            <div>
                <label for="criterio">Buscar por DNI o Apellido</label>
                <input type="text" id="criterio" name="criterio" placeholder="Ingrese DNI o apellido" value="<?php echo htmlspecialchars($criterioBusqueda); ?>">
            </div>
            <button type="submit" style="grid-column:auto; align-self:end;">Buscar</button>
        </form>
        <?php if ($criterioBusqueda !== ''): ?>
            <p><a href="pacientes.php">← Ver todos los pacientes</a></p>
        <?php endif; ?>

        <h2>Registrar Paciente</h2>
        <form action="../php/procesar/procesar_paciente.php" method="POST" onsubmit="return confirm('¿Confirmas que los datos del paciente son correctos?');">
            <div>
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" maxlength="8" placeholder="Ej. 71234567" required
                       pattern="[0-9]{8}" inputmode="numeric" title="Debe contener exactamente 8 números"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);">
            </div>
            <div>
                <label for="nombres">Nombres</label>
                <input type="text" id="nombres" name="nombres" placeholder="Nombres del paciente" required
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                       oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');">
            </div>
            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos del paciente" required
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                       oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');">
            </div>
            <div>
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                       min="1920-01-01" max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ej. 987654321"
                       maxlength="9" pattern="9[0-9]{8}" inputmode="numeric"
                       title="Debe empezar con 9 y tener 9 dígitos en total"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);">
            </div>
            <div>
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com">
            </div>
            <div class="campo-completo">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" placeholder="Dirección del paciente">
            </div>
            <button type="submit">Registrar Paciente</button>
        </form>

        <h2>Lista de Pacientes</h2>
        <table>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($listaPacientes) === 0): ?>
                    <tr>
                        <td colspan="6">No se encontraron pacientes.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listaPacientes as $p): ?>
                        <tr>
                            <td data-label="DNI"><?php echo htmlspecialchars($p['dni']); ?></td>
                            <td data-label="Nombres"><?php echo htmlspecialchars($p['nombres']); ?></td>
                            <td data-label="Apellidos"><?php echo htmlspecialchars($p['apellidos']); ?></td>
                            <td data-label="Teléfono"><?php echo htmlspecialchars($p['telefono']); ?></td>
                            <td data-label="Correo"><?php echo htmlspecialchars($p['correo']); ?></td>
                            <td data-label="Acciones">
                                <a href="historial.php?id_paciente=<?php echo $p['id_paciente']; ?>">Ver historial</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>