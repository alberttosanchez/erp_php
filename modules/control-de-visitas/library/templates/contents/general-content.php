<link rel="stylesheet" href="<?php echo CV_ASSETS_DIRECTORY;?>/css/general.style.css">

<div id="cv_general" class="cv_general_content content">

    <h3>Información de la Institución</h3>

    <div class="gral_group_wrapper gral_wrapper form-group">

        <div class="gral_info_wrapper gral_wrapper form-group">

            <div class="gral_info_box_one_group gral_box_group form-group">
                
                
                <div class="gral_info_name_box gral_box form-group">
                    <label for="gral_info_name">Nombre/Razón Social</label>
                    <input type="text" id="gral_info_name" name="gral_info_name" class="gral_info_name form-control" maxlength="100"/>
                </div>
                
                <div class="gral_info_phone_box gral_box form-group">
                    <label for="gral_info_phone">Teléfono</label>
                    <input type="text" id="gral_info_phone" name="gral_info_phone" class="gral_info_phone form-control" maxlength="15"/>
                </div>
                
                <input type="hidden" id="gral_info_id" value="" name="gral_info_id" class="gral_info_id form-control" maxlength="100"/>

            </div>

            <div class="gral_info_box_two_group gral_box_group form-group">

                <div class="gral_info_address_box gral_box form-group">
                    <label for="gral_info_address">Dirección</label>
                    <input type="text" id="gral_info_address" name="gral_info_address" class="gral_info_address form-control" maxlength="100"/>
                </div>

            </div>

            <div class="gral_info_box_three_group gral_box_group form-group">

                <div class="gral_info_postal_code_box gral_box form-group">
                    <label for="gral_info_postal_code">Código Postal</label>
                    <input type="text" id="gral_info_postal_code" name="gral_info_postal_code" class="gral_info_postal_code form-control" maxlength="100"/>
                </div>

                <div class="gral_info_floor_amount_box gral_box form-group">
                    <label for="gral_info_floor_amount">Cantidad de Pisos</label>                
                    <input type="text" id="gral_info_floor_amount" name="gral_info_floor_amount" class="gral_info_floor_amount form-control" maxlength="100"/>
                </div>

                <div class="gral_info_save_btn_box gral_box form-group">                
                    <button type="button" id="gral_info_save_btn" name="gral_info_save_btn" class="gral_info_save_btn btn btn-primary">GUARDAR</button>
                </div>

            </div>

        </div>

        <input type="hidden" name="gral_form_id" value="<?php echo session_id(); ?>">
    </div>

    <hr>

    <h3>Opciones de Impresión</h3>

    <div class="gral_group_wrapper gral_wrapper form-group">

        <div class="gral_info_wrapper gral_wrapper form-group">

            <div class="gral_info_box_one_group gral_box_group form-group">                
                
                <div class="form-group gral_check_element_box">                
                    <label for="permitir_impresion">
                        <span class="switch_btn"></span>
                        <input type="checkbox" id="permitir_impresion" name="permitir_impresion" class="no-show" value="">
                    </label>                
                    <span class="">Permitir Impresión</span>
                </div>

            </div>

        </div>

        <input type="hidden" id="gral_form_id" name="gral_form_id" value="<?php echo session_id(); ?>">
        <input type="hidden" id="gral_setting_id" name="gral_setting_id" value="">
    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/general.js"></script>