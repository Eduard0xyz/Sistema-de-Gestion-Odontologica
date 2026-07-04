<?php $paginaActiva = 'login'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main style="max-width:420px;">
        <h2>Iniciar Sesión</h2>
        <form action="../php/procesar/procesar_login.php" method="POST" style="grid-template-columns:1fr;">
            <div>
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit">Ingresar</button>
        </form>
    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>
