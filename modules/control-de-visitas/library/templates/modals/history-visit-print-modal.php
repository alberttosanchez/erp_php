<!-- Modal -->
<style>
    .ht_visit_print_modal{

    }
    .ht_visit_print_modal .modal-dialog{
        width: calc(100% - 80px);
        max-width: 2560px;
        border: 1px solid #dbdbdb;
        background-color: white;
        top: 10px;               
    }
    .ht_visit_print_modal .cv_visit_history_report_wrapper{
        background-color: #198754;
        display: flex;
        justify-content: space-between;
        padding: 5px;
    }
    .ht_print_header > h1 {
        text-align: center;
        font-size: 20px;
    }
    .ht_print_pic.photo_pic_box {
        position: relative;
        width: 120px;
        height: 140px;
        padding: 5px;
        border: 1px solid #989090;
        margin: auto;
    }
    table.ht_print_table{

    }
    table.ht_print_table tr td{
        font-size: 12px;
    }
    @media print {
        .modal {
            background-color: white;
        }
        .ht_visit_print_modal .modal-dialog{
            border: unset;        
            width: 100%;
            max-width: 100%;
            margin: 0;
        }
        .modal-content{
            border: unset;
        }
        .ht_visit_print_modal .cv_visit_history_report_wrapper{
            background-color: white;
        }
        .ht_visit_print_modal .cv_visit_history_report_wrapper p{
            color: black;
        }
        div#cv_visit_history_report_wrapper > button {
            display: none;
        }
    }
</style>
<div class="modal fade ht_visit_print_modal" id="ht_visit_print_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ht_visit_print_modal" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">

                <div id="cv_visit_history_report_wrapper" class="cv_visit_history_report_wrapper">
                    <p class="m-0" style="color:white"><b>Historial de: <span class="ht_print_visit_name">Alberto Sánchez</span> - Fecha <span class="ht_print_visit_print_date">17/02/2021</span></b></p>
                    <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#ht_visit_modal" aria-label="Close" style="color:white"></button>
                </div>

                <div class="moda_body" style="padding:20px">

                    <div class="ht_print_header">
                        <h1><?php echo APP_NAME; ?><br>REPORTE DE VISITAS</h1>
                        <p><b>Fecha del Reporte: </b><span class="report_date">19/02/2021</span></p>
                    </div>
                    
                    <div class="form-group">
                        <div class="ht_print_pic photo_pic_box">
                            <p>Foto</p>
                            <img src="" alt="Foto del Visitante" class="" title="Foto del Visitante" 
                            style="
                                width: 100%;
                                height: 100%;
                            ">
                            <span class="user_icon no-show"><i class="fa fa-user"
                                style="
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    font-size: 70px;
                                    padding: 25px;
                                    width: 100%;
                                    height: 100%;
                                    color: #dbdbdb;
                                "
                            ></i></span>
                        </div>
                    </div>
                    
                    <table id="ht_print_table" class="ht_print_table table">
                        <tbody>
                            <tr>
                                <td colspan="5"><h4>DATOS DEL VISITANTE</h4></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>Nombres: </b><span class="ht_tb_name">ALBERTO ELIGIO</span></td>
                                <td colspan="2"><b>Apellidos: </b><span class="ht_tb_last_name">SANCHEZ GERMAN</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Fecha de Nacimiento: </b><span class="ht_tb_birth_date">19/02/2021</span></td>
                                <td><b>Código de Identidad: </b><span class="ht_tb_visitant_id">00300152457</span></td>
                                <td><b>Tipo Código de Identidad: </b><span class="ht_tb_identification_type">Cédula</span></td>
                            </tr>
                            <tr><td colspan="5"><h4>DETALLES DE LA VISITA</h4></td></tr>
                            <tr>
                                <td><b>Fecha de la Visita: </b><br><span class="ht_tb_visit_date">17/02/2021</span></td>
                                <td><b>Hora de Llegada: </b><br><span class="ht_tb_arrive_time">08:35:15 am</span></td>
                                <td><b>Hora de Salida: </b><br><span class="ht_tb_out_time">11:15:11 am</span></td>
                                <td><b>Nivel de Acceso: </b><br><span class="ht_tb_access_level">A,B,C</span></td>
                                <td><b>Contacto en la Empresa: </b><br><span class="ht_tb_coworker_name">KEYFREE FIGUEREO</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Departamento: </b><br><span class="ht_tb_dpto">DTIC</span></td>
                                <td><b>Portaba Arma de Fuego: </b><br><span class="ht_tb_has_gun">No</span></td>
                                <td><b>Tipo de Licencia del Arma: </b><br><span class="ht_tb_gun_license">No Aplica</span></td>
                                <td><b>Código Licencia del Arma: </b><br><span class="ht_tb_gun_id">No Aplica</span></td>                                
                            </tr>
                            <tr>
                                <td><b>Se entregó el arma al irse: </b><br><span class="ht_tb_gun_status">No Aplica</span></td>
                                <td colspan="2"><b>Observaciones al iniciar visita: </b><br><span class="ht_tb_start_comments">Lorem ipsum dolor sit amet consectetur adipisicing elit. Non officiis inventore facilis ipsum assumenda hic, nihil beatae iure cumque accusantium repudiandae doloribus commodi at officia deserunt, ducimus praesentium eaque voluptate.</span></td>
                                <td colspan="2"><b>Observaciones al finalizar visita: </b><br><span class="ht_tb_end_comments">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem soluta at officia! Vero laudantium atque alias facilis culpa a. Adipisci impedit in tenetur voluptas quasi, totam voluptatem odio aperiam veniam?</span></td>                                                               
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        
    </div>
</div> <!-- Fin Modal -->

<script>

    render_ht_visit_details_print_single_data = (visit_key_id) => {
        console.log('visit_key_id : ' + visit_key_id);
        let ht_visit_visitant_history_data = JSON.parse(localStorage.ht_visit_visitant_history_data);
        let ht_visit_single_tr_data_fetched = JSON.parse(localStorage.ht_visit_single_tr_data_fetched);

        ht_visit_single_tr_data_fetched.map( (item, index) => {            
            $('.ht_print_pic.photo_pic_box img').prop('src', item.photo_path );
            $('.ht_tb_name').text(`${item.name}`);
            $('.ht_tb_last_name').text(`${item.last_name}`);
            $('.ht_tb_birth_date').text(`${fix_date_format(item.birth_date)}`);
            $('.ht_tb_visitant_id').text(`${item.ident_number}`);
            $('.ht_tb_identification_type').text(`${item.identification_type}`);

            
        });
        
        ht_visit_visitant_history_data.map( (item, index) => {

            // convertimos una fecha de cadena en timestamp
            let date_started = new Date(item.started_at);                
            let date_ended = new Date(item.ended_at);
            
            if( visit_key_id == item.visit_id )
            {
                $('.ht_tb_visit_date').text(`${date_started.toLocaleDateString("es-DO")}`);    
                $('.ht_tb_arrive_time').text(`${date_started.toLocaleTimeString("es-DO")}`);    
                $('.ht_tb_out_time').text(`${date_ended.toLocaleTimeString("es-DO")}`);    
                $('.ht_tb_access_level').text(`${item.level_access}`);                      
                //$('.ht_tb_coworker_name').text(`${item.cw_name} ${item.cw_last_name}`);    
                $('.ht_tb_coworker_name').text(`${item.cw_raw_full_name}`);                    
                //$('.ht_tb_dpto').text(`${item.department}`);    
                $('.ht_tb_dpto').text(`${item.cw_raw_department}`);
                $('.ht_tb_has_gun').text(`${(item.has_gun == 0) ? 'NO' : 'SI'}`);    
                $('.ht_tb_gun_license').text(`${item.gun_license}`);    
                $('.ht_tb_gun_id').text(`${item.license_number}`);
                $('.ht_tb_gun_status').text(`${item.gun_status}`);
                $('.ht_tb_start_comments').text(`${item.start_comments}`);
                $('.ht_tb_end_comments').text(`${item.end_comments}`);
            }

        });
    }

</script>