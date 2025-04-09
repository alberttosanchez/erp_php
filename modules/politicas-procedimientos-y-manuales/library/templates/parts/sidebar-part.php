<nav class="nav sidebar menu_sidebar">

    <ul class="menu unstyled-list">
        <li class="opened">
            <a class="btn" data-bs-toggle="collapse" href="#collapseArchivo" role="button" aria-expanded="false" aria-controls="collapseArchivo">
                <span class="menu_icon_title_wrap">
                    <span class="menu_icon"><i class="fas fa-book"></i></span>
                    <span class="menu_title_text">Entradas</span>
                </span>                        
            </a>
            <div class="collapse show" id="collapseArchivo">
                <ul class="sub_menu unstyled-list">
                    <li id="arch_new_submenu">
                        <a href="<?php echo ARCH_URI_BASE; ?>/arch_post" style="display:none;">
                            <span class="submenu_icon_text_wrap">
                                <span class="sub_menu_icon"><i class="fas fa-th-list"></i></span>
                                <span class="sub_menu_text">Post</span>
                            </span>                        
                        </a>
                        <a href="<?php echo ARCH_URI_BASE; ?>/arch_all">
                            <span class="submenu_icon_text_wrap">
                                <span class="sub_menu_icon"><i class="fas fa-th-list"></i></span>
                                <span class="sub_menu_text">Mostrar Todas</span>
                            </span>                        
                        </a>

                        <?php if ( isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2 ) : ?>

                            <a href="<?php echo ARCH_URI_BASE; ?>/arch_new">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-plus-circle"></i></span>
                                    <span class="sub_menu_text">Nueva</span>
                                </span>                        
                            </a>

                            <a href="<?php echo ARCH_URI_BASE; ?>/arch_edit" style="display:none !important">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-plus-circle"></i></span>
                                    <span class="sub_menu_text">Editar Post</span>
                                </span>
                            </a>

                        <?php endif; ?>
                        
                    </li>                    
                    <li>
                        <a href="<?php echo ARCH_URI_BASE; ?>/arch_consult">
                            <span class="submenu_icon_text_wrap">
                                <span class="sub_menu_icon"><i class="fas fa-id-card"></i></span>
                                <span class="sub_menu_text">Consultar</span>
                            </span>                        
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <?php if ( isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2 ) : ?>

            <li class="opened">
                <a class="btn" data-bs-toggle="collapse" href="#collapseCategories" role="button" aria-expanded="false" aria-controls="collapseCategories">
                    <span class="menu_icon_title_wrap">
                        <span class="menu_icon"><i class="far fa-list-alt"></i></span>
                        <span class="menu_title_text">Categorias</span>
                    </span>                        
                </a>
                <div class="collapse show" id="collapseCategories">
                    <ul class="sub_menu unstyled-list">                    
                        <li>
                            <a href="<?php echo ARCH_URI_BASE; ?>/arch_category">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="far fa-flag"></i></span>
                                    <span class="sub_menu_text">Consultar</span>
                                </span>                        
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="opened">
                <a class="btn" data-bs-toggle="collapse" href="#collapseOptions" role="button" aria-expanded="false" aria-controls="collapseOptions">
                    <span class="menu_icon_title_wrap">
                        <span class="menu_icon"><i class="fas fa-cogs"></i></span>
                        <span class="menu_title_text">Opciones</span>
                    </span>                        
                </a>
                <div class="collapse show" id="collapseOptions">
                    <ul class="sub_menu unstyled-list">                    
                        <li>
                            <a href="<?php echo ARCH_URI_BASE; ?>/arch_settings">
                                <span class="submenu_icon_text_wrap">
                                    <span class="sub_menu_icon"><i class="fas fa-tools"></i></span>
                                    <span class="sub_menu_text">Generales</span>
                                </span>                        
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        <?php endif; ?>
        
    </ul>            

    <button class="expand_btn" type="button" onclick="expand_nav()">
        <span class="expand_btn_icon"><i class="fas fa-angle-double-right"></i></span>
        <span class="expand_btn_icon no-show"><i class="fas fa-angle-double-left"></i></span>
    </button>
    
</nav>