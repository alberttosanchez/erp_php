<?php 

    $path = get_path('dashboard', 2, '/') === 'dashboard' ? 'dashboard' : get_path('dashboard', 1, '/') ;
    
    switch ( $path )
    {

        case 'politicas-procedimientos-y-manuales':
            include_once( ARCH_TEMPLATE_PAGES . '/module-page.php');
            break;
            
       /*  case 'dashboard':
            include_once( ARCH_TEMPLATE_PAGES . '/module-page.php');
            break; */
            
        default:        
            include_once('./library/templates/pages/404-page.php');
            break;
    }