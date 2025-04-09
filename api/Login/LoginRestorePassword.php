<?php //target : login-restore_password

$user_email = isset($jsonObject->user_email) ? cleanData($jsonObject->user_email) : "";

if ( !isset($user_email) || empty($user_email) )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
} 

// instanciamos la conexion
$Conexion = new Library\Classes\Conexion;

// obtenemos la conexion
// $dbconfig -> ver config.php
$conn = $Conexion->get($dbConfig);      

if ( !$conn )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(500,'Error. Desconocido contacte al administrador de sistemas.',$target);
    die();
} 

// instanciamos la clase persona
$Person = new Library\Classes\Person;

// solicitamos los datos el token de seguridad actualizado y verificamos que el email enviado
// este asociados a un usuario,
// devuelve un array con los datos si es true, -> ver class.person.php
// de los contrario devuelve false
$arrayData = $Person->get_security_token_by_email($conn,$user_email);

if ( $arrayData == false )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
} 

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

/** Si no hubo errores devuelve el resultado con estado 200 */

http_response_code(200);
$result = [
    'status'    => '200',
    'message'   => 'Si los datos son correctos recibirá un correo con los pasos para recurperar su contraseña.',
    'data'      => [],
]; 