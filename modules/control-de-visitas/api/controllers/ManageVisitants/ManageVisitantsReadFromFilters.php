<?php // target : manage_visitants-read_fromfilters


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


    $ident_number   = isset($jsonObject->info_data->ident_number)   ? $jsonObject->info_data->ident_number : "";
    $ident_type_id  = isset($jsonObject->info_data->ident_type_id)  ? $jsonObject->info_data->ident_type_id : "1";
    $selected_page  = isset($jsonObject->selected_page)             ? $jsonObject->selected_page : "1";
  
    
    $filter = ['ident_type_id','ident_number','visit_state'];
    $keyword = [$ident_type_id, $ident_number,'1'];

    if (empty($ident_number))
    {
        $filter = "visit_state";
        $keyword = "1";
    }
    
    $table_name = 'cvmj_view_visitant_and_visit';

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = $table_name,
        $filter = $filter,
        $keyword = $keyword,            
        $limit = 20,
        $selected_page = $selected_page,
        $array_fields = false,
        $order_by = 'cw_raw_full_name',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['visit_id']) )
    {
        on_exception_server_response(200,'no data',$target);
        die();
    }

    // copiamos el array para utilizarlo como contador
    $arr = $result['fetched'];

    // removemos del resultado los campos que no requemos mostrar en la respuesta
    for ($i=0; $i < count($arr); $i++) { 

        // si el estado de la fila no es igual que 1 removerla del array
        if ($result['fetched'][$i]['visit_state'] != 1)
        {
            unset($result['fetched'][$i]);            
        }
        // de lo contrario solo remueve las siguientes propiedades
        else        
        {
            unset($result['fetched'][$i][0]);
            unset($result['fetched'][$i]['visit_id']);
            
            unset($result['fetched'][$i][2]);
            unset($result['fetched'][$i]['started_at']);

            unset($result['fetched'][$i][3]);
            unset($result['fetched'][$i]['ended_at']);
            
            unset($result['fetched'][$i][14]);
            unset($result['fetched'][$i]['week_day_id']);

            unset($result['fetched'][$i][15]);
            unset($result['fetched'][$i]['week_day']);

            unset($result['fetched'][$i][16]);
            unset($result['fetched'][$i]['coworker_id']);

            unset($result['fetched'][$i][17]);
            unset($result['fetched'][$i]['cw_name']);

            unset($result['fetched'][$i][18]);
            unset($result['fetched'][$i]['cw_last_name']);

            unset($result['fetched'][$i][20]);
            unset($result['fetched'][$i]['level_access_id']);

            unset($result['fetched'][$i][21]);
            unset($result['fetched'][$i]['level_access']);

            unset($result['fetched'][$i][25]);
            unset($result['fetched'][$i]['reason_of_visit_id']);

            unset($result['fetched'][$i][26]);
            unset($result['fetched'][$i]['reason_of_visit']);            

            unset($result['fetched'][$i][29]);
            unset($result['fetched'][$i]['gun_license']);

            unset($result['fetched'][$i][30]);
            unset($result['fetched'][$i]['start_comments']);

            unset($result['fetched'][$i][32]);
            unset($result['fetched'][$i]['visit_state']);

            unset($result['fetched'][$i][33]);
            unset($result['fetched'][$i]['job_department_id']);

            unset($result['fetched'][$i][35]);
            unset($result['fetched'][$i]['raw_coworker_dpt_id']);

            unset($result['fetched'][$i][37]);
            unset($result['fetched'][$i]['floor_location_id']);

            unset($result['fetched'][$i][39]);
            unset($result['fetched'][$i]['job_title']);            
                       
            unset($result['fetched'][$i][40]);
            unset($result['fetched'][$i]['phone_extension']);

            unset($result['fetched'][$i][41]);
            unset($result['fetched'][$i]['job_email']);

            unset($result['fetched'][$i][42]);
            unset($result['fetched'][$i]['id_state']);

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

