<?php // target : settings-read_all

// se debe verificar en cada controlador que lo necesite debido a que hay peticiones que no necesitan el id de formulario.
$form_id = isset($jsonObject->form_id) ? cleanData(trim_double($jsonObject->form_id)) : "";        

if ( !isset($form_id) || empty($form_id) || $form_id !== session_id() )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
}

$author_id                = $user_id;

$user_is_admin_or_support = $user_id;

/** Procedemos a verificar que el usuario sea administrador o soporte */

if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
} 


/** Procedemos a obtener las opciones de la base de datos */

    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;
    
    // obtenemos los datos del usuario activo
    $result = $ManageDB->get_table_rows(
        $table_name = 'arch_settings',
        $filter = "",
        $keyword = "",            
        $limit = 1,
        $selected_page = 1,
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

// si el array fetched no esta seteado y el array esta vacio
if ( ! isset($result['fetched']) )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

// enviamos la respuesta y los datos obtenidos
$response = [
    'status'    => '200',
    'message'   => 'Opciones Recuperadas',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;