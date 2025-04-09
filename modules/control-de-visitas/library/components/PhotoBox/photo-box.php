<?php
    /*
    * COMPONENTE PHP - PHOTO BOX
    * Este componente contiene todos el codigo necesario para cargar una foto, 
    * crear una b64, temporal y luego enviar la data al servidor.
    **/
?>

<link rel="stylesheet" href="<?php echo "../." . CV_LIBRARY_COMPONENTS . "/PhotoBox/photo-box.css"; ?>">

<div class="photo_box photo_pic_box">
    <p>Foto</p>
    <div class="photo_container">
        
        <?php // echo ASSETS_DIRECTORY . "/images/ministro.jpg"; ?>

        <img id="photo_img_picture" src="" alt="Foto" class="no-show" title="Foto">
        <span id="image_icon" class="user_icon"><i class="fa fa-user"></i></span>
    </div>
</div>

<script src="<?php echo "../." . CV_LIBRARY_COMPONENTS . "/PhotoBox/photo-box.js"; ?>"></script>