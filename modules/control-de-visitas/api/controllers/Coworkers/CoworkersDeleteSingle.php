<?php // target: coworkers-delete_single

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

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    $table_name = 'cvmj_coworkers';  

    # Capturamos los datos a verificar que exista el usuario
    $coworker_id      = isset($jsonObject->info_data->id)      ? cleanData(trim_double($jsonObject->info_data->id)) : "";    
    

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($coworker_id) || ! isset($coworker_id) || empty($coworker_id) )
    {
        on_exception_server_response(200,'Error. Faltan parametros.',$target);
        die();
    }

    // obtenemos el id del registro a eliminar
    $result = $ManageDB->get_table_rows(
        $table_name = $table_name,
        $filter = 'id',
        $keyword = $coworker_id,
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
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['id']) || (int)$coworker_id != (int)$result['fetched'][0]['id'] )
    {
        on_exception_server_response(403,'Error 403. Contacte al administrador de sistemas.',$target);
        die();
    }    

    # Verificamos si es necesario eliminar la imagen de perfil del servidor

    $image_b64 = isset($jsonObject->info_data->image_b64) ? $jsonObject->info_data->image_b64 : "";
    $image_src = isset($jsonObject->info_data->image_src) ? $jsonObject->info_data->image_src : "";
    
    // procedemos a eliminar la imagen de perfil del servidor    
    $array_photo_path = (array)json_decode($result['fetched'][0]['photo_path']);
    $photo_path = "";

    if (isset($array_photo_path['filename']) && strlen($array_photo_path['filename']) > 0 )
    {

        $photo_path = $array_photo_path['path'] . $array_photo_path['filename'];
    }
    
    if ( empty($image_b64) && isset($image_src) && ($image_src != $photo_path) )
    {        

        if( is_file($photo_path) )
        {
            $file_was_deleted = unlink($photo_path);
        }
        
    }

    $table_name = 'cvmj_coworkers';    
    
    # limpiamos el path donde esta guardad la foto
    $photo_path = '{"path" : "", "filename" : "", "public_url":""}';

    $array_new_post_data = [
        'id'         => $coworker_id,        
        'photo_path' => $photo_path,
        'id_state'   => '3', // 3: registro eliminado
    ];
        
    // actualizamos los valores en la tabla
    $result = $ManageDB->update( $table_name , $array_new_post_data );

    // si la respuesta no esta seteado o es false
    if ( !isset($result) || !$result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }
    
    $result = [];

    $response = [
        'status'    => '200',
        'message'   => 'Registro Eliminado',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;