<?php

    //echo 'config.php';
   
    // Nombre del Modulo
    define( 'ARCH_MODULE_NAME', 'Politicas, Procedimientos y Manuales' );
    
    define( 'POST_FILE_MAX_SIZE', 41943040 );
    
    define( 'POST_LIMIT_PER_PAGE', 10 );
    

    // Rutas    
    define ( 'ARCH_URL_BASE', MODULES_DIRECTORY . get_module_name() );

    define( 'ARCH_ADMIN_DIRECTORY', ARCH_URL_BASE . '/admin');

    define( 'ARCH_ASSETS_DIRECTORY', '../.' . ARCH_URL_BASE . '/assets');

    define( 'ARCH_LIBRARY_DIRECTORY', ARCH_URL_BASE . '/library');

    define( 'ARCH_CLASSES_DIRECTORY', '../.' . ARCH_LIBRARY_DIRECTORY . '/class');    

    define( 'ARCH_SCRIPTS_DIRECTORY', '../.' . ARCH_LIBRARY_DIRECTORY . '/templates/scripts');

    define( 'ARCH_TEMPLATE_PAGES', ARCH_LIBRARY_DIRECTORY . '/templates/pages');

    define( 'ARCH_TEMPLATE_PARTS', ARCH_LIBRARY_DIRECTORY . '/templates/parts');

    define( 'ARCH_TEMPLATE_CONTENTS', ARCH_LIBRARY_DIRECTORY . '/templates/contents');   

    define( 'ARCH_TEMPLATE_MODALS', ARCH_LIBRARY_DIRECTORY . '/templates/modals');   

    define( 'ARCH_PUBLIC_TEMP','./../../../public/temp');   

    define( 'ARCH_UPLOADS_PUBLIC_POST_FILE_DIR_PATH' , './../../../public/uploads/post_files' );

    // Rutas de la API
    define( 'ARCH_API_MANAGE_DB_URL', DOMAIN_URL . 'api/manage_db.php');   

    //----------------------- Rutas Publicas -------------------------------------

    define ( 'ARCH_URI_BASE', '/' . get_module_name() );

    define( 'ARCH_API_URL', DOMAIN_URL .  '/' . ARCH_URL_BASE . '/api/index.php');   

    // TABLE NAMES -> ver module-meta-part.php
    define( 'ARCH_FIRST_LV_CATEGORY_TABLE',     'arch_cat_first_level_category');   
    define( 'ARCH_SECOND_LV_CATEGORY_TABLE',    'arch_cat_second_level_category');   
    define( 'ARCH_THIRD_LV_CATEGORY_TABLE',     'arch_cat_third_level_category');   
    define( 'ARCH_FOUR_LV_CATEGORY_TABLE',      'arch_cat_four_level_category');   
    define( 'ARCH_FIVE_LV_CATEGORY_TABLE',      'arch_cat_five_level_category');   

    define( 'ARCH_VIEW_ALL_CATEGORIES_TABLE',   'arch_view_all_categories');
    define( 'ARCH_POST_DATA_TABLE',             'arch_post_data');
    