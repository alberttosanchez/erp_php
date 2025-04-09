<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo ASSETS_DIRECTORY.'images/'.FAVICON; ?>" type="image/x-icon">

<!-- CSS -->
<link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/global-style.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/all.min.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/bootstrap.min.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/cropper.min.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/loading-style.css'; ?>">

<?php if ( file_exists( MODULES_DIRECTORY . $GLOBALS['MODULE_TO_SHOW'] . '/assets/css/module-style.css' ) ) : ?>
    <link rel="stylesheet" href="<?php echo DOMAIN_URL . '/modules/' . $GLOBALS['MODULE_TO_SHOW'] . '/assets/css/module-style.css'; ?>">
<?php endif; ?>

<?php if (is_path('not-found') ) : ?>
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/not-found-style.css'; ?>">
<?php endif; ?>

<?php if ( is_home() || is_path('login') || is_path('recovery') ) : ?>
    
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/login-page-style.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/login.css'; ?>">

    <?php if ( is_path('recovery') ) : ?>

        <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/recovery.css'; ?>">

    <?php endif; ?>
    
<?php endif; ?>

<?php if ( is_path('app') || is_path('profile') || is_path('manage')  || is_path( get_module_name() ) ) : ?>

    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/app-header-style.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/app-body-style.css'; ?>">        
    <link rel="stylesheet" href="<?php echo ASSETS_DIRECTORY.'css/app-sidebar-style.css'; ?>">


<?php endif; ?>

<!-- JS -->
<?php require_once('./admin/config-js.php'); ?>
<script src="<?php echo SCRIPTS_DIRECTORY.'functions.js'; ?>"></script>
<?php if (
  ! ( is_home() ||
      is_path('login') ||
      is_path('index.php') ||
      is_path('recovery') ||
      is_path('sign-out') ||
      is_path('not-found') ||
      ( get_path('/confirm_account') === "/confirm_account" ) ||
      ( get_path('/change_password') === "/change_password" ) )
    ) : ?>
    <?php require_once('./admin/check-session-js.php'); ?>
<?php endif; ?>

<?php if ( is_path('app') || is_path('profile') || is_path('manage')  || is_path( get_module_name() ) ) : ?>
    <script src="<?php echo SCRIPTS_DIRECTORY.'check-session-time.js'; ?>"></script>
<?php endif; ?>

<script src="<?php echo ASSETS_DIRECTORY.'js/all.min.js'; ?>"></script>
<script src="<?php echo ASSETS_DIRECTORY.'js/bootstrap.min.js'; ?>"></script>
<script src="<?php echo ASSETS_DIRECTORY.'js/cropper.min.js'; ?>"></script>
<script src="<?php echo ASSETS_DIRECTORY.'js/chart.min.js'; ?>"></script>
<script src="<?php echo ASSETS_DIRECTORY.'js/momentjs-with-locales.min.js'; ?>"></script>
<?php if ( ! is_path('not-found') ) : ?>
    <?php require_once('./library/templates/parts/no-script.php'); ?>
<?php endif; ?>