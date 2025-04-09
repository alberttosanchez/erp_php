<?php 
    require_once('./../admin/config.php');
    require_once('./functions.php');

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}


    /* require_once('./../library/class/class.conn.php');
    require_once('./../library/class/class.session.php');
    require_once('./../library/class/class.person.php'); */

    // recibe session_id y user_id a traves de la url
    $jsonString = file_get_contents('php://input');
    $jsonObject = json_decode($jsonString);  

    // limpiamos los caracteres especiales    
    $target        = isset($jsonObject->target)       ? cleanData($jsonObject->target)        : "";
    $session_token = isset($jsonObject->session_token)? cleanData($jsonObject->session_token) : "";    
    $user_id       = isset($jsonObject->user_id)      ? cleanData($jsonObject->user_id)       : "";

if( 
    $_SERVER["REQUEST_METHOD"] == "POST" && 
    isset($session_token) && !empty($session_token) && 
    isset($user_id) && !empty($user_id) && $target == "url")
{   
    
    // verifico que los datos obtenidos tenga el formato esperado
    if ( $user_id == "" or strlen($user_id) < 1 or strlen($session_token) !== 9 )
    {   
        http_response_code(401);
        $result = [
            'status'    => '401',
            'message'   => 'Token invalido'
        ];
    }    
    else
    {           
        $array_token_and_user_id = [
            "session_token" => $session_token,
            "user_id"       => $user_id
        ];

        // instanciamos la conexion
        $Conn = new Library\Classes\Conexion;        
        
        // obtenemos la conexion
        $conn = $Conn->get($dbConfig);
        
        if ($conn)
        {
            // instanciamos la session
            $Session = new Library\Classes\Session;

            // veficamos el token y el user id en la base de datos
            // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
            // si no es falso
            $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
            
            if($result)
            {
                if (count($result) == 2)
                {   
                    $Person = new Library\Classes\Person;
                    
                    // solicitamos los datos session a travez de un array que contiene
                    // el token y el id de usuario validos 
                    // devuelve un array con los datos si es true
                    // de lo contrario devuelve false
                    $result = $Person->get_session_info($conn,$result);
                                    
                    if ($result)
                    {
                        http_response_code(200);                
                    }
                    else
                    {
                        http_response_code(401);
                        $result = [
                            'status'    => '401',
                            'message'   => 'Session invalida.'
                        ];
                    }    
                }
                else
                {
                    http_response_code(401);
                    $result = [
                        'status'    => '401',
                        'message'   => 'Session invalida.'
                    ];
                }
            }
            else
            {
                http_response_code(401);
                $result = [
                    'status'    => '401',
                    'message'   => 'Token invalido'
                ];
            }
        }
        else
        {
            http_response_code(409);
            $result = [
                'status'    => "409",
                'message'   => "Faltan parametros."
            ];
        }
    }
}
else
{    
    http_response_code(400);
    $result = [
        'status'    => "400",
        'message'   => "Faltan parametros."
    ];
}
header("Content-Type: application/json");
echo json_encode($result);
die();