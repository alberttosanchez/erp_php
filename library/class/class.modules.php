<?php
/**
 * Esta clase maneja las propiedades y atributos para instalar y desinstalar los modulos 
 * para el IJOVEN.
 */

namespace Library\Classes;

class Modules {

    private static $instance;     
    
    /**
     * recibe un archivo en un array de la funcion file()
     * obtiene un array con los datos del modulo
     * devuelve el array con los datos o false
     */
    public function getModuleDataFromFileInArray($file_in_array)
    {
        /*
        Module Name     : Clock Timer  
        Version         : 1.0.0  
        Description     : Reloj digital clasico.  
        Author          : Jhon Alam  
        Web             : https://midomain.com  
        isLink          : 1
        Licence         : MIT
        */
        
        $module_data = [];

        for ($i=0; $i < count($file_in_array); $i++) { 
            
            if ( strpos($file_in_array[$i],"Module Name") > -1 )            
            {
                $array_line = explode(":",$file_in_array[$i]);

                if ( count($array_line) == 2 )
                {
                    $module_data["name"] = htmlspecialchars(trim($array_line[1]));
                }
            }
            else if ( strpos($file_in_array[$i],"Version") > -1 )
            {
                $array_line = explode(":",$file_in_array[$i]);
                if ( count($array_line) == 2 )
                {
                    $module_data["version"] = htmlspecialchars(trim($array_line[1]));
                }
            }
            else if ( strpos($file_in_array[$i],"Description") > -1 )
            {
                $array_line = explode(":",$file_in_array[$i]);
                if ( count($array_line) == 2 )
                {
                    $module_data["description"] = htmlspecialchars(trim($array_line[1]));
                }
            }
            else if ( strpos($file_in_array[$i],"Author") > -1 )
            {
                $array_line = explode(":",$file_in_array[$i]);
                if ( count($array_line) == 2 )
                {
                    $module_data["author"] = htmlspecialchars(trim($array_line[1]));
                }
            }
            else if ( strpos($file_in_array[$i],"Web") > -1 )
            {
                $array_line = explode(":",$file_in_array[$i]);

                if ( count($array_line) == 2 )
                {
                    $module_data["web"] = htmlspecialchars(trim($array_line[1]));
                }
                else if ( count($array_line) > 2 )
                {   
                    $module_data["web"] = trim($array_line[1]) . ":";

                    for ($e=2; $e < count($array_line); $e++) {     
                                                
                        $module_data["web"] .= trim( $array_line[$e] );
                    }
                }
            }
            else if ( strpos($file_in_array[$i],"isLink") > -1 )
            {
                $array_line = explode(":",$file_in_array[$i]);

                if ( count($array_line) == 2 )
                {
                    $module_data["islink"] = htmlspecialchars(trim($array_line[1]));
                }
                else if ( count($array_line) > 2 )
                {   
                    $module_data["islink"] = trim($array_line[1]) . ":";

                    for ($e=2; $e < count($array_line); $e++) {     
                                                
                        $module_data["islink"] .= trim( $array_line[$e] );
                    }
                }
            }
                        
            
            if ( $i+1 == count($file_in_array) )
            {
                
                if ( count($module_data) >= 5 )        
                {
                    return $module_data;
                }
                return false;            
            }

        }

    }

    /**
     * agrega una entrada a la base de datos indicando que existe un modulo.
     * estos datos son mostrador en el listado de modulos.
     * devuelve true si los datos se guardan correctamente
     * de lo contrario false
     */
    public function install( $conn , $module_data = array() )
    {       
        
        // si el campo islink no esta seteado por defecto es 0;
        if ( !isset($module_data['islink']) ) { $module_data['islink'] = "0"; };

        /* $module_data = [
            "name"                  => "Clock Timer 3",
            "description"           => "Reloj digital 2",
            "version"               => "1.0.0",
            "author"                => "Alberto Sanchez",
            "web"                   => "https://midominio.com",  
            "islink"                => "1",  
            "installed"             => "1",
            "tables_name"           => "<string>",
            "views_name"            => "<string>",
            "store_procedures_name  => "<string>"
        ]; */       
       
        if($conn && is_array($module_data) && count($module_data) == 10)
        {

            $query = "INSERT INTO " . PREFIX . "module_status (
                name,
                description,
                version,
                author,
                web,
                installed,
                islink,
                tables_name,
                views_name,
                store_procedures_name
            )  VALUES (
                :name,
                :description,
                :version,
                :author,
                :web,
                :installed,
                :islink,
                :tables_name,
                :views_name,
                :store_procedures_name
            )";

            $statement = $conn->prepare($query);

            $statement->execute(array(                
                ":name"                 => $module_data["name"],
                ":description"          => $module_data["description"],
                ":version"              => $module_data["version"],
                ":author"               => $module_data["author"],
                ":web"                  => $module_data["web"],            
                ":installed"            => $module_data["installed"],
                ":islink"               => $module_data["islink"],            
                ":tables_name"          => $module_data["tables_name"],
                ":views_name"           => $module_data["views_name"],
                ":store_procedures_name"=> $module_data["store_procedures_name"]
            ));
            
            $query = "SELECT * FROM " . PREFIX . "module_status 
                    WHERE name = :name and version = :version and author = :author LIMIT 1";

            $statement = $conn->prepare($query);

            $statement->execute(array(                
                ":name"          => $module_data["name"],                
                ":version"       => $module_data["version"],
                ":author"        => $module_data["author"]                           
            ));

            $result = $statement->fetchAll();
            
            if ( is_array($result) && count($result) > 0 )
            {
                foreach ($result as $item) {
                        
                    if ( isset($item["name"]) )
                    {
                        return true;
                    }
                    
                }
            }
        }
        
        return false;

    }

    /** 
     * eliminar una entrada a la base de datos indicando que no existe un modulo.
     * estos datos son mostrados en el listado de modulos.
     * devuelve true si los datos se eliminan correctamente
     * de lo contrario false
     */
    public function uninstall($conn,$module_id = "" )
    {        
        
        /* $module_id = "1" */               
        
        if($conn && isset($module_id) && $module_id !== "")
        {
            // ------------------------------- continuar aqui
            $query = "call " . PREFIX . "sp_delete_module(:module_id)";

            $statement = $conn->prepare($query);

            $statement->execute(array(                
                ":module_id"     => $module_id,                    
            ));
            
            $query = "SELECT * FROM " . PREFIX . "module_status 
                    WHERE id = :module_id LIMIT 1";

            $statement = $conn->prepare($query);

            $statement->execute(array(                
                ":module_id"    => $module_id,                                         
            ));

            $result = $statement->fetchAll();
            
            if ( is_array($result) && (count($result) == 0) )
            { 
                return true;
            }
        }
        
        return false;

    }

    /**
     * comprueba la existencia de un modulo, si existe devuelve true
     * de los contrario false.
     */
    public function module_exists($conn,$module_data = array() )
    {
        if($conn && is_array($module_data) && count($module_data) == 6)
        {

            $query = "SELECT * FROM " . PREFIX . "module_status 
                    WHERE name = :name and version = :version LIMIT 1";

            $statement = $conn->prepare($query);

            $statement->execute(array(                
                ":name"          => $module_data["name"],                
                ":version"       => $module_data["version"]                         
            ));

            $result = $statement->fetchAll();
            
            if ( is_array($result) && count($result) > 0 )
            {
                foreach ($result as $item) {
                        
                    if ( isset($item["name"]) )
                    {
                        return true;
                    }
                    
                }

            }
        }
        
        return false;   
    }

    /**
     * devuelve un array con los datos de los modulos
     * limitado por posicionamiento inicial y final
     */
    public function get_list($conn,$selected_page = '0')
    {
        // limite de modulos a mostrar por pagina
        // ROWS_PER_PAGE_IN_MODULES -> ver config.php
        $limit = ROWS_PER_PAGE_IN_MODULES;

        // posicion inicial de la consulta
        $start_pos = 0;
        
        $current_page = ( (int)$selected_page <= 1 ) ? 1 : (int)$selected_page;
        
        $start_pos = ($current_page == 1 ) ? 0 : ($limit * $current_page) - $limit;
             
        
        $query = "SELECT * FROM ".DEFAULT_DATABASE."." . PREFIX . "module_status ORDER BY name LIMIT $start_pos, $limit";
       
        $statement = $conn->prepare($query);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        // echo var_dump($result), die();

        if( is_array($result) && count($result) > 0 )
        {
                        
            $query = "SELECT COUNT(*) FROM ".DEFAULT_DATABASE."." . PREFIX . "module_status";   
        
            $statement = $conn->prepare($query);

            $statement->execute();
            $count_result = $statement->fetchAll();

            // numero pagina siguiente
            $next_page      = ( ($current_page * $limit) >= $count_result[0][0] ) ? "" : $current_page+1;

            // numero pagina anterior
            $prev_page      = ($current_page-1 <= 0) ? "" : $current_page-1;

            // indica cantidad de paginas a mostrar
            $pages      = ceil($count_result[0][0] / $limit);
            $first_page = 1;
            $last_page  = $pages;

            $result = [
                'pagination'    =>  [
                    'counter'           =>  $count_result[0][0],
                    'limit'             =>  $limit,
                    'first_page'        =>  $first_page,
                    'current_page'      =>  $current_page,
                    'last_page'         =>  $last_page,
                    'next_page'         =>  $next_page,
                    'prev_page'         =>  $prev_page,
                    'star_pos'          =>  $start_pos,
                    'pages'             =>  $pages,
                    'selected_page'     =>  $current_page
                ],
                'data'          =>  $result
            ];
            
            //echo var_dump($result); die();

            return $result;            
        }
        else
        {
            return false;
        }      
    }

    /**
     * devuelve un array con los datos de todos los modulos
     */
    public function get_all_modules($conn)
    {
        
        $query = "SELECT * FROM ".DEFAULT_DATABASE."." . PREFIX . "module_status ORDER BY 'name'";
       
        $statement = $conn->prepare($query);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        if( is_array($result) && count($result) > 0 )
        {            
            return $result;            
        }
        else
        {
            return false;
        }      
    }

    /**     
     * devuelve un array con los datos de todos los modulos
     * de lo contrario devuelve false
     */
    public function get_module_data_from_id($conn,$module_id)
    {
        
        $query = "SELECT * FROM ".DEFAULT_DATABASE."." . PREFIX . "module_status WHERE id = :module_id LIMIT 1";
       
        $statement = $conn->prepare($query);
        
        $statement->execute(array(
            ':module_id'    => $module_id
        ));
        
        $result = $statement->fetchAll();
        
        if( is_array($result) && count($result) > 0 )
        {            
            return $result;            
        }
        else
        {
            return false;
        }      
    }

    public function module_status($conn,$module_id,$action)
    {
        if ($action == "active")
        {
            $query = "UPDATE ".DEFAULT_DATABASE."." . PREFIX . "module_status SET activation = 1 WHERE id = :module_id";
        }
        else if ( $action == "inactive")
        {
            $query = "UPDATE ".DEFAULT_DATABASE."." . PREFIX . "module_status SET activation = 0 WHERE id = :module_id";
        }
        
        $statement = $conn->prepare($query);
        $statement->execute(array(
            ":module_id"   => $module_id
        ));

        $query = "SELECT * FROM ".DEFAULT_DATABASE."." . PREFIX . "module_status WHERE id = :module_id LIMIT 1";

        $statement = $conn->prepare($query);
        $statement->execute(array(
            ":module_id"   => $module_id
        ));

        $result = $statement->fetchAll();

        if (is_array($result) && count($result) > 0){
            return $result;
        }

        return false;
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