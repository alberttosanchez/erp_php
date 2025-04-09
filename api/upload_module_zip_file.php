<?php

require_once('./../admin/config.php');
require_once('./functions.php');

// incluimos el directorio de clases
foreach ( glob(  CLASSES_DIRECTORY . '*.php') as $filename){ include_once $filename;}

/* require_once('./../library/class/class.conn.php');
require_once('./../library/class/class.session.php');
require_once('./../library/class/class.person.php');
require_once('./../library/class/class.files.php');
require_once('./../library/class/class.modules.php'); */

$target = isset($_POST['target']) ? cleanData($_POST['target']) : "";

if ( 
    $_SERVER['REQUEST_METHOD'] == 'POST' && 
    $target == 'upload_module'
)
{

    // instanciamos la clase conexion
    $Conexion = new Library\Classes\Conexion;
    
    // obtenemos la conexion, para $dbConfig ver config.php
    $conn = $Conexion->get($dbConfig);

    // instanciamos la clase sesion
    $Session = new Library\Classes\Session;

    // verificamos que el rol de admin y la session esten correctos
    // devuelve true si todo esta bien, de lo contrario false
    $result = $Session->check_session_and_role_from_admin_id($conn,$_POST['user_id']);
    
    if($result)
    {
        // obtenemos la extension del archivo a subir
        $ext = get_file_extension($_FILES['zip_file']['name']);
        
        //echo var_dump($ext);
    
        // verificamos el tamaño del archivo a subir
        if ($_FILES['zip_file']['size'] > 512000000 )
        {
            http_response_code(403);
            $result = [
                "status"    => "403",
                "messsage"  => "El tamaño máximo del archivo debe ser 500mb."
            ];        
        }
        // verificamos la extension del archivo a subir
        else if (         
            ( $_FILES['zip_file']['type'] == "application/x-zip-compressed" && ($ext == "zip" ) )        
        )
        {        
            if (
                isset($_POST['session_token']) && 
                isset($_POST['user_id'])  && 
                isset($_POST['user_role_id']) 
            )
            {
                //instanciamos la clase conexion
                $Conexion = new Library\Classes\Conexion;
        
                //obtenemos la conexion
                $conn = $Conexion->get($dbConfig);
        
                // verificamos la conexion
                if($conn)
                {
        
                    $session_token  = cleanData($_POST['session_token']);
                    $user_id        = cleanData($_POST['user_id']);
                    $user_role_id   = cleanData($_POST['user_role_id']);
        
                    $array_token_and_user_id = [
                        "session_token" => $session_token,
                        "user_id"       => $user_id
                    ];
        
                    // instanciamos la session
                    $Session = new Library\Classes\Session;
        
                    // veficamos el token y el user id en la base de datos
                    // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
                    // si no es falso
                    $result = $Session->verify_token_and_id_in_db($conn,$array_token_and_user_id);
                    
                    //echo var_dump($result);
    
                    if($result)
                    {
                        $user_conected_id = $result[1];
        
                        // instanciamos la clase persona.
                        $Person = new Library\Classes\Person;
                        
                        // verificamos si el usuario es administrador.
                        if( $Person->is_user_an_admin($conn,$user_id) )
                        {
                            $zip_file = ( isset($_FILES['zip_file']) ) ? $_FILES['zip_file'] : null;
                            
                            // si el archivo existe
                            if ($zip_file)
                            {
        
                                $file_temp_name = $zip_file['tmp_name'];
                                
                                // instanciamos la clase Files
                                $Files = new Library\Classes\Files;
    
                                // verifica si el archivo zip es valido y
                                // devuelve la extension del tipo mime si es zip
                                // de lo contrario false
                                $ext = $Files->get_zip_mime_type($zip_file);
                                //echo var_dump($ext); die();
                                if($ext)
                                {
    
                                    // Instanciamos la clase ZipArchive agregada a php como una extension.
                                    // ver php zip extension(modulo)
                                    $Zip = new ZipArchive;
    
                                    // descomprimimos el archivo zip
                                    $zip_opened = $Zip->open($file_temp_name);
                                    
                                    if( $zip_opened )
                                    {
    
                                        $temp_mod_dir_name = "temp_module_dir";                                    
                                        // ruta directorio que contiene el modulo
                                        $temp_module_path = MODULE_DIR_PATH.$temp_mod_dir_name."/";
                                                                         
                                        // extraemos el archivo zip en la ruta indicada.
                                        $zip_extrated = $Zip->extractTo($temp_module_path);
                                        //echo var_dump($zip_extrated); die();
                                        $zip_close = $Zip->close();
                                        
                                        //glob devuelve un array con todos los archivos y directorios
                                        $array_with_files_on_temp_dir = glob( $temp_module_path . "*" );
                                        
                                        // verificamos que la extraccion y el cierre del zip este completa.
                                        // si el archivo zip fue extraido y cerrado
                                        if ( $zip_extrated && $zip_close )
                                        {                                          
                                            // iniciamos un ciclo para verificar los archivos y directorios
                                            // enviamos un array con la estructura de archivos y directorios
                                            $counter = 0;
                                            //foreach ( $array_with_files_on_temp_dir as $file ) {
                                            for ($i=0; $i < count($array_with_files_on_temp_dir); $i++) { 
                                                    
                                                // verificamos que sea un archivo
                                                if ( is_file( $array_with_files_on_temp_dir[$i] ) )
                                                {
                                                    $counter++;
                                                    //creamos un array con la cadena que represente el path                                         
                                                    $string_to_array = explode("/", $array_with_files_on_temp_dir[$i] );
                                                        
                                                    // iniciamos un ciclo para obtener el archivo de instalacion
                                                    // del modulo
                                                    $found = 0;                                                
                                                    for ( $u=0; $u < count($string_to_array); $u++ ) {  
                                                        
                                                        // comprobamos que el path sea un archivo extension .php
                                                        if ( strpos($string_to_array[$u],".php") > -1 )
                                                        {
                                                            
                                                            $found = 1;                                                       
                                                            
                                                            // convertimos un archivo.php a un array linea por linea
                                                            $file_in_array = file($array_with_files_on_temp_dir[$i]);
                                                            
                                                            if( is_array($file_in_array) && count($file_in_array) > 0 )
                                                            {
                                                                // iniciamos el ciclo para encontrar cadenas especificas.
                                                                $found = 0;                                                            
                                                                for ( $g=0; $g < count($file_in_array); $g++ ) {
                                                                    
                                                                    // si encuentra la cadena 'Module Name' en el archivo leido
                                                                    if ( strpos( $file_in_array[$g] , "Module Name" ) > -1 && $found == 0 )
                                                                    {
                                                                        $found = 1;                                                                    
                                                                        
                                                                        $Modules = new Library\Classes\Modules;
                                                                        
                                                                        // obtiene un array asociativo con los datos del modulo
                                                                        // $module_data["name"]
                                                                        // $module_data["version"]
                                                                        // $module_data["description"]
                                                                        // $module_data["author"]
                                                                        // $module_data["web"]
                                                                        $module_data = $Modules->getModuleDataFromFileInArray($file_in_array);
                                                                        
                                                                        
                                                                        // indicamos el valor del modulo que indica que ha sido instalado en la base de datos.
                                                                        $module_data["installed"] = ! isset( $module_data["installed"] ) ? '1' : $module_data["installed"];
                                                                        
                                                                        //var_dump($module_data); die();

                                                                        if ( is_array($module_data) )
                                                                        {
                                                                                $module_data['name'] = isset($module_data['name']) ? ucwords(strtolower($module_data['name'])) : "";
                                                                                
                                                                                // comprobamos que el modulo no existe en la base de datos
                                                                                if ( ! $Modules->module_exists($conn,$module_data) )
                                                                                {
    
                                                                                    //obtenemos el nombre del modulo
                                                                                    if ( isset($module_data["name"]) && strlen($module_data["name"]) > 0 )
                                                                                    {
                                                                                        // reemplazamos espacios por guiones y
                                                                                        // convertimos caracteres en minusculas 
                                                                                        $module_name = str_replace( " " , "-" , strtolower($module_data["name"]) );
                                                                                        
                                                                                        if ( is_dir(MODULE_DIR_PATH.$module_name) )
                                                                                        {
                                                                                            $temp_dir_was_deleted = removeDirAndFiles(MODULE_DIR_PATH.$module_name);
                                                                                        }
                                                                                        
                                                                                        // si rename() no funciona entonces crea una copia del directorio 
                                                                                        // indicado con el nuevo nombre y borra el viejo
                                                                                        if ( ! @rename(MODULE_DIR_PATH.$temp_mod_dir_name,MODULE_DIR_PATH.$module_name) )
                                                                                        {
                                                                                            
                                                                                            //verificamos si existe un directorio con el mismo nombre
                                                                                            if ( is_dir(MODULE_DIR_PATH.$module_name) )
                                                                                            {
                                                                                                $temp_dir_was_deleted = removeDirAndFiles($temp_module_path);
                                                                                                
                                                                                                http_response_code(403);
                                                                                                $result = [
                                                                                                    'status'    => '403',
                                                                                                    'message'   => 'Ya existe un modulo con el mismo nombre.'
                                                                                                ];
                                                                                                
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                
                                                                                                // crea una copia del directorio indicado.
                                                                                                recurseCopy( MODULE_DIR_PATH.$temp_mod_dir_name , MODULE_DIR_PATH.$module_name );
                                                                                                
                                                                                                $temp_dir_was_deleted = removeDirAndFiles($temp_module_path);
                                                                                                
                                                                                                if( $temp_dir_was_deleted )
                                                                                                {   
                                                                                                    
                                                                                                    $array_with_files_and_dir = glob(MODULE_DIR_PATH.$module_name."/*");
                                                                                            
                                                                                                    // buscamos el archivo de instalacion y lo renombramos
                                                                                                    // a functions.php
                                                                                                    foreach ($array_with_files_and_dir as $file) {
                                                                                                        
                                                                                                        if ( strpos($file,".php") > -1 )
                                                                                                        {
                                                                                                            
                                                                                                            $file_in_array = file($file);
        
                                                                                                            foreach ($file_in_array as $line) {
                                                                                                                
                                                                                                                if ( strpos($line,"Module Name") > -1 )
                                                                                                                {
                                                                                                                    rename( $file , MODULE_DIR_PATH . "/" . $module_name . "/" . "functions" . ".php" );
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
        
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    http_response_code(403);
                                                                                                    $result = [
                                                                                                        'status'    => '403',
                                                                                                        'message'   => 'Error al descomprimir modulo. Contacte al administrador de sistemas.'
                                                                                                    ];
                                                                                                }
        
                                                                                            }
        
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            
                                                                                            $array_with_files_and_dir = glob(MODULE_DIR_PATH.$module_name."/*");                                                                                    
                                                                                            
                                                                                            //buscamos el archivo de instalacion y lo renombramos
                                                                                            foreach ($array_with_files_and_dir as $file) {
                                                                                                
                                                                                                if ( strpos($file,".php") > -1 )
                                                                                                {
                                                                                                    $file_in_array = file($file);
        
                                                                                                    foreach ($file_in_array as $line) {
                                                                                                        
                                                                                                        if ( strpos($line,"Module Name") > -1 )
                                                                                                        {
                                                                                                            rename( $file , MODULE_DIR_PATH."/".$module_name."/" . "functions" . ".php" );
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }    
                                                                                            
                                                                                        }                                                                                        
                                                                                        
                                                                                        
                                                                                        // verificamos que el archivo NO_SQL.txt no existe
                                                                                        if ( !is_dir(MODULE_DIR_PATH . $module_name . "/sql") ){

                                                                                            $array_with_sql_scripts_files = [];
                                                                                            $module_data['tables_name'] = "";
                                                                                            $module_data['views_name'] = "";
                                                                                            $module_data['store_procedures_name'] = "";
                                                                                            
                                                                                            $result = true;                                                                                            
                                                                                        }
                                                                                        else
                                                                                        {

                                                                                            // obtenemos la lista de tablas, vistas,y procedimientos almacenados que se instalaran.
                                                                                            // para ser guardados en la base de datos.
                                                                                            $array_with_sql_scripts_files = glob( MODULE_DIR_PATH . $module_name . "/sql/*.sql" , GLOB_MARK );                                                                                                                                                                                 
    
                                                                                            // instanciamos la clase Files
                                                                                            $Files = new Library\Classes\Files;
                                                                                            
                                                                                            $array_with_module_tables_name = $Files->get_module_entities_names( $array_with_sql_scripts_files , 'MYSQL_TABLE' );                                                                                                                                                                               
                                                                                            //var_dump($array_with_module_tables_name); die();
                                                                                            
                                                                                            $array_with_module_views_name = $Files->get_module_entities_names( $array_with_sql_scripts_files , 'MYSQL_VIEWS' );
                                                                                            //var_dump($array_with_module_views_name);  die();                                                                                        
                                                                                            
                                                                                            $array_with_module_store_procedure_name = $Files->get_module_entities_names( $array_with_sql_scripts_files , 'MYSQL_STORE_PROCEDURE' );
                                                                                            //var_dump($array_with_module_store_procedure_name); die();
    
                                                                                            $module_data['tables_name'] = implode( ',' , $array_with_module_tables_name );
                                                                                            $module_data['views_name'] = implode( ',' , $array_with_module_views_name );
                                                                                            $module_data['store_procedures_name'] = implode( ',' , $array_with_module_store_procedure_name );
                                                                                        }
                                                                                            //var_dump($module_data); die();
    
                                                                                            //instanciamos la clase Modules
                                                                                            $Modules = new Library\Classes\Modules;
                                                                                            
                                                                                            /*
                                                                                            $module_data = [
                                                                                                "name"                  => "Clock Timer",
                                                                                                "description"           => "Reloj digital",
                                                                                                "version"               => "1.0.0",
                                                                                                "author"                => "Alberto Sanchez",
                                                                                                "web"                   => "https://midominio.com", 
                                                                                                "islink"                => "1",
                                                                                                "installed"             => "1",
                                                                                                "tables_name"           => "string",
                                                                                                "views_name"            => "string",
                                                                                                "store_procedures_name  => "string"           
                                                                                            ];
                                                                                            */
                                                                                            
                                                                                            // agrega una entrada a la base de datos indicando que existe un modulo.
                                                                                            // estos datos son mostrador en el listado de modulos.
                                                                                            // devuelve true si los datos se guardan correctamente
                                                                                            // de lo contrario false
                                                                                            $result = $Modules->install($conn,$module_data);
                                                                                            
                                                                                            $ManageDB = new Library\Classes\ManageDB;
                                                                                            
                                                                                            if ( count( $array_with_sql_scripts_files) > 0)
                                                                                            {
                                                                                                // Crear una base de datos y sus tablas con los datos a traves de un conjunto de 
                                                                                                // archivos .sql o un unico archivo sql.
                                                                                                // si la base de datos existe crear las tablas y sus registros
                                                                                                // tambien crear todo las vistas, procedimientos, disparadores, etc.
                                                                                                // recibe la ruta que contiene los archivos scripts
                                                                                                $result = $ManageDB->create_schema_by_files_scripts(  $array_with_sql_scripts_files );
                                                                                            }
                                                                                           
                                                                                        
                                                                                        
                                                                                        if ($result)
                                                                                        {

                                                                                            http_response_code(200);
                                                                                            $result = [
                                                                                                'status'    => '200',
                                                                                                'message'   => 'Modulo Instalado. Redirigiendo al panel de Modulos Instalados.'
                                                                                            ];
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            http_response_code(403);
                                                                                            $result = [
                                                                                                'status'    => '403',
                                                                                                'message'   => 'Error desconocido. Modulo No instalado.'
                                                                                            ];
                                                                                        }
        
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        http_response_code(403);
                                                                                        $result = [
                                                                                            'status'    => '403',
                                                                                            'message'   => 'Error desconocido. Contacte al administrador de sistemas.'
                                                                                        ];
                                                                                    }
    
                                                                                }
                                                                                else
                                                                                {
                                                                                    $result = removeDirAndFiles($temp_module_path);
    
                                                                                    http_response_code(403);
                                                                                    $result = [
                                                                                        'status'    => '403',
                                                                                        'message'   => 'El modulo ya existe.'
                                                                                    ];
                                                                                }
    
                                                                            }  
    
                                                                    }
                                                                    else if ($found == 0 && ( $i+1 == count($array_with_files_on_temp_dir) ) )
                                                                    {   
                                                                        
                                                                        $result = removeDirAndFiles($temp_module_path);
    
                                                                        http_response_code(403);
                                                                        $result = [
                                                                            'status'    => '403',
                                                                            'message'   => 'Archivo de instalación no encontrado.'
                                                                        ];
                                                                    
                                                                    }                                                                
                                                                    
                                                                }
                                                                
                                                            }
                                                            
                                                        }
                                                        else if ($found == 0 && ($i+1 == count($array_with_files_on_temp_dir)) )
                                                        {   
    
                                                            $result = removeDirAndFiles($temp_module_path);
    
                                                            http_response_code(403);
                                                            $result = [
                                                                'status'    => '403',
                                                                'message'   => 'Archivo de instalacion no encontrado..'
                                                            ];
                                                        }
    
                                                    }
                                                    
                                                }
                                                else if ($counter == 0 && $i+1 == count($array_with_files_on_temp_dir) )
                                                {   
                                                    
                                                    $result = removeDirAndFiles($temp_module_path);
    
                                                    http_response_code(403);
                                                    $result = [
                                                        'status'    => '403',
                                                        'message'   => 'Archivo de instalacion no encontrado...'
                                                    ];
                                                
                                                }
                                            }
                                            
                                        }
                                        else
                                        {
    
                                            // verificamos que el directorio temporal contenga archivos y carpetas
                                            if ( count($array_with_files_on_temp_dir) > 0)
                                            {                                            
                                             
                                                // devuelve true si elemina todo el directorio 
                                                // pasado como parametro
                                                $result = removeDirAndFiles($temp_module_path);
    
                                                if( $result )
                                                {
                                                    
                                                    http_response_code(409);
                                                    $result = [
                                                        'status'    => '409',
                                                        'message'   => 'Extracción incompleta. Compruebe el archivo ZIP..'
                                                    ];
    
                                                }
                                                else
                                                {
                                                    http_response_code(406);
                                                    $result = [
                                                        'status'    => '406',
                                                        'message'   => 'Extracción incompleta. Contacte al administrador de sistemas.'
                                                    ];
                                                }
    
                                            }
                                            else
                                            {
    
                                                http_response_code(409);
                                                $result = [
                                                    'status'    => '409',
                                                    'message'   => 'Extracción incompleta. Compruebe el archivo ZIP.'
                                                ];
    
                                            }
                                            
                                        }
    
                                    }
                                    else
                                    {
                                        http_response_code(406);
                                        $result = [
                                            'status'    => '406',
                                            'message'   => 'El archivo no pudo descomprimirse correctamente.'
                                        ];
                                    }
                                    
                                }
                                else
                                {
                                    http_response_code(403);
                                    $result = [
                                        'status'    => '403',
                                        'message'   => 'El archivo zip no es valido.'
                                    ];
                                }
                            }
                            else
                            {
                                http_response_code(403);
                                $result = [
                                    "status"    => "403",
                                    "message"   => "Debe cargar una imagen."
                                ];
                            }
                        }
                        else
                        {
                            http_response_code(401);
                            $result = [
                                "status"    => "401",
                                "message"   => "No tiene autorización. Token invalido."
                            ];
                        }
                    }
                    else
                    {
                        http_response_code(401);
                        $result = [
                            "status"    => "401",
                            "message"   => "No tiene autorización. Token invalido."
                        ];
                    }
        
                }
                else
                {
                    http_response_code(409);
                    $result = [
                        "status"    => "409",
                        "message"   => "El servidor no responde. Contacte a su administrador."
                    ];  
                }
            }
            else
            {            
                $result = [
                    "status"    => "400",
                    "message"   => "Faltan parametros"
                ]; 
            }
        }    
        else
        {
            http_response_code(403);
            $result = [
                "status"    => "403",
                "message"  => "Archivo formato desconocido."
            ];
        }

    }
    else
    {
        http_response_code(401);
        $result = [            
            'status' => "401",
            'messge' => "No tiene autorizacion."
        ];  
    }
}
else
{
    $result = [
        "status"    => "400",
        "message"   => "Faltan parametros"
    ];
}
header("Content-Type:application/json");
echo json_encode($result);
die();