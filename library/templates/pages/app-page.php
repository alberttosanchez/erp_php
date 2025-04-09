<?php 
    // var_dump($_SESSION);
    if ( ! isset($_SESSION['user_id']) )
    {
        # capturamos los datos en la variable global $_session
        $_SESSION['user_id'] = isset($_POST['user_id']) ? $_POST['user_id'] : "";
        $_SESSION['role_id'] = isset($_POST['role_id']) ? $_POST['role_id'] : "";
        $_SESSION['role_super'] = isset($_POST['role_super']) ? $_POST['role_super'] : "";
        $_SESSION['session_token'] = isset($_POST['session_token']) ? $_POST['session_token'] : "";        
    }
?>

<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
<head>
    <?php require_once(META_TAGS); ?>
    
    <script src="<?php echo SCRIPTS_DIRECTORY . '/app-page.js';?>"></script>
    <script src="<?php echo SCRIPTS_DIRECTORY . '/app-page-header.js';?>"></script>

    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/app/sidebar-style.css'; ?>">

    <?php if ( is_path('profile') ) : ?>
        <!-- css -->
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/profile/profile-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/profile/profile-info-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/profile/profile-message-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/profile/profile-avatar-style.css'; ?>">
        <!-- js -->
        <script src="<?php echo SCRIPTS_DIRECTORY.'sidebar.js'; ?>"></script>
        <script src="<?php echo SCRIPTS_DIRECTORY.'profile-content.js'; ?>"></script>        
    <?php elseif ( is_path('manage') ) : ?>
        <!-- css -->
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/manage/manage-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/manage/manage-users-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/manage/settings-users-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/manage/new-users-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/modules/modules-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/modules/modules-install-style.css'; ?>">
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . 'css/modal-warning-style.css'; ?>">
        <!-- js -->
        <script src="<?php echo SCRIPTS_DIRECTORY.'manage-content.js'; ?>"></script>        
        <script src="<?php echo SCRIPTS_DIRECTORY.'module-content.js'; ?>"></script>        
    <?php endif; ?>    
    <title><?php echo APP_NAME; ?> - <?php echo ( the_module_title() ) ? the_module_title() : "Dashboard"; ?></title>
</head>
<body>
    
    <div id="Main__wrapper" class="Main__wrapper">
        <div class="App__page" style="display: none !important">

            <?php include_once('./library/templates/parts/app-header-part.php') ?>   
                  
            <?php include_once('./library/templates/parts/app-body-part.php') ?>         
            
            <?php // include_once('./library/templates/parts/app-footer-part.php') ?>         
        
        </div> 
    </div>   
    
    <?php include_once('./library/templates/parts/loading-part.php') ?>

    <?php include_once('./library/templates/parts/warning-modal-part.php') ?>

</body>
</html>