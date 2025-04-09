<?php

/**
 * Este archivo contine la logica para mostrar el menu del sidebar del modulo.
 */
?>
<link rel="stylesheet" href="<?php echo CV_ASSETS_DIRECTORY . '/css/sidebar.style.css'; ?>">
<!-- Sidebar -->
<div id="module_sidebar" class="module_sidebar left_sidebar contract">
    <div class="top_sidebar_wrap">
        <h2 class="no-show">Control de Visitas</h2>
        <button onclick="expand_nav()" class="expand_or_close_button">
            <span class="sidebar_menu_button"><i class="fa fa-bars"></i></span>
            <span class="sidebar_menu_button no-show"><i class="fas fa-times"></i></span>
        </button>
    </div>
    <hr>
    <ul class="menu-list unstyled-list">

        <li id="cv_dashboard">
            <?php // <button type="button" value="cv_dashboard" onclick="show_info(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="fas fa-chart-pie"></i></span> DashBoard</button> ?>
            <a href="<?php echo CV_URI_BASE; ?>/dashboard" class="sidebar_anchor">
                <span class="submenu_icon_text_wrap">
                    <span class="menu_icon"><i class="fas fa-chart-pie"></i></span>
                    <span class="sub_menu_text">DashBoard</span>
                </span>                        
            </a>
        </li>

        <li class="has_children">
            <button type="button" value="cv_manage_visit" onclick="expand_or_contract_sub_menu_in_sidebar(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="fas fa-hands-helping"></i></span> Gestionar Visitantes</button>
            <ul class="sub-menu-list  unstyled-list">
                <div class="sub-menu-wrap contract">

                    <li id="cv_register_visit">                        
                        <a href="<?php echo CV_URI_BASE; ?>/register" class="sidebar_anchor">
                            <span class="submenu_icon_text_wrap">
                                <span class="sub_menu_icon"><i class="fas fa-users"></i></span>
                                <span class="sub_menu_text">Registrar Visita</span>
                            </span>                        
                        </a>
                    </li>

                    <li id="cv_finalize_visit">                                            
                        <a href="<?php echo CV_URI_BASE; ?>/finalize_visit" class="sidebar_anchor">
                            <span class="submenu_icon_text_wrap">
                                <span class="sub_menu_icon"><i class="fas fa-user-check"></i></span>
                                <span class="sub_menu_text">Finalizar Visita</span>
                            </span>                        
                        </a>
                    </li>

                </div>
            </ul>
        </li>
        <li id="cv_history_visit">            
            <a href="<?php echo CV_URI_BASE; ?>/visit_history" class="sidebar_anchor">
                <span class="submenu_icon_text_wrap">
                    <span class="menu_icon"><i class="fas fa-history"></i></span>
                    <span class="sub_menu_text">Consultar</span>
                </span>                        
            </a>
        </li>
        <li id="cv_reports">            
            <a href="<?php echo CV_URI_BASE; ?>/reports" class="sidebar_anchor">
                <span class="submenu_icon_text_wrap">
                    <span class="menu_icon"><i class="fas fa-book"></i></span>
                    <span class="sub_menu_text">Reportes</span>
                </span>                        
            </a>
        </li>
        
        <?php if ( isset($_SESSION['role_id']) && $_SESSION['role_id'] == "1" || $_SESSION['role_id'] == "2" ) : ?>

            <li id="administration_menu" class="has_children">            
            
                <button type="button" value="admin" onclick="expand_or_contract_sub_menu_in_sidebar(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="fas fa-cogs"></i></span> Administrar</button>
                <ul class="sub-menu-list  unstyled-list">
                    <div class="sub-menu-wrap contract">
                        <li id="cv_coworkers">                            
                            <a href="<?php echo CV_URI_BASE; ?>/coworkers" class="sidebar_anchor">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-user-tie"></i></span>
                                    <span class="sub_menu_text">Colaboradores</span>
                                </span>                        
                            </a>
                        </li>
                        <li id="cv_plant_distribution">                            
                            <a href="<?php echo CV_URI_BASE; ?>/plant_distribution" class="sidebar_anchor">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-building"></i></span>
                                    <span class="sub_menu_text">Distribución en Planta</span>
                                </span>                        
                            </a>
                        </li>
                        <li id="cv_general">                            
                            <a href="<?php echo CV_URI_BASE; ?>/general" class="sidebar_anchor">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-filter"></i></span>
                                    <span class="sub_menu_text">General</span>
                                </span>                        
                            </a>
                        </li>
                    </div>
                </ul>            
            </li>

        <?php endif; ?>
    </ul>       
</div>