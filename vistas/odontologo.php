<?php $paginaActiva = 'odontologos'; ?>
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

        <h2>Consultar Agenda del Odontólogo</h2>

        <form action="../php/procesar/controladorOdontologo.php" method="GET" style="grid-template-columns: 1fr 1fr 1fr;">
            <div>
                <label for="id_odontologo">Odontólogo</label>
                <select id="id_odontologo" name="id_odontologo">
                    <option value="1">Dr. Carlos Mendoza</option>
                    <option value="2">Dra. Rosa Fernández</option>
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
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Carlos</td>
                    <td>Mendoza Ruiz</td>
                    <td>Ortodoncia</td>
                    <td>945612378</td>
                    <td>c.mendoza@clinicadental.com</td>
                </tr>
                <tr>
                    <td>Rosa</td>
                    <td>Fernández Luna</td>
                    <td>Odontología general</td>
                    <td>956123478</td>
                    <td>r.fernandez@clinicadental.com</td>
                </tr>
            </tbody>
        </table>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>