<?php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PUERTO', 587);
define('SMTP_USUARIO', 'clinicahuanuco642@gmail.com');
define('SMTP_PASSWORD', 'orwd zppq tekr adhu');
define('SMTP_NOMBRE_REMITENTE', 'Clinica Dental CURASI');

define(
    'SMTP_CONFIGURADO',
    SMTP_USUARIO !== 'gmail de la clinica'
    && SMTP_PASSWORD !== 'contraseña de aplicacion'
    && !empty(SMTP_USUARIO)
    && !empty(SMTP_PASSWORD)
);
