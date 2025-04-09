<?php

    try {

        $APACHE_HEADERS = apache_request_headers();        

        if ( isset($APACHE_HEADERS["Content-Length"]) && $APACHE_HEADERS["Content-Length"] >  POST_FILE_MAX_SIZE )        
        {
            on_exception_server_response(403,'Los archivos exceden la capacidad permitida de carga.');
            die();
        }

        // recibe un objeto json
        $jsonString = file_get_contents('php://input'); 
        
        // lo convertimos en una clase php 
        // $jsonObject = json_decode($jsonString);

        /**
         * Recibe un objeto json string o un formulario multipart/form-data, no envie ambos en una sola peticion.
         */

        $jsonObject = ( !empty($jsonString) && is_json($jsonString) ) ? json_decode($jsonString) : (object) $_POST;
        
        $target         = isset($jsonObject->target)        ? cleanData(trim_double($jsonObject->target)) : "";
        
        # $form_id        = isset($jsonObject->form_id)       ? cleanData(trim_double($jsonObject->form_id)) : "";
        # el form_id se debe verificar en los controladores que lo necesiten

        $session_token  = isset($jsonObject->session_token) ? cleanData(trim_double($jsonObject->session_token)) : null;
        $user_id        = isset($jsonObject->user_id)       ? cleanData(trim_double($jsonObject->user_id)) : null;
        
        if ( 
            $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($target) || empty($target)
            # !isset($form_id) || empty($form_id) || $form_id !== session_id()
            )
        {
            // Si el metodo no es post, el target no esta definido
            // entonces resultado 400.
            on_exception_server_response(400,'Error. Faltan parametros.',$target);
            die();
        }    
        
        //instanciamos la conexion
        $Conexion = Library\Classes\Conexion::singleton();

        //obtenemos la conexion
        $conn = $Conexion->get($dbConfig);

        // si no hay conexion
        if ( ! $conn )
        {   
            // Devuelve el codigo de respuesta del servidor especificado y un json con datos.        
            on_exception_server_response(409,'Error. Contacte al administrador.');
            die();
        }
        
        $array_token_and_user_id = [
            "session_token" => $session_token,
            "user_id"       => $user_id
        ];

        // instanciamos la clase persona.
        $Person = Library\Classes\Person::singleton();        
        // booleano true o false
        $user_is_admin = $Person->is_user_an_admin($conn,$array_token_and_user_id['user_id']);
        // booleano true o false
        $user_is_admin_or_support = $Person->is_user_an_admin_or_support($conn,$array_token_and_user_id['user_id']);

        // instanciamos la clase session
        $Session = Library\Classes\Session::singleton();
        
        // veficamos el token y el user id en la base de datos
        // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
        // si no es falso
        $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
        
        // si es falso
        if( ! $result )
        {
            on_exception_server_response(401,'Token Expirado.');
            die();
        }
                 
        $array_target = explode("-",$target);
        $target_lv1 = isset($array_target[0]) ? $array_target[0] : "";
        $target_lv2 = isset($array_target[1]) ? $array_target[1] : "";
                
        switch ($target_lv1) {
            
            case 'post':                
                switch ($target_lv2) {
                    case 'create'   :  include_once('./controllers/Post/PostCreate.php'); break;
                    case 'read'     :  include_once('./controllers/Post/PostRead.php'); break;
                    case 'read_all' :  include_once('./controllers/Post/PostReadAll.php'); break;
                    case 'consult'  :  include_once('./controllers/Post/PostConsult.php'); break;
                    case 'update'   :  include_once('./controllers/Post/PostUpdate.php'); break;
                    case 'delete'   :  include_once('./controllers/Post/PostDelete.php'); break;
                    default: break;
                }                
                break;             
            case 'categories':
                switch ($target_lv2) {
                    case 'create': include_once('./controllers/Categories/CategoriesCreate.php'); break;
                    case 'read_all': include_once('./controllers/Categories/CategoriesReadAll.php'); break;                    
                    case 'delete': include_once('./controllers/Categories/CategoriesDelete.php'); break;                    
                    case 'update': include_once('./controllers/Categories/CategoriesUpdate.php'); break;                    
                    default: break;
                }
                break;
            case 'files':
                switch ($target_lv2) {
                    case 'show_or_download': include_once('./controllers/Files/FilesShowOrDownload.php'); break;                    
                    case 'delete_temp_file': include_once('./controllers/Files/FilesDeleteTempFile.php'); break;                    
                    
                    default: break;
                }
                break;                
            case 'settings':
                switch ($target_lv2) {
                    case 'read_all': include_once('./controllers/Settings/SettingsReadAll.php'); break;
                    case 'update_printer': include_once('./controllers/Settings/SettingsUpdatePrinter.php'); break;
                    case 'update_files_download': include_once('./controllers/Settings/SettingsUpdateFilesDownload.php'); break;
                    case 'update_files_upload': include_once('./controllers/Settings/SettingsUpdateFilesUpload.php'); break;
                    
                    default: break;
                }
                break;
            default: on_exception_server_response(400,'Faltan parametros'); break;

        }    
        die();
    } catch(Exception $e) {
        echo 'catch Message: ' .$e->getMessage();
    }
   