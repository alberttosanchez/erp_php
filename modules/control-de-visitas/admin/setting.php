<?php

    define( 'CROOPER_PROFILE_PIC_ASPECT_RATIO', 114 / 132 );

    define( 'CV_UPLOADS_PUBLIC_FILE_DIR_PATH', './../../../public/' );

    //echo 'config.php';

    define( 'CV_MODULE_NAME', 'Control de Visitas' );
    
    define( 'CV_MODULE_NAME_URL', get_module_name() );

    define( 'CV_POST_FILE_MAX_SIZE', 41943040 );
    
    define( 'CV_POST_LIMIT_PER_PAGE', 10 );

    define( 'CV_URL_BASE', MODULES_DIRECTORY . CV_MODULE_NAME_URL );

    define( 'CV_ADMIN_DIRECTORY', CV_URL_BASE . '/admin');

    define( 'CV_ASSETS_DIRECTORY', "../." . CV_URL_BASE . '/assets' );
     
    define( 'CV_LIBRARY_DIRECTORY', CV_URL_BASE . '/library' );

    define( 'CV_CLASSES_DIRECTORY', CV_URL_BASE . '/library/class' );
    
    define( 'CV_STYLES_DIRECTORY', "../." . CV_LIBRARY_DIRECTORY . '/templates/styles' );

    define( 'CV_SCRIPTS_DIRECTORY', "../." . CV_LIBRARY_DIRECTORY . '/templates/scripts' );

    define( 'CV_TEMPLATE_PAGES', CV_LIBRARY_DIRECTORY . '/templates/pages' );

    define( 'CV_TEMPLATE_PARTS', CV_LIBRARY_DIRECTORY . '/templates/parts' );

    define( 'CV_TEMPLATE_CONTENTS', CV_LIBRARY_DIRECTORY . '/templates/contents' );   

    define( 'CV_TEMPLATE_MODALS', CV_LIBRARY_DIRECTORY . '/templates/modals' );   

    define( 'CV_LIBRARY_COMPONENTS', CV_LIBRARY_DIRECTORY . '/components' );

    // API routes

    define( 'CV_API_MANAGE_DB_URL', DOMAIN_URL . 'api/manage_db.php');   

    define( 'CV_API_URL', "../." .CV_URL_BASE . '/api/index.php');   
    
    // TABLE NAMES

    define( 'CV_BUSINESS_TABLE', 'cvmj_business_info');

    define( 'CV_SETTING_TABLE', 'cvmj_setting');

    define( 'CVCAT_PLANT_DIST_FILTER_TABLE', 'cvcat_plant_dist_filter');
    
    
    //----------------------- Rutas Publicas -------------------------------------

    define ( 'CV_URI_BASE', '/' . get_module_name() );