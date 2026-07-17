<?php
/**
 * Datos de la cuenta de correo que envia los recordatorios.
 *
 * Esta version NO usa Composer ni PHPMailer: el envio lo hace un cliente
 * SMTP propio, escrito en PHP puro (php/clases/EnviadorSMTP.php), que se
 * conecta directo al servidor de correo mediante una conexion de red.
 * No hay nada que instalar ni configurar en el servidor (nada de Sendmail
 * para Windows, nada de tocar php.ini).
 *
 * Lo unico que SI es indispensable, con cualquier tecnologia, es una
 * cuenta de correo real que autorice el envio (alguien tiene que "firmar"
 * el mensaje). Con Gmail son 2 pasos, sin instalar nada:
 *
 *   1. Activa la verificacion en 2 pasos en tu cuenta de Gmail:
 *      https://myaccount.google.com/security
 *   2. Genera una "contrasena de aplicacion" en:
 *      https://myaccount.google.com/apppasswords
 *      (tu contrasena normal de Gmail NO funciona aqui)
 *
 * Pega esos datos abajo y ya puedes enviar recordatorios reales.
 * Si prefieres usar el correo de la universidad, Outlook u otro proveedor,
 * solo cambia SMTP_HOST y SMTP_PUERTO por los de ese proveedor.
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PUERTO', 587);
define('SMTP_USUARIO', 'tu_correo@gmail.com');
define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx');
define('SMTP_NOMBRE_REMITENTE', 'Clinica Dental');