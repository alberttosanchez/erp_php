<link rel="stylesheet" href="<?php echo CV_ASSETS_DIRECTORY;?>/css/plant_distribution.style.css">

<div id="cv_plant_distribution" class="cv_plant_distribution_content content">

    <?php include_once( CV_TEMPLATE_MODALS . '/edit-plant-distribution-modal.php'); ?>

    <?php include_once( CV_TEMPLATE_MODALS . '/delete-plant-distribution-modal.php'); ?>

    <h3>Distribución en Planta</h3>

    <div class="pt_dist_group_wrapper pt_dist_group_wrapper form-group">

        <div class="pt_dist_one_wrapper pt_dist_wrapper form-group">

            <div class="pt_dist_filter_box pt_dist_box form-group">
                <select name="pt_dist_filter_select" id="pt_dist_filter_select" class="pt_dist_filter_select form-control">
                    <option value="">-- Filtrar Búsqueda --</option>                    
                </select>
            </div>
    
            <div class="pt_dist_search_box pt_dist_box form-group">
                <input type="text" id="pt_dist_search_input" name="pt_dist_search_input" class="pt_dist_search_input form-control" placeholder="Palabra Clave" />
                <button type="button" id="pt_dist_search_btn" name="pt_dist_search_btn" class="pt_dist_search_btn btn-primary"><i class="fa fa-search"></i></button>
            </div>

        </div>

        <br>

        <div class="pt_dist_two_wrapper pt_dist_wrapper form-group">

            <div class="pt_dist_table_box pt_dist_box form-group">

                <table id="pt_dist_table" class="pt_dist_table table table-hover table-stripped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Departamento o área</th>
                            <th>Ubicación</th>
                            <th>Nivel de Acceso Requerido</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr key="1">
                            <td>1</td>
                            <td>DTIC</td>
                            <td>PRIMER NIVEL</td>
                            <td>A</td>                            
                            <td>
                                <button title="Ver" type="button" value="1"><i class="fa fa-eye"></i></button>
                                <button title="Editar" onclick="pt_dist_edit_info(this)"type="button" value="1"  data-bs-toggle="modal" data-bs-target="#pt_dist_edit_modal_wrapper"><i class="fa fa-edit"></i></button>
                                <button title="Eliminar" type="button" value="1" data-bs-toggle="modal" data-bs-target="#del_pt_dist_modal_wrapper"><i class="fa fa-times"></i></button>
                            </td>                            
                        </tr> -->
                        
                    </tbody>
                </table>
                
            </div>

        </div>

        <br>

        <div class="pt_dist_three_wrapper pt_dist_wrapper form-group">
        
            <h4>Distribución en Planta</h4>

            <div class="pt_dist_four_wrapper pt_dist_wrapper form-group">

                <div class="pt_dist_box_group box_group form-group">

                    <div class="pt_dist_dpto_box pt_dist_box form-group">
                        <label for="pt_dist_dpto">Departamento</label>
                        <input type="text" id="pt_dist_dpto" name="pt_dist_dpto" class="pt_dist_dpto form-control" />
                    </div>
    
                    <div class="pt_dist_location_box pt_dist_box form-group">
                        <label for="pt_dist_location">Piso</label>
                        <select name="pt_dist_location" id="pt_dist_location" class="pt_dist_location form-control">
                            <!-- <option value="">-</option> -->
                        </select>
                    </div>
    
                    <div class="pt_dist_level_access_box pt_dist_box form-group">
                        <label for="pt_dist_level_access">Nivel de Acceso Requerido</label>
                        <select name="pt_dist_level_access" id="pt_dist_level_access" class="pt_dist_level_access form-control">
                            <!-- <option value="">-</option> -->                            
                        </select>
                    </div>

                    <input type="hidden" id="pt_dist_id" value="<?php echo session_id(); ?>" name="pt_dist_id" class="pt_dist_id form-control" maxlength="100"/>

                </div>

                <div class="pt_dist_add_btn_box pt_dist_box form-group">
                    <button type="button" id="pt_dist_add_btn" name="pt_dist_add_btn" class="pt_dist_add_btn btn btn-primary">AÑADIR</button>
                    <span class="pt_dist_add_message no-show"><i>Item Agregado</i></span>
                </div>

            </div>

        </div>

    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/plant_distribution.js"></script>