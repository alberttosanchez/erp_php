<?php // target : categories-update

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

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

// obtenemos los datos del usuario activo
$result = $ManageDB->get_table_rows(
    $table_name = PREXI . 'users_profile',
    $filter = "user_id",
    $keyword = $author_id,            
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

$author_full_name = $result['fetched'][0]['first_name'] . ' ' . $result['fetched'][0]['last_name'];

# Capturamos los datos enviados por POST

$category_description     = isset( $jsonObject->category_description ) ? cleanData(trim_double($jsonObject->category_description)) : "";
$category_id_and_lv       = isset( $jsonObject->category_id )          ? cleanData(trim_double($jsonObject->category_id)) : 0;

$array_category_id_and_lv = explode("_with_",$category_id_and_lv);
$category_id              = $array_category_id_and_lv[0];
$category_level           = $array_category_id_and_lv[1];

$category_name            = isset( $jsonObject->category_name )           ? cleanData(trim_double($jsonObject->category_name)) : "";
$category_slug            = isset( $jsonObject->category_slug )           ? cleanData(trim_double($jsonObject->category_slug)) : "";


// si la respuesta no esta seteado o es false
if ( !isset($category_name) || strlen($category_name) < 1 )
{
    on_exception_server_response(403,'El nombre de categoria no puede estar en blanco',$target);
    die();
}

// si la respuesta no esta seteado o es false
if ( !isset($category_slug) || strlen($category_slug) < 1 )
{
    on_exception_server_response(403,'El Slug no puede estar en blanco',$target);
    die();
}


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


switch ($category_level) {
    case 'id_lv1':       $table_name = 'arch_cat_first_level_category';  break;
    case 'id_lv2':       $table_name = 'arch_cat_second_level_category'; break;
    case 'id_lv3':       $table_name = 'arch_cat_third_level_category';  break;
    case 'id_lv4':       $table_name = 'arch_cat_four_level_category';   break;
    case 'id_lv5':       $table_name = 'arch_cat_five_level_category';   break;    
    default:             $table_name = '';                               break;
}

$array_new_post_data = [    
    'id'                    => (int)$category_id,
    'category_name'         => $category_name,
    'category_slug'         => $category_slug,
    'category_description'  => $category_description,
];

if ( $category_level != "id_lv1" )
{
    $array_new_post_data = [    
        'id'                       => (int)$category_id,
        'subcategory_name'         => $category_name,
        'subcategory_slug'         => strtolower($category_slug),
        'subcategory_description'  => $category_description,
    ];    
}

// actualizamos los valores en la tabla
$result = $ManageDB->update( $table_name , $array_new_post_data );

// si la respuesta no esta seteado o es false
if ( !isset($result) || !$result )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

$response = [
    'status'    => '200',
    'message'   => 'Post Actualizado',
    'data'      => []
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;