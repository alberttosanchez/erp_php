<!-- Sidebar -->
<div id="profile_sidebar" class="profile_sidebar left_sidebar">
    <div class="top_sidebar_wrap">
        <h2 class="no-show">Perfil</h2>
        <button onclick="expand_or_contract_sidebar()" class="expand_or_close_button">
            <span class="sidebar-menu-button"><i class="fa fa-bars"></i></span>
            <span class="sidebar-menu-button no-show"><i class="fas fa-times"></i></span>
        </button>
    </div>
    <hr>
    <ul class="menu-list unstyled-list">
        <li>
            <button type="button" value="profile_info_section" onclick="show_info(this)" class="w3-bar-item w3-button"><span class="file-alt-icon f-icon"><i class="far fa-file-alt"></i></span> Información</button>
        </li>
        <li>
            <button type="button" value="profile_avatar_section" onclick="show_info(this)" class="w3-bar-item w3-button"><span class="user-icon f-icon"><i class="fa fa-user"></i></span> Avatar</button>
        </li>        
    </ul>    
</div>