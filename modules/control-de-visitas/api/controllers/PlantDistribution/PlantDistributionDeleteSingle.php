<?php // target: plant_distribution-del_single

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

    # Capturamos los datos a insertar
    
    $id                 = isset($jsonObject->info_data->id)                 ? cleanData(trim_double($jsonObject->info_data->id)) : "";

    $table_name = 'cvmj_plant_distribution';    
    
    $array_new_post_data = [
        'id'                    => $id,
        'id_state'              => '3', # 3: deleted: la data solo estara disponible para reportes.        
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
        'message'   => 'Datos Actualizados',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;