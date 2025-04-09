<style>
   .reports_wrapper label{
        font-size: 12px;
        font-weight: bold;
   }
   .report_date_picker_box{
       width: 100%;
       /* max-width: calc(50% - 5px); */
   }
   .rp_bars_graphic_wrapper {
        display: flex;
        flex-wrap: wrap;        
        justify-content: space-around;
        margin: 10px 10px 20px 10px;
    }
    .rp_vertical_graphic_bar_box,
    .rp_horizontal_graphic_bar_box {
        width: 100%;
        max-width: calc(50% - 5px);
    }
    .bar_box{
        width: 100%;
        max-width: 450px;        
    }
    .rp_pie_graphic_group_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .rp_bars_graphic_wrapper > div {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        align-self: center;
    }
    .rp_pie_graphic_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 100%;
        max-width: calc(90% - 5px);
        margin: auto;
    }

    .report_date_picker_box {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .rp_date_picker_input_box.form-group {
        width: 100%;
        max-width: 50%;
        padding: 0 5px;
    }
    .rp_print_button_box.form-group {
        display: flex;
        flex-direction: column-reverse;
    }
    .rp_pie_graphic_wrapper > div {
        width: 100%;
        max-width: 25%;
        align-items: center;
        align-self: center;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .rp_pie_graphic_wrapper .pie_box{
        margin: 10px;
        width: 100%;
        max-width: 180px;
    }
    .rp_pie_graphic_wrapper .pie_box > span{
        padding: 10px;
        font-weight: bold;
    }
    .rp_form {
        width: 100%;
        display: flex;
    }
</style>
<div id="cv_reports" class="cv_reports_content content">

    <h3>Reportes</h3>

    <div id="reports_wrapper form-group" class="reports_wrapper form-group">

        <div class="report_date_picker_box form-group">

            <form id="rp_form" class="rp_form" action="" onsubmit="return false" method="post">
                <div class="rp_date_picker_input_box form-group">
                    <label for="rp_date_picker_input">Selecciona el Período</label>
                    <input type="text" class="form-control" id="rp_date_picker_input" name="rp_date_picker_input" maxlength="100"/>
                </div>

                <script>
                    $('#rp_date_picker_input').daterangepicker({
                        "locale": {
                        "format": "DD/MM/YYYY",                        
                        "daysOfWeek": [
                            "Do",
                            "Lu",
                            "Ma",
                            "Mi",
                            "Ju",
                            "Vi",
                            "Sa"
                        ],
                        "monthNames": [
                            "Enero",
                            "Febrero",
                            "Marzo",
                            "Abril",
                            "Mayo",
                            "Junio",
                            "Julio",
                            "Agosto",
                            "Septiembre",
                            "Octubre",
                            "Noviembre",
                            "Diciembre"
                        ]},
                        "firstDay": 0,
                        ranges   : {
                            'Hoy'               : [moment(), moment()],
                            'Ayer'              : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ultimos 7 Dias'    : [moment().subtract(6, 'days'), moment()],
                            'Ultimos 30 Dias'   : [moment().subtract(29, 'days'), moment()],
                            'Este Mes'          : [moment().startOf('month'), moment().endOf('month')],
                            'Ultimo Mes'        : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment(),
                        endDate  : moment()
                        },function (start, end) {                            
                            
                            let start_date = start.format('YYYY-MM-DD');
                            let end_date = end.format('YYYY-MM-DD');
                            //# console.log(start_date + ' - ' + end_date);
                            //# handle_ht_visit_modal_date_range_picker(start_date,end_date);
                        }
                    );

                    $('#rp_date_picker_input').on('apply.daterangepicker', function(ev, picker) {
                        let start_date = picker.startDate.format('YYYY-MM-DD');
                        let end_date = picker.endDate.format('YYYY-MM-DD');
                        //#console.log(start_date + ' - ' + end_date);
                        handle_rp_date_range_picker(start_date,end_date);                        
                    });
                </script>

                <div class="rp_date_picker_input_box form-group">
                    <label for="rp_date_picker_input">Selecciona Reporte</label>
                    <select name="" class="form-control" id="" value="">
                        <option value="0">--</option>
                        <option value="1">Visitas Por Periodo</option>
                        <option value="2">Visitas Por Departamento</option>
                        <option value="3">Visitas Por Sexo Biológico</option>
                        <option value="4">Visitas Por Porte de Armas</option>
                        <option value="5">Visitas Por Nivel de Acceso</option>
                        <option value="6">Visitas Por Motivo</option>
                    </select>
                </div>

                <div class="rp_print_button_box form-group">
                    <!-- Button trigger modal -->
                    <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#report_modal_wrapper"><i class="fa fa-print"></i></button>                    

                    <?php include_once( CV_TEMPLATE_MODALS . '/reports-modal.php'); ?>

                </div>

            </form>
        </div>

        <br>

        <div class="rp_graphic_group_wrapper form-group">

            <div class="rp_bars_graphic_wrapper form-group">

                <?php include_once( CV_TEMPLATE_PARTS . '/vertical-graphic-bars-part.php'); ?>
        
                <?php include_once( CV_TEMPLATE_PARTS . '/horizontal-graphic-bars-part.php'); ?>
    
            </div>
    
            <div class="rp_pie_graphic_group_wrapper form-group">
    
                <div class="rp_pie_graphic_wrapper">
    
                    <?php include_once( CV_TEMPLATE_PARTS . '/sex-graphic-doughnut-part.php'); ?>                    
        
                    <?php include_once( CV_TEMPLATE_PARTS . '/gun-graphic-pie-part.php'); ?>                                                

                    <?php include_once( CV_TEMPLATE_PARTS . '/level-access-graphic-doughnut-part.php'); ?>                    

                    <?php include_once( CV_TEMPLATE_PARTS . '/visit-type-graphic-pie-part.php'); ?>
    
                </div>
    
            </div>

        </div>

    </div>

</div>

<script>    
    handle_rp_date_range_picker = (start_date,end_date) => {
        

        console.log('handle_rp_date_range_picker');

        let id_visitant =   localStorage.ht_visit_current_visitant;

        localStorage.ht_visit_modal_filter = JSON.stringify({                
            filter          :   'id_visitant',
            keyword         :   id_visitant,
            filter_between  :   'started_at',
            array_between   :   [ start_date , end_date ],
        });
        
        setTimeout(() => {
            //# console.log(localStorage.ht_visit_modal_filter);
            get_ht_visit_visitant_history();
        }, 100);

    }
</script>