<section id="profile_image_section" class="Profile__image profile_image_section content">                     
    <img src="<?php echo ASSETS_DIRECTORY . '/images/profile/profile.png'; ?>" alt="profile"/>            
</section>

<section id="profile_info_section" class="profile_info_section content" style="display: none !important;">    
    <h2>Información de Perfil</h2>              
    <div>
        <form onsubmit="return handleProfileSubmit(this)" class="settingUsers__profile form-group">                        
            <div class="profile_container">
                <div class="form-group">
                    <label for="profile_user_name">
                        <span>Usario</span><br/>
                        <input class="form-control" id="profile_user_name" name="users_name" type="text" maxlength="100" value="" disabled={true}/>
                    </label>                                
                </div>
                <div class="form-group">
                    <label for="profile_first_name">
                        <span>Nombres</span><br/>
                        <input class="form-control" id="profile_first_name" name="first_name" type="text" maxlength="100" value="" disabled={true}/>
                    </label>
                    <label for="profile_last_name">
                        <span>Apellidos</span><br/>
                        <input class="form-control" id="profile_last_name" name="last_name" type="text" maxlength="100" value="" disabled={true}/>
                    </label>
                    <label for="profile_goverment_id">
                        <span>Cédula</span><br/>
                        <input class="form-control" id="profile_goverment_id" name="users_goverment_id" type="text" maxlength="100" value="" disabled={true}/>
                    </label>
                </div>
                <div class="form-group">
                    <label for="profile_email">
                        <span>Correo Electrónico</span><br/>
                        <input class="form-control" id="profile_email" name="users_email" type="text" maxlength="100" value="" disabled={true}/>
                    </label>
                    <label for="profile_phone">
                        <span>Teléfono</span><br/>
                        <input onkeyup="handlePhoneChange(this)" class="form-control" id="profile_phone" name="users_phone" type="text" maxlength="20" value="" />
                    </label>
                    <label for="profile_birth_date">
                        <span>Fecha de Nacimiento</span><br/>
                        <input onkeyup="handleBDChange(this)" class="form-control" id="profile_birth_date" name="birth_date" type="text" maxlength="100" value="" />
                    </label>
                    <label for="profile_gender">
                        <span>Sexo</span><br/>                                    
                        <select onchange="handleChange(this)" class="form-control" id="profile_gender" name="gender_id" type="text" maxlength="100" value="" >
                            <option value=""></option>                            
                        </select>
                    </label>
                </div>                            
            </div>
            <button class="btn btn-primary" type="submit">Actualizar</button>                        
        </form>        
    </div>
</section>

<section id="profile_avatar_section" class="profile_avatar_section content" style="display: none !important;">
    <?php // ver profile-content.js ?>
    <div class="profileAvatar_wrapper">
        <h2>Imagen de Perfil</h2>
        <div class="profileAvatar_wrap">
            <div id="avatar_box" class="avatar_box">
                <?php // ver profile-content.js ?>   
                <span id="avatar_icon_form" class="avatar_icon_form"><i class="fa fa-user"></i></span>            
                <div class="spinner_box no-show">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <small>Suba una imagen en formato JPG o PNG.<br/>Tamaño maximo 2mb.</small>
            <button onclick="removeAvatar()" class="remove_avatar_btn btn btn-warning" type="button">Quitar Avatar</button>                    
        </div>                
        <div class="profileAvatar_formWrapper">
            <form onsubmit="return handleAvatarFormSubmit(this)" action="" id="form_avatar_image" encType="multipart-data">
                <label class="" for="avatar_file">Subir Imagen
                    <input onchange="handleAvatarChange(this)" class="avatar_file" type="file" name="avatar_file" id="avatar_file"/>
                </label>
                <!-- <button class="btn btn-primary" type="submit">Subir</button> -->
            </form>
        </div>
    </div>    
</section>