
<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY . "/finalize-visit-content.css";?>">

<div id="cv_finalize_visit" class="cv_finalize_visit_content content">

    <?php include_once( CV_TEMPLATE_MODALS . '/finalize-all-visits-modal.php'); ?>

    <div class="cv_finalize_visit_search form-group d-flex justify-content-between">

        <div class="form-group">
            <h3>FINALIZAR VISITA</h3>
        </div>

        <div>
            <button type="button" id="fn_vt_all_visits_btn" name="fn_vt_all_visits_btn" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#co_fnall_modal_wrapper">FINALIZAR TODAS LAS VISITAS</button>
        </div>
        <div class="fn_visit_search_box queries_box form-group">
            <div class="queries_inputs">
                <button type="button" id="fn_vt_clean_btn" class="fn_vt_clean_btn" name="fn_vt_clean_btn" title="Limpiar consulta"  style="background-color:red" ><i class="fas fa-eraser"></i></button>
                <input type="text" id="fn_vt_input_search" class="fn_vt_input_search" name="fn_vt_input_search" placeholder="Buscar Visitante" maxlength="100"/>
                <button type="button" id="fn_visit_search_btn" class="fn_visit_search_btn" title="Buscar"><i class="fa fa-search"></i></button>
            </div>
            <div class="queries_filters">
                <span>Buscar por: </span>
                <label for="fn_filter_id">
                    <input id="fn_filter_id" name="fn_filter_option" type="radio" value="1" checked />
                    <span>Cédula</span>
                </label>
                <label for="fn_filter_passport">
                    <input id="fn_filter_passport" name="fn_filter_option" type="radio" value="2" />
                    <span>Pasaporte</span>
                </label>
                <label for="fn_filter_other">
                    <input id="fn_filter_other" name="fn_filter_option" type="radio" value="3" />
                    <span>Otro</span>
                </label>                    
            </div>
        </div>

    </div>

    <div id="cv_finalize_visit_wrapper" class="cv_finalize_visit_wrapper">
        
        <table class="table table-responsible table-striped table-hover">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Visitante</th>
                    <th>Cédula / Pasaporte</th>
                    <th>Ubicación</th>
                    <th>Contacto</th>
                    <th>Hora Llegada</th>                    
                </tr>
            </thead>
            <tbody>
                <?php /*    
                    <!-- <tr key="63">
                        <td>
                            <input onclick="handleSelectedRow(this)" type="check" id="check_63" checked="false"/>
                        <td/>
                        <td>Román Perez</td>
                        <td>001-0014521-1</td>
                        <td>DTIC - 1er Piso</td>
                        <td>Keifre Figuereo</td>
                        <td>12:12:25 pm</td>                    
                    </tr>
                    <tr key="62">
                        <td>Pedro Gómez</td>
                        <td>003-2004521-1</td>
                        <td>Despacho - 3er Piso</td>
                        <td>Secretaria</td>
                        <td>11:10:50 am</td>                    
                    </tr>  -->               
                */ ?>
            </tbody>
        </table>        
    </div>

    <hr>

    <h4>Información del Visitante</h4>

    <div id="cv_finalize_visit_info_wrapper" class="cv_finalize_visit_info_wrapper">
        <style>
            .cv_finalize_visit_info_wrapper label {
                font-weight: bold;
                font-size: 12px;
            }
            @media screen and (max-width: 769px){

                .cv_finalize_visit_info_wrapper .form-group{
                    width: 100% !important;    
                    max-width: 100% !important;
                    flex-wrap: wrap;
                }

            }
        </style>
        <div class="box_one form-group d-flex justify-content-between">

            <div class="form-group" style="width:calc(50% - 5px)">
                <label for="fn_name">Nombres</label>
                <input type="text" id="fn_name" name="fn_name" class="form-control" maxlength="100" disabled/>
            </div>
    
            <div class="form-group" style="width:calc(50% - 5px)">
                <label for="fn_last_name">Apellidos</label>
                <input type="text" id="fn_last_name" name="fn_last_name" class="form-control" maxlength="100" disabled/>
            </div>

        </div>

        <div class="box_two form-group d-flex justify-content-between">

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_gender">Sexo</label>
                <input type="text" name="fn_gender" id="fn_gender" class="form-control" maxlength="100" disabled/>                    
            </div>

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_id_code">Código de Identidad</label>
                <input type="text" id="fn_id_code" name="fn_id_code" class="form-control" maxlength="100" disabled/>
            </div>

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_id_type">Tipo de Documento</label>
                <input type="text" name="fn_id_type" id="fn_id_type" class="form-control" maxlength="100" disabled/>
            </div>

        </div>

        <div class="box_three form-group d-flex justify-content-between">

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_birth_date">fecha de Nacimiento</label>
                <input type="date" id="fn_birth_date" name="fn_birth_date" class="form-control" maxlength="100" disabled/>
            </div>

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_gun">Posee Arma de Fuego</label>
                <input type="text" name="fn_gun" id="fn_gun" class="form-control" maxlength="100" value="" disabled/>
            </div>

            <div class="form-group" style="width:calc(33.33% - 5px)">
                <label for="fn_gun_status">Indicar Estado Arma de Fuego</label>
                <select name="fn_gun_status" id="fn_gun_status" class="form-control" maxlength="100" disabled>
                    <option value="1" selected>No Aplica</option>
                    <option value="2">Retenida por Seguridad</option>
                    <option value="3">Entregada al Visitante</option>
                    <option value="4">Otros</option>
                </select>
            </div>

        </div>

        <div class="box_four form-group d-flex justify-content-between">

            <div class="form-group" style="width:calc(80% - 5px);">
                <label for="fn_observations">Observaciones al Finalizar</label>
                <textarea name="fn_observations" id="fn_observations" cols="30" rows="10" class="form-control" style="max-height:150px;min-height:100px" maxlength="200"></textarea>
            </div>

            <input type="hidden" id="fn_visit_info_id" name="fn_visit_info_id" value="">
            <input type="hidden" id="fn_form_id" name="fn_form_id" value="<?php echo session_id();?>">

            <div class="form-group" 
                style="
                    width: calc(20% - 5px);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height:60px;
                ">
                <button type="button" id="fn_vt_btn" class="btn btn-danger" disabled><b>FINALIZAR VISITA</b></button>
            </div>
            
        </div>

    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/finalize-visit-content.js"></script>