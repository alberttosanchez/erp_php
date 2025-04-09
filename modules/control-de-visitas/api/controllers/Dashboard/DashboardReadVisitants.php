<?php // dashboard-read_visitants

    /** Verificamos que la informacion provenga de un formulario valido por el id de session de php */

    $form_id = isset($jsonObject->form_id) ? $jsonObject->form_id : "";

    if ( $form_id == null || $form_id == "" || $form_id != session_id() )
    {
        on_exception_server_response(409,'Faltan parametros',$target); 
        die();
    }

    /** Procedemos a obtener los datos de la base de datos mediante el id del post */

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvmj_view_visitant_and_visit',
        $filter = "",
        $keyword = "",            
        $limit = 1000,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'started_at',
        $order_dir = 'DESC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['visit_state']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }
    
    // copiamos el array para utilizarlo como contador
    $arr = $result['fetched'];

    // removemos del resultado los campos que no requemos mostrar en la respuesta
    for ($i=0; $i < count($arr); $i++) { 

        // si el estado de la fila es mayor que 1 removerla del array
        if ($result['fetched'][$i]['visit_state'] != 1)
        {
            unset($result['fetched'][$i]);            
        }
        // de lo contrario solo remueve las siguientes propiedades
        else        
        {
            // elimina del array las posiciones indexadas por numeros
            for ($u=0; $u < count($result['fetched'][$i]); $u++) {
                if ( !is_nan($u) )
                {
                    unset($result['fetched'][$i][$u]);
                };
            };

            unset($result['fetched'][$i]['visit_id']);
            //unset($result['fetched'][$i]['id_visitant']);
            //#unset($result['fetched'][$i]['started_at']);
            //#unset($result['fetched'][$i]['ended_at']);                        
            //#unset($result['fetched'][$i]['ident_number']);
            unset($result['fetched'][$i]['ident_type_id']);
            unset($result['fetched'][$i]['identification_type']);
            //#unset($result['fetched'][$i]['name']);
            //#unset($result['fetched'][$i]['last_name']);
            unset($result['fetched'][$i]['gender_id']);
            unset($result['fetched'][$i]['gender']);
            unset($result['fetched'][$i]['birth_date']);
            unset($result['fetched'][$i]['last_visit_date']);
            unset($result['fetched'][$i]['visit_info_id']);
            unset($result['fetched'][$i]['week_day_id']);
            unset($result['fetched'][$i]['week_day']);
            unset($result['fetched'][$i]['coworker_id']);
            unset($result['fetched'][$i]['cw_name']);
            unset($result['fetched'][$i]['cw_last_name']);
            //#unset($result['fetched'][$i]['cw_raw_full_name']);
            unset($result['fetched'][$i]['level_access_id']);
            unset($result['fetched'][$i]['level_access']);
            unset($result['fetched'][$i]['has_gun']);
            unset($result['fetched'][$i]['gun_status_id']);
            unset($result['fetched'][$i]['gun_status']);
            unset($result['fetched'][$i]['reason_of_visit_id']);
            unset($result['fetched'][$i]['reason_of_visit']);
            unset($result['fetched'][$i]['license_number']);
            unset($result['fetched'][$i]['license_type_id']);
            unset($result['fetched'][$i]['gun_license']);
            unset($result['fetched'][$i]['start_comments']);
            unset($result['fetched'][$i]['end_comments']);
            //#unset($result['fetched'][$i]['visit_state']);
            unset($result['fetched'][$i]['job_department_id']);
            unset($result['fetched'][$i]['department']);
            unset($result['fetched'][$i]['raw_coworker_dpt_id']);
            //#unset($result['fetched'][$i]['cw_raw_department']);
            unset($result['fetched'][$i]['floor_location_id']);
            //#unset($result['fetched'][$i]['floor_location']);
            unset($result['fetched'][$i]['job_title']);
            unset($result['fetched'][$i]['phone_extension']);
            unset($result['fetched'][$i]['job_email']);
            //#unset($result['fetched'][$i]['id_state']);            
        }
    }

    // reindexamos el array 
    $result['fetched'] = array_values($result['fetched']);

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