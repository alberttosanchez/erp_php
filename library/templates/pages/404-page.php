<!DOCTYPE html>
<html lang="es">
    <head>        
        <title>IJOVEN - Pagina No Encontrada</title>
    </head>
    <body>
        <section id="not_found_section" class="not_found_section">

            <div id="NotFound" class="NotFound">
                <div class="NotFound__wrapper">
                    <h1>Información<br/> No encontrada</h1>
                    <p><a href="/">Volver al Inicio</a></p>
                    <noscript>
                        <p>Utilice un navegador compatible con Javascript.</p>
                    </noscript>
                </div>
            </div>

        </section>
        <style>
            body{
                margin: 0;
                font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: transparent;
                background-image: url(<?php echo '/';?>src/assets/images/ijoven-background3.png);
            }            
            div#NotFound {
                display: flex;
                width: 100vw;
                height: 100vh;
                justify-content: center;
                align-items: center;
            }

            .NotFound__wrapper {
                width: 100%;
                max-width: 400px;
                height: 200px;
                background-color: rgba(255,255,255,0.7);
                border: 2px solid blue;
                padding: 20px;
            }

            @media screen and (max-width:768px){
                .NotFound__wrapper > h1 {
                    font-size: 2em;
                }
            }
        </style>
    </body>
</html>