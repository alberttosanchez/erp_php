<div id="input_file_cropper_modal" class="input_file_cropper_modal_wrapper">

    <?php // este modal depende de la libreria de crooper y bootstrap 5 ?>

    <div class="modal fade" id="div-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Redimencionar Foto de Perfil</h5>
                    <button onclick="closeModal()" type="button" class="btn-close" <?php //data-bs-dismiss="modal" ?> aria-label="Close"></button>
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
                    <button onclick="closeModal()" type="button" class="btn btn-secondary">Cerrar</button>
                    <button onclick="closeModal()" type="button" class="btn btn-primary" id="btn-crop" >Recortar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const closeModal = () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('div-modal'));
                modal.hide();
        }
    </script>
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