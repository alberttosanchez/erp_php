<?php

    //echo 'config.php';

    // Nombre del Modulo
    define( 'BCMJ_MODULE_NAME', 'Becas Juventud' );
    

    // Rutas    
    define ( 'BCMJ_URL_BASE', MODULES_DIRECTORY . get_module_name() );

    define( 'BCMJ_ADMIN_DIRECTORY', BCMJ_URL_BASE . '/admin');

    define( 'BCMJ_ASSETS_DIRECTORY', BCMJ_URL_BASE . '/assets');

    define( 'BCMJ_LIBRARY_DIRECTORY', BCMJ_URL_BASE . '/library');

    define( 'BCMJ_CLASSES_DIRECTORY', BCMJ_LIBRARY_DIRECTORY . '/class');    

    define( 'BCMJ_SCRIPTS_DIRECTORY', BCMJ_LIBRARY_DIRECTORY . '/templates/scripts');

    define( 'BCMJ_TEMPLATE_PAGES', BCMJ_LIBRARY_DIRECTORY . '/templates/pages');

    define( 'BCMJ_TEMPLATE_PARTS', BCMJ_LIBRARY_DIRECTORY . '/templates/parts');

    define( 'BCMJ_TEMPLATE_CONTENTS', BCMJ_LIBRARY_DIRECTORY . '/templates/contents');   

    define( 'BCMJ_TEMPLATE_MODALS', BCMJ_LIBRARY_DIRECTORY . '/templates/modals');   

    define( 'BCMJ_PUBLIC_TEMP','./../../../public/temp');   

    // Rutas de la API
    define( 'BCMJ_API_MANAGE_DB_URL', DOMAIN_URL . 'api/manage_db.php');   

    

    // TABLE NAMES