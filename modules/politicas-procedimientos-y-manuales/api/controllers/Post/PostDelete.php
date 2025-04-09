<?php // target : post-delete

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

if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
} 


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

$post_id_to_delete = (int)$result['fetched'][0]['id'];

if ( !isset($post_id_to_delete) || empty($post_id_to_delete) || is_nan($post_id_to_delete)   )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

/** Obtenemos los nombres y la ruta de los archivos adjuntos */

//var_dump($result['fetched'][0]['id']); die();

$files_path = (array)json_decode($result['fetched'][0]['files_path']);

//var_dump($files_path); die();

if ( isset($files_path['post_files']) && count($files_path['post_files']) > 0)
{
    $path = $files_path['path'];
    
    // nombre de los archivos a eliminar
    $post_files = [];
    
    for ($i=0; $i < count($files_path['post_files']) ; $i++) { 
        
        $files = (array)$files_path['post_files'][$i];
    
        $post_files[$i] = $files['file_name'];    
    }

    //var_dump($post_files); die();

    /** Procedemos a eliminar los archivos adjuntos */
    
    // instanciamos la clase Files
    $Files = new Library\Classes\Files;
        
    $result = $Files->removeFiles($path,$post_files);
    
    // si devuelve false, hay un error al borrar los archivos.
    if ( ! $result )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

}

/** Procedemos a eliminar el post de la base de datos */

 $result = $ManageDB->delete_table_row("arch_post_data",$post_id_to_delete,"id");

// enviamos la respuesta y los datos obtenidos
$response = [
    'status'    => '200',
    'message'   => 'Post Eliminado',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;