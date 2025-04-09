<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
<head>
    <?php require_once(META_TAGS); ?>    
    <title><?php echo APP_NAME; ?> - Inicio</title>
</head>
<body>
    
    <?php if ( is_home() || is_path('login') ) : ?>
        <?php include_once('./library/templates/parts/login-part.php') ?>
    <?php endif; ?>

</body>
</html>