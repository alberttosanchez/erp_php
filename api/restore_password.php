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

if( $_SERVER["REQUEST_METHOD"] == "POST" && isset($jsonObject->user_email) )
{
    $user_email = cleanData($jsonObject->user_email);               
    

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

        // solicitamos los datos el token de seguridad actualizado y verificamos que el email enviado
        // este asociados a un usuario,
        // devuelve un array con los datos si es true, -> ver class.person.php
        // de los contrario devuelve false
        $arrayData = $Person->get_security_token_by_email($conn,$user_email);
        
        if($arrayData)
        {
            // indicamos la ruta el archivo html a enviar como mensaje
            $fileToUser = RESTORE_PASSWORD_MESSAGE_FILE; // -> ver config.php            
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
                'where'         => 'restore',	    # int       Ver -> metodo send() en class.email.php;
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
                    "message"   => "Si los datos son correctos recibirá un correo con los pasos para recurpera su contraseña."
                ];         
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
        "message"   => "Faltan Pararemotros."
    ];
}
header("Content-Type:application/json");
echo json_encode($result);