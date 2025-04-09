<style>
    .pt_dist_edit_modal_group_wrapper {
        width: 100%;
        max-width: calc(100% - 20px);
        
    }
    .pt_dist_edit_modal_content_box {
        border-radius: unset;
    }
    .pt_dist_edit_modal_title_bar_box{
        display: flex;
        justify-content: space-between;
        padding: 3px;
        /* border: 1px solid #dbdbdb; */
        background-color: #21ad21 !important;
        align-items: center;
        color: white;
    }    
    .pt_dist_edit_modal_title_bar_box p{
        margin: 0;
        font-size: 12px;
        font-weight: bold;        
    }
    .pt_dist_edit_modal_body_wrapper{
        padding: 20px;
    }
    .pt_dist_edit_modal_one_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .pt_dist_edit_modal_box_group{
      display: flex;
      justify-content: space-between;
    }
    .pt_dist_edit_modal_box_group > div:nth-child(1),
    .pt_dist_edit_modal_box_group > div:nth-child(2) {        
        width: 100%;
        max-width: calc(40% - 5px);
    }  
    .pt_dist_edit_modal_box_group > div:nth-child(3){
        width: 100%;
        max-width: calc(20% - 5px);
    }
    .pt_dist_edit_modal_add_btn_box{
        margin-top: 20px;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="pt_dist_edit_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pt_dist_edit_modalLabel" aria-hidden="true">
    <div class="pt_dist_edit_modal_group_wrapper modal-dialog">
        <div class="pt_dist_edit_modal_content_box modal-content">

            <div class="pt_dist_edit_modal_title_bar_box form-group">
                <p><span class="pt_dist_edit_modal_title_bar">EDITAR DISTRIBUCION EN PLANTA</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="pt_dist_edit_modal_body_wrapper pt_dist_modal_wrapper form-group">

                <div class="pt_dist_edit_modal_box_group box_group form-group">

                    <div class="pt_dist_edit_modal_dpto_box pt_dist_box form-group">
                        <label for="pt_dist_edit_modal_dpto">Departamento</label>
                        <input type="text" id="pt_dist_edit_modal_dpto" name="pt_dist_edit_modal_dpto" class="pt_dist_edit_modal_dpto form-control" />
                    </div>

                    <div class="pt_dist_edit_modal_location_box pt_dist_box form-group">
                        <label for="pt_dist_edit_modal_location">Ubicación</label>
                        <select name="pt_dist_edit_modal_location" id="pt_dist_edit_modal_location" class="pt_dist_edit_modal_location form-control">
                            <option value="">-</option>
                            
                        </select>
                    </div>

                    <div class="pt_dist_edit_modal_level_access_box pt_dist_box form-group">
                        <label for="pt_dist_edit_modal_level_access">Nivel de Acceso Requerido</label>
                        <select name="pt_dist_edit_modal_level_access" id="pt_dist_edit_modal_level_access" class="pt_dist_edit_modal_level_access form-control">
                            <option value="">-</option>                            
                        </select>
                    </div>

                    <input id="pt_dist_edit_modal_id" type="hidden" name="pt_dist_edit_modal_id" value="">
                </div>

                <div class="pt_dist_edit_modal_add_btn_box pt_dist_box form-group">
                    <button type="button" id="pt_dist_edit_modal_add_btn" name="pt_dist_edit_modal_add_btn" class="pt_dist_edit_modal_add_btn btn btn-success">ACTUALIZAR</button>
                    <span class="pt_dist_edit_modal_add_message no-show"><i>Item Actualizado</i></span>
                </div>

            </div>
            
        </div>
    </div>
</div>

<script>

    const clean_edit_pt_dist_modal = () => {
        let pt_dist_edit_modal_location_all = document.querySelectorAll('#pt_dist_edit_modal_location > option');
        
            pt_dist_edit_modal_location_all.forEach( item => {
                item.remove();
            });

        let pt_dist_edit_modal_level_access_all = document.querySelectorAll('#pt_dist_edit_modal_level_access > option');

            pt_dist_edit_modal_level_access_all.forEach( item => {
                item.remove();
            });
    };

    const render_edit_pt_dist_modal = () => {

        //console.log('render_edit_pt_dist_modal');

        clean_edit_pt_dist_modal();

        //console.log(localStorage.search_info_from_single_data);
        
        JSON.parse(localStorage.search_info_from_single_data).forEach( item => {
            
            localStorage.current_row = JSON.stringify({
                'id' : item.id,
                'level_access' :item.level_access,
                'department' : item.department,
                'floor_location' : item.floor_location
            });            
            
        });

        let current_row = JSON.parse(localStorage.current_row);

        //console.log(current_row);

        document.querySelector('#pt_dist_edit_modal_id').value = current_row.id;
        document.querySelector('#pt_dist_edit_modal_dpto').value = current_row.department;
        
        JSON.parse(localStorage.floor_location_data).forEach( item => {
            let pt_dist_edit_modal_location = document.querySelector('#pt_dist_edit_modal_location');

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.floor_location;
                
                if (item.floor_location == current_row.floor_location){
                    option.selected = true;
                };
            
                pt_dist_edit_modal_location.appendChild(option);
                
        });

        JSON.parse(localStorage.level_access_data).forEach( item => {
            let pt_dist_edit_modal_level_access = document.querySelector('#pt_dist_edit_modal_level_access');

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.level_access;
                
                if (item.level_access == current_row.level_access){
                    option.selected = true;
                };
            
                pt_dist_edit_modal_level_access.appendChild(option);
                
        });

    }

    const asign_edit_pt_modal_action_event = () => {

        const   pt_dist_edit_modal_add_btn = document.querySelector('#pt_dist_edit_modal_add_btn');
                pt_dist_edit_modal_add_btn.addEventListener('click', handle_pt_dist_edit_modal_save_btn  );

    }; window.addEventListener('DOMContentLoaded', asign_edit_pt_modal_action_event );

    const handle_pt_dist_edit_modal_save_btn = () => {

        //console.log('handle_pt_dist_edit_modal_save_btn');

        localStorage.pt_dist_edit_modal_data = JSON.stringify({
            'id'                : document.querySelector('#pt_dist_edit_modal_id').value,
            'department'        : document.querySelector('#pt_dist_edit_modal_dpto').value,
            'floor_location_id' : document.querySelector('#pt_dist_edit_modal_location').value,
            'level_access_id'   : document.querySelector('#pt_dist_edit_modal_level_access').value
        });

        setTimeout(() => {
            update_pt_dist_edit_modal_info();
        }, 100);

    };

    /**
     * Actualiza la informacion de distribucion de planta
     */
    const update_pt_dist_edit_modal_info = async () => {
        
        loading();
        
        const pt_dist_id = document.getElementById('pt_dist_id');

        let body_data = JSON.stringify({                
                            target       : "plant_distribution-update_single",                            
                            info_data    : JSON.parse(localStorage.pt_dist_edit_modal_data),
                            session_token: localStorage.session_token,
                            user_id      : this.state.data.fetched.user_id,
                            form_id      : pt_dist_id.value
                        });
        
        //console.log(body_data);

        try {

            let url = CV_API_URL;
            
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
                    show_notification_message(response.message.capitalize(),'success');

                    clear_pt_dist_info();

                    handle_pt_dist_search_btn();

                    break;
                
                case 401:
                    window.location.href = URL_BASE+"/";   
                    break;
            
                default:
                    show_notification_message(response.message.capitalize(),'warning');
                    break;
            }
        } catch (error) {

            console.log(error);

        }

    };
    
</script>