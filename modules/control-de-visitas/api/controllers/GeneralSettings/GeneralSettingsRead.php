<?php // target : general_settings-read

    //var_dump($jsonObject);

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
    
    /** Procedemos a obtener los datos de la base de datos mediante el id del post */

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvmj_setting',
        $filter = "id",
        $keyword = 1,            
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
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['printer_id_status']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    $printer_id_status = (int)$result['fetched'][0]['printer_id_status'];

    // obtenemos los datos de la institucion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvmj_business_info',
        $filter = "",
        $keyword = "",            
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
    if ( ! isset($result['fetched']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // si el array fetched no esta seteado y el array esta vacio
    if ( isset($result['fetched']) && count($result['fetched']) == 0 )
    {
        

        /* 
            $result['fetched'][0]['business_name']          = "";
            $result['fetched'][0]['business_phone']         = "";
            $result['fetched'][0]['business_address']       = "";
            $result['fetched'][0]['business_zip_code']      = "";
            $result['fetched'][0]['business_floor_quanty']  = "";
            
            $counter = count((array)$result['fetched'][0]);
            for ($i=0; $i < $counter; $i++) { 
                $result['fetched'][0][$i] = "";
            }
        */


        on_exception_server_response(200,'no data',$target);
        die();
        
    }  
    
    $result['fetched'][0]['printer_id_status'] = $printer_id_status;

    // enviamos la respuesta y los datos obtenidos
    $response = [
        'status'    => '200',
        'message'   => 'Datos Recuperados',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;