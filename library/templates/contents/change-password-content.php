<div class="LoginChangePassword__box">                     
    <div class="LoginChangePassword__title">
        <h1>CAMBIAR CONTRASEÑA</h1>
    </div>
    <form onsubmit="return handleChangePassSubmit()" id="LoginChangePassword__form" class="LoginChangePassword__body LoginChangePassword__form" action="" method="post">
        <label class="LoginChangePassword__formUserInput" for="LoginChangePassword__newPassword">            
            <input onkeyup="handleInputChangePassChange(this)" onchange="handleInputChangePassChange(this)" class="form-control" type="password" id="LoginChangePassword__newPassword" name="LoginChangePassword__newPassword" value="" placeholder="Escriba nueva contraseña" maxLength="100" disabled/>
        </label>
        <label class="LoginChangePassword__formUserInput" for="LoginChangePassword__newPassword2">            
            <input onkeyup="handleInputChangePassChange(this)" onchange="handleInputChangePassChange(this)" class="form-control" type="password" id="LoginChangePassword__newPassword2" name="LoginChangePassword__newPassword2" value="" placeholder="Repita la nueva contraseña" maxLength="100" disabled/>                        
        </label>
        <button onclick="handleChangePassBtnClick()" class="btn btn-primary" type="submit">Cambiar Contraseña</button>                                                    
    </form>
    <div class="LoginChangePassword__footer">
        <p><a href="<?php echo URL_BASE . '/login'; ?>">Iniciar Sesión</a></p>
    </div>          
</div>  