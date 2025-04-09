<?php
    require_once './../admin/config.php';
    require_once './functions.php';

    // incluimos el directorio de clases
    foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename; }

    $jsonString  = file_get_contents('php://input');
    $jsonObject  = json_decode($jsonString); 

    if ( isset($jsonObject) )
    {
        $jsonObject->target         = cleanData($jsonObject->target);    
        $jsonObject->session_token  = cleanData($jsonObject->session_token);
        $jsonObject->user_id        = cleanData($jsonObject->user_id);
        $jsonObject->table_name     = cleanData($jsonObject->table_name);
        $jsonObject->selected_page  = cleanData(isset($jsonObject->selected_page) ? $jsonObject->selected_page : 1);
        $jsonObject->filter         = cleanData(isset($jsonObject->filter)  ? $jsonObject->filter  : "");
        $jsonObject->keyword        = cleanData(isset($jsonObject->keyword) ? $jsonObject->keyword : "");
        $jsonObject->strict_mode    = cleanData(isset($jsonObject->strict_mode) ? $jsonObject->strict_mode : false);
        
        $target         = $jsonObject->target;
        $session_token  = $jsonObject->session_token;
        $user_id        = $jsonObject->user_id;
        $table_name     = $jsonObject->table_name;
        $filter         = $jsonObject->filter;
        $keyword        = $jsonObject->keyword;
        $strict_mode    = $jsonObject->strict_mode;
        $info_data      = isset($jsonObject->info_data) ? $jsonObject->info_data : [];
    
        $selected_page  = isset($jsonObject->selected_page) ? $jsonObject->selected_page : 1;
        $order_by       = isset($jsonObject->order_by) ? $jsonObject->order_by : 'id';
        // se guarda los datos de usuario y session en GLOBALS
        $GLOBALS['array_token_and_user_id'] = [
            'session_token' => $session_token,
            'user_id'       => $user_id
        ];

        // convierte un objeto stdClass en array
        $array_info_data = json_decode( json_encode($info_data), true);
        //var_dump($array_info_data); die();
    }


    if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST' && 
    isset($target) && !empty($target)
    )
    {
        // instanciamos la clase ManageDB        
        $ManageDB = new Library\Classes\ManageDB;
                
        if ( $ManageDB->check_session() )
        {
            if ( $target == "update" && isset($array_info_data['id']) && !empty($array_info_data['id']) )
            {
                // verificamos que la fila exista en la tabla indicada
                // devuelve true de los contrario false
                $result = $ManageDB->row_exists($table_name,$array_info_data);
                    
                if ($result == true)
                {
                    $result = $ManageDB->update($table_name,$array_info_data);
                }
    
                if ($result == true)
                {
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'datos actualizados'
                    ];
                }
                else
                {
                    http_response_code(406);
                    $result = [
                        'status'    => '406',
                        'message'   => 'Datos no actualizados.1'
                    ];
                }
            }                        
            else if ( $target == "insert" )
            {
                
                $filter = !empty($filter) ? $filter : 'id';
                $assoc_index = 'id';
                
                // devuelve true si los datos existen en la base de datos
                // se verifica mediante el id de la entidad
                $result = $ManageDB->row_exists($table_name,$array_info_data, $filter, $assoc_index);
    
                //var_dump($result); die();
    
                if ($result == false)
                {
                    // enviamos el nombre de la tabla y la data en un array asociativo
                    // el mismo contiene como clave los nombres de los campos y los valores a insertar
                    // devuelve true de lo contrario false
                    $result = $ManageDB->insert($table_name,$array_info_data);
        
                    //var_dump($result); die();
                            
                    if ($result == true)
                    {
                        http_response_code(200);
    
                        $result = [
                            'status'    => '200',
                            'message'   => 'insertado',
                            'data'      => [],
                        ];

                    }
                    else
                    {
                        http_response_code(200);
    
                        $result = [
                            'status'    => '200',
                            'message'   => 'no insertado',
                            'data'      => [],
                        ];
                    }
    
                }
                else
                {
    
                    $result = $ManageDB->update($table_name,$array_info_data);
    
                    //var_dump($result); die();
    
                    if ($result == true)
                    {
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'actualizado',
                            'data'      => [],
                        ];
                    }
                    else
                    {
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no actualizado',
                            'data'      => [],
                        ];
                    }
    
                }
                
            }
            else if ( $target == "co_register_and_get" )
            {

                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                        
                    if ( isset($item_array['identification_id']) )
                    {
                        $filter = isset($item_array['identification_id']) ? 'identification_id' : 'id';
                        $assoc_index = isset($item_array['identification_id']) ? $item_array['identification_id'] : 'id';

                    }
                    else if ( isset($array_info_data['coworker_id']) )
                    {
                        $filter = isset($array_info_data['coworker_id']) ? 'coworker_id' : 'id';
                        $assoc_index = isset($array_info_data['coworker_id']) ? $array_info_data['coworker_id'] : 'id';
                    }
                    else
                    {
                        $filter = 'id';
                        $assoc_index = 'id';
                    }

                    // devuelve true si los datos existen en la base de datos
                    // se verifica mediante el id de la entidad
                    $result = $ManageDB->row_exists($table_name,$array_info_data, $filter, $assoc_index);

                    //var_dump($result);
                    //var_dump("table_name: " . $table_name);
                    //var_dump($item_array);
                    if ($result == false)
                    {
                        // enviamos el nombre de la tabla y la data en un array asociativo
                        // el mismo contiene como clave los nombres de los campos y los valores a insertar
                        // devuelve true de lo contrario false
                        $result = $ManageDB->insert($table_name,$array_info_data);
            
                        //var_dump("insert: ".$result);
            
                        if ( $result == true )
                        {
                            $result = $ManageDB->get_table_rows(
                                $table_name,
                                $filter = "",
                                $keyword = "",
                                $limit = 1,
                                $selected_page = '1',
                                $array_fields = false,
                                $order_by = 'id',
                                $order_dir = 'DESC'
                            );

                            //var_dump($result); 
                            
                            if ( is_array($result) && count($result) > 0 )
                            {   
                                
                                http_response_code(200);
        
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'insertado',
                                    'data'      => $result,
                                ];
                            }
                            else
                            {
                                http_response_code(200);
            
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'no insertado.',
                                    'data'      => [],
                                ];
                            }
                        }                            
                        else
                        {           
                                    
                            http_response_code(200);

                            $result = [
                                'status'    => '200',
                                'message'   => 'no insertado',
                                'data'      => [],
                            ];
                        }
        
                    }
                    else
                    {
                        
                        http_response_code(200);
    
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos existentes',
                            'data'      => [],
                        ];                        
        
                    }
                    
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
                
            }
            else if ( $target == "co_update_and_get" )
            {

                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                        
                    if ( isset($array_info_data['id']) )
                    {
                        $filter = isset($array_info_data['id']) ? 'id' : 'id';
                        $assoc_index = isset($array_info_data['id']) ? $array_info_data['id'] : 'id';

                    }
                    else if ( isset($array_info_data['coworker_id']) )
                    {
                        $filter = isset($array_info_data['coworker_id']) ? 'coworker_id' : 'id';
                        $assoc_index = isset($array_info_data['coworker_id']) ? $array_info_data['coworker_id'] : 'id';
                    }
                    else
                    {
                        $filter = 'id';
                        $assoc_index = 'id';
                    }

                    // devuelve true si los datos existen en la base de datos
                    // se verifica mediante el id de la entidad
                    $result = $ManageDB->row_exists($table_name,$array_info_data, $filter, $assoc_index);
                  
                    if ($result == true)
                    {
                        //var_dump($array_info_data);
                        // actualiza un registro en una tabla de la base de datos
                        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
                        // devuelve true de los contrario false
                        $result = $ManageDB->update($table_name,$array_info_data);
            
                        //var_dump($result);
            
                        if ( $result == true )
                        {
                            $result = $ManageDB->get_table_rows(
                                $table_name,
                                $filter = '',
                                $keyword = '',
                                $limit = 1,
                                $selected_page = '1',
                                $array_fields = false,
                                $order_by = 'updated_at',
                                $order_dir = 'DESC'                           
                            );

                            //var_dump($result); 
                            
                            if ( is_array($result) && count($result) > 0 )
                            {   
                                //$row_on_table_id[$counter] = $result['fetched'][0]['id'];
                                //$row_on_table_name[$counter] = $table_name;
                                //$counter++;
                              
                                http_response_code(200);
        
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'actualizado',
                                    'data'      => $result,
                                ];
                            }
                            else
                            {
                                http_response_code(200);
            
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'no actualizado.',
                                    'data'      => [],
                                ];
                            }
                        }                            
                        else
                        {           
                                    
                            http_response_code(200);

                            $result = [
                                'status'    => '200',
                                'message'   => 'no actualizado',
                                'data'      => [],
                            ];
                        }
        
                    }
                    else
                    {
                        
                        http_response_code(200);
    
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos no existentes',
                            'data'      => [],
                        ];                        
        
                    }                    
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
                
            }
            else if ( $target == "reg_get_single" )
            {                

                if ( isset($array_info_data['id_visitant']) )
                {
                    $filter = 'id';
                    $keyword = isset($array_info_data['id_visitant']) ? $array_info_data['id_visitant'] : "";
                }
                else                
                {

                    $filter = []; $keyword = []; $counter = 0;
                    foreach ($array_info_data as $key => $value) {
                        # code...
                        $filter[$counter] = $key;
                        $keyword[$counter] = $value; 
    
                        $counter++;
                    }
                }
                
                $limit = 1;                
                
                $result = $ManageDB->get_table_rows(
                    $table_name,
                    $filter,
                    $keyword,                    
                    $limit,
                    $selected_page,
                    $array_fields = false,
                    $order_by = 'id',
                    $order_dir = 'DESC',
                    $filter_between = "",
                    $array_between = false,
                    $strict_mode = true
                );                
                
                if ( is_array($result) )
                {
                    if ( isset($result['fetched']) && count($result['fetched']) > 0 )
                    {
                        
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];
                        
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {
    
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];
    
                }
    
            }
            else if ( $target == "get" )
            {
                $filter = !empty($filter) ? $filter : 'id';
                $keyword = !empty($keyword) ? $keyword : (isset($array_info_data['id']) ? $array_info_data['id'] : "");
                //$keyword = isset($array_info_data['id']) ? $array_info_data['id'] : "";
                $limit = 1000;                
                
                $result = $ManageDB->get_table_rows(
                    $table_name,
                    $filter,
                    $keyword,                    
                    $limit,
                    $selected_page,
                    $array_fields = false,
                    $order_by = $filter,                    
                );                
                
                if ( is_array($result) )
                {
                    if ( count($result['fetched']) > 0 )
                    {
                        
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];
                        
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {
    
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];
    
                }
    
            }
            else if ( $target == "category" )
            {
                // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                // de los contrario false.
                //$result = $ManageDB->get_table_rows($table_name);
                $filter = "";
                $keyword = "";
                $limit = 1000;
                $array_fields = false;                

                $result = $ManageDB->get_table_rows(
                    $table_name, 
                    $filter,
                    $keyword,
                    $limit,
                    $selected_page,
                    $array_fields,
                    $order_by 
                );

                if ( is_array($result) )
                {
                    if ( count($result) > 0 )
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];
    
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {
    
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];
    
                }
            }
            else if ( $target == "get_from_filter" )
            {
                
                $filter = isset($array_info_data['filter']) ? $array_info_data['filter'] : "";
                $keyword = isset($array_info_data['keyword']) ? $array_info_data['keyword'] : "";
                $array_fields = isset($array_info_data['array_fields']) ? $array_info_data['array_fields'] : false;                
                $order_by = (!is_array($filter)) ? $filter : 'id';
                $order_dir = isset($array_info_data['order_dir']) ? $array_info_data['order_dir'] : "ASC";
                $filter_between = isset($array_info_data['filter_between']) ? $array_info_data['filter_between'] : "";
                $array_between = isset($array_info_data['array_between']) ? $array_info_data['array_between'] : false;
                $limit = 7;

                // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                // de los contrario false.
                //$result = $ManageDB->get_table_rows_from_filter($table_name,$filter,$keyword);
                $result = $ManageDB->get_table_rows(
                    $table_name, 
                    $filter, 
                    $keyword,                    
                    $limit,
                    $selected_page,
                    $array_fields,
                    $order_by = $order_by,
                    $order_dir,
                    $filter_between,
                    $array_between
                );

                if ( is_array($result) )
                {
                    if ( count($result) > 0 )
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];
    
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {
    
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];
    
                }
            }
            else if ( $target == "delete" )
            {
                $filter = 'id';
                $keyword = isset($array_info_data['id']) ? $array_info_data['id'] : "";                                
                
                $result = $ManageDB->delete_table_row(
                    $table_name,
                    $keyword,
                    $filter                   
                );

                //var_dump($result); die();

                if ( $result == true )
                {
                    
                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'datos eliminados',
                        'data'      => [],
                    ];
                    
                }
                else
                {

                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'datos no eliminados',
                        'data'      => [],
                    ];

                }

            }
            else if ( $target == "push_reg_visitant_one" )
            {
                //var_dump($array_info_data); die();
                // si no hay ultima visita, obten la fecha actual, sino devuelve fecha ultima visita
                $array_info_data['last_visit_date'] = empty($array_info_data['last_visit_date']) ? date('Y-m-d H:m:s') : $array_info_data['last_visit_date'];

                // Si es una array con mas de 1 elemento entonces
                if ( is_array($array_info_data) && count($array_info_data) > 1 )
                {

                    $filter = isset($array_info_data['id']) ? 'id' : 'id';
                    $assoc_index = isset( $array_info_data['id'] ) ? $array_info_data['id'] : '';

                    $uploads_path = UPLOADS_PUBLIC_FILE_DIR_PATH.date('Y')."/".date('m');

                    if ( !empty( $array_info_data['photo_path'] ) && !(strpos($array_info_data['photo_path'],'http') > -1) )
                    {
                        // ruta directorio que contiene la imagen
                        $photo_path = $uploads_path."/".$array_info_data['photo_path'];
                        
                        // ruta completa donde se guardara el archivo (incluye nombre del archivo)
                        $path_to_save_image_file = $photo_path;
                        
                        //var_dump($path_to_save_image_file); 
                        
                        // instanciamos la clase Files        
                        $Files = new Library\Classes\Files;
                        
                        // recibe la ruta a crear y el modo de acceso por defecto todo permitido
                        // si no existe lo crea, devuelve true al crearlo de lo contrario false
                        $Files->create_path($uploads_path);                       
                        
                        $temp_file_path = TEMP_PUBLIC_FILE_DIR_PATH . $array_info_data['photo_path'];

                    }
                    elseif (  !(strpos($array_info_data['photo_path'],'http') > -1) )
                    {
                        if ( isset( $array_info_data['photo_path'] ) ){
                            unset( $array_info_data['photo_path'] );
                        }
                    }

                    //var_dump($array_info_data['photo_path']); die();
                    // devuelve true si los datos existen en la base de datos
                    // se verifica mediante el id de la entidad
                    $result = $ManageDB->row_exists($table_name,$array_info_data, $filter, $assoc_index);
                    //var_dump($result); die();
                    if ($result == false)
                    {
                        //var_dump($path_to_save_image_file . "<br>" . $temp_file_path ); die();                        

                        if ( isset($array_info_data['photo_path']) )
                        {
                            // devuelve true si el archivo es movido correctamente, de lo contrario false.
                            $file_was_moved = @copy($temp_file_path, $path_to_save_image_file);

                            if( $file_was_moved )
                            {
                                @unlink($temp_file_path);
                                    
                                $public_file_ext = get_file_extension($array_info_data['photo_path']);
                                
                                $new_public_file_name = strtolower($array_info_data['name']."_".$array_info_data['last_name']."_".date('Y-m-dH:i:s'));
                                $new_public_file_name = str_replace(":","",str_replace("-","",str_replace(" ","_", $new_public_file_name)));
                                
                                // renombramos el archivo
                                $result = rename($path_to_save_image_file, $uploads_path."/".$new_public_file_name.".".$public_file_ext );
                                
                                if ( $result )
                                {
                                    // url publica
                                    $array_info_data['photo_path'] =  DOMAIN_URL . "/public/uploads/".date('Y')."/".date('m')."/".$new_public_file_name.".".$public_file_ext;
                                }                                
    
                                if ( isset($array_info_data['public_photo_path']) ) unset($array_info_data['public_photo_path']);
    
                            }
                            else
                            {
                                http_response_code(200);        
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'datos no actualizados.3',
                                    'data'      => [],
                                ]; 
                            }
                        }
                        
                         // enviamos el nombre de la tabla y la data en un array asociativo
                        // el mismo contiene como clave los nombres de los campos y los valores a insertar
                        // devuelve true de lo contrario false
                        $result = $ManageDB->insert($table_name,$array_info_data);
            
                        //var_dump($result); die();
            
                        if ( $result == true )
                        {
                            $result = $ManageDB->get_table_rows(
                                $table_name,
                                $filter = "",
                                $keyword = "",
                                $limit = 1,
                                $selected_page = '1',
                                $array_fields = false,
                                $order_by = 'id',
                                $order_dir = 'DESC'
                            );

                            //var_dump($result); 
                            
                            if ( is_array($result) && count($result) > 0 )
                            {   
                                
                                http_response_code(200);
        
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'insertado',
                                    'data'      => $result,
                                ];
                            }
                            else
                            {
                                http_response_code(200);
            
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'no insertado.',
                                    'data'      => [],
                                ];
                            }
                        }                            
                        else
                        {           
                                    
                            http_response_code(200);

                            $result = [
                                'status'    => '200',
                                'message'   => 'no insertado',
                                'data'      => [],
                            ];
                        }                         
                                                
        
                    }
                    else
                    {
                        
                        if( isset($array_info_data['id']) && strlen($array_info_data['id']) > 0 )
                        {
                            $filter = 'id';
                            $keyword = isset($array_info_data['id']) ? $array_info_data['id'] : "";
                            $limit = 1;                
                            
                            $result = $ManageDB->get_table_rows(
                                $table_name,
                                $filter,
                                $keyword,                    
                                $limit,
                                $selected_page,
                                $array_fields = false,
                                $order_by = $filter,                    
                            );
                            
                            //var_dump('query');
                            //var_dump($result);

                            if ( is_array($result) && count($result) > 0 )
                            {
                                
                                // verificamos si existe la foto para eliminarla
                                if ( isset($array_info_data['photo_path']) && !(strpos($array_info_data['photo_path'],'http') > -1) )
                                {
                                    //var_dump($result['fetched'][0]['photo_path']); 
                                    
                                    if ( strlen($result['fetched'][0]['photo_path']) > 0 )
                                    {

                                        $old_photo_path = $result['fetched'][0]['photo_path'];
                                        
                                        $old_photo_path = str_replace( URL_BASE ,"", $old_photo_path );
                                        
                                        $old_photo_path = "./..".$old_photo_path;
    
                                        @unlink($old_photo_path);

                                    }

                                        
                                    // devuelve true si el archivo es movido correctamente, de lo contrario false.
                                    $file_was_moved = @copy($temp_file_path, $path_to_save_image_file);
                                    
                                    //var_dump($file_was_moved); die();

                                    if($file_was_moved)
                                    {   
            
                                        unlink($temp_file_path);
            
                                        $public_file_ext =  get_file_extension($array_info_data['photo_path']);
                                        
                                        $new_public_file_name = strtolower($array_info_data['name']."_".$array_info_data['last_name']."_".date('Y-m-dH:i:s'));
                                        $new_public_file_name = str_replace(":","",str_replace("-","",str_replace(" ","_", $new_public_file_name)));
                                            
                                        // renombramos el archivo
                                        $result = rename($path_to_save_image_file, $uploads_path."/".$new_public_file_name.".".$public_file_ext );
                                        //var_dump($result);
                                        if ($result)
                                        {
                                            // url publica
                                            $array_info_data['photo_path'] = DOMAIN_URL . "/public/uploads/".date('Y')."/".date('m')."/".$new_public_file_name.".".$public_file_ext;
                                            $array_info_data['photo_path'] = htmlspecialchars($array_info_data['photo_path']);
                                            
                                        }
                                        else
                                        {
                                            http_response_code(200);        
                                            $result = [
                                                'status'    => '200',
                                                'message'   => 'datos no actualizados',
                                                'data'      => [],
                                            ]; 
                                        }
                                    }
                                    else
                                    {
                                        $array_info_data['photo_path'] = "";
                                    }
                                    
                                }
                                /* else if ( !isset($array_info_data['public_photo_path']) )
                                {
                                    $array_info_data['photo_path'] = "";
                                } */
                                
                            }
                        }                        

                        if ( isset($array_info_data['public_photo_path']) && !(strpos($array_info_data['photo_path'],'http') > -1) ) unset($array_info_data['public_photo_path']);
                        
                        //var_dump('array_info_data');
                        //var_dump($array_info_data['photo_path']); die();
                        // actualiza un registro en una tabla de la base de datos
                        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
                        // devuelve true de los contrario false
                        
                        $result = $ManageDB->update( $table_name , $array_info_data );
                       
                        if ($result)
                        {

                            http_response_code(200);        
                            $result = [
                                'status'    => '200',
                                'message'   => 'datos actualizados',
                                'data'      => [],
                            ];                        
                        }
                        else
                        {
                            http_response_code(200);        
                            $result = [
                                'status'    => '200',
                                'message'   => 'datos no actualizados',
                                'data'      => [],
                            ]; 
                        }   
        
                    }
                    
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
                
            }
            else if ( $target == "push_reg_visitant_two" )
            {

                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                       
                    if (isset($array_info_data['id_visitant']))
                    {
                        $filter = [ 'id_visitant' , 'ident_number' , 'ident_type_id' ];
                        $keyword = [ $array_info_data['id_visitant'] , $array_info_data['ident_number'] , $array_info_data['ident_type_id'] ];
                        //var_dump($filter);
                        //var_dump($keyword);
                        // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                        // de los contrario false.
                        $result = $ManageDB->get_table_rows(
                            $table_name,
                            $filter,
                            $keyword,            
                            $limit = 1,
                            $selected_page = '1',
                            $array_fields = false,
                            $order_by = 'id',
                            $order_dir = 'DESC'
                        );

                        //var_dump($result);
                    }   
                    
                    if ( $result == false || ( isset($result['fetched']) && ( count($result['fetched']) <= 0 ) ) )
                    {
                        // enviamos el nombre de la tabla y la data en un array asociativo
                        // el mismo contiene como clave los nombres de los campos y los valores a insertar
                        // devuelve true de lo contrario false
                        $result = $ManageDB->insert($table_name,$array_info_data);
            
                        //var_dump("insert: ".$result);
            
                        if ( $result == true )
                        {
                            $result = $ManageDB->get_table_rows(
                                $table_name,
                                $filter = "",
                                $keyword = "",
                                $limit = 1,
                                $selected_page = '1',
                                $array_fields = false,
                                $order_by = 'id',
                                $order_dir = 'DESC'
                            );

                            //var_dump($result); 
                            
                            if ( is_array($result) && count($result) > 0 )
                            {   
                                
                                http_response_code(200);
        
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'insertado',
                                    'data'      => $result,
                                ];
                            }
                            else
                            {
                                http_response_code(200);
            
                                $result = [
                                    'status'    => '200',
                                    'message'   => 'no insertado.',
                                    'data'      => [],
                                ];
                            }
                        }                            
                        else
                        {           
                                    
                            http_response_code(200);

                            $result = [
                                'status'    => '200',
                                'message'   => 'no insertado',
                                'data'      => [],
                            ];
                        }
        
                    }
                    else
                    {
                        
                        //var_dump($array_info_data); die();

                        // actualiza un registro en una tabla de la base de datos
                        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
                        // devuelve true de los contrario false
                        $result = $ManageDB->update( $table_name , $array_info_data );

                        //var_dump($result);

                        if ($result)
                        {

                            http_response_code(200);        
                            $result = [
                                'status'    => '200',
                                'message'   => 'datos actualizados',
                                'data'      => [],
                            ];                        
                        }
                        else
                        {
                            http_response_code(200);        
                            $result = [
                                'status'    => '200',
                                'message'   => 'datos no actualizados',
                                'data'      => [],
                            ]; 
                        }
        
                    }
                    
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
                
            }
            else if ( $target == "push_reg_visitant_three" )
            {

                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                     
                    // enviamos el nombre de la tabla y la data en un array asociativo
                    // el mismo contiene como clave los nombres de los campos y los valores a insertar
                    // devuelve true de lo contrario false
                    $result = $ManageDB->insert($table_name,$array_info_data);
        
                    //var_dump($result);
        
                    if ( $result == true )
                    {
                        $result = $ManageDB->get_table_rows(
                            $table_name,
                            $filter = "",
                            $keyword = "",
                            $limit = 1,
                            $selected_page = '1',
                            $array_fields = false,
                            $order_by = 'id',
                            $order_dir = 'DESC'
                        );

                        //var_dump($result); 
                        
                        if ( is_array($result) && count($result['fetched']) > 0 )
                        {   
                            
                            http_response_code(200);
    
                            $result = [
                                'status'    => '200',
                                'message'   => 'insertado',
                                'data'      => $result,
                            ];
                        }
                        else
                        {
                            http_response_code(200);
        
                            $result = [
                                'status'    => '200',
                                'message'   => 'no insertado',
                                'data'      => [],
                            ];
                        }
                    }                            
                    else
                    {           
                                
                        http_response_code(200);

                        $result = [
                            'status'    => '200',
                            'message'   => 'no insertado',
                            'data'      => [],
                        ];
                    }
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
                
            }
            else if ( $target == "check_last_visit" )
            {
                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                       
                    if (isset($array_info_data['id']))
                    {
                        $filter = [ 'visitant_id' , 'visit_state' ];
                        $keyword = [ $array_info_data['id_visitant'] , '1' ];
                        
                        // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                        // de los contrario false.
                        $result = $ManageDB->get_table_rows(
                            $table_name,
                            $filter,
                            $keyword,            
                            $limit = 1,
                            $selected_page = '1',
                            $array_fields = false,
                            $order_by = 'updated_at',
                            $order_dir = 'DESC',
                            $filter_between = "",
                            $array_between = false,
                            $strict_mode = true
                        );

                    }   
                    //var_dump($result);
                    
                    if ( isset($result['fetched']) && ( count($result['fetched']) > 0 ) )
                    {
                        
                                
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'visitante activo',
                            'data'      => [],
                        ];  
                       
                    }
                    else
                    { 

                        http_response_code(200);        
                        $result = [
                            'status'    => '200',
                            'message'   => 'visitante inactivo',
                            'data'      => [],
                        ];
        
                    }
                    
                   
                }
                else
                {
                    http_response_code(200);

                    $result = [
                        'status'    => '200',
                        'message'   => 'datos incompletos',
                        'data'      => [],
                    ];  
                }
            }
            else if ( $target == "get_visitants_data")
            {
                if ( isset($array_info_data['ident_number']) && strlen($array_info_data['ident_number']) > 0 )
                {
                    // el visit_state 1 indica que el usario tiene una visita sin finalizar.
                    $filter = [ 'ident_number' , 'ident_type_id' , 'visit_state' ];
                    $keyword = [ $array_info_data['ident_number'] , $array_info_data['ident_type_id'] , '1' ];
                    $limit = 1;
                }
                else if ( !isset($array_info_data['ident_number']) && isset($array_info_data['id']) )
                {
                    
                    $filter = [ 'id_visitant' , 'visit_state' ];
                    $keyword = [ $array_info_data['id'] , '1' ];
                    $limit = 1;
                }
                else
                {
                    $filter = 'visit_state';
                    $keyword = '1';
                    $limit = 10;
                }
            
                // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                // de los contrario false.
                //$result = $ManageDB->get_table_rows_from_filter($table_name,$filter,$keyword);
                $result = $ManageDB->get_table_rows(
                    $table_name, 
                    $filter, 
                    $keyword,                    
                    $limit,
                    $selected_page,
                    $array_fields = false,
                    $order_by = 'last_visit_date',
                    $order_dir = 'DESC',
                    $filter_between = "",
                    $array_between = false,
                    $strict_mode = $strict_mode
                );

                //var_dump($result); die();

                if ( $result )
                {

                    if ( count($result['fetched']) > 0 )
                    {
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];                         
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {

                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];

                }
            }
            else if ( $target == "get_today_visitants_data")
            {                
                    
                $filter = 'started_at';
                $keyword = date('Y-m-d');
                $limit = 1000;
                
            
                // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
                // de los contrario false.
                //$result = $ManageDB->get_table_rows_from_filter($table_name,$filter,$keyword);
                $result = $ManageDB->get_table_rows(
                    $table_name, 
                    $filter, 
                    $keyword,                    
                    $limit,
                    $selected_page,
                    $array_fields = false,
                    $order_by = 'last_visit_date',
                    $order_dir = 'DESC'
                );

                //var_dump($result); die();

                if ( $result )
                {

                    if ( count($result['fetched']) > 0 )
                    {
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'datos obtenidos',
                            'data'      => $result,
                        ];                         
                    }
                    else
                    {
    
                        http_response_code(200);
                        $result = [
                            'status'    => '200',
                            'message'   => 'no data',
                            'data'      => [],
                        ];
    
                    }
                }
                else
                {

                    http_response_code(200);
                    $result = [
                        'status'    => '200',
                        'message'   => 'no data',
                        'data'      => [],
                    ];

                }
            }
            else if ( $target == "push_fn_visit_data" )
            {

                if ( is_array($array_info_data) && count($array_info_data) > 1)
                {
                                       
                    // enviamos el nombre de la tabla y la data en un array asociativo
                    // el mismo contiene como clave los nombres de los campos y los valores a insertar
                    // devuelve true de lo contrario false
                    $result = $ManageDB->update($table_name,$array_info_data);
        
                    //var_dump($array_info_data); die();
        
                    if ( $result == true )
                    {

                        $result = $ManageDB->get_table_rows(
                            $table_name,
                            $filter = "",
                            $keyword = "",
                            $limit = 1,
                            $selected_page = '1',
                            $array_fields = false,
                            $order_by = 'id',
                            $order_dir = 'DESC'
                        );

                        //var_dump($result); die();
                        
                        if ( is_array($result) && count($result) > 0 )
                        {   
                            
                            http_response_code(200);
    
                            $result = [
                                'status'    => '200',
                                'message'   => 'actualizado',
                                'data'      => $result,
                            ];
                        }
                        else
                        {
                            http_response_code(200);
        
                            $result = [
                                'status'    => '200',
                                'message'   => 'no actualizado.',
                                'data'      => [],
                            ];
                        }
                    }
                    else
                    {
                        http_response_code(200);
    
                        $result = [
                            'status'    => '200',
                            'message'   => 'no actualizado.',
                            'data'      => [],
                        ];
                    }
                }
                
            }
        }
        else        
        {
            http_response_code(401);
            $result = [
                'status'    => '401',
                'message'   => 'no autorizado',
                'data'      => [],
            ]; 
        }
        
        
    }
    else
    {
        $result = [
            'status'    => '401',
            'message'   => 'faltan parametros',
            'data'      => [],
        ]; 
    }
    header('Content-Type: application/json');
    $result = json_encode($result);
    
    echo $result;
    die();