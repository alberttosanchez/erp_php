<?php // target : manage_visitants-finalize_visit

    /** Recuperamos la data a partir de los filtros recibido
     *  Id, Departamento o area, Ubicacion, Nivel de Acceso Requerido, Accion
     */

    /** Verificamos que la informacion provenga de un formulario valido por el id de session de php */

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

    /** Procedemos a obtener los datos de la base de datos mediante el id del post */

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    # Capturamos los datos a insertar

    $visit_id       = isset($jsonObject->info_data->id)   ? $jsonObject->info_data->id : "";
    $gun_status_id  = isset($jsonObject->info_data->gun_status_id)  ? $jsonObject->info_data->gun_status_id : "1";    
    $visit_state    = "0"; // 0 : Visita finalizada
    
    
    $table_name = 'cvmj_visit_info';
    
    $array_new_post_data = [
        'id'            => $visit_id,
        'gun_status_id' => $gun_status_id,        
        'visit_state'   => $visit_state,
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
        'message'   => 'Visita Finalizada',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;

