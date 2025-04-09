<style>
    .ht_visit_modal .modal-dialog{
        width: calc(100% - 40px);
        max-width: 2560px;
        border: 1px solid #dbdbdb;
        background-color: white;
    }
    .cv_visit_history_report_wrapper{
        background-color: dodgerblue;
        display: flex;
        justify-content: space-between;
        padding: 5px;
    }
    .ht_table_wrapper {
        border: 1px solid #dbdbdb;
        height: 260px;
        overflow: scroll;
    }
    .cv_ht_visit_table tbody tr:hover{
        cursor: pointer;
        background-color: yellow;
    }
</style>
<!-- Modal -->
<div class="modal fade ht_visit_modal" id="ht_visit_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ht_visit_modalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div id="cv_visit_history_report_wrapper" class="cv_visit_history_report_wrapper">
                <p class="m-0" style="color:white"><b>Historial de Visitas de: <span id="title_visitant"></span></b></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
            </div>

            <div class="moda_body" style="padding:20px">

                <div class="form-group" style="max-width: calc(50% - 5px)">
                    <label for="">Selecciona el Período</label>
                    <input type="text" id="datarange_picker_input" class="datarange_picker_input form-control"/>
                </div>
                
                <script>
                    $('#datarange_picker_input').daterangepicker({
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

                    $('#datarange_picker_input').on('apply.daterangepicker', function(ev, picker) {
                        let start_date = picker.startDate.format('YYYY-MM-DD');
                        let end_date = picker.endDate.format('YYYY-MM-DD');
                        //#console.log(start_date + ' - ' + end_date);
                        handle_ht_visit_modal_date_range_picker(start_date,end_date);                        
                    });
                </script>
                <br>

                <div class="cv_modal_ht_visit_table">
                    
                    <div class="form-group ht_table_wrapper">
                        <table id="cv_ht_visit_table" class="cv_ht_visit_table table table-responsive table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha Visita</th>
                                    <th>Contacto</th>
                                    <th>Departamento</th>
                                    <th>Hora Llegada</th>
                                    <th>Hora Salida</th>
                                    <th>Duración</th>
                                    <th>Nivel de Acceso</th>
                                    <?php //<th>Acción</th> ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php /*
                                <tr key="3">
                                    <td>13-09-2021</td>
                                    <td>Keifre Figuereo</td>
                                    <td>DTIC</td>
                                    <td>10:05:32 a.m.</td>
                                    <td>11:12:05 a.m.</td>
                                    <td>01:06:27</td>
                                    <td>A</td>
                                    <td> - - - </td>
                                </tr> */
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    
                    <div class="ht_visit_details_wrapper">

                        <div class="form-group d-flex justify-content-between" >

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_visit_date">Fecha de la Visita</label>
                                <input type="text" class="form-control" id="ht_details_visit_date" name="ht_details_visit_date" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_arrive_time">Hora de Llegada</label>
                                <input type="text" class="form-control" id="ht_details_arrive_time" name="ht_details_arrive_time" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_out_time">Hora de Salida</label>
                                <input type="text" class="form-control" id="ht_details_out_time" name="ht_details_out_time" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_access_level">Nivel de Acceso</label>
                                <input type="text" class="form-control" id="ht_details_access_level" name="ht_details_access_level" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(40% - 5px)">
                                <label for="ht_details_business_contact">Contacto en la Empresa</label>
                                <input type="text" class="form-control" id="ht_details_business_contact" name="ht_details_business_contact" maxlength="100" disabled/>
                            </div>

                        </div>
                        
                        <div class="form-group d-flex justify-content-between" >

                            <div class="form-group" style="width:calc(35% - 5px)">
                                <label for="ht_details_dpto">Departamento</label>
                                <input type="text" class="form-control" id="ht_details_dpto" name="ht_details_dpto" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_has_gun">Portaba Arma de Fuego</label>
                                <input type="text" class="form-control" id="ht_details_has_gun" name="ht_details_has_gun" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_gun_license">Tipo Licencia del Arma</label>
                                <input type="text" class="form-control" id="ht_details_gun_license" name="ht_details_gun_license" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(15% - 5px)">
                                <label for="ht_details_gun_license_id">Código Licencia del Arma</label>
                                <input type="text" class="form-control" id="ht_details_gun_license_id" name="ht_details_gun_license_id" maxlength="100" disabled/>
                            </div>

                            <div class="form-group" style="width:calc(20% - 5px)">
                                <label for="ht_details_gus_status">Se entregó el Arma al Irse</label>
                                <input type="text" class="form-control" id="ht_details_gus_status" name="ht_details_gus_status" maxlength="100" disabled/>
                            </div>

                        </div>

                        <div class="form-group d-flex justify-content-between" >

                            <div class="form-group" style="width:calc(42% - 5px)">
                                <label for="ht_details_start_comments">Observaciones al Iniciar Visita</label>
                                <textarea type="text" class="form-control" id="ht_details_start_comments" name="ht_details_start_comments" maxlength="300" style="max-height:100px;min-height:62px" disabled></textarea>
                            </div>

                            <div class="form-group" style="width:calc(42% - 5px)">
                                <label for="ht_details_end_comments">Observaciones al Finalizar Visita</label>
                                <textarea type="text" class="form-control" id="ht_details_end_comments" name="ht_details_end_comments" maxlength="300" style="max-height:100px;min-height:62px" disabled></textarea>
                            </div>

                            <div class="form-group d-flex align-center" style="width:calc(16% - 5px);justify-content: center;align-items: center;margin-top: 16px;">

                                <!-- Button trigger modal -->            
                                <button id="ht_details_print_btn" class="btn btn-success ht_details_print_btn" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#ht_visit_print_modal" style="width: 100%;height: calc(100% - 8px);font-size: 25px;margin-top: 8px;" disabled><i class="fa fa-print"></i></button>
                                                                
                            </div>

                        </div>

                    </div>
                </div>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->

            </div>
        </div>
    </div>

    <script>

        $( function(){
            $('#ht_visit_modal_btn').on('click', (ev)=> {

                let ht_visit_single_tr_data_fetched = JSON.parse(localStorage.ht_visit_single_tr_data_fetched);

                ht_visit_single_tr_data_fetched.map( (item) => {
                    $('#title_visitant').text(`${item.name} ${item.last_name} - ${item.ident_number}`);
                });                

            });

            $('#ht_details_print_btn').on('click', () => render_ht_visit_details_print_single_data( localStorage.visit_key_id ) );

        });

        handle_ht_visit_modal_date_range_picker = (start_date,end_date) => {
        
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

        get_ht_visit_visitant_history = async () => {            
            
            loading();

            let body_data = JSON.stringify({                
                target          : "get_from_filter",
                table_name      : CVMJ_VIEW_VISITANT_AND_VISIT_TABLE,
                info_data       : JSON.parse(localStorage.ht_visit_modal_filter),
                selected_page   : '1',
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id
            });

            //console.log(body_data);

            try {

                let url = API_MANAGE_DB_URL;
                
                const server = await fetch( url, {
                    method: "POST",
                    headers: {                                
                        'Content-Type': 'application/json'       
                    },
                    body: body_data, 
                });

                const response = await server.json();

                //console.log(response);
                
                loading();

                switch (server.status) {
                    case 200:
                        
                                            
                        if ( response.message == "datos obtenidos" )
                        {
                            
                            localStorage.ht_visit_visitant_history_data = JSON.stringify(response.data.fetched);
                            
                            clear_ht_visit_visitant_history_table();
                            clear_ht_visit_visitant_history_details();

                            setTimeout(() => {
                                render_ht_visit_visitant_history();                                
                            }, 100);

                        }
                        else
                        {
                            localStorage.ht_visit_visitant_history_data = "";                            
                            //# clean_ht_visit_visitant_history();
                        }
                        
                        break;
                    
                    case 401:
                        window.location.href = URL_BASE+"/";   
                        break;
                
                    default:
                        actionMessage(response.message.capitalize(),'warning');
                        break;
                }
            } catch (error) {

                console.log(error);

            }
        }
        
        get_today_as_string = () => {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            return today;

        }

        render_ht_visit_visitant_history = () => {
            
            const ht_visit_visitant_history_data = JSON.parse(localStorage.ht_visit_visitant_history_data);
                            
            $.each( ht_visit_visitant_history_data, function( key, item ) {
                
                if ( item.ended_at == null )
                {
                    item.ended_at = get_today_as_string() + " 00:00:00";
                }

                //console.log(item.id_visitant);
                $('#cv_ht_visit_table tbody').append(

                    $('<tr></tr>')                                                
                        .attr({ key : item.visit_id })
                        .append( $('<td></td>').text(item.last_visit_date) )
                        //.append( $('<td></td>').text(item.cw_name +" "+item.cw_last_name) )
                        .append( $('<td></td>').text(item.cw_raw_full_name) )                        
                        //.append( $('<td></td>').text(item.department) )
                        .append( $('<td></td>').text(item.cw_raw_department) )
                        .append( $('<td></td>').text(item.started_at) )
                        .append( $('<td></td>').text(item.ended_at) )
                        .append( $('<td></td>').text( get_time_duration( item.started_at , item.ended_at ) ) )
                        .append( $('<td></td>').text(item.level_access) 
                    )

                );
                
            });                    

            setTimeout(() => {
                catch_ht_single_visit_visitant_history();
            }, 100);
            
        }
        
        catch_ht_single_visit_visitant_history = () => {
            $('#cv_ht_visit_table tbody > tr').each( (key, item) => {
                
                $(item).on('click', (ev) => {

                    let visit_key_id = ev.currentTarget.attributes.key.value;

                    localStorage.visit_key_id = visit_key_id;
                    
                    render_ht_visit_single_data( visit_key_id );
                });
            });
        }        

        render_ht_visit_single_data = ( visit_key_id ) => {
            const ht_visit_visitant_history_data = JSON.parse(localStorage.ht_visit_visitant_history_data);
            
            ht_visit_visitant_history_data.map( (item, index) =>{

                // convertimos una fecha de cadena en timestamp
                let date_started = new Date(item.started_at);                
                let date_ended = new Date(item.ended_at);

                if (visit_key_id == item.visit_id )
                {
                    $('#ht_details_visit_date').val( date_started.toLocaleDateString("es-DO") );
                    $('#ht_details_arrive_time').val( date_started.toLocaleTimeString("es-DO") );
                    $('#ht_details_out_time').val( date_ended.toLocaleTimeString("es-DO") );
                    $('#ht_details_access_level').val( item.level_access );
                    //$('#ht_details_business_contact').val( `${item.cw_name} ${item.cw_last_name}` );
                    $('#ht_details_business_contact').val( `${item.cw_raw_full_name}` );                    
                    //$('#ht_details_dpto').val( item.department );
                    $('#ht_details_dpto').val( item.cw_raw_department );
                    $('#ht_details_has_gun').val( (item.has_gun == 0) ? 'NO' : 'SI' );
                    $('#ht_details_gun_license').val( (item.has_gun == 0) ? "" : item.gun_license );
                    $('#ht_details_gun_license_id').val( item.license_number );
                    $('#ht_details_gus_status').val( item.gun_status );
                    $('#ht_details_start_comments').val( item.start_comments );
                    $('#ht_details_end_comments').val( item.end_comments );
                }

            });

            $('#ht_details_print_btn').prop('disabled', false);
        }

        clear_ht_visit_visitant_history_table = () => {
            $('#cv_ht_visit_table tbody tr').each( (key, item) => {                
                item.remove();
            });
        }

        clear_ht_visit_visitant_history_details = () => {
            
            $(".cv_modal_ht_visit_table .ht_visit_details_wrapper input").each( (key, item) =>{
                $(item).val("");
            });

            $(".cv_modal_ht_visit_table .ht_visit_details_wrapper textarea").each( (key, item) =>{
                $(item).val("");
            });

            $('#ht_details_print_btn').prop('disabled', true);
        };    

    </script>
</div> <!-- Fin Modal -->