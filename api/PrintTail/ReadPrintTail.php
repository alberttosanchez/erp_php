<?php  // target : print_tail-read

    $read_path = TEMP_PUBLIC_FILE_DIR_PATH;

    // instanciamos la clase Files        
    $Files = new Library\Classes\Files;
    
    $file_name = isset($jsonObject->info_data->file_name) ? cleanData($jsonObject->info_data->file_name) : "";

    $response = $Files->get_last_line_on_file($read_path, $file_name );
        
    if ( ! $response )    
    {
        on_exception_server_response(200,'La cola de impresion no pudo ser obtenida.','print_tail-read'); 
        die();
    }

    /** Si no hubo errores convertimos la cola en un array */

    $print_tail_array = explode("|",$response);
        
    /** Si no hubo errores devuelve el resultado con estado 200 */

    http_response_code(200);
    $result = [
        'status'    => '200',
        'message'   => 'Cola de impresion obtenida correctamente.',
        'data'      => $print_tail_array,
    ]; 