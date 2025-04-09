<?php

require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php');
require_once('./../library/class/class.person.php');
require_once('./../library/class/class.files.php'); */

$target = isset($_POST['target']) ? cleanData($_POST['target']) : "";

if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST' && 
    $target == 'upload_avatar'
)
{
    // obtenemosla extension del archivo a subir
    $ext = get_file_extension($_FILES['avatar_file']['name']);

    // verificamos la extension del archivo a subir
    if (
        ( 
            ( $_FILES['avatar_file']['type'] == "image/jpeg" && ($ext == "jpg" || $ext == "jpeg" ) ) ||
            ( $_FILES['avatar_file']['type'] == "image/png" && $ext == "png" ) 
        ) &&  $_FILES['avatar_file']['size'] < 2024000
    )
    {        
        if (
            isset($_POST['session_token']) && 
            isset($_POST['user_id'])  && 
            isset($_POST['user_role_id']) 
        )
        {
            //instanciamos la clase conexion
            $Conexion = new Library\Classes\Conexion;
    
            //obtenemos la conexion
            $conn = $Conexion->get($dbConfig);
    
            // verificamos la conexion
            if($conn)
            {
    
                $session_token  = cleanData($_POST['session_token']);
                $user_id        = cleanData($_POST['user_id']);
                $user_role_id   = cleanData($_POST['user_role_id']);
    
                $array_token_and_user_id = [
                    "session_token" => $session_token,
                    "user_id"       => $user_id
                ];
    
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
                    if( $user_conected_id == $user_id )
                    {
    
                        $image_file = (isset($_FILES['avatar_file'])) ? $_FILES['avatar_file'] : null;
    
                        // si el archivo existe
                        if ($image_file)
                        {
    
                            $file_temp_name = $image_file['tmp_name'];
                            
                            // instanciamos la clase Files
                            $Files = new Library\Classes\Files;

                            // devuelve la extension del tipo mime si es jpg o png
                            // de lo contrario false
                            $ext = $Files->get_image_mime_type($file_temp_name);
                            
                            if($ext)
                            {
                                // ruta directorio que contiene la imagen
                                $image_path = PROFILE_AVATAR_DIR_PATH.$user_id."/avatar"."/";

                                // elimina el archivo de imagen actual
                                // si recibe la extension del nuevo archivo elimina la extensiones contrarias
                                // si no recibe la extension eliminar todas las extensiones encontradas
                                // devuelte true al eliminar, de lo contrario false
                                $result = $Files->delete_current_image_file($user_id,$image_path,$ext);
                                
                                if ($result)
                                {
                                    // recibe la ruta a crear y el modo de acceso por defecto todo permitido
                                    // si no existe lo crea, devuelve true al crearlo de lo contrario false
                                    $result = $Files->create_path($image_path);
                                    
                                    if($result)
                                    {
                                        // ruta completa donde se guardara el archivo (incluye nombre del archivo)
                                        $path_to_save_image_file = $image_path.$user_id.".".$ext;
                                        
                                        // devuelve true si el archivo es movido correctamente, de lo contrario false.
                                        $file_was_moved = move_uploaded_file($image_file['tmp_name'], $path_to_save_image_file);                        
                                                                
                                        if($file_was_moved)
                                        {   
                                            
                                            $image_name = $user_id.".".$ext;
                                            // instanciamos la clase persona.
                                            $Person = new Library\Classes\Person;
                                            
                                            // actualiza el nombre del archivo de imagen en la base de datos.
                                            $result = $Person->update_user_profile_image_name($conn,$user_id,$image_name);
                                            
                                            if($result)
                                            {
                                                http_response_code(200);
                                                $result = [
                                                    'status'    => '200',
                                                    'message'   => 'Avatar actualizado.'
                                                ]; 
                                            }
                                            else
                                            {
                                                http_response_code(403);
                                                $result = [
                                                    'status'    => '403',
                                                    'message'   => 'Avatar no actualizado.'
                                                ];
                                            }
                                        }
                                        else
                                        {
                                            http_response_code(403);
                                            $result = [
                                                'status'    => '403',
                                                'message'   => 'Avatar no guardado.'
                                            ];
                                        }
                                    }
                                    else
                                    {
                                        http_response_code(409);
                                        $response = [
                                            'status'    => '409',
                                            'message'   => 'Error. Contacte al Administrador.'
                                        ];
                                    }
                                }
                                else
                                {
                                    http_response_code(409);
                                    $result = [
                                        'status'    => '409',
                                        'message'   => 'Error. Contactar al administrador.'
                                    ];
                                }
                            }
                            else
                            {
                                http_response_code(403);
                                $result = [
                                    'status'    => '403',
                                    'message'   => 'tipo de archivo no permitido.'
                                ];
                            }
                        }
                        else
                        {
                            http_response_code(403);
                            $result = [
                                "status"    => "403",
                                "message"   => "Debe cargar una imagen."
                            ];
                        }
                    }
                    else
                    {
                        http_response_code(401);
                        $result = [
                            "status"    => "401",
                            "message"   => "No tiene autorizacion. Token invalido."
                        ];
                    }
                }
                else
                {
                    http_response_code(401);
                    $result = [
                        "status"    => "401",
                        "message"   => "No tiene autorizcion. Token invalido."
                    ];
                }
    
            }
            else
            {
                http_response_code(409);
                $result = [
                    "status"    => "409",
                    "message"   => "El servidor no responde. Contacte a su administrador."
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
    }
    else if ($_FILES['avatar_file']['size'] > 2024000 )
    {
        http_response_code(403);
        $result = [
            "status"    => "403",
            "messsage"  => "El tamaño máximo de la imagen debe ser 2mb."
        ];
    }
    else
    {
        http_response_code(403);
        $result = [
            "status"    => "403",
            "messsage"  => "Solo se permiten imagenes jpg y png."
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
header("Content-Type:application/json");
echo json_encode($result);
die();