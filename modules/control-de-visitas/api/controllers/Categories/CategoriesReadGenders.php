<?php // target: categories-read_genders
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
        $table_name = 'cvcat_genders',
        $filter = "",
        $keyword = "",            
        $limit = 1000,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['gender']) )
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