<style>
    .pt_dist_del_modal_group_wrapper {
        width: 100%;
        max-width: 600px;
    }
    .pt_dist_del_modal_content_box {
        border-radius: unset;
    }
    .pt_dist_del_modal_title_bar_box{
        display: flex;
        justify-content: space-between;
        padding: 3px;        
        background-color: red;
        align-items: center;
        color: white;
    }
    .pt_dist_del_modal_title_bar_box p{
        margin: 0;
        font-size: 12px;
        font-weight: bold;        
    }
    .pt_dist_del_modal_body_wrapper{
        display: flex;
        align-items: center;
        flex-direction: column;
        padding: 20px;
    }
    .pt_dist_del_modal_one_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .pt_dist_del_modal_one_box_group > div {
        width: 100%;
        max-width: calc(50% - 5px);
    }
    .pt_dist_del_modal_two_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .pt_dist_del_modal_two_box_group > div {
        width: 100%;
        max-width: calc(25% - 5px);
    }
    .pt_dist_del_modal_three_box_group {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }   
    .pt_dist_del_modal_three_box_group > div {
        width: 100%;
        max-width: calc(33.33% - 5px);
    }
    .pt_dist_del_modal_three_box_group > div:nth-child(1),
    .pt_dist_del_modal_three_box_group > div:nth-child(2) {        
        width: 100%;
        max-width: calc(40% - 5px);
    }  
    .pt_dist_del_modal_three_box_group > div:nth-child(3){
        width: 100%;
        max-width: calc(20% - 5px);
    }
    .pt_dist_del_modal_four_box_group{
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .pt_dist_del_modal_four_box_group > div:nth-child(1) {
        width: 100%;
        max-width: calc(60% - 5px);
    }
    .pt_dist_del_modal_four_box_group > div:nth-child(2) {
        display: flex;
        max-width: calc(20% - 5px);
        align-items: end;
    }
    .pt_dist_del_modal_two_box.box {
        display: flex;
        width: 100%;
        justify-content: space-around;
    }
    @media screen and (max-width:769px){
        .pt_dist_del_modal_one_box_group > div,
        .pt_dist_del_modal_two_box_group > div,
        .pt_dist_del_modal_three_box_group > div,
        .pt_dist_del_modal_four_box_group > div {
            max-width: 100% !important;
        }
        .pt_dist_del_modal_four_box_group > div:nth-child(2) {            
            width: 100%;            
            justify-content: center;
            margin-top: 10px;
        }
    }
</style>
<!-- Modal -->
<div class="pt_dist_del_modal_wrapper modal fade" id="del_pt_dist_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="del_pt_dist_modalLabel" aria-hidden="true">
    <div class="pt_dist_del_modal_group_wrapper modal-dialog">
        <div class="pt_dist_del_modal_content_box modal-content">

            <div class="pt_dist_del_modal_title_bar_box form-group">
                <p><span class="pt_dist_title_bar">ADVERTENCIA - MENSAJE DE ELIMINACION DE DISTRIBUCION EN PLANTA</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="pt_dist_del_modal_body_wrapper pt_dist_body_wrapper form-group">

                <div class="pt_dist_del_modal_one_box box form-group">
                    <p>Esta a punto de Eliminar la distribución en Planta cuyo ID es <span class="pt_dist_del_id"></span></p>
                </div>

                <div class="pt_dist_del_modal_two_box box form-group">

                    <button type="button" id="pt_dist_del_modal_cancel_btn" name="pt_dist_del_modal_cancel_btn" data-bs-dismiss="modal" class="btn btn-secondary">CANCELAR</button>
                    <button type="button" id="pt_dist_del_modal_confirm_btn" value="" name="pt_dist_del_modal_confirm_btn" data-bs-dismiss="modal" class="btn btn-danger">CONFIRMAR</button>

                </div>

            </div>
            
        </div>
    </div>
</div>

<script>

    const handle_pt_dist_single_delete_btn = () => {
        let pt_dist_del_modal_confirm_btn = document.querySelector('#pt_dist_del_modal_confirm_btn').value;

        localStorage.pt_dist_del_modal_confirm_btn = pt_dist_del_modal_confirm_btn;
        
        setTimeout(() => {
            pt_dist_del_single_info();            
        }, 100);

    }; document.querySelector('#pt_dist_del_modal_confirm_btn').addEventListener('click', handle_pt_dist_single_delete_btn );

    const pt_dist_del_single_info = async () => {

        loading();

        const pt_dist_id = document.getElementById('pt_dist_id');

        let info_data_obj = {
            'id'        : localStorage.pt_dist_del_modal_confirm_btn,
        }; 
        
        let body_data = JSON.stringify({                
                            target          : "plant_distribution-del_single",                            
                            info_data       : info_data_obj,
                            session_token   : localStorage.session_token,
                            user_id         : this.state.data.fetched.user_id,
                            form_id         : pt_dist_id.value,            
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
                    actionMessage(response.message.capitalize(),'warning');
                    localStorage.pt_dist_del_modal_confirm_btn = "";
                    if (response.message == "datos eliminados")
                    {
                        handle_pt_dist_search_btn();
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

    const pt_dist_delete_info = () => {
        //console.log('pt_dist_delete_info');
        setTimeout(() => {
            document.querySelector('.pt_dist_del_id').innerHTML = localStorage.pt_dist_table_item_key_value;            
            document.querySelector('#pt_dist_del_modal_confirm_btn').value = localStorage.pt_dist_table_item_key_value;            
        }, 100);
    };

    const asign_click_event_to_pt_dist_delete_modal_btn = () => {

        const pt_dist_del_modal_confirm_btn = document.querySelector('#pt_dist_del_modal_confirm_btn');

    }; window.addEventListener('DOMContentLoaded', asign_click_event_to_pt_dist_delete_modal_btn );

</script>