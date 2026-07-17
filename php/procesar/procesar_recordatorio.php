<?php
/**
 * Ejecuta el envío de recordatorios de citas próximas.
 *
 * Este script sirve para dos casos, sin instalar nada adicional:
 *
 * 1) Desde la web: la pantalla vistas/recordatorio.php envía un POST
 *    aquí (botón "Enviar recordatorios ahora") y luego se redirige de
 *    vuelta mostrando el resumen del envío.
 *
 * 2) Desde consola / tarea programada (cron en Linux o el Programador
 *    de tareas de Windows), para que los recordatorios salgan solos
 *    todos los días sin que nadie tenga que entrar a la web:
 *
 *       php /ruta/al/proyecto/php/procesar/procesar_recordatorio.php
 *
 *    Solo requiere tener el PHP CLI instalado (php -v), nada de
 *    Composer ni extensiones extra.
 */

require_once __DIR__ . '/../clases/recordatorio.php';

$esCli = (php_sapi_name() === 'cli');

$diasAntes = 1;
if ($esCli && isset($argv[1])) {
    $diasAntes = intval($argv[1]);
} elseif (!$esCli && isset($_POST['dias_antes'])) {
    $diasAntes = intval($_POST['dias_antes']);
}
if ($diasAntes <= 0) {
    $diasAntes = 1;
}

$recordatorioObj = new Recordatorio();
$resumen = $recordatorioObj->enviarRecordatorios($diasAntes);

if ($esCli) {
    // Salida legible por consola / log de la tarea programada
    echo "=== Envío de recordatorios (" . date('Y-m-d H:i:s') . ") ===" . PHP_EOL;
    echo "Enviados: {$resumen['enviados']} | Fallidos: {$resumen['fallidos']} | Omitidos: {$resumen['omitidos']}" . PHP_EOL;
    foreach ($resumen['detalle'] as $linea) {
        echo " - {$linea}" . PHP_EOL;
    }
    exit($resumen['fallidos'] > 0 ? 1 : 0);
}

// Caso web: guardamos el resumen en sesión y redirigimos (patrón
// Post/Redirect/Get para no reenviar el formulario al refrescar).
session_start();
$_SESSION['resumen_recordatorios'] = $resumen;
header('Location: ../../vistas/recordatorio.php?mensaje=ejecutado');
exit;
