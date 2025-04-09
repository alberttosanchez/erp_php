<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
    <head>
        <?php require_once(META_TAGS); ?>    
        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/page-confirm-account-style.css';?>">
        <script src="<?php echo SCRIPTS_DIRECTORY . '/confirm-account.js';?>"></script>    
        <title><?php echo APP_NAME; ?> - Activar Cuenta</title>   
    </head>
    <body>

        <div id="Main__wrapper" class="Main__wrapper no-show">
            <div class="Main__page">
                <div class="ConfirmAccount__wrapper">                           
                    <div class="ConfirmAccount__box">
                        <div class="ConfirmAccount__header">
                            <div class="Logo__imgBox mj-logo"><img src="<?php echo ASSETS_DIRECTORY.'images/mj-logo2-400x217.png';?>" alt="mj-logo"/></div>
                            <div class="Logo__imgBox ijoven-logo"><img src="<?php echo ASSETS_DIRECTORY.'images/ijoven-logo-slogan.png';?>" alt="ijoven-logo"/></div>
                        </div>   
                        <div class="ConfirmAccount__container">                            
                            <h4>Su cuenta ha sido confirmada correctamente.</h4>                          
                            <span>En breve será redirigido a la página de inicio, o presione <a href="/">aqui</a> para ir a inicio.</span>
                        </div>
                    </div>                                             
                </div>      
            </div>
        </div>

    </body>
</html>