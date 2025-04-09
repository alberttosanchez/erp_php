<?php // target : manage_visitants-finalize_all_visits

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
    
    $table_name = 'cvmj_visit_info';
    
    // cambiar visit_state 1 a visit_state 0
    
    $array_new_post_data = [        
        'visit_state'   => '0',        
    ];  
    
    $array_where = [
        'visit_state'   => '1',        
    ];

    // obtemos los id de visitantes seleccionados para finalizzar la visita

    $selected_visitants = isset($jsonObject->info_data->selected_visitants) ? (array)$jsonObject->info_data->selected_visitants : [];

    if(is_array($selected_visitants) && count($selected_visitants) > 0)
    {

        function recursiveUpdateAll($table_name,$array_new_post_data,$array_where,$selected_visitants,$counter = 0)
        {            
            $c = $counter;
            
            $arr_len = count($selected_visitants);
            
            if ( $c < $arr_len )
            {
                // instanciamos la clase ManageDB   
                $ManageDB = new Library\Classes\ManageDB;

                $arr_current_visitant = (array)$selected_visitants[$c];
                $array_where['visitant_id'] = $arr_current_visitant['id_visitant'];

                //var_dump($array_where); //die();

                // TENER PRECAUCION AL USAR ESTE METODO PUEDE ACTUALIZAR TODOS LOS REGISTROS DE UNA TABLA.
                // actualizamos todos las filas seleccionadas en una tabla
                $result = $ManageDB->updateAll( $table_name , $array_new_post_data, $array_where );
                //$result = true;
            }

            $counter++;

            if(isset($result) && $result)
            {
                return recursiveUpdateAll($table_name,$array_new_post_data,$array_where,$selected_visitants,$counter);
            }

            return 'done';
        }
        $counter = 0;
        $result = recursiveUpdateAll($table_name,$array_new_post_data,$array_where,$selected_visitants,$counter);

        if ($result == 'done') { $result = true; }
         
    }
    else
    {
        //var_dump($array_where); die();

        // TENER PRECAUCION AL USAR ESTE METODO PUEDE ACTUALIZAR TODOS LOS REGISTROS DE UNA TABLA.    
        // actualizamos todos las filas en una tabla
        $result = $ManageDB->updateAll( $table_name , $array_new_post_data, $array_where );

    }
    
    // si la respuesta no esta seteado o es false
    if ( !isset($result) || !$result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    $result = [];

    $response = [
        'status'    => '200',
        'message'   => 'Visitas Finalizadas',
        'data'      => $result
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;

