<?php // target : print_tail-fill

    $uploads_path = TEMP_PUBLIC_FILE_DIR_PATH;

    // instanciamos la clase Files        
    $Files = new Library\Classes\Files;
                            
    // recibe la ruta a crear y el modo de acceso por defecto todo permitido
    // si no existe lo crea, devuelve true al crearlo de lo contrario false
    $response = $Files->create_path($uploads_path);                       

    if ( ! $response )
    {
        on_exception_server_response(403,'El directorio no pudo ser creado.','print_tail-fill'); 
        die();
    }

    $file_path = $uploads_path;

    $file_name = isset($jsonObject->info_data->file_name) ? cleanData($jsonObject->info_data->file_name) : "";

    /** Verificamos que los datos a escribir existan */

    if ( ! isset($jsonObject) )
    {
        on_exception_server_response(403,'Faltan parametros.','print_tail-fill'); 
        die();
    }
    
    $name           = isset($jsonObject->info_data->name)           ? cleanData($jsonObject->info_data->name)           : "";    
    $last_name      = isset($jsonObject->info_data->last_name)      ? cleanData($jsonObject->info_data->last_name)      : "";
    $co_worker_dpto = isset($jsonObject->info_data->co_worker_dpto) ? cleanData($jsonObject->info_data->co_worker_dpto) : "";
    $identification = isset($jsonObject->info_data->identification) ? cleanData($jsonObject->info_data->identification) : "";
    $dpto_floor     = isset($jsonObject->info_data->dpto_floor)     ? cleanData($jsonObject->info_data->dpto_floor)     : "";
    $visit_date     = isset($jsonObject->info_data->visit_date)     ? cleanData($jsonObject->info_data->visit_date)     : "";
       
    $line_to_write  = $name;    
    $line_to_write .= '|' . $last_name;
    $line_to_write .= '|' . $co_worker_dpto;
    $line_to_write .= '|' . $identification;
    $line_to_write .= '|' . $dpto_floor;
    $line_to_write .= '|' . $visit_date;

    $response = $Files->insert_line_on_file($file_path, $file_name, $line_to_write );

    if ( ! $response )
    {
        on_exception_server_response(403,'La cola de impresion no puse ser agregada.','print_tail-fill'); 
        die();
    }

    /** Si no hubo errores devuelve el resultado con estado 200 */

    http_response_code(200);
    $result = [
        'status'    => '200',
        'message'   => 'Cola de impresion agregada correctamente.',
        'data'      => [],
    ]; 