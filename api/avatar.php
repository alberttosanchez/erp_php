<?php
    require_once './../admin/config.php';
    require_once './functions.php';

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename; }

    /* require_once './../library/class/class.conn.php';
    require_once './../library/class/class.session.php';    
    require_once './../library/class/class.person.php'; */

    $jsonString     = file_get_contents('php://input');
    $jsonObject     = json_decode($jsonString); 

    $target         = cleanData($jsonObject->target);    
    $session_token  = cleanData($jsonObject->session_token);
    $user_id        = cleanData($jsonObject->user_id);

if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST'   && 
    isset($target) && !empty($target)
   )
{
    //instanciamos la conexion
    $Conexion = new Library\Classes\Conexion;

    //obtenemos la conexion
    $conn = $Conexion->get($dbConfig);

    if($conn)
    {
        $array_token_and_user_id = [
            "session_token" => isset($session_token)    ? $session_token : null,
            "user_id"       => isset($user_id)          ? $user_id : null
        ];

        // instanciamos la clase session
        $Session = new Library\Classes\Session;
        
        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
        
        // si es verdadero entra.
        if($result)
        {   
            //instanciamos la clase persona
            $Person = new Library\Classes\Person;

            if ( $target == "get_avatar" )
            {
                // devuelve el nombre del archivo del avatar correspondiente al usuario
                $result = $Person->get_avatar_image($conn,$user_id);
    
                if($result)
                {
                    http_response_code(200);
                    $result = [
                        'status'    => "200",
                        "data"      => $result
                    ];
                }            
                else
                {
                    http_response_code(206);
                    $result = [
                        'status'    => '206',
                        'message'   => 'No tiene avatar.'
                    ];
                }
            }
            else if ( $target == "remove_avatar" )
            {
                // public/profile/users/
                $path = PROFILE_AVATAR_DIR_PATH.$user_id."/avatar"."/";
                        
                $image_path = $path;

                // eliminar el archivo del avatar 
                if( file_exists($image_path.$user_id.".png") )
                {
                    unlink($image_path.$user_id.".png");
                }
                else if ( file_exists($image_path.$user_id.".jpg") )
                {
                    unlink($image_path.$user_id.".jpg");
                }

                // devuelve el nombre del archivo del avatar correspondiente al usuario
                $result = $Person->remove_avatar_image($conn,$user_id);

                if($result)
                {
                    http_response_code(200);
                    $result = [
                        'status'    => "200",
                        "message"   => "Imagen removida."
                    ];
                }            
                else
                {
                    http_response_code(206);
                    $result = [
                        'status'    => '206',
                        'message'   => 'Imagen no removida.'
                    ];
                }
            }
        }
        else
        {
            http_response_code(401);
            $result = [
                'status'    => '401',
                'message'   => 'Token invalido.'
            ];
        }
    }
    else
    {
        http_response_code(409);
        $result = [
            "status"    => "409",
            "message"   => "Error. Contacte al administrador."
        ];
    }
} 
else
{
    $result = [
        "status"    => "400",
        "message"   => "Faltan parametros"
    ];
}
header("Content-Type:application/json");
echo json_encode($result);