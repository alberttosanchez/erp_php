
<section id="manage_image_section" class="Manage__image manage_image_section content">                     
    <img src="<?php echo ASSETS_DIRECTORY . '/images/manage/manage.png'; ?>" alt="manage"/>
</section>

<section id="manage_users_section" class="manage_users_section content" style="display: none !important;">    
    <div class="settingUsers__wrapper">
        <div class="filter_section_container" >
            <h2>INFORMACION DE USUARIOS</h2>
            <form onsubmit="return handleSubmitFilter()" class="form_container" action="" method="post">
                <div class="form-group d-flex">
                    <div class="input-group">
                        <label class="w-50 p-1" for="filter_selected">
                            <span>Filtrar</span><br/>
                            <select onchange="handleFilterChange(this)" value="" class="form-control" id="filter_selected" name="filter_selected" type="text" placeholder="Seleccionar un filtro..." maxlength="30" >
                                <option default value=""> -- Filtros -- </option>
                                <?php // filters ?>
                            </select>
                        </label>
                        <label class="w-50 p-1" for="keyword">
                            <span>Palabra Clave</span><br/>
                            <input onchange="handleFilterChange(this)" value="" class="form-control" id="keyword" name="keyword" type="text" placeholder="Introduzca palabra clave" maxlength="50" />
                        </label>
                    </div>
                    <button onclick="handleManageUsersClick(this)" name="filter_button" value="1" class="btn btn-primary" type="submit" style="margin-top:27px;margin-bottom:4px">Buscar</button>                    
                </div>
            </form>  
        </div>
        <hr/>
        <div class="settingUsers__gridData form-group">
            <table id="settingUsers_table" class="table_container">                            
                <thead>
                    <tr class="table_header" >
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Cedula</th>
                        <th>Correo Electrónico</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php // queryResult ?>
                </tbody>
            </table>
        </div>
        
        <!-- <Pagination 
            onCounter={props.onCounter}
            onPagination={props.onPagination}
            onPaginationClick={props.onPaginationClick}
        /> -->
        
        <?php require_once('./library/templates/contents/pagination-content.php'); ?>

        <hr/>
       
        <form id="setting_user_details_form" onsubmit="return handleSubmitDetails()" class="settingUsers__details form-group">
            <h2>DETALLE DE USUARIO</h2>
            <div id="details__activationWrapper" class='details__activationWrapper no-show'>
                <div class="activationWrap">
                    <p>
                        El usuario aun no esta activado.
                    </p>
                    <button onclick="handleActivationButton()" class="details_activartionButton" type="button">Reenviar Mensaje de Activación</button>
                </div>
            </div>
            <div class="details_container">
                <div class="form-group">
                    <label for="details_user_name">
                        <span>Usuario</span><br/>
                        <input onchange="handleUserChange(this)" class="form-control" id="details_user_name" name="users_name" type="text" maxlength="100" value="" disabled/>
                    </label>
                    <label for="details_rol">
                        <span>Rol</span><br/>                                    
                        <select  onchange="handleUserChange(this)" class="form-control" id="details_rol" name="role_id" type="text" maxlength="100" value="" disabled>
                        <option value=""></option>
                        <!-- {role_options} -->
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="details_first_name">
                        <span>Nombres</span><br/>
                        <input  onchange="handleUserChange(this)" class="form-control" id="details_first_name" name="first_name" type="text" maxlength="100" value="" disabled/>
                    </label>
                    <label for="details_last_name">
                        <span>Apellidos</span><br/>
                        <input  onchange="handleUserChange(this)" class="form-control" id="details_last_name" name="last_name" type="text" maxlength="100" value="" disabled/>
                    </label>
                    <label for="details_goverment_id">
                        <span>Cédula</span><br/>
                        <input  onkeyup="handleId(this)" class="form-control" id="details_goverment_id" name="users_goverment_id" type="text" maxlength="100" value="" disabled/>
                    </label>
                </div>
                <div class="form-group">
                    <label for="details_email">
                        <span>Correo Electrónico</span><br/>
                        <input  onchange="handleUserChange(this)" class="form-control" id="details_email" name="users_email" type="email" maxlength="100" value="" disabled/>
                    </label>
                    <label for="details_phone">
                        <span>Teléfono</span><br/>
                        <input  onkeyup="handlePhone(this)" class="form-control" id="details_phone" name="users_phone" type="text" maxlength="20" value="" disabled/>
                    </label>
                    <label for="details_birth_date">
                        <span>Fecha de Nacimiento</span><br/>
                        <input  onkeyup="handleBirthDate(this)" class="form-control" id="details_birth_date" name="birth_date" type="text" maxlength="100" value="" disabled/>
                    </label>
                    <label for="details_gender">
                        <span>Sexo</span><br/>                                    
                        <select  onchange="handleUserChange(this)" class="form-control" id="details_gender" name="gender_id" type="text" maxlength="100" value="" disabled>
                        <option value=""> -- Sexo -- </option>
                        <!-- {options} -->
                    </select>
                    </label>
                </div>                            
            </div>
            <button id="details_btn_submit" class="btn btn-primary" type="submit">Actualizar</button>                        
        </form>        
    </div>
</section>

<section id="manage_new_user_section" class="manage_new_user_section content" style="display: none !important;">
    <div class="newUsers__wrapper">                    
        <form onsubmit="return false" class="newUsers__details form-group">
            <h2>REGISTRAR NUEVO USUARIO</h2>
            <div class="newUsers_container">
                <div class="form-group">
                    <label for="newUsers_user_name">
                        <span>Nombre de Usuario (nick)</span><br/>
                        <input  onkeyup="handleUserChange(this)" class="form-control" id="newUsers_users_name" name="newUsers_users_name" type="text" value="" maxlength="50" />
                    </label>
                </div>
                <div class="form-group">
                    <label for="newUsers_first_name">
                        <span>Nombres</span><br/>
                        <input  onkeyup="handleUserChange(this)" class="form-control" id="newUsers_first_name" name="newUsers_first_name" type="text" value="" maxlength="100" />
                    </label>
                    <label for="newUsers_last_name">
                        <span>Apellidos</span><br/>
                        <input  onkeyup="handleUserChange(this)" class="form-control" id="newUsers_last_name" name="newUsers_last_name" type="text" value="" maxlength="100" />
                    </label>
                </div>
                <div class="form-group">
                    <label for="newUsers_gender">
                        <span>Sexo</span><br/>
                        <select  onchange="handleUserChange(this)" class="form-control" id="newUsers_gender" name="newUsers_gender" type="text" value="" maxlength="100" >
                            <option value=""> -- Sexo -- </option>                                    
                            <?php // options ?>
                        </select>
                    </label>
                    <label for="newUsers_goverment_id">
                        <span>Cédula</span><br/>
                        <input  onkeyup="handleId(this)" class="form-control" id="newUsers_goverment_id" name="newUsers_users_goverment_id" type="text" value="" maxlength="13" placeholder="000-0000000-0" />
                    </label>
                </div>      
                <div class="form-group">
                    <label for="newUsers_email">
                        <span>Correo Electrónico</span><br/>
                        <input  onkeyup="handleUserChange(this)" class="form-control" id="newUsers_email" name="newUsers_users_email" type="email" value="" maxlength="100" />
                    </label>
                    <label for="newUsers_phone">
                        <span>Teléfono</span><br/>
                        <input  onkeyup="handlePhone(this)" class="form-control" id="newUsers_phone" name="newUsers_users_phone" value="" type="phone" maxlength="12" placeholder="000-000-0000" />
                    </label>
                    
                </div>                      
            </div>
            <button class="btn btn-primary" type="button" onclick="handleNewUSerSubmit()">Registrar</button>                        
        </form>
    </div>
</section>