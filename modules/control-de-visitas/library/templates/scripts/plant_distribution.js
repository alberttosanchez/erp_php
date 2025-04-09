
const handle_add_btn_pt_plant = () => {
    
    //console.log('handle_add_btn_pt_plant pressed');
    
    let pt_dist_info = {};

    //pt_dist_info.id = document.querySelector('#pt_dist_id').value;
    pt_dist_info.department = document.querySelector('#pt_dist_dpto').value.toUpperCase();
    pt_dist_info.floor_location_id = document.querySelector('#pt_dist_location').value;
    pt_dist_info.level_access_id = document.querySelector('#pt_dist_level_access').value;        
    
    //console.log(pt_dist_info);

    localStorage.pt_dist_info = JSON.stringify(pt_dist_info);

    setTimeout(() => {
        put_pt_dist_info();
    }, 100);

};

const handle_pt_dist_search_btn = () => {

    //console.log('handle_pt_dist_search_btn pressed');

    let pt_dist_search_info = {};

    pt_dist_search_info.filter = document.querySelector('#pt_dist_filter_select').value;
    pt_dist_search_info.keyword = document.querySelector('#pt_dist_search_input').value;

    switch (pt_dist_search_info.filter) {
        case "1": pt_dist_search_info.filter = 'id'; break;
        case "2": pt_dist_search_info.filter = 'department'; break;
        case "3": pt_dist_search_info.filter = 'floor_location'; break;
        case "4": pt_dist_search_info.filter = 'level_access'; break;        
        default: break;
    }       

    //console.log(pt_dist_search_info);

    localStorage.pt_dist_search_info = JSON.stringify(pt_dist_search_info);

    localStorage.selected_page = "1";
    localStorage.pt_dist_scroll_ajust = '0';
    
    clear_pt_dist_info_table();

    setTimeout(() => {
        get_pt_dist_search_info();
    }, 100);

}

/**
 * Guarda la informacion de distribucion de planta
 */
const put_pt_dist_info = async () => {
    
    loading();
    
    const pt_dist_id = document.getElementById('pt_dist_id');

    let body_data = JSON.stringify({                
                        target       : "plant_distribution-put_single",                        
                        info_data    : JSON.parse(localStorage.pt_dist_info),
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

const asign_events_on_plant_distribution = () => {

    const   pt_dist_add_btn = document.querySelector('#pt_dist_add_btn');
            pt_dist_add_btn.addEventListener('click', handle_add_btn_pt_plant );
    
    const   pt_dist_search_btn = document.querySelector('#pt_dist_search_btn');
            pt_dist_search_btn.addEventListener('click', handle_pt_dist_search_btn );

}; window.addEventListener('DOMContentLoaded', asign_events_on_plant_distribution );

const render_pt_dist_info = () => {
    //console.log("renderizando pt_dist_info");

    let pt_dist_info = JSON.parse(localStorage.pt_dist_info);

    //console.log(pt_dist_info);

    if (typeof pt_dist_info == 'object' && null !== pt_dist_info.id && typeof pt_dist_info.id !== 'undefined' )
    {
        document.querySelector('#pt_dist_id').value = pt_dist_info.id;
        document.querySelector('#pt_dist_dpto').value = pt_dist_info.department;
        document.querySelector('#pt_dist_location').value = pt_dist_info.floor_location_id;
        document.querySelector('#pt_dist_level_access').value = pt_dist_info.level_access_id;              
    }

};

const clear_pt_dist_info = () => {
    //document.querySelector('#pt_dist_id').value = "";
    document.querySelector('#pt_dist_dpto').value = "";
    document.querySelector('#pt_dist_location').value = "";
    document.querySelector('#pt_dist_level_access').value = ""; 
};

/**
 * Obtener la informacion filtrada.
 */
const get_pt_dist_search_info = async () => {

    loading();
    
    const pt_dist_id = document.getElementById('pt_dist_id');

    let body_data = JSON.stringify({                
        target          : "plant_distribution-read_fromfilters",        
        info_data       : JSON.parse(localStorage.pt_dist_search_info),
        selected_page   : localStorage.selected_page,
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : pt_dist_id.value
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

                localStorage.search_info_from_filter_data = JSON.stringify(response.data.fetched);
                localStorage.selected_page = JSON.stringify(response.data.pagination.next_page);
                
                setTimeout(() => {
                    render_pt_dist_info_table();                        
                }, 100);

                setTimeout( () => {
                    asign_click_event_to_pt_dist_table_row();                        
                }, 300);

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

/**
 * Obtiene la data el departamento seleccionado
 */
const get_pt_dist_search_single_info = async () => {

    //loading();

    let info_data_obj = {
        'id'      : localStorage.pt_dist_edit_modal_id,
        'filter'  : 'id',
        'keyword' : localStorage.pt_dist_edit_modal_id,
    };        

    const pt_dist_id = document.getElementById('pt_dist_id');

    let body_data = JSON.stringify({                
        target          : "plant_distribution-read_singleplantinfo",        
        info_data       : info_data_obj,
        selected_page   : localStorage.single_selected_page,
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : pt_dist_id.value
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
        
        //loading();

        switch (server.status) {
            case 200:

                localStorage.search_info_from_single_data = JSON.stringify(response.data.fetched);
                localStorage.single_selected_page = JSON.stringify(response.data.pagination.next_page);
                
                setTimeout(() => {
                    render_edit_pt_dist_modal();                        
                }, 100);

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

const clear_pt_dist_info_table = () => {

    const pt_dist_table = document.querySelectorAll('#pt_dist_table > tbody > tr');

    pt_dist_table.forEach(item => {
        item.remove();
    });

};

const render_pt_dist_info_table = () => {
    let search_info_from_filter_data = JSON.parse(localStorage.search_info_from_filter_data);

    //console.log(search_info_from_filter_data);

    const pt_dist_table = document.querySelector('#pt_dist_table > tbody');

    search_info_from_filter_data.forEach(item => {
    
    let tr_row = document.createElement('tr');
        tr_row.setAttribute('key',item.id);
    
    let td_one = document.createElement('td');
        td_one.setAttribute('name','id');
        td_one.setAttribute('value',item.id);
        td_one.innerHTML = item.id;

        tr_row.appendChild(td_one);
    
    let td_two = document.createElement('td');
        td_two.setAttribute('name','department');
        td_two.setAttribute('value',item.department);
        td_two.innerHTML = item.department;

        tr_row.appendChild(td_two);
    
    let td_three = document.createElement('td');
        td_three.setAttribute('name','floor_location');
        td_three.setAttribute('value',item.floor_location);
        td_three.innerHTML = item.floor_location;

        tr_row.appendChild(td_three);

    let td_four = document.createElement('td');
        td_four.setAttribute('name','level_access');
        td_four.setAttribute('value',item.level_access);
        td_four.innerHTML = item.level_access;

        tr_row.appendChild(td_four);

    let td_five = document.createElement('td');
    
    /*  let btn_one = document.createElement('button');            
        btn_one.setAttribute('tittle','Ver');
        btn_one.setAttribute('type','button');
        btn_one.setAttribute('value',item.id);
    
    let icon_one = document.createElement('i');
        icon_one.setAttribute('class','fa fa-eye');

        btn_one.appendChild(icon_one);

        td_five.appendChild(btn_one); */

        tr_row.appendChild(td_five);
    //---------------------------------------------
    let btn_two = document.createElement('button');
        btn_two.setAttribute('onclick','pt_dist_edit_info(this)');
        btn_two.setAttribute('tittle','Editar');
        btn_two.setAttribute('type','button');
        btn_two.setAttribute('value',item.id);
        btn_two.setAttribute('data-bs-toggle','modal');
        btn_two.setAttribute('data-bs-target','#pt_dist_edit_modal_wrapper');
    
    let icon_two = document.createElement('i');
        icon_two.setAttribute('class','fa fa-edit');

        btn_two.appendChild(icon_two);

        td_five.appendChild(btn_two);
        
        tr_row.appendChild(td_five);
    //---------------------------------------------
    let btn_three = document.createElement('button');
        btn_three.setAttribute('onclick','pt_dist_delete_info(this)');
        btn_three.setAttribute('tittle','Eliminar');
        btn_three.setAttribute('type','button');
        btn_three.setAttribute('value',item.id);
        btn_three.setAttribute('data-bs-toggle','modal');
        btn_three.setAttribute('data-bs-target','#del_pt_dist_modal_wrapper');
    
    let icon_three = document.createElement('i');
        icon_three.setAttribute('class','fa fa-times');

        btn_three.appendChild(icon_three);

        td_five.appendChild(btn_three);
        
        tr_row.appendChild(td_five);

        pt_dist_table.appendChild(tr_row);
        
    });

};    

const asign_click_event_to_pt_dist_table_row = () => {

    let pt_dist_table_row = document.querySelectorAll('#pt_dist_table tbody > tr');

    for (let i=0; i < pt_dist_table_row.length; i++){

        pt_dist_table_row[i].addEventListener('click', () => {
            
            //console.log( pt_dist_table_row[i].getAttribute('key') ); 

            let clicked_id = localStorage.pt_dist_table_item_key_value = pt_dist_table_row[i].getAttribute('key');
            
        });

    };

};

const pt_dist_edit_info = (e) => {
    
    localStorage.pt_dist_edit_modal_id = e.value;
    localStorage.single_selected_page = '1';

    setTimeout(() => {
        get_pt_dist_search_single_info();
    }, 100);        

};

/**
 * Obtiene la lista de opciones para filtrar la busqueda
 */
const get_pt_dist_filter_option_list = async () => {


    const pt_dist_id = document.getElementById('pt_dist_id');

    // preparamos un objeto json con los datos a enviar
    let body_data = JSON.stringify({                
                        target : "plant_distribution-read_searchfilters",                                                  
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,
                        form_id : pt_dist_id.value
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
        
        switch (server.status) {
            case 200:                
                localStorage.pt_dist_filter_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {
                    render_pt_dist_filter_option_list();
                }, 100);
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
}; window.addEventListener( 'DOMContentLoaded' , get_pt_dist_filter_option_list ); 

const render_pt_dist_filter_option_list = () => {
    
    let pt_dist_filter_category = JSON.parse(localStorage.pt_dist_filter_category);
    //console.log(pt_dist_filter_category);

    const pt_dist_filter_select = document.querySelector('#pt_dist_filter_select');

    
    pt_dist_filter_category.forEach(item => {
        let option = document.createElement('option');
            option.setAttribute('key', item.id);
            option.setAttribute('value', item.id);
            option.innerHTML = item.filter_name.capitalize();

            pt_dist_filter_select.appendChild(option);            
    });
    
};

/**
 * Obtiene la lista de niveles de acceso
 */
const get_pt_dist_level_access_list = async () => {

    //loading();

    const pt_dist_id = document.getElementById('pt_dist_id');

    let body_data = JSON.stringify({                
                        target : "plant_distribution-read_levelaccess",                        
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,
                        form_id : pt_dist_id.value
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
        
        //loading();

        switch (server.status) {
            case 200:

                localStorage.level_access_data = JSON.stringify(response.data.fetched);

                setTimeout(() => {
                    render_pt_dist_level_access_data();
                }, 100);
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

}; window.addEventListener( 'DOMContentLoaded' , get_pt_dist_level_access_list );

/**
 * Obtiene la lista de los pisos
 */
const get_pt_dist_floor_location_list = async () => {
    //loading();

    const pt_dist_id = document.getElementById('pt_dist_id');
    let body_data = JSON.stringify({                
                        target : "plant_distribution-read_floorlocations",                        
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,
                        form_id : pt_dist_id.value
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
        
        //loading();

        switch (server.status) {
            case 200:

                localStorage.floor_location_data = JSON.stringify(response.data.fetched);
                
                setTimeout(() => {
                    render_pt_dist_add_info();
                }, 100);
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
}; window.addEventListener( 'DOMContentLoaded' , get_pt_dist_floor_location_list );

const clean_pt_dist_add_info = () => {

    let pt_dist_location_op = document.querySelectorAll('#pt_dist_location > option');

    if ( pt_dist_location_op.length > 0){
        
        pt_dist_location_op.forEach( element => {
            element.remove();
        });
    }
}
const render_pt_dist_add_info = () => {        
    
    clean_pt_dist_add_info();

    JSON.parse(localStorage.floor_location_data).forEach( item => {

        let pt_dist_location = document.querySelector('#pt_dist_location');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.floor_location;
                    
            pt_dist_location.appendChild(option);
            
    });            

}; 

const clean_pt_dist_level_access_data = () => {

    let pt_dist_level_access_op = document.querySelectorAll('#pt_dist_level_access > option');

    if ( pt_dist_level_access_op.length > 0){

        pt_dist_level_access_op.forEach( element => {
            element.remove();
        });
    }
}

const render_pt_dist_level_access_data = () => {        
    
    clean_pt_dist_level_access_data();

    JSON.parse(localStorage.level_access_data).forEach( item => {
        let pt_dist_level_access = document.querySelector('#pt_dist_level_access');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.level_access;
            
            pt_dist_level_access.appendChild(option);
            
    });
        

}; 

const infinityScroll = (e) =>
{        
    //console.log('get_pt_dist_search_info');

    let parent = e.parentNode;

    if( e.scrollTop + e.offsetHeight - localStorage.pt_dist_scroll_ajust > parent.offsetHeight )
    {            
        localStorage.pt_dist_scroll_ajust = localStorage.pt_dist_scroll_ajust + (e.scrollHeight < 900) ? e.scrollHeight : 0;            
        setTimeout(() => {
            if ( JSON.parse(localStorage.selected_page) !== "" )
            {
                get_pt_dist_search_info();
            }
        }, 100);
    }        
}

let pt_dist_table_box = document.querySelector(".pt_dist_table_box.pt_dist_box");
    pt_dist_table_box.addEventListener( 'scroll' , (e)=>infinityScroll(pt_dist_table_box),false);

//window.addEventListener('scroll', onScroll) // llamamos a onScroll cuando el usuario hace scroll

