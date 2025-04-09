<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY ?>/register_visit.style.css" >

<div id="cv_register_visit" class="cv_register_visit_content content">
    
    <?php include_once( CV_TEMPLATE_MODALS . '/print-visitant-name-modal.php' ); ?>

    <!-- REGISTRAR VISITANTES -->
    
    <h3>REGISTRAR VISITANTES</h3>

    <div id="cv_register_wrapper" class="cv_register_wrapper form-control">

        <div id="search_container" class="search_container">
            <div class="queries_box">
                <div class="queries_inputs" style="border: 1px solid lightgrey">
                    <input  type="text" id="reg_search_visit_input" name="reg_search_visit_input" value="" placeholder="Buscar Visitante" maxlength="100"/>
                    <button type="button" id="reg_search_visitant_btn" name="reg_search_visitant_btn" class="btn_disabled" disabled><i class="fa fa-search"></i></button>
                </div>
                <div class="queries_filters">
                    <span>Buscar por: </span>
                    <label for="reg_filter_id">
                        <input id="reg_filter_id" name="reg_filter_option" value="1" type="radio"/>
                        <span>Cédula</span>
                    </label>
                    <label for="reg_filter_passport">
                        <input id="reg_filter_passport" name="reg_filter_option" value="2" type="radio"/>
                        <span>Pasaporte</span>
                    </label>
                    <label for="reg_filter_other">
                        <input id="reg_filter_other" name="reg_filter_option" value="3" type="radio"/>
                        <span>Otro</span>
                    </label>                    
                </div>
            </div>
            <div class="result_box">
                <label for="reg_input_result"><span>Resultado:</span>
                    <b><input type="text" class="reg_input_result" id="reg_input_result" name="reg_input_result" placeholder="Realice una búsqueda" maxlength="100" disabled/></b>
                </label>
            </div>
        </div>

        <hr>
        
        <!-- Información del Visitante -->

        <h5>Información del Visitante</h5>

        <div id="visitant_details_container" class="visitant_details_container">

            <div class="visitit_wrapper form-group">
                <div class="justify-content-between d-flex form-group">
                    <div class="inputs_box" style="width:100%;max-width:calc(50% - 5px)">
                        <label for="">Nombres</label>
                        <input type="text" id="reg_name" name="reg_name" value="" class="form-control" maxlength="100"/>
                    </div>
                    <div class="inputs_box" style="width:100%;max-width:calc(50% - 5px)">
                        <label for="">Apellidos</label>
                        <input type="text" id="reg_last_name" name="reg_last_name" value="" class="form-control" maxlength="100"/>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group" style="width:100%;max-width:calc(30% - 5px)">
                        <div style="width:100%;max-width:calc(100% - 5px)">
                            <label for="">Sexo</label>
                            <select name="reg_genders" id="reg_genders" class="form-control">
                                <option value="">Elija una opción</option>                                
                            </select>
                        </div>
                        <div style="width:100%;max-width:calc(100% - 5px)">
                            <label for="">Fecha de Nacimiento</label>
                            <input type="date" id="reg_birth_date" name="reg_birth_date" class="form-control" maxlength="100" value="2023-03-20" disabled/>
                        </div>
                    </div>
                    <div class="form-group" style="width:100%;max-width:calc(70% - 5px)">
                        <div class="d-flex justify-content-between">
                            <div style="width:100%;max-width:calc(50% - 5px)">
                                <label for="">Código de Identidad</label>
                                <input type="text" id="reg_identification_id" name="reg_identification_id" class="form-control" maxlength="100" disabled/>
                            </div>
                            <div style="width:100%;max-width:calc(50% - 5px)">
                                <label for="">Tipo de Documento</label>
                                <select name="reg_identification_type" id="reg_identification_type" class="form-control" disabled>                                    
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="">Ultima Visita</label>
                            <input type="text" id="reg_last_visit_date" name="reg_last_visit_date" class="form-control" maxlength="100" disabled/>
                        </div>
                        
                        <input type="hidden" id="reg_week_day_id" name="reg_week_day_id" value="">

                        <input type="hidden" id="reg_visitant_id" name="reg_visitant_id" value="">

                        <input type="hidden" id="reg_visit_state" name="reg_visit_state" value="1">

                        <input type="hidden" id="reg_form_id" name="reg_form_id" value="<?php echo session_id(); ?>">

                    </div>
                </div>
            </div>

            <style>
                .visitant_details_container{
                    display: flex;
                    justify-content: space-between;
                }
                .visitit_wrapper.form-group {
                    width: 100%;
                    padding: 0 5px;
                }
                .photo_pic_box {
                    position: relative;
                    width: 120px;
                    height: 140px;
                    padding: 5px;
                    border: 1px solid #989090;
                }
                .photo_pic_box > p {
                    position: absolute;
                    top: -13px;
                    background-color: white;
                    padding: 0 5px;
                }
                .photo_wrapper {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                }
                .photo_wrapper span.user_icon {
                    font-size: 81px;
                    text-align: center;
                    margin: auto;
                    display: block;
                    color: #989090;
                }
                .photo_wrapper .photo_button_box {
                    background-color: dodgerblue;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                    /* padding: 5px; */
                }
                .photo_wrapper .photo_button_box:hover {
                    background-color: blue;
                    cursor: pointer;
                }
                .photo_wrapper .photo_button_box:hover label:hover{
                    background-color: blue;
                    cursor: pointer;
                }
                .photo_wrapper .photo_button_box .photo_text_box{
                    margin: auto;
                }
                .photo_wrapper .photo_button_box button {
                    border: unset;
                    background-color: #e03b3b;
                    padding: 5px;
                    color: white;
                    outline: unset;
                }
                .photo_wrapper .photo_button_box button:hover {                    
                    background-color: red;
                }
                .photo_wrapper .photo_pic_box > img {
                    width: 100%;
                    height: 100%;
                }
            </style>
            
            <!-- Foto del Visitante -->
                
            <div class="photo_wrapper form-group">            

                <div class="reg_photo_container form-group">

                    <?php include( CV_LIBRARY_COMPONENTS . "/PhotoBox/photo-box.php"); ?>

                    <?php include( CV_LIBRARY_COMPONENTS . "/InputFileCropper/input-file-cropper.php"); ?>

                </div>
           
            </div>

        </div>

        <hr>
        
        <!-- Informacion de la Visita -->

        <h5>Informacion de la Visita</h5>

        <div id="visit_details_container" class="visit_details_container">
            
            <div class="form-group group_one d-flex justify-content-between">
                
                <div class="contact_box form-group">
                    <label for="reg_business_contact">Contacto en la Empresa</label>
                    <input type="text" name="raw_coworker_full_name" id="raw_coworker_full_name" class="form-control">
                    <select name="reg_business_contact" id="reg_business_contact" class="form-control" style="display:none;" disabled>
                        <option value=""> -- Seleccione un Colaborador -- </option>
                        <option value="1" selected>COLABORADOR MANUAL</option>
                        <?php // ver -> get_reg_coworkers_list() ?>
                    </select>
                </div>
                
                <div class="dpto_box form-group">
                    <label for="contact_dpto">Departamento donde labora el contacto</label>
                    <input type="hidden" id="contact_dpto" name="contact_dpto" class="form-control" maxlength="100" style="display:};" disabled>                    
                    <select data-placeholder=" -- Seleccione el Departamento -- " name="contact_dpto_from_select" id="contact_dpto_from_select" class="form-control" >
                        <!-- <option value=""> -- Seleccione el Departamento -- </option> -->
                    </select>
                </div>
                
                <div class="access_box form-group">
                    <label for="reg_access_level">Nivel de Acceso</label>
                    <select name="reg_access_level" id="reg_access_level" class="form-control">
                        <option value=""> -- Seleccione el nivel -- </option>
                    </select>
                </div>

                <div class="gun_box form-group">
                    <label for="reg_has_gun">Visitante Porta Arma de Fuego</label>
                    <select name="reg_has_gun" id="reg_has_gun" class="form-control">
                        <option key="1" value="1">SI</option>
                        <option key="0" value="0" selected>NO</option>
                    </select>
                </div>

            </div>

            <div class="form-group group_two d-flex justify-content-between">

                <div class="form-group">
                    <label for="reg_visit_concert">Motivo de la Visita</label>
                    <select name="reg_visit_concert" id="reg_visit_concert" class="form-control">
                        <option key="" value=""> -- Seleccione el motivo -- </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="reg_cat_gun">Categoria Licencia de Arma</label>
                    <select name="reg_cat_gun" id="reg_cat_gun" class="form-control" disabled>
                        <option key="" value="">-</option>                        
                    </select>
                </div>

                <div class="form-group">
                    <label for="reg_gun_code">Código de Licencia</label>
                    <input type="text" id="reg_gun_code" name="reg_gun_code" class="form-control" maxlength="100" disabled>
                </div>

                <div class="form-group">
                    <label for="reg_gun_status">Estado del Arma</label>
                    <select name="reg_gun_status" id="reg_gun_status" class="form-control" disabled>                        
                    </select>
                </div>

            </div>

            <div class="form-group group_three d-flex justify-content-between">

                <div class="textarea_box form-group">
                    <label for="visit_observations">Observaciones al Iniciar Visita</label>
                    <textarea class="form-control" name="visit_observations" id="visit_observations" cols="30" rows="10" maxlength="300"></textarea>
                </div>

                <div title="Realice una busqueda para verificar visitante" class="submit_box form-group">             
                    <?php // data-bs-target="#vt_name_modal_wrapper" data-bs-toggle="modal"       ?>
                    <button type="button" id="reg_btn_success" name="reg_btn_success" class="btn btn-success" disabled>Registrar Visita</button>
                </div>

            </div>
            
        </div>

    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY; ?>/register-visit.js"></script>

<script src="<?php echo CV_SCRIPTS_DIRECTORY; ?>/zebra-printer.js"></script>