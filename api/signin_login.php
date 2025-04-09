<?php
    
require_once('./../admin/config.php');
require_once('./../functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');    
require_once('./../library/class/class.person.php'); */

$jsonString = file_get_contents('php://input');
$jsonObject = json_decode($jsonString); 

$jsonObject->login_user = cleanData($jsonObject->login_user);    

// si la peticion es post y la propiedad no esta vacia
if( $_SERVER["REQUEST_METHOD"] == "POST" && !empty($jsonObject->login_user) )
{
    
    $array_user_and_password_submited = array(
        'login_user'   => $jsonObject->login_user,
        'login_password'=> $jsonObject->login_password
    );

    // si los datos son correctos
    if(
         count($array_user_and_password_submited) == 2               &&
         isset($array_user_and_password_submited['login_user'])      &&
         isset($array_user_and_password_submited['login_password'])  &&
        !empty($array_user_and_password_submited['login_user'])      &&
        !empty($array_user_and_password_submited['login_password'])
    )
    {
        
        //si la sesion esta iniciada la destruye
        if (session_id() !== "") { session_destroy(); };

        // obtengo el objeto de conexion si existe o lo crea si no.
        $Conn = new Library\Classes\Conexion;
        // obtengo la conexion
        $conn = $Conn->get($dbConfig);

        // si hay conexion
        if($conn)
        {
            
            //Instanciamos la clase persona
            $Person = new Library\Classes\Person;
        
            // solicitamos los datos del usuario
            // si los datos enviados son correctos devuelve un array los datos del usuario
            // de lo contrario devuelve false
            // recibe la conexion en PDO y un array con los datos de inicios de sesion
            $arrayData = $Person->get_login_info($conn,$array_user_and_password_submited);
            
            // Si el resultado es falso devuelve devuelve estado del servidor 204 - No content
            // y finaliza el script
            // ver functions.php
            if($arrayData)
            {
                // solicitamos la insercion de un token de session
                // y obtenemos los datos de usuario y session
                // de lo contrario devuelve false
                $result = $Person->insert_session_token($conn,$arrayData);
                
                if($result)
                {   
                    // estado del servidor 200 - Acepted
                    http_response_code(200); 
                    $result = [
                        'status'    => '200',
                        'data'   => $result
                    ];       
                }
                else
                {
                    http_response_code(409);
                    $result = [
                        'status'    => '409',
                        'message'   => 'Error. Contacte al administrador de sistemas.'
                    ];
                }
            }
            else
            {
                http_response_code(403);
                $result = [
                    'status'    => '403',
                    'message'   => 'Usuario o contraseña in correctos.'
                ];
            }
            
        }
        // de lo contrario
        else
        {
            http_response_code(409);
            $result = [
                'status'    => '409',
                'message'   => 'Fallo de conexion. Contacte al administrador de sistemas.'
            ];
        }
    }
    // de los contrario
    else
    {
        $result = [
            'status'    => '400',
            'message'   => 'Faltan parametros.'
        ];    
    }
}
// de lo contrario
else
{    
    $result = [
        'status'    => '400',
        'message'   => 'Faltan parametros.'
    ];
}

// devolvemos el resultado
header('Content-Type: application/json'); 
echo json_encode($result);  
die();