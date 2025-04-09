<?php include_once( ARCH_TEMPLATE_PARTS . '/module-meta-part.php'); ?>

<main id="module_page" class="module_page">

    <div class="page_wrapper">

        <?php include_once( ARCH_TEMPLATE_PARTS . '/sidebar-part.php'); ?>

        <div class="section_wrapper">

            <?php include_once( ARCH_TEMPLATE_PARTS . '/landing-section-part.php'); ?>
    
            
            <?php #Detalle del post modo lectura ?>
            <?php if (get_path('arch_post', 2, '/') === 'arch_post') : ?>
                <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-post-section-part.php'); ?>
            <?php endif; ?>

            <?php #Todos los Archivos ?>
            <?php if (get_path('arch_all', 2, '/') === 'arch_all') : ?>
                <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-all-section-part.php'); ?>
            <?php endif; ?>

            <?php if ( isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2 ) : ?>
            
                <?php if (get_path('arch_new', 2, '/') === 'arch_new') : ?>
                    <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-new-section-part.php'); ?>
                <?php endif; ?>

                <?php if (get_path('arch_edit', 2, '/') === 'arch_edit') : ?>
                    <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-edit-post-section-part.php'); ?>
                <?php endif; ?>
            
            <?php endif; ?>

            <?php if (get_path('arch_consult', 2, '/') === 'arch_consult') : ?>
                <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-consult-section-part.php'); ?>
            <?php endif; ?>

            <?php if ( isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2 ) : ?>

                <?php if (get_path('arch_category', 2, '/') === 'arch_category') : ?>
                    <?php #Categorias ?>
                    <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-category-section-part.php'); ?>                
                <?php endif; ?>
            
                <?php if (get_path('arch_settings', 2, '/') === 'arch_settings') : ?>
                    <?php #Opciones ?>
                    <?php include_once( ARCH_TEMPLATE_PARTS . '/arch-settings-section-part.php'); ?>                
                <?php endif; ?>

            <?php endif; ?>

            <?php #Caja de notificaciones ?>
            <?php include_once( ARCH_TEMPLATE_CONTENTS . '/notification-box-content.php'); ?>

        </div>

    </div>

</main>

<script src="<?php echo  ARCH_SCRIPTS_DIRECTORY . '/arch-sidebar.js'; ?>"></script>