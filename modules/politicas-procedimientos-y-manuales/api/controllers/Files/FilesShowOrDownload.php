<?php // target : files-show_or_download
    
    $post_id = isset($jsonObject->post_id) ? cleanData(trim_double($jsonObject->post_id)) : null;
    $file_key = isset($jsonObject->file_key) ? cleanData(trim_double($jsonObject->file_key)) : null;
    $file_name = isset($jsonObject->file_name) ? cleanData(trim_double($jsonObject->file_name)) : null;
    /** verificamos que el filter de post exista o devuelve faltan parametros */

    if ( !isset($post_id)         || empty($post_id)         || $post_id  == ""  ||
         !isset($file_key)        || $file_key  == ""        ||     
         !isset($file_name)       || empty($file_name)       || $file_name  == "" )
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

    $role_id = $result['fetched'][0]['role_id'];
    
    // decidimos si descargar o solo mostrar el archivo mediante el rol del usuario
    $content_type = ( ($role_id == 1) || ($role_id == 2) ) ? "application/octet-stream" : "mimetype";

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
    if ( ! isset($result['fetched'][0]['files_path']) || count($result['fetched']) < 1 )
    {
        on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
        die();
    }

    $files_path = json_decode($result['fetched'][0]['files_path']);

    $path = $files_path->path;
    $array_post_files = $files_path->post_files;

    // obtenemos el nombre del archivo a descargar
    for ($i=0; $i < count($array_post_files); $i++) { 

        $array_file = (array)$array_post_files[$i];

        if ($array_file["file_name"] == $file_name)
        {        
            $file_name = $array_file["file_name"];
        }

    }

    $full_file_path = $path . "/" . $file_name;
    
        
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


    // to do
    $content_type = "application/octet-stream";
    
    if ( ($role_id == 1) || ($role_id == 2) || $download_allowed )
    {
        header("Content-type: $content_type");
        header("Content-Transfer-Encoding: Binary");
        header('Content-Disposition: attachment; filename=' . $file_name);
        echo file_get_contents($full_file_path);
    }
    else
    {   

        // cargamos los datos de accesso de config.php del sigpromj 
        $_SERVER['PHP_AUTH_USER'] = HTPASSWD['user'];
        $_SERVER['PHP_AUTH_PW'] = HTPASSWD['pass'];

        include_once('./controllers/Files/FilesTemplates/FilesShowWithViewerJS.php');
    }
    die();