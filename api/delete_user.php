<?php 
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php');
require_once('./../library/class/class.person.php');
require_once('./../library/class/class.files.php'); */

// recibe session_id y user_id a traves de la url
$jsonString = file_get_contents('php://input');    
$jsonObject = json_decode($jsonString);  

if ( isset($jsonObject->session_token) )
{
    // limpiamos los caracteres especiales
    $session_token      = cleanData($jsonObject->session_token);
    $user_id           = cleanData($jsonObject->user_id);
    $user_to_delete_id  = cleanData($jsonObject->user_to_delete_id);
}

if( 
    $_SERVER["REQUEST_METHOD"] == "POST" && !empty($session_token) &&
    !empty($user_id) && !empty($user_to_delete_id)
    )
{
    // instanciamos la conexion
    $Conexion = new Library\Classes\Conexion;
    
    // obtenemos la conexion -> ver config.php
    $conn = $Conexion->get($dbConfig);
    
    if($conn)
    {
        $array_token_and_user_id = [
            'session_token' => $session_token,
            'user_id'       => $user_id
        ];

        $Session = new Library\Classes\Session;

        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);

        if($result)
        {

            // instanciamos la clase Person
            $Person = new Library\Classes\Person;
            
            // verifica si el usuario que ejecuta la accion es administrador o soporte
            // devuelve true de lo contrario false
            $result = $Person->is_user_an_admin_or_support($conn,$user_id);

            if ($result)
            {
                // verifica si el usuario a eliminar es el mismo que hace la peticion
                // devuelve true de lo contrario false
                $result = $Person->is_user_the_same_user_to_delete($conn,$user_to_delete_id,$user_id);
                
                if ($result == false)
                {
                    $result = $Person->delete_user_info_from_id($conn,$user_to_delete_id);
                    
                    if($result)
                    {
                        
                        // instanciamos la clase Files
                        $Files = new Library\Classes\Files;

                        // ruta directorio que contiene la imagen
                        $image_path = PROFILE_AVATAR_DIR_PATH.$user_to_delete_id."/avatar"."/";
                        
                        // elimina el archivo de imagen actual
                        // si recibe la extension del nuevo archivo elimina la extensiones contrarias
                        // si no recibe la extension eliminar todas las extensiones encontradas
                        // devuelte true al eliminar, de lo contrario false
                        $result = $Files->delete_current_image_file($user_to_delete_id,$image_path);
                        
                        if ($result)
                        {
                            http_response_code(200);            
                            $result = [
                                'status'    => "200",
                                'message'   => "Usuario Eliminado."
                            ];
                        }
                        else
                        {
                            http_response_code(409);            
                            $result = [
                                'status'    => "409",
                                'message'   => "Error. Contactar al Administrador."
                            ];
                        }

                    }
                    else
                    {
                        http_response_code(409);            
                        $result = [
                            'status'    => "409",
                            'message'   => "Error. Contacte a su Administrador."
                        ];
                    }
                }
                else
                {
                    http_response_code(406);
                    $result = [
                        'status'    => "406",
                        'message'   => "Error. No puede eliminarse asi mismo."
                    ];
                }

            }
            else
            {
                http_response_code(401);
                $result = [
                    'status'    => "401",
                    'message'   => "No tiene autorizacion para esta accion."
                ];
            }

        }
        else
        {
            http_response_code(401);            
            $result = [
                'status'    => "401",
                'message'   => "No tiene autorizacion o token inactivo. - Unauthorized"
            ];
        }
    }
    else
    {
        http_response_code(503);
        $result = [
            'status'    => "503",
            'message'   => "Contacte a su administrador. - Service Unavailable"
        ];
    }
}
else
{    
    $result = [
        'status'    => "400",
        'message'   => "Faltan parametros"
    ];
}
header('Content-Type: application/json');    
echo json_encode($result);
die();