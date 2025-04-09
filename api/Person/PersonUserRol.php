<?php // target : person-user_rol


if ( !isset($user_id) || empty($user_id) || is_nan($user_id) )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
} 

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

// obtenemos los datos del usuario activo
$result = $ManageDB->get_table_rows(
    $table_name = PREFIX .'users_security_data',
    $filter = "user_id",
    $keyword = $user_id,            
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
if ( ! isset($result['fetched'][0]['role_id']) || count($result['fetched']) < 1 )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

/** Si no hubo errores devuelve el resultado con estado 200 */

http_response_code(200);
$result = [
    'status'    => '200',
    'message'   => 'Rol recuperado.',
    'data'      => [
        'rol_id'    => $result['fetched'][0]['role_id']
    ],
]; 