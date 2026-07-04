<?php $paginaActiva = 'index'; ?>
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
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>
