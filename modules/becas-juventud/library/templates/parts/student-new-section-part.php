<section id="student_new" class="landing student_new_section section">

    <?php include_once( BCMJ_TEMPLATE_MODALS . '/student-new-profile-picture-modal.php'); ?>

    <div class="student_new_wrapper">

        <div class="section_title_wrap">

            <div class="section_title_box box">
                <h1><span class="title_icon"><i class="fas fa-plus-circle"></i> NUEVO ESTUDIANTE</h1></span>
            </div>           
            
        </div>

        
        <form id="bcmj_new_student_form" class="bcmj_new_student_form form-group" action="" method="post" enctype='multipart/form-data'>

            <div class="form-group d-flex w-100 flex-wrap-reverse">

                <!-- Datos de Personales -->
                <div class="form-group col-md-10 col-12">

                    <div class="form-group col-12">
                        <span class="form-control blue_title">Datos Personales</span>
                        <input id="names" name="names" class="form-control" type="text" placeholder="Nombres">
                    </div>

                    <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                        <div class="form-group col-md-6 col-12 middle_field">
                            <input id="last_name_one" name="last_name_one" class="form-control" type="text" placeholder="Primer Apellido">
                        </div>

                        <div class="form-group col-md-6 col-12 middle_field">
                            <input id="last_name_two" name="last_name_two" class="form-control" type="text" placeholder="Segundo Apellido (opcional)">
                        </div>

                        <div class="form-group col-md-6 col-12 middle_field">                        
                            <select id="gender_id" name="gender_id" class="form-control">
                                <option value="" style="color:#82757d">-- Sexo de Nacimiento --</option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>                            
                            </select>
                        </div>

                        <div class="form-group col-md-6 col-12 middle_field">                        
                            <input id="birth_date" name="birth_date" type="text" placeholder="Fecha de Nacimiento" onfocus="(this.type='date')" onblur="(this.type='text')" min="1960-01-01" class="form-control">                            
                        </div>

                    </div>

                </div>

                <!-- Foto de Perfil  -->
                <div class="form-group col-md-2 col-12">
                    <div class="image_box">
                        <span id="image_picture" class="image_picture no-show"><img src="" alt="Foto del estudiante"></span>
                        <span id="image_icon" class="image_icon"><i class="far fa-grin"></i></span>
                        <div id="spinner_box" class="spinner_box no-show">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>                
                    <div class="temp_photo_wrap d-flex">
                        <label class="student_photo" for="student_photo">
                            <span id="span_student_photo" class="btn btn-primary">Subir Foto</span>
                            <input id="student_photo" name="student_photo" type="File" class="no-show" accept="image/jpeg, image/png">
                        </label>
                        <button id="erase_temp_photo_btn" type="button" class="erase_temp_photo_btn btn btn-danger"><i class="fa fa-times"></i></button>
                    </div>
                </div>

            </div>

            <!-- Datos de Identidad  -->
            <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                <div class="form-group col-12">
                    <span class="form-control blue_title">Datos de Identidad</span>                        
                </div>

                <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                    <div class="form-group col-md-6 col-12 triple_field">
                        <input id="id_code" name="id_code" class="form-control" type="text" placeholder="Código de identidad">
                    </div>                   

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <select id="id_type" name="id_type" class="form-control">
                            <option value="" style="color:#82757d">-- Tipo de ID --</option>
                            <option value="1">Cedula</option>
                            <option value="2">Pasaporte</option>                            
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">
                        <input id="id_issue_entity" name="id_issue_entity" class="form-control" type="text" placeholder="Entidad Emisora">
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <select id="nationality_id" name="nationality_id" class="form-control">
                            <option value="" style="color:#82757d">-- Nacionalidad --</option>                            
                            <option value="65" selected>Republica Dominicana</option>                            
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <input id="issue_date" name="issue_date" type="text" placeholder="Fecha de Expedición" onfocus="(this.type='date')" onblur="(this.type='text')" min="1960-01-01" class="form-control">                            
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <input id="expire_date" name="expire_date" type="text" placeholder="Fecha de Vencimiento" onfocus="(this.type='date')" onblur="(this.type='text')" min="1960-01-01" class="form-control">                            
                    </div>

                </div>
            </div>

            <!-- Datos de Contacto  -->
            <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                <div class="form-group col-12">
                    <span class="form-control blue_title">Datos de Contacto</span>                        
                </div>

                <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <select id="country_of_residency_id" name="country_of_residency_id" class="form-control">
                            <option value="" style="color:#82757d">-- País de Residencia --</option>                                             
                        </select>
                    </div>                   

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <select id="estate_id" name="estate_id" class="form-control">
                            <option value="" style="color:#82757d">-- Estado / Provincia --</option>                            
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">                        
                        <select id="city_id" name="city_id" class="form-control">
                            <option value="" style="color:#82757d">-- Ciudad --</option>                            
                        </select>
                    </div>

                    <div class="form-group col-md-6 col-12 middle_field">                        
                        <input id="address_one" name="address_one" type="text" placeholder="Dirección 1" class="form-control">                            
                    </div>

                    <div class="form-group col-md-6 col-12 middle_field">                        
                        <input id="address_two" name="address_two" type="text" placeholder="Dirección 2 (opcional)" class="form-control">                            
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">
                        <input  id="zip_code" name="zip_code" class="form-control" type="text" placeholder="Código Postal">
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">
                        <input id="movil_phone" name="movil_phone" class="form-control" type="text" placeholder="Teléfono Móvil">
                    </div>

                    <div class="form-group col-md-6 col-12 triple_field">
                        <input id="home_phone" name="home_phone" class="form-control" type="text" placeholder="Teléfono Móvil">
                    </div>

                </div>

                <div class="form-group col-12 d-flex justify-content-end flex-wrap">
                    <button class="btn btn-success btn_student_new_register" type="submit">
                        <span id="submit_text_btn">Registrar</span>
                        <div id="bcmj_spinner_box" class="bcmj_spinner_box no-show">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </button>
                </div>                
            </div>

            <input type="hidden" id="new_student_form_id" name="new_student_form_id" value="<?php echo session_id(); ?>">
        </form>

    </div>    
    <script type="module" src="<?php echo BCMJ_SCRIPTS_DIRECTORY . '/student-new.js';?>"></script>    
</section>
