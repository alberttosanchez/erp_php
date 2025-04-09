<link rel="stylesheet" href="<?php echo ARCH_ASSETS_DIRECTORY . '/css/arch-new-section-part-style.css';?>">
<section id="arch_new" class="landing arch_new_section section">

    <?php include_once( ARCH_TEMPLATE_MODALS . '/arch-new-profile-picture-modal.php'); ?>

    <div class="arch_new_wrapper">

        <div class="section_title_wrap">

            <div class="section_title_box box">
                <h1><span class="title_icon"><i class="fas fa-plus-circle"></i> NUEVA ENTRADA</h1></span>
            </div>
            
        </div>
        
        <form id="arch_new_arch_form" class="arch_new_arch_form form-group" action="/politicas-procedimientos-y-manuales/arch_new" method="post" enctype='multipart/form-data'>

            <div class="form-group d-flex w-100 flex-wrap">

                <!-- Datos de Personales -->
                <div class="form-group col-md-10 col-12">

                    <div class="form-group col-12">
                        <span class="form-control blue_title">TITULO Y DESCRIPCION DE LA ENTRADA</span>
                        <input id="post_title" name="post_title" class="form-control" type="text" placeholder="Titulo">
                    </div>

                    <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                        <div class="form-group col-12">
                            <textarea id="post_description" name="post_description" class="arch_textarea form-control" type="text" placeholder="Descripción del Post" maxlegth="200"></textarea>                                
                        </div>

                    </div>

                    <!-- Archivos Adjuntos  -->
                    <div class="form-group col-12 d-flex justify-content-between flex-wrap">
        
                        <div class="form-group col-12">
                            <span class="form-control blue_title">Archivos Adjuntos</span>                        
                        </div>
        
                        
                            <label id="drop_zone_label" for="drop_zone" class="drop_zone_label form-group col-12 d-flex justify-content-between flex-wrap">
                                <span class="drop_text">Presione o arrastre aqui los archivos a subir</span>                    
                                <input 
                                    type="file" 
                                    id="drop_zone" 
                                    name="drop_zone[]" 
                                    class="input_drop_zone no-show" 
                                    accept="application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                                    multiple
                                />
                            </label>
                       
                    </div>        
                    
                    <!-- Datos de los Archivos  -->
                    <div class="form-group col-12 d-flex justify-content-between flex-wrap">
                        
                        <div class="form-group col-12 d-flex justify-content-between flex-wrap">

                            <div id="arch_new_file_list" class="arch_new_file_list">
                                <div role="table" id="arch_new_file_table" class="arch_new_file_table no-show table table-striped table-hover table-responsive">
                                    <div class="thead">
                                        <div class="thead_tr">
                                            <span>No.</span>
                                            <span>Nombre del Archivo</span>
                                            <span>Acción</span>
                                        </div>
                                    </div>
                                    <div class="tbody"></div>
                                </div>
                            </div>

                            <!-- <button class="btn btn-success btn_arch_new_register" type="submit">
                                <span id="submit_text_btn">Postear</span>                        
                                <div id="arch_spinner_box" class="arch_spinner_box no-show">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </button> -->
                        </div>                
                    </div>
                    
                </div>

                <!-- Ajustes  -->
                <div class="form-group col-md-2 col-12" style="padding-left: 10px">
                    
                    <div class="form-group col-12">

                        <div class="form-group">
                            <span class="form-control dark_blue_title">AJUSTES</span>
                            <div class="form-group check_element_box">                
                                <span class="">Publicar</span>
                                <label for="publicar">
                                    <span class="swite_btn selected"></span>
                                    <input type="checkbox" id="publicar" name="publicar" class="no-show" checked>
                                </label>                                            
                            </div>
                        </div>

                        <div class="form-group categories_box">
                            <span>Categoría</span>
                            <select class="arch_select" name="post_categories" id="post_categories" value="">
                                <optgroup label="Categorías"></optgroup>
                                <option key="" level="lv0" id_lv="0" value="(sin categoría)">(sin categoría)</option>
                            </select>
                        </div>
                        
                        <div class="form-group categories_box">
                            <span>Agregar Categoría</span>
                            <input type="text" id="arch_new_category" class="arch_input_text arch_new_category" value="" placeholder="Escriba una categoría">
                            <button type="button" id="arch_new_category_btn" class="arch_button arch_new_category_btn">                                
                                <span id="submit_category_text_btn" class="">Agregar</span>
                                <div id="arch_category_spinner_box" class="arch_category_spinner_box spinner-button-box no-show">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div class="form-group categories_text_box">
                            <p>Seleccione una categoría antes de añadir una nueva. La nueva categoría quedará por debajo de la categoría seleccionada.</p>
                        </div>

                        <div class="form-group categories_textarea_box">
                            <span>Descripción Corta</span>
                            <textarea class="arch_textarea post_excerpt" name="post_excerpt" id="post_excerpt" cols="30" rows="10" placeholder="Escribe una descripción"></textarea>
                        </div>

                    </div>

                    <button class="btn btn-success btn_arch_new_register" type="submit">
                        <span id="submit_text_btn">Postear</span>                        
                        <div id="arch_spinner_box" class="arch_spinner_box no-show">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </button>

                    <script>
                        const swite_btn = document.querySelectorAll('.swite_btn');
                            swite_btn.forEach(element => {
                                element.addEventListener('click', (ev)=>{                                    
                                    let check_element = ev.target.parentElement.children[1];
                                    
                                    ev.target.classList.toggle('selected');
                                                                        
                                    if ( check_element.value == 'on' )
                                    {
                                        check_element.value = 'off';                                        
                                    }
                                    else
                                    {
                                        check_element.value = 'on';                                        
                                    }                                    
                                });                                
                            });
                    </script>
                </div>

            </div>

            <input type="hidden" id="form_id" name="form_id" value="<?php echo session_id(); ?>">

        </form>

    </div>
    
        
    
    <script type="module" src="<?php echo ARCH_SCRIPTS_DIRECTORY . '/arch-new-post-module.js';?>"></script>
</section>
