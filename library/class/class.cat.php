<?php
/**
 * Esta clase se encarga de hacer consultas a la base de datos del IJOVEN
 * y datos de las tablas consideradas categorias (que contienen datos fijos)
 * 
 */
namespace Library\Classes;

class Categories {
    
    private static $instance; 

    public function get_details_filters($conn)
    {
        try {           
            
            $query = "SELECT id,search_filter,db_field FROM ".DEFAULT_DATABASE . "." . PREFIX . "cat_users_search_filter";
    
            $statement  = $conn->prepare($query);
            $statement->execute();
            $result     = $statement->fetchAll();
    
            return $result;

        } catch (Exception $e) {

            $result = array(
                0 => [
                    'status'    => '409',
                    'error_msg' => 'La conexion no pudo ser realizada. contacte a su administrador.'
                ]
            );

            return $result;
        }

    }
    
    public function get_role($conn)
    {
        try {           
            
            $query = "SELECT id,role_name FROM ".DEFAULT_DATABASE  . "." . PREFIX . "cat_roles";
    
            $statement = $conn->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
    
            return $result;

        } catch (Exception $e) {

            $result = [
                'status'    => '409',
                'message'   => 'Ref. SV-CAT-0001 - Categorias no obtenidas.'
            ];

            return $result;
        }

    }

    public function get_gender($conn)
    {
        try {           
            
            $query = "SELECT id,gender FROM ".DEFAULT_DATABASE  . "." . PREFIX . "cat_users_gender";
    
            $statement = $conn->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
    
            return $result;

        } catch (Exception $e) {

            $result = [
                'status'    => '409',
                'message'   => 'Ref. SV-CAT-0001 - Categorias no obtenidas.'
            ];

            return $result;
        }

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