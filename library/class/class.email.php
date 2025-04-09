<?php
    /**
     * Clase Email
     * Esta clase maneja todo los eventos de correo electronico.
     * 
     * Esta clase utiliza el archivo config.php para obtene constantes.
     * Esta clase utiliza el archivo functions.php para obtener funciones.
     */

    namespace Library\Classes;
    
    class Email {
        private static $instance;    
        private static $where;        
        private static $arrayData;                        
        private static $action;  # action: r, r+, w, w+, a, a+
        private static $oldData; # cadena a buscar
        private static $newData; # cadena de reemplazo
        private static $conn;                        
    # ---------------------------------------------------- 
    
        //Abre un archivo html para buscar y reemplazar los datos suministrados
        //Si no hay datos de reemplazo devuelve el archivo en una cadena.
        public function readMessage($msgFile,$oldData = null,$newData = null){            
            // readOnlyMessage(param1,r) ejecuta las funciones fopen(), feof(), fgets(), fclose()
            // ver functions.php
            $action = 'r';            
            
            $message = "";    
            $message_data = fopen($msgFile,$action);             
            
            if ( isset($newData) ) {    
                
                $search = $oldData;
                $replace = $newData;
                while( !feof($message_data) ) {
                    $getLine = fgets($message_data);
                    // str_replace() buscar un string para ser reemplazado.
                    $getLine = str_replace($search,$replace,$getLine);
                    $message = $message.$getLine;
                }               
            // si no hay nuevo datos recorre el archivo y lo guarda en un estring.
            } else {
        
                while( !feof($message_data) ) {
                    $getLine = fgets($message_data);
                    $message = $message.$getLine;
                }
                
            }    
                fclose($message_data);
                return $message;
        }
    
        # ----------------------------------------------------
        public function mailToNewUser(){
            
            /*self::$arrayData = [
                'emailToUser' => '',    # email
                'emailToStaff' => '',   # email
                'aliasName' => '',      # string
                'msgToUser' => '',      # url
                'msgToStaff' => '',     # url
                'where' => '',          # int       Ver -> class.mail.php    
                'token' => ''           # string    Ver -> functions.php           
            ];*/        
            try 
            {   
                $emailToUser = self::$arrayData['emailToUser'];                        
                $msgToUser = self::$arrayData['msgToUser'];             
                $aliasName = self::$arrayData['aliasName'];

                $headers = "";
                $headers .= "Content-Type: text/html; charset=8\r\n";
                $headers .= "From:".EMAIL_TO_STAFF."\r\n";
                $email_to = $emailToUser;
                $mail_subject = "Bienvenido al IJOVEN - " . $aliasName;                       
                
                $oldData = "{user_name}";
                $newData = $aliasName;                

                $message = $this->readMessage($msgToUser,$oldData,$newData);  

                $send = @mail($email_to, $mail_subject, $message, $headers);
                
                if ($send) {

                    //enviamos correo al soporte    
                    $mail_subject = "Nuevo Usuario | IJOVEN - " . $aliasName;
                    $emailToStaff = self::$arrayData['emailToStaff'];                         
                    $msgToStaff = self::$arrayData['msgToStaff'] ? self::$arrayData['msgToStaff'] : "Notificación de Nuevo Usuario en el IJOVEN.";                        
                    $oldData = '{user_email}';
                    $newData = $emailToUser;

                    $message = $this->readMessage($msgToStaff,$oldData,$newData);
                    // Ver -> functions.php
                    
                    $mailSend = @mail($emailToStaff, $mail_subject, $message, $headers);
                    // mail() envia el mensaje al correo electronico.
                    
                    if ($mailSend && $send)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }  

            } catch ( PDOException $Exception ) {
                die('Error Mail. Contacte el Administrador.');
            }
        }

        //----------------------------------------------------
        # ----------------------------------------------------
        public function mailToActiveAccount(){
            /*
            $arrayData = [
                'emailToUser'   => '',  # email
                'aliasName'     => '',  # string
                'msgToUser'     => '',	# url archivo
                'where'         => '',	# int       Ver -> metodo send();
                'token'         => '' 	# string    Ver -> functions.php
            ]; 
            */
            // cleanData() funcion externa.
            $emailToUser    = self::$arrayData['emailToUser'];                            
            $aliasName      = ( empty(self::$arrayData['aliasName']) ) ? "Sin Nombre" : cleanData(self::$arrayData['aliasName']);                               
            $msgToUser      = self::$arrayData['msgToUser'];            
            $token          = self::$arrayData['token'];
        
            $headers        = "";
            $headers       .= "Content-Type: text/html; charset=utf-8\r\n";
            $headers       .= "From:".EMAIL_TO_STAFF."\r\n";
            $email_to       = $emailToUser;
            $mail_subject   = "IJOVEN | Activación de Cuenta";                       
                
            $oldData = "{user_name}";
        
            # convierte la cadena en un array por cada espacio encontrado.
            $userName = (strpos($aliasName,' ') > 0) ? explode(' ',$aliasName) : $aliasName;
            
            # si es un array toma el primer valor.
            $userName = ( gettype($userName) == 'array' ) ? $userName[0] : $userName;
            
            # Agrega el nombre al mensaje que recibirá el usuario.
            $newData = (isset($realName) and $realName != '') ? $realName : $userName;        
        
            $messageInString = $this->readMessage($msgToUser,$oldData,$newData);  
            
            $oldData = "{email}";
            $newData = $emailToUser;  
            
            $messageInString = str_replace($oldData,$newData,$messageInString);       
            
            $oldData = "{url}";
            $newData = URL_BASE.EMAIL_CONFIRM_ACCOUNT_ROUTE.$token."-".$emailToUser;  
            
            $message = str_replace($oldData,$newData,$messageInString);            
            
            $mailSended = @mail($email_to, $mail_subject, $message, $headers);
            
            # Verifica si el mensaje se envio (true).       
            if($mailSended) {       
                return true;            
            } else {
                return false;
            }
        }

        # ----------------------------------------------------
        public function storePublicData(){               
                
            if (!empty(self::$conn)) {

                $sql = 'SELECT publics_mails,subscription_status FROM pub_subscription_mails WHERE publics_mails = :emailToUser';
                $statement = self::$conn->prepare($sql);            
                $statement->execute(array(':emailToUser' => self::$arrayData['emailToUser']));
                $result = $statement->fetchAll();            
                
                # Verifica si encontro algo y su estado no es borrado.
                if ( ($statement->rowCount() ) > 0 and $result[0]['subscription_status'] != 3) {                                
                    return false;
                
                    # Verifica si encontro algo y su estado es borrado.
                } elseif ( $statement->rowCount() > 0 and $result[0]['subscription_status'] == 3) {
                    
                    # Si estado es borrado lo actualiza a activo.
                    $sql =
                        'UPDATE pub_subscription_mails SET subscription_status=1,publics_tokens = :token
                            WHERE publics_mails = :email';
                    $statement = self::$conn->prepare($sql);            
                    $statement->execute(array(':email' => self::$arrayData['emailToUser'],':token' => self::$arrayData['token']));

                    # Verificacion si el registro se actualizó en la base de datos.
                    $sql = 
                    'SELECT * FROM pub_subscription_mails WHERE publics_mails = :emailToUser and subscription_status = 1 LIMIT 1';
                    $statement = self::$conn->prepare($sql);            
                    $statement->execute(array(':emailToUser' => self::$arrayData['emailToUser']));
                    //$result = $statement->fetchAll();
                    $result = $statement;
        
                    # si encontro algo
                    if ( ($result->rowCount()) > 0 ) {
                        return $result;
                    }

                } else {
                    
                    $sql = "INSERT INTO pub_subscription_mails (publics_mails,publics_tokens,subscription_status) values (:emailToUser,:token,1)";
                    
                    $statement = self::$conn->prepare($sql);            
                    $statement->execute(array(':emailToUser' => self::$arrayData['emailToUser'], ':token' => self::$arrayData['token']));
        
                    # Verificacion si el registro se creo en la base de datos.
                    $sql = 
                    'SELECT * FROM pub_subscription_mails WHERE publics_mails = :emailToUser LIMIT 1';
                    $statement = self::$conn->prepare($sql);            
                    $statement->execute(array(':emailToUser' => self::$arrayData['emailToUser']));
                    //$result = $statement->fetchAll();
                    $result = $statement;
        
                    if ( ($result->rowCount()) > 0 ) {
                        return $result;
                    } else {                    
                        return false;
                    }
                }

            } else {
                return false;
            }
            
        }

        # ----------------------------------------------------
        /*$arrayData = [
            'emailToUser' => '',    # email
            'emailToStaff' => '',   # email
            'aliasName' => '',      # string
            'msgToUser' => '',      # url archivo
            'textToUser' => '',     # texto
            'msgToStaff' => '',     # url archivo
            'textToStaff' => '',    # texto        
            'where' => '',          # int       Ver -> metodo send();
            'token' => NULL         # string    Ver -> functions.php           
        ];*/

        public function mailToContactStaff(){     
            
            // cleanData() funcion externa.
            $emailToUser = self::$arrayData['emailToUser'];                
            $emailToStaff = self::$arrayData['emailToStaff'];                
            $aliasName = ( empty(self::$arrayData['aliasName']) ) ? "Sin Nombre" : cleanData(self::$arrayData['aliasName']);                        
            $msgToUser = self::$arrayData['msgToUser'];
            $msgToStaff = self::$arrayData['msgToStaff'];
            $textToUser = self::$arrayData['textToUser'];
            $textToStaff = $textToUser;

            $headers = "";
            $headers .= "Content-Type: text/html; charset=8\r\n";
            $headers .= "From:".EMAIL_TO_STAFF."\r\n";
            $email_to = $emailToUser;
            $mail_subject = "Sumate | Mensaje Recibido de: " . $emailToUser;                       
                
            $oldData = "<!--{nombre}-->";

            # convierte la cadena en un array por cada espacio encontrado.
            $userName = (strpos($aliasName,' ') > 0) ? explode(' ',$aliasName) : $aliasName;
            # si es un array toma el primer valor.
            $userName = ( gettype($userName) == 'array' ) ? $userName[0] : $userName;

            # Agrega el nombre al mensaje que recibira el usuario.
            $newData = $userName;        

            $messageInString = $this->readMessage($msgToUser,$oldData,$newData);  
                    
            $oldData = "<!--{mensaje}-->";
            $newData = $textToUser;  
            
            $message = str_replace($oldData,$newData,$messageInString);
            //$message = $this->readMessage($message,$oldData,$newData);  
            
            $send = @mail($email_to, $mail_subject, $message, $headers);

            # Verifica si el mensaje se envio (true).
            if($send) {

                $headers .= "From:".EMAIL_TO_STAFF."\r\n";
                $email_to = $emailToStaff;
                $mail_subject = "Sumate | Mensaje Recibido de: $emailToUser";                       
                
                $oldData = "<!--{nombreCompleto}-->";
                $newData = self::$arrayData['aliasName'];  

                $messageInString = $this->readMessage($msgToStaff,$oldData,$newData);  

                $oldData = "<!--{email}-->";
                $newData = $emailToUser;
                
                $messageInString = str_replace($oldData,$newData,$messageInString);                        

                $oldData = "<!--{mensaje}-->";
                $newData = $textToStaff; 
                
                $message = str_replace($oldData,$newData,$messageInString);            

                $send = @mail($email_to, $mail_subject, $message, $headers);

                if ($send) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        
        # ---------------------------------------------------
        public function mailForPasswordChange(){
            /*$arrayData = [
                'emailToUser' => '',    # email
                'emailToStaff' => '',   # email
                'aliasName' => '',      # string
                'realName' => '',       # string
                'msgToUser' => '',      # url archivo
                'textToUser' => '',     # texto
                'msgToStaff' => '',     # url archivo
                'textToStaff' => '',    # texto        
                'where' => '',          # int       Ver -> metodo send();
                'token' => ''           # string    Ver -> functions.php           
            ];*/
            // cleanData() funcion externa.
            $emailToUser = self::$arrayData['emailToUser'];                
            $emailToStaff = self::$arrayData['emailToStaff'];                
            $aliasName = ( empty(self::$arrayData['aliasName']) ) ? "Usuario" : cleanData(self::$arrayData['aliasName']);                        
            $realName = ( empty(self::$arrayData['realName']) ) ? '' : cleanData(self::$arrayData['realName']);                        
            $msgToUser = self::$arrayData['msgToUser'];
            //$msgToStaff = self::$arrayData['msgToStaff'];
            //$textToUser = self::$arrayData['textToUser'];
            //$textToStaff = $textToUser;
            $token = self::$arrayData['token'];

            $headers = "";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $headers .= "From:" . EMAIL_TO_STAFF . "\r\n";
            $email_to = $emailToUser;
            $mail_subject = "IJOVEN | Notificación de Cambio de Contraseña: " . $emailToUser;                       
                
            $oldData = "{user_name}";

            # convierte la cadena en un array por cada espacio encontrado.
            $userName = (strpos($aliasName,' ') > 0) ? explode(' ',$aliasName) : $aliasName;
            $realName = (strpos($realName,' ') > 0) ? explode(' ',$realName) : $realName;
            # si es un array toma el primer valor.
            $userName = ( gettype($userName) == 'array' ) ? $userName[0] : $userName;
            $realName = ( gettype($realName) == 'array' ) ? $realName[0] : $realName;

            # Agrega el nombre al mensaje que recibira el usuario.
            $newData = (isset($realName) && $realName !== '') ? $realName : $userName;        

            $message = $this->readMessage($msgToUser,$oldData,$newData);

            $send = @mail($email_to, $mail_subject, $message, $headers);

            # Verifica si el mensaje se envio (true).
            if($send)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
        # ---------------------------------------------------
            /* $arrayData = [
                'emailToUser'   => '',  # email
                'emailToStaff'  => '',  # email
                'aliasName'     => '',  # string							
                'msgToUser'     => '',	# url archivo							
                'msgToStaff'    => '',	# url archivo							
                'where'         => '',	# int       Ver -> metodo send();
                'token'         => '' 	# string    Ver -> functions.php           
            ]; */
        public function mailToRestorePassword(){
            // cleanData() funcion externa.
            $emailToUser = self::$arrayData['emailToUser'];                
            $emailToStaff = self::$arrayData['emailToStaff'];                
            $aliasName = ( empty(self::$arrayData['aliasName']) ) ? "Sin Nombre" : cleanData(self::$arrayData['aliasName']);                               
            $msgToUser = self::$arrayData['msgToUser'];
            $msgToStaff = self::$arrayData['msgToStaff'];       
            $token = self::$arrayData['token'];

            $headers = "";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $headers .= "From:" . EMAIL_TO_STAFF . "\r\n";
            $email_to = $emailToUser;
            $mail_subject = "IJOVEN | Recuperar Contraseña";                       
                
            $oldData = "{user_name}";

            # convierte la cadena en un array por cada espacio encontrado.
            $userName = (strpos($aliasName,' ') > 0) ? explode(' ',$aliasName) : $aliasName;
            //$realName = (strpos($realName,' ') > 0) ? explode(' ',$realName) : $realName;
            # si es un array toma el primer valor.
            $userName = ( gettype($userName) == 'array' ) ? $userName[0] : $userName;
            //$realName = ( gettype($realName) == 'array' ) ? $realName[0] : $realName;
            # Agrega el nombre al mensaje que recibira el usuario.
            $newData = (isset($realName) and $realName != '') ? $realName : $userName;        

            $messageInString = $this->readMessage($msgToUser,$oldData,$newData);  
            
            $oldData = "{email}";
            $newData = $emailToUser;  
            
            $messageInString = str_replace($oldData,$newData,$messageInString);       
            
            $oldData = "{url}";
            $newData = URL_BASE.EMAIL_RESTORE_PASSWORD_ROUTE.$token."-".$emailToUser;  
            
            $message = str_replace($oldData,$newData,$messageInString);
            //var_dump($message); die();              
            
            $mailSended = @mail($email_to, $mail_subject, $message, $headers);
            
            # Verifica si el mensaje se envio (true).       
            if($mailSended) {       
                    return true;            
            } else {
                return false;
            } 

        }
        # ---------------------------------------------------
        /*$arrayData = [
            'emailToUser' => '',    # email
            'emailToStaff' => '',   # email
            'aliasName' => '',      # string
            'realName' => '',       # string
            'msgToUser' => '',      # url archivo
            'textToUser' => '',     # texto
            'msgToStaff' => '',     # url archivo
            'textToStaff' => '',    # texto        
            'where' => '',          # int       Ver -> metodo send();
            'token' => ''           # string    Ver -> functions.php           
        ];*/
        public function send($arrayData,$conn = null){
            if (isset($conn))
            {
                self::$conn = $conn;                        
            }
            self::$arrayData = $arrayData; 
            $where = $arrayData['where'];
            
            # Los case se pasan desde la propiedad 'where' del $arrayData.
            switch ($where) {
                case 'notification_new_user':                    
                    return $this->mailToNewUser();
                    break;
                case 'contact':
                    return $this->mailToContactStaff();                  
                    break;
                case 'notificacion_password_change':
                    return $this->mailForPasswordChange();                  
                    break;
                case 'restore':
                    return $this->mailToRestorePassword();                  
                    break;
                case 'active_account':
                    return $this->mailToActiveAccount();
                    break;                    
                default:
                    # code...
                    break;
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