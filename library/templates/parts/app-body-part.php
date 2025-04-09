<div class="body__wrapper">

<?php if ( is_path('app') || is_path('manage') || is_path('profile') )  : ?>

    <div class="MenuLeftSidebar__wrapper contract">
        <div class="MenuLeftSidebar__leftSidebar">
            <div class="MenuLeftSidebar__menu">

                <?php if ( is_path('manage') )  : ?>
                    
                    <script src="<?php echo SCRIPTS_DIRECTORY . 'check-role.js'; ?>"></script>
                
                <?php endif; ?>    

                <?php if ( is_path('profile') ) : ?>
                
                    <?php include_once('./library/templates/contents/profile-sidebar-content.php'); ?>
                
                <?php elseif ( is_path('manage') ) : ?>
                    
                    <?php include_once('./library/templates/contents/manage-sidebar-content.php'); ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
    
<?php endif; ?>

    <?php if ( is_path('app') || is_path('profile') || is_path('manage') ) : ?>

        <div class="MenuRightSidebar__wrapper">

            <?php if ( is_path('app') ) : ?>

                <?php include_once('./library/templates/parts/module-icon-part.php'); ?>

            <?php elseif ( is_path('profile') ) : ?>
                    
                <?php include_once('./library/templates/contents/profile-content.php'); ?>
            
            <?php elseif ( is_path('manage') ) : ?>

                <?php include_once('./library/templates/contents/manage-content.php'); ?>

                <?php include_once('./library/templates/contents/module-content.php'); ?>
            
            <?php endif; ?>
            
        </div>
    
    <?php elseif ( is_path( get_module_name(), 1, false ) ) : ?>
        
        <?php include_once( './modules/index.php' ); ?> 
    
    <?php endif; ?>

    <div id="users_message" class="users_message"></div>
</div>