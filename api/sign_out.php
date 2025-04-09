<?php 
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php'); */

// recibe session_id y user_id a traves de la url
$jsonString = file_get_contents('php://input');    
$jsonObject = json_decode($jsonString);  

if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($jsonObject->user_id) )
{   
    // limpiamos los caracteres especiales
    $destroy_session    = cleanData($jsonObject->destroy_session);
    $user_id            = isset($jsonObject->user_id) ? cleanData($jsonObject->user_id) : "";    
    
    if ( $user_id == "")
    {
        http_response_code(200);
        $response = [
            'status'    => '200',
            'data'      => '2',
            'message'   => 'session expirada'
        ];        
    }

    $Conexion = new Library\Classes\Conexion;

    $conn = $Conexion->get($dbConfig);

    $Session = new Library\Classes\Session;

    // obtenemos la respuesta de la clase
    // devuelve falso si no se pudo destruir la session
    $result = $Session->destroy_session($conn,$destroy_session,$user_id);
    
    if ($result)
    {
        http_response_code(200);
        $response = [
            'status'    => '200',
            'data'      => '2',
            'message'   => 'session expirada'
        ];
    }
    else
    {
        http_response_code(409);
        $response = [
            'status'    => '409',            
            'message'   => 'Error. Contacte a su administrador.'
        ];
    }    
}
else
{
    $response = [
        'status'    => '400',        
        'message'   => 'Faltan parametros'
    ];  
}
header('Content-Type: application/json'); 
echo json_encode($response);
die();