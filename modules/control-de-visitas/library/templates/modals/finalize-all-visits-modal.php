
<!-- Modal -->

<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY . "/finalize-all-visits-modal.css";?>">

<div class="co_fnall_modal_wrapper modal fade" id="co_fnall_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="co_fnall_modalLabel" aria-hidden="true">
    <div class="co_fnall_modal_group_wrapper modal-dialog">
        <div class="co_fnall_modal_content_box modal-content">

            <div class="co_fnall_modal_title_bar_box form-group">
                <p><span class="co_title_bar">ADVERTENCIA - MENSAJE DE FINALIZACION DE VISITAS</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="co_fnall_modal_body_wrapper co_body_wrapper form-group">

                <div class="co_fnall_modal_one_box box form-group">
                    <p>Esta a punto de Finalizar <b>todas las visitas activas.</b><br>Esta acción no podrá deshacerse.</span></p>
                </div>

                <input type="hidden" id="co_fnall_single_id" name="co_fnall_single_id" value="">

                <div class="co_fnall_modal_two_box box form-group">

                    <button type="button" id="co_fnall_modal_cancel_btn" name="co_fnall_modal_cancel_btn" data-bs-dismiss="modal" class="btn btn-secondary">CANCELAR</button>
                    <button type="button" id="co_fnall_modal_confirm_btn" name="co_fnall_modal_confirm_btn" data-bs-dismiss="modal" class="btn btn-danger">CONFIRMAR</button>

                </div>
                
                <input type="hidden" id="co_fnall_form_id" name="co_fnall_form_id" value="<?php echo session_id(); ?>">
            </div>
            
        </div>
    </div>
</div>