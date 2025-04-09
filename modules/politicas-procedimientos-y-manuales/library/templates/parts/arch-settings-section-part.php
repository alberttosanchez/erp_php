<link rel="stylesheet" href="<?php echo ARCH_ASSETS_DIRECTORY . '/css/arch-settings-section-part-style.css';?>">
<section id="arch_settings" class="arch_settings arch_settings_section section">
    <div class="arch_settings_wrapper">

        <div class="section_title_wrap">

            <div class="section_title_box box">
                <h1><span class="title_icon"><i class="fas fa-tools"></i> OPCIONES GENERALES</h1></span>
            </div>

        </div>

        <hr>

        <div id="section_printer_wrap" class="section_printer_wrap"></div>

        <hr>

        <div id="section_download_wrap" class="section_download_wrap"></div>

        <hr>

        <div id="section_upload_wrap" class="section_upload_wrap"></div>

        <input type="hidden" id="form_id" name="form_id" value="<?php echo session_id(); ?>">
    </div>
    <script type="module" src="<?php echo ARCH_SCRIPTS_DIRECTORY . '/arch-settings-module.js';?>"></script>
</section>