<?php
    require_once './../admin/config.php';
    require_once './functions.php';

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

    /* require_once './../library/class/class.conn.php';
    require_once './../library/class/class.session.php';
    require_once './../library/class/class.modules.php';
    require_once './../library/class/class.files.php';
    require_once './../library/class/class.cat.php'; */

    $jsonString                 = file_get_contents('php://input');
    $jsonObject                 = json_decode($jsonString); 

    $jsonObject->target           = cleanData($jsonObject->target);    
    $jsonObject->session_token    = cleanData($jsonObject->session_token);
    $jsonObject->user_id          = cleanData($jsonObject->user_id);
    
    $target         = $jsonObject->target;
    $session_token  = $jsonObject->session_token;
    $user_id        = $jsonObject->user_id;
    
    
if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST'   && 
    isset($target) && !empty($target)
   )
{
    // instanciamos la conexion
    $Conexion = new Library\Classes\Conexion;

    // obtenemos la conexion
    $conn = $Conexion->get($dbConfig);

    // comprobamos la conexion
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
            // instanciamos la clase modulos
            $Modules = new Library\Classes\Modules;
            
            if ( $target == 'module' )
            {
                //$module_name = 
                // obtiene los datos del modulo por su nombre,
                // devuelve un array con los datos de los contrario false
                //$module_data = $Modules->get_module_data_by_name($conn,$module_name);
            }
            else if ( $target == 'modules' )
            {
                
                $selected_page = cleanData($jsonObject->selected_page);

                // devuelve un array con los datos de los modulos
                // limitado por posicionamiento inicial y final
                $result = $Modules->get_list($conn,$selected_page);

                $response['message'] = 'Modulos obtenidos.';
                $response['target']  = 'modules';

            }
            else if ( $target == 'modules_action' )
            {
                $action_active    = cleanData($jsonObject->action_active);
                $action_inactive  = cleanData($jsonObject->action_inactive);
                                
                if ( strlen($action_active) > 0 )
                { 
                    $module_id =  $action_active; 
                    $action = "active";
                } 
                else if ( strlen($action_inactive) > 0 )
                { 
                    $module_id =  $action_inactive;
                    $action = "inactive";
                }
                
                // esta metodo define el estado del modulo
                // activado o desactivado.
                // devuelve los datos del modulo o de lo contrario false
                $result = $Modules->module_status($conn,$module_id,$action);
                
                /* $module_data = array(                
                    "name"          => $result[0]["name"],
                    "version"       => $result[0]["version"],
                    "installed"     => $result[0]["installed"]
                ); */                

                $response['message'] = 'Estado del modulo cambiado.';
                $response['target']  = 'module_action';
            }
            else if ( $target == 'actives_modules' )
            {
                $result = $Modules->get_all_modules($conn);

                $response['message'] = 'Modulos obtenidos.';
                $response['target']  = 'actives_modules';

            }
            else if ( $target == 'module_uninstall' )
            {
                $action_uninstall = cleanData($jsonObject->action_uninstall);

                if ( strlen($action_uninstall) > 0 )
                {
                    $module_id =  $action_uninstall;

                    // devuelve un array con los datos de todos los modulos
                    // de lo contrario devuelve false
                    $result = $Modules->get_module_data_from_id($conn,$module_id);

                    if ($result)
                    {                        
                        $module_data = $result;

                        $ManageDB = new Library\Classes\ManageDB;

                        // eliminar las entidades de la base de datos que fueron creadas por el modulo 
                        // en cuestion. Recibe el Id del modulo.
                        // devuelve true de lo contrario false
                        $result = $ManageDB->remove_module_schema_from_database( $module_id );
                        
                        //var_dump($result); die();

                        if ( ! $result ) { on_exception_server_response( 409 , 'Entidad de Modulo no Eliminada en DB.', $target ); die(); }

                        // este metodo se encarga de desinstalar el modulo.
                        // de la base de datos
                        $result = $Modules->uninstall($conn,$module_id);
                        
                        if ($result)
                        {
                            // instanciamos la clase Files.
                            $Files = new Library\Classes\Files;
                            //"./../modules/control-de-visitas"
                            $module_path = MODULE_DIR_PATH . str_replace(' ','-',strtolower($module_data[0]['name']));
                            
                            // devuelve true si elimina todos los archivos y carpetas de un directorio dado
                            // recibe un array creado con la funcion glob($path."*")
                            // de lo contrario false 
                            $result = $Files->removeDirAndFiles($module_path);
                            
                            if ( ! $result ) { on_exception_server_response( 409 , 'Modulos no desinstalado.', $target ); die(); }                                                        

                            $response['message'] = 'Modulo desintalado correctamente.';
                        }
                        
                        
                    }
                    else
                    {
                        $response['message'] = 'Modulo no obtenido por id.';
                    }
                    
                    $response['target']  = 'module_uninstall';

                    if ($result)
                    {
                        http_response_code(200);
                        
                        $response['data'] = $result;
                        $response['status'] = '200';
                        $response['uninstalled'] = 'true';                       
                        
                    }
                    else
                    {
                        http_response_code(409);
                        
                        $response['status'] = '409';
                        $response['message'] = 'Modulos no desinstalado.';                        
                        $response['uninstalled'] = 'false';
                    }

                }
                
                header('Content-Type: application/json');
                $response = json_encode($response);
                echo $response;
                
                die();

            }
            
            if ($result)
            {
                http_response_code(200);

                $response['data']  = $result;
                $response['status'] = '200';
                
            }
            else
            {
                http_response_code(200);

                $response['status'] = '204';
                $response['message'] = 'Modulos no obtenidos.';                
            }
        }
        else
        {
            http_response_code(401);

            $response['status'] = '401';
            $response['message'] = 'Token invalido.';
            
        }
    }
    // si no hay conexion devuelve un mensaje
    else
    {
        http_response_code(500);

        $response['status'] = '500';
        $response['message'] = 'Fallo de conexion. Contacte al administrador de sistemas.';
        
    }

    header('Content-Type: application/json');
    $response = json_encode($response);
    echo $response;
}
die();