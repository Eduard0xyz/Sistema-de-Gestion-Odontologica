<?php $paginaActiva = 'historial'; ?>
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
        <h2>Registrar Atención en Historial Clínico</h2>
        <!-- Formulario que envía datos a HistorialClinico::registrarHistorial() -->
        <form action="../php/procesar/procesar_historial.php" method="POST">
            <div>
                <label for="id_paciente">Paciente</label>
                <select id="id_paciente" name="id_paciente">
                    <option value="1">Ana Rojas Pérez</option>
                    <option value="2">Luis Torres Vega</option>
                </select>
            </div>
            <div>
                <label for="id_odontologo">Odontólogo</label>
                <select id="id_odontologo" name="id_odontologo">
                    <option value="1">Dr. Carlos Mendoza</option>
                    <option value="2">Dra. Rosa Fernández</option>
                </select>
            </div>
            <div>
                <label for="fecha_atencion">Fecha de atención</label>
                <input type="date" id="fecha_atencion" name="fecha_atencion">
            </div>
            <div>
                <label for="tratamiento">Tratamiento</label>
                <input type="text" id="tratamiento" name="tratamiento" placeholder="Ej. Limpieza dental, extracción...">
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
        <!-- Formulario que envía datos a HistorialClinico::consultarHistorialPorPaciente() -->
        <form action="../php/procesar/procesar_historial.php" method="GET" style="grid-template-columns: 3fr 1fr;">
            <div>
                <label for="buscar_paciente">Paciente</label>
                <select id="buscar_paciente" name="id_paciente">
                    <option value="1">Ana Rojas Pérez</option>
                    <option value="2">Luis Torres Vega</option>
                </select>
            </div>
            <button type="submit" style="grid-column:auto; align-self:end;">Ver Historial</button>
        </form>

        <h2>Historial Clínico - Ana Rojas Pérez</h2>
        <!-- Tabla alimentada por HistorialClinico::consultarHistorialPorPaciente() -->
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Odontólogo</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>15/05/2026</td>
                    <td>Dr. Carlos Mendoza</td>
                    <td>Caries en pieza 26</td>
                    <td>Resina compuesta</td>
                    <td>Paciente indica sensibilidad leve</td>
                </tr>
                <tr>
                    <td>02/06/2026</td>
                    <td>Dra. Rosa Fernández</td>
                    <td>Control de rutina</td>
                    <td>Limpieza dental</td>
                    <td>Sin observaciones</td>
                </tr>
            </tbody>
        </table>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>