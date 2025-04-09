<?php // target : register_visitants-read_single

    /**
     * Verificamos que la informacion provenga de un formulario valido por el id de session de php
     */
    $form_id = isset($jsonObject->form_id) ? $jsonObject->form_id : "";

    if ( $form_id == null || $form_id == "" || $form_id != session_id() )
    {
        on_exception_server_response(409,'Faltan parametros',$target); 
        die();
    }    
    
    /** Procedemos a obtener los datos de la base de datos mediante el id del post */

    # Capturamos los datos recibidos por fetch

    $ident_number         = isset($jsonObject->info_data->ident_number)  ? $jsonObject->info_data->ident_number : "";
    $ident_type_id        = isset($jsonObject->info_data->ident_type_id) ? $jsonObject->info_data->ident_type_id : "";

    $filter  = [ 'ident_number' , 'ident_type_id' ];
    $keyword = [  $ident_number , $ident_type_id  ];

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvmj_identification_type',
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

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['ident_number']) )
    {
        on_exception_server_response(200,'Usuario no registrado.',$target);
        die();
    }

    $visitant_id = (int)$result['fetched'][0]['id_visitant'];

    //var_dump($visitant_id); die();

    $filter = ["visitant_id", "visit_state"];
    $keyword = [ $visitant_id, 1]; // 1: visitante activo

    /** Verificamos si el Visitante tiene una visita activa */
    
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
        
    // si el array fetched no esta seteado y el array esta vacio
    if ( isset($result['fetched']) && count($result['fetched']) > 0 || isset($result['fetched'][0]['visitant_id']) )
    {
        on_exception_server_response(200,'El visitante esta activo.',$target);
        die();
    }

    /** En este punto el usuario esta registrado pero no tiene visita activa */
    
    $filter = 'id_visitant';
    $keyword = $visitant_id;

    /** Obtenemos los datos del usuario registrado */
         
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvvw_visitant_info',
        $filter = $filter,
        $keyword = $keyword,            
        $limit = 1,
        $selected_page = $selected_page,
        $array_fields = false,
        $order_by = 'id_visitant',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['id_visitant']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // si el estado del usuario es mayor que 1
    if ( isset($result['fetched'][0]['id_state']) && $result['fetched'][0]['id_state'] > 1 )
    {
        on_exception_server_response(200,'Usuario Deshabilitado.',$target);
        die();
    }

    // copiamos el array para utilizarlo como contador
    $arr = $result['fetched'];

    // removemos del resultado los campos que no requemos mostrar en la respuesta
    for ($i=0; $i < count($arr); $i++) { 

        // si el estado de la fila es mayor que 1 removerla del array
        if ($result['fetched'][$i]['id_state'] > 1)
        {
            unset($result['fetched'][$i]);            
        }
        // de lo contrario solo remueve las siguientes propiedades
        else        
        {
            unset($result['fetched'][$i][1]);
            unset($result['fetched'][$i]['created_at']);
            unset($result['fetched'][$i][2]);
            unset($result['fetched'][$i]['updated_at']);
            unset($result['fetched'][$i][5]);
            unset($result['fetched'][$i]['id_state']);
        }
    }

    // reindexamos el array 
    $result['fetched'] = array_values($result['fetched']);
    
    // enviamos la respuesta y los datos obtenidos
    $response = [
        'status'    => '200',
        'message'   => 'Usuario registrado.',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;