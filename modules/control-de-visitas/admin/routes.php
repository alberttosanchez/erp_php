<?php 

    $path = get_path('dashboard', 2, '/') == 'dashboard' ? 'dashboard' : get_path('dashboard', 1, '/') ;

    switch ( $path )
    {

        case 'control-de-visitas':                        
            include_once( CV_TEMPLATE_PAGES . '/module-page.php');
            break;
            
        case 'dashboard':
            include_once( CV_TEMPLATE_PAGES . '/module-page.php');
            break;
        
        case 'printer':
            include_once( CV_TEMPLATE_PAGES . '/printer-page.php');
            break;

        default:        
            include_once('./library/templates/pages/404-page.php');
            break;
    }