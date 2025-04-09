<?php

    try {

        $APACHE_HEADERS = apache_request_headers();        

        if ( isset($APACHE_HEADERS["Content-Length"]) && $APACHE_HEADERS["Content-Length"] >  CV_POST_FILE_MAX_SIZE )        
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

        // var_dump($array_token_and_user_id); die();

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
        $target_lv1   = isset($array_target[0]) ? $array_target[0] : "";
        $target_lv2   = isset($array_target[1]) ? $array_target[1] : "";
                
        //var_dump($target_lv2); die();

        switch ($target_lv1) {
            case 'dashboard':                
                switch ($target_lv2) {
                    case 'read_visitants'           :  include_once('./controllers/Dashboard/DashboardReadVisitants.php'); break;
                    
                    
                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;   
            case 'register_visitants':                
                switch ($target_lv2) {
                    case 'read_single'              :  include_once('./controllers/RegisterVisitants/RegisterVisitantsReadSingle.php'); break;
                    case 'put_single'               :  include_once('./controllers/RegisterVisitants/RegisterVisitantsPutSingle.php'); break;                    
                    
                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;   
            case 'categories':                
                switch ($target_lv2) {
                    case 'read_genders'             :  include_once('./controllers/Categories/CategoriesReadGenders.php'); break;
                    case 'read_identificationtype'  :  include_once('./controllers/Categories/CategoriesReadIdentificationType.php'); break;
                    case 'read_levelaccess'         :  include_once('./controllers/Categories/CategoriesReadLevelAccess.php'); break;
                    case 'read_visitreason'         :  include_once('./controllers/Categories/CategoriesReadVisitReason.php'); break;
                    case 'read_gunstatus'           :  include_once('./controllers/Categories/CategoriesReadGunStatus.php'); break;
                    case 'read_gunslicense'         :  include_once('./controllers/Categories/CategoriesReadGunsLicense.php'); break;
                    case 'read_plantdistribution'   :  include_once('./controllers/Categories/CategoriesReadPlantDistribution.php'); break;
                    case 'read_floorlocation'       :  include_once('./controllers/Categories/CategoriesReadFloorLocation.php'); break;

                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break; 

            case 'general_settings':                
                switch ($target_lv2) {
                    case 'read'              :  include_once('./controllers/GeneralSettings/GeneralSettingsRead.php'); break;
                    case 'update_settings'   :  include_once('./controllers/GeneralSettings/GeneralSettingsUpdateSettings.php'); break;
                    case 'update_info'       :  include_once('./controllers/GeneralSettings/GeneralSettingsUpdateInfo.php'); break;
                    
                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;             
            case 'plant_distribution':
                switch ($target_lv2) {
                    case 'read_searchfilters'     : include_once('./controllers/PlantDistribution/PlantDistributionReadSearchFilters.php'); break;
                    case 'read_fromfilters'       : include_once('./controllers/PlantDistribution/PlantDistributionReadFromFilters.php'); break;
                    case 'read_levelaccess'       : include_once('./controllers/PlantDistribution/PlantDistributionReadLevelAccess.php'); break;
                    case 'read_floorlocations'    : include_once('./controllers/PlantDistribution/PlantDistributionReadFloorLocations.php'); break;                    
                    case 'read_singleplantinfo'   : include_once('./controllers/PlantDistribution/PlantDistributionReadSinglePlantInfo.php'); break;                    
                    case 'put_single'             : include_once('./controllers/PlantDistribution/PlantDistributionPutSingle.php'); break;                    
                    case 'update_single'          : include_once('./controllers/PlantDistribution/PlantDistributionUpdateSingle.php'); break;                    
                    case 'del_single'             : include_once('./controllers/PlantDistribution/PlantDistributionDeleteSingle.php'); break;
                    case 'read_genders'           : include_once('./controllers/PlantDistribution/PlantDistributionReadGenders.php'); break;
                    case 'read_identificationtype': include_once('./controllers/PlantDistribution/PlantDistributionReadIdentificationType.php'); break;
                    case 'read_plantdistribution' : include_once('./controllers/PlantDistribution/PlantDistributionReadPlantDistribution.php'); break;

                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;
            case 'coworkers':                
                switch ($target_lv2) {
                    case 'read_fromfilters'       : include_once('./controllers/Coworkers/CoworkersReadFromFilters.php'); break;                    
                    case 'put_single'             : include_once('./controllers/Coworkers/CoworkersPutSingle.php'); break;
                    case 'update_single'          : include_once('./controllers/Coworkers/CoworkersUpdateSingle.php'); break;
                    case 'delete_single'          : include_once('./controllers/Coworkers/CoworkersDeleteSingle.php'); break;
                    
                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;
            case 'manage_visitants':                
                switch ($target_lv2) {
                    case 'read_fromfilters'       : include_once('./controllers/ManageVisitants/ManageVisitantsReadFromFilters.php'); break;
                    case 'finalize_visit'         : include_once('./controllers/ManageVisitants/ManageVisitantsFinalizeVisit.php'); break;
                    case 'finalize_all_visits'    : include_once('./controllers/ManageVisitants/ManageVisitantsFinalizeAllVisits.php'); break;
                    
                    
                    default: on_exception_server_response(400,'Faltan parametros',$target); break;
                }                
                break;             
            default: on_exception_server_response(400,'Faltan parametros',$target); break;

        }    
        die();
    } catch(Exception $e) {
        echo 'catch Message: ' .$e->getMessage();
    }
   