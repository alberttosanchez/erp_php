<?php 

    // establece la zona horaria del servidor        
    $timeZone = 'America/Santo_Domingo'; 
    date_default_timezone_set($timeZone);

    // const
    define('LANG','es');

    define('APP_NAME','ERP');

    if ( ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' )
        || $_SERVER['SERVER_PORT'] == 443 )
    {
        define('HTTP_PROTOCOL','https://');
    }
    else
    {
        define('HTTP_PROTOCOL','http://');
    }

    define('SERVER_NAME', 'erp.domain.local');    

    //define('URL_BASE', HTTP_PROTOCOL . SERVER_NAME);
    define('URL_BASE', HTTP_PROTOCOL . SERVER_NAME );


    define('WINDOW_SERVER_ROOT_NEEEDLE','inetpub');    

    define('SESSION_INTERVAL', 600000 );    // (600000 / 1000) = 600 / 60 = 10 minutos
    
    define('SESSION_WARNING_INTERVAL', 60000 );    // (60000 / 1000) = 1000 / 60 = 1 minuto

    define('DEBUG_APP','public');
    
    // files and directories routes

    if ( strpos( $_SERVER['SERVER_NAME'] , "localhost" ) > -1)
    {
        define('STRING_TO_CUT','/projects/domain');        
        define('DOMAIN_URL', HTTP_PROTOCOL . SERVER_NAME);        
        define('MODULE_PATH', '/projects/domain/modules/' );
    }
    else
    {
        define('STRING_TO_CUT',SERVER_NAME);
        define('DOMAIN_URL', HTTP_PROTOCOL . SERVER_NAME);
        define('MODULE_PATH', '/modules/' );
    }

    define('MODULES_DIRECTORY', './modules/' );
    
    define('PROFILE_USERS_URL', DOMAIN_URL . '/public/uploads/profile/users' );

    define('PUBLIC_TEMP_URL', DOMAIN_URL . '/public/temp' );
    
    define('API_INDEX_URL', DOMAIN_URL . '/api/index.php' );

    define('API_MODULES_URL', DOMAIN_URL . '/api/modules.php' );

    define('API_SESSION_URL', DOMAIN_URL . '/api/session.php' );

    define('API_MANAGE_DB_URL', DOMAIN_URL . '/api/manage_db.php' );

    define('API_CATEGORIES_URL', DOMAIN_URL . '/api/categories.php' );

    define('API_CHECK_ROLE_URL', DOMAIN_URL . '/api/check_role.php' );    

    define('API_SETTING_USER_INFO_URL', DOMAIN_URL . '/api/setting_user_info.php' );

    define('API_UPDATE_USER_DATA_URL', DOMAIN_URL . '/api/update_user_data.php' );

    define('API_DELETE_USER_URL', DOMAIN_URL . '/api/delete_user.php' );

    define('API_SIGN_OUT_URL', DOMAIN_URL . '/api/sign_out.php' );

    define('API_SIGNIN_LOGIN_URL', DOMAIN_URL . '/api/signin_login.php' );

    define('API_SIGN_UP_URL', DOMAIN_URL . '/api/sign_up.php' );
 
    define('API_RESTORE_PASSWORD_URL', DOMAIN_URL . '/api/restore_password.php' );

    define('API_CONFIRM_ACCOUNT_URL', DOMAIN_URL . '/api/confirm_account.php' );

    define('API_PROFILE_INFO_URL', DOMAIN_URL . '/api/profile_info.php' );

    define('API_UPDATE_USER_PROFILE_URL', DOMAIN_URL . '/api/update_user_profile.php' );
    
    define('API_UPLOAD_PUBLIC_FILE_URL', DOMAIN_URL . '/api/upload_public_files.php' );

    define('API_UPLOAD_PROFILE_AVATAR_URL', DOMAIN_URL . '/api/upload_profile_avatar.php' );

    define('API_UPLOAD_MODULE_ZIP_FILE_URL', DOMAIN_URL . '/api/upload_module_zip_file.php' );

    define('API_AVATAR_URL', DOMAIN_URL . '/api/avatar.php' );
                      
    define('API_VALIDATE_SECURITY_TOKEN_URL', DOMAIN_URL . '/api/validate_security_token.php' );
       
    define('API_CHANGE_PASSWORD_URL', DOMAIN_URL . '/api/change_password.php' );

    define('API_SEND_ACTIVATION_MESSAGE_URL', DOMAIN_URL . '/api/send_activation_message.php' );

    define('MODULE_DEFAULT_IMAGE', DOMAIN_URL . '/modules/default.png' );

    define('MODULES_ROUTE', '' );
    
    define('ASSETS_DIRECTORY', URL_BASE . '/src/assets/');

    define('SCRIPTS_DIRECTORY', URL_BASE . '/library/scripts/');

    define('CLASSES_DIRECTORY', './../library/class/');

    define('PAGES_DIRECTORY', './../library/templates/pages/');
    
    define('TEMP_PUBLIC_FILE_NAME', 'temp_public_file');    

    define('FAVICON','ijoven-logo.png');

    define('META_TAGS','./library/templates/parts/meta-part.php');

    // previjo de tablas de la base de datos
    define('PREFIX', 'erp_');

    define('DEFAULT_DATABASE', PREFIX.'database');
    
    // datos de conexion de base de datos
    $dbConfig = array(
        'host'      => 'localhost',
        'db_name'   => DEFAULT_DATABASE,
        'user'      => '',
        'password'  => ''
    );
    

    // estos datos estan guardados en un archivo .htpasswd en la raiz del servidor apache
    // o servidor windows segun el caso
    define('HTPASSWD', array(
        'user'  => '',
        'pass'  => ''
    ));

    // constante con datos de conexion
    define('DB_CONFIG', $dbConfig );

    // correo del equipo de soporte
    define('EMAIL_TO_STAFF','webmaster@juventud.gob.do');

    // obtiene el nombre del dominio actual
    $port = HTTP_PROTOCOL;
    if ( isset( $_SERVER['HTTP_ORIGIN'] ) )
    {
        if ( strpos($_SERVER['HTTP_ORIGIN'],":3000") > -1  )
        {

            $domain_name = $_SERVER['HTTP_HOST'].":3000";

        }
        else if (  strpos($_SERVER['HTTP_ORIGIN'],":5000") > -1 )
        {

            $domain_name = $_SERVER['HTTP_HOST'].":5000";

        }
        else
        {

            $domain_name = $_SERVER['HTTP_HOST'];

        }

        // Esta ruta es relativa para el archivo restore-password.php
        define('RESTORE_PASSWORD_MESSAGE_FILE', "./../library/files/restore_password_message.html");    
        define('NOTIFICACION_CHANGE_PASSWORD_MESSAGE_FILE', "./../library/files/notificacion_change_password_message.html");    
        define('NOTIFICACION_TO_NEW_USER_MESSAGE_FILE', "./../library/files/notificacion_to_new_user_message.html");    
        define('NOTIFICACION_TO_STAFF_FOR_NEW_USER_MESSAGE_FILE', "./../library/files/notificacion_to_staff_for_new_user_message.html");    
        define('NOTIFICACION_TO_NEW_USER_TO_ACTIVATE_ACCOUNT_MESSAGE_FILE', "./../library/files/notificacion_to_new_user_to_activate_account_message.html");
            
        define('DOMAIN_NAME', $port.$domain_name);
        
        // ruta relativa para guardar la foto de perfil
        define('PROFILE_AVATAR_DIR_PATH',"./../public/uploads/profile/users/");

        // ruta relativa para guardar archivos temporales
        define('TEMP_PUBLIC_FILE_DIR_PATH',"./../public/temp/");
        
        // ruta relativa para guardar archivos publicos
        define('UPLOADS_PUBLIC_FILE_DIR_PATH', "./../public/uploads/");

        // ruta relativa para guardar el modulo a instalar
        define('MODULE_DIR_PATH',"./../modules/");
    }
    else
    {
        // Esta ruta es relativa para el archivo restore-password.php
        define('RESTORE_PASSWORD_MESSAGE_FILE', $_SERVER['DOCUMENT_ROOT']."/library/files/restore_password_message.html");    
        define('NOTIFICACION_CHANGE_PASSWORD_MESSAGE_FILE', $_SERVER['DOCUMENT_ROOT']."/library/files/notificacion_change_password_message.html");
        define('NOTIFICACION_TO_NEW_USER_MESSAGE_FILE', $_SERVER['DOCUMENT_ROOT']."/library/files/notificacion_to_new_user_message.html");
        define('NOTIFICACION_TO_STAFF_FOR_NEW_USER_MESSAGE_FILE', $_SERVER['DOCUMENT_ROOT']."/library/files/notificacion_to_staff_for_new_user_message.html");
        define('NOTIFICACION_TO_NEW_USER_TO_ACTIVATE_ACCOUNT_MESSAGE_FILE', $_SERVER['DOCUMENT_ROOT']."/library/files/notificacion_to_new_user_to_activate_account_message.html");

        $domain_name = $_SERVER['HTTP_HOST'];        
        define('DOMAIN_NAME', $port.$domain_name);

        // ruta relativa para guardar la foto de perfil    
        define('PROFILE_AVATAR_DIR_PATH',$_SERVER['DOCUMENT_ROOT']."/uploads/profile/users/");

        // ruta relativa para guardar archivos temporales
        define('TEMP_PUBLIC_FILE_DIR_PATH',$_SERVER['DOCUMENT_ROOT']."/temp/");

        // ruta relativa para guardar archivos publicos
        define('UPLOADS_PUBLIC_FILE_DIR_PATH',$_SERVER['DOCUMENT_ROOT']."/public/uploads/");

        // ruta relativa para guardar el modulo a instalar
        define('MODULE_DIR_PATH',$_SERVER['DOCUMENT_ROOT']."/modules"."/");
    }  
    
    // esta ruta
    define('EMAIL_CONFIRM_ACCOUNT_ROUTE','/confirm_account-');

    define('EMAIL_RESTORE_PASSWORD_ROUTE','/change_password-');

    define('MESSAGE_FILE_ROUTE', DOMAIN_NAME . '/active/' );

    // numero de filas a mostrar por pagina del index en detalle usuario -> userSetting.js
    define('ROWS_PER_PAGE',10);   

    // numero de filas a mostrar por pagina del index en Modulos -> Modules.js
    define( 'ROWS_PER_PAGE_IN_MODULES' , 5 );   
    
