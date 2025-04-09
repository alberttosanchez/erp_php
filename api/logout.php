<?php 
require_once('./config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename ){ include_once $filename;}

/* require_once('./class/class.conn.php');
require_once('./class/class.session.php'); */

if( $_SERVER["REQUEST_METHOD"] == "POST")
{    
    // recibe session_id y user_id a traves de la url
    $jsonString = file_get_contents('php://input');    
    $jsonObject = json_decode($jsonString);  
    
    // limpiamos los caracteres especiales
    $destroy_session = cleanData($jsonObject->destroy_session);
    $user_id = isset($jsonObject->user_id) ? $jsonObject->user_id : "";
    $user_id = cleanData($user_id);    
    
    $Conexion = new Library\Classes\Conexion;

    $conn = $Conexion->get($dbConfig);

    $Session = new Library\Classes\Session;

    // obtenemos la respuesta de la clase
    // devuelve falso si no se pudo destruir la session
    $result = $Session->destroy_session($conn,$destroy_session,$user_id);
    
    if ($result != false)
    {
        //header('Content-Type: application/json'); 
        echo $result;
    }
    /* else
    {
        echo "no";
    } */
}