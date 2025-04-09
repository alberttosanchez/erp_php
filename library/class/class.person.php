<?php

namespace Library\Classes;

class Person {
    
    private static $instance;

    // obtiene el nombre de la archivo de image del avatar
    public function remove_avatar_image($conn,$user_id)
    {
        $query = "CALL " . PREFIX . "sp_remove_avatar_from_profile(:user_id)";

        $statement = $conn->prepare($query);

        $statement->execute(array(
            ":user_id" => (int)$user_id
        ));

        $result = $statement->fetchAll();

        if(count($result) > 0)
        {
            foreach ($result as $item) {
                
                if ( isset($item['status']) && $item['status'] == "200" )
                {
                    return true;
                }
            }
        }
        return false;
    }

    // obtiene el nombre de la archivo de image del avatar
    public function get_avatar_image($conn,$user_id)
    {
        $query = "SELECT thumbnail FROM " . PREFIX . "users_profile WHERE user_id = :user";

        $statement = $conn->prepare($query);

        $statement->execute(array(
            ":user" => $user_id
        ));

        $result = $statement->fetchAll();
        
        if(count($result) > 0)
        {
            foreach ($result as $item) {
                
                if (isset($item['thumbnail']) && !empty($item['thumbnail']))
                {
                    return $item['thumbnail'];
                }
            }
        }
        return false;
    }

    // actualiza el nombre del archivo del avatar
    public function update_user_profile_image_name($conn,$user_id,$image_name)
    {   
        
        if( $conn && !empty($user_id) && !empty($image_name) )
        {
            $query = "CALL " . PREFIX . "sp_update_profile_image(:user_id,:thumbnail)";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user_id'   => (int)$user_id,
                ':thumbnail' => $image_name
            ));

            $result = $statement->fetchAll();                        
            
            foreach ($result as $item) {
                
                if ($item["status"] == "200")
                {
                    return true;
                }

            }
            
        }
        return false;
    }

    // verifica si el usuario a eliminar es el mismo que hace la peticion
    // devuelve true de lo contrario false
    public function is_user_the_same_user_to_delete($conn,$user_to_delete_id,$user_id)
    {
        if($conn)
        {
            $query = "SELECT id 
                        FROM " . PREFIX . "users_login_info
                            WHERE id = :user_id";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user_id' => $user_to_delete_id
            ));
            
            $result = $statement->fetchAll();

            if($result)
            {
                foreach ($result as $item) {
                    if ($item['id'] == $user_id)
                    {
                        return true;
                    }
                }                
            }
        }
        return false;
    }

    // verifica si el usuario que ejecuta la accion es administrador
    // devuelve true de lo contrario false
    public function is_user_an_admin($conn,$user_id)
    {
        if($conn)
        {
            $query = "SELECT role_id 
                        FROM " . PREFIX . "users_security_data
                            WHERE user_id = :user_id";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user_id' => $user_id
            ));
            
            $result = $statement->fetchAll();
            
            if($result)
            {
                foreach ($result as $item) {
                    if ($item['role_id'] == "1")
                    {
                        return true;
                    }
                }                
            }
        }

        return false;
    }

    // verifica si el usuario que ejecuta la accion es administrador o soporte
    // devuelve true de lo contrario false
    public function is_user_an_admin_or_support($conn,$user_id)
    {
        if($conn)
        {
            $query = "SELECT role_id 
                        FROM " . PREFIX . "users_security_data
                            WHERE user_id = :user_id";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user_id' => $user_id
            ));
            
            $result = $statement->fetchAll();
            
            if($result)
            {
                foreach ($result as $item) {
                    if ( $item['role_id'] == '1' || $item['role_id'] == '2' )
                    {
                        return true;
                    }
                }                
            }
        }
        return false;
    }

    public function delete_user_info_from_id($conn,$user_id)
    {
        if($conn)
        {
            $query = "call " . PREFIX . "sp_delete_user(:user_id);";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user_id' => $user_id
            ));
            
            $result = $statement->fetchAll();
            
            if($result[0]['status'] == "200")
            {
                return true;
            }
        }
        return false;
    }

    public function update_user_details($conn,$arrayData)
    {
        /*
            $arrayData = [
                'target'                => '',
                'role_id'               => '',
                'user_id'               => '',
                'first_name'            => '',
                'last_name'             => '',
                'users_goverment_id'    => '',
                'users_email'           => '',
                'users_phone'           => '',
                'gender_id'             => '',
                'birth_date'            => '',
            ]; 
        */

        $query = "call " . PREFIX . "sp_update_user_details(
                    :role_id,
                    :user_id,
                    :first_name,
                    :last_name,
                    :users_goverment_id,
                    :users_email,
                    :users_phone,
                    :gender_id,
                    :birth_date
                )";

        $statement = $conn->prepare($query);

        $statement->execute(array(
            ':role_id'              => $arrayData['role_id'],
            ':user_id'              => $arrayData['user_id'],
            ':first_name'           => $arrayData['first_name'],
            ':last_name'            => $arrayData['last_name'],
            ':users_goverment_id'   => $arrayData['users_goverment_id'],
            ':users_email'          => $arrayData['users_email'],
            ':users_phone'          => $arrayData['users_phone'],
            ':gender_id'            => $arrayData['gender_id'],
            ':birth_date'           => $arrayData['birth_date']
        ));

        $result = $statement->fetchAll();

        foreach ($result as $item) {
            $result = $item['status'];
        }

        if($result == "200")
        {
            return true;
        }
        else
        {
            return false;
        }

    }
    // devuelve los datos del usuarios por el id
    // de lo contrario false
    public function get_users_info_from_id($conn,$user_id)    
    {        
        if( 
            isset($user_id) && (strlen($user_id) > 0) && !empty($user_id) 
          )
        {
            $query = "SELECT * FROM ".DEFAULT_DATABASE.".view_users_info
                        WHERE user_id = :user LIMIT 1";

            $statement = $conn->prepare($query);

            $statement->execute(array(
                ':user' => $user_id
            ));
            
            $result = $statement->fetchAll();
        }
        
        

        if( count($result) > 0)
        {
            return $result;            
        }
        else
        {
            return false;
        }        
    }

    public function get_users_info_from_filter($conn,$db_field,$keyword,$selected_page = '0')    
    {           

        // limite de registros a mostrar por pagina
        // ROWS_PER_PAGE -> ver config.php
        $limit = ROWS_PER_PAGE;

        // posicion inicial de la consulta
        $start_pos = 0;
        
        $current_page = ( (int)$selected_page <= 1 ) ? 1 : (int)$selected_page;
        
        $start_pos = ($current_page == 1 ) ? 0 : ($limit * $current_page) - $limit;
             
        if( 
            isset($db_field) && isset($keyword) && (strlen($db_field) > 0) &&
            !empty($db_field) && !empty($keyword) && (strlen($keyword) > 0)
          )
        {
            $query = "SELECT * FROM ".DEFAULT_DATABASE.".view_users_info
                        WHERE $db_field LIKE '%$keyword%' ORDER BY $db_field LIMIT $start_pos, $limit";

            $statement = $conn->prepare($query);
        }
        else if( 
            isset($db_field) && (strlen($db_field) > 0) && !empty($db_field) 
          )
        {
            $query = "SELECT * FROM ".DEFAULT_DATABASE.".view_users_info
                        ORDER BY $db_field LIMIT $start_pos, $limit";

            $statement = $conn->prepare($query);
        }
        else
        {
            $query = "SELECT * FROM ".DEFAULT_DATABASE.".view_users_info LIMIT $start_pos, $limit";   
            $statement = $conn->prepare($query);
        }
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        
        if( count($result) > 0 && isset($result[0]['user_id']) )
        {
            
            if( 
                isset($db_field) && isset($keyword) && (strlen($db_field) > 0) &&
                !empty($db_field) && !empty($keyword) && (strlen($keyword) > 0)
              )
            {
                $query = "SELECT COUNT(*) FROM ".DEFAULT_DATABASE.".view_users_info
                            WHERE $db_field LIKE '%$keyword%' ORDER BY $db_field";
            }
            else if( 
                isset($db_field) && (strlen($db_field) > 0) && !empty($db_field) 
              )
            {
                $query = "SELECT COUNT(*) FROM ".DEFAULT_DATABASE.".view_users_info
                            ORDER BY $db_field";
            }
            else
            {
                $query = "SELECT COUNT(*) FROM ".DEFAULT_DATABASE.".view_users_info";   
            }

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
                    'pages'             =>  $pages
                ],
                'data'          =>  $result
            ];
            
            return $result;            
        }
        else
        {
            return false;
        }        
    }

    // confirma la cuenta de un nuevo usuario validando su email
    // devuelve true de lo contrario false
    public function confirm_account($conn,$user_email)
    {
        $query = "call " . PREFIX . "sp_confirm_account('".$user_email."')";

        $statement = $conn->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();
        
        if($result)
        {
            if($result[0]['status'] == "200")
            {
                return true;
            }
        }
        return false;
    }

    // registro de usuario
    // recibe un array con los parametros que seran enviados
    // a un procedimiento almacenado para registrar un nuevo usuario
    public function sign_up($conn,$array_user_data)
    {        
        /* 
            $array_user_data = array(
                'user_name'      => '';
                'first_name'     => '';
                'last_name'      => '';
                'goverment_id'   => '';
                'gender'         => '';
                'user_email'     => '';
                'user_phone'     => ''; 
            ) 
        */

        $user_name      = $array_user_data['users_name'];
        $first_name     = $array_user_data['first_name'];
        $last_name      = $array_user_data['last_name'];
        $goverment_id   = $array_user_data['users_goverment_id'];
        $gender         = $array_user_data['gender'];
        $user_email     = $array_user_data['users_email'];
        $user_phone     = $array_user_data['users_phone'];

        // generamos un token md5 al azar de 32 caracteres
        $token = mt_rand(10000000,99999999);
        $new_token = md5($token);

        $query = "call " . PREFIX . "sp_signup(
            :user_name,
            :first_name,
            :last_name,
            :goverment_id,
            :gender,
            :user_email,
            :user_phone,
            :new_token            
        )";

        $statement = $conn->prepare($query);

        $statement->execute(array(
            ':user_name'      => $user_name,
            ':first_name'     => $first_name,
            ':last_name'      => $last_name,
            ':goverment_id'   => $goverment_id,
            ':gender'         => (int)$gender,
            ':user_email'     => $user_email,
            ':user_phone'     => $user_phone ? $user_phone : "",
            ':new_token'      => $new_token,
        ));

        $result = $statement->fetchAll();
        
        return $result;

    }

    // solicitamos los datos del usuario
    // si los datos enviados son correctos devuelve un array los datos del usuario
    // de lo contrario devuelve false
    // recibe la conexion en PDO y un array con los datos de inicios de sesion
    public function get_login_info($conn,$array_user_and_password)
    {
        $login_user     = $array_user_and_password['login_user'];
        $login_password = $array_user_and_password['login_password'];

        for ($i=0; $i <= 3; $i++)
        {

            switch ($i)
            {
                case (0):
                    $query = "SELECT user_id,role_id,users_name,users_email,users_phone,users_goverment_id,users_password FROM " . PREFIX . "users_login_info as login JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id WHERE users_name = '$login_user'";            
                    break;
                case (1):
                    $query = "SELECT user_id,role_id,users_name,users_email,users_phone,users_goverment_id,users_password FROM " . PREFIX . "users_login_info as login JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id WHERE users_email = '$login_user'";            
                    break;
                case (2):
                    $query = "SELECT user_id,role_id,users_name,users_email,users_phone,users_goverment_id,users_password FROM " . PREFIX . "users_login_info as login JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id WHERE users_goverment_id = '$login_user'";            
                    break;
                default:
                    break;
            }

            $statement = $conn->prepare($query);
            $statement->execute();
            
            $result = $statement->fetchAll();
            
            //Pregunto si la consulta es correcta.
            if( $result )
            {     
                
                foreach ($result as $row)
                {
                    
                    $result = array_map('utf8_encode',$row);
                }
            }

            
            
            // obtiene el hash de la base de datos
            $hash = isset($result['users_password']) ? $result['users_password'] : null;
            
            // verifica el password encriptado
            if (password_verify($login_password,$hash))
            {
                // devuelve un array con los datos del usuario.
                return $result;           
            }
            elseif (!password_verify($login_password,$hash) && $i>2)
            {
                return false;
            }    
        }
    }

    // solicitamos la insercion de un token de session
    // y obtenemos los datos de usuario y session
    // de lo contrario devuelve false
    public function insert_session_token($conn,$array_with_user_data)
    {   
        // guarda el id de usuario obtenido;
        $user_id = (int)$array_with_user_data['user_id'];

        // generamos un token de session de 9 digitos
        $session_token = mt_rand(100000000,999999999);        
        
        if (isset($session_token) && isset($user_id) )
        {
            // preparamos la consulta
            $query = "call " . PREFIX . "sp_insert_session_token('$session_token',$user_id)";            
            //var_dump($query); die();
            $statement = $conn->prepare($query);
            // ejecutamos la consulta
            $statement->execute();
            
            // obtenemos el resultado de nuestra consulta
            $result = $statement->fetchAll();
            
            //Pregunto si la consulta es correcta.
            if($result)
            {            
                // preparamos la consulta para obtene los datos de usuario    
                $query = "SELECT security.user_id,role_id,roles.role_super,users_name,users_email,users_phone,users_goverment_id,session_token,security.account_confirmed,session.updated_at,expire_session_id
                            FROM " . PREFIX . "users_login_info as login 
                                JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id 
                                    JOIN " . PREFIX . "users_security_session as session ON login.id = session.user_id 
                                        JOIN " . PREFIX . "cat_roles as roles ON security.role_id = roles.id
                                            WHERE login.id = $user_id";

                
                $statement = $conn->prepare($query);
                // ejecutamos la consulta
                $statement->execute();
                
                // obtenemos el resultado de nuestra consulta
                $result = $statement->fetchAll();
            }

            if($result)
            {
                
                foreach ($result as $row)
                {   
                    $result = array_map('utf8_encode',$row);
                }
                return $result;                        
            }
            else
            {
                return false;
            } 
        }
    }

    // solicitamos los datos session a travez de un array que contiene
    // el token y el id de usuario validos 
    // devuelve un array con los datos si es true
    // de lo contrario devuelve false
    public function get_session_info($conn,$array_token_and_user_id)
    {
        
        $session_token = $array_token_and_user_id[0];
        $user_id = $array_token_and_user_id[1]; 

        // preparamos la consulta
        // preparamos la consulta para obtene los datos de usuario    
        $query = "SELECT security.user_id,role_id,roles.role_super,users_name,users_email,users_phone,users_goverment_id,session_token,session.updated_at,expire_session_id
        FROM " . PREFIX . "users_login_info as login 
            JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id 
                JOIN " . PREFIX . "users_security_session as session ON login.id = session.user_id 
                    JOIN " . PREFIX . "cat_roles as roles ON security.role_id = roles.id
                        WHERE login.id = :user_id and session.session_token = :session_token";        

        $statement = $conn->prepare($query);
        // ejecutamos la consulta
        $statement->execute(array(
            ':user_id'          => $user_id,
            ':session_token'    => $session_token
        ));
        
        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();
        
        //Pregunto si la consulta es correcta.
        if($result)
        {            
            foreach ($result as $row)
            {   
                $array = array_map('utf8_encode',$row);
            }
            return $array; 
            
        }
        else
        {
            return false;
        }
    }

    // actualiza el token de seguridad mediante el id de usuario
    // devuelve true si lo hace, sino false.
    public function update_security_token($conn,$user_id)
    {
            // generamos un token md5 al azar de 32 caracteres
            $token = mt_rand(10000000,99999999);
            $new_token = md5($token);
            
            $query = "call " . PREFIX . "sp_insert_security_token('$new_token',$user_id)";            
            
            $statement = $conn->prepare($query);
            
            // ejecutamos la consulta
            $statement->execute();

            // obtenemos el resultado de nuestra consulta
            $result = $statement->fetchAll();
            
            if($result)
            {
                foreach ($result as $item) {
                    // si regresa un mensaje de error retorna false
                    if ( isset($item['error_msg']) )
                    {
                        return false;
                    }                    
                }
                return true;
            }
    }

    // solicitamos los datos el token de seguridad actualizado y verificamos que el email enviado
    // este asociados a un usuario,
    // devuelve un array con los datos si es true,
    // de los contrario devuelve false    
    public function get_security_token_by_email($conn,$email_to_confirm)
    { 
        
        // preparamos la consulta para obtener el user_id, email, y token de seguridad
        // esta consulta verifica que el email exista para devolver los datos asociados
        $query = "SELECT security.user_id, login.users_email, security.token from " . PREFIX . "users_login_info as login
                    JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id
                        WHERE login.users_email = :email";

        $statement = $conn->prepare($query);
        // ejecutamos la consulta
        $statement->execute(array(
            ':email' => $email_to_confirm            
        ));
        
        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();                
        
        //Pregunto si la consulta es correcta.
        if($result)
        {            
            foreach ($result as $row)
            {   
                $array = array_map('utf8_encode',$row);
            }
            
            /* $array = [
                   user_id => '',
                   users_email => '',
                   token => ''
              ] */            
                          
            $user_id = $array['user_id'];
            
            // actualizamos el token de seguridad.
            $result = $this->update_security_token($conn,$user_id);
            
            if ($result)
            {
                // de los contrario retorna true, el token fue actualizado
                $query = "SELECT security.user_id, login.users_name, login.users_email, security.token from " . PREFIX . "users_login_info as login
                        JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id
                            WHERE login.users_email = :email";
    
                $statement = $conn->prepare($query);

                // ejecutamos la consulta
                $statement->execute(array(
                    ':email' => $email_to_confirm            
                ));
                
                // obtenemos el resultado de nuestra consulta
                $result = $statement->fetchAll();
                
                // devuelve el array con los datos, de los contrario false
                if($result)
                {            
                    foreach ($result as $row)
                    {   
                        $array = array_map('utf8_encode',$row);
                    }
    
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
        else
        {
            return false;
        }
    }

    // validamos que el token este correcto y vigente
    // si es correcto devuelve el token de lo contrario false.
    public function validate_security_token_by_email($conn,$security_token,$user_email)
    {
        // preparamos la consulta para obtener el user_id, email, y token de seguridad
        // esta consulta verifica que el email exista para devolver los datos asociados
        $query = "SELECT security.user_id, login.users_email, security.updated_at, security.token from " . PREFIX . "users_login_info as login
                    JOIN " . PREFIX . "users_security_data as security ON login.id = security.user_id
                        WHERE login.users_email = :email and security.token = :token";
        
        $statement = $conn->prepare($query);
        // ejecutamos la consulta
        $statement->execute(array(
            ':email' => $user_email,
            ':token' => $security_token          
        ));
        
        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();  

        //Pregunto si la consulta es correcta.
        if($result)
        {            
            foreach ($result as $row)
            {   
                $array = array_map('utf8_encode',$row);
            }
            
            $token = $array['token'];
            $last_update = $array['updated_at'];
            
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
            
            // si paso 30 minutos desde la ultima actualizacion de session
            // si el token no coincide
            // entonces devuelve false, cierra session            
            if ( ($current_date - $last_update) > 1800 || $token !== $security_token)
            { 
                
                return false;                                
            }
            else
            {
                return $token;
            }
        }
        else
        {
            return false;
        }
    }

    public function get_user_id_from_email($conn,$user_email)
    {
        // preparamos la consulta para obtener el user_id, email, y token de seguridad
        // esta consulta verifica que el email exista para devolver los datos asociados
        $query = "SELECT id FROM " . PREFIX . "users_login_info WHERE users_email = :email";
        
        $statement = $conn->prepare($query);
        // ejecutamos la consulta
        $statement->execute(array(
            ':email' => $user_email            
        ));        
        
        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();  

        if($result)
        {
            foreach ($result as $item) {
                if (isset($item['id']))
                {
                    return $item['id'];
                }
            }
            return false;
        }
        else
        {
            return false;
        }
    }

    // recibe el nuevo token, email y nuevo password
    // devuelve true de lo contrario false
    public function update_password($conn,$user_email,$new_password)
    {

        // obtenemos el id de usuario via email
        $user_id = $this->get_user_id_from_email($conn,$user_email);
        //encriptamos el password
        $hash = password_hash($new_password,PASSWORD_DEFAULT);
        
        
        // actualizamos el password
        $query = "call " . PREFIX . "sp_update_password('$hash',$user_id)";
        
        $statement = $conn->prepare($query);
        // ejecutamos la consulta
        $statement->execute();        
        // obtenemos el resultado de nuestra consulta
        $result = $statement->fetchAll();            
        
        foreach ($result as $item) {
            
            if (isset($item['status']) && $item['status'] == "200" )
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