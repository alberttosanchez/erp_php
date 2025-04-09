<?php // plant_distribution-read_singleplantinfo

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


    $filter         = isset($jsonObject->info_data->filter)  ? $jsonObject->info_data->filter   : "";
    $keyword        = isset($jsonObject->info_data->keyword) ? $jsonObject->info_data->keyword  : "";
    $selected_page  = isset($jsonObject->selected_page)      ? $jsonObject->selected_page       : "1";

    // obtenemos los datos de la configuracion
    $result = $ManageDB->get_table_rows(
        $table_name = 'cvvw_plant_distribution',
        $filter = $filter,
        $keyword = $keyword,            
        $limit = 1,
        $selected_page = $selected_page,
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched no esta seteado y el array esta vacio
    if ( ! isset($result['fetched']) || count($result['fetched']) < 1 || !isset($result['fetched'][0]['department']) )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // enviamos la respuesta y los datos obtenidos
    $response = [
        'status'    => '200',
        'message'   => 'datos obtenidos',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;