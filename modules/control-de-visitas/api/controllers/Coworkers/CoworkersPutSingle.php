<?php // target: coworkers-put_single

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

    if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
    {
        // Si el usuario no es administrador o soporte entonces resultado 400.
        on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
        die();
    }

    # Convertimos la foto b64 en una imagen y la guardamos en el servidor

    $base64_image = isset($jsonObject->info_data->image_b64) ? $jsonObject->info_data->image_b64 : "";

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
            $directory = CV_UPLOADS_PUBLIC_FILE_DIR_PATH . 'cv_files/coworkers/profile/' . $anno .'/' . $month . '/' . $day . '/';

            // ruta publica 
            $public_url = URL_BASE . '/public/cv_files/coworkers/profile/' . $anno .'/' . $month . '/' . $day . '/';


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
            }
        } 

    } 
    
    

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    $table_name = 'cvmj_coworkers';  

    # Capturamos los datos a verificar
    $identification_id      = isset($jsonObject->info_data->identification_id)      ? cleanData(trim_double($jsonObject->info_data->identification_id)) : "";
    $identification_type_id = isset($jsonObject->info_data->identification_type_id) ? cleanData(trim_double($jsonObject->info_data->identification_type_id)) : "1";
    
    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($identification_id) || ! isset($identification_type_id) || empty($identification_id) || empty($identification_type_id) )
    {
        on_exception_server_response(200,'Error. Faltan parametros.',$target);
        die();
    }

    // obtenemos el id del nuevo registro creado    
    $result = $ManageDB->get_table_rows(
        $table_name = $table_name,
        $filter = ['identification_id','identification_type_id'],
        $keyword = [ $identification_id , $identification_type_id],
        $limit = 1,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( isset($result['fetched'][0]['identification_id']) && $result['fetched'][0]['identification_id'] == $identification_id )
    {
        on_exception_server_response(200,'El colaborador ya esta registrado.',$target);
        die();
    }

    # Capturamos los datos a insertar
    $name                   = isset($jsonObject->info_data->name)                   ? cleanData(trim_double($jsonObject->info_data->name)) : "";
    $last_name              = isset($jsonObject->info_data->last_name)              ? cleanData(trim_double($jsonObject->info_data->last_name)) : "";
    $gender_id              = isset($jsonObject->info_data->gender_id)              ? cleanData(trim_double($jsonObject->info_data->gender_id)) : "1";
    $birth_date             = isset($jsonObject->info_data->birth_date)             ? cleanData(trim_double($jsonObject->info_data->birth_date)) : "";
    
    # Obtenemos el path donde esta guardad la foto
    $photo_path             = isset($json_photo_path) ? $json_photo_path  : '{"path" : "", "filename" : "", "public_url":""}';

    $table_name = 'cvmj_coworkers';    
    
    $array_new_post_data = [
        'name'                      => $name,
        'last_name'                 => $last_name,        
        'gender_id'                 => $gender_id,             
        'identification_id'         => $identification_id,
        'identification_type_id'    => $identification_type_id,
        'birth_date'                => $birth_date,
        'photo_path'                => $photo_path,
    ];
        
    // actualizamos los valores en la tabla
    $result = $ManageDB->insert( $table_name , $array_new_post_data );

    // si la respuesta no esta seteado o es false
    if ( !isset($result) || !$result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // obtenemos el id del nuevo registro creado    
    $result = $ManageDB->get_table_rows(
        $table_name = $table_name,
        $filter = 'identification_id',
        $keyword = $identification_id,            
        $limit = 1,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['id']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // insertamos los demas datos en la otra trabla
    $coworker_id = $result['fetched'][0]['id'];

    $table_name = 'cvmj_job_info';

    # Capturamos los datos a insertar
    
    $job_department_id  = isset($jsonObject->info_data->job_department_id)  ? cleanData(trim_double($jsonObject->info_data->job_department_id)) : "";
    $job_title          = isset($jsonObject->info_data->job_title)          ? cleanData(trim_double($jsonObject->info_data->job_title)) : "";
    $phone_extension    = isset($jsonObject->info_data->phone_extension)    ? cleanData(trim_double($jsonObject->info_data->phone_extension)) : "1";
    $job_email          = isset($jsonObject->info_data->job_email)          ? cleanData(trim_double($jsonObject->info_data->job_email)) : "";
    
    $array_new_post_data = [
        'coworker_id'       => $coworker_id,
        'job_department_id' => $job_department_id,        
        'job_title'         => $job_title,             
        'phone_extension'   => $phone_extension,
        'job_email'         => $job_email,        
    ];
        
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