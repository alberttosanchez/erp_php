<?php // target : categories-delete

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

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

// obtenemos los datos del usuario activo
$result = $ManageDB->get_table_rows(
    $table_name = PREFIX . 'users_profile',
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

$category_id      = isset( $jsonObject->category_id )     ? cleanData(trim_double($jsonObject->category_id)) : "";
$category_level   = isset( $jsonObject->category_level )  ? cleanData(trim_double($jsonObject->category_level)) : "";


switch ($category_level) {
    case 'id_lv1':       $cat_lv = 'lv1'; break;
    case 'id_lv2':       $cat_lv = 'lv2'; break;
    case 'id_lv3':       $cat_lv = 'lv3'; break;
    case 'id_lv4':       $cat_lv = 'lv4'; break;
    case 'id_lv5':       $cat_lv = 'lv5'; break;    
    default:             $cat_lv = ''; break;
}

$table_name = 'arch_post_data';

// verificamos que la categoria no este siendo usada por articulos
$result = $ManageDB->get_table_rows(
    $table_name = $table_name,
    $filter = ["category_level","category_id"],
    $keyword = [$cat_lv,$category_id],            
    $limit = 1000,
    $selected_page = '1',
    $array_fields = false,
    $order_by = 'id',
    $order_dir = 'ASC',
    $filter_between = "",
    $array_between = false,
    $strict_mode = true
);

// si el array fetched esta seteado y el array no esta vacio
if ( isset($result['fetched']) && count($result['fetched']) > 0 )
{    
    on_exception_server_response(403,'No puede eliminar una categoria que esta siendo usada.',$target);
    die();
}

/** Procedemos a eliminar la categoria de la base de datos */

switch ($category_level) {
    case 'id_lv1':       
        $table_name = 'arch_cat_first_level_category';  
        $child_table = "arch_cat_second_level_category"; 
        $cat_up_level_field_name = "category_first_level_id";
        break;
    case 'id_lv2':       
        $table_name = 'arch_cat_second_level_category'; 
        $child_table = "arch_cat_third_level_category"; 
        $cat_up_level_field_name = "category_second_level_id";
        break;
    case 'id_lv3':       
        $table_name = 'arch_cat_third_level_category';  
        $child_table = "arch_cat_four_level_category"; 
        $cat_up_level_field_name = "category_third_level_id";
        break;
    case 'id_lv4':       
        $table_name = 'arch_cat_four_level_category';   
        $child_table = "arch_cat_five_level_category";
        $cat_up_level_field_name = "category_four_level_id";
        break;
    case 'id_lv5':       
        $table_name = 'arch_cat_five_level_category';
        $child_table = "";
        $cat_up_level_field_name = "";
        break;    
    default:             
        $table_name = ""; 
        $child_table = "";
        $cat_up_level_field_name = "";
        break;
}


/** Procedemos a verificar que la categoria a eliminar no tenga subcategorias */

function recursive_get_table_row($child_table,$cat_up_level_field_name,$category_id){

    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;
    
    // verificamos que la categoria no este siendo usada por articulos
    $result = $ManageDB->get_table_rows(
        $table_name = $child_table,
        $filter = $cat_up_level_field_name,
        $keyword = $category_id,            
        $limit = 1000,
        $selected_page = '1',
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

    // si el array fetched esta seteado y el array no esta vacio
    if ( isset($result['fetched']) && count($result['fetched']) > 0 )
    {    
        return true;
    }

    return false;
}


// si el array fetched esta seteado y el array no esta vacio
if ( recursive_get_table_row($child_table,$cat_up_level_field_name,$category_id) && $category_level != "id_lv5" )
{    
    on_exception_server_response(403,'No puede eliminar una categoria que tiene subcategorias.',$target);
    die();
}

/** Procedemos a eliminar la categoria */

$category_id_to_delete = $category_id;

$result = $ManageDB->delete_table_row($table_name,$category_id_to_delete,"id");

// enviamos la respuesta y los datos obtenidos
$response = [
    'status'    => '200',
    'message'   => 'Categoria Eliminada',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;