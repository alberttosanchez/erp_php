<style>
    .co_edit_modal_group_wrapper {
        width: 100%;
        max-width: calc(100% - 20px);
    }
    .co_edit_modal_content_box {
        border-radius: unset;
    }
    .co_edit_modal_title_bar_box{
        display: flex;
        justify-content: space-between;
        padding: 3px;
        /* border: 1px solid #dbdbdb; */
        background-color: #21ad21 !important;
        align-items: center;
        color: white;
    }    
    .co_edit_modal_title_bar_box p{
        margin: 0;
        font-size: 12px;
        font-weight: bold;        
    }
    .co_edit_modal_body_wrapper{
        padding: 20px;
    }
    .co_edit_modal_one_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .co_edit_modal_one_box_group > div {
        width: 100%;
        max-width: calc(50% - 5px);
    }
    .co_edit_modal_two_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .co_edit_modal_two_box_group > div {
        width: 100%;
        max-width: calc(25% - 5px);
    }
    .co_edit_modal_three_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }   
    .co_edit_modal_three_box_group > div {
        width: 100%;
        max-width: calc(33.33% - 5px);
    }
    .co_edit_modal_three_box_group > div:nth-child(1),
    .co_edit_modal_three_box_group > div:nth-child(2) {        
        width: 100%;
        max-width: calc(40% - 5px);
    }  
    .co_edit_modal_three_box_group > div:nth-child(3){
        width: 100%;
        max-width: calc(20% - 5px);
    }
    .co_edit_modal_four_box_group{
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .co_edit_modal_four_box_group > div:nth-child(1) {
        width: 100%;
        max-width: calc(60% - 5px);
    }
    .co_edit_modal_four_box_group > div:nth-child(2) {
        display: flex;
        max-width: calc(40% - 5px);
        align-items: end;
    } 
    .co_edit_modal_register_btn_box .co_edit_modal_message {
        padding: 8px;
        font-style: italic;
        color: green;
    }   
    @media screen and (max-width:769px){
        .co_edit_modal_one_box_group > div,
        .co_edit_modal_two_box_group > div,
        .co_edit_modal_three_box_group > div,
        .co_edit_modal_four_box_group > div {
            max-width: 100% !important;
        }
        .co_edit_modal_four_box_group > div:nth-child(2) {            
            width: 100%;            
            justify-content: center;
            margin-top: 10px;
        }
    }
</style>
<!-- Modal -->
<div class="co_edit_coworker_modal_wrapper modal fade" id="co_edit_coworker_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="co_edit_coworker_modalLabel" aria-hidden="true">
    <div class="co_edit_modal_group_wrapper modal-dialog">
        <div class="co_edit_modal_content_box modal-content">

            <div class="co_edit_modal_title_bar_box form-group">
                <p><span class="co_title_bar">EDITAR COLABORADOR</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="co_edit_modal_body_wrapper co_body_wrapper form-group">

                <h4>Datos Personales</h4>

                <div class="co_edit_modal_one_wrapper co_wrapper form-group">

                    <div class="co_edit_modal_one_box_group co_box_group form-group">
    
                        <div class="co_edit_modal_name_box box form-group">
                            <label for="co_edit_modal_name">Nombres</label>
                            <input type="text" id="co_edit_modal_name" name="co_edit_modal_name" class="form-control" maxlength="100" />
                        </div>
        
                        <div class="co_edit_modal_last_name_box box form-group">
                            <label for="co_edit_modal_last_name">Apellidos</label>
                            <input type="text" id="co_edit_modal_last_name" name="co_edit_modal_last_name" class="form-control" maxlength="100" />
                        </div>
    
                    </div>
    
                    <div class="co_edit_modal_two_box_group co_box_group form-group">
    
                        <div class="co_edit_modal_gender_box box form-group">
                            <label for="co_edit_modal_gender">Sexo</label>
                            <select name="co_edit_modal_gender" id="co_edit_modal_gender" class="co_edit_modal_gender form-control" >
                                <option value="">-</option>
                            </select>
                        </div>
    
                        <div class="co_edit_modal_ident box form-group">
                            <label for="co_edit_modal_ident">Código de Identidad</label>
                            <input type="text" id="co_edit_modal_ident" name="co_edit_modal_ident" class="co_edit_modal_ident form-control" maxlength="100" />
                        </div>
    
                        <div class="co_edit_modal_id_type_box box form-group">
                            <label for="co_edit_modal_type_id">Tipo de Identificación</label>
                            <select type="text" id="co_edit_modal_type_id" name="co_edit_modal_type_id" class="co_edit_modal_type_id form-control">
                                <option value="">-</option>
                            </select>
                        </div>
    
                        <div class="co_edit_modal_birth_date_box box form-group">
                            <label for="co_edit_modal_birth_date">Fecha de Nacimiento</label>
                            <input type="date" id="co_edit_modal_birth_date" name="co_edit_modal_birth_date" class="co_edit_modal_birth_date form-control" maxlength="100" />
                        </div>
    
                        <input type="hidden" id="co_edit_modal_id" name="co_edit_modal_id" value="">

                    </div>

                </div>

                <br>

                <h4>Información Laboral</h4>

                <div class="co_edit_modal_two_wrapper co_wrapper form-group">

                    <div class="co_edit_modal_three_box_group co_box_group form-group">
                        
                        <div class="co_edit_modal_dpto_box box form-group">
                            <label for="co_edit_modal_dpto">Departamento donde Labora</label>
                            <select id="co_edit_modal_dpto" name="co_edit_modal_dpto" class="co_edit_modal_dpto form-control">
                                <option value="">-</option>
                                <option value="">DTIC</option>
                                <option value="">Recepción</option>
                                <option value="">Comunicaciones</option>
                                <option value="">Becas</option>
                            </select>
                        </div>
    
                        <div class="co_edit_modal_job_title_box box form-group">
                            <label for="co_edit_modal_job_title">Cargo</label>
                            <input type="text" id="co_edit_modal_job_title" name="co_edit_modal_job_title" class="co_edit_modal_job_title form-control" maxlength="100" />
                        </div>
    
                        <div class="co_edit_modal_phone_ext_box box form-group">
                            <label for="co_edit_modal_phone_ext">Extensión Telefónica</label>
                            <input type="text" id="co_edit_modal_phone_ext" name="co_edit_modal_phone_ext" class="co_edit_modal_phone_ext form-control" maxlength="100" />
                        </div>

                    </div>
                    
                    <div class="co_edit_modal_four_box_group co_box_group form-group">

                        <div class="co_edit_modal_email_box box form-group">
                            <label for="co_edit_modal_email">Correo Institucional</label>
                            <input type="text" id="co_edit_modal_email" name="co_edit_modal_email" class="co_edit_modal_email form-control" maxlength="100" />
                        </div>

                        <div class="co_edit_modal_register_btn_box box form-group">                            
                            <span class="no-show co_edit_modal_message"><small>Datos Actualizados</small></span>
                            <buton type="button" id="co_edit_modal_register_btn" name="co_edit_modal_register_btn" class="co_edit_modal_register_btn btn btn-success">ACTUALIZAR</buton>
                        </div>

                    </div>

                </div>

            </div>
            
        </div>
    </div>
</div>

<script>

    

    clean_co_edit_modal = () => {

        let co_edit_modal_inputs = document.querySelectorAll('.co_edit_modal_body_wrapper input');
        let co_edit_modal_selects = document.querySelectorAll('.co_edit_modal_body_wrapper select');

        co_edit_modal_inputs.forEach( item => {
            item.innerHTML = "";
        });
        
        co_edit_modal_selects.forEach( item => {
            
            for (let u = 0; u < item.children.length; u++) {
    
                let HTMLCollection = item.children;

                for (let i = 0; i < HTMLCollection.length; i++) {

                    HTMLCollection[i].remove();
                    
                }
    
            };

        });        

    };

    handle_co_edit_modal_register_btn = () => {

        let co_edit_modal_single_data_one = {
            'id'                    : document.querySelector('#co_edit_modal_id').value,
            'name'                  : document.querySelector('#co_edit_modal_name').value.toUpperCase(),
            'last_name'             : document.querySelector('#co_edit_modal_last_name').value.toUpperCase(),
            'gender_id'             : document.querySelector('#co_edit_modal_gender').value,
            'identification_id'     : document.querySelector('#co_edit_modal_ident').value,
            'identification_type_id': document.querySelector('#co_edit_modal_type_id').value,
            'birth_date'            : document.querySelector('#co_edit_modal_birth_date').value,
        };
        let co_edit_modal_single_data_two = {
            'coworker_id'       : document.querySelector('#co_edit_modal_id').value,
            'job_department_id' : document.querySelector('#co_edit_modal_dpto').value,
            'job_title'         : document.querySelector('#co_edit_modal_job_title').value.toUpperCase(),
            'phone_extension'   : document.querySelector('#co_edit_modal_phone_ext').value,
            'job_email'         : document.querySelector('#co_edit_modal_email').value.toLowerCase(),
        };
        
        console.log(co_edit_modal_single_data_one);

        localStorage.co_edit_modal_single_data_one = JSON.stringify(co_edit_modal_single_data_one);
        localStorage.co_edit_modal_single_data_two = JSON.stringify(co_edit_modal_single_data_two);

        setTimeout(() => {
            update_co_edit_modal_single_data_one();
        }, 100);
    };

    render_co_edit_modal = () => {
        
        console.log('render_edit_co_modal');

        //console.log(localStorage.co_search_info_from_single_data);
        
        JSON.parse(localStorage.co_search_info_from_single_data).forEach( item => {
            
            localStorage.co_edit_current_row = JSON.stringify({
                'id'                    : item.id,
                'name'                  : item.name,
                'last_name'             : item.last_name,
                'gender_id'             : item.gender_id,
                'gender'                : item.gender,
                'identification_id'     : item.identification_id,
                'identification_type_id': item.identification_type_id,
                'identification_type'   : item.identification_type,
                'birth_date'            : item.birth_date,
                'job_department_id'     : item.job_department_id,
                'job_title'             : item.job_title,
                'phone_extension'       : item.phone_extension,
                'job_email'             : item.job_email,
            });            
            
        });
        
        let co_edit_current_row = JSON.parse(localStorage.co_edit_current_row);            

        //console.log(co_edit_current_row);

        document.querySelector('#co_edit_modal_id').value = co_edit_current_row.id;
        document.querySelector('#co_edit_modal_name').value = co_edit_current_row.name;
        document.querySelector('#co_edit_modal_last_name').value = co_edit_current_row.last_name;
        
        document.querySelector('#co_edit_modal_ident').value = co_edit_current_row.identification_id;

        document.querySelector('#co_edit_modal_birth_date').value = co_edit_current_row.birth_date;
        document.querySelector('#co_edit_modal_job_title').value = co_edit_current_row.job_title;
        document.querySelector('#co_edit_modal_phone_ext').value = co_edit_current_row.phone_extension;
        document.querySelector('#co_edit_modal_email').value = co_edit_current_row.job_email;
        
        
        JSON.parse(localStorage.co_genders_category).forEach( item => {
            let co_edit_modal_gender = document.querySelector('#co_edit_modal_gender');

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.gender;
                
                if (item.id == co_edit_current_row.gender_id){
                    option.selected = true;
                };
            
                co_edit_modal_gender.appendChild(option);
                
        });      

        JSON.parse(localStorage.co_identification_type_category).forEach( item => {
            let co_edit_modal_type_id = document.querySelector('#co_edit_modal_type_id');

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.identification_type;
                
                if (item.id == co_edit_current_row.identification_type_id){
                    option.selected = true;
                };
            
                co_edit_modal_type_id.appendChild(option);
                
        });
        
        
        JSON.parse(localStorage.co_plant_distribution_category).forEach( item => {

            let co_edit_modal_dpto = document.querySelector('#co_edit_modal_dpto');
        
            let option = document.createElement('option');
                option.setAttribute('key', item.id);
                option.setAttribute('value', item.id);
                option.innerHTML = item.department.capitalize();

                if (item.id == co_edit_current_row.job_department_id){
                    option.selected = true;
                };

                co_edit_modal_dpto.appendChild(option);

        });
        
        JSON.parse(localStorage.level_access_data).forEach( item => {
            let pt_dist_edit_modal_level_access = document.querySelector('#pt_dist_edit_modal_level_access');

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.level_access;
                
                if (item.level_access == co_edit_current_row.level_access){
                    option.selected = true;
                };
            
                pt_dist_edit_modal_level_access.appendChild(option);
                
        });
    };

    update_co_edit_modal_single_data_one = async () => {

        loading();
        
        let body_data = JSON.stringify({                
                            target : "co_update_and_get",
                            table_name : CV_COWORKERS_TABLE,
                            info_data: JSON.parse(localStorage.co_edit_modal_single_data_one),
                            session_token: localStorage.session_token,
                            user_id: this.state.data.fetched.user_id
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
    
            console.log(response);
            
            loading();

            switch (server.status) {
                case 200:
                    
                    if (response.message == 'actualizado' )
                    {
                        update_co_edit_modal_single_data_two();
                    }
                    else
                    {
                        actionMessage(response.message.capitalize(),'warning');
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
    }; 
    
    update_co_edit_modal_single_data_two = async () => {

        loading();

        let body_data = JSON.stringify({                
                            target : "co_update_and_get",
                            table_name : CV_JOB_INFO_TABLE,
                            info_data: JSON.parse(localStorage.co_edit_modal_single_data_two),
                            session_token: localStorage.session_token,
                            user_id: this.state.data.fetched.user_id
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

            console.log(response);
            
            loading();

            switch (server.status) {
                case 200:
                    
                    if (response.message == 'actualizado' )
                    {
                        actionMessage(response.message.capitalize(),'warning');
                    }
                    else
                    {
                        actionMessage(response.message.capitalize(),'warning');
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
    };
    
    asign_co_edit_modal_events = () => {

        const co_edit_modal_register_btn = document.querySelector('#co_edit_modal_register_btn');
              co_edit_modal_register_btn.addEventListener( 'click', handle_co_edit_modal_register_btn );

    }; window.addEventListener( 'DOMContentLoaded', asign_co_edit_modal_events )

</script>