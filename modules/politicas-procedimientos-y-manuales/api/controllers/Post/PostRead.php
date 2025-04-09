<?php // target : post-read

/** Procedemos a obtener el id del post del objeto json */

(int)$post_id = isset($jsonObject->post_id) ? cleanData(trim_double($jsonObject->post_id)) : ""; 

/** verificamos que el id de post exista o devuelve faltan parametros */

if ( !isset($post_id) || empty($post_id) || is_nan($post_id) )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
} 

/** Procedemos a verificar que el usuario sea administrador o soporte */

/* if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
}  */


/** Procedemos a obtener los datos de la base de datos mediante el id del post */

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

// obtenemos los datos del usuario activo
$result = $ManageDB->get_table_rows(
    $table_name = 'arch_post_data',
    $filter = "id",
    $keyword = $post_id,            
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
if ( ! isset($result['fetched']) || count($result['fetched']) < 1 )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

// obtenemos el slug del post recuperado
$slug = $ManageDB->get_table_rows(
    $table_name = 'arch_view_all_post',
    $filter = "id",
    $keyword = $post_id,            
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
if ( ! isset($slug['fetched']) || count($slug['fetched']) < 1 )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

$category_slug = $slug['fetched'][0]['category_slug'];
// agregamos el slug a la data del post.
// assoc_key
$result['fetched'][0]['category_slug'] = $category_slug;
// number_key
$result['fetched'][0][floor((count($result['fetched'][0])/2))] = $category_slug;

//verificamos si ese slug tiene permitido imprimir
$settings = $ManageDB->get_table_rows(
    $table_name = 'arch_settings',
    $filter = "id",
    $keyword = 1,            
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
if ( ! isset($settings['fetched']) || count($settings['fetched']) < 1 )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

$array_settings = (array)json_decode($settings['fetched'][0]['json_options']);

$slug_allowed = $array_settings["downloads"]->slug_allowed;

$array_with_slugs = explode(",",$slug_allowed);

$download_allowed = false;

for ($i=0; $i < count($array_with_slugs) ; $i++) { 
    
    if ( $category_slug == $array_with_slugs[$i])
    {
        $download_allowed = true;
    }
}

// agregamos el download_allowed a la data del post.
// assoc_key
$result['fetched'][0]['download_allowed'] = $download_allowed;
// number_key
$result['fetched'][0][floor((count($result['fetched'][0])/2))] = $download_allowed;

// enviamos la respuesta y los datos obtenidos
$response = [
    'status'    => '200',
    'message'   => 'Post Recuperado',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;