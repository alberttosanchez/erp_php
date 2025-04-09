<?php 
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename; }

/* 
    require_once('./../library/class/class.conn.php');
    require_once('./../library/class/class.person.php'); 
*/

// recibe session_id y user_id a traves de la url
$jsonString = file_get_contents('php://input');
$jsonObject = json_decode($jsonString);

if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($jsonObject->user_email) )
{   
    // limpiamos los caracteres especiales
    $security_token = cleanData($jsonObject->security_token);
    $user_email     = cleanData($jsonObject->user_email);
    
    // si las variables son nulas la ceteamos en cadena vacia.
    $security_token = isset($jsonObject->security_token) ? $jsonObject->security_token  : "";
    $user_email     = isset($jsonObject->user_email)     ? $jsonObject->user_email      : "";

    // instanciamos la clase conexion
    $Conexion = new Library\Classes\Conexion;

    // ver config.php para $dbConfig
    $conn = $Conexion->get($dbConfig);
    
    if( $conn )
    {
        // instanciamos la clase persona
        $Person = new Library\Classes\Person;
                
        $result = $Person->validate_security_token_by_email($conn,$security_token,$user_email);
        
        if($result)
        {
            $result = $Person->confirm_account($conn,$user_email);
        
            if ($result)
            {
                http_response_code(200);
                $result = [
                    "status"    => "200",
                    "message"   => "Cuenta confirmada."
                ];
            }
            else
            {
                http_response_code(409);
                $result = [
                    "status"    => "409",
                    "message"   => "Cuenta No confirmada."
                ];
            }
        }
        else
        {
            http_response_code(401);
            $result = [
                "status"    => "401",
                "message"   => "Datos invalidos."
            ];
        }
    
    }
    else
    {
        http_response_code(503);
        $result = [
            "status"    => "503",
            "message"   => "Error al conectar con el servidor. Contacte a su administrador."
        ];
    }

}
else
{
    $result = [
        "status"    => "400",
        "message"   => "Faltan parametros."
    ];
}
header('Content-Type: application/json');    
echo json_encode($result);
die();