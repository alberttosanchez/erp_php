<?php
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php');
require_once('./../library/class/class.person.php');
require_once('./../library/class/class.email.php'); */

// recibe un objeto json
$jsonString = file_get_contents('php://input'); 

// lo convertimos en una clase php 
$jsonObject = json_decode($jsonString);

$target     = isset($jsonObject->target) ? cleanData($jsonObject->target) : "";

if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST' && 
    $target == "active_message" && isset($jsonObject->user_email) 
)
{
    // extraemos los datos de la clase    
    $session_token   = cleanData($jsonObject->session_token);
    $session_user_id = cleanData($jsonObject->session_user_id);    
    $user_name       = cleanData($jsonObject->user_name);
    $user_email      = cleanData($jsonObject->user_email);

    // instanciamos la clase conexion
    $Conexion = new Library\Classes\Conexion;

    $conn = $Conexion->get($dbConfig);

    if($conn)
    {

        $array_token_and_user_id = [
            'session_token' => $session_token,
            'user_id'       => $session_user_id
        ];

        $Session = new Library\Classes\Session;

        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);

        if($result)
        {
            $Person = new Library\Classes\Person;
    
            // solicitamos los datos el token de seguridad actualizado y verificamos que el email enviado
            // este asociados a un usuario,
            // devuelve un array con los datos si es true,
            // de los contrario devuelve false  
            $result = $Person->get_security_token_by_email($conn,$user_email);
    
            if($result)
            {
                $arrayData = [
                    'emailToUser'   => $user_email,  # email
                    'aliasName'     => $user_name,  # string
                    'msgToUser'     => NOTIFICACION_TO_NEW_USER_TO_ACTIVATE_ACCOUNT_MESSAGE_FILE,
                    'where'         => 'active_account',
                    'token'         => $result['token']
                ];
        
                $Email = new Library\Classes\Email;
        
                $result = $Email->send($arrayData,$conn);
    
                if($result)
                {
                    http_response_code(200);
                    $result = [            
                        'status' => "200",
                        'message'=> "Mensaje Enviado."
                    ]; 
                }
                else
                {
                    http_response_code(409);
                    $result = [            
                        'status' => "409",
                        'message'=> "Error. Mensaje No enviado."
                    ];    
                }
            }
            else
            {
                http_response_code(401);
                $result = [            
                    'status' => "401",
                    'message'=> "Token invalido."
                ];
            }
        }
        else
        {
            http_response_code(401);
            $result = [            
                'status' => "401",
                'message'=> "Token Invalido."
            ];
        }

    }
    else
    {
        http_response_code(409);
        $result = [            
            'status' => "409",
            'message'=> "Error. Contacte al administrador de sistemas."
        ];
    }
}
else
{
    $result = [            
        'status' => "400",
        'message'=> "Faltan parametros."
    ];
}
header("Content-Type: application/json");
echo json_encode($result);
die();