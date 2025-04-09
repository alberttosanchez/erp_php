<?php // target : register_visitants-put_single

/**
 * Verificamos que la informacion provenga de un formulario valido por el id de session de php
 */

    $form_id = isset($jsonObject->form_id) ? $jsonObject->form_id : "";

    if ( $form_id == null || $form_id == "" || $form_id != session_id() )
    {
        on_exception_server_response(409,'Faltan parametros',$target); 
        die();
    }

    /** Procedemos a verificar que el usuario sea administrador o soporte */

    /* if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
    {
        // Si el usuario no es administrador o soporte entonces resultado 400.
        on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
        die();
    } */
    
    # Capturamos los datos a verificar
    $id_visitant   = isset($jsonObject->info_data->id_visitant)   ? cleanData(trim_double($jsonObject->info_data->id_visitant)) : "";
    $ident_number  = isset($jsonObject->info_data->ident_number)  ? cleanData(trim_double($jsonObject->info_data->ident_number)) : "";
    $ident_type_id = isset($jsonObject->info_data->ident_type_id) ? cleanData(trim_double($jsonObject->info_data->ident_type_id)) : "1";
    
    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($ident_number) || ! isset($ident_type_id) || empty($ident_number) || empty($ident_type_id) )
    {
        on_exception_server_response(200,'Error. Faltan parametros.',$target);
        die();
    }

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    $table_name = 'cvmj_identification_type';  

    if(isset($id_visitant) && !empty($id_visitant))
    {
        $filter = 'id_visitant';
        $keyword = $id_visitant;
    }
    else
    {
        $filter = [ 'ident_number','ident_type_id'];
        $keyword = [ $ident_number , $ident_type_id];
    }

    // obtenemos el id del nuevo registro creado    
    $result = $ManageDB->get_table_rows(
        $table_name = $table_name,
        $filter = $filter,
        $keyword = $keyword,
        $limit = 1,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched esta seteado y el array esta vacio
    if ( isset($result['fetched'][0]['ident_number']) && $result['fetched'][0]['ident_number'] == $ident_number )
    {
        // El visitante ya esta registrado        
        $id_visitant = isset($result['fetched'][0]['id_visitant']) ? $result['fetched'][0]['id_visitant'] : "";


        /** Verificamos si el Visitante tiene una visita activa */
        
        $filter = ["visitant_id", "visit_state"];
        $keyword = [ $id_visitant, 1]; // 1: visitante activo

        // obtenemos los datos de la institucion
        $result = $ManageDB->get_table_rows(
            $table_name = 'cvmj_visit_info',
            $filter = $filter,
            $keyword = $keyword,            
            $limit = 1,
            $selected_page = '1',
            $array_fields = false,
            $order_by = 'id',
            $order_dir = 'ASC',
            $filter_between = "",
            $array_between = false,
            $strict_mode = true
        );
        
        
        // si el array fetched esta seteado y el array NO esta vacio
        if ( isset($result['fetched']) && count($result['fetched']) > 0 || isset($result['fetched'][0]['visitant_id']) )
        {
            on_exception_server_response(200,'El visitante esta activo.',$target);
            die();
        }



    }

    # Convertimos la foto b64 en una imagen y la guardamos en el servidor

    $base64_image = isset($jsonObject->info_data->base64data) ? $jsonObject->info_data->base64data : "";

    if ( isset($base64_image) && !empty($base64_image) ) {
        
        // formatos admitidos de imagen
        $type = [ 'jpg', 'jpeg', 'png' ];

        // Comprobar el formato de la imagen (PNG, JPG, etc.)
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
            $type = strtolower($type[1]); // jpg, png, gif, bmp, etc.
    
            // Eliminar la parte de la cadena base64 para dejar solo los datos
            $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
    
            // Decodificar la imagen
            $base64_image = base64_decode($base64_image);
    
            // Comprobar si la decodificación fue exitosa                      
            if ( $base64_image === false )
            {
                on_exception_server_response(409,'Error b64. Contacte al administrador de sistemas.',$target);
                die();
            }
    
            // Crear un nombre único para la imagen
            $new_empty_filename = $user_id .'-' . uniqid() . '.' . $type;
            
            // Obtener la fecha actual en el formato Y-m-d
            $current_date = date("Y-m-d");

            // Separar la fecha en año, mes y día
            list($anno, $month, $day) = explode("-", $current_date);

            // ruta donde se guarda la foto del perfil del colaborador (coworker)            
            $directory = CV_UPLOADS_PUBLIC_FILE_DIR_PATH . 'cv_files/visitants/' . $anno .'/' . $month . '/' . $day . '/';

            // ruta publica 
            $public_url = URL_BASE . '/public/cv_files/visitants/' . $anno .'/' . $month . '/' . $day . '/';

            // Verificar si el directorio existe
            if (!is_dir($directory)) {
                // Intentar crear el directorio
                if (!mkdir($directory, 0777, true)) {
                    on_exception_server_response(409,'Error 409. Contacte al administrador de sistemas.',$target);
                    die();
                }
            }

            // instanciamos la clase ManageDB   
            //$Files = new Library\Classes\Files;

            /**
             * mueve un array de archivos a la ubicacion indicada, los renombra de ser necesario
             * Devuelve un array con las rutas de los archivos movidos de los contrario false
             */
            //$result = $Files->recursive_move_uploaded_file($files_path,$assoc_name);
           
    
            // Escribir los datos de la imagen en un archivo
            $result = file_put_contents( $directory . $new_empty_filename, $base64_image );
    
            if ($result)
            {
                $json_photo_path = '{"path" : "' . $directory . '", "filename" : "'. $new_empty_filename .'", "public_url" : "'. $public_url .'"}';
                $photo_path_was_changed = true;
            }
        } 

    } 

    # Capturamos los datos a insertar o actualizar, segun el caso
    
    $name                   = isset($jsonObject->info_data->name)                   ? cleanData(trim_double($jsonObject->info_data->name)) : "";
    $last_name              = isset($jsonObject->info_data->last_name)              ? cleanData(trim_double($jsonObject->info_data->last_name)) : "";
    $gender_id              = isset($jsonObject->info_data->gender_id)              ? cleanData(trim_double($jsonObject->info_data->gender_id)) : "1";
    $birth_date             = isset($jsonObject->info_data->birth_date)             ? cleanData(trim_double($jsonObject->info_data->birth_date)) : "";
        
    
    # Procedemos a elimina la imagen antigua de la base de datos, si es el caso
    
    // obtenemos el photo_path de la base de datos si el visitante ya existe
    if(isset($id_visitant) && !empty($id_visitant))
    {
        $table_name = 'cvmj_visitants';

        $filter = 'id';
        $keyword = $id_visitant;

        // obtenemos el id del nuevo registro creado    
        $result = $ManageDB->get_table_rows(
            $table_name = $table_name,
            $filter = $filter,
            $keyword = $keyword,
            $limit = 1,
            $selected_page = '1',
            $array_fields = false,
            $order_by = 'id',
            $order_dir = 'ASC',
            $filter_between = "",
            $array_between = false,
            $strict_mode = true
        );

        
        // si el array fetched esta seteado y el array esta vacio
        if ( ! isset($result['fetched']) || count($result['fetched']) < 1 )
        {
            on_exception_server_response(500,'Error 500-1. Contacte al administrador de sistemas.',$target);
            die();
        }
        
        $photo_path_from_db = isset($result['fetched'][0]['photo_path']) ? $result['fetched'][0]['photo_path'] :'{"path" : "", "filename" : "", "public_url":""}';
    
        
        $image_src = isset($jsonObject->info_data->image_src) ? $jsonObject->info_data->image_src : "";
        
        // procedemos a eliminar la imagen de perfil del servidor    
        $array_photo_path = (array)json_decode($photo_path_from_db);

        $photo_path = "";        
        if (isset($array_photo_path['filename']) && strlen($array_photo_path['filename']) > 0 )
        {
    
            $photo_path = $array_photo_path['path'] . $array_photo_path['filename'];
        }        
        
        if ( 
            // elimina la imagen guardada si hay una imagen blob o una cadena especifica
            /* (isset($image_src) && strpos($image_src,'blob:') > -1) ||
            $image_src == 'http://ijoven.juventud.local/control-de-visitas/register' */
            isset($base64_image) && !empty($base64_image) 
            || (isset($image_src) && strpos($image_src,'blob:') > -1) 
            || $image_src == 'http://ijoven.juventud.local/control-de-visitas/register'
         )
        {        
            
            if( is_file($photo_path) )
            {
                $file_was_deleted = unlink($photo_path);                
                $photo_path_was_changed = true;
            }
            
            
        }
        else
        {
            $photo_path_was_changed = false;
        }   

    }
    

    $array_new_post_data = [
        'id'                        => $id_visitant,
        'name'                      => $name,
        'last_name'                 => $last_name,        
        'gender_id'                 => (int)$gender_id,                     
        'birth_date'                => $birth_date,        
    ];
    
    // si tienes el id_visitant entonces actualiza un registro en la tabla de visitantes
    if ( isset($id_visitant) && !empty($id_visitant) )
    {

        if ( isset($photo_path_was_changed) && $photo_path_was_changed )
        {
            # Obtenemos el path donde esta guardad la foto
            $photo_path = isset($json_photo_path) ? $json_photo_path  : '{"path" : "", "filename" : "", "public_url":""}';
            $array_new_post_data['photo_path'] = $photo_path;
        }

        $table_name = 'cvmj_visitants';
        
        // actualizamos los valores en la tabla, devuelve true o false
        $result = $ManageDB->update( $table_name , $array_new_post_data );
        
    }
    // si no tienes el id_visitant entonces inserta un nuevo registro en la tabla de visitantes
    else if ( isset($id_visitant) && empty($id_visitant) )
    {
        unset($array_new_post_data['id']);

        $table_name = 'cvmj_visitants';
                
        // insertamos los valores en la tabla, devuelve true o false
        $result = $ManageDB->insert( $table_name , $array_new_post_data );
        # obtenemos el nuevo registro insertado o el existen mediante los datos identificacion.

        $table_name = 'cvmj_visitants';  

        // obtenemos el id del nuevo registro creado    
        $result = $ManageDB->get_table_rows(
            $table_name = $table_name,
            $filter = "",
            $keyword = "",
            $limit = 1,
            $selected_page = '1',
            $array_fields = false,
            $order_by = 'id',
            $order_dir = 'DESC',
            $filter_between = "",
            $array_between = false,
            $strict_mode = true
        );

        // si el array fetched esta seteado y el array esta vacio
        if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['id']) )
        {
            on_exception_server_response(500,'Error 500-2. Contacte al administrador de sistemas.',$target);
            die();
        }

        $new_id_visitant = $result['fetched'][0]['id'];

    }
    

    // si el resultado es falso, hay in error desconocido
    if( !$result )
    {
        on_exception_server_response(500,'Error 500-3. Contacte al administrador de sistemas.',$target);
        die();
    }

    // si el visitante es nuevo insertamos los datos de identificacion
    if (isset($new_id_visitant) && !empty($new_id_visitant) )
    {
        
        $table_name = 'cvmj_identification_type';
    
        $array_new_post_data = [
            'id_visitant'  =>  $new_id_visitant,
            'ident_number'  => $ident_number,
            'ident_type_id' => $ident_type_id,                    
        ];
            
        // actualizamos los valores en la tabla
        $result = $ManageDB->insert( $table_name , $array_new_post_data );
        
    }
    
    # Procedemos a insertar los datos de la visita
    
    $table_name = 'cvmj_visit_info';    

    # Capturamos los datos a insertar
    
    $week_day_id            = isset($jsonObject->info_data->week_day_id)            ? cleanData(trim_double($jsonObject->info_data->week_day_id)) : "";
    
    $visitant_id            = ( isset($new_id_visitant) && !empty($new_id_visitant) ) ? $new_id_visitant : $id_visitant;

    // se coloca el id 1, debido a que no se esta registrando los colaboradores almacenados, 
    // sino que se pone manual a quien se esta visitando
    $coworker_id            = 1; 
    
    $raw_coworker_full_name = isset($jsonObject->info_data->raw_coworker_full_name) ? cleanData(trim_double($jsonObject->info_data->raw_coworker_full_name)) : "";
    $raw_coworker_dpt_id    = isset($jsonObject->info_data->raw_coworker_dpt_id)    ? cleanData(trim_double($jsonObject->info_data->raw_coworker_dpt_id)) : "";
    $level_access_id        = isset($jsonObject->info_data->level_access_id)        ? cleanData(trim_double($jsonObject->info_data->level_access_id)) : "1";
    $has_gun                = isset($jsonObject->info_data->has_gun)                ? cleanData(trim_double($jsonObject->info_data->has_gun)) : "1";
    $gun_status_id          = isset($jsonObject->info_data->gun_status_id)          ? cleanData(trim_double($jsonObject->info_data->gun_status_id)) : "1";
    $reason_of_visit_id     = isset($jsonObject->info_data->reason_of_visit_id)     ? cleanData(trim_double($jsonObject->info_data->reason_of_visit_id)) : "";
    $license_number         = isset($jsonObject->info_data->license_number)         ? cleanData(trim_double($jsonObject->info_data->license_number)) : "";
    $license_type_id        = isset($jsonObject->info_data->license_type_id)        ? cleanData(trim_double($jsonObject->info_data->license_type_id)) : "7"; // 7: no aplica    
    $license_type_id        = empty($license_type_id) ? 7 : $license_type_id;
    $start_comments         = isset($jsonObject->info_data->start_comments)         ? cleanData(trim_double($jsonObject->info_data->start_comments)) : "";
    
    $array_new_post_data = [
        'week_day_id'            => $week_day_id,
        'visitant_id'            => $visitant_id,        
        'coworker_id'            => $coworker_id,             
        'raw_coworker_full_name' => $raw_coworker_full_name,
        'raw_coworker_dpt_id'    => $raw_coworker_dpt_id,        
        'level_access_id'        => $level_access_id,  
        'has_gun'                => $has_gun,  
        'gun_status_id'          => $gun_status_id,  
        'reason_of_visit_id'     => $reason_of_visit_id,  
        'license_number'         => $license_number,  
        'license_type_id'        => $license_type_id,  
        'start_comments'         => $start_comments,  
        'visit_state'            => 1, // 1 : visitante activo  
    ];
        
    //var_dump($array_new_post_data); die();
    // actualizamos los valores en la tabla
    $result = $ManageDB->insert( $table_name , $array_new_post_data );
    // si la respuesta no esta seteado o es false
    if ( !isset($result) || !$result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    $result = [];

    $response = [
        'status'    => '200',
        'message'   => 'Datos Guardados',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;