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

$db_field     = cleanData($jsonObject->db_field);
$keyword      = cleanData($jsonObject->keyword);

//echo var_dump($db_field . " - " . $keyword);

// definimos parametros de paginacion
$selected_page= cleanData( !empty($jsonObject->selected_page)  ? $jsonObject->selected_page : 1 );
    
$limit        = cleanData( !empty($jsonObject->limit) ? $jsonObject->limit: 2 );

$actionView   = cleanData($jsonObject->action_view);
$actionEdit   = cleanData($jsonObject->action_edit);
$actionDelete = cleanData($jsonObject->action_delete);

$session_token= cleanData($jsonObject->session_token);
$user_id      = cleanData($jsonObject->user_id);

if (
    $_SERVER['REQUEST_METHOD'] == 'POST'             && 
    ( isset($actionView)   && !empty($actionView) )   ||
    ( isset($actionEdit)   && !empty($actionEdit) )   ||
    ( isset($actionDelete) && !empty($actionDelete) )
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
            
            // inicializamos result
            $result = false;
    
            // obtiene la informacion para visualizarla
            if ( isset($actionView) && !empty($actionView) )
            {
                $user_id = isset($actionView) ? $actionView : null;
                
                $result = $Person->get_users_info_from_id($conn,$user_id);
                
                if($result)
                { 
                    http_response_code(200);
                    $result = [
                        'status' => "200",
                        'opc'    => "view",
                        'data' => $result[0]
                    ];                
                }
                
            }
            // obtiene la informacion para editarla
            else if ( isset($actionEdit) && !empty($actionEdit) )
            {
                $user_id = isset($actionEdit) ? $actionEdit : null;
    
                $result = $Person->get_users_info_from_id($conn,$user_id);
                
                if($result)
                {
                    http_response_code(200);  
                    $result = [
                        'status' => "200",
                        'opc'    => "edit",
                        'data' => $result[0]
                    ];                
                }
    
            }
            // borrar el usuario indicado
            else if ( isset($actionDelete) && !empty($actionDelete) )
            {
                $user_id = isset($actionDelete) ? $actionDelete : null;
    
                $result = $Person->delete_user_info_from_id($conn,$user_id);
                
                if($result)
                {  
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'Usuario Eliminado.'
                    ];
                }
            }
    
            if(!$result)
            {
                http_response_code(409);
                $result = [
                    'status'    => '409',
                    'message'   => 'Datos no encontrados, contacte al administrador.'
                ];
            }
        }
        else
        {
            http_response_code(401);  
            $result = [
                'status'    => '401',
                'message'   => 'Token invalido'
            ];
        } 
    }
}
else if (
    $_SERVER['REQUEST_METHOD'] == 'POST'   && 
    isset($db_field) && isset($keyword)  
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

        if($result)
        {
            // instanciamos la clase persona
            $Person = new Library\Classes\Person;
    
            // verifica si el usuario que ejecuta la accion es administrador o soporte devuelve
            // true de lo contrario false
            $result = $Person->is_user_an_admin_or_support($conn,$user_id);
    
            if($result)
            {
                // devuelve los datos de la consulta
                $result = $Person->get_users_info_from_filter($conn,$db_field,$keyword,$selected_page);
                
                if($result)
                {   
                    http_response_code(200);  
                    $result = [
                        'status'        => '200',
                        'pagination'    => $result['pagination'],
                        'data'          => $result['data']
                    ];
                }
                else
                {
                    http_response_code(206);
                    $result = [
                        'status'    => '206',
                        'message'   => 'No hubo resultados.'
                    ];                
                }
            }
            else
            {
                http_response_code(401);
                $result = [
                    'status'    => '401',
                    'message'   => 'No tiene autorizacion.'
                ];
            }
        }
        else
        {
            http_response_code(401);
            $result = [
                'status'    => '401',
                'message'   => 'No tiene autorizacion.'
            ];
        }

        
    }
      
}
else
{    
    $result = [
        'status'    => '502',
        'message'   => 'Faltan parametros.'
    ];
}

// si no hay conexion devuelve un mensaje
if( $_SERVER['REQUEST_METHOD'] == 'POST' && !$conn )
{   
    http_response_code(503); 
    $result = array(
        0 =>
        [
            'status'    => '503',
            'message'   => 'Fallo de conexion. Contacte al administrador de sistemas.'
        ]
    );
}
else if (!$result && !$conn)
{
    http_response_code(400);
    $result = [
        'status'    => '400',
        'message'   => 'Faltan parametros.'
    ];
}  

header('Content-Type: application/json');
echo json_encode($result);
die();