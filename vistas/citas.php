<?php $paginaActiva = 'citas'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas - Sistema de Gestión Odontológica</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/citas.css">
</head>
<body>
    <?php include 'incluir/header.php'; ?>

    <main class="pagina-citas">
        
        <!-- SECCIÓN: AGENDAR -->
        <section class="seccion-cita">
            <h2>Agendar Cita</h2>
            <div class="linea-decorativa"></div>
            
            <form class="form-cita" action="../php/procesar/procesar_cita.php" method="POST">
                <div class="grupo-campo">
                    <label for="id_paciente">Paciente</label>
                    <select id="id_paciente" name="id_paciente">
                        <option value="1">Ana Rojas Pérez</option>
                        <option value="2">Luis Torres Vega</option>
                    </select>
                </div>
                
                <div class="grupo-campo">
                    <label for="id_odontologo">Odontólogo</label>
                    <select id="id_odontologo" name="id_odontologo">
                        <option value="1">Dr. Carlos Mendoza</option>
                        <option value="2">Dra. Rosa Fernández</option>
                    </select>
                </div>
                
                <div class="grupo-campo">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                
                <div class="grupo-campo">
                    <label for="hora">Hora</label>
                    <input type="time" id="hora" name="hora" required>
                </div>
                
                <div class="grupo-campo campo-ancho">
                    <label for="motivo">Motivo de la cita</label>
                    <textarea id="motivo" name="motivo" rows="3" placeholder="Ej. Control, limpieza, dolor dental..."></textarea>
                </div>
                
                <div class="grupo-campo campo-boton">
                    <button type="submit" class="btn-agendar">
                        <span>📅</span> Agendar Cita
                    </button>
                </div>
            </form>
        </section>

        <!-- SECCIÓN: REPROGRAMAR / CANCELAR -->
        <section class="seccion-cita seccion-gestion">
            <h2>Reprogramar / Cancelar Cita</h2>
            <div class="linea-decorativa"></div>
            
            <form class="form-gestion" action="../php/procesar/procesar_cita.php" method="POST">
                <div class="grupo-campo">
                    <label for="id_cita">N° de Cita</label>
                    <input type="text" id="id_cita" name="id_cita" placeholder="ID de la cita">
                </div>
                
                <div class="grupo-campo">
                    <label for="nueva_fecha">Nueva fecha</label>
                    <input type="date" id="nueva_fecha" name="nueva_fecha">
                </div>
                
                <div class="grupo-campo">
                    <label for="nueva_hora">Nueva hora</label>
                    <input type="time" id="nueva_hora" name="nueva_hora">
                </div>
                
                <div class="grupo-campo campo-boton">
                    <button type="submit" name="accion" value="reprogramar" class="btn-reprogramar">
                        <span>🔄</span> Reprogramar
                    </button>
                </div>
                
                <div class="grupo-campo campo-boton">
                    <button type="submit" name="accion" value="cancelar" class="btn-cancelar">
                        <span>✕</span> Cancelar Cita
                    </button>
                </div>
            </form>
        </section>

        <!-- SECCIÓN: LISTA -->
        <section class="seccion-cita">
            <h2>Lista de Citas</h2>
            <div class="linea-decorativa"></div>
            
            <div class="contenedor-tabla">
                <table class="tabla-citas">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Paciente</th>
                            <th>Odontólogo</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="num-cita">1</td>
                            <td>Ana Rojas Pérez</td>
                            <td>Dr. Carlos Mendoza</td>
                            <td>10/07/2026</td>
                            <td>10:00 a.m.</td>
                            <td><span class="estado programada">Programada</span></td>
                        </tr>
                        <tr>
                            <td class="num-cita">2</td>
                            <td>Luis Torres Vega</td>
                            <td>Dra. Rosa Fernández</td>
                            <td>11/07/2026</td>
                            <td>03:30 p.m.</td>
                            <td><span class="estado reprogramada">Reprogramada</span></td>
                        </tr>
                        <tr>
                            <td class="num-cita">3</td>
                            <td>Ana Rojas Pérez</td>
                            <td>Dra. Rosa Fernández</td>
                            <td>05/07/2026</td>
                            <td>09:00 a.m.</td>
                            <td><span class="estado cancelada">Cancelada</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <?php include 'incluir/footer.php'; ?>
</body>
</html>