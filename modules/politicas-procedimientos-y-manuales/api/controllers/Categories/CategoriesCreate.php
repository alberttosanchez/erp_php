<?php // target : categories-create

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

if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
}
$info_data = isset($jsonObject->info_data) ? $jsonObject->info_data : "";  

//var_dump($info_data); die();
$category_name = isset($info_data->category_name) ? cleanData($info_data->category_name) : "";      
$category_slug = isset($info_data->category_slug) ? cleanData($info_data->category_slug) : (isset($info_data->subcategory_slug) ? cleanData($info_data->subcategory_slug) : "");

/** Verificamos que el slug no exista o se renombra */

function recursive_rename_slug($category_slug, $counter = 0)
{

    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;

    // obtenemos los datos del usuario activo
    $result = $ManageDB->get_table_rows(
        $table_name = 'arch_view_all_combine_categories',
        $filter = "category_slug",
        $keyword = $category_slug,            
        $limit = 1,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'category_slug',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );
    
    // si la respuesta no esta seteado o es false
    if ( isset($result["fetched"]) && count($result["fetched"]) > 0 )
    {
        $category_slug = explode("-",$category_slug)[0];

        $category_slug = recursive_rename_slug($category_slug."-".++$counter,$counter++);
    }

    return $category_slug;

}

$category_slug = recursive_rename_slug($category_slug);


/** Procedemos a insertar la categoria en la Base de datos */

    $table_name = isset($jsonObject->table_name) ? cleanData(trim_double($jsonObject->table_name)) : "";          
    
    if ( isset($info_data->category_slug) )
    {
        $info_data->category_slug = $category_slug;
    }
    elseif ( isset($info_data->subcategory_slug) )
    {
        $info_data->subcategory_slug = $category_slug;
    }
    
    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;

    // insertamos los valores en la tabla
    $result = $ManageDB->insert( $table_name , (array)$info_data );
    
    // si la respuesta no esta seteado o es false
    if ( !isset($result) || !$result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }


$response = [
    'status'    => '200',
    'message'   => 'Categoria Agregada Correctamente',
    'data'      => []
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;