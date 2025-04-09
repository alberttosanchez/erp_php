<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY . "/coworkers.css";?>">

<div id="cv_coworkers" class="cv_coworkers_content content">

    <div class="co_title_wrapper form-group">

        <h3>Administrar Colaboradores</h3>
    
        <div class="co_new_coworker_box form-group">
            <!-- Button trigger modal -->
            <button type="button" id="new_coworker_btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new_coworker_modal_wrapper">NUEVO COLABORADOR</button>
        </div>

        <?php include_once( CV_TEMPLATE_MODALS . '/new-coworker-modal.php'); ?>

        <?php // include_once( CV_TEMPLATE_MODALS . '/edit-coworker-modal.php'); ?>

        <?php include_once( CV_TEMPLATE_MODALS . '/delete-coworker-modal.php'); ?>

    </div>

    <div class="co_wrapper form-group">

        <div class="co_filter_wrapper form-group">

            <div class="co_search_box form-group">
                <label for="co_filter_select">Filtrar Búsqueda</label>
                <select id="co_filter_select" name="co_filter_select" class="form-control">
                    <option value="">Seleccionar Filtro</option>
                    <option value="1">ID</option>
                    <option value="2">Código de Identidad</option>
                    <option value="3">Nombres</option>
                    <option value="4">Apellidos</option>
                    <option value="5">Sexo</option>
                    <option value="6">Fecha de Nacimiento</option>
                </select>
            </div>

            <div class="co_keyword_wrapper form-group">
                <div class="co_keyword_input_box">
                    <input type="text" name="co_keyword_input" id="co_keyword_input" class="co_keyword_input form-control">
                    <button type="button" id="co_keywork_btn" class="btn-primary co_keywork_btn"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </div>

        <br>

        <div class="co_table_parent_wrapper">            
            <div class="co_table_wrapper">
            
                <table id="co_table" class="co_table table table-hover table-stripped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código de Identidad</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Sexo</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr key="3">
                            <td>1</td>
                            <td>003-4512875-1</td>
                            <td>Keifree Jose</td>
                            <td>Figuereo Mata</td>
                            <td>Hombre</td>
                            <td>14-09-2000</td>
                            <td>
                                <button title="Ver" type="button" value="3"><i class="fa fa-eye"></i></button> -->
                                <!-- Edit Button trigger modal -->
                                <!-- <button  title="Editar" type="button" value="3" data-bs-toggle="modal" data-bs-target="#edit_coworker_modal_wrapper"><i class="fa fa-edit"></i></button> -->
                                <!-- Delete Button trigger modal -->
                                <!-- <button title="Eliminar" type="button" value="3" data-bs-toggle="modal" data-bs-target="#del_coworker_modal_wrapper"><i class="fa fa-times"></i></button>
                            </td>
                        </tr> -->                    
                    </tbody>
                </table>    
            </div>
        </div>

        <br>

        <div class="co_info_group_wrapper">
            
            <div class="co_info_wrapper form-group">

                <h4>Información del/la Colaborador(a)</h4>

                <div class="co_info_one_wrapper form-group">
    
                    <div class="co_info_name_box form-group">
                        <label for="co_info_name">Nombres</label>
                        <input type="text" id="co_info_name" name="co_info_name" class="form-control" maxlenght="100" disabled/>
                    </div>
    
                    <div class="co_info_last_name_box form-group">
                        <label for="co_info_last_name">Apellidos</label>
                        <input type="text" id="co_info_last_name" name="co_info_last_name" class="form-control" maxlenght="100" disabled/>
                    </div>
    
                </div> 
                
                <div class="co_info_two_wrapper form-group">
    
                    <div class="co_info_gender_box form-group">
                        <label for="co_info_gender">Sexo</label>
                        <select name="co_info_gender" id="co_info_gender" class="form-control"disabled>
                            <option value="">-</option>
                            <option value="">Hombre</option>
                            <option value="">Mujer</option>
                            <option value="">Otro</option>
                        </select>
                    </div>
    
                    <div class="co_info_ident_box form-group">
                        <label for="co_info_ident">Código de Identidad</label>
                        <input type="text" id="co_info_ident" name="co_info_ident" class="form-control" maxlenght="100" disabled/>
                    </div>
    
                    <div class="co_info_id_type_box form-group">
                        <label for="co_info_id_type">Tipo de Identidad</label>
                        <select name="co_info_id_type" id="co_info_id_type" class="form-control"disabled>
                            <option value="">-</option>
                            <option value="">Cédula</option>
                            <option value="">Pasaporte</option>
                            <option value="">Otro</option>
                        </select>
                    </div>
    
                </div> 
    
                <div class="co_info_three_wrapper form-group">
    
                    <div class="co_info_birth_date_box form-group">
                        <label for="co_info_birth_date">Fecha de Nacimiento</label>
                        <input type="date" id="co_info_birth_date" name="co_info_birth_date" class="form-control" maxlenght="100" disabled/>
                    </div>
    
                    <div class="co_info_dpto_box form-group">
                        <label for="co_info_dpto">Departamento</label>
                        <input type="text" id="co_info_dpto" name="co_info_dpto" class="form-control" maxlenght="100" disabled/>
                    </div>
    
                    <input type="hidden" id="co_info_id" name="co_info_id" value=""/>
                    <input type="hidden" id="co_form_id" name="co_form_id" value="<?php echo session_id();?>"/>

                </div> 
                
            </div>

            <div class="co_photo_wrapper form-group">
    
                <?php include( CV_LIBRARY_COMPONENTS . "/PhotoBox/photo-box.php"); ?>
    
            </div>

        </div>


    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/coworkers.js"></script>