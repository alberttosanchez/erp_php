<?php include_once( BCMJ_TEMPLATE_PARTS . '/module-meta-part.php'); ?>  

<main id="module_page" class="module_page">

    <div class="page_wrapper">

        <?php include_once( BCMJ_TEMPLATE_PARTS . '/sidebar-part.php'); ?>

        <div class="section_wrapper">

            <?php include_once( BCMJ_TEMPLATE_PARTS . '/landing-section-part.php'); ?>
    
            <?php #Estudiantes ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/student-new-section-part.php'); ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/student-academic-data-section-part.php'); ?>        
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/student-consult-section-part.php'); ?>
    
            <?php #Universidades ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/university-manage-section-part.php'); ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/university-consult-section-part.php'); ?>                
    
            <?php #Becas ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/scholarship-manage-section-part.php'); ?>
            <?php include_once( BCMJ_TEMPLATE_PARTS . '/scholarship-consult-section-part.php'); ?>        

            <?php #Caja de notificaciones ?>
            <?php include_once( BCMJ_TEMPLATE_CONTENTS . '/notification-box-content.php'); ?>

        </div>

    </div>

</main>

<script src="<?php echo  BCMJ_SCRIPTS_DIRECTORY . '/bcmj-sidebar.js'; ?>"></script>