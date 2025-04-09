<?php
    
    /**
     * Clase Conexion
     * 
     * METODOS:
     * 
     * singletonConexion()  -> public static : crea objeto si no existe, si existe lo invoca.
     * 
     * __clone()            -> private : evita duplicar el objeto.
     * 
     * conn($typeConexion,$IdentityCharacterForDatabase)
     *                    -> public : conexion a base de datos.
     * 
     * PROPIEDADES:
     * 
     * $instance   -> private static : 
     * $serverName  -> public static  :
     * $typeConexion-> public static  : recibe un numero para elegir el user y password 
     *                                  que se usará para la conexion de base de datos.
     *                                  dicho valor se obtiene de switch enviando un numero
     *                                  del 1 al 4. (ver incova la conexion)
     * $IdentityCharacterForDatabase
     *              -> public static  : obtiene el valor para el nombre de la base de 
     *                                  datos del servidor.
     * $reader[]    -> array private
     * $operator[]  -> array private
     * $admin[]     -> array private
     * $abd[]       -> array private
     */

    namespace Library\Classes;
    
    use mysqli;
    use mysqli_query;
    use PDO;
    use PDOException;

    class Conexion {

        private static $instance;    
        private $serverName = "localhost";  # nombre del servidor.
        public static $typeConexion; # obtiene el valor para el user Y password del servidor.
        public static $CharacterForIdentifyDatabase; # obtiene el valor para la base de datos del servidor.
        
        
    # ----------------------------------------------------   
        # método singleton
        public static function singleton() {       
            
            if (!isset(self::$instance)) {
                $myclass = __CLASS__; # __CLASS__ devuelve el nombre de esta clase.
                self::$instance = new $myclass;
            } 
    
            return self::$instance;
    
        }
    # ----------------------------------------------------
        # invoca la conexion.
        public function get($Config) {                          
            //print_r($Config);
            
            try {                
                $conn = new PDO('mysql:host='.$Config['host'].';dbname='.$Config['db_name'].';charset=utf8mb4',$Config['user'],$Config['password']);                                                    
                return $conn;

            } catch ( PDOException $Exception ) {

                # Si hay error se ejecuta este codigo.
                //echo $Exception;
                http_response_code(503); 
                $result = [
                        'status'    => '503',
                        'message'   => 'No se puede establecer una conexión, contacte al administrador.',
                        'error'     => $Exception->getMessage(),
                    ];
                echo json_encode($result);
                die(); 
                # Con die matamos la ejecucion del codigo.
            }
            
        }
        /**
         * Obtenemos una conexion a mysqli (orientado a conexion a objetos);
         */
        public function get_mysqli($Config){

            try {
                
                $hostname = $Config['host'];
                $username = $Config['user'];
                $password = $Config['password'];
                $database = $Config['db_name'];
                //$port = ini_get("mysqli.default_port");
                //$socket = ini_get("mysqli.default_socket");
    
                $Mysqli = new mysqli(
                            $hostname,
                            $username,
                            $password,
                            $database
                            );

                //var_dump($Mysqli); die();
                return $Mysqli;

            } catch ( PDOException $Exception ) {
                $result = [
                    'status'    => '503',
                    'message'   => 'No se puede establecer una conexión, contacte al administrador.',
                    'error'     => $Exception->getMessage(),
                ];
                echo json_encode($result);                
                die(); 
                # Con die matamos la ejecucion del codigo.
            }
        }

        public function close_mysqli($mysqli_conn){
                $result = $mysqli_conn->close();
                if ($result){
                    return true;
                }
                return false;
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