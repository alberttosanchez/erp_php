<?php 

    // comprobar si una cadena es json
    function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    //removemos el doble espaciado innesario, ex. "  " -> " "
    function trim_double($string)
    {
        return preg_replace('/( ){2,}/u',' ',$string);
    }
                            
    // limpia los datos
    function cleanData($data = null, $html_entities = false)
    {
        // remueve espacios vacios al principio y al final de una cadena.
        $data = trim($data);

        if ( $html_entities )
        {
            // neutraliza caracteres especiales.
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, "UTF-8");
        }
        else
        {
            $data = htmlspecialchars($data);
        }

        // reemplaza caracteres especiales no permitidos
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $data = strtr( $data, $unwanted_array );

        return $data;
    }

    function remove_get_attributes_from_url ($array_exploded_from_url)
    {

        // recorremos el array para quitar los atributos pasados por get.
        for ($i=0; $i < count($array_exploded_from_url); $i++) { 
            
            if ( strpos($array_exploded_from_url[$i],"?") > -1 )
            {
                $start_position = strpos($array_exploded_from_url[$i],"?");
                
                if ($start_position > -1)
                {
                    $string_to_replace = substr($array_exploded_from_url[$i], $start_position);                    
                    $array_exploded_from_url[$i] = str_replace($string_to_replace,"",$array_exploded_from_url[$i]);                    
                }
            }

            if ( strpos($array_exploded_from_url[$i],"#") > -1 )
            {
                $start_position = strpos($array_exploded_from_url[$i],"#");
                
                if ($start_position > -1)
                {
                    $string_to_replace = substr($array_exploded_from_url[$i], $start_position);                    
                    $array_exploded_from_url[$i] = str_replace($string_to_replace,"",$array_exploded_from_url[$i]);                    
                }
            }
        }
        
        return $array_exploded_from_url;

    }

    /**
     * Obtiene la url actual y verifica si esta en el path correcto.
     * Toma en cuenta la posicion omitiendo el dominio
     * el parametro exact = false busca coincidencia en la cadena del path.
     * Parametros : string , interger (opcional), exact(opcional)
     * 
     */
    function is_path($url, $indexPosition = 1, $exact = true )
    {   
        // obtiene url actual 
        $uri = $_SERVER['REQUEST_URI'];

        if ($_SERVER['SERVER_NAME'] === "localhost")
        {        
            //echo var_dump(STRING_TO_CUT);
            
            $stringToCutLenght = strlen(STRING_TO_CUT);

            $uri = substr($uri,$stringToCutLenght);
            
            //echo var_dump($uri);
        }
        
        // explode crea array a partir de una cadena, tomando en cuenta un delimitador
        $array = explode('/',$uri);
        //echo var_dump($array);

        $array = remove_get_attributes_from_url($array);

        $indexPosition = ( $indexPosition <= count($array)-1 ) ? $indexPosition : count($array)-1;

        $found = $array[$indexPosition];
        
        //echo var_dump($found);

        if ( $exact === true && $found === $url )
        {
            return true;
        }
        elseif ($exact === false && (strpos($found,$url) > -1) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
   
    /**
     * Devuelve una cadena con el path sin el dominio.
     * Esta funciona elimina el directorio localhost de la cadena.
     * Si envia un parametro cadena y lo encuentra lo devuelve.
     */
    function get_path($string_to_search = "", $indexPosition = null, $delimiter = "-")
    {
        // obtenemos el path sin el dominio
        $current_full_path = str_replace(STRING_TO_CUT,"",$_SERVER['REQUEST_URI']);
        
        // si no hay cadena a buscar
        if ($string_to_search !== "" && strlen($string_to_search) > 0)
        {
            // creamos una array con el delimitador
            $array_from_string_to_search = explode($delimiter,$current_full_path);
            
            $array_from_string_to_search = remove_get_attributes_from_url($array_from_string_to_search);

            //var_dump( $array_from_string_to_search );
            
            // si indexposition esta definido devolvemos esa posicion del array
            if ( isset($indexPosition) && (count($array_from_string_to_search)-1) >= $indexPosition )
            {
                $found = isset($indexPosition) ? $array_from_string_to_search[$indexPosition] : null;

                return $found;
            }
            else if ( count($array_from_string_to_search) > 0 )
            {

                for ($i=0; $i < count($array_from_string_to_search) ; $i++) {
                
                    $delimiter = ( $delimiter !== "-" ) ? $delimiter : "";

                    if ( ($delimiter . $array_from_string_to_search[$i]) === $string_to_search )
                    {                        
                        return $delimiter . $array_from_string_to_search[$i];
                    }

                }

            }
        }

        return $current_full_path;
    }
    
    /**     
     * @returns true si es la pagina de inicio
     * @returns false de los contrario
     */
    function is_home()
    {
        
        if( get_path() === "/index.php" || get_path() === "/" )
        {
            return true;
        }

        return false;

    }

    /**
     * @returns el nombre del modulo obtenido verificado de la url
     * con el directorio existente; de lo contrario false
     */
    function get_module_name($indexPosition = 1)
    {

        // obtiene url actual 
        $uri = $_SERVER['REQUEST_URI'];

        if ($_SERVER['SERVER_NAME'] === "localhost")
        {        
            
            $stringToCutLenght = strlen(STRING_TO_CUT);

            $uri = substr($uri,$stringToCutLenght);
                    
        }
        
        // explode crea array a partir de una cadena, tomando en cuenta un delimitador
        $array = explode('/',$uri);
        
        //echo var_dump($array); die();

        if ( count($array) > 0 ) 
        {
            $path_mod_name = $array[$indexPosition]; 

            $dir_and_files = glob( $_SERVER['DOCUMENT_ROOT'] . MODULE_PATH . "*" );
            
            //echo var_dump($dir_and_files);

            foreach ($dir_and_files as $item) {
                
                if ( is_dir($item) )
                {
                    $array_from_path = explode('/',$item);
                    
                    if (count($array_from_path) > 0)
                    {                           
                        $index = array_search( $path_mod_name, $array_from_path);

                        $mod_name = $array_from_path[$index];
                        
                        if ( $mod_name === $path_mod_name )
                        {                            
                            return $mod_name;    
                        }
                    }
                }

            }
        
        }
        
        return false;
        
    }

    function the_module_title()
    {

        $module_name = ucwords(str_replace( '-' , ' ' , get_module_name() ) );

        return $module_name;

    }

    // funciones de la api

    // copia un directorio completo
    function recurseCopy($src,$dst, $childFolder='') { 

        $dir = opendir($src); 
        @mkdir($dst);
        if ($childFolder!='') {
            @mkdir($dst.'/'.$childFolder);
    
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) { 
                    if ( is_dir($src . '/' . $file) ) { 
                        recurseCopy($src . '/' . $file,$dst.'/'.$childFolder . '/' . $file); 
                    } 
                    else { 
                        copy($src . '/' . $file, $dst.'/'.$childFolder . '/' . $file); 
                    }  
                } 
            }
        }else{
                // return $cc; 
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) { 
                    if ( is_dir($src . '/' . $file) ) { 
                        recurseCopy($src . '/' . $file,$dst . '/' . $file); 
                    } 
                    else { 
                        copy($src . '/' . $file, $dst . '/' . $file); 
                    }  
                } 
            } 
        }
        
        closedir($dir); 
    }

    // elimina todos los archivos y carpetas de un directorio dado
    // recibe un array creado con la funcion glob($path."*")
    function removeDirAndFiles($path)
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

    function get_file_extension($file = null)
    {

        $array = explode(".",$file);

        for ($i = 0; $i < count($array); $i++) {
            
            if ( $i == count($array)-1 )
            {
                return $array[$i];
            }
            
        }
        return false;
        
    }

    // Si el resultado es falso devuelve un objeto vacio.
    function is_result_false($result)
    {
        // si es falso se ejecuta
        if (!$result)        
        {
            // respuesta del servidor 204 - sin contenido
            http_response_code(204);
            $result = [
                "status"    => "204",
                "message"   => "Resultado falso."
            ];        
            header('Content-Type: application/json');    
            echo json_encode($result);
            die();                        
        }
    }

    // devuelve un token en formato md5
    function get_security_token()
    {
        $token = mt_rand(10000000,99999999);
        $new_token = md5($token);
        return $new_token;
    }

    
    // Devuelve el codigo de respuesta del servidor especificado y un json con datos.
    // Recibe 3 parametros:
    // 1- el codigo de respuesta del servidor
    // 2- un mensaje a devolver
    // 3- un array opcional de resultado (default true) que sera converido en json
    function on_exception_server_response( $http_response_code , $message = 'Ejecución Incorrecta' , $target = 'unset', $send_result = true )
    {
        // server codigos : 200, 403 , 409 , etc
        http_response_code($http_response_code);
    
        header('Content-Type: application/json');

        if ( $send_result )
        {
            $result = [
                'status'        => strval($http_response_code),                
                'message'       => $message,
                'target'        => $target,
            ];
            $result = json_encode($result);
            echo $result;

        }

    }
    