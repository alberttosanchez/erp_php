<?php
    require_once './../admin/config.php';
    require_once './functions.php';

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename; }

    $jsonString  = file_get_contents('php://input');
    $jsonObject  = json_decode($jsonString); 

    if ( isset($jsonObject) )
    {
        $target         = isset($jsonObject->target) ? cleanData($jsonObject->target) : "";    
        $session_token  = isset($jsonObject->session_token) ? cleanData($jsonObject->session_token) : "";
        $user_id        = isset($jsonObject->user_id) ? cleanData($jsonObject->user_id) : "";
        
        // se guarda los datos de usuario y session en GLOBALS
        $GLOBALS['array_token_and_user_id'] = [
            'session_token' => $session_token,
            'user_id'       => $user_id
        ];
    }

    if ( 
        $_SERVER['REQUEST_METHOD'] == 'POST' && 
        isset($target) && !empty($target)
    )
    {
        // instanciamos la clase ManageDB        
        $ManageDB = new Library\Classes\ManageDB;

        // verificamos los datos de session (id usuario y token de sesion en $GLOBALS)
        if ( 
            $ManageDB->check_session() || 
            $target == "login-restore_password" 
            
            )
        {
            $array_target = explode("-",$target);
    
            $target_lv1 = isset($array_target[0]) ? $array_target[0] : "";
            $target_lv2 = isset($array_target[1]) ? $array_target[1] : "";
            $target_lv3 = isset($array_target[2]) ? $array_target[2] : "";

            switch ($target_lv1) {
                case 'login'    :
                    switch ($target_lv2) {                    
                        case 'restore_password': include_once('./Login/LoginRestorePassword.php'); break;
                    }
                case 'person'    :
                    switch ($target_lv2) {                    
                        case 'user_rol': include_once('./Person/PersonUserRol.php'); break;                        
                    }
                case 'print_tail':
                    switch ($target_lv2) {                    
                        case 'fill': include_once('./PrintTail/CreatePrintTail.php'); break;
                        case 'read': include_once('./PrintTail/ReadPrintTail.php'); break;  
                    }
                break;
                default: on_exception_server_response(403,'Faltan parametros'); break;
            }  
        }
        else        
        {
            http_response_code(401);
            $result = [
                'status'    => '401',
                'message'   => 'no autorizado',
                'data'      => [],
            ]; 
        }
        
    }
    else
    {
        $result = [
            'status'    => '401',
            'message'   => 'faltan parametros',
            'data'      => [],
        ]; 
    }
    header('Content-Type: application/json');
    $result = json_encode($result);
    
    echo $result;
    die();