<?php 
 
$module_to_show = $GLOBALS['MODULE_TO_SHOW'];

$module_path = MODULES_DIRECTORY . $module_to_show . '/';

if ( is_dir( $module_path ) )
{
    $module_route =  $module_path . 'index.php';       

    if ( is_file( $module_route ) )
    {
        require_once( $module_route );
        //die();
    }
    else
    {
        echo "<script> not_found(); </script>";
    }
}
else
{
    echo "<script> not_found(); </script>";
}