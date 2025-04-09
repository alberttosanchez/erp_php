<?php

require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php');
require_once('./../library/class/class.person.php'); */

$jsonString = file_get_contents('php://input');
$jsonObject = json_decode($jsonString);

if ( isset($jsonObject->user_id) )
{    
    $array_with_user_id = [
        'user_id' => cleanData($jsonObject->user_id)    
    ];
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($array_with_user_id) )
{

    // instanciamos la conexion.
    $Conexion = new Library\Classes\Conexion;

    // obtenemos la conexion.
    $conn = $Conexion->get($dbConfig);

    if($conn)
    {
        
            // instanciamos la clase persona.
            $Person = new Library\Classes\Person;

            // verifica si el usuario que ejecuta la accion es administrador o soporte devuelve
            // true de lo contrario false
            $result = $Person->is_user_an_admin_or_support($conn,$array_with_user_id['user_id']);

            if($result)
            {
            
                http_response_code(200);
                $response = [
                    'status'    => '200',
                    'message'   => 'Los datos fueron actualizados.'
                ];
                
            }
            else
            {
                http_response_code(401);
                $response = [
                    'status'    => '401',
                    'message'   => 'No tiene autorizacion.'
                ];
            }

    }
    else
    {
        http_response_code(400);
        $response = [
            'status'    => '400',
            'message'   => 'El servidor no esta disponible. contacte a su administrador.'
        ];  
    }
    
}
else
{
    http_response_code(204);
    $response = [
        'status'    => '204',
        'message'   => 'Faltan Parametros.'
    ]; 
}
header('Content-Type: application/json');
$response = json_encode($response);
echo $response;
die();