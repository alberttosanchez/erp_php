<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
<head>
    <?php require_once(META_TAGS); ?>
    <script src="<?php echo SCRIPTS_DIRECTORY.'/login-page.js';?>"></script>    
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/page-recovery-style.css';?>">        
    <title><?php echo APP_NAME; ?> - Login</title>    
</head>
<body>
    
    <div id="Main__wrapper" class="Main__wrapper">
        <div class="Main__page">
            <div class="Login__wrapper">                           
                <div class="Login__box">
                    
                    <div class="Login__container">                            

                        <?php if ( is_home() || is_path('login') ) : ?>

                            <?php include_once('./library/templates/parts/login-part.php') ?>

                        <?php elseif ( is_path('recovery') ) : ?>

                            <?php include_once('./library/templates/parts/recovery-part.php') ?>

                        <?php endif; ?>          

                    </div>
                </div>                     
                <?php //<!-- <div id="message-box" class="message-box"></div>  --> ?>
                <?php #Caja de notificaciones ?>
                <?php include_once( './library/templates/contents/notification-box-content.php'); ?>
            </div>      
        </div> 
    </div>   
    
    <?php include_once('./library/templates/parts/loading-part.php') ?>

</body>
</html>