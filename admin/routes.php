<?php    
        
    $GLOBALS['MODULE_TO_SHOW'] = ( get_module_name() ) ? get_module_name() : "";    

    $path = "";
    
    if ( get_module_name() )
    {
        $path = "/module";
    }
    elseif ( get_path('/confirm_account') == "/confirm_account" )
    {
        $path = get_path('/confirm_account');
    }
    elseif ( get_path('/change_password') == "/change_password" )
    {
        $path = get_path('/change_password');
    }
    else {
        $path = get_path();
    }
    
    switch ( $path )
    {

        case '/':
            include_once('./library/templates/pages/login-page.php');
            break;

        case '/index.php':
            include_once('./library/templates/pages/login-page.php');
            break;

        case '/login':
            include_once('./library/templates/pages/login-page.php');
            break;
        
        case '/recovery':
            include_once('./library/templates/pages/login-page.php');
            break;

        case '/app':
            include_once('./library/templates/pages/app-page.php');
            break;

        case '/profile':
            include_once('./library/templates/pages/app-page.php');
            break;

        case '/manage':
            include_once('./library/templates/pages/app-page.php');
            break;

        case '/sign-out':            
            include_once('./library/templates/pages/sign-out-page.php');
            break;

        case '/confirm_account':
            include_once('./library/templates/pages/confirm-account-page.php');
            break;

        case '/change_password':
            include_once('./library/templates/pages/change-password-page.php');
            break;

        case '/module':
            include_once('./library/templates/pages/app-page.php');
            break;
            
        default:        
            include_once('./library/templates/pages/404-page.php');
            break;
    }