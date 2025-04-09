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

if ( isset($jsonObject->session_token) )
{
    $array_token_and_user_id = [
        'session_token'         => cleanData($jsonObject->session_token),
        'user_id'               => cleanData($jsonObject->session_user_id),
    ];
    
    $arrayData = [
        'target'                => cleanData($jsonObject->target),
        'user_id'               => cleanData((int)$jsonObject->user_id),
        'role_id'               => cleanData((int)$jsonObject->user_role_id),
        'first_name'            => cleanData($jsonObject->first_name),
        'last_name'             => cleanData($jsonObject->last_name),
        'users_goverment_id'    => cleanData($jsonObject->users_goverment_id),
        'users_email'           => cleanData($jsonObject->users_email),
        'users_phone'           => cleanData($jsonObject->users_phone),
        'gender_id'             => cleanData((int)$jsonObject->gender_id),
        'birth_date'            => cleanData($jsonObject->birth_date)
    ];

    $target = cleanData($jsonObject->target);

}


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && $arrayData['target'] == 'user_profile' )
{

    // instanciamos la conexion.
    $Conexion = new Library\Classes\Conexion;

    // obtenemos la conexion.
    $conn = $Conexion->get($dbConfig);

    if($conn)
    {
        // instanciamos la session
        $Session = new Library\Classes\Session;

        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
        
        if($result)
        {
            $user_conected_id = $result[1];

            // verificamos que el perfil a actualizar sea del usuario conectado
            if( $user_conected_id == $arrayData['user_id'] )
            {
                // instanciamos la clase persona.
                $Person = new Library\Classes\Person;

                $result = $Person->update_user_details($conn,$arrayData);
                
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
                        'message'   => 'datos no actualizados.'
                    ];
                }
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
            http_response_code(401);
            $response = [
                'status'    => '401',
                'message'   => 'No tiene autorizacion.'
            ];
        }

    }
    else
    {
        http_response_code(503);
        $response = [
            'status'    => '503',
            'message'   => 'El servidor no esta disponible. contacte a su administrador.'
        ];  
    }
}
else
{    
    $response = [
        'status'    => '400',
        'message'   => 'Faltan Parametros.'
    ];  
}
header('Content-Type: application/json');
echo json_encode($response);
die();