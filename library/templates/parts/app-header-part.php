<?php // ver app-page-header.js para los scripts ?>                        
<div id="App__headerWrapper" class="App__headerWrapper">
    <div class="App__title">
        <?php /* css controla este titulo*/ ?>
        <span class="short_title"><h1><a class="no-decoration" href="<?php echo URL_BASE . '/app'; ?>">IJOVEN</a></h1></span>
        <span class="large_title"><h1><a class="no-decoration" href="<?php echo URL_BASE . '/app'; ?>">IJOVEN</a> - Intranet Joven del Ministerio de la Juventud</h1></span>
    </div>
    <div class="App__menu">
        <div class="menu_wrapper">                        
            <button onclick="toggleMenu()" type="button" class="menu_button">
                <span class="user_name"></span>                
            </button>
            <div id="main_menu" class="main_menu no-show">
                <ul class="unstyled-list">  
                    <li><a href="<?php echo URL_BASE . '/app'; ?>"><span class="icon_home"><i class="fa fa-home"></i></span><span>Inicio<span></a></li>                                
                    <?php // si es un administrador mostrar opciones de administracion ?>                        
                    <?php //<li><a href="/manage"><span class="tools-icon"><i class="fa fa-tools"></i></span><span>Administrar<span></a></li> ?>                                               
                    <li><a href="<?php echo URL_BASE . '/profile'; ?>"><span class="user-edit-icon"><i class="fa fa-user-edit"></i></span><span>Perfil<span></a></li>                                
                    <li><a href="<?php echo URL_BASE . '/sign-out'; ?>" ><span class="sign-out-alt-icon"><i class=" fa fa-sign-out-alt"></i></span><span>Salir<span></a></li>
                </ul>                                
            </div>
        </div>
    </div>
</div> 