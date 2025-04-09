<?php
require_once('./../admin/config.php');
require_once('./../functions.php');

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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($jsonObject->users_email) ) //
{
    // extraemos los datos de la clase a un array
    $array_user_data = array(
        'users_name'        => strtolower(cleanData($jsonObject->users_name)),
        'first_name'        => strtoupper(cleanData($jsonObject->first_name)),
        'last_name'         => strtoupper(cleanData($jsonObject->last_name)),
        'users_goverment_id'=> cleanData($jsonObject->users_goverment_id),
        'gender'            => cleanData($jsonObject->gender),
        'users_email'       => strtolower(cleanData($jsonObject->users_email)),
        'users_phone'       => cleanData($jsonObject->users_phone),            
        'admin_id'          => cleanData($jsonObject->admin_id)
    );

    // elimina los guiones de la cedula
    while ( strpos($array_user_data['users_goverment_id'],"-") > -1)
    {
        $array_user_data['users_goverment_id'] = str_replace("-","",$array_user_data['users_goverment_id']);
    }

    // verifica que los datos este seteados
    if ( 
        isset($array_user_data['users_name'])           &&
        isset($array_user_data['first_name'])           &&
        isset($array_user_data['last_name'])            &&
        isset($array_user_data['users_goverment_id'])   &&
        isset($array_user_data['gender'])               &&
        isset($array_user_data['users_email'])          &&
        isset($array_user_data['admin_id'])        
    ){
        // instanciamos la clase conexion
        $Conexion = new Library\Classes\Conexion;
    
        // obtenemos la conexion, para $dbConfig ver config.php
        $conn = $Conexion->get($dbConfig);
    
        // instanciamos la clase sesion
        $Session = new Library\Classes\Session;
    
        // verificamos que el rol de admin y la session esten correctos
        // devuelve true si todo esta bien, de lo contrario false
        $result = $Session->check_session_and_role_from_admin_id($conn,$array_user_data['admin_id']);
        
        if ($result)
        {
            
            // instanciamos la clase persona
            $Person = new Library\Classes\Person;
        
            // enviamos los datos para ser registrados en la base de datos.
            $result = $Person->sign_up($conn,$array_user_data);
            
            if(isset($result[0]['status']))
            {
                foreach ($result as $item) {
                    $status = isset($item['status']) ? $item['status'] : null;
                }
            }
            else
            {
                $status="409";
            }
            
            // si se registro el usuario el estado es 200
            if ($status == "200")
            {            
                // indicamos la ruta el archivo html a enviar como mensaje
                $emailToUser    = $array_user_data['users_email'];
                $emailToStaff   = EMAIL_TO_STAFF; // -> ver config.php
                $msgToUser      = NOTIFICACION_TO_NEW_USER_MESSAGE_FILE; // -> ver config.php            
                $msgToStaff     = NOTIFICACION_TO_STAFF_FOR_NEW_USER_MESSAGE_FILE;
                $aliasName      = isset($array_user_data['first_name']) ? $array_user_data['first_name'] : "Usuario";            
        
                $arrayData = [
                    'emailToUser'   => $emailToUser,                # email
                    'emailToStaff'  => $emailToStaff,               # email
                    'aliasName'     => $aliasName,                  # string
                    'msgToUser'     => $msgToUser,                  # url
                    'msgToStaff'    => $msgToStaff,                 # url
                    'where'         => 'notification_new_user',     # int       Ver -> class.mail.php    
                    'token'         => ''                           # string    Ver -> functions.php           
                ];
        
                // instanciamos la clase Email
                $Email = new Library\Classes\Email;
        
                // enviamos el correo
                $send = $Email->send($arrayData,$conn);
                
                if($send)
                {   
                    
                    // solicitamos los datos el token de seguridad actualizado y verificamos que el email enviado
                    // este asociados a un usuario,
                    // devuelve un array con los datos si es true,
                    // de los contrario devuelve false  
                    $result = $Person->get_security_token_by_email($conn,$array_user_data['users_email']);
    
    
                    // indicamos la ruta el archivo html a enviar como mensaje de activacion
                    $emailToUser    = $array_user_data['users_email'];                
                    $msgToUser      = NOTIFICACION_TO_NEW_USER_TO_ACTIVATE_ACCOUNT_MESSAGE_FILE; // -> ver config.php                            
                    $aliasName      = isset($array_user_data['first_name']) ? $array_user_data['first_name'] : "Usuario";            
            
                    $arrayData = [
                        'emailToUser'   => $emailToUser,                # email
                        'emailToStaff'  => '',                          # email
                        'aliasName'     => $aliasName,                  # string
                        'msgToUser'     => $msgToUser,                  # url
                        'msgToStaff'    => '',                          # url
                        'where'         => 'active_account',            # int       Ver -> class.mail.php    
                        'token'         => $result['token']             # string    Ver -> functions.php           
                    ];
    
                    // enviamos el correo
                    $send = $Email->send($arrayData,$conn);
    
                    if($send)
                    {
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'Mensaje de activacion enviado.'
                        ];
                    }
                    else
                    {
                        http_response_code(409);
                        $result = [
                            'status'    => '409',
                            'message'   => 'El mensaje de activacion no pudo ser enviado.'
                        ];
                    }
                }
                else
                {            
                    http_response_code(409);
                    $result = [
                        'status'    => "409",
                        "message"   => "Usuario creado, pero el correo de bienvenida no pudo ser enviado."
                    ];
                }
            }
            elseif ($status == "403")
            {
                
                http_response_code(403);           
                $result = [            
                    'status' => "403",
                    'messge' => "El usuario ya existe."
                ];
            }
            else
            {
                http_response_code(409);           
                $result = [            
                    'status' => "409",
                    'message' => "El usuario no pudo ser registrado."
                ];
            }
        }
        else
        {
            http_response_code(401);
            $result = [            
                'status' => "401",
                'messge' => "No tiene autorizacion."
            ];             
        }
    }
    else
    {
        http_response_code(401);
        $result = [            
            'status' => "401",
            'message'=> "Debe llenar todos los campos."
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