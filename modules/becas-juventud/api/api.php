<?php
// recibe un objeto json
$jsonString = file_get_contents('php://input'); 

// lo convertimos en una clase php 
//$jsonObject = json_decode($jsonString);

$jsonObject = ( !empty($jsonString) ) ? json_decode($jsonString) : (object) $_POST;

$result = [            
    'status' => "400",
    'message'=> "Faltan parametros."
];

//var_dump( $jsonObject->form_id === session_id() ); die();

if ( 
    $_SERVER['REQUEST_METHOD'] !== 'POST' || // empty($jsonString) && 
    !isset($jsonObject->target) || $jsonObject->form_id !== session_id()
    )
{
    // Si el metodo no es post, el target no esta definido y la session no es la misma
    // entonces resultado 400.
}
else
{
    //instanciamos la conexion
    $Conexion = Library\Classes\Conexion::singleton();

    //obtenemos la conexion
    $conn = $Conexion->get($dbConfig);

    if ( ! $conn )
    {   
        // Devuelve el codigo de respuesta del servidor especificado y un json con datos.
        // Recibe 3 parametros:
        // 1- el codigo de respuesta del servidor
        // 2- un mensaje a devolver
        // 3- un array opcional de resultado (default true) que sera converido en json
        on_exception_server_response(409,'Error. Contacte al administrador.');
        die();
    }
    else
    {
        $array_token_and_user_id = [
            "session_token" => isset($jsonObject->session_token) ? $jsonObject->session_token : null,
            "user_id"       => isset($jsonObject->user_id)? $jsonObject->user_id : null
        ];

        // instanciamos la clase session
        $Session = Library\Classes\Session::singleton();
        
        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
        
        // si es verdadero entra.
        if( ! $result )
        {
            on_exception_server_response(401,'Token Expirado.');
            die();
        }
        else
        { 
            $target = isset($jsonObject->target) ? $jsonObject->target : "";
            
            switch ($target) {
        
                case 'remove_temp_new_student_photo':            
                    include_once('./new-student-remove-temp-photo-api.php');
                    break;
                
                default:
                    # code...
                    break;
            }
        }  
    }    
}

if ( !empty($result['status']) && $result['status'] === '400' )
{
    //header('Location: ' . PAGES_DIRECTORY . '/404-page.php');
    if ( defined('PAGES_DIRECTORY') )
    {
        include_once( './../../' . PAGES_DIRECTORY . '/404-page.php' );
    }
    else {
        include_once( './../../../library/templates/pages/404-page.php' );
    }
    die();
}
//header("Content-Type: application/json");
echo json_encode($result);
die();


/*

<?php    //cuando el dato se envía como json, este no llega en la variable $_POST 
    //sino a través de php://input
    if($json = file_get_contents("php://input"))
    {
        //pasamos de json a array
        $post = json_decode($json, true);
        //en la key image llega un string de este estilo:
        // data:image/png;base64,iVBORw0KGgoAAAA....
        $parts = explode(";base64,", $post["image"]);
        //despues de decodificarlo vuelve a ser "blob" (mirar el codigo js que lo convierte de blob a base64)
        //$strblob guardaría algo como
        // �PNG  IHDR��󠒱 IDATx^���gv�U�˹_�42�@�Yaf�I�,{m�Ɩ���8h����H�h5Z�v5��+k�Y�$+L�H+�f
        $strblob = base64_decode($parts[1]);
        $uuid = uniqid();
        $pathfile = "upload/$uuid.png";
        file_put_contents($pathfile, $strblob);
        echo json_encode([
            "message" => "image uploaded successfully.",
            "file"    => $pathfile
        ]);
        exit;
    }

    <?php 

    // si el id de session esta vacio inicia la session
    /* if( empty( session_id() ) )
    {
        session_start();
    } */

// recibe un objeto json
# $jsonString = file_get_contents('php://input'); 

// lo convertimos en una clase php 
# $jsonObject = json_decode($jsonString);

#$target  = isset($jsonObject->target)   ? $jsonObject->target   : "";

function login( $jsonObject , $configDB )
{
    
    try 
    {           
        $usuario  = isset($jsonObject->usuario)  ? $jsonObject->usuario  : "";
        $password = isset($jsonObject->password) ? $jsonObject->password : "";
        $session_token = isset($jsonObject->session_id) ? $jsonObject->session_id : "";

        $Conexion = Library\Classes\Conexion::singleton();

        $conn = $Conexion->get($configDB);
        
        if ($conn)
        {

            $query = "SELECT id,user,pass FROM bcf_users WHERE user = :user and pass = :pass;";
    
            $statement = $conn->prepare($query);
    
            $statement->execute(array(
                ':user'     => $usuario,
                ':pass'     => $password
            ));
    
            $data = $statement->fetchAll();
    
            $id = isset($data[0]['id']) ? $data[0]['id'] : '';
    
            http_response_code(404);
            
            $result = [
                'status'    => 404,
                'message'   => 'no data',
                'data'      => []
            ];
    
            if ( count($data) > 0 )
            {
                $query = "UPDATE bcf_users SET session_token = :session_token WHERE id = :id;";
    
                $statement = $conn->prepare($query);
    
                $statement->execute(array(
                    ':session_token' => $session_token,
                    ':id'            => (int)$id
                ));
    
                $query = "SELECT id,user,pass,session_token FROM bcf_users WHERE id = :id;";
    
                $statement = $conn->prepare($query);
    
                $statement->execute(array(
                    ':id'     => $id                
                ));
    
                $data = $statement->fetchAll();
    
                http_response_code(200);    
                $result = [
                    'status'    => 200,
                    'message'   => 'data fetched',
                    'data'      => $data
                ];
            }        
            
        }
        else
        {
            $result = [
                'status'    => '500',
                'message'   => 'No hay conexion.'
            ];
        }
        
       
        
        
    } catch ( PDOException $Exception ) {
        http_response_code(500);
        $result = [            
            'status' => "500",
            'message'=> "Contacte el Administrador."
        ];
    }

    return $result; 
    
}

if ( isset($jsonObject->session_id) 
    && $jsonObject->session_id == session_id()
 )
{
    $result = login( $jsonObject , $configDB );
}
else
{
    include_once('./404.php');    
}
