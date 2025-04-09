
<!-- Modal -->

<link rel="stylesheet" href="<?php echo CV_STYLES_DIRECTORY . "/delete-coworker-modal.css";?>">

<div class="co_del_coworker_modal_wrapper modal fade" id="co_del_coworker_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="co_del_coworker_modalLabel" aria-hidden="true">
    <div class="co_del_modal_group_wrapper modal-dialog">
        <div class="co_del_modal_content_box modal-content">

            <div class="co_del_modal_title_bar_box form-group">
                <p><span class="co_title_bar">ADVERTENCIA - MENSAJE DE ELIMINACION DE COLABORADOR</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="co_del_modal_body_wrapper co_body_wrapper form-group">

                <div class="co_del_modal_one_box box form-group">
                    <p>Esta a punto de Eliminar el/la colaborador/a cuyo ID es <span class="co_del_show_id"></span></p>
                </div>

                <input type="hidden" id="co_del_single_id" name="co_del_single_id" value="">

                <div class="co_del_modal_two_box box form-group">

                    <button type="button" id="co_del_modal_cancel_btn" name="co_del_modal_cancel_btn" data-bs-dismiss="modal" class="btn btn-secondary">CANCELAR</button>
                    <button type="button" id="co_del_modal_confirm_btn" name="co_del_modal_confirm_btn" data-bs-dismiss="modal" class="btn btn-danger">CONFIRMAR</button>

                </div>
                
                <input type="hidden" id="co_del_form_id" name="co_del_form_id" value="<?php echo session_id(); ?>">
            </div>
            
        </div>
    </div>
</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/delete-coworker-modal.js"></script>