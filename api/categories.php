<?php
    require_once './../admin/config.php';
    require_once './functions.php';

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}
    
    /* require_once './../library/class/class.conn.php';
    require_once './../library/class/class.session.php';
    require_once './../library/class/class.cat.php'; */

    $jsonString                 = file_get_contents('php://input');
    $jsonObject                 = json_decode($jsonString); 

    $jsonObject->target         = cleanData($jsonObject->target);    
    $jsonObject->session_token  = cleanData($jsonObject->session_token);
    $jsonObject->user_id        = cleanData($jsonObject->user_id);
    
    $target         = $jsonObject->target;
    $session_token  = $jsonObject->session_token;
    $user_id        = $jsonObject->user_id;

if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST'   && 
    isset($target) && !empty($target)
   )
{
    // instanciamos la conexion
    $Conexion = new Library\Classes\Conexion;

    // obtenemos la conexion
    $conn = $Conexion->get($dbConfig);

    // comprobamos la conexion
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
            // instanciamos las categorias
            $Categories = new Library\Classes\Categories;
            
            if ( $target == 'gender' )
            {
                $result = $Categories->get_gender($conn);
            }
            else if ( $target == 'query_filters' )
            {
                $result = $Categories->get_details_filters($conn);
            }
            else if ( $target == 'role' )
            {
                $result = $Categories->get_role($conn);
            }
    
            if ($result)
            {
                http_response_code(200);

                $result = [
                    'status'    => '200',
                    'data'      => $result,
                ];
            }
            else
            {
                http_response_code(409);

                $result = [
                    'status'    => '409',
                    'message'   => 'Ref. SV-CAT-0001 - Categorias no obtenidas.'
                ];
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
    // si no hay conexion devuelve un mensaje
    else
    {
        http_response_code(409);

        $result = [
            'status'    => '409',
            'message'   => 'Fallo de conexion. Contacte al administrador de sistemas.'
        ];        
    }

    header('Content-Type: application/json');
    $result = json_encode($result);
    echo $result;
}
die();