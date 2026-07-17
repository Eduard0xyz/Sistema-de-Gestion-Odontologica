<?php
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
   
    echo "=== Envío de recordatorios (" . date('Y-m-d H:i:s') . ") ===" . PHP_EOL;
    echo "Enviados: {$resumen['enviados']} | Fallidos: {$resumen['fallidos']} | Omitidos: {$resumen['omitidos']}" . PHP_EOL;
    foreach ($resumen['detalle'] as $linea) {
        echo " - {$linea}" . PHP_EOL;
    }
    exit($resumen['fallidos'] > 0 ? 1 : 0);
}

session_start();
$_SESSION['resumen_recordatorios'] = $resumen;
header('Location: ../../vistas/recordatorio.php?mensaje=ejecutado');
exit;
