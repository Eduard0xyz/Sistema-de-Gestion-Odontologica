<?php
if (!isset($paginaActiva)) {
    $paginaActiva = '';
}

function claseActiva($nombre, $paginaActiva) {
    return $nombre === $paginaActiva ? 'class="activo"' : '';
}
?>
<header>
    <h1> Clínica Dental - Sistema de Gestión</h1>
    <nav>
        <ul>
            <li><a href="index.php" <?= claseActiva('index', $paginaActiva) ?>>Inicio</a></li>
            <li><a href="pacientes.php" <?= claseActiva('pacientes', $paginaActiva) ?>>Pacientes</a></li>
            <li><a href="odontologos.php" <?= claseActiva('odontologos', $paginaActiva) ?>>Odontólogos</a></li>
            <li><a href="citas.php" <?= claseActiva('citas', $paginaActiva) ?>>Citas</a></li>
            <li><a href="historial.php" <?= claseActiva('historial', $paginaActiva) ?>>Historial Clínico</a></li>
            <li><a href="login.php" <?= claseActiva('login', $paginaActiva) ?>>Iniciar Sesión</a></li>
        </ul>
    </nav>
</header>