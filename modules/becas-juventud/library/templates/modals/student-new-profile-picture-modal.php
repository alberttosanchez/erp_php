<div id="student_new_profile_picture_modal" class="student_new_profile_picture_modal_wrapper">

    <?php // este modal depende de la libreria de crooper y bootstrap 5 ?>

    <div class="modal fade" id="div-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Redimencionar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- en este img se visualizará todo el archivo seleccionado-->
                                <img id="img-original" class="img-fluid">
                            </div>
                            <div class="col-md-4">
                                <!-- en este div se mostrará la zona seleccionada, lo que quedará despues de hacer click en el boton crop-->
                                <div id="div-preview" class="preview img-fluid"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-crop" data-bs-dismiss="modal">Recortar</button>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }
        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid #0B5ED7;
        }
    </style>
</div>