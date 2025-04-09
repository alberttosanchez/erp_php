<?php

namespace Library\Classes;

class Files {
    
    private static $instance;


    public function get_last_line_on_file($file_path, $file_name)
    {
        $action = 'r+';

        $full_file = $file_path . $file_name;

        $file_opened = fopen( $full_file , $action );
        
        // contamos las lineas del archivo
        $counter_line = 0;        
        while( !feof($file_opened) ) { 
            $get_last_line = fgets($file_opened);
            $counter_line++; 
        }               
        
        $line_counter = 0;
        while( !feof($file_opened) ) {
            if ( $line_counter === $counter_line )
            {
                $get_last_line = fgets($file_opened);
            }
            $line_counter++;            
        }               
        
        fclose($file_opened);
        
        if (strlen($get_last_line) > 4)
        {

            // load the data and delete the line from the array 
            $lines = file($full_file); 
            $last = count($lines) - 1;             
            unset($lines[$last]);            

            file_put_contents($full_file, $lines);

            // eliminamos las lineas en blanco
            $new_data = file_get_contents($full_file);
            file_put_contents($full_file, trim($new_data) );

            return $get_last_line;
        }

        return false;

    }
    /**
     * crea un archivo si no existe y escribe una linea al final.
     */
    public function insert_line_on_file($file_path, $file_name, $line_to_write)
    {
        if (!file_get_contents("data:,ok")) {
            die("Houston, we have a stream wrapper problem.");
        }
        
        if ( ! ini_get('allow_url_fopen') ) {        
            on_exception_server_response(500,'allow_url_fopen esta deshabilitado');            
            die();
        }

        $full_file = $file_path . $file_name;
        
        $current_data = file_get_contents($full_file);
        $lines = file($full_file);
        
        if (strlen($current_data) > 4 && count($lines) >= 1 )
        {
            $new_data = $current_data . PHP_EOL . $line_to_write;
        }        
        else
        {
            $new_data = $line_to_write;
        }
        
        $wrote = file_put_contents($full_file, trim($new_data) );
        
        if ($wrote == false)
        {
            return false;
        }

        return true;
    }

    /**
     * crea un archivo si no existe.
     */
    public function create_file($file_path, $file_name)
    {
        
        $full_file = $file_path . $file_name;

        // fopen abre (w) o lo crea (w+) si no existe el archivo.
        $full_file = fopen( $full_file ,'w+');

        if ($full_file == false)
        {
            return false;
        }
        
        fclose($full_file);

        return true;
    }

    public function combine_names_with_files_size($array_files_name = [], $array_files_size = [])
    {        
        $new_array=[]; $i=0;
        foreach ($array_files_name as $key => $value) {
            $new_array[$i]['file_name'] = $value;
            $new_array[$i]['file_size'] = $array_files_size[$i];
            $i++;
        }        
        //var_dump('new_array',$new_array);       
        return $new_array;
    }

    /**
     * esta funcion elimina un archivo despues de descargarse completamente del servidor
     */
    public function delete_file_after_download($full_file_path, $counter = 0){

        // si el archivo existe y su peso es mayor que 0.
        if (file_exists($full_file_path) && filesize($full_file_path) > 0) {
            // expera 5 segundos antes de eliminarlo
            sleep(5);
            // eliminalo
            if (unlink($full_file_path)) {
                return true;
            }
        }

        if ($counter >= 30) {            
            return false;
        }
        $counter++;

        sleep(5);
        return delete_file_after_download($full_file_path,$counter);
    }
    /**
     * Copia un archivo en el mismo directorio que se encuentra y devuelve la ruta completa con 
     * el nuevo nombre, de lo contrario false
     */
    public function copy_file_with_new_name($full_file_path,$new_full_file_path){
                
        if ( copy($full_file_path, $new_full_file_path) ) {            
            return true;
        } 

        return false;

    }

    private function recursive_action($files_path,$array_names=[],$array_tmp_names=[],$i)
    {
        
        if ( empty($files_path) || !isset($array_names) || !isset($array_tmp_names) )
        {
            return false;
        }
        $array_new_names=[];
        for ($i=0; $i < count($array_names); $i++) { 
            
            // renombramos el archivo si es necesario
            $new_file_name = $this->rename_file_if_exist( $files_path , $array_names[$i] );            
            
            // guardamos el nuevo nombre en el array
            $array_new_names[$i] = $new_file_name;

            // ruta completa donde se guardara el archivo (incluye nombre del archivo)
            $path_to_save_file = $files_path."/".$new_file_name;
            
            // devuelve true si el archivo es movido correctamente, de lo contrario false.
            $file_was_moved = move_uploaded_file($array_tmp_names[$i],$path_to_save_file);  
            
            // si el archiv no fue movido
            if ( ! $file_was_moved )
            {
               return false;
            }        
    
            $array_names[$i] = $path_to_save_file;

        }

        // retorna el array con los nombre movidos
        return $array_new_names;
        
    }
    
    /**
     * mueve un array de archivos a la ubicacion indicada, los renombra de ser necesario
     * Devuelve un array con las rutas de los archivos movidos de los contrario false
     */
    public function recursive_move_uploaded_file($files_path,$assoc_name,$i=0)
    {

        $array_names=[];$array_types=[];$array_tmp_names=[];$array_errors=[]; $array_sizes=[];
        foreach ($_FILES[$assoc_name] as $assoc_key => $array_value) {

           

            $counter=0;
            foreach ($array_value as $index => $value) {
            
                // cambiamos las vocales asentuadas por normales
                $value = cleanData($value);
                
                switch ($assoc_key) {
                    case 'name':
                        $array_names[$counter] = $value;
                        break;
                    case 'type':
                        $array_types[$counter] = $value;
                        break;
                    case 'tmp_name':
                        $array_tmp_names[$counter] = $value;
                        break;
                    case 'error':
                        $array_errors[$counter] = $value;
                        break;
                    case 'size':
                        $array_sizes[$counter] = $value;
                        break;
                    default:
                        # code...
                        break;
                }
                $counter++;
            }
        }
        
        $result = $this->recursive_action($files_path,$array_names,$array_tmp_names,$i);
                
        $result = $this->combine_names_with_files_size($result,$array_sizes);

        return $result;
    }
    
    private function recursive_rename($files_path,$file_name,$ext,$counter=0)
    {
        $new_name = $file_name."(".$counter.").".$ext;
                
        if ( file_exists($files_path."/".$new_name) )
        {
            $counter++;
            $new_name = $this->recursive_rename($files_path,$file_name,$ext,$counter);
        }

        return $new_name;
    }

    /**
     * Renombrar un archivo si existe
     */
    public function rename_file_if_exist( $files_path , $file_name )
    {
        $array_path = explode(".", $file_name);
        
        $new_file_name="";$ext=""; $path_save_file="";
        for ($i=0; $i < count($array_path) ; $i++) { 
            
            if ( $i+1 < count($array_path) )
            {
                $new_file_name .= $array_path[$i];
            }
            elseif ( $i+1 == count($array_path))
            {
                $ext = $array_path[$i];
            }

        }
                
        if ( file_exists( $files_path."/".$new_file_name.".".$ext ) )
        {
            $path_save_file = $this->recursive_rename($files_path,$new_file_name,$ext);
        }
        else
        {
            $path_save_file = $new_file_name.".".$ext;
        }
        
        return $path_save_file;
    }

    // devuelve true si elimina los archivos de un directorio dado
    // recibe un array con los nombre de los archivos
    // de lo contrario false    
    public function removeFiles($path,$array_with_files_names)
    {

        foreach ( $array_with_files_names as $file) {

            if ( is_file($path."/".$file) )
            {                
                if ( @unlink($path."/".$file) )
                {
                    //file was deleted!!!                                                                         
                }
                else
                {
                    return false;
                }
            }           
            
        }        

        return true;

    }

    // devuelve true si elimina todos los archivos y carpetas de un directorio dado
    // recibe un array creado con la funcion glob($path."*")
    // de lo contrario false    
    public function removeDirAndFiles($path)
    {

        foreach (glob($path."/*") as $file_or_dir) {

            if ( is_dir($file_or_dir) )
            {
                removeDirAndFiles($file_or_dir);
            }
            else
            {
                if ( @unlink($file_or_dir) )
                {
                    //file was deleted!!!                                                                         
                }
                else
                {
                    return false;
                }
            }
            
        }

        //elimina directorio vacio
        @rmdir($path);

        return true;

    }

    // recibe la ruta a crear y el modo de acceso por defecto todo permitido
    // si no existe lo crea, devuelve true al crearlo de lo contrario false
    public function create_path($path,$mode = 0777)
    {
        $response = false;                        
        // verificamos si existe el directorio de lo contrario lo crea
        if ( !file_exists($path) && !is_dir($path) )
        {
            // crea el directorio y los sub-directorio indicados
            $response = mkdir($path, $mode, true);
            
            if($response)
            {
                return true;
            }
            return false;
        }
        else if ( file_exists($path) && is_dir($path) )
        {
            return true;
        }
        return false;
    }

    // elimina el archivo de imagen actual
    // si recibe la extension del nuevo archivo elimina la extensiones contrarias
    // si no recibe la extension eliminar todas las extensiones encontradas
    // devuelte true al eliminar, de lo contrario false
    public function delete_current_image_file($user_id,$path,$ext = "")
    {
        $image_path = $path;
        
        if( $ext == "" )
        {
            // extensiones permitidas
            $ext = [
                "jpg",
                "png"
            ];

            for ($i=0; $i < count($ext) ; $i++) { 
                // eliminar el anterior archivo del avatar 
                if( file_exists($image_path.$user_id.".".$ext[$i]) )
                {
                    unlink($image_path.$user_id.".".$ext[$i]);
                }
            }

            if ( !file_exists($image_path.$user_id.".jpg") && !file_exists($image_path.$user_id.".png") )
            {
                return true;
            }
        }
        else
        {
            // eliminar el anterior archivo del avatar 
            if( $ext == 'jpg' && file_exists($image_path.$user_id.".png") )
            {
                unlink($image_path.$user_id.".png");
            }
            else if ( $ext == 'png' && file_exists($image_path.$user_id.".jpg") )
            {
                unlink($image_path.$user_id.".jpg");
            }
            else if ( $ext == 'png' && file_exists($image_path.$user_id.".png") )
            {
                unlink($image_path.$user_id.".png");
            }
            else if ( $ext == 'jpg' && file_exists($image_path.$user_id.".jpg") )
            {
                unlink($image_path.$user_id.".jpg");
            }
    
            if ( !file_exists($image_path.$user_id.".jpg") && !file_exists($image_path.$user_id.".png") )
            {
                return true;
            }
        }
        return false;

    }

    // elimina el archivo de imagen actual
    // si recibe la extension del nuevo archivo elimina la extensiones contrarias
    // si no recibe la extension eliminar todas las extensiones encontradas
    // devuelte true al eliminar, de lo contrario false
    public function delete_current_public_file($path,$ext = "")
    {
        $public_path = $path;
        
        if( $ext == "" )
        {
            // extensiones permitidas
            $ext = [
                "jpg",
                "png",
                "cvs",
            ];

            for ($i=0; $i < count($ext) ; $i++) { 
                // eliminar el anterior archivo del avatar 
                if( file_exists($public_path.".".$ext[$i]) )
                {
                    unlink($public_path.".".$ext[$i]);
                }
            }

            if ( !file_exists($public_path.".jpg") && 
                 !file_exists($public_path.".png") && 
                 !file_exists($public_path.".cvs")
                 )
            {
                return true;
            }
        }
        else
        {
            // eliminar el anterior archivo temporal
            if( $ext == 'jpg' && file_exists($public_path.".png") )
            {
                unlink($public_path.".png");
            }
            else if ( $ext == 'png' && file_exists($public_path.".jpg") )
            {
                unlink($public_path.".jpg");
            }
            else if ( $ext == 'png' && file_exists($public_path.".png") )
            {
                unlink($public_path.".png");
            }
            else if ( $ext == 'jpg' && file_exists($public_path.".jpg") )
            {
                unlink($public_path.".jpg");
            }
            else if ( $ext == 'cvs' )
            {
                if ( file_exists($public_path.".jpg") ) unlink($public_path.".jpg");
                if ( file_exists($public_path.".png") ) unlink($public_path.".png");
            }
    
            if ( !file_exists($public_path.".jpg") && 
                 !file_exists($public_path.".png") &&
                 !file_exists($public_path.".cvs") 
                 )
            {
                return true;
            }
        }
        return false;

    }

    // devuelve la extension del tipo mime si es jpg o png
    // de lo contrario false
    public function get_image_mime_type($file_temp_name)
    {
        // No confie en el valor de $_FILES['avatar_file']['mime']
        // verique el MIME Type por si mismo.
        //$finfo = new finfo(FILEINFO_MIME_TYPE);

        // array_search devuelve la llave asociativa del la coincidencia dentro del array       
        // devuelve la priemra correspondencia encontrada de lo contrario false
        $ext = array_search(
            mime_content_type($file_temp_name),
            array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',                            
            ), true);

        if ( $ext !== false )
        {
            return $ext;
        }
        return false;        
    }

    // devuelve la extension del tipo mime si es jpg o png
    // de lo contrario false
    public function get_valid_mime_type(
        $file_temp_name,
        $array_with_extensions = [
            'doc'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'   => 'application/vnd.ms-excel',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            'pdf'   => 'application/pdf',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg'   => 'image/jpeg',
            'png'   => 'image/png',
            'odt'   => 'application/vnd.oasis.opendocument.text'
            ]
        )
    {
        // No confie en el valor de $_FILES['avatar_file']['mime']
        // verique el MIME Type por si mismo.
        // $finfo = new finfo(FILEINFO_MIME_TYPE);
        
        // array_search devuelve la llave asociativa del la coincidencia dentro del array       
        // devuelve la primera correspondencia encontrada de lo contrario false
        $ext = array_search(
            mime_content_type($file_temp_name),
            $array_with_extensions, true);

        if ( $ext !== false )
        {
            return $ext;
        }
        return false;        
    }

    // devuelve la extension del tipo mime si es jpg, png o cvs
    // de lo contrario false
    public function get_admited_file_mime_type($file_temp_name)
    {
        // No confie en el valor de $_FILES['avatar_file']['mime']
        // verique el MIME Type por si mismo.
        //$finfo = new finfo(FILEINFO_MIME_TYPE);

        // array_search devuelve la llave asociativa del la coincidencia dentro del array       
        // devuelve la priemra correspondencia encontrada de lo contrario false
        $ext = array_search(
            mime_content_type($file_temp_name),
            array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png', 
            'csv' => 'text/csv',
            ), true);

        if ( $ext !== false )
        {
            return $ext;
        }
        return false;        
    }

    // verifica si el archivo zip es valido y
    // devuelve la extension del tipo mime si es zip
    // de lo contrario false
    public function get_zip_mime_type($zip_file)
    {
        
        $file_temp_name = $zip_file['tmp_name'];
        

        // No confie en el valor de $_FILES['avatar_file']['mime']
        // verique el MIME Type por si mismo.
        //$finfo = new finfo(FILEINFO_MIME_TYPE);

        // array_search devuelve la llave asociativa del la coincidencia dentro del array       
        // devuelve la priemra correspondencia encontrada de lo contrario false
        $ext = array_search(
            mime_content_type($file_temp_name),
            array(
            'zip' => 'application/zip',                      
            ), true);
            
        if ( $ext !== false )
        {
            $zip = zip_open($file_temp_name);
            
            if ( !is_int($zip) ) {
                return $ext;
            }

        }
        return false;        
    }

    /* $delimiter = '(';
    $dust = 'create table if not exists';
    $flag = 'TABLE';
    $flag = 'VIEW';
    $flag = 'STORE_PROCEDURE'; */
    /* public function get_module_entities_names_old( $array_with_sql_scripts_files , $delimiter , $dust )
    {        
        if ( is_array($array_with_sql_scripts_files) && count($array_with_sql_scripts_files) > 0 )
        {
            $action = 'r'; // [r]ead - leer solamente
            $counter = 0;
            foreach ($array_with_sql_scripts_files as $sql_file ) {
                
                // si no es un archivo devuelve un array vacio.
                if ( ! is_file($sql_file) ) return [];

                $sql_file_opened = fopen( $sql_file , $action );

                $sql_string = "";
                while( !feof($sql_file_opened) ) {
                    $getLine = strtolower(fgets($sql_file_opened));
                    $sql_string = $sql_string.$getLine;                    
                }               
                
                fclose($sql_file_opened);

                if ( strpos( $sql_string , $delimiter ) > -1 )
                {
                    $sql_array = explode( $delimiter , $sql_string );

                    
                    foreach ($sql_array as $line) {

                        if ( strpos( $line , $dust ) > -1 )
                        {
                            $line_array = explode( $dust , $line );

                            for ($i=0; $i < count($line_array) ; $i++) { 
                                
                                if ( $i+1 == count($line_array) )
                                {
                                    $array_table_name[$counter] = trim($line_array[1]);
                                }

                            }
                        }
                    $counter++;
                    }
                }
            }
            
            return isset($array_table_name) ? $array_table_name : []; 
            
        }

        return []; // false -- array vacio
    } */

    public function get_module_entities_names( $array_with_sql_scripts_files , $flag )
    {
        if ( is_array( $array_with_sql_scripts_files) && count($array_with_sql_scripts_files) > 0 )
        {
            
            $counter = 0; $array_name = [];
            foreach ( $array_with_sql_scripts_files as $sql_file ) {
                
                if ( is_file($sql_file) )
                {
                    $sql_file_opened = fopen( $sql_file , 'r' );
                    $sql_script = "";
                    while( !feof($sql_file_opened) ) {
                        $getLine = fgets($sql_file_opened);
                        $sql_script = $sql_script.$getLine;
                    }

                    fclose($sql_file_opened);

                    $query = trim(strtolower($sql_script));                    
                    
                    while ( strlen($query) > 0 ) {                             
                        
                        // tables
                        if ( $flag == "MYSQL_TABLE" && strpos( $query , 'create table if not exists' ) > -1 )
                        {                            
                            $dust = 'create table if not exists';
                            $end_extract = "(";
                            $needle = ";";
                            $found = true;
                        }
                        // tables
                        elseif ( $flag == "MYSQL_TABLE" && strpos( $query , 'create table' ) > -1 )
                        {                            
                            $dust = 'create table';
                            $end_extract = "(";
                            $needle = ";";
                            $found = true;
                        }
                        // views
                        elseif ( $flag == "MYSQL_VIEWS" && strpos( $query , 'create view' ) > -1 )
                        {                            
                            $dust = 'create view';
                            $end_extract = "as";
                            $needle = ";";
                            $found = true;
                        }
                        // views
                        elseif ( $flag == "MYSQL_VIEWS" && strpos( $query , 'create or replace view' ) > -1 )
                        {                            
                            $dust = 'create or replace view';
                            $end_extract = "as";
                            $needle = ";";
                            $found = true;
                        }
                        // store_procedure -> verificar que el delimitador sea el mismo en el script origen
                        elseif ( $flag == "MYSQL_STORE_PROCEDURE" && strpos( $query, 'create procedure' ) > -1 )
                        {
                            $dust = 'create procedure';
                            $end_extract = "(";
                            $needle = "delimiter ;";
                            $found = true;
                        }                                               

                        if ( isset($found) && $found == true )                        
                        {
                            // posicion inicial
                            $start_pos = strpos( $query , $dust );
                            
                            // posicion final + 1
                            $end_pos = strpos($query, $needle, $start_pos)+1; 
                            
                            // extraemos la cadena a que contiene el nombre de la entidad
                            $query_extracted = trim(substr($query , $start_pos , $end_pos-$start_pos));                        
                            
                            // guardamos la query con sin la parte extraida
                            $query = substr($query, $end_pos); // new query
                            
                            // obtenemos el tamaño de la cadena de la posicion inicial                            
                            $dust_len = strlen( $dust );                    
    
                            // posicion inicial de la cadena extraida
                            $start_pos_from_extracted = strpos( $query_extracted , $dust );

                            // posicion final de la cadena extraida
                            $end_pos_from_extracted = strpos( $query_extracted , $end_extract );
    
                            
                            $array_name[$counter] =  trim(substr( $query_extracted, $start_pos_from_extracted+$dust_len, $end_pos_from_extracted-($start_pos_from_extracted+$dust_len)));
                            
                            $counter++;

                            $found = false; 
                        }
                        else
                        {
                            $query = "";
                        }
                        
                    }
                    //var_dump($counter);                     
                }
                
            };            
            
            //var_dump($array_name); die();
            return isset($array_name) ? $array_name : [];
            
        }

        return []; // false -- array vacio
    }

    # ----------------------------------------------------   
    # método singleton
    public static function singleton() {       
        
        if (!isset(self::$instance)) {
            $myclass = __CLASS__; # __CLASS__ devuelve el nombre de esta clase.
            self::$instance = new $myclass;
        } 

        return self::$instance;

    }

    # --------------------------------------------------
    # Evita que el objeto se pueda clonar
    public function __clone() {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }

    /* public function verificarSingleton(){
        return self::$typeConexion;
    } */
}