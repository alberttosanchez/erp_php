<div id="cv_dashboard" class="cv_dashboard_content content">

    <h3>DashBoard</h3>

    <div id="cv_dashboard_wrapper" class="cv_dashboard_wrapper">

        <div class="d-flex justify-content-between">

            <div style="min-width:333px;padding:0 20px">
                
                <div id="cv_dashboard_clock" class="cv_dashboard_clock">
                    <canvas id="canvas" width="200" height="200" style="display:block;margin:auto"></canvas>
                    <script src="<?=CV_ASSETS_DIRECTORY . '/js/canvas-clock.js';?>"></script>
                </div>

                <div id="cv_dashboard_calendar" class="cv_dashboard_calendar">
                    <link rel="stylesheet" href="<?=CV_ASSETS_DIRECTORY . '/css/jsCalendar.css';?>">
                    <!-- jsCalendar -->
                    <div class="auto-jsCalendar classic-theme red" data-language="es"></div>
                    <script src="<?=CV_ASSETS_DIRECTORY . '/js/jsCalendar.lang.es.js';?>"></script>
                    <script src="<?=CV_ASSETS_DIRECTORY . '/js/jsCalendar.js';?>"></script>
                </div>

            </div>
            
            <div class="d-flex flex-column" style="width:100%;padding:0 20px;">

                <h4>VISITANTES DEL DIA</h4>
    
                <div id="cv_dashboard_table_resume" class="cv_dashboard_table_resume" style="height:500px;overflow:scroll;">
    
                    <table class="table table-responsible table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Visitante</th>
                                <th>Cédula</th>
                                <th>Ubicación</th>
                                <th>Contacto</th>
                                <th>Hora Llegada</th>
                                <th>Hora Salida</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php /*
                            <!-- <tr key="3">
                                <td>Román Perez</td>
                                <td>001-0014521-1</td>
                                <td>DTIC - 1er Piso</td>
                                <td>Keifre Figuereo</td>
                                <td>12:12:25 pm</td>
                                <td>-</td>
                            </tr>   --> 
                            */ ?>
                        </tbody>
                    </table>

                    <input type="hidden" id="dasboard_form_id" name="dasboard_form_id" value="<?php echo session_id(); ?>">
                </div>

            </div>

        </div>

    </div>

</div>

<script src="<?php echo CV_SCRIPTS_DIRECTORY;?>/dashboard.js"></script>