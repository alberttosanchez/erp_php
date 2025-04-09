<?php // target : settings-update_files_download

// se debe verificar en cada controlador que lo necesite debido a que hay peticiones que no necesitan el id de formulario.
$form_id = isset($jsonObject->form_id) ? cleanData(trim_double($jsonObject->form_id)) : "";        

if ( !isset($form_id) || empty($form_id) || $form_id !== session_id() )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
}

/** Procedemos a verificar que el usuario sea administrador o soporte */

// instanciamos la clase Person
//$Person = new Library\Classes\Person esta instanciada en api.php

if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
}

// obtenemos un array
$obj_file_download_options = isset( $jsonObject->file_download_options ) ? json_decode(trim_double($jsonObject->file_download_options)) : json_decode("{}");

//var_dump($obj_file_download_options);

/** Obtenemos el objeto json de la base de datos para luego actualizar */

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

// obtenemos los datos del usuario activo
$result = $ManageDB->get_table_rows(
    $table_name = 'arch_settings',
    $filter = "",
    $keyword = "",
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
if ( ! isset($result['fetched'][0]['json_options']) || count($result['fetched']) < 1 )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

$settings_id = isset($result['fetched'][0]['id']) ? $result['fetched'][0]['id'] : 1;

$obj_old_json_options = json_decode($result['fetched'][0]['json_options']);

$obj_old_json_options->downloads->allow             = $obj_file_download_options->allow;
$obj_old_json_options->downloads->filter            = $obj_file_download_options->filter;
$obj_old_json_options->downloads->slug_allowed      = $obj_file_download_options->slug_allowed;
$obj_old_json_options->downloads->file_extensions   = $obj_file_download_options->file_extensions;

//var_dump(json_encode($obj_old_json_options));die();


$array_new_post_data = [    
    'id'             => (int)$settings_id,
    'json_options'   => json_encode($obj_old_json_options)    
];    

$table_name = 'arch_settings';
// actualizamos los valores en la tabla
$result = $ManageDB->update( $table_name , $array_new_post_data );

// si la respuesta no esta seteado o es false
if ( !isset($result) || !$result )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

/** Registramos en el Logs los cambios realizados por el usuarios */

$array_new_post_data = [
    'user_id'            => $user_id,
    'log_description'    => "El usuario id: $user_id, cambia opciones de descargar archivos y filtros.",
    'root'               => "modulo: " . ARCH_MODULE_NAME . ", Opciones de descargas."
];

$table_name = PREFIX . "log_for_users_actions";
// insertamos los valores en la tabla
$result = $ManageDB->insert( $table_name , $array_new_post_data );

// si la respuesta no esta seteado o es false
if ( !isset($result) || !$result )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}


/** Enviamos la respuesta satisfactoria */

$response = [
    'status'    => '200',
    'message'   => 'Opciones Actualizadas',
    'data'      => []
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;