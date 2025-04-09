<?php // target: files-delete_temp_file

    $path_to_save_file = isset($jsonObject->temp_file_name) ? cleanData(trim_double($jsonObject->temp_file_name)) : null;

    /** verificamos que el path de post exista o devuelve faltan parametros */

    if ( !isset($path_to_save_file)         || empty($path_to_save_file)         || $path_to_save_file  == "" )
    {
        // Si el id de formulario no coincide con el id de session la session no es la misma
        // entonces resultado 400.
        on_exception_server_response(400,'Error. Faltan parametros.',$target);
        die();
    }

    // instanciamos la clase Files
    $Files = new Library\Classes\Files;
    
    $result = $Files->delete_file_after_download($path_to_save_file);
        
    // si el array fetched no esta seteado y el array esta vacio
    if ( ! $result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    // enviamos la respuesta y los datos obtenidos
    $response = [
        'status'    => '200',
        'message'   => 'Archivo Eliminado',
        'data'      => []
    ];

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    $response = json_encode($response);
    echo $response;