<section id="manage_modules_section" class="manage_modules_section content" style="display: none !important;">
    <div class="modules__wrapper">
        <div class="modules_details form-group">
            <h2>ADMINISTRAR MODULOS</h2>                    
            <div class="modules_container">
                <table id="modules_table" class="modules_table table responsive table-striped" style="display:flex;flex-direction:column;">
                    <thead>
                        <tr>
                            <th style="min-width:200px;">Módulo</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>                                
                        <?php // <!-- {options} --> ?>
                    </tbody>
                </table>
            </div>                    
        </div>
        <?php require('./library/templates/contents/module-pagination-content.php'); ?>
    </div>
</section>

<section id="manage_install_modules_section" class="manage_install_modules content" style="display: none !important;">
    <div class="modulesInstall__wrapper">                    
        <form action="./manage-modules" onsubmit="return handleInstallModuleSubmit()" class="modulesInstall_details form-group" encType="multipart/form-data" method="post">
            <h2>INSTALAR MODULO</h2>
            <label class="moduleInstall_zip_label" for="module_zip">Cargar Archivo zip
                <input onclick="handleInputInstallModuleClick(this)" onchange="handleInputInstallModuleChange(this)" class="no-display" type="file" name="module_zip" id="module_zip" el-control="no" />
            </label>                    
            <div class="modulesInstall_container">                       
                <div class="progress">
                    <div id="progress-bar" bar-status="inherit" bar-width="0" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                </div>
            </div>                    
        </form>                
    </div>
    <div id="ProgressConsole" class="ProgressConsole">
        <p class="test">...</p>                
    </div>
</section>