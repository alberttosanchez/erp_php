<style>
    .cv_main > section {
        max-width: 1240px;
        margin: auto;
    }
</style>
<main id="cv_main" class="cv_main" style="width:100vw;padding:20px;background-color:white;">
    
    <?php if (get_path('dashboard', 2, '/') === 'dashboard' || get_path('dashboard', 2, '/') === '/control-de-visitas')  : ?> 

        <!-- DashBoard -->
        <section id="cv_dashboard_section" class="cv_dashboard section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/dashboard-content.php'); ?>
        
        </section>

    <?php endif; ?>

    <?php if (get_path('register', 2, '/') === 'register') : ?>  

        <!-- Gestionar Visitantes -->
        <section id="cv_register_visit_section" class="cv_register_visit section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/register-visit-content.php'); ?>
            
        </section>

    <?php endif; ?>

    <?php if (get_path('finalize_visit', 2, '/') === 'finalize_visit') : ?> 

        <section id="cv_finalize_visit_section" class="cv_finalize_visit section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/finalize-visit-content.php'); ?>
            
        </section>

    <?php endif; ?>
    
    <?php if (get_path('visit_history', 2, '/') === 'visit_history') : ?>

        <!-- Consultar  -->
        <section id="cv_history_visit_section" class="cv_history_visit section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/history-visit-content.php'); ?>
            
        </section>

    <?php endif; ?>
    
    <?php if (get_path('reports', 2, '/') === 'reports') : ?>

        <!-- Reportes -->
        <section id="cv_reports_section" class="cv_reports_visit section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/reports-content.php'); ?>
            
        </section>

    <?php endif; ?>
    
    <?php if (get_path('coworkers', 2, '/') === 'coworkers') : ?>

        <!-- Administrar -->
        <section id="cv_coworkers_section" class="cv_coworkers_section section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/coworkers-content.php'); ?>
            
        </section>

    <?php endif; ?>
    
    <?php if (get_path('plant_distribution', 2, '/') === 'plant_distribution') : ?>

        <!-- Distribucion de planta fisica -->
        <section id="cv_plant_distribution_section" class="cv_plant_distribution_section section">
        
            <?php include_once( CV_TEMPLATE_CONTENTS . '/plant-distribution-content.php'); ?>
        
        </section>

    <?php endif; ?>
    
    <?php if (get_path('general', 2, '/') === 'general') : ?>

        <!-- Opciones Generales -->
        <section id="cv_general_section" class="cv_general_section section">
            
            <?php include_once( CV_TEMPLATE_CONTENTS . '/general-content.php'); ?>
            
        </section>

    <?php endif; ?>    
    

</main>