<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($paginaActiva)) {
    $paginaActiva = '';
}
$rol = $_SESSION['rol'] ?? '';
$usuarioSesion = $_SESSION['usuario'] ?? '';
?>
<header>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <h1>Clínica Dental - Sistema de Gestión</h1>
            </div>

            <?php if ($rol === 'Odontologo'): ?>
                <ul class="nav-menu">
                    <li><a href="index.php" <?php echo ($paginaActiva == 'index') ? 'class="activo"' : ''; ?>>Mi Agenda</a></li>
                    <li><a href="historial.php" <?php echo ($paginaActiva == 'historial') ? 'class="activo"' : ''; ?>>Historial Clínico</a></li>
                </ul>
            <?php elseif ($rol === 'Administrador' || $rol === 'Recepcionista'): ?>
                <ul class="nav-menu">
                    <li><a href="index.php" <?php echo ($paginaActiva == 'index') ? 'class="activo"' : ''; ?>>Dashboard</a></li>
                    <li><a href="pacientes.php" <?php echo ($paginaActiva == 'pacientes') ? 'class="activo"' : ''; ?>>Pacientes</a></li>
                    <li><a href="odontologo.php" <?php echo ($paginaActiva == 'odontologos') ? 'class="activo"' : ''; ?>>Odontólogos</a></li>
                    <li><a href="citas.php" <?php echo ($paginaActiva == 'citas') ? 'class="activo"' : ''; ?>>Citas</a></li>
                    <li><a href="historial.php" <?php echo ($paginaActiva == 'historial') ? 'class="activo"' : ''; ?>>Historial Clínico</a></li>
                    <li><a href="recordatorio.php" <?php echo ($paginaActiva == 'recordatorios') ? 'class="activo"' : ''; ?>>Recordatorios</a></li>
                </ul>
            <?php else: ?>
                <ul class="nav-menu">
                    <li><a href="index.php" <?php echo ($paginaActiva == 'index') ? 'class="activo"' : ''; ?>>Inicio</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>

    <div class="auth-buttons">
        <?php if ($rol === 'Odontologo'): ?>
            <span class="usuario-activo">Odontólogo: <?php echo htmlspecialchars($usuarioSesion); ?></span>
            <a href="../php/procesar/logout.php" class="btn-login">Cerrar Sesión</a>
        <?php elseif ($rol === 'Administrador' || $rol === 'Recepcionista'): ?>
            <span class="usuario-activo"><?php echo htmlspecialchars($rol); ?>: <?php echo htmlspecialchars($usuarioSesion); ?></span>
            <a href="../php/procesar/logout.php" class="btn-login">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</header>