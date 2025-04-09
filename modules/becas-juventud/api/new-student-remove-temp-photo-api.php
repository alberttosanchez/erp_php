<?php // target : remove_temp_new_student_photo

    $temp_image_key = isset($jsonObject->temp_image_key) ? $jsonObject->temp_image_key : "";

    // ./../../../public/temp
    $path = BCMJ_PUBLIC_TEMP ."/temp_new_student_photo_";
           
    $image_path = $path;
    
    // eliminar el archivo temporal del perfil del nuevo estudiante 
    if( file_exists($image_path.$temp_image_key.".png") )
    {
        $result = unlink($image_path.$temp_image_key.".png");
    }
    else if ( file_exists($image_path.$temp_image_key.".jpg") )
    {
        $result = unlink($image_path.$temp_image_key.".jpg");
    }

    