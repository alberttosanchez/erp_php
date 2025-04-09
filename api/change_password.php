<?php
    
require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');    
require_once('./../library/class/class.person.php');      
require_once('./../library/class/class.email.php');  */

$jsonString = file_get_contents('php://input');
$jsonObject = json_decode($jsonString);  

$target = isset($jsonObject->target) ? cleanData($jsonObject->target) : "";   

if( $_SERVER["REQUEST_METHOD"] == "POST" && $target == "update_password" )
{ 
    // verificamos que el jsonObject este seteado y obtemos el email        
    $user_email     = isset($jsonObject->user_email)    ? cleanData($jsonObject->user_email)    : "";   
    $new_password   = isset($jsonObject->new_password)  ? cleanData($jsonObject->new_password)  : "";   
    $security_token = isset($jsonObject->security_token)? cleanData($jsonObject->security_token): "";               
    
    // instanciamos la conexion
    $Conexion = new Library\Classes\Conexion;

    // obtenemos la conexion
    // $dbconfig -> ver config.php
    $conn = $Conexion->get($dbConfig);
    
    // si no hay conexion devuelve un objeto vacio
    if ($conn)
    {
        // instanciamos la clase persona
        $Person = new Library\Classes\Person;
    
        // validamos que el token este correcto y vigente
        // si es correcto devuelve el token de lo contrario false.
        $security_token = $Person->validate_security_token_by_email($conn,$security_token,$user_email);

        if($security_token)
        {
            // recibe el nuevo token, email y nuevo password
            // devuelve true de lo contrario false
            $response = $Person->update_password($conn,$user_email,$new_password);            
            
            if($response)
            {
                // solicitamos los datos el token de seguridad actualizado y 
                // verificamos que el email enviado este asociados a un usuario,
                // devuelve un array con los datos si es true,
                // de los contrario devuelve false
                $arrayData = $Person->get_security_token_by_email($conn,$user_email);
                
                if($arrayData)
                {
                
                    // indicamos la ruta el archivo html a enviar como mensaje
                    $fileToUser = NOTIFICACION_CHANGE_PASSWORD_MESSAGE_FILE; // -> ver config.php            
                    $emailToStaff = EMAIL_TO_STAFF; // -> ver config.php
            
                    $emailToUser = $arrayData['users_email'];
                    $aliasName = isset($arrayData['users_name']) ? $arrayData['users_name'] : "Usuario";
                    $token = $arrayData['token'];
            
                    $arrayData = [
                        'emailToUser'   => $emailToUser,    # email
                        'emailToStaff'  => $emailToStaff,   # email
                        'aliasName'     => $aliasName,      # string							
                        'msgToUser'     => $fileToUser,	    # url archivo							
                        'msgToStaff'    => '',              # url archivo							
                        'where'         => 'notificacion_password_change',	# int       Ver -> metodo send() en class.email.php;
                        'token'         => $token 	# string    Ver -> functions.php           
                    ];
            
                    // instanciamos la clase Email
                    $Email = new Library\Classes\Email;
            
                    $result = $Email->send($arrayData);
            
                    if($result)
                    {
                        http_response_code(200);
                        $result = [
                            "status"    => "200",
                            "message"   => "Password actualizado."
                        ];
                    }                    
                    else
                    {
                        http_response_code(409);
                        $result = [
                            "status"    => "409",
                            "message"   => "Error. Contacte al administrador"
                        ];
                    }
                }
                else
                {
                    http_response_code(409);
                    $result = [
                        "status"    => "409",
                        "message"   => "Error. Contactar al administrador."
                    ];
                }
                
            }
            else
            {
                http_response_code(409);
                $result = [
                    "status"    => "409",
                    "message"   => "Error. Datos no actualizados."
                ];
            }
        
        }
        else
        {
            http_response_code(401);
            $result = [
                "status"    => "401",
                "message"   => "Token invalido."
            ];
        }
        
    }
    else
    {
        http_response_code(409);
        $result = [
            "status"    => "409",
            "message"   => "Error. Contacte al administrador de sistemas."
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
header("Content-Type:application/json");
echo json_encode($result);