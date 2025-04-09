<?php
require_once './../admin/config.php';
require_once './functions.php';

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once './../library/class/class.conn.php';
require_once './../library/class/class.session.php';
require_once './../library/class/class.person.php'; */

$jsonString   = file_get_contents('php://input');
$jsonObject   = json_decode($jsonString);

$session_token= cleanData($_GET["session_token"]);
$user_id      = cleanData($_GET["user_id"]);

if ( 
    $_SERVER["REQUEST_METHOD"] == "GET" && 
    isset($session_token) && !empty($session_token) && 
    isset($user_id) && !empty($user_id) 
)
{
    // instanciamos la clase conexion
    $Conexion = new Library\Classes\Conexion;
    // obtenemos la conexion -> para dbConfig ver config.php
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
            // instanciamos la clase persona
            $Person = new Library\Classes\Person;

            // devuelve los datos del usuarios por el id
            // de lo contrario false
            $result = $Person->get_users_info_from_id($conn,$user_id);

            if($result)
            {
                http_response_code(200);
                $result = [
                    "status"    => "200",
                    "data"      => $result[0],
                ];
            }
            else
            {
                http_response_code(409);
                $result = [
                    "status"    => "409",
                    "message"   => "Error. Contacte a su Administrador."
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
            "message"   => "Error de Conexion. Contacte al administrador."
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

header("Content-Type: application/json");
echo json_encode($result);