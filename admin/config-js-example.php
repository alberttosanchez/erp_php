<script>

    const URL_BASE="<?php echo URL_BASE; ?>";

    const API_UPLOAD_PUBLIC_FILE_URL="<?php echo API_UPLOAD_PUBLIC_FILE_URL; ?>";     
    
    const TEMP_PUBLIC_FILE_DIR_PATH="<?php echo TEMP_PUBLIC_FILE_DIR_PATH; ?>";     

    const PUBLIC_TEMP_URL="<?php echo PUBLIC_TEMP_URL; ?>";     
    
    <?php if ( is_path('login') || is_home() ) : ?>
        
        const API_SIGNIN_LOGIN_URL="<?php echo API_SIGNIN_LOGIN_URL; ?>";

    <?php elseif ( is_path('app') || is_path('profile') || is_path('manage') || is_path( get_module_name() ) ) : ?>   
        
        const MODULE_DEFAULT_IMAGE="<?php echo MODULE_DEFAULT_IMAGE; ?>";

        const MODULES_DIRECTORY="<?php echo MODULES_DIRECTORY; ?>";

        const MODULES_ROUTE="<?php echo MODULES_ROUTE; ?>";
        
        const PROFILE_USERS_URL="<?php echo PROFILE_USERS_URL; ?>";
        
        const API_AVATAR_URL="<?php echo API_AVATAR_URL; ?>";

        const API_MANAGE_DB_URL="<?php echo API_MANAGE_DB_URL; ?>";
        
        const API_PROFILE_INFO_URL="<?php echo API_PROFILE_INFO_URL; ?>";
    
        const API_CATEGORIES_URL="<?php echo API_CATEGORIES_URL; ?>";
        
        const API_CHECK_ROLE_URL="<?php echo API_CHECK_ROLE_URL; ?>";
        
        const API_UPDATE_USER_PROFILE_URL="<?php echo API_UPDATE_USER_PROFILE_URL; ?>";

        const API_UPLOAD_PROFILE_AVATAR_URL="<?php echo API_UPLOAD_PROFILE_AVATAR_URL; ?>";

        const API_SETTING_USER_INFO_URL="<?php echo API_SETTING_USER_INFO_URL; ?>";

        const API_UPDATE_USER_DATA_URL="<?php echo API_UPDATE_USER_DATA_URL; ?>";

        const API_DELETE_USER_URL="<?php echo API_DELETE_USER_URL; ?>";
        
        const API_SEND_ACTIVATION_MESSAGE_URL="<?php echo API_SEND_ACTIVATION_MESSAGE_URL; ?>";

        const API_SIGN_UP_URL="<?php echo API_SIGN_UP_URL; ?>";

        const API_MODULES_URL="<?php echo API_MODULES_URL; ?>";

        const API_UPLOAD_MODULE_ZIP_FILE_URL="<?php echo API_UPLOAD_MODULE_ZIP_FILE_URL; ?>";
        
        const API_SESSION_URL="<?php echo API_SESSION_URL; ?>";

    <?php elseif ( is_path('confirm_account', 1, false) ) : ?>

        const API_CONFIRM_ACCOUNT_URL="<?php echo API_CONFIRM_ACCOUNT_URL; ?>";

    <?php elseif ( is_path('recovery') ) : ?>   
        
        const API_RESTORE_PASSWORD_URL="<?php echo API_RESTORE_PASSWORD_URL; ?>";

    <?php elseif ( is_path('change_password', 1, false) ) : ?>

        const API_VALIDATE_SECURITY_TOKEN_URL="<?php echo API_VALIDATE_SECURITY_TOKEN_URL; ?>";

        const API_CHANGE_PASSWORD_URL="<?php echo API_CHANGE_PASSWORD_URL; ?>";
        
    <?php elseif ( is_path('sign-out') ) : ?>

        const API_SIGN_OUT_URL="<?php echo API_SIGN_OUT_URL; ?>";
    
    <?php endif; ?>


</script>