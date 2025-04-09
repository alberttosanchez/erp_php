<?php include_once( $module_path . 'admin/setting-js.php'); // ver setting-js.php ?>

<?php if ( is_file(ARCH_SCRIPTS_DIRECTORY . '/setting.js') ) : ?>
    <!-- Meta Ini -->
    <!-- JS -->
    <script src="<?php echo ARCH_SCRIPTS_DIRECTORY . '/setting.js';?>"></script>
    <script src="<?php echo ARCH_SCRIPTS_DIRECTORY . '/functions.js';?>"></script>
    <script src="<?php echo ARCH_ASSETS_DIRECTORY . '/js/cropper.min.js';?>"></script>
    <script type="module" src="<?php echo ARCH_CLASSES_DIRECTORY . '/class.index.js';?>"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ARCH_ASSETS_DIRECTORY . '/css/cropper.min.css';?>">

<?php endif; ?>

<!-- Meta Fin -->