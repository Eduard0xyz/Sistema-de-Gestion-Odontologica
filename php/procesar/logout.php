<?php
/**
 * logout.php
 * Cierra la sesión del usuario y redirige al login.
 */
session_start();
session_destroy();
header('Location: ../../vistas/login.php');
exit;
