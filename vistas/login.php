<?php $paginaActiva = 'login'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="pagina-login">
    <?php include 'incluir/header.php'; ?>

    <div class="login-wrapper">
        <div class="login-card">
            <h2>Iniciar Sesión</h2>
            <p class="login-subtitulo">Ingresa tus credenciales para continuar</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="login-alerta">
                    <?php
                        echo $_GET['error'] === 'vacio'
                            ? 'Debes completar usuario y contraseña.'
                            : 'Usuario o contraseña incorrectos.';
                    ?>
                </div>
            <?php endif; ?>

            <form action="../php/procesar/procesar_login.php" method="POST" class="login-form">
                <div class="campo-login">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required autofocus>
                </div>
                <div class="campo-login">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn-ingresar">Ingresar</button>
            </form>
        </div>
    </div>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>