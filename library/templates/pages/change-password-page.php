<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
<head>
    <?php require_once(META_TAGS); ?>
    <!-- CSS -->    
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . "css/login.css"; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . "css/app/page-change-password-style.css"; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY . "css/app/login-change-password-style.css"; ?>">    
    <!-- JS -->    
    <script src="<?php echo SCRIPTS_DIRECTORY . "/change-password.js"; ?>"></script>    
    <title><?php echo APP_NAME; ?> - Cambiar Contraseña</title>
</head>
<body>

    <?php include_once('./library/templates/parts/change-password-part.php'); ?>
    
    <?php include_once('./library/templates/parts/loading-part.php') ?>

</body>
</html>