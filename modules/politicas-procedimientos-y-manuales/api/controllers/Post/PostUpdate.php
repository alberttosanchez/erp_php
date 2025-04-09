<?php // target : post-update
    
// se debe verificar en cada controlador que lo necesite debido a que hay peticiones que no necesitan el id de formulario.
$form_id = isset($jsonObject->form_id) ? cleanData(trim_double($jsonObject->form_id)) : "";        

if ( !isset($form_id) || empty($form_id) || $form_id !== session_id() )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
}   

# Capturamos los datos enviados por POST

$post_id                = isset( $_POST['post_id'] )         ? utf8_decode(trim_double(cleanData($_POST['post_id'],true))) : "";

$post_title             = isset( $_POST['post_title'] )         ? utf8_decode(trim_double(cleanData($_POST['post_title'],true))) : "";
$post_content           = isset( $_POST['post_description'] )   ? utf8_encode(trim_double(cleanData($_POST['post_description'],true))) : "";
$post_description       = isset( $_POST['post_excerpt'] )       ? utf8_decode(trim_double(cleanData($_POST['post_excerpt'],true))) : "";
$post_categories        = isset( $_POST['post_categories'] )    ? cleanData(trim_double($_POST['post_categories'])) : "";
$publicar               = isset( $_POST['publicar'] )           ? cleanData(trim_double($_POST['publicar'])) : "off";
$category_level         = isset( $_POST['cat_level'] )          ? cleanData(trim_double($_POST['cat_level'])) : "";
$category_id            = isset( $_POST['cat_id_lv'] )          ? cleanData(trim_double($_POST['cat_id_lv'])) : 0;
$post_publication_state = isset( $_POST['publicar'] )           ? cleanData(trim_double($_POST['publicar'])) : "";
$author_id              = $user_id;

// instanciamos la clase ManageDB
$ManageDB = new Library\Classes\ManageDB;

# Obtemos el path donde se guardaran y  eliminaran los archivos del host del servidor.
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

$array_files_path = (array)json_decode($result['fetched'][0]['files_path']);

$files_path = $array_files_path["path"];
$array_with_files_names = ( isset($array_files_path["post_files"]) ) ? (array)$array_files_path["post_files"] : [];

/** Convertimos objetos de clases en arrays */

for ($i=0; $i < count($array_with_files_names); $i++) { 
    $array_with_files_names[$i] = (array)$array_with_files_names[$i];
}

#var_dump('array_with_files_names1',$array_with_files_names);

/* Procedemos a subir los archivos al servidor */

$array_with_file_name_and_path = [
    'path'  => $files_path,
    'post_files'   => $array_with_files_names
];

#var_dump('array_with_file_name_and_path1',$array_with_file_name_and_path);

$files_was_uploaded = false;
if ( isset($_FILES["drop_zone"]['tmp_name']) && count($_FILES["drop_zone"]['tmp_name']) > 0 ){

    // obtenemos las extensiones permitidas de la base de datos si los filtros estan activados
    $result = $ManageDB->get_table_rows(
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

    // instanciamos la clase persona.
    $Files = Library\Classes\Files::singleton();

    $array_with_extensions = [];
    // si el array fetched no esta seteado y el array esta vacio
    if ( isset($result['fetched'][0]['json_options']) || count($result['fetched']) > 0 )
    {  
    
        $json_options            = (array)json_decode($result['fetched'][0]['json_options']);
        
        $uploads                 = (array)$json_options["uploads"];
        $uploads_allow           = $uploads['allow'];
        $uploads_filter          = $uploads['filter'];
        $uploads_slug_allowed    = $uploads['slug_allowed'];
        
        $uploads_file_extensions = explode(",",$uploads['file_extensions'],);
        
        // si el array no esta vacio
        if ( count($uploads_file_extensions) > 0){            
        
            // creamos un array asociativo con llaves y valores iguales
            for ($i=0; $i < count($uploads_file_extensions); $i++) { 
                $array_with_extensions[$uploads_file_extensions[$i]] = $uploads_file_extensions[$i];
            }
            
        }
    }
    else
    {
        // extensiones permitidas
        $array_with_extensions = [
            'doc'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'   => 'application/vnd.ms-excel',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            'pdf'   => 'application/pdf',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'odt'   => 'application/vnd.oasis.opendocument.text'
        ];
    }
    
    $index=0;$array_ext=[];
    foreach ($_FILES["drop_zone"]['tmp_name'] as $key => $file_tmp_name) {        
        
        if ( ! isset($uploads_filter) && $uploads_filter == false )    
        {
            // devuelve false si la extension no es valida, de lo contrario obtenemos la extension
            if ( ! ( $array_ext[$index] = $Files->get_valid_mime_type($file_tmp_name,$array_with_extensions) ) )
            {
                on_exception_server_response(406,$file_tmp_name.', no es un tipo de archivo permitido.',$target);
                die();
            }
            else
            {
                $index++; 
            }
    
        }
        else
        {
            $ext = array_search(
                $file_tmp_name,
                $array_with_extensions, true);
    
            if ( ! isset($ext) )
            {
                on_exception_server_response(406,$file_tmp_name.', no es un tipo de archivo permitido.',$target);
                die();
            }
        }

    }

    

    // si el path esta vacio
    if (strlen($files_path) < 1)
    {
        // ruta directorio donde se guardaran los archivos
         $files_path = ARCH_UPLOADS_PUBLIC_POST_FILE_DIR_PATH."/".date('Y')."/".date('m');
    }
        
    // recibe la ruta a crear y el modo de acceso por defecto todo permitido
    // si no existe lo crea, devuelve true al crearlo de lo contrario false    
    if ( ! $Files->create_path($files_path) )
    {
        on_exception_server_response(409,'El directorio no pudo ser creado.',$target);
        die();
    }

    foreach ($_FILES as $files_assoc_key => $array_files) {
        
        if (count($_FILES) == 1)
        {
            # Devuelve un array con los nombres de los archivos movidos de los contrario false;            
            $result = $Files->recursive_move_uploaded_file($files_path,$files_assoc_key);
        }

    }

    if ( !is_array($result) && !$result )
    {
        on_exception_server_response(409,'los archivos no pudieron ser movidos.',$target);
        die();
    }
    $files_was_uploaded = true;
    $array_with_file_name_and_path['path'] = $files_path;
    $array_with_file_name_and_path['post_files'] = array_merge($result,$array_with_files_names);
    $array_with_files_names = $array_with_file_name_and_path['post_files'];

}

#ar_dump('array_with_files_names2',$array_with_files_names);
#var_dump('array_with_file_name_and_path',$array_with_file_name_and_path);

/* Procedemos a crear un objeto json para ser guardado en la base de datos */

// creamos el objeto json con json_encode (JSON_UNESCAPED_UNICODE evitar que cambie las vocales asentuadas)
$json_with_file_name_and_path = json_encode($array_with_file_name_and_path,JSON_UNESCAPED_UNICODE);

#var_dump('json_with_file_name_and_path1',$json_with_file_name_and_path);

/* Procedemos a remover los archivos quitados del post */

$array_file_list_to_remove    = isset($_POST['file_list_to_remove'])  ? json_decode($_POST['file_list_to_remove'],true) : [];

#var_dump('array_file_list_to_remove',$array_file_list_to_remove);
#var_dump('count($array_file_list_to_remove) > 0',count($array_file_list_to_remove) > 0);

if (count($array_file_list_to_remove) > 0)
{
    
    /** Creamos un array solo con los nombre de archivos guardados */

    $array_with_names = [];
    for ($i=0; $i < count($array_with_files_names); $i++) { 
        $array_with_names[$i] = $array_with_files_names[$i]['file_name'];
    }

    $array_with_files_names_to_delete = array_intersect($array_file_list_to_remove,$array_with_names);

    #var_dump('array_with_files_names_to_delete',$array_with_files_names_to_delete);
    
    // verificamos que haya nombre de archivos a borrar
    if ( count($array_with_files_names_to_delete) > 0 )
    {
        // instanciamos la clase Files
        $Files = new Library\Classes\Files;
    
        $result = $Files->removeFiles($files_path,$array_with_files_names_to_delete);

        // si devuelve false, hay un error al borrar los archivos.
        if ( ! $result )
        {
            on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
            die();
        }

        $array_with_files_names_to_preserve = array_diff($array_with_names,$array_file_list_to_remove);

        #var_dump('array_with_files_names_to_preserve1',$array_with_files_names_to_preserve);

        /** agregamos la propiedad size a los archivos a preservar */

        $new_array_to_preserve=[]; $c=0;
        foreach ($array_with_files_names_to_preserve as $key => $value) {
                    
            for ($u=0; $u < count($array_with_files_names); $u++) { 
                
                if ($value == $array_with_files_names[$u]['file_name'] )
                {
                    $new_array_to_preserve[$c]['file_name'] = $array_with_files_names[$u]['file_name'];
                    $new_array_to_preserve[$c]['file_size'] = $array_with_files_names[$u]['file_size'];
                    $c++;
                }

            }
        }

        $array_with_files_names_to_preserve = $new_array_to_preserve;

        #var_dump('array_with_files_names_to_preserve2',$array_with_files_names_to_preserve);

        $array_with_file_name_and_path = json_decode($json_with_file_name_and_path,true);
        
        #var_dump('$array_with_file_name_and_path',$array_with_file_name_and_path);

        if ( isset( $files_was_uploaded ) && $files_was_uploaded )
        {
            $array_with_file_name_and_path['post_files'] = array_values($array_with_files_names_to_preserve);

        }
        else
        {
            $array_with_file_name_and_path = [
                "path" => $files_path,
                "post_files" => $array_with_files_names_to_preserve,
            ];
            
            $array_with_file_name_and_path["post_files"] = array_values($array_with_file_name_and_path["post_files"]);            
            
        }
        
        $json_with_file_name_and_path = json_encode($array_with_file_name_and_path,JSON_UNESCAPED_UNICODE);

        #var_dump('json_with_file_name_and_path',$json_with_file_name_and_path);
    }

}

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


$table_name = 'arch_post_data';
$author_full_name = $result['fetched'][0]['first_name'] . ' ' . $result['fetched'][0]['last_name'];

$array_new_post_data = [
    'id'                    => $post_id,
    'post_title'            => $post_title,
    'post_content'          => $post_content,
    'post_description'      => $post_description,
    'author_id'             => $author_id,
    'author_full_name'      => $author_full_name,
    'files_path'            => $json_with_file_name_and_path,
    'category_level'        => $category_level,
    'category_id'           => (int)$category_id,
    'post_publication_state'=> $post_publication_state,
];

// actualizamos los valores en la tabla
$result = $ManageDB->update( $table_name , $array_new_post_data );

// si la respuesta no esta seteado o es false
if ( !isset($result) || !$result )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}

$result = [];

$response = [
    'status'    => '200',
    'message'   => 'Post Actualizado',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;