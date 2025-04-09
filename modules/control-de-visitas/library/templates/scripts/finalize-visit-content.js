const reRenderFinalizeAllVisitButton = () => {

    let selectedVisitants = JSON.parse(localStorage.selectedVisitants);

    const fn_vt_all_visits_btn = document.getElementById('fn_vt_all_visits_btn');
    const fnall_modal_title = document.querySelector('#co_fnall_modal_wrapper .co_title_bar');
    const fnall_modal_message = document.querySelector('#co_fnall_modal_wrapper .co_fnall_modal_one_box p');
    

    if ( selectedVisitants.selected_visitants.length > 0)
    {
        fn_vt_all_visits_btn.innerHTML = 'FINALIZAR VISITAS SELECCIONADAS';
        fnall_modal_title.innerHTML = 'ADVERTENCIA - MENSAJE DE FINALIZACION DE VISITAS SELECCIONADAS';
        fnall_modal_message.innerHTML = 'Esta a punto de Finalizar las <b>visitas activas seleccionadas.</b><br>Esta acción no podrá deshacerse.';
    }
    else
    {
        fn_vt_all_visits_btn.innerHTML = 'FINALIZAR TODAS LAS VISITAS';
        fnall_modal_title.innerHTML = 'ADVERTENCIA - MENSAJE DE FINALIZACION DE VISITAS';
        fnall_modal_message.innerHTML = 'Esta a punto de Finalizar <b>todas las visitas activas.</b><br>Esta acción no podrá deshacerse.';
    }

}

const handleSelectedRow = (id_visitant,element) => {

        //console.log(element);

    let obj = JSON.parse(localStorage.selectedVisitants);

    let element_was_checked = element.checked;

        //console.log('element_was_checked',element_was_checked);

    let selectedVisitants = { 'selected_visitants' : [] };

    if ( 
        obj != null && typeof obj == 'object' && obj.hasOwnProperty('selected_visitants')
    )
    {
        selectedVisitants = JSON.parse(localStorage.selectedVisitants);
            
    }
    
    if (element_was_checked)
    {
        
        let id_was_inserted = false;
        for (let u = 0; u < selectedVisitants.selected_visitants.length; u++) {
            
            if (selectedVisitants.selected_visitants[u].id_visitant == id_visitant)
            {
                id_was_inserted = true;
            }
            else if (
                !id_was_inserted && 
                (u+1 == selectedVisitants.selected_visitants.length)                            
            )
            {
                selectedVisitants.selected_visitants[u+1] = { 'id_visitant' : id_visitant }
            }
        }
        
        if ( selectedVisitants.selected_visitants.length == 0)
        {
            selectedVisitants.selected_visitants[0] = { 'id_visitant' : id_visitant }   
        }
                    
    }
    else
    {
        // removemos el id del visitante del objeto        
        for (let u = 0; u < selectedVisitants.selected_visitants.length; u++) {
                        
            if (selectedVisitants.selected_visitants[u].id_visitant == id_visitant)
            {
                // elimina un elemento del array
                selectedVisitants.selected_visitants.splice(u,1);
            }
            
        }


    }
        
    //console.log(selectedVisitants);
    localStorage.selectedVisitants = JSON.stringify(selectedVisitants);

    reRenderFinalizeAllVisitButton();
};

const render_fn_vt_visitants_data_fetched = () => {

    let fn_vt_visitants_data_fetched = JSON.parse(localStorage.fn_vt_visitants_data_fetched);    

    let obj = JSON.parse(localStorage.all_visitants_data_fetched);

    if ( obj != null && typeof obj == 'object' && obj.hasOwnProperty('fetched') )    
    {
        obj = {
            ...obj,                        
        }
            
        let last = 0;
        if ( typeof fn_vt_visitants_data_fetched.fetched != 'undefined' )
        {
            last = fn_vt_visitants_data_fetched.fetched.length;
        }

        fn_vt_visitants_data_fetched.fetched.forEach( item => {
            obj.fetched.splice(last,0,item);            
        });
    }
    else
    {
        obj = fn_vt_visitants_data_fetched;
    }
    
    setTimeout(() => {
        localStorage.all_visitants_data_fetched = JSON.stringify(obj);                    
    }, 100);


    if (typeof fn_vt_visitants_data_fetched == 'object' && Object.keys(fn_vt_visitants_data_fetched).length > 0  )
    {

        //console.log(JSON.parse(localStorage.fn_vt_visitants_data_fetched));

        const fn_vt_tbody = document.querySelector('#cv_finalize_visit_wrapper > table > tbody');
        
        fn_vt_visitants_data_fetched.fetched.forEach( item => {
           
            let tr = document.createElement('tr');
                tr.setAttribute('key',`${item.id_visitant}`);
                tr.setAttribute('onclick',`assign_fn_vt_click_event_on_table_row(this)`);

            let html = `<td>
                            <input onclick="handleSelectedRow(${item.id_visitant},this)" type="checkbox" id="check_${item.id_visitant}" />
                        </td>
                        <td>${item.name} ${item.last_name}</td>
                        <td>${item.ident_number}</td>
                        <td>${item.cw_raw_department} - ${item.floor_location}</td>
                        <td>${item.cw_raw_full_name}</td>
                        <td>${item.last_visit_date}</td>`;
            
                tr.innerHTML = html;

            fn_vt_tbody.appendChild(tr);        

        });
    }   

};

const assign_fn_vt_click_event_on_table_row = (DOMElement) => {
    
    localStorage.fn_vt_single_data_choosen = DOMElement.getAttribute('key');

    setTimeout(() => {
        render_fn_vt_single_data_fetched();
        //get_fn_vt_single_data_fetched();
    }, 300);
        
    
};

const render_fn_vt_single_data_fetched = () => {

    let fn_vt_single_visitants_data_fetched = JSON.parse(localStorage.fn_vt_visitants_data_fetched);

    let obj = JSON.parse(localStorage.all_visitants_data_fetched);

    if ( obj != null && typeof obj == 'object' && obj.hasOwnProperty('fetched') )    
    {
        obj = {
            ...obj,            
        }
    }
    else
    {
        obj = fn_vt_single_visitants_data_fetched;
    }    

    let fn_name          = document.querySelector('#fn_name');
    let fn_last_name     = document.querySelector('#fn_last_name');
    let fn_gender        = document.querySelector('#fn_gender');
    let fn_id_code       = document.querySelector('#fn_id_code');
    let fn_id_type       = document.querySelector('#fn_id_type');
    let fn_birth_date    = document.querySelector('#fn_birth_date');
    let fn_gun           = document.querySelector('#fn_gun');
    let fn_gun_status    = document.querySelector('#fn_gun_status');
    let fn_gun_status_op = document.querySelectorAll('#fn_gun_status > option');
    let fn_observations  = document.querySelector('#fn_observations');
    let fn_visit_info_id = document.querySelector('#fn_visit_info_id');
    
    
    obj.fetched.forEach( item => {
        
        if ( item.id_visitant == localStorage.fn_vt_single_data_choosen )
        {
            fn_name.value = item.name;
            fn_last_name.value = item.last_name;
            fn_gender.value = item.gender;
            fn_id_code.value = item.ident_number;
            fn_id_type.value = item.identification_type;
            fn_birth_date.value = item.birth_date;
            fn_gun.value = (item.has_gun == '1') ? 'Si' : 'No';

            fn_gun_status_op.forEach( element => {
                // selecciona la opcion que coincida con el id
                if ( element.value == item.gun_status_id ) element.selected = true;

            });                

            fn_gun_status.disabled = (item.has_gun == '1') ? false : true;

            fn_visit_info_id.value = item.visit_info_id;                
        }

        localStorage.gun_status_id = item.gun_status_id;
    });
    
    let fn_vt_btn = document.querySelector('#fn_vt_btn');
        fn_vt_btn.disabled = false;

};

const get_fn_vt_visitants_data = async () => {
    
    loading();

    localStorage.fn_vt_visitants_data_fetched = "";

    const fn_form_id = document.getElementById('fn_form_id');

    let body_data = JSON.stringify({                
        target          : "manage_visitants-read_fromfilters",        
        info_data       : JSON.parse(localStorage.fn_vt_search_params),
        selected_page   : localStorage.fn_vt_selected_page,
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : fn_form_id.value
    });

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
                if (response.message == "Datos Recuperados")
                {
                    localStorage.fn_vt_visitants_data_fetched = JSON.stringify(response.data);
                    localStorage.fn_vt_selected_page = JSON.stringify(response.data.pagination.next_page);

                    setTimeout(() => {
                        show_notification_message(response.message.capitalize(),'success');    
                        render_fn_vt_visitants_data_fetched();                            
                    }, 100);                    

                }
                else if (response.message == "no data")
                {                    
                    show_notification_message('No hay Resultados'.capitalize(),'warning');
                }
                else
                {
                    show_notification_message(response.message.capitalize(),'error');
                }


                break;
            
            case 401:
                window.location.href = '/';
                break;
        
            default:
                show_notification_message(response.message.capitalize(),'error');
                break;
        }
    } catch (error) {

        console.log(error);

    }

};

const handle_fn_visit_search_btn = () => {

    localStorage.selectedVisitants = null;
    localStorage.all_visitants_data_fetched = null;
    
    console.log('handle_fn_visit_search_btn clicked');

    clean_fn_vt_visitants_data_fetched();
    clean_fn_vt_single_data_fetched();

    let fn_vt_search_params = {};

    let fn_vt_input_search = document.querySelector('#fn_vt_input_search');

    let fn_vt_input_search_radios = document.querySelectorAll('.fn_visit_search_box .queries_filters input[type=radio]');

    fn_vt_search_params.ident_number = fn_vt_input_search.value;

    fn_vt_input_search_radios.forEach( item => {
        if ( item.checked == true ) fn_vt_search_params.ident_type_id = item.value;
    });
    
    localStorage.fn_vt_search_params = JSON.stringify(fn_vt_search_params);

    localStorage.fn_vt_selected_page = "1";
    localStorage.fn_vt_scroll_ajust = '0';

    setTimeout(() => {
        get_fn_vt_visitants_data();
    }, 100);

};

const clean_fn_vt_visitants_data_fetched = () => {

    localStorage.fn_vt_visitants_data_fetched = "{}";

    let fn_vt_table_row = document.querySelectorAll('.cv_finalize_visit_wrapper table tbody tr');

        fn_vt_table_row.forEach( item => { item.remove(); });

};

const clean_fn_vt_single_data_fetched = () => {

    let fn_vt_info_inputs = document.querySelectorAll('#cv_finalize_visit_info_wrapper input');

        fn_vt_info_inputs.forEach( item => {

            if (item.id != 'fn_form_id')
            {
                item.value = "";
            }
        });

    let fn_gun_status = document.querySelectorAll('#fn_gun_status option');
        
        fn_gun_status.forEach( item => {                
            if ( item.value == "1" ) item.selected = true;
        });

    document.querySelector('#fn_vt_btn').disabled = true;
    
    document.querySelector('#fn_observations').value = "";

};

const push_fn_all_visits_data = async () => {

    loading();

    const fn_form_id = document.getElementById('fn_form_id');

    let body_data = JSON.stringify({                
        target          : "manage_visitants-finalize_all_visits",        
        info_data       : JSON.parse( localStorage.selectedVisitants ),            
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : fn_form_id.value
    });

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
                if (response.message == "Visitas Finalizadas")
                {                                                
                    clean_fn_vt_visitants_data_fetched();
                    clean_fn_vt_single_data_fetched();

                    setTimeout(() => {
                        render_fn_vt_visitants_data_fetched();

                        // cargamos loa visitantes del dia
                        setTimeout(() => {
                            get_db_active_visitants();            
                        }, 100);
                    }, 500);

                    show_notification_message('Visitas Finalizadas'.capitalize(),'success');

                }
                else
                {
                    show_notification_message(response.message.capitalize(),'warning');
                }
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

const push_fn_vt_single_data = async () => {

    loading();

    const fn_form_id = document.getElementById('fn_form_id');

    let body_data = JSON.stringify({                
        target          : "manage_visitants-finalize_visit",        
        info_data       : JSON.parse( localStorage.fn_visit_data ),            
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : fn_form_id.value
    });

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
                if (response.message == "Visita Finalizada")
                {                                                
                    clean_fn_vt_visitants_data_fetched();
                    clean_fn_vt_single_data_fetched();

                    setTimeout(() => {
                        render_fn_vt_visitants_data_fetched();

                        // cargamos loa visitantes del dia
                        setTimeout(() => {
                            get_db_active_visitants();            
                        }, 100);
                    }, 500);

                    show_notification_message('Visita Finalizada'.capitalize(),'success');

                }
                else
                {
                    show_notification_message(response.message.capitalize(),'warning');
                }
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

const handle_fn_vt_all_visits_btn = () =>{

    push_fn_all_visits_data();
}

const handle_fn_visit_btn = () => {                

    //console.log('handle_fn_visit_btn');

    let fn_visit_data = {
        id            : document.getElementById('fn_visit_info_id').value,
        gun_status_id : document.getElementById('fn_gun_status').value,
        end_comments  : document.getElementById('fn_observations').value,        
    };

    localStorage.fn_visit_data = JSON.stringify(fn_visit_data);

    setTimeout(() => {
        push_fn_vt_single_data();            
    }, 100);


};

const handle_fn_vt_clean_btn = () => {
    clean_fn_vt_visitants_data_fetched();
    clean_fn_vt_single_data_fetched();
};

const fn_visit_assign_events = () => {

    let fn_visit_search_btn = document.querySelector('#fn_visit_search_btn');
        fn_visit_search_btn.addEventListener('click' , handle_fn_visit_search_btn );

    let fn_vt_btn = document.querySelector('#fn_vt_btn');
        fn_vt_btn.addEventListener('click' , handle_fn_visit_btn );

}; window.addEventListener('DOMContentLoaded', fn_visit_assign_events );


const assign_start_events = () => {

    localStorage.selectedVisitants = null;

    const fn_vt_clean_btn = document.querySelector('#fn_vt_clean_btn');
            fn_vt_clean_btn.addEventListener('click' , handle_fn_vt_clean_btn );

    const co_fnall_modal_confirm_btn = document.getElementById('co_fnall_modal_confirm_btn');
          co_fnall_modal_confirm_btn.addEventListener('click' , handle_fn_vt_all_visits_btn );

}; window.addEventListener('DOMContentLoaded', assign_start_events );

const fn_vt_infinityScroll = (e) =>
{
    let parent = e.parentNode;
    //console.log(e.scrollTop + e.offsetHeight - localStorage.fn_vt_scroll_ajust > parent.offsetHeight-50);
    if( e.scrollTop + e.offsetHeight - localStorage.fn_vt_scroll_ajust > parent.offsetHeight-50 )
    {            
        //console.log( e.scrollTop + e.offsetHeight - localStorage.fn_vt_scroll_ajust > parent.offsetHeight-50 );
        localStorage.fn_vt_scroll_ajust = localStorage.fn_vt_scroll_ajust + (e.scrollHeight < 900) ? e.scrollHeight : 0;            
        setTimeout(() => {
            if ( JSON.parse(localStorage.fn_vt_selected_page) !== "" )
            {
                get_fn_vt_visitants_data();
            }
        }, 100);
    }        
}; let cv_finalize_visit_wrapper = document.querySelector(".cv_finalize_visit_wrapper"); cv_finalize_visit_wrapper.addEventListener( 'scroll' , (e)=>fn_vt_infinityScroll(cv_finalize_visit_wrapper),false);


