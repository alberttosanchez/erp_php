<?php // agregue aqui las dependencias ?>

<!-- Meta  -->
<?php include_once( CV_TEMPLATE_PARTS . '/meta-part.php' ); ?>

<?php include_once( CV_ADMIN_DIRECTORY . '/setting-js.php' ); ?>

<script src="<?php echo CV_SCRIPTS_DIRECTORY . '/config.js'; ?>"></script>

<script src="<?php echo CV_SCRIPTS_DIRECTORY . '/functions.js';?>"></script>

<div id="cv_module_page" class="cv_module_page" style="display:flex;overflow:hidden;">

    <?php include_once( CV_TEMPLATE_PARTS . '/sidebar-part.php' ); ?>

    <?php include_once( CV_TEMPLATE_PARTS . '/dashboard-part.php' ); ?>
    
    <?php include_once( CV_TEMPLATE_CONTENTS . '/notification-box-content.php' ); ?>

</div>
<script src="<?=CV_SCRIPTS_DIRECTORY . '/cv-sidebar.js';?>"></script>