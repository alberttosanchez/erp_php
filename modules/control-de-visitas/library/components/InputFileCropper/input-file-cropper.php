<?php
    /*
    * COMPONENTE PHP - INPUT FILE CROPPER
    * Este componente contiene todos el codigo necesario para cargar la libreria crooper, 
    * necesita tener bootstrap 5 cargado en el ambiente.
    **/
?>

<link rel="stylesheet" href="<?php echo "../." . CV_LIBRARY_COMPONENTS . "/InputFileCropper/input-file-cropper.css"; ?>">

<div class="label_file_cropper_container">
    <label class="label_file_cropper_button" id="label_file_cropper"  for="input_file_cropper">
        <span class="text">CAMBIAR</span>
        <input type="file" name="input_file_cropper" id="input_file_cropper" class="no-show" accept=".png, .jpg, .jpeg">
    </label>
    <button id="erase_temp_photo_btn" type="button" class="close-icon"><i class="fas fa-times"></i></span>
</div>

<?php include_once( CV_LIBRARY_COMPONENTS . "/InputFileCropper/input-file-cropper-modal.php" ); ?>

<script src="<?php echo "../." . CV_LIBRARY_COMPONENTS . "/InputFileCropper/input-file-cropper.js"; ?>"></script>