<style>
    .left_sidebar .top_sidebar_wrap {

    }
    .left_sidebar .menu-list > li {
        position: relative;
    }    
    .rotate:before{
        transform: rotate(-45deg) !important;
    }
    .left_sidebar .has_children:before {
        content: "";
        width: 7px;
        height: 7px;
        display: block;
        position: absolute;
        transform: rotate(45deg);
        border-left: 1px solid #ffffff;
        border-bottom: 1px solid #ffffff;
        right: 0;
        top: 34px;
        transition: 0.3s;
    }
    .left_sidebar .sub-menu-list{
        position: relative;
    }
    .left_sidebar .sub-menu-list > div{
        overflow: hidden;   
    }
    .left_sidebar .sub-menu-list li{
        margin-left: 63px;        
    }
    .left_sidebar .sub-menu-list button{
        border: unset;
        background-color: unset;
        color: white;
        font-weight: bold;
    }
    .sub-menu-wrap{
        transition: 0.5s;
    }
    .contract{
        height: 0;
        transition: 0.5s;
    }
</style>
<script>
    window.addEventListener('DOMContentLoaded', () => {

        if (localStorage.show_manage === "true")
        {
            show_info("manage_modules_section"); getModules();
            localStorage.show_manage = "";
        }

    })
</script>
<!-- Sidebar -->
<div id="manage_sidebar" class="manage_sidebar left_sidebar">
    <div class="top_sidebar_wrap">
        <h2 class="no-show">Administración</h2>
        <button onclick="expand_or_contract_sidebar()" class="expand_or_close_button">
            <span class="sidebar-menu-button"><i class="fa fa-bars"></i></span>
            <span class="sidebar-menu-button no-show"><i class="fas fa-times"></i></span>
        </button>
    </div>
    <hr>
    <ul class="menu-list unstyled-list">
        <li class="has_children">
            <button type="button" value="manage_users" onclick="expand_or_contract_sub_menu_in_sidebar(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="fas fa-users"></i></span> Usuarios</button>
            <ul class="sub-menu-list unstyled-list">
                <div class="sub-menu-wrap contract">
                    <li>
                        <button type="button" value="manage_users_section" onclick="show_info(this)" class="w3-bar-item w3-button">Gestionar</button>
                    </li>
                    <li>
                        <button type="button" value="manage_new_user_section" onclick="show_info(this)" class="w3-bar-item w3-button">Nuevos</button>
                    </li>
                </div>
            </ul>
        </li>
        <li class="has_children">
            <button type="button" value="manage_modules" onclick="expand_or_contract_sub_menu_in_sidebar(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="far fa-file-alt"></i></span> Modulos</button>
            <ul class="sub-menu-list unstyled-list">
                <div class="sub-menu-wrap contract">
                    <li>
                        <button type="button" value="manage_modules_section" onclick="show_info(this); getModules()" class="w3-bar-item w3-button">Administrar</button>
                    </li>
                    <li>
                        <button type="button" value="manage_install_modules_section" onclick="show_info(this)" class="w3-bar-item w3-button">Instalar</button>
                    </li>
                </div>
            </ul>
        </li>        
    </ul>    
</div>