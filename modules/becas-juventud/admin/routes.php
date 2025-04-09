<?php 

    $path = get_path('dashboard', 2, '/') === 'dashboard' ? 'dashboard' : get_path('dashboard', 2, '/') ;

    switch ( $path )
    {

        case '/becas-juventud':
            include_once( BCMJ_TEMPLATE_PAGES . '/module-page.php');
            break;
            
       /*  case 'dashboard':
            include_once( BCMJ_TEMPLATE_PAGES . '/module-page.php');
            break; */
            
        default:        
            include_once('./library/templates/pages/404-page.php');
            break;
    }