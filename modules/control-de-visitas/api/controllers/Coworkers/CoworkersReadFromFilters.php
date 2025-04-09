<?php // target : coworkers-read_fromfilters


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

    if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
    {
        // Si el usuario no es administrador o soporte entonces resultado 400.
        on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
        die();
    }

    /** Procedemos a obtener los datos de la base de datos mediante el id del post */

    // instanciamos la clase ManageDB   
    $ManageDB = new Library\Classes\ManageDB;


    $filter         = isset($jsonObject->info_data->filter)  ? $jsonObject->info_data->filter : "";
    $keyword        = isset($jsonObject->info_data->keyword) ? $jsonObject->info_data->keyword : "";
    $selected_page  = isset($jsonObject->selected_page)      ? $jsonObject->selected_page : "1";

    switch ($filter) {
        case "1": $filter = 'id'; break;
        case "2": $filter = 'identification_id'; break;
        case "3": $filter = 'name'; break;
        case "4": $filter = 'last_name'; break;        
        case "5": $filter = 'gender'; break;        
        case "6": $filter = 'birth_date'; break;        
        default : break;
    };      
    
    $order_by = 'id';

    if (!empty($filter) && empty($keyword))
    {
        $order_by = $filter;
    }
    
    /* if ( empty($filter) )
    {
        $filter = 'id_state';
        $keyword = 1;
    }
    else if ( !empty($filter) && !empty($keyword) )
    {
        $filter  = [$filter , 'id_state' ];
        $keyword = [$keyword, 1 ];        
    }
    else if ( !empty($filter) && empty($keyword) )
    {
        $order_by = $filter;        
    }

    if ( (!empty($filter) && !is_array($filter) ) && empty($keyword) )
    {
        $order_by = $filter;
        $filter = "";
    } 
    else if ( (!empty($filter) && is_array($filter) ) && empty($keyword) )
    {
        $order_by = $filter[0];
        $filter = "";
    } */

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvvw_coworker_info',
        $filter = $filter,
        $keyword = $keyword,            
        $limit = 1000,
        $selected_page = $selected_page,
        $array_fields = false,
        $order_by = $order_by,
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['identification_id']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
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
        'message'   => 'Datos Recuperados',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;
