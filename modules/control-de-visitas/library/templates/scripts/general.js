
const handle_info_save_btn = () => {
    
    //console.log('handle_info_save_btn pressed');
    
    let gral_info = {};

    gral_info.id = document.querySelector('#gral_info_id').value;
    gral_info.business_name = document.querySelector('#gral_info_name').value;
    gral_info.business_phone = document.querySelector('#gral_info_phone').value;
    gral_info.business_address = document.querySelector('#gral_info_address').value;
    gral_info.business_zip_code = document.querySelector('#gral_info_postal_code').value;
    gral_info.business_floor_quanty = document.querySelector('#gral_info_floor_amount').value;
    
    //console.log(gral_info);

    localStorage.gral_info = JSON.stringify(gral_info);

    setTimeout(() => {
        update_business_info();
    }, 100);
    
    
};     

const update_business_info = async () => {
    
    loading();

    try {

        const gral_form_id = document.getElementById('gral_form_id');

        let url = CV_API_URL;
        
        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({                
                target          : "general_settings-update_info",                
                info_data       : JSON.parse(localStorage.gral_info),
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id,
                session_id      : gral_form_id.value
            })  
        });

        const response = await server.json();

        //console.log(response);
        //console.log(server);

        loading();

        switch (server.status) {
            case 200:
                
                show_notification_message(response.message,'success');

                setTimeout(() => {
                    get_general_setting_options();
                }, 100);
                break;

            case 406:

                show_notification_message(response.message,'error');
                console.log(response.message);
                break;
            
            default:
                break;
        }

    } catch (error) {

        console.log(error);

    }

}

const asign_events = () => {

    const   gral_info_phone = document.querySelector('#gral_info_phone');
            gral_info_phone.addEventListener('keyup', (ev) => {                    
                gral_info_phone.value = formatMask(ev.target.value,'000-000-0000');
            } );

    const   gral_info_save_btn = document.querySelector('#gral_info_save_btn');
            gral_info_save_btn.addEventListener('click', handle_info_save_btn );

}; window.addEventListener('DOMContentLoaded', asign_events );

const render_business_info = () => {
    //console.log("renderizando gral_info");

    let gral_info = JSON.parse(localStorage.gral_info_and_settings);

    //console.log(gral_info);
    gral_info.forEach( item => {
        
        if (typeof gral_info == 'object' && null !== item.id && typeof item.id !== 'undefined' )
        {
            document.querySelector('#gral_info_id').value = item.id;
            document.querySelector('#gral_info_name').value = item.business_name;
            document.querySelector('#gral_info_phone').value = item.business_phone;
            document.querySelector('#gral_info_address').value = item.business_address;
            document.querySelector('#gral_info_postal_code').value = item.business_zip_code;
            document.querySelector('#gral_info_floor_amount').value = item.business_floor_quanty;
        }

    });

};

const update_general_setting_options = async () => {

    try {

        const gral_form_id = document.getElementById('gral_form_id');

        let url = CV_API_URL;
        
        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({                
                target          : "general_settings-update_settings",
                //table_name      : CV_SETTING_TABLE,
                info_data       : JSON.parse(localStorage.gral_info_and_settings),
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id,
                form_id         : gral_form_id.value
            })  
        });

        const response = await server.json();

        //console.log(response);
        //console.log(server);

        switch (server.status) {
            case 200:                                            
                setTimeout(() => {                    
                    get_general_setting_options();
                }, 100);
                break;

            case 406:
                console.log(response.message);
                break;
            
            default:
                break;
        }

    } catch (error) {

        console.log(error);

    }
}

const render_allow_impresion = () =>{

    const gral_setting_options_info = JSON.parse(localStorage.gral_info_and_settings);
    const switch_btn = document.querySelector('.switch_btn');
    const allow_impresion = document.getElementById('permitir_impresion');  
    
    //console.log(gral_setting_options_info);
    
    gral_setting_options_info.forEach( (item, key,array) => {
        
        if ( item.printer_id_status == '1' && array.length-1 == key)
        {
            switch_btn.attributes.class.value += " selected";
            allow_impresion.value = 'on';  
        }
        else {                
            allow_impresion.value = 'off';
        }
    });

}

const render_hidden_gral_setting_id = () => {
    
    const gral_info_and_settings = JSON.parse(localStorage.gral_info_and_settings);
    const gral_setting_id = document.getElementById('gral_setting_id');

    gral_info_and_settings.forEach( item => {

        gral_setting_id.value = '';
        
        if(item.printer_id_status == '1')
        {
            gral_setting_id.value = item.printer_id_status;
        }
        

    });
}

const render_get_general_setting_options = () => {

    render_hidden_gral_setting_id();

    render_allow_impresion();

}

const clear_general_setting = () => {

    localStorage.gral_info_and_settings = JSON.stringify({});

}

const get_general_setting_options = async () => {

    clear_general_setting();

    try {

        const gral_form_id = document.getElementById('gral_form_id');

        let url = CV_API_URL;
        
        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({                
                target          : "general_settings-read",
                //table_name      : CV_SETTING_TABLE,
                info_data       : JSON.parse(localStorage.gral_info_and_settings),
                session_token   : localStorage.session_token,
                user_id         : this.state.data.fetched.user_id,
                form_id         : gral_form_id.value
            })  
        });

        const response = await server.json();

        console.log(response);

        switch (server.status) {
            case 200:                   

                if (response.data == "no data")
                {
                    //console.log("no data");
                }
                else
                {
                    //console.log("gral_setting_options_info");
                    //localStorage.gral_info_and_settings_options_info = JSON.stringify(response.data.fetched);
                    console.log("gral_info_and_settings");
                    localStorage.gral_info_and_settings = JSON.stringify(response.data.fetched);
                    localStorage.gral_setting_options_info = JSON.stringify(response.data.fetched);
                    
                    setTimeout(() => {
                        render_get_general_setting_options();
                        render_business_info();
                    }, 100);
                }

                break;
            
            default:
                break;
        }

        } catch (error) {

        console.log(error);

    }

}; window.addEventListener('DOMContentLoaded', get_general_setting_options );

const handle_general_setting_options = () => {

    let allow_impresion = document.getElementById('permitir_impresion').value;  
        allow_impresion = ( allow_impresion == "on" ) ? "1" : "2";

    let gral_setting_id = document.getElementById('gral_setting_id').value;
    
    let gral_setting = JSON.parse(localStorage.gral_info_and_settings);
        
        gral_setting = {
            ...gral_setting,
            id                : gral_setting_id,
            printer_id_status : allow_impresion
        }

    localStorage.gral_info_and_settings = JSON.stringify(gral_setting);

    

    setTimeout(() => {
        update_general_setting_options();
    }, 100);
}

// eventos
$('.switch_btn').on('click', (ev)=>{                         
    
    //console.log(ev);
    ev.target.classList.toggle('selected');
    
    const allow_impresion = document.getElementById('permitir_impresion');        
            
            allow_impresion.value = (allow_impresion.value == 'on') ? 'off' : 'on';
    
    let gral_setting = JSON.parse(localStorage.gral_info_and_settings);
        
        gral_setting = {
            ...gral_setting,
            id                : "",
            printer_id_status : allow_impresion.value,
        };

    localStorage.gral_info_and_settings = JSON.stringify(gral_setting);

    setTimeout(() => {
        handle_general_setting_options();                        
    }, 100);
});

