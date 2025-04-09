<?php // general_settings-update_settings

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

    # Capturamos los datos a actualizar
    
    $printer_id_status = isset($jsonObject->info_data->printer_id_status) ? cleanData(trim_double($jsonObject->info_data->printer_id_status)) : "2";

    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;

     // obtenemos los datos del ultimo registro de la tabla
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvmj_setting',
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

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    $printer_id = $result['fetched'][0]['id'];

    $table_name = 'cvmj_setting';    
    
    $array_new_post_data = [
        'id'                    => $printer_id,
        'printer_id_status'     => $printer_id_status,        
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
        'message'   => 'Datos Actualizado',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;