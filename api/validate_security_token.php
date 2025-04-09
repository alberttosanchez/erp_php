<?php 
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.person.php'); */

if( $_SERVER["REQUEST_METHOD"] == "POST" )
{    
    // recibe session_id y user_id a traves de la url
    $jsonString = file_get_contents('php://input');    
    $jsonObject = json_decode($jsonString);  
    
    // limpiamos los caracteres especiales
    $security_token     = cleanData($jsonObject->security_token);
    $user_email         = cleanData($jsonObject->user_email);
    
    // si las vaibles son nulas la seteamos en vacio.
    $security_token     = isset($jsonObject->security_token) ? $jsonObject->security_token : "";
    $user_email         = isset($jsonObject->user_email) ? $jsonObject->user_email : "";

    // instacioamos la clase conexion
    $Conexion = new Library\Classes\Conexion;

    // ver config.php para $dbConfig
    $conn = $Conexion->get($dbConfig);

    // devuelve un objeto vacio si la variable es false;
    is_result_false($conn);

    // instacioamos la clase persona
    $Person = new Library\Classes\Person;

    $result = $Person->validate_security_token_by_email($conn,$security_token,$user_email);

    is_result_false($result);

    header('Content-Type: application/json');
    
    $result = array(
        'status' => "200",
        'message' => "token valido."
    );
    echo json_encode($result);
    die();
}
die();