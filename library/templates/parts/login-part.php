<div id="Login_form" class="Login__wrap">    
    <form onsubmit="return handle_login_submit()" id="Login__form"  class="Login__body Login__form" action="" method="post">
        <label class="Login__formUserInput" htmlFor="Login__userName">
            <span>Nombre de usuario o correo electrónico</span>
            <div class="Login__user_group_wrap">
                <span class="user-icon"><i class="far fa-user"></i></span>
                <input onchange="handle_login_form_change(this)" class="form-control Login__input" type="text" id="Login__userName" name="Login__userName" placeholder="Usuario, ID o Correo Electrónico" value="" autoComplete="username" maxlength="100"/>
            </div>
        </label>
        <label class="Login__formPassInput" htmlFor="Login__password">
            <span>Contraseña</span>
            <div class="Login__user_group_wrap">
                <span class="lock-icon"><i class="fa fa-lock"></i></span>            
                <input onchange="handle_login_form_change(this)" class="form-control Login__input" type="password" id="Login__password" name="Login__password" placeholder="Contraseña" value="" autoComplete="current-password" maxlength="100"/>
            </div>
        </label>
        <button id="submit_login_button" onclick="handle_login_click_button()" class="btn btn-danger" type="submit" disabled>Iniciar Sesión</button>
    </form>                                                                    
    <div class="Login__footer">
        <p><a href="<?php echo URL_BASE.'/recovery'; ?>" disabled>¿Olvidaste tu contraseña?</a></p>
        <!-- <button onclick="show_rescovery_form()" type="button" value="/restore">Recuperar Contraseña</button> -->
    </div> 
</div>