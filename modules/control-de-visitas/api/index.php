<?php session_start();

// config, funcions y clases del sistema
require_once('./../../../admin/config.php');
require_once('./../../../functions.php');
foreach ( glob( './../../' . CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename; }

// config del modulo
require_once('./../admin/setting.php');
// funciones del modulo
require_once('./../functions.php');
// incluimos el directorio de clases del modulo
foreach ( glob(  CV_CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

require_once('./api.php');