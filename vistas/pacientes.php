<?php $paginaActiva = 'pacientes'; ?>
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
        <h2>Buscar Paciente</h2>
        <form action="../php/procesar/procesar_paciente.php" method="GET" style="grid-template-columns: 3fr 1fr;">
            <div>
                <label for="criterio">Buscar por DNI o Apellido</label>
                <input type="text" id="criterio" name="criterio" placeholder="Ingrese DNI o apellido">
            </div>
            <button type="submit" style="grid-column:auto; align-self:end;">Buscar</button>
        </form>

        <h2>Registrar Paciente</h2>
        <form action="../php/procesar/procesar_paciente.php" method="POST">
            <div>
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" maxlength="8" placeholder="Ej. 71234567" required>
            </div>
            <div>
                <label for="nombres">Nombres</label>
                <input type="text" id="nombres" name="nombres" placeholder="Nombres del paciente" required>
            </div>
            <div>
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos del paciente" required>
            </div>
            <div>
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
            </div>
            <div>
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej. 987654321">
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
                <tr>
                    <td>71234567</td>
                    <td>Piero</td>
                    <td>Avelino Martin</td>
                    <td>987654321</td>
                    <td>piero.avelino@correo.com</td>
                    <td><a href="historial.php">Ver historial</a></td>
                </tr>
                <tr>
                    <td>76543210</td>
                    <td>Chichiko</td>
                    <td>Velde</td>
                    <td>912345678</td>
                    <td>chichiko.velde@correo.com</td>
                    <td><a href="historial.php">Ver historial</a></td>
                </tr>
            </tbody>
        </table>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>