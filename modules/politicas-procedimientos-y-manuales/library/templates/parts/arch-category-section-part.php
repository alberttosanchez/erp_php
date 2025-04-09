<link rel="stylesheet" href="<?php echo ARCH_ASSETS_DIRECTORY . '/css/arch-category-section-part-style.css';?>">
<section id="arch_category" class="arch_category_section section">
    <div id="category_wrapper" class="category_wrapper">
        
        <div class="section_title_wrap">

            <div class="section_title_box box">
                <h1><span class="title_icon"><i class="far fa-flag"></i> CONSULTAR CATEGORIAS</h1></span>
            </div>
            
        </div>

        <hr>

        <div id="arch_category_search_container" class="arch_category_search_container"></div>

        <hr>

        <div id="arch_category_table_container" class="arch_category_table_container"></div>
        
        <hr>

        <div id="arch_category_pagination" class="arch_category_pagination"></div>

        <input type="hidden" id="form_id" name="form_id" class="form_id" value="<?php echo session_id(); ?>">
        
    </div>
    
    <script type="module" src="<?php echo ARCH_SCRIPTS_DIRECTORY . '/arch-category-post-module.js';?>"></script>
</section>