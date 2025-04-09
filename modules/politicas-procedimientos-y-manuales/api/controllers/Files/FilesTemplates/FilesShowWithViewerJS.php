<?php

$arch_api_url = URL_BASE . "/modules/" . get_module_name(2) . "/api/index.php";

// Función para verificar las credenciales de usuario
function verificarCredenciales($user, $pass) {
    
    // Aquí puedes realizar la validación de credenciales, como verificarlas en una base de datos o en un archivo .htpasswd
    
    // htPassWD -> config.php del sigpromj
    $validUSer = HTPASSWD;
    
    // Verificar si el usuario y la contraseña coinciden
    return isset($validUSer['user']) && $validUSer['user'] === $user && $validUSer['pass'] === $pass;
}

// Verificar si las credenciales fueron enviadas
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    // Si no se han enviado, enviar el encabezado de autenticación
    header('WWW-Authenticate: Basic realm="Área restringida"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Autenticación requerida.';
    exit;
} else {
    // Si las credenciales fueron enviadas, verificarlas
    if (verificarCredenciales($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
        // Si las credenciales son válidas, permitir el acceso        
    } else {
        // Si las credenciales no son válidas, mostrar un mensaje de error
        header('HTTP/1.0 401 Unauthorized');
        echo 'Credenciales incorrectas.';
        exit;
    }
}

// Procedemos a copiar el archivo que se va a mostrar en un directorio temporal y le cambiamos el nombre


    // ruta directorio que contiene el documento
    $file_path = $full_file_path;

    // ruta direcotrio temporal
    $temp_path = ARCH_PUBLIC_TEMP;

    // instanciamos la clase Files
    $Files = new Library\Classes\Files;
    
    // recibe la ruta a crear y el modo de acceso por defecto todo permitido
    // si no existe lo crea, devuelve true al crearlo de lo contrario false    
    if ( ! $Files->create_path($temp_path) )
    {
        on_exception_server_response(409,'El directorio no pudo ser creado.',$target);
        die();
    }
    
    $ext = get_file_extension($file_name);

    // creamos un numero al azar entre 1000 y 1000000
    $new_file_name = (string)rand(1000,1000000);
    
    // agregamos la extension
    $new_file_name .= "." . $ext;

    // ruta completa donde se guardara el archivo (incluye nombre del archivo)
    $path_to_save_file = $temp_path . "/" . $new_file_name;
        
    // copia el archivo en el directorio indicado con un nombre nuevo y devuelve true de lo contrario false
    $file_was_copied = $Files->copy_file_with_new_name($file_path,$path_to_save_file);
   
    // si el archiv no fue movido
    if ( ! $file_was_copied )
    {
        echo '{
            "status": "500",
            "message": "Contacte al Administrador de Sistemas",
            "target": "files-show_or_download"
            }';
        die();
    } 
    

?>
<?php if ( is_file($path_to_save_file) ) : ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="<?php echo ASSETS_DIRECTORY . "/js/jquery-3.6.0.min.js"; ?>"></script>
        <title><?php echo $new_file_name; ?></title>        
    </head>
    <body style="display:flex;justify-content:center;">
        <iframe id="iframe" src="<?php echo ASSETS_DIRECTORY . "js/ViewerJS/#./../../" . $path_to_save_file; ?>" width='1024' height='1024' allowfullscreen webkitallowfullscreen></iframe>
        <input type="hidden" id="temp_file_name" name="temp_file_name" value="<?php echo $path_to_save_file; ?>">
        <script>
            $('#iframe').ready(function() {
                setTimeout(function() {

                    // Crea una instancia de MutationObserver con una función de devolución de llamada
                    var observador = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            <?php // Manejar el cambio de tamaño aquí ?>
                            
                            <?php // quitamos los estilos ?>
                            const textLayer = $('#iframe').contents().find('.textLayer'); 
                            $.each(textLayer, function(key, element) { 
                                element.setAttribute('style','');
                            });
                        });
                    });

                    // Configura las opciones para el observador (en este caso, observar cambios en el tamaño)
                    var opcionesObservador = {
                        attributes: true,
                        attributeFilter: ['style'],
                        attributeOldValue: true
                    };

                    <?php // eliminamos el boton de descarga ?>
                    $('#iframe').contents().find('#download').remove();

                    <?php // Quitamos los styles css por defecto cargados por el script ?>

                    const textLayer = $('#iframe').contents().find('.textLayer'); 
                    $.each(textLayer, function(key, element) { 
                        element.setAttribute('style','');
                    });

                    const page = $('#iframe').contents().find('.page'); 
                    $.each(page, function(key, element) { 
                        
                        element.setAttribute('style',"");
    
                        <?php // Inicia la observación del o de los elementos ?>
                        observador.observe(element, opcionesObservador);
                    });
                    
                    

                }, 300);
            });  


        </script>        
    </body>
    <style>
        /* Copyright 2014 Mozilla Foundation
        *
        * Licensed under the Apache License, Version 2.0 (the "License");
        * you may not use this file except in compliance with the License.
        * You may obtain a copy of the License at
        *
        *     http://www.apache.org/licenses/LICENSE-2.0
        *
        * Unless required by applicable law or agreed to in writing, software
        * distributed under the License is distributed on an "AS IS" BASIS,
        * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
        * See the License for the specific language governing permissions and
        * limitations under the License.
        */

        /* Prevent text selection of a <body> element in all major browsers */
        .textLayer {            
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            opacity: 0.2;
            line-height: 1.0;            
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Chrome 6.0+, Safari 3.1+, Edge & Opera 15+ */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* IE 10+ and Edge */
            user-select: none; /* Non-prefixed version*/
        }

        .textLayer > span {
            color: transparent;
            position: absolute;
            white-space: pre;
            cursor: text;
            -webkit-transform-origin: 0% 0%;
            transform-origin: 0% 0%;
        }

        .textLayer .highlight {
            margin: -1px;
            padding: 1px;

            background-color: #007dc3;
            border-radius: 4px;
        }

        .textLayer .highlight.begin {
            border-radius: 4px 0px 0px 4px;
        }

        .textLayer .highlight.end {
            border-radius: 0px 4px 4px 0px;
        }

        .textLayer .highlight.middle {
            border-radius: 0px;
        }

        .textLayer .highlight.selected {
            background-color: #f79800;
        }

        .textLayer ::-moz-selection {
            background: rgb(0, 0, 255);
        }

        .textLayer ::selection {
            background: rgb(0, 0, 255);
        }

        .textLayer .endOfContent {
            display: block;
            position: absolute;
            left: 0px;
            top: 100%;
            right: 0px;
            bottom: 0px;
            z-index: -1;
            cursor: default;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .textLayer .endOfContent.active {
            top: 0px;
        }


        .annotationLayer section {
            position: absolute;
        }

        .annotationLayer .linkAnnotation > a,
        .annotationLayer .buttonWidgetAnnotation.pushButton > a {
            position: absolute;
            font-size: 1em;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .annotationLayer .linkAnnotation > a:hover,
        .annotationLayer .buttonWidgetAnnotation.pushButton > a:hover {
            opacity: 0.2;
            background: #ff0;
            box-shadow: 0px 2px 10px #ff0;
        }

        .annotationLayer .textAnnotation img {
            position: absolute;
            cursor: pointer;
        }

        .annotationLayer .textWidgetAnnotation input,
        .annotationLayer .textWidgetAnnotation textarea,
        .annotationLayer .choiceWidgetAnnotation select,
        .annotationLayer .buttonWidgetAnnotation.checkBox input,
        .annotationLayer .buttonWidgetAnnotation.radioButton input {
            background-color: rgba(0, 54, 255, 0.13);
            border: 1px solid transparent;
            box-sizing: border-box;
            font-size: 9px;
            height: 100%;
            margin: 0;
            padding: 0 3px;
            vertical-align: top;
            width: 100%;
        }

        .annotationLayer .choiceWidgetAnnotation select option {
            padding: 0;
        }

        .annotationLayer .buttonWidgetAnnotation.radioButton input {
            border-radius: 50%;
        }

        .annotationLayer .textWidgetAnnotation textarea {
            font: message-box;
            font-size: 9px;
            resize: none;
        }

        .annotationLayer .textWidgetAnnotation input[disabled],
        .annotationLayer .textWidgetAnnotation textarea[disabled],
        .annotationLayer .choiceWidgetAnnotation select[disabled],
        .annotationLayer .buttonWidgetAnnotation.checkBox input[disabled],
        .annotationLayer .buttonWidgetAnnotation.radioButton input[disabled] {
            background: none;
            border: 1px solid transparent;
            cursor: not-allowed;
        }

        .annotationLayer .textWidgetAnnotation input:hover,
        .annotationLayer .textWidgetAnnotation textarea:hover,
        .annotationLayer .choiceWidgetAnnotation select:hover,
        .annotationLayer .buttonWidgetAnnotation.checkBox input:hover,
        .annotationLayer .buttonWidgetAnnotation.radioButton input:hover {
            border: 1px solid #000;
        }

        .annotationLayer .textWidgetAnnotation input:focus,
        .annotationLayer .textWidgetAnnotation textarea:focus,
        .annotationLayer .choiceWidgetAnnotation select:focus {
            background: none;
            border: 1px solid transparent;
        }

        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:before,
        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:after,
        .annotationLayer .buttonWidgetAnnotation.radioButton input:checked:before {
            background-color: #000;
            content: '';
            display: block;
            position: absolute;
        }

        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:before,
        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:after {
            height: 80%;
            left: 45%;
            width: 1px;
        }

        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:before {
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .annotationLayer .buttonWidgetAnnotation.checkBox input:checked:after {
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }

        .annotationLayer .buttonWidgetAnnotation.radioButton input:checked:before {
            border-radius: 50%;
            height: 50%;
            left: 30%;
            top: 20%;
            width: 50%;
        }

        .annotationLayer .textWidgetAnnotation input.comb {
            font-family: monospace;
            padding-left: 2px;
            padding-right: 0;
        }

        .annotationLayer .textWidgetAnnotation input.comb:focus {
            /*
            * Letter spacing is placed on the right side of each character. Hence, the
            * letter spacing of the last character may be placed outside the visible
            * area, causing horizontal scrolling. We avoid this by extending the width
            * when the element has focus and revert this when it loses focus.
            */
            width: 115%;
        }

        .annotationLayer .buttonWidgetAnnotation.checkBox input,
        .annotationLayer .buttonWidgetAnnotation.radioButton input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding: 0;
        }

        .annotationLayer .popupWrapper {
            position: absolute;
            width: 20em;
        }

        .annotationLayer .popup {
            position: absolute;
            z-index: 200;
            max-width: 20em;
            background-color: #FFFF99;
            box-shadow: 0px 2px 5px #333;
            border-radius: 2px;
            padding: 0.6em;
            margin-left: 5px;
            cursor: pointer;
            font: message-box;
            word-wrap: break-word;
        }

        .annotationLayer .popup h1 {
            font-size: 1em;
            border-bottom: 1px solid #000000;
            margin: 0;
            padding-bottom: 0.2em;
        }

        .annotationLayer .popup p {
            margin: 0;
            padding-top: 0.2em;
        }

        .annotationLayer .highlightAnnotation,
        .annotationLayer .underlineAnnotation,
        .annotationLayer .squigglyAnnotation,
        .annotationLayer .strikeoutAnnotation,
        .annotationLayer .lineAnnotation svg line,
        .annotationLayer .squareAnnotation svg rect,
        .annotationLayer .circleAnnotation svg ellipse,
        .annotationLayer .polylineAnnotation svg polyline,
        .annotationLayer .polygonAnnotation svg polygon,
        .annotationLayer .inkAnnotation svg polyline,
        .annotationLayer .stampAnnotation,
        .annotationLayer .fileAttachmentAnnotation {
            cursor: pointer;
        }

        .pdfViewer .canvasWrapper {
            overflow: hidden;
        }

        .pdfViewer .page {
            direction: ltr;
            width: 816px;
            height: 1056px;
            margin: 1px auto -8px auto;
            position: relative;
            overflow: visible;
            border: 9px solid transparent;
            background-clip: content-box;
            -o-border-image: url(images/shadow.png) 9 9 repeat;
            border-image: url(images/shadow.png) 9 9 repeat;
            background-color: white;
        }

        .pdfViewer.removePageBorders .page {
            margin: 0px auto 10px auto;
            border: none;
        }

        .pdfViewer.singlePageView {
            display: inline-block;
        }

        .pdfViewer.singlePageView .page {
            margin: 0;
            border: none;
        }

        .pdfViewer.scrollHorizontal, .pdfViewer.scrollWrapped, .spread {
            margin-left: 3.5px;
            margin-right: 3.5px;
            text-align: center;
        }

        .pdfViewer.scrollHorizontal, .spread {
            white-space: nowrap;
        }

        .pdfViewer.removePageBorders,
        .pdfViewer.scrollHorizontal .spread,
        .pdfViewer.scrollWrapped .spread {
            margin-left: 0;
            margin-right: 0;
        }

        .spread .page,
        .pdfViewer.scrollHorizontal .page,
        .pdfViewer.scrollWrapped .page,
        .pdfViewer.scrollHorizontal .spread,
        .pdfViewer.scrollWrapped .spread {
            display: inline-block;
            vertical-align: middle;
        }

        .spread .page,
        .pdfViewer.scrollHorizontal .page,
        .pdfViewer.scrollWrapped .page {
            margin-left: -3.5px;
            margin-right: -3.5px;
        }

        .pdfViewer.removePageBorders .spread .page,
        .pdfViewer.removePageBorders.scrollHorizontal .page,
        .pdfViewer.removePageBorders.scrollWrapped .page {
            margin-left: 5px;
            margin-right: 5px;
        }

        .pdfViewer .page canvas {
            margin: 0;
            display: block;
        }

        .pdfViewer .page canvas[hidden] {
            display: none;
        }

        .pdfViewer .page .loadingIcon {
            position: absolute;
            display: block;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: url('images/loading-icon.gif') center no-repeat;
        }

        .pdfPresentationMode .pdfViewer {
            margin-left: 0;
            margin-right: 0;
        }

        .pdfPresentationMode .pdfViewer .page,
        .pdfPresentationMode .pdfViewer .spread {
            display: block;
        }

        .pdfPresentationMode .pdfViewer .page,
        .pdfPresentationMode .pdfViewer.removePageBorders .page {
            margin-left: auto;
            margin-right: auto;
        }

        .pdfPresentationMode:-ms-fullscreen .pdfViewer .page {
            margin-bottom: 100% !important;
        }

        .pdfPresentationMode:-webkit-full-screen .pdfViewer .page {
            margin-bottom: 100%;
            border: 0;
        }

        .pdfPresentationMode:-moz-full-screen .pdfViewer .page {
            margin-bottom: 100%;
            border: 0;
        }

        .pdfPresentationMode:fullscreen .pdfViewer .page {
            margin-bottom: 100%;
            border: 0;
        }

        :root {
            --sidebar-width: 200px;
        }

        * {
            padding: 0;
            margin: 0;
        }

        html {
            height: 100%;
            width: 100%;
            /* Font size is needed to make the activity bar the correct size. */
            font-size: 10px;
        }

        body {
            height: 100%;
            width: 100%;
            background-color: #404040;
            background-image: url(images/texture.png);
        }

        body,
        input,
        button,
        select {
            font: message-box;
            outline: none;
        }

        .hidden {
            display: none !important;
        }

        [hidden] {
            display: none !important;
        }

        #viewerContainer.pdfPresentationMode:-ms-fullscreen {
            top: 0px !important;
            overflow: hidden !important;
        }

        #viewerContainer.pdfPresentationMode:-ms-fullscreen::-ms-backdrop {
            background-color: #000;
        }

        #viewerContainer.pdfPresentationMode:-webkit-full-screen {
            top: 0px;
            border-top: 2px solid transparent;
            background-color: #000;
            width: 100%;
            height: 100%;
            overflow: hidden;
            cursor: none;
            -webkit-user-select: none;
            user-select: none;
        }

        #viewerContainer.pdfPresentationMode:-moz-full-screen {
            top: 0px;
            border-top: 2px solid transparent;
            background-color: #000;
            width: 100%;
            height: 100%;
            overflow: hidden;
            cursor: none;
            -moz-user-select: none;
            user-select: none;
        }

        #viewerContainer.pdfPresentationMode:-ms-fullscreen {
            top: 0px;
            border-top: 2px solid transparent;
            background-color: #000;
            width: 100%;
            height: 100%;
            overflow: hidden;
            cursor: none;
            -ms-user-select: none;
            user-select: none;
        }

        #viewerContainer.pdfPresentationMode:fullscreen {
            top: 0px;
            border-top: 2px solid transparent;
            background-color: #000;
            width: 100%;
            height: 100%;
            overflow: hidden;
            cursor: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .pdfPresentationMode:-webkit-full-screen a:not(.internalLink) {
            display: none;
        }

        .pdfPresentationMode:-moz-full-screen a:not(.internalLink) {
            display: none;
        }

        .pdfPresentationMode:-ms-fullscreen a:not(.internalLink) {
            display: none;
        }

        .pdfPresentationMode:fullscreen a:not(.internalLink) {
            display: none;
        }

        .pdfPresentationMode:-webkit-full-screen .textLayer > span {
            cursor: none;
        }

        .pdfPresentationMode:-moz-full-screen .textLayer > span {
            cursor: none;
        }

        .pdfPresentationMode:-ms-fullscreen .textLayer > span {
            cursor: none;
        }

        .pdfPresentationMode:fullscreen .textLayer > span {
            cursor: none;
        }

        .pdfPresentationMode.pdfPresentationModeControls > *,
        .pdfPresentationMode.pdfPresentationModeControls .textLayer > span {
            cursor: default;
        }

        #headerContainer {
            width: 100%;
            background: linear-gradient(#007dc3, #005a8f);
            height: 80px;
        }

        #headerContainer h3 {
            color: #fff; /*font-family:"Avenir Next LT W01 Bold";*/
        }

        #headerContainer #gnwlogo {
            margin-left: 30px;
            padding-right: 30px;
            height: 44px;
            margin-top: 18px;
            width: auto;
            float: left;
            border-right: 1px solid #fff;
        }

        #headerContainer h3#noprint {
            float: right;
            margin-right: 30px;
            font-style: italic;
            font-weight: 700;
            margin-top: 26px;
            font-size: 1.5em;
        }

        #headerContainer h3#gps {
            float: left;
            margin-left: 30px;
            font-weight: 700;
            margin-top: 30px;
        }

        #outerContainer {
            width: 100%;
            height: calc(100% - 80px);
            position: relative;
        }

        #sidebarContainer {
            position: absolute;
            top: 32px;
            bottom: 0;
            width: 200px;
            /* Here, and elsewhere below, keep the constant value for compatibility
                            with older browsers that lack support for CSS variables. */
            width: var(--sidebar-width);
            visibility: hidden;
            z-index: 100;
            border-top: 1px solid #333;

            transition-duration: 200ms;
            transition-timing-function: ease;
        }

        html[dir='ltr'] #sidebarContainer {
            transition-property: left;
            left: -200px;
            left: calc(-1 * var(--sidebar-width));
        }

        html[dir='rtl'] #sidebarContainer {
            transition-property: right;
            right: -200px;
            right: calc(-1 * var(--sidebar-width));
        }

        .loadingInProgress #sidebarContainer {
            top: 36px;
        }

        #outerContainer.sidebarResizing #sidebarContainer {
            /* Improve responsiveness and avoid visual glitches when the sidebar is resized. */
            transition-duration: 0s;
            /* Prevent e.g. the thumbnails being selected when the sidebar is resized. */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        #outerContainer.sidebarMoving #sidebarContainer,
        #outerContainer.sidebarOpen #sidebarContainer {
            visibility: visible;
        }

        html[dir='ltr'] #outerContainer.sidebarOpen #sidebarContainer {
            left: 0px;
        }

        html[dir='rtl'] #outerContainer.sidebarOpen #sidebarContainer {
            right: 0px;
        }

        #mainContainer {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            min-width: 320px;
        }

        #sidebarContent {
            top: 32px;
            bottom: 0;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            position: absolute;
            width: 100%;
            background-color: hsla(0, 0%, 0%, .1);
        }

        html[dir='ltr'] #sidebarContent {
            left: 0;
            box-shadow: inset -1px 0 0 hsla(0, 0%, 0%, .25);
        }

        html[dir='rtl'] #sidebarContent {
            right: 0;
            box-shadow: inset 1px 0 0 hsla(0, 0%, 0%, .25);
        }

        #viewerContainer {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            position: absolute;
            top: 32px;
            right: 0;
            bottom: 0;
            left: 0;
            outline: none;
        }

        #viewerContainer:not(.pdfPresentationMode) {
            transition-duration: 200ms;
            transition-timing-function: ease;
        }

        html[dir='ltr'] #viewerContainer {
            box-shadow: inset 1px 0 0 hsla(0, 0%, 100%, .05);
        }

        html[dir='rtl'] #viewerContainer {
            box-shadow: inset -1px 0 0 hsla(0, 0%, 100%, .05);
        }

        #outerContainer.sidebarResizing #viewerContainer {
            /* Improve responsiveness and avoid visual glitches when the sidebar is resized. */
            transition-duration: 0s;
        }

        html[dir='ltr'] #outerContainer.sidebarOpen #viewerContainer:not(.pdfPresentationMode) {
            transition-property: left;
            left: 200px;
            left: var(--sidebar-width);
        }

        html[dir='rtl'] #outerContainer.sidebarOpen #viewerContainer:not(.pdfPresentationMode) {
            transition-property: right;
            right: 200px;
            right: var(--sidebar-width);
        }

        .toolbar {
            position: relative;
            left: 0;
            right: 0;
            z-index: 9999;
            cursor: default;
        }

        #toolbarContainer {
            width: 100%;
        }

        #toolbarSidebar {
            width: 100%;
            height: 32px;
            background-color: #424242; /* fallback */
            background-image: url(images/texture.png),
            linear-gradient(hsla(0, 0%, 30%, .99), hsla(0, 0%, 25%, .95));
        }

        html[dir='ltr'] #toolbarSidebar {
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.25),
            inset 0 -1px 0 hsla(0, 0%, 100%, .05),
            0 1px 0 hsla(0, 0%, 0%, .15),
            0 0 1px hsla(0, 0%, 0%, .1);
        }

        html[dir='rtl'] #toolbarSidebar {
            box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.25),
            inset 0 1px 0 hsla(0, 0%, 100%, .05),
            0 1px 0 hsla(0, 0%, 0%, .15),
            0 0 1px hsla(0, 0%, 0%, .1);
        }

        #sidebarResizer {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 6px;
            z-index: 200;
            cursor: ew-resize;
        }

        html[dir='ltr'] #sidebarResizer {
            right: -6px;
        }

        html[dir='rtl'] #sidebarResizer {
            left: -6px;
        }

        #toolbarContainer, .findbar, .secondaryToolbar {
            position: relative;
            height: 32px;
            background-color: #474747; /* fallback */
            background-image: url(images/texture.png),
            linear-gradient(hsla(0, 0%, 32%, .99), hsla(0, 0%, 27%, .95));
        }

        html[dir='ltr'] #toolbarContainer, .findbar, .secondaryToolbar {
            box-shadow: inset 0 1px 1px hsla(0, 0%, 0%, .15),
            inset 0 -1px 0 hsla(0, 0%, 100%, .05),
            0 1px 0 hsla(0, 0%, 0%, .15),
            0 1px 1px hsla(0, 0%, 0%, .1);
        }

        html[dir='rtl'] #toolbarContainer, .findbar, .secondaryToolbar {
            box-shadow: inset 0 1px 1px hsla(0, 0%, 0%, .15),
            inset 0 -1px 0 hsla(0, 0%, 100%, .05),
            0 1px 0 hsla(0, 0%, 0%, .15),
            0 1px 1px hsla(0, 0%, 0%, .1);
        }

        #toolbarViewer {
            height: 32px;
        }

        #loadingBar {
            position: relative;
            width: 100%;
            height: 4px;
            background-color: #333;
            border-bottom: 1px solid #333;
        }

        #loadingBar .progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: #ddd;
            overflow: hidden;
            transition: width 200ms;
        }

        @-webkit-keyframes progressIndeterminate {
            0% {
                left: -142px;
            }
            100% {
                left: 0;
            }
        }

        @keyframes progressIndeterminate {
            0% {
                left: -142px;
            }
            100% {
                left: 0;
            }
        }

        #loadingBar .progress.indeterminate {
            background-color: #999;
            transition: none;
        }

        #loadingBar .progress.indeterminate .glimmer {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: calc(100% + 150px);

            background: repeating-linear-gradient(135deg,
            #bbb 0, #999 5px,
            #999 45px, #ddd 55px,
            #ddd 95px, #bbb 100px);

            -webkit-animation: progressIndeterminate 950ms linear infinite;

            animation: progressIndeterminate 950ms linear infinite;
        }

        .findbar, .secondaryToolbar {
            top: 32px;
            position: absolute;
            z-index: 10000;
            height: auto;
            min-width: 16px;
            padding: 0px 6px 0px 6px;
            margin: 4px 2px 4px 2px;
            color: hsl(0, 0%, 85%);
            font-size: 12px;
            line-height: 14px;
            text-align: left;
            cursor: default;
        }

        .findbar {
            min-width: 300px;
        }

        .findbar > div {
            height: 32px;
        }

        .findbar.wrapContainers > div {
            clear: both;
        }

        .findbar.wrapContainers > div#findbarMessageContainer {
            height: auto;
        }

        html[dir='ltr'] .findbar {
            left: 68px;
        }

        html[dir='rtl'] .findbar {
            right: 68px;
        }

        .findbar label {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        #findInput {
            width: 200px;
        }

        #findInput::-webkit-input-placeholder {
            color: hsl(0, 0%, 75%);
        }

        #findInput:-ms-input-placeholder {
            font-style: italic;
        }

        #findInput::-ms-input-placeholder {
            font-style: italic;
        }

        #findInput::placeholder {
            font-style: italic;
        }

        #findInput[data-status="pending"] {
            background-image: url(images/loading-small.png);
            background-repeat: no-repeat;
            background-position: right;
        }

        html[dir='rtl'] #findInput[data-status="pending"] {
            background-position: left;
        }

        .secondaryToolbar {
            padding: 6px;
            height: auto;
            z-index: 30000;
        }

        html[dir='ltr'] .secondaryToolbar {
            right: 4px;
        }

        html[dir='rtl'] .secondaryToolbar {
            left: 4px;
        }

        #secondaryToolbarButtonContainer {
            max-width: 200px;
            max-height: 400px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: -4px;
        }

        #secondaryToolbarButtonContainer.hiddenScrollModeButtons > .scrollModeButtons,
        #secondaryToolbarButtonContainer.hiddenSpreadModeButtons > .spreadModeButtons {
            display: none !important;
        }

        .doorHanger,
        .doorHangerRight {
            border: 1px solid hsla(0, 0%, 0%, .5);
            border-radius: 2px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        }

        .doorHanger:after, .doorHanger:before,
        .doorHangerRight:after, .doorHangerRight:before {
            bottom: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .doorHanger:after,
        .doorHangerRight:after {
            border-bottom-color: hsla(0, 0%, 32%, .99);
            border-width: 8px;
        }

        .doorHanger:before,
        .doorHangerRight:before {
            border-bottom-color: hsla(0, 0%, 0%, .5);
            border-width: 9px;
        }

        html[dir='ltr'] .doorHanger:after,
        html[dir='rtl'] .doorHangerRight:after {
            left: 13px;
            margin-left: -8px;
        }

        html[dir='ltr'] .doorHanger:before,
        html[dir='rtl'] .doorHangerRight:before {
            left: 13px;
            margin-left: -9px;
        }

        html[dir='rtl'] .doorHanger:after,
        html[dir='ltr'] .doorHangerRight:after {
            right: 13px;
            margin-right: -8px;
        }

        html[dir='rtl'] .doorHanger:before,
        html[dir='ltr'] .doorHangerRight:before {
            right: 13px;
            margin-right: -9px;
        }

        #findResultsCount {
            background-color: hsl(0, 0%, 85%);
            color: hsl(0, 0%, 32%);
            text-align: center;
            padding: 3px 4px;
        }

        #findMsg {
            font-style: italic;
            color: #A6B7D0;
        }

        #findMsg:empty {
            display: none;
        }

        #findInput.notFound {
            background-color: rgb(255, 102, 102);
        }

        #toolbarViewerMiddle {
            position: absolute;
            left: 50%;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
        }

        html[dir='ltr'] #toolbarViewerLeft,
        html[dir='rtl'] #toolbarViewerRight {
            float: left;
        }

        html[dir='ltr'] #toolbarViewerRight,
        html[dir='rtl'] #toolbarViewerLeft {
            float: right;
        }

        html[dir='ltr'] #toolbarViewerLeft > *,
        html[dir='ltr'] #toolbarViewerMiddle > *,
        html[dir='ltr'] #toolbarViewerRight > *,
        html[dir='ltr'] .findbar * {
            position: relative;
            float: left;
        }

        html[dir='rtl'] #toolbarViewerLeft > *,
        html[dir='rtl'] #toolbarViewerMiddle > *,
        html[dir='rtl'] #toolbarViewerRight > *,
        html[dir='rtl'] .findbar * {
            position: relative;
            float: right;
        }

        html[dir='ltr'] .splitToolbarButton {
            margin: 3px 2px 4px 0;
            display: inline-block;
        }

        html[dir='rtl'] .splitToolbarButton {
            margin: 3px 0 4px 2px;
            display: inline-block;
        }

        html[dir='ltr'] .splitToolbarButton > .toolbarButton {
            border-radius: 0;
            float: left;
        }

        html[dir='rtl'] .splitToolbarButton > .toolbarButton {
            border-radius: 0;
            float: right;
        }

        .toolbarButton,
        .secondaryToolbarButton,
        .overlayButton {
            border: 0 none;
            background: none;
            width: 32px;
            height: 25px;
        }

        .toolbarButton > span {
            display: inline-block;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .toolbarButton[disabled],
        .secondaryToolbarButton[disabled],
        .overlayButton[disabled] {
            opacity: .5;
        }

        .splitToolbarButton.toggled .toolbarButton {
            margin: 0;
        }

        .splitToolbarButton:hover > .toolbarButton,
        .splitToolbarButton:focus > .toolbarButton,
        .splitToolbarButton.toggled > .toolbarButton,
        .toolbarButton.textButton {
            background-color: hsla(0, 0%, 0%, .12);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            border: 1px solid hsla(0, 0%, 0%, .35);
            border-color: hsla(0, 0%, 0%, .32) hsla(0, 0%, 0%, .38) hsla(0, 0%, 0%, .42);
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .15) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease;

        }

        .splitToolbarButton > .toolbarButton:hover,
        .splitToolbarButton > .toolbarButton:focus,
        .dropdownToolbarButton:hover,
        .overlayButton:hover,
        .overlayButton:focus,
        .toolbarButton.textButton:hover,
        .toolbarButton.textButton:focus {
            background-color: hsla(0, 0%, 0%, .2);
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .15) inset,
            0 0 1px hsla(0, 0%, 0%, .05);
            z-index: 199;
        }

        .splitToolbarButton > .toolbarButton {
            position: relative;
        }

        html[dir='ltr'] .splitToolbarButton > .toolbarButton:first-child,
        html[dir='rtl'] .splitToolbarButton > .toolbarButton:last-child {
            position: relative;
            margin: 0;
            margin-right: -1px;
            border-top-left-radius: 2px;
            border-bottom-left-radius: 2px;
            border-right-color: transparent;
        }

        html[dir='ltr'] .splitToolbarButton > .toolbarButton:last-child,
        html[dir='rtl'] .splitToolbarButton > .toolbarButton:first-child {
            position: relative;
            margin: 0;
            margin-left: -1px;
            border-top-right-radius: 2px;
            border-bottom-right-radius: 2px;
            border-left-color: transparent;
        }

        .splitToolbarButtonSeparator {
            padding: 8px 0;
            width: 1px;
            background-color: hsla(0, 0%, 0%, .5);
            z-index: 99;
            box-shadow: 0 0 0 1px hsla(0, 0%, 100%, .08);
            display: inline-block;
            margin: 5px 0;
        }

        html[dir='ltr'] .splitToolbarButtonSeparator {
            float: left;
        }

        html[dir='rtl'] .splitToolbarButtonSeparator {
            float: right;
        }

        .splitToolbarButton:hover > .splitToolbarButtonSeparator,
        .splitToolbarButton.toggled > .splitToolbarButtonSeparator {
            padding: 12px 0;
            margin: 1px 0;
            box-shadow: 0 0 0 1px hsla(0, 0%, 100%, .03);
            transition-property: padding;
            transition-duration: 10ms;
            transition-timing-function: ease;
        }

        .toolbarButton,
        .dropdownToolbarButton,
        .secondaryToolbarButton,
        .overlayButton {
            min-width: 16px;
            padding: 2px 6px 0;
            border: 1px solid transparent;
            border-radius: 2px;
            color: hsla(0, 0%, 100%, .8);
            font-size: 12px;
            line-height: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            /* Opera does not support user-select, use <... unselectable="on"> instead */
            cursor: default;
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease;
        }

        html[dir='ltr'] .toolbarButton,
        html[dir='ltr'] .overlayButton,
        html[dir='ltr'] .dropdownToolbarButton {
            margin: 3px 2px 4px 0;
        }

        html[dir='rtl'] .toolbarButton,
        html[dir='rtl'] .overlayButton,
        html[dir='rtl'] .dropdownToolbarButton {
            margin: 3px 0 4px 2px;
        }

        .toolbarButton:hover,
        .toolbarButton:focus,
        .dropdownToolbarButton,
        .overlayButton,
        .secondaryToolbarButton:hover,
        .secondaryToolbarButton:focus {
            background-color: hsla(0, 0%, 0%, .12);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            border: 1px solid hsla(0, 0%, 0%, .35);
            border-color: hsla(0, 0%, 0%, .32) hsla(0, 0%, 0%, .38) hsla(0, 0%, 0%, .42);
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .15) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
        }

        .toolbarButton:hover:active,
        .overlayButton:hover:active,
        .dropdownToolbarButton:hover:active,
        .secondaryToolbarButton:hover:active {
            background-color: hsla(0, 0%, 0%, .2);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            border-color: hsla(0, 0%, 0%, .35) hsla(0, 0%, 0%, .4) hsla(0, 0%, 0%, .45);
            box-shadow: 0 1px 1px hsla(0, 0%, 0%, .1) inset,
            0 0 1px hsla(0, 0%, 0%, .2) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 10ms;
            transition-timing-function: linear;
        }

        .toolbarButton.toggled,
        .splitToolbarButton.toggled > .toolbarButton.toggled,
        .secondaryToolbarButton.toggled {
            background-color: hsla(0, 0%, 0%, .3);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            border-color: hsla(0, 0%, 0%, .4) hsla(0, 0%, 0%, .45) hsla(0, 0%, 0%, .5);
            box-shadow: 0 1px 1px hsla(0, 0%, 0%, .1) inset,
            0 0 1px hsla(0, 0%, 0%, .2) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 10ms;
            transition-timing-function: linear;
        }

        .toolbarButton.toggled:hover:active,
        .splitToolbarButton.toggled > .toolbarButton.toggled:hover:active,
        .secondaryToolbarButton.toggled:hover:active {
            background-color: hsla(0, 0%, 0%, .4);
            border-color: hsla(0, 0%, 0%, .4) hsla(0, 0%, 0%, .5) hsla(0, 0%, 0%, .55);
            box-shadow: 0 1px 1px hsla(0, 0%, 0%, .2) inset,
            0 0 1px hsla(0, 0%, 0%, .3) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
        }

        .dropdownToolbarButton {
            width: 120px;
            max-width: 120px;
            padding: 0;
            overflow: hidden;
            background: url(images/toolbarButton-menuArrows.png) no-repeat;
        }

        html[dir='ltr'] .dropdownToolbarButton {
            background-position: 95%;
        }

        html[dir='rtl'] .dropdownToolbarButton {
            background-position: 5%;
        }

        .dropdownToolbarButton > select {
            min-width: 140px;
            font-size: 12px;
            color: hsl(0, 0%, 95%);
            margin: 0;
            padding: 3px 2px 2px;
            border: none;
            background: rgba(0, 0, 0, 0); /* Opera does not support 'transparent' <select> background */
        }

        .dropdownToolbarButton > select > option {
            background: hsl(0, 0%, 24%);
        }

        #customScaleOption {
            display: none;
        }

        #pageWidthOption {
            border-bottom: 1px rgba(255, 255, 255, .5) solid;
        }

        html[dir='ltr'] .splitToolbarButton:first-child,
        html[dir='ltr'] .toolbarButton:first-child,
        html[dir='rtl'] .splitToolbarButton:last-child,
        html[dir='rtl'] .toolbarButton:last-child {
            margin-left: 4px;
        }

        html[dir='ltr'] .splitToolbarButton:last-child,
        html[dir='ltr'] .toolbarButton:last-child,
        html[dir='rtl'] .splitToolbarButton:first-child,
        html[dir='rtl'] .toolbarButton:first-child {
            margin-right: 4px;
        }

        .toolbarButtonSpacer {
            width: 30px;
            display: inline-block;
            height: 1px;
        }

        html[dir='ltr'] #findPrevious {
            margin-left: 3px;
        }

        html[dir='ltr'] #findNext {
            margin-right: 3px;
        }

        html[dir='rtl'] #findPrevious {
            margin-right: 3px;
        }

        html[dir='rtl'] #findNext {
            margin-left: 3px;
        }

        .toolbarButton::before,
        .secondaryToolbarButton::before {
            /* All matching images have a size of 16x16
            * All relevant containers have a size of 32x25 */
            position: absolute;
            display: inline-block;
            top: 4px;
            left: 7px;
        }

        html[dir="ltr"] .secondaryToolbarButton::before {
            left: 4px;
        }

        html[dir="rtl"] .secondaryToolbarButton::before {
            right: 4px;
        }

        html[dir='ltr'] .toolbarButton#sidebarToggle::before {
            content: url(images/toolbarButton-sidebarToggle.png);
        }

        html[dir='rtl'] .toolbarButton#sidebarToggle::before {
            content: url(images/toolbarButton-sidebarToggle-rtl.png);
        }

        html[dir='ltr'] .toolbarButton#secondaryToolbarToggle::before {
            content: url(images/toolbarButton-secondaryToolbarToggle.png);
        }

        html[dir='rtl'] .toolbarButton#secondaryToolbarToggle::before {
            content: url(images/toolbarButton-secondaryToolbarToggle-rtl.png);
        }

        html[dir='ltr'] .toolbarButton.findPrevious::before {
            content: url(images/findbarButton-previous.png);
        }

        html[dir='rtl'] .toolbarButton.findPrevious::before {
            content: url(images/findbarButton-previous-rtl.png);
        }

        html[dir='ltr'] .toolbarButton.findNext::before {
            content: url(images/findbarButton-next.png);
        }

        html[dir='rtl'] .toolbarButton.findNext::before {
            content: url(images/findbarButton-next-rtl.png);
        }

        html[dir='ltr'] .toolbarButton.pageUp::before {
            content: url(images/toolbarButton-pageUp.png);
        }

        html[dir='rtl'] .toolbarButton.pageUp::before {
            content: url(images/toolbarButton-pageUp-rtl.png);
        }

        html[dir='ltr'] .toolbarButton.pageDown::before {
            content: url(images/toolbarButton-pageDown.png);
        }

        html[dir='rtl'] .toolbarButton.pageDown::before {
            content: url(images/toolbarButton-pageDown-rtl.png);
        }

        .toolbarButton.zoomOut::before {
            content: url(images/toolbarButton-zoomOut.png);
        }

        .toolbarButton.zoomIn::before {
            content: url(images/toolbarButton-zoomIn.png);
        }

        .toolbarButton.presentationMode::before,
        .secondaryToolbarButton.presentationMode::before {
            content: url(images/toolbarButton-presentationMode.png);
        }

        .toolbarButton.print::before,
        .secondaryToolbarButton.print::before {
            content: url(images/toolbarButton-print.png);
        }

        .toolbarButton.openFile::before,
        .secondaryToolbarButton.openFile::before {
            content: url(images/toolbarButton-openFile.png);
        }

        .toolbarButton.download::before,
        .secondaryToolbarButton.download::before {
            content: url(images/toolbarButton-download.png);
        }

        .toolbarButton.bookmark,
        .secondaryToolbarButton.bookmark {
            box-sizing: border-box;
            outline: none;
            padding-top: 4px;
            text-decoration: none;
        }

        .secondaryToolbarButton.bookmark {
            padding-top: 5px;
        }

        .bookmark[href='#'] {
            opacity: .5;
            pointer-events: none;
        }

        .toolbarButton.bookmark::before,
        .secondaryToolbarButton.bookmark::before {
            content: url(images/toolbarButton-bookmark.png);
        }

        #viewThumbnail.toolbarButton::before {
            content: url(images/toolbarButton-viewThumbnail.png);
        }

        html[dir="ltr"] #viewOutline.toolbarButton::before {
            content: url(images/toolbarButton-viewOutline.png);
        }

        html[dir="rtl"] #viewOutline.toolbarButton::before {
            content: url(images/toolbarButton-viewOutline-rtl.png);
        }

        #viewAttachments.toolbarButton::before {
            content: url(images/toolbarButton-viewAttachments.png);
        }

        #viewFind.toolbarButton::before {
            content: url(images/toolbarButton-search.png);
        }

        .toolbarButton.pdfSidebarNotification::after {
            position: absolute;
            display: inline-block;
            top: 1px;
            /* Create a filled circle, with a diameter of 9 pixels, using only CSS: */
            content: '';
            background-color: #70DB55;
            height: 9px;
            width: 9px;
            border-radius: 50%;
        }

        html[dir='ltr'] .toolbarButton.pdfSidebarNotification::after {
            left: 17px;
        }

        html[dir='rtl'] .toolbarButton.pdfSidebarNotification::after {
            right: 17px;
        }

        .secondaryToolbarButton {
            position: relative;
            margin: 0 0 4px 0;
            padding: 3px 0 1px 0;
            height: auto;
            min-height: 25px;
            width: auto;
            min-width: 100%;
            white-space: normal;
        }

        html[dir="ltr"] .secondaryToolbarButton {
            padding-left: 24px;
            text-align: left;
        }

        html[dir="rtl"] .secondaryToolbarButton {
            padding-right: 24px;
            text-align: right;
        }

        html[dir="ltr"] .secondaryToolbarButton.bookmark {
            padding-left: 27px;
        }

        html[dir="rtl"] .secondaryToolbarButton.bookmark {
            padding-right: 27px;
        }

        html[dir="ltr"] .secondaryToolbarButton > span {
            padding-right: 4px;
        }

        html[dir="rtl"] .secondaryToolbarButton > span {
            padding-left: 4px;
        }

        .secondaryToolbarButton.firstPage::before {
            content: url(images/secondaryToolbarButton-firstPage.png);
        }

        .secondaryToolbarButton.lastPage::before {
            content: url(images/secondaryToolbarButton-lastPage.png);
        }

        .secondaryToolbarButton.rotateCcw::before {
            content: url(images/secondaryToolbarButton-rotateCcw.png);
        }

        .secondaryToolbarButton.rotateCw::before {
            content: url(images/secondaryToolbarButton-rotateCw.png);
        }

        .secondaryToolbarButton.selectTool::before {
            content: url(images/secondaryToolbarButton-selectTool.png);
        }

        .secondaryToolbarButton.handTool::before {
            content: url(images/secondaryToolbarButton-handTool.png);
        }

        .secondaryToolbarButton.scrollVertical::before {
            content: url(images/secondaryToolbarButton-scrollVertical.png);
        }

        .secondaryToolbarButton.scrollHorizontal::before {
            content: url(images/secondaryToolbarButton-scrollHorizontal.png);
        }

        .secondaryToolbarButton.scrollWrapped::before {
            content: url(images/secondaryToolbarButton-scrollWrapped.png);
        }

        .secondaryToolbarButton.spreadNone::before {
            content: url(images/secondaryToolbarButton-spreadNone.png);
        }

        .secondaryToolbarButton.spreadOdd::before {
            content: url(images/secondaryToolbarButton-spreadOdd.png);
        }

        .secondaryToolbarButton.spreadEven::before {
            content: url(images/secondaryToolbarButton-spreadEven.png);
        }

        .secondaryToolbarButton.documentProperties::before {
            content: url(images/secondaryToolbarButton-documentProperties.png);
        }

        .verticalToolbarSeparator {
            display: block;
            padding: 8px 0;
            margin: 8px 4px;
            width: 1px;
            background-color: hsla(0, 0%, 0%, .5);
            box-shadow: 0 0 0 1px hsla(0, 0%, 100%, .08);
        }

        html[dir='ltr'] .verticalToolbarSeparator {
            margin-left: 2px;
        }

        html[dir='rtl'] .verticalToolbarSeparator {
            margin-right: 2px;
        }

        .horizontalToolbarSeparator {
            display: block;
            margin: 0 0 4px 0;
            height: 1px;
            width: 100%;
            background-color: hsla(0, 0%, 0%, .5);
            box-shadow: 0 0 0 1px hsla(0, 0%, 100%, .08);
        }

        .toolbarField {
            padding: 3px 6px;
            margin: 4px 0 4px 0;
            border: 1px solid transparent;
            border-radius: 2px;
            background-color: hsla(0, 0%, 100%, .09);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            border: 1px solid hsla(0, 0%, 0%, .35);
            border-color: hsla(0, 0%, 0%, .32) hsla(0, 0%, 0%, .38) hsla(0, 0%, 0%, .42);
            box-shadow: 0 1px 0 hsla(0, 0%, 0%, .05) inset,
            0 1px 0 hsla(0, 0%, 100%, .05);
            color: hsl(0, 0%, 95%);
            font-size: 12px;
            line-height: 14px;
            outline-style: none;
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 150ms;
            transition-timing-function: ease;
        }

        .toolbarField[type=checkbox] {
            display: inline-block;
            margin: 8px 0px;
        }

        .toolbarField.pageNumber {
            -moz-appearance: textfield; /* hides the spinner in moz */
            min-width: 16px;
            text-align: right;
            width: 40px;
        }

        .toolbarField.pageNumber.visiblePageIsLoading {
            background-image: url(images/loading-small.png);
            background-repeat: no-repeat;
            background-position: 1px;
        }

        .toolbarField.pageNumber::-webkit-inner-spin-button,
        .toolbarField.pageNumber::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .toolbarField:hover {
            background-color: hsla(0, 0%, 100%, .11);
            border-color: hsla(0, 0%, 0%, .4) hsla(0, 0%, 0%, .43) hsla(0, 0%, 0%, .45);
        }

        .toolbarField:focus {
            background-color: hsla(0, 0%, 100%, .15);
            border-color: hsla(204, 100%, 65%, .8) hsla(204, 100%, 65%, .85) hsla(204, 100%, 65%, .9);
        }

        .toolbarLabel {
            min-width: 16px;
            padding: 3px 6px 3px 2px;
            margin: 4px 2px 4px 0;
            border: 1px solid transparent;
            border-radius: 2px;
            color: hsl(0, 0%, 85%);
            font-size: 12px;
            line-height: 14px;
            text-align: left;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: default;
        }

        #thumbnailView {
            position: absolute;
            width: calc(100% - 60px);
            top: 0;
            bottom: 0;
            padding: 10px 30px 0;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }

        #thumbnailView > a:active,
        #thumbnailView > a:focus {
            outline: 0;
        }

        .thumbnail {
            margin: 0 10px 5px 10px;
        }

        html[dir='ltr'] .thumbnail {
            float: left;
        }

        html[dir='rtl'] .thumbnail {
            float: right;
        }

        #thumbnailView > a:last-of-type > .thumbnail {
            margin-bottom: 10px;
        }

        #thumbnailView > a:last-of-type > .thumbnail:not([data-loaded]) {
            margin-bottom: 9px;
        }

        .thumbnail:not([data-loaded]) {
            border: 1px dashed rgba(255, 255, 255, 0.5);
            margin: -1px 9px 4px 9px;
        }

        .thumbnailImage {
            border: 1px solid transparent;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.5), 0 2px 8px rgba(0, 0, 0, 0.3);
            opacity: 0.8;
            z-index: 99;
            background-color: white;
            background-clip: content-box;
        }

        .thumbnailSelectionRing {
            border-radius: 2px;
            padding: 7px;
        }

        a:focus > .thumbnail > .thumbnailSelectionRing > .thumbnailImage,
        .thumbnail:hover > .thumbnailSelectionRing > .thumbnailImage {
            opacity: .9;
        }

        a:focus > .thumbnail > .thumbnailSelectionRing,
        .thumbnail:hover > .thumbnailSelectionRing {
            background-color: hsla(0, 0%, 100%, .15);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .2) inset,
            0 0 1px hsla(0, 0%, 0%, .2);
            color: hsla(0, 0%, 100%, .9);
        }

        .thumbnail.selected > .thumbnailSelectionRing > .thumbnailImage {
            box-shadow: 0 0 0 1px hsla(0, 0%, 0%, .5);
            opacity: 1;
        }

        .thumbnail.selected > .thumbnailSelectionRing {
            background-color: hsla(0, 0%, 100%, .3);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .1) inset,
            0 0 1px hsla(0, 0%, 0%, .2);
            color: hsla(0, 0%, 100%, 1);
        }

        #outlineView,
        #attachmentsView {
            position: absolute;
            width: calc(100% - 8px);
            top: 0;
            bottom: 0;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        #outlineView {
            padding: 4px 4px 0;
        }

        #attachmentsView {
            padding: 3px 4px 0;
        }

        html[dir='ltr'] .outlineWithDeepNesting > .outlineItem,
        html[dir='ltr'] .outlineItem > .outlineItems {
            margin-left: 20px;
        }

        html[dir='rtl'] .outlineWithDeepNesting > .outlineItem,
        html[dir='rtl'] .outlineItem > .outlineItems {
            margin-right: 20px;
        }

        .outlineItem > a,
        .attachmentsItem > button {
            text-decoration: none;
            display: inline-block;
            min-width: 95%;
            min-width: calc(100% - 4px);
            /* Subtract the right padding (left, in RTL mode)
                                            of the container. */
            height: auto;
            margin-bottom: 1px;
            border-radius: 2px;
            color: hsla(0, 0%, 100%, .8);
            font-size: 13px;
            line-height: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            white-space: normal;
        }

        .attachmentsItem > button {
            border: 0 none;
            background: none;
            cursor: pointer;
            width: 100%;
        }

        html[dir='ltr'] .outlineItem > a {
            padding: 2px 0 5px 4px;
        }

        html[dir='ltr'] .attachmentsItem > button {
            padding: 2px 0 3px 7px;
            text-align: left;
        }

        html[dir='rtl'] .outlineItem > a {
            padding: 2px 4px 5px 0;
        }

        html[dir='rtl'] .attachmentsItem > button {
            padding: 2px 7px 3px 0;
            text-align: right;
        }

        .outlineItemToggler {
            position: relative;
            height: 0;
            width: 0;
            color: hsla(0, 0%, 100%, .5);
        }

        .outlineItemToggler::before {
            content: url(images/treeitem-expanded.png);
            display: inline-block;
            position: absolute;
        }

        html[dir='ltr'] .outlineItemToggler.outlineItemsHidden::before {
            content: url(images/treeitem-collapsed.png);
        }

        html[dir='rtl'] .outlineItemToggler.outlineItemsHidden::before {
            content: url(images/treeitem-collapsed-rtl.png);
        }

        .outlineItemToggler.outlineItemsHidden ~ .outlineItems {
            display: none;
        }

        html[dir='ltr'] .outlineItemToggler {
            float: left;
        }

        html[dir='rtl'] .outlineItemToggler {
            float: right;
        }

        html[dir='ltr'] .outlineItemToggler::before {
            right: 4px;
        }

        html[dir='rtl'] .outlineItemToggler::before {
            left: 4px;
        }

        .outlineItemToggler:hover,
        .outlineItemToggler:hover + a,
        .outlineItemToggler:hover ~ .outlineItems,
        .outlineItem > a:hover,
        .attachmentsItem > button:hover {
            background-color: hsla(0, 0%, 100%, .02);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .2) inset,
            0 0 1px hsla(0, 0%, 0%, .2);
            border-radius: 2px;
            color: hsla(0, 0%, 100%, .9);
        }

        .outlineItem.selected {
            background-color: hsla(0, 0%, 100%, .08);
            background-image: linear-gradient(hsla(0, 0%, 100%, .05), hsla(0, 0%, 100%, 0));
            background-clip: padding-box;
            box-shadow: 0 1px 0 hsla(0, 0%, 100%, .05) inset,
            0 0 1px hsla(0, 0%, 100%, .1) inset,
            0 0 1px hsla(0, 0%, 0%, .2);
            color: hsla(0, 0%, 100%, 1);
        }

        .noResults {
            font-size: 12px;
            color: hsla(0, 0%, 100%, .8);
            font-style: italic;
            cursor: default;
        }

        /* TODO: file FF bug to support ::-moz-selection:window-inactive
        so we can override the opaque grey background when the window is inactive;
        see https://bugzilla.mozilla.org/show_bug.cgi?id=706209 */
        ::-moz-selection {
            background: rgba(0, 0, 255, 0.3);
        }

        ::selection {
            background: rgba(0, 0, 255, 0.3);
        }

        #errorWrapper {
            background: none repeat scroll 0 0 #FF5555;
            color: white;
            left: 0;
            position: absolute;
            right: 0;
            z-index: 1000;
            padding: 3px;
            font-size: 0.8em;
        }

        .loadingInProgress #errorWrapper {
            top: 37px;
        }

        #errorMessageLeft {
            float: left;
        }

        #errorMessageRight {
            float: right;
        }

        #errorMoreInfo {
            background-color: #FFFFFF;
            color: black;
            padding: 3px;
            margin: 3px;
            width: 98%;
        }

        .overlayButton {
            width: auto;
            margin: 3px 4px 2px 4px !important;
            padding: 2px 6px 3px 6px;
        }

        #overlayContainer {
            display: table;
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: hsla(0, 0%, 0%, .2);
            z-index: 40000;
        }

        #overlayContainer > * {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }

        #overlayContainer > .container {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        #overlayContainer > .container > .dialog {
            display: inline-block;
            padding: 15px;
            border-spacing: 4px;
            color: hsl(0, 0%, 85%);
            font-size: 12px;
            line-height: 14px;
            background-color: #474747; /* fallback */
            background-image: url(images/texture.png),
            linear-gradient(hsla(0, 0%, 32%, .99), hsla(0, 0%, 27%, .95));
            box-shadow: inset 1px 0 0 hsla(0, 0%, 100%, .08),
            inset 0 1px 1px hsla(0, 0%, 0%, .15),
            inset 0 -1px 0 hsla(0, 0%, 100%, .05),
            0 1px 0 hsla(0, 0%, 0%, .15),
            0 1px 1px hsla(0, 0%, 0%, .1);
            border: 1px solid hsla(0, 0%, 0%, .5);
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        }

        .dialog > .row {
            display: table-row;
        }

        .dialog > .row > * {
            display: table-cell;
        }

        .dialog .toolbarField {
            margin: 5px 0;
        }

        .dialog .separator {
            display: block;
            margin: 4px 0 4px 0;
            height: 1px;
            width: 100%;
            background-color: hsla(0, 0%, 0%, .5);
            box-shadow: 0 0 0 1px hsla(0, 0%, 100%, .08);
        }

        .dialog .buttonRow {
            text-align: center;
            vertical-align: middle;
        }

        .dialog :link {
            color: white;
        }

        #passwordOverlay > .dialog {
            text-align: center;
        }

        #passwordOverlay .toolbarField {
            width: 200px;
        }

        #documentPropertiesOverlay > .dialog {
            text-align: left;
        }

        #documentPropertiesOverlay .row > * {
            min-width: 100px;
        }

        html[dir='ltr'] #documentPropertiesOverlay .row > * {
            text-align: left;
        }

        html[dir='rtl'] #documentPropertiesOverlay .row > * {
            text-align: right;
        }

        #documentPropertiesOverlay .row > span {
            width: 125px;
            word-wrap: break-word;
        }

        #documentPropertiesOverlay .row > p {
            max-width: 225px;
            word-wrap: break-word;
        }

        #documentPropertiesOverlay .buttonRow {
            margin-top: 10px;
        }

        .clearBoth {
            clear: both;
        }

        .fileInput {
            background: white;
            color: black;
            margin-top: 5px;
            visibility: hidden;
            position: fixed;
            right: 0;
            top: 0;
        }

        #PDFBug {
            background: none repeat scroll 0 0 white;
            border: 1px solid #666666;
            position: fixed;
            top: 32px;
            right: 0;
            bottom: 0;
            font-size: 10px;
            padding: 0;
            width: 300px;
        }

        #PDFBug .controls {
            background: #EEEEEE;
            border-bottom: 1px solid #666666;
            padding: 3px;
        }

        #PDFBug .panels {
            bottom: 0;
            left: 0;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            position: absolute;
            right: 0;
            top: 27px;
        }

        #PDFBug button.active {
            font-weight: bold;
        }

        .debuggerShowText {
            background: none repeat scroll 0 0 yellow;
            color: blue;
        }

        .debuggerHideText:hover {
            background: none repeat scroll 0 0 yellow;
        }

        #PDFBug .stats {
            font-family: courier;
            font-size: 10px;
            white-space: pre;
        }

        #PDFBug .stats .title {
            font-weight: bold;
        }

        #PDFBug table {
            font-size: 10px;
        }

        #viewer.textLayer-visible .textLayer {
            opacity: 1.0;
        }

        #viewer.textLayer-visible .canvasWrapper {
            background-color: rgb(128, 255, 128);
        }

        #viewer.textLayer-visible .canvasWrapper canvas {
            mix-blend-mode: screen;
        }

        #viewer.textLayer-visible .textLayer > span {
            background-color: rgba(255, 255, 0, 0.1);
            color: black;
            border: solid 1px rgba(255, 0, 0, 0.5);
            box-sizing: border-box;
        }

        #viewer.textLayer-hover .textLayer > span:hover {
            background-color: white;
            color: black;
        }

        #viewer.textLayer-shadow .textLayer > span {
            background-color: rgba(255, 255, 255, .6);
            color: black;
        }

        .grab-to-pan-grab {
            cursor: url("images/grab.cur"), move !important;
            cursor: -webkit-grab !important;
            cursor: grab !important;
        }

        .grab-to-pan-grab *:not(input):not(textarea):not(button):not(select):not(:link) {
            cursor: inherit !important;
        }

        .grab-to-pan-grab:active,
        .grab-to-pan-grabbing {
            cursor: url("images/grabbing.cur"), move !important;
            cursor: -webkit-grabbing !important;
            cursor: grabbing !important;

            position: fixed;
            background: transparent;
            display: block;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: 50000; /* should be higher than anything else in PDF.js! */
        }

        @page {
            margin: 0;
        }

        #printContainer {
            display: none;
        }

        @media screen and (-webkit-min-device-pixel-ratio: 1.1), screen and (min-resolution: 1.1dppx) {
            /* Rules for Retina screens */
            .toolbarButton::before {
                -webkit-transform: scale(0.5);
                transform: scale(0.5);
                top: -5px;
            }

            .secondaryToolbarButton::before {
                -webkit-transform: scale(0.5);
                transform: scale(0.5);
                top: -4px;
            }

            html[dir='ltr'] .toolbarButton::before,
            html[dir='rtl'] .toolbarButton::before {
                left: -1px;
            }

            html[dir='ltr'] .secondaryToolbarButton::before {
                left: -2px;
            }

            html[dir='rtl'] .secondaryToolbarButton::before {
                left: 186px;
            }

            .toolbarField.pageNumber.visiblePageIsLoading,
            #findInput[data-status="pending"] {
                background-image: url(images/loading-small@2x.png);
                background-size: 16px 17px;
            }

            .dropdownToolbarButton {
                background: url(images/toolbarButton-menuArrows@2x.png) no-repeat;
                background-size: 7px 16px;
            }

            html[dir='ltr'] .toolbarButton#sidebarToggle::before {
                content: url(images/toolbarButton-sidebarToggle@2x.png);
            }

            html[dir='rtl'] .toolbarButton#sidebarToggle::before {
                content: url(images/toolbarButton-sidebarToggle-rtl@2x.png);
            }

            html[dir='ltr'] .toolbarButton#secondaryToolbarToggle::before {
                content: url(images/toolbarButton-secondaryToolbarToggle@2x.png);
            }

            html[dir='rtl'] .toolbarButton#secondaryToolbarToggle::before {
                content: url(images/toolbarButton-secondaryToolbarToggle-rtl@2x.png);
            }

            html[dir='ltr'] .toolbarButton.findPrevious::before {
                content: url(images/findbarButton-previous@2x.png);
            }

            html[dir='rtl'] .toolbarButton.findPrevious::before {
                content: url(images/findbarButton-previous-rtl@2x.png);
            }

            html[dir='ltr'] .toolbarButton.findNext::before {
                content: url(images/findbarButton-next@2x.png);
            }

            html[dir='rtl'] .toolbarButton.findNext::before {
                content: url(images/findbarButton-next-rtl@2x.png);
            }

            html[dir='ltr'] .toolbarButton.pageUp::before {
                content: url(images/toolbarButton-pageUp@2x.png);
            }

            html[dir='rtl'] .toolbarButton.pageUp::before {
                content: url(images/toolbarButton-pageUp-rtl@2x.png);
            }

            html[dir='ltr'] .toolbarButton.pageDown::before {
                content: url(images/toolbarButton-pageDown@2x.png);
            }

            html[dir='rtl'] .toolbarButton.pageDown::before {
                content: url(images/toolbarButton-pageDown-rtl@2x.png);
            }

            .toolbarButton.zoomIn::before {
                content: url(images/toolbarButton-zoomIn@2x.png);
            }

            .toolbarButton.zoomOut::before {
                content: url(images/toolbarButton-zoomOut@2x.png);
            }

            .toolbarButton.presentationMode::before,
            .secondaryToolbarButton.presentationMode::before {
                content: url(images/toolbarButton-presentationMode@2x.png);
            }

            .toolbarButton.print::before,
            .secondaryToolbarButton.print::before {
                content: url(images/toolbarButton-print@2x.png);
            }

            .toolbarButton.openFile::before,
            .secondaryToolbarButton.openFile::before {
                content: url(images/toolbarButton-openFile@2x.png);
            }

            .toolbarButton.download::before,
            .secondaryToolbarButton.download::before {
                content: url(images/toolbarButton-download@2x.png);
            }

            .toolbarButton.bookmark::before,
            .secondaryToolbarButton.bookmark::before {
                content: url(images/toolbarButton-bookmark@2x.png);
            }

            #viewThumbnail.toolbarButton::before {
                content: url(images/toolbarButton-viewThumbnail@2x.png);
            }

            html[dir="ltr"] #viewOutline.toolbarButton::before {
                content: url(images/toolbarButton-viewOutline@2x.png);
            }

            html[dir="rtl"] #viewOutline.toolbarButton::before {
                content: url(images/toolbarButton-viewOutline-rtl@2x.png);
            }

            #viewAttachments.toolbarButton::before {
                content: url(images/toolbarButton-viewAttachments@2x.png);
            }

            #viewFind.toolbarButton::before {
                content: url(images/toolbarButton-search@2x.png);
            }

            .secondaryToolbarButton.firstPage::before {
                content: url(images/secondaryToolbarButton-firstPage@2x.png);
            }

            .secondaryToolbarButton.lastPage::before {
                content: url(images/secondaryToolbarButton-lastPage@2x.png);
            }

            .secondaryToolbarButton.rotateCcw::before {
                content: url(images/secondaryToolbarButton-rotateCcw@2x.png);
            }

            .secondaryToolbarButton.rotateCw::before {
                content: url(images/secondaryToolbarButton-rotateCw@2x.png);
            }

            .secondaryToolbarButton.selectTool::before {
                content: url(images/secondaryToolbarButton-selectTool@2x.png);
            }

            .secondaryToolbarButton.handTool::before {
                content: url(images/secondaryToolbarButton-handTool@2x.png);
            }

            .secondaryToolbarButton.scrollVertical::before {
                content: url(images/secondaryToolbarButton-scrollVertical@2x.png);
            }

            .secondaryToolbarButton.scrollHorizontal::before {
                content: url(images/secondaryToolbarButton-scrollHorizontal@2x.png);
            }

            .secondaryToolbarButton.scrollWrapped::before {
                content: url(images/secondaryToolbarButton-scrollWrapped@2x.png);
            }

            .secondaryToolbarButton.spreadNone::before {
                content: url(images/secondaryToolbarButton-spreadNone@2x.png);
            }

            .secondaryToolbarButton.spreadOdd::before {
                content: url(images/secondaryToolbarButton-spreadOdd@2x.png);
            }

            .secondaryToolbarButton.spreadEven::before {
                content: url(images/secondaryToolbarButton-spreadEven@2x.png);
            }

            .secondaryToolbarButton.documentProperties::before {
                content: url(images/secondaryToolbarButton-documentProperties@2x.png);
            }

            .outlineItemToggler::before {
                -webkit-transform: scale(0.5);
                transform: scale(0.5);
                top: -1px;
                content: url(images/treeitem-expanded@2x.png);
            }

            html[dir='ltr'] .outlineItemToggler.outlineItemsHidden::before {
                content: url(images/treeitem-collapsed@2x.png);
            }

            html[dir='rtl'] .outlineItemToggler.outlineItemsHidden::before {
                content: url(images/treeitem-collapsed-rtl@2x.png);
            }

            html[dir='ltr'] .outlineItemToggler::before {
                right: 0;
            }

            html[dir='rtl'] .outlineItemToggler::before {
                left: 0;
            }
        }

        @media print {
            /* General rules for printing. */
            body {
                background: transparent none;
            }

            /* Rules for browsers that don't support mozPrintCallback. */
            #sidebarContainer, #secondaryToolbar, .toolbar, #loadingBox, #errorWrapper, .textLayer {
                display: none;
            }

            #viewerContainer {
                overflow: visible;
            }

            #mainContainer, #viewerContainer, .page, .page canvas {
                position: static;
                padding: 0;
                margin: 0;
            }

            .page {
                float: left;
                display: none;
                border: none;
                box-shadow: none;
                background-clip: content-box;
                background-color: white;
            }

            .page[data-loaded] {
                display: block;
            }

            .fileInput {
                display: none;
            }

            /* Rules for browsers that support PDF.js printing */
            body[data-pdfjsprinting] #outerContainer {
                display: none;
            }

            body[data-pdfjsprinting] #printContainer {
                display: block;
            }

            #printContainer {
                height: 100%;
            }

            /* wrapper around (scaled) print canvas elements */
            #printContainer > div {
                position: relative;
                top: 0;
                left: 0;
                width: 1px;
                height: 1px;
                overflow: visible;
                page-break-after: always;
                page-break-inside: avoid;
            }

            #printContainer canvas,
            #printContainer img {
                display: block;
            }
        }

        .visibleLargeView,
        .visibleMediumView,
        .visibleSmallView {
            display: none;
        }

        @media all and (max-width: 900px) {
            #toolbarViewerMiddle {
                display: table;
                margin: auto;
                left: auto;
                position: inherit;
                -webkit-transform: none;
                transform: none;
            }
        }

        @media all and (max-width: 840px) {
            #sidebarContent {
                background-color: hsla(0, 0%, 0%, .7);
            }

            html[dir='ltr'] #outerContainer.sidebarOpen #viewerContainer {
                left: 0px !important;
            }

            html[dir='rtl'] #outerContainer.sidebarOpen #viewerContainer {
                right: 0px !important;
            }

            #outerContainer .hiddenLargeView,
            #outerContainer .hiddenMediumView {
                display: inherit;
            }

            #outerContainer .visibleLargeView,
            #outerContainer .visibleMediumView {
                display: none;
            }
        }

        @media all and (max-width: 770px) {
            #outerContainer .hiddenLargeView {
                display: none;
            }

            #outerContainer .visibleLargeView {
                display: inherit;
            }
        }

        @media all and (max-width: 700px) {
            #outerContainer .hiddenMediumView {
                display: none;
            }

            #outerContainer .visibleMediumView {
                display: inherit;
            }
        }

        @media all and (max-width: 640px) {
            .hiddenSmallView, .hiddenSmallView * {
                display: none;
            }

            .visibleSmallView {
                display: inherit;
            }

            .toolbarButtonSpacer {
                width: 0;
            }

            html[dir='ltr'] .findbar {
                left: 38px;
            }

            html[dir='rtl'] .findbar {
                right: 38px;
            }
        }

        @media all and (max-width: 535px) {
            #scaleSelectContainer {
                display: none;
            }
        }


        /*Disable Certain Function*/
        .toolbarButton.openFile, .secondaryToolbarButton.openFile,
        .toolbarButton.print, .secondaryToolbarButton.print,
        .toolbarButton.download, .secondaryToolbarButton.download,
        .toolbarButton.presentationMode, .secondaryToolbarButton.presentationMode,
        .toolbarButton.bookmark, .secondaryToolbarButton.bookmark,
        #secondaryToolbar, #secondaryToolbarToggle, #viewAttachments {
            display: none;
        }

        @media print {
            html, body {
                display: none; /* hide whole page */
            }
        }


        /*Custom Colouring*/
        #viewFind {
            background: linear-gradient(#f79800, #c97c00);
        }

        #viewFind.toolbarButton::before {
            -webkit-transform: scale(1.25);
            transform: scale(1.25);
        }

        #findInput {
            background: #fff;
            color: #000;
        }

        #findbar {
            background: #f79800;
            padding: 0;
            color: #000;
        }

        #findbar .toolbarLabel {
            color: #fff;
        }

        #findbar:after {
            border-bottom-color: #f79800;
        }

        #findbarInstructionContainer {
            float: left;
            clear: both;
            background: #007fc3;
            margin-top: 1px;
            padding: 10px;
            height: auto;
            width: 100%;
            box-sizing: border-box;
        }

        #findInstruction {
            color: #fff;
            font-weight: 700;
            font-style: italic;
        }

        #findbarOptionsOneContainer, #findbarInputContainer {
            padding: 5px 10px;
        }

        #findResultsCount {
            background: transparent;
        }
    </style>    
    <script>

        window.addEventListener('load', (event) => {

            setTimeout(() => {
                
                const delete_temp_file = async () => {

                    try {
                        
                        const temp_file_name = document.getElementById('temp_file_name');

                        let options = {
                            method : "POST",
                            headers : {
                                "Content-Type" : "application/json",                        
                            },
                            body: JSON.stringify({
                                target        : "files-delete_temp_file",
                                session_token : localStorage.session_token,
                                user_id       : localStorage.user_id,
                                temp_file_name: temp_file_name.value
                            })
                        };

                        const server_response = await fetch("<?php echo $arch_api_url; ?>", options );
                        
                        const json = await server_response.json();
                        console.log(json);
                        switch (server_response.status) {
                            case 200: 
                                console.log('SERVER OK');
                                break;
                            default: break;
                        }
                    } catch (error) {
                        console.log(error);
                    }
                };
                <?php // eliminamos el archivo temporal despues de descargarlo ?>
                delete_temp_file();

            }, 10000);

        });
    </script>
</html>
<?php endif; ?>