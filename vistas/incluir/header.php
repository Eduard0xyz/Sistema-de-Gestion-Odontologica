<header>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <h1>Clínica Dental - Sistema de Gestión</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="../index.php" <?php echo ($paginaActiva == 'inicio') ? 'class="activo"' : ''; ?>>Inicio</a></li>
                <li><a href="pacientes.php" <?php echo ($paginaActiva == 'pacientes') ? 'class="activo"' : ''; ?>>Pacientes</a></li>
                <li><a href="odontologo.php" <?php echo ($paginaActiva == 'odontologos') ? 'class="activo"' : ''; ?>>Odontólogos</a></li>
                <li><a href="citas.php" <?php echo ($paginaActiva == 'citas') ? 'class="activo"' : ''; ?>>Citas</a></li>
                <li><a href="historial.php" <?php echo ($paginaActiva == 'historial') ? 'class="activo"' : ''; ?>>Historial Clínico</a></li>
                <li><a href="recordatorio.php" <?php echo ($paginaActiva == 'recordatorios') ? 'class="activo"' : ''; ?>>Recordatorios</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="login.php" class="btn-login">Iniciar Sesión</a>
            </div>
        </div>
    </nav>
</header>