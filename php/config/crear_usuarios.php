<?php
/**
 * crear_usuarios.php
 * Crea los usuarios de prueba (uno por cada rol) con contraseña encriptada.
 * Ejecutar UNA SOLA VEZ, despues de haber importado base_datos.sql:
 *   - Desde consola: php crear_usuarios.php
 *   - Desde navegador: http://localhost/.../php/config/crear_usuarios.php
 * Solo inserta si la tabla "usuario" esta vacia (no duplica).
 */
require_once __DIR__ . '/conexion.php';

try {
    $con = new Conexion();
    $pdo = $con->conectar();

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM usuario");
    $existen = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existen['total'] > 0) {
        echo "Ya existen {$existen['total']} usuario(s) en la base de datos. No se insertaron duplicados.\n";
        exit;
    }

    $usuarios = [
        ['admin', 'admin123', 'Administrador'],
        ['odontologo1', 'odonto123', 'Odontologo'],
        ['recepcion', 'recepcion123', 'Recepcionista'],
    ];

    $insert = $pdo->prepare("INSERT INTO usuario (usuario, password, rol) VALUES (?, ?, ?)");
    foreach ($usuarios as $u) {
        $hash = password_hash($u[1], PASSWORD_DEFAULT);
        $insert->execute([$u[0], $hash, $u[2]]);
    }

    echo "Usuarios creados exitosamente:\n";
    echo "  - admin / admin123 (Administrador)\n";
    echo "  - odontologo1 / odonto123 (Odontologo)\n";
    echo "  - recepcion / recepcion123 (Recepcionista)\n";
    echo "\nIMPORTANTE: para que el rol 'Odontologo' vea su propia agenda, el usuario\n";
    echo "'odontologo1' debe coincidir con el correo registrado en la tabla 'odontologo'\n";
    echo "(se compara la parte antes del @). Ajusta esto en tus datos de prueba si hace falta.\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
