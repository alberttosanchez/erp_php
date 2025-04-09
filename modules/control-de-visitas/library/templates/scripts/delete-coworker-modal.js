const co_delete_modal_single_info = async () => {

    loading();

    let info_data_obj = {
        'id'        : localStorage.co_del_single_id,        
    }; 

    const co_del_form_id = document.getElementById('co_del_form_id');

    let body_data = JSON.stringify({                
        target          : "coworkers-delete_single",        
        info_data       : info_data_obj,
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : co_del_form_id.value
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
        //console.log(server);
        
        if (server.status == 200)
        {
            
            loading();

            switch (server.status) {
                case 200:                    
                    localStorage.pt_dist_del_modal_confirm_btn = "";
                    handle_co_keyword_btn();                    
                    show_notification_message("Datos eliminados.","success");
                    break;
                
                case 401:
                    window.location.href = URL_BASE+"/";   
                    break;
            
                default:
                    show_notification_message(response.message.capitalize(),'warning');
                    break;
            }
        }
        else if (server.status == 401)
        {
            window.location.href = URL_BASE+"/";   
        }


    } catch (error) {
        console.log(error);
    }

};

const render_co_del_modal_single_info = () => {

    let co_del_single_id = localStorage.co_del_single_id;
    
    document.querySelector('.co_del_show_id').innerHTML = co_del_single_id;
    document.querySelector('#co_del_single_id').value = co_del_single_id;

};

const handle_co_del_modal_confirm_btn = () => {
    
    localStorage.co_del_single_id = document.querySelector('#co_del_single_id').value;

    setTimeout(() => {
        co_delete_modal_single_info();            
    }, 100);

};

const asign_co_del_modal_events = () => {
    const co_del_modal_confirm_btn = document.querySelector('#co_del_modal_confirm_btn');
co_del_modal_confirm_btn.addEventListener( 'click', handle_co_del_modal_confirm_btn );

}; window.addEventListener( 'DOMContentLoaded', asign_co_del_modal_events );

