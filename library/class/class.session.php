<?php
/**
 * Esta clase verifica que el token de session existe.
 * Si existe lo obtiene y actualiza su entidad en la base de datos.
 * 
 */

namespace Library\Classes;

class Session {
    
    private static $instance;  

    // este medoto verifica que el usuario sea administrado y la session este activa
    // devuelve true , de lo contrario false
    public function check_session_and_role_from_admin_id($conn,$admin_id)
    {

        if( $conn && null !== (int)$admin_id )
        {
            $query = "SELECT sd.role_id, ss.expire_session_id FROM " . PREFIX . "users_security_data as sd
                        JOIN " . PREFIX . "users_security_session as ss
                            ON sd.user_id = ss.user_id
                                WHERE sd.user_id = :id and ss.user_id = :id LIMIT 1";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':id' =>  $admin_id
            ));

            $result = $statement->fetchAll();
            
            if($result)
            {
                foreach ($result as $item) {
                    // role : 1 -> administrator | session : 1 -> correct
                    if ( $item['role_id'] == "1" && $item['expire_session_id'] == "1" )
                    {
                        return true;
                    }                    
                }   
            }
        }
        return false;
    }

    // Este metodo recibe una cadena url : e.g. https://dominio.com/sub/4345342666636
    // luego extrae el token de dicha url y el id de usuario.
    // retorna un array con los datos obtenidos, sino false.
    public function get_token_and_user_id_from_url($url = null)
    {
        if ($url != null)
        {
            // convierto la url en un array
            $urlArray = explode("/",$url);
            
            $session_token = "";

            for ($i=0; $i < count($urlArray); $i++) { 

                if (strlen((int)$urlArray[$i]) > 9)
                {
                    $session_token = $urlArray[$i];
                }                
            }
            
            if ( strlen($session_token) > 9)
            {
                
                // obtengo el id de usuario
                $user_id = substr($session_token,9);
                
                // obtengo el session_token
                $session_token = substr($session_token,0,9);
    
                //devuelvo un array con el token de sesion y el id de usuario
                $array = array($session_token,$user_id);
    
                return $array;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }    

    // extrae el token y el id de usuario.
    // retorna un array con los datos obtenidos, sino false.
    public function get_token_from_submited_token($submited_token = null)
    {
        if ($submited_token != null)
        {
            // obtengo el id de usuario
            $user_id = substr($submited_token,9);
                
            // obtengo el session_token
            $session_token = substr($submited_token,0,9);

            //devuelvo un array con el token de sesion y el id de usuario
            $array = array($session_token,$user_id);

            return $array;
        }
        else
        {
            return false;
        }
    }

    // veficamos el token y el user id en la base de datos
    // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
    // si no es falso
    public function verify_token_and_id_in_db($conn,$array_token_and_user_id)
    {
        $session_token = !empty($array_token_and_user_id['session_token']) ? $array_token_and_user_id['session_token'] : null;
        $user_id = !empty($array_token_and_user_id['user_id']) ? $array_token_and_user_id['user_id'] : null;
        
        // preparamos la consulta para obtener los datos de session
        $query = "SELECT * FROM " . PREFIX . "users_security_session 
                    WHERE user_id = :user and session_token = :session_token";            

        $statement = $conn->prepare($query);

        // ejecutamos la consulta
        $statement->execute(array(            
            ':user' => $user_id,
            ':session_token' => $session_token
        ));

        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();
        
        // establece la zona horaria del servidor        
        $timeZone = 'America/Santo_Domingo'; 
        date_default_timezone_set($timeZone);
        
        //Pregunto si la consulta es correcta.
        if(isset($result[0]) && count($result[0]) > 0)
        {
            
            foreach ($result as $item) {
                $last_update = $item['updated_at'];   
                $expire_session_id = $item['expire_session_id'];   
            }
            
            // si la variable esta seteada
            if(isset($last_update))
            {
                $last_update = strtotime($last_update);
                $current_date = time();
            }
            else
            {
                return false;                
            }     
                        
            // si paso 10 minutos desde la ultima actualizacion de session
            // entonces devuelve 0, cierra session            
            if ( ($current_date - $last_update) > (SESSION_INTERVAL / 1000) or $expire_session_id == 2 )
            {    
                // expire_session_id = 2 -> expirada    
                $query = "UPDATE " . PREFIX . "users_security_session
                SET expire_session_id = 2
                    WHERE user_id = :user";            

                $statement = $conn->prepare($query);
                // ejecutamos la consulta
                $statement->execute(array(':user' => $user_id));

                return false;                
            }
            else
            {
                // preparamos la consulta para actualizar la fecha
                // expire_session_id = 1 -> activa                
                $query = "UPDATE " . PREFIX . "users_security_session
                SET updated_at = current_timestamp(), expire_session_id = 1
                    WHERE user_id = :user";            

                $statement = $conn->prepare($query);
                // ejecutamos la consulta
                $statement->execute(array(':user' => $user_id));

                foreach ($result as $item) {
                    $session_token = $item['session_token'];                
                    $user_id = $item['user_id'];                
                }

                //devuelvo un array con el token de sesion y el id de usuario
                $array = array($session_token,$user_id);
                
                return $array;
            }
        }
    }

    // este metodo destruye la session, si logra destruirla devuelve true
    // de los contrario devuelve false
    public function destroy_session($conn,$destroy_session = false,$user_id = null)
    {
        if ($destroy_session == true & $user_id != null)
        {  
            // preparamos la consulta para obtener los datos de session
            $query = "UPDATE " . PREFIX . "users_security_session 
                        SET expire_session_id = 2
                        WHERE user_id = :user_id";            

            $statement = $conn->prepare($query);

            // ejecutamos la consulta
            $statement->execute(array(            
                ':user_id' => $user_id
            ));
            
            // preparamos la consulta para obtener los datos de session
            $query = "SELECT * FROM " . PREFIX . "users_security_session                     
                            WHERE user_id = :user_id";            

            $statement = $conn->prepare($query);

            // ejecutamos la consulta
            $statement->execute(array(            
                ':user_id' => $user_id
            ));

            $result = $statement->fetchAll();            
            
            foreach ($result as $row)
            {   
                $result = array_map('utf8_encode',$row);
            }
            
            if($result['expire_session_id'] == 2)
            {
                return true;
            }
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