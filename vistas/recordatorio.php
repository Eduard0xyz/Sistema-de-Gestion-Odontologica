<?php
session_start();
$paginaActiva = 'recordatorios';

require_once __DIR__ . '/../php/clases/recordatorio.php';

$recordatorioObj = new Recordatorio();
$historial = $recordatorioObj->listarHistorial(50);

$resumen = null;
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'ejecutado' && isset($_SESSION['resumen_recordatorios'])) {
    $resumen = $_SESSION['resumen_recordatorios'];
    unset($_SESSION['resumen_recordatorios']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recordatorios - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/recordatorio.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main class="pagina-recordatorios">

      
        <section class="seccion-recordatorio">
            <h2>Recordatorios Automáticos de Citas</h2>
            <div class="linea-decorativa"></div>

            <form class="form-recordatorio" action="../php/procesar/procesar_recordatorio.php" method="POST">
                <div class="grupo-campo">
                    <label for="dias_antes">Enviar a pacientes con cita en</label>
                    <select id="dias_antes" name="dias_antes">
                        <option value="1" selected>Mañana (1 día antes)</option>
                        <option value="2">2 días antes</option>
                        <option value="3">3 días antes</option>
                    </select>
                </div>
                <div class="grupo-campo">
                    <button type="submit" class="btn-enviar-recordatorios">
                        <span></span> Enviar recordatorios 
                    </button>
                </div>
            </form>
        </section>

     
        <?php if ($resumen !== null): ?>
        <section class="seccion-recordatorio">
            <h2>Resultado del último envío</h2>
            <div class="linea-decorativa"></div>

            <div class="resumen-envio">
                <div class="tarjeta-resumen enviados">
                    <span class="numero"><?php echo (int) $resumen['enviados']; ?></span>
                    Enviados
                </div>
                <div class="tarjeta-resumen fallidos">
                    <span class="numero"><?php echo (int) $resumen['fallidos']; ?></span>
                    Fallidos
                </div>
                <div class="tarjeta-resumen omitidos">
                    <span class="numero"><?php echo (int) $resumen['omitidos']; ?></span>
                    Omitidos
                </div>
            </div>

            <?php if (!empty($resumen['detalle'])): ?>
                <ul class="detalle-envio">
                    <?php foreach ($resumen['detalle'] as $linea): ?>
                        <li><?php echo htmlspecialchars($linea); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
        <?php endif; ?>

       
        <section class="seccion-recordatorio">
            <h2>Historial de Recordatorios</h2>
            <div class="linea-decorativa"></div>

            <div class="contenedor-tabla">
                <table class="tabla-recordatorios">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Correo</th>
                            <th>Cita</th>
                            <th>Medio</th>
                            <th>Enviado el</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historial)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:#78909c;">Todavía no se ha enviado ningún recordatorio.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historial as $r): ?>
                                <?php
                                $estadoClase = strtolower($r['estado']); // enviado | fallido | pendiente
                                $fechaCitaFmt = date('d/m/Y', strtotime($r['fecha_cita'])) . ' ' . date('h:i A', strtotime($r['hora_cita']));
                                $fechaEnvioFmt = date('d/m/Y h:i A', strtotime($r['fecha_envio']));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['paciente_nombres'] . ' ' . $r['paciente_apellidos']); ?></td>
                                    <td><?php echo htmlspecialchars($r['correo']); ?></td>
                                    <td><?php echo $fechaCitaFmt; ?></td>
                                    <td><?php echo htmlspecialchars($r['medio']); ?></td>
                                    <td><?php echo $fechaEnvioFmt; ?></td>
                                    <td><span class="estado-recordatorio <?php echo $estadoClase; ?>"><?php echo htmlspecialchars($r['estado']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>
