<?php
session_start();

if (isset($_SESSION['rol'])) {
    header('Location: vistas/index.php');
} else {
    header('Location: vistas/login.php');
}
exit;
?>