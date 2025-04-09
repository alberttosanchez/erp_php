
<!-- Modal -->

<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY . "/new-coworker-modal.css";?>">

<div class="co_modal_wrapper modal fade" id="new_coworker_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="new_coworker_modalLabel" aria-hidden="true">
    <div class="co_modal_group_wrapper modal-dialog">
        <div class="co_modal_content_box modal-content">

            <div class="co_modal_title_bar_box form-group">
                <p><span class="co_title_bar">AGREGAR NUEVO COLABORADOR</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div id="co_new_modal_form" class="co_modal_body_wrapper co_body_wrapper form-group">

                <h4>Datos Personales</h4>

                <div class="co_modal_one_wrapper co_wrapper form-group d-flex">

                    <div class="co_modal_one_box_group co_box_group form-group co_modal_photo_wrapper">

                        <div class="co_photo_container form-group">
        
                            <?php include( CV_LIBRARY_COMPONENTS . "/PhotoBox/photo-box.php"); ?>

                            <?php include( CV_LIBRARY_COMPONENTS . "/InputFileCropper/input-file-cropper.php"); ?>

                        </div>

                    </div>

                    <div class="form-group w-100">

                        <div class="co_modal_one_box_group co_box_group form-group">
        
                            <div class="co_modal_name_box box form-group">
                                <label for="co_modal_name">Nombres</label>
                                <input type="text" id="co_modal_name" name="co_modal_name" class="form-control" maxlength="100" required/>
                            </div>
            
                            <div class="co_modal_last_name_box box form-group">
                                <label for="co_modal_last_name">Apellidos</label>
                                <input type="text" id="co_modal_last_name" name="co_modal_last_name" class="form-control" maxlength="100" required/>
                            </div>
        
                        </div>
        
                        <div class="co_modal_two_box_group co_box_group form-group">
        
                            <div class="co_modal_gender_box box form-group">
                                <label for="co_modal_gender">Sexo</label>
                                <select name="co_modal_gender" id="co_modal_gender" class="co_modal_gender form-control" required>
                                    <option value="">-</option>                                
                                </select>
                            </div>
        
                            <div class="co_modal_id_box box form-group">
                                <label for="co_modal_identification_id">Código de Identidad</label>
                                <input type="text" id="co_modal_identification_id" name="co_modal_identification_id" class="co_modal_identification_id form-control" maxlength="100" required/>
                            </div>
        
                            <div class="co_modal_id_type_box box form-group">
                                <label for="co_modal_type_id">Tipo de Identidad</label>                            
                                <select name="co_modal_type_id" id="co_modal_type_id" class="co_modal_type_id form-control" required>
                                    <option value="">-</option>                                
                                </select>
                            </div>
        
                            <div class="co_modal_birth_date_box box form-group">
                                <label for="co_modal_birth_date">Fecha de Nacimiento</label>
                                <input type="date" id="co_modal_birth_date" name="co_modal_birth_date" class="co_modal_birth_date form-control" maxlength="100" required/>
                            </div>
        
                            <input type="hidden" id="co_modal_row_id" name="co_modal_row_id" value=""/>
    
                        </div>

                        <br>
        
                        <h4>Información Laboral</h4>
        
                        <div class="co_modal_two_wrapper co_wrapper form-group">
        
                            <div class="co_modal_three_box_group co_box_group form-group">
                                
                                <div class="co_modal_dpto_box box form-group">
                                    <label for="co_modal_dpto">Departamento donde Labora</label>
                                    <select id="co_modal_dpto" name="co_modal_dpto" class="co_modal_dpto form-control" required>
                                        <option value="">-</option>                                <
                                    </select>
                                </div>
            
                                <div class="co_modal_job_title_box box form-group">
                                    <label for="co_modal_job_title">Cargo</label>
                                    <input type="text" id="co_modal_job_title" name="co_modal_job_title" class="co_modal_job_title form-control" maxlength="100" required/>
                                </div>
            
                                <div class="co_modal_phone_ext_box box form-group">
                                    <label for="co_modal_phone_ext">Extensión Telefónica</label>
                                    <input type="text" id="co_modal_phone_ext" name="co_modal_phone_ext" class="co_modal_phone_ext form-control" maxlength="100" />
                                </div>
        
                            </div>
                            
                            <input type="hidden" id="co_modal_id" name="co_modal_id" value="">                            
                            <input type="hidden" id="co_modal_form_id" name="co_modal_form_id" value="<?php echo session_id(); ?>">

                            <div class="co_modal_four_box_group co_box_group form-group">
        
                                <div class="co_modal_email_box box form-group">
                                    <label for="co_modal_email">Correo Institucional</label>
                                    <input type="email" id="co_modal_email" name="co_modal_email" class="co_modal_email form-control" maxlength="100" required/>
                                </div>
        
                                <div class="co_modal_register_btn_box box form-group">                            
                                    <buton type="button" id="co_modal_register_btn" form="co_new_modal_form" name="co_modal_register_btn" class="co_modal_register_btn btn btn-success">REGISTRAR</buton>
                                </div>
        
                            </div>
        
                        </div>
                        
                    </div>

                </div>


            </div>
            
        </div>
    </div>
</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/new-coworker-modal.js"></script>
<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/edit-coworker-modal.js"></script>