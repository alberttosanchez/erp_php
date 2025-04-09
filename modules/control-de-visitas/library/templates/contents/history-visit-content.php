<style>
    .cv_history_visit_content{

    }
    .cv_history_visit_content > h3 {

    }
    .cv_history_visit_content > .cv_history_visit_wrapper {

    }
    .cv_history_visit_wrapper .cv_search_filter {
        border: 1px solid #dbdbdb;
        border-radius: 5px;
        overflow: hidden;
    }
    .cv_history_visit_wrapper .cv_search_filter select {
        border: unset;
    }
    .cv_history_visit_wrapper .cv_search_filter select option {

    }
    .cv_history_visit_wrapper .cv_search_filter input {
        border: unset;
        outline: unset;
    }
    .cv_history_visit_wrapper .cv_search_filter button {
        border: unset;
        background-color: dodgerblue;
        padding: 5px 10px;
        color: white;
    }
    .cv_history_visit_wrapper .cv_search_filter button:hover {
        opacity: 0.8;
    }
    .cv_history_visit_table tr{
        cursor: pointer;
    }
    .cv_history_visit_table tbody tr:hover{
        background-color: yellow;
    }
    .cv_ht_table_wrapper {
        height: 300px;
        overflow: scroll;
    }
</style>
<div id="cv_history_visit" class="cv_history_visit_content content">

    <h3>CONSULTAR VISITANTES</h3>

    <div id="cv_history_visit_wrapper" class="cv_history_visit_wrapper d-flex justify-content-between">

        <div class="cv_search_filter form-group" style="width:calc(50% - 5px)">
            <select type="text" name="ht_filter_select" id="ht_filter_select" class="form-control" >
                <option value="">Selectione Filtro de Búsqueda</option>
                <option value="1">ID</option>
                <option value="2">Nombres</option>
                <option value="3">Apellidos</option>                
                <option value="4">Número de Identificación</option>
                <option value="5">Fecha de Última Visita</option>
            </select>
        </div>

        <div class="cv_search_filter form-group d-flex" style="width:calc(50% - 5px)">
            <input type="text" name="ht_search_filter_key" id="ht_search_filter_key" class="ht_search_filter_key form-control" placeholder="Palabra Clave"/>
            <button id='get_ht_visit_tb_list_btn' name='get_ht_visit_tb_list_btn' title="Filtrar Búsqueda" type="button"><span class="search_icon"><i class="fa fa-search"></i></span></button>
        </div>

    </div>
    <br>
    <div>
        <div class="cv_ht_table_wrapper">
    
            <table id="cv_history_visit_table" class="cv_history_visit_table table table-responsible table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No. Identificación</th>
                        <th>Tipo Identificación</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Fecha de Nacimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    /* ver -> 
                    <!-- <tr key="63">
                        <td>63</td>
                        <td>001-0014521-1</td>
                        <td>-</td>
                        <td>Román</td>
                        <td>Perez</td>
                        <td>09-09-1990</td>                                    
                    </tr> --> */
                    ?>
                </tbody>
            </table>
    
            <script>
                catch_ht_visit_table_row_id = () => {
                    let table_row = document.querySelectorAll('#cv_history_visit_table tbody > tr');
        
                    for (let i=0; i < table_row.length; i++){
                        table_row[i].addEventListener('click', () => {

                            <?php // ver -> history-visit-modal.php ?>                            
                            clear_ht_visit_visitant_history_table();
                            clear_ht_visit_visitant_history_details();
                            
                            $('#ht_visit_modal_btn').prop('disabled', false);

                            get_ht_visit_single_tb_row_data( table_row[i].getAttribute('key') );
                        });
                    }
                }
            </script>
    
        </div>
    </div>
    
    <hr>

    <div class="form-group ht_visit_info_wrapper">

        <style>
            .ht_visit_info_wrapper label{
                font-size: 12px;
                font-weight: bold;
            }
        </style>

        <div class="form-group d-flex justify-content-between">

            <h4>Información del Visitante</h4>

            <!-- Button trigger modal -->            
            <button id="ht_visit_modal_btn" type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ht_visit_modal" disabled>Historial de Visitas</button>

            <?php include_once( CV_TEMPLATE_MODALS . '/history-visit-modal.php'); ?>
            
            <?php include_once( CV_TEMPLATE_MODALS . '/history-visit-print-modal.php'); ?>
            
        </div>
        
        <br>

        <div class="form-group d-flex justify-content-between">
    
            <div class="form-group" style="width:calc(80% - 5px)">    
        
                <div class="form-group d-flex justify-content-between">

                    <div class="box_one form-group" style="width:calc(50% - 5px)">
                        <label for="ht_info_name">Nombres</label>
                        <input type="text" id="ht_info_name" name="ht_info_name" class="form-control" maxlength="100" disabled/>
                    </div>
            
                    <div class="box_two form-group" style="width:calc(50% - 5px)">
                        <label for="ht_info_last_name">Apellidos</label>
                        <input type="text" id="ht_info_last_name" name="ht_info_last_name" class="form-control" maxlength="100" disabled/>
                    </div>

                </div>

                <div class="form-group d-flex justify-content-between">

                    <div class="box_three form-group" style="width:calc(33.33% - 5px)">
                        <label for="ht_info_gender">Sexo</label>
                        <select name="ht_info_gender" id="ht_info_gender" class="form-control" maxlength="100" disabled>
                            <option value="0"></option>
                            <option value="1">HOMBRE</option>
                            <option value="2">MUJER</option>
                            <option value="3">OTRO</option>
                        </select>
                    </div>
                    
                    <div class="box_four form-group" style="width:calc(33.33% - 5px)">
                        <label for="ht_info_id">Código de Identidad</label>
                        <input type="text" id="ht_info_id" name="ht_info_id" class="form-control" maxlength="100" disabled/>
                    </div>
            
                    <div class="box_five form-group" style="width:calc(33.33% - 5px)">
                        <label for="ht_info_id_type">Tipo de Documento</label>
                        <select id="ht_info_id_type"  name="ht_info_id_type" class="form-control" maxlength="100" disabled>
                            <option value="0"></option>
                            <option value="1">CEDULA</option>
                            <option value="2">PASAPORTE</option>
                            <option value="3">OTRO</option>
                        </select>
                    </div>

                </div>

                <div class="form-group d-flex justify-content-between">

                    <div class="box_six form-group" style="width:calc(20% - 5px)">
                        <label for="">Fecha de Nacimiento</label>
                        <input type="date" id="ht_info_birth_date"  name="ht_info_birth_date"  class="form-control" disabled/>
                    </div>
            
                    <div class="box_seven form-group" style="width:calc(80% - 5px)">
                        <label for="">Última Visita</label>
                        <input type="text"  id="ht_info_last_visit_date"  name="ht_info_last_visit_date" class="form-control" disabled/>
                    </div>

                </div>

        
            </div>
    
            <div class="form-group" 
                style="
                    width:calc(20% - 5px);
                    justify-content: center;
                    margin: auto;
                    display: flex;
                ">
    
                <div class="form-group">
                    <div class="cv_ht_visit_photo photo_pic_box">
                        <p>Foto</p>
                        <img id="ht_visit_single_photo" src="" alt="Foto del Visitante" class="no-show" title="Foto del Visitante" 
                        style="
                            width: 100%;
                            height: 100%;
                        ">
                        <span class="user_icon"><i class="fa fa-user"
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
    
            </div>
        </div>

    </div>

    <script>

        handle_ht_visit_filters = () => {

            clean_ht_visit_tb_list_data();
            clean_ht_visit_single_tb_row_data();


            let filter='';
            switch ( document.querySelector('#ht_filter_select').value ) {
                case '1': filter = 'id_visitant'; break;
                case '2': filter = 'name'; break;
                case '3': filter = 'last_name'; break;
                case '4': filter = 'ident_number'; break;
                case '5': filter = 'last_visit_date'; break;            
                default: filter = 'id_visitant'; break;
            }            

            localStorage.ht_visit_tb_list_data = JSON.stringify({
                filter : filter,
                keyword: document.querySelector('#ht_search_filter_key').value
            });

            localStorage.ht_visit_selected_page = "1";
            localStorage.ht_visit_scroll_ajust = '0';

            setTimeout(() => {
                get_ht_visit_tb_list();
            }, 100);
        }

        // limpiar lista de visitantes
        clean_ht_visit_tb_list_data = () => {

            let cv_history_visit_table = document.querySelectorAll('#cv_history_visit_table > tbody > tr');

            if ( cv_history_visit_table.length > 0)
            {
                cv_history_visit_table.forEach(element => {
                    element.remove();
                });
            }

            $('#ht_visit_modal_btn').prop('disabled', true); 
        }        
        
        // obtener datos del visitante seleccionado
        get_ht_visit_single_tb_row_data = async (id_visitant) => {

            loading();

            let body_data = JSON.stringify({                
                target          : "get_from_filter",
                table_name      : CVVW_VISITANTS_INFO_TABLE,
                info_data       : { 
                    filter : "id_visitant",
                    keyword : id_visitant
                },
                selected_page   : '1',
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id
            });

            //console.log(body_data);

            //limpiamos el cachet de la foto        
            //# localStorage.reg_visit_single_data.photo_path = "";

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

                console.log(response);
                
                loading();

                switch (server.status) {
                    case 200:
                        
                                            
                        if ( response.message == "datos obtenidos" )
                        {
                            
                            localStorage.ht_visit_single_tr_data_fetched = JSON.stringify(response.data.fetched);
                            
                            setTimeout(() => {
                                render_ht_visit_single_tb_row_data(id_visitant);
                            }, 100);

                        }
                        else
                        {
                            localStorage.ht_visit_single_tr_data_fetched = "";                            
                            //# clean_ht_visit_tb_list_data();
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

        // renderizar datos del visitante seleccionado
        render_ht_visit_single_tb_row_data = (id_visitant) => {

            let ht_visit_single_tr_data_fetched = JSON.parse(localStorage.ht_visit_single_tr_data_fetched);
            

            let ht_info_name            = document.querySelector('#ht_info_name');
            let ht_info_last_name       = document.querySelector('#ht_info_last_name');
            let ht_info_gender_op       = document.querySelectorAll('#ht_info_gender > option'); // select
            let ht_info_id              = document.querySelector('#ht_info_id');
            let ht_info_id_type_op      = document.querySelectorAll('#ht_info_id_type > option'); // select
            let ht_info_birth_date      = document.querySelector('#ht_info_birth_date');
            let ht_info_last_visit_date = document.querySelector('#ht_info_last_visit_date');
            let ht_visit_single_photo   = document.querySelector('#ht_visit_single_photo');
            let photo_pic_box_span      = document.querySelector('.ht_visit_info_wrapper .cv_ht_visit_photo.photo_pic_box > span.user_icon');

            ht_visit_single_tr_data_fetched.forEach( item => {

                localStorage.ht_visit_current_visitant = item.id_visitant;

                if ( item.id_visitant == id_visitant )
                {
                    ht_info_name.value = item.name;
                    ht_info_last_name.value = item.last_name;
                    //ht_info_gender.value = item.gender;
                    ht_info_id.value = item.ident_number;
                   // ht_info_id_type.value = item.identification_type;
                    ht_info_birth_date.value = item.birth_date;
                    ht_info_last_visit_date.value = item.last_visit_date;

                    
                    ht_info_gender_op.forEach( element => {
                        // selecciona la opcion que coincida con el id                        
                        if ( element.value == item.gender_id ) element.selected = true;

                    });                

                    ht_info_id_type_op.forEach( element => {
                        
                        // selecciona la opcion que coincida con el id
                        if ( element.innerText.toLowerCase() == item.identification_type.toLowerCase() ) element.selected = true;                        
                    });                
                    
                    if ( item.photo_path !== null && item.photo_path.length > 0 )
                    {
                        ht_visit_single_photo.setAttribute('src', item.photo_path );
                            
                        ht_visit_single_photo.setAttribute('class','show');
                        photo_pic_box_span.setAttribute('class','user_icon no-show');
                    }
                    else
                    {
                        ht_visit_single_photo.setAttribute('class','no-show');
                        photo_pic_box_span.setAttribute('class','user_icon show');
                    }
                }

                localStorage.ht_selected_visitant_id = item.visitant_id;
            });

            let fn_vt_btn = document.querySelector('#fn_vt_btn');
                fn_vt_btn.disabled = false;

        };
        
        // limpiar datos de visitantes
        clean_ht_visit_single_tb_row_data = () => {

            localStorage.ht_visit_current_visitant = "";
            
            let ht_visit_info_wrapper_inputs = document.querySelectorAll('.ht_visit_info_wrapper input');
                ht_visit_info_wrapper_inputs.forEach(element => element.value = "");
           
            let ht_visit_single_photo   = document.querySelector('#ht_visit_single_photo');
                ht_visit_single_photo.setAttribute('class','no-show');

            let photo_pic_box_span      = document.querySelector('.ht_visit_info_wrapper .cv_ht_visit_photo.photo_pic_box > span.user_icon');
                photo_pic_box_span.setAttribute('class','user_icon show');

            let ht_info_gender_op       = document.querySelectorAll('#ht_info_gender > option'); // select
            let ht_info_id_type_op      = document.querySelectorAll('#ht_info_id_type > option'); // select
            
            ht_info_gender_op.forEach( element => {
                // selecciona la opcion que coincida con el id                        
                if ( element.value == 0 ) element.selected = true;

            });                

            ht_info_id_type_op.forEach( element => {
                // selecciona la opcion que coincida con el id
                if ( element.value == '0' ) element.selected = true;                        
            });
            
        }

        // renderizar lista de visitantes
        render_ht_visit_tb_list_data = () => {


            let ht_visit_fetched_tb_list_data = JSON.parse(localStorage.ht_visit_fetched_tb_list_data);
            
            ht_visit_fetched_tb_list_data.forEach( item => {
                            
                let td_one = document.createElement('td');
                    td_one.innerHTML = item.id_visitant;
                let td_two = document.createElement('td');
                    td_two.innerHTML = item.ident_number;
                let td_three = document.createElement('td');                
                    td_three.innerHTML = item.identification_type;
                let td_four = document.createElement('td');
                    td_four.innerHTML = item.name;
                let td_five = document.createElement('td');
                    td_five.innerHTML = item.last_name;
                let td_six = document.createElement('td');
                    td_six.innerHTML = item.birth_date;

                let tr = document.createElement('tr');
                    tr.setAttribute('key', item.id_visitant);
                
                tr.appendChild(td_one);
                tr.appendChild(td_two);
                tr.appendChild(td_three);
                tr.appendChild(td_four);
                tr.appendChild(td_five);
                tr.appendChild(td_six);

                let cv_history_visit_table = document.querySelector('#cv_history_visit_table > tbody');

                    cv_history_visit_table.appendChild(tr);

                    
            });
                
            setTimeout(() => {
                catch_ht_visit_table_row_id();
            }, 100);

            

        }

        // EventListener get_ht_visit_tb_list        
        let get_ht_visit_tb_list_btn = document.querySelector('#get_ht_visit_tb_list_btn');
            get_ht_visit_tb_list_btn.addEventListener('click', handle_ht_visit_filters );

        // obtener lista de visitantes
        get_ht_visit_tb_list = async () => {

            loading();

            let body_data = JSON.stringify({                
                target          : "get_from_filter",
                table_name      : CVVW_VISITANTS_INFO_TABLE,
                info_data       : JSON.parse(localStorage.ht_visit_tb_list_data),
                selected_page   : localStorage.ht_visit_selected_page,
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id
            });

            //# console.log(body_data);

            //limpiamos el cachet de la foto        
            //# localStorage.ht_visit_single_data.photo_path = "";

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

                console.log(response);
                
                loading();

                switch (server.status) {
                    case 200:
                        
                                            
                        if ( response.message == "datos obtenidos" )
                        {
                            
                            localStorage.ht_visit_fetched_tb_list_data = JSON.stringify(response.data.fetched);
                            localStorage.ht_visit_selected_page = JSON.stringify(response.data.pagination.next_page);
                            setTimeout(() => {
                                render_ht_visit_tb_list_data();                        
                            }, 100);

                        }
                        else
                        {
                            localStorage.ht_visit_fetched_tb_list_data = "";                            
                            clean_ht_visit_tb_list_data();
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

        ht_visit_infinityScroll = (e) =>
        {
            
            let parent = e.parentNode;
            //# console.log( (e.scrollTop + e.offsetHeight - localStorage.ht_visit_scroll_ajust) > parent.offsetHeight-50);
            if( e.scrollTop + e.offsetHeight - localStorage.ht_visit_scroll_ajust > parent.offsetHeight-50 )
            {            
                //# console.log( e.scrollTop + e.offsetHeight - localStorage.ht_visit_scroll_ajust > parent.offsetHeight-50 );
                localStorage.ht_visit_scroll_ajust = localStorage.ht_visit_scroll_ajust + (e.scrollHeight < 900) ? e.scrollHeight : 0;            
                setTimeout(() => {
                    if ( JSON.parse(localStorage.ht_visit_selected_page) !== "" )
                    {
                        get_ht_visit_tb_list();
                    }
                }, 100);
            }        
        }; 
        let cv_ht_table_wrapper = document.querySelector(".cv_ht_table_wrapper"); 
            cv_ht_table_wrapper.addEventListener( 'scroll' , (e)=>ht_visit_infinityScroll(cv_ht_table_wrapper),false);
    </script>

</div>