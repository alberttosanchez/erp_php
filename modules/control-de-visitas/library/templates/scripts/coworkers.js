
/**
 * Obtiene los tipos de generos
 */
const co_get_genders = async () => {

    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
                        target : "plant_distribution-read_genders",                        
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,                        
                        form_id : co_form_id.value
                    });
    
    //console.log(body_data);

    try {

        let url = CV_API_URL;
        

        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body    : body_data,
            
        });

        const response = await server.json();

        //console.log(response);
        
        switch (server.status) {
            case 200:
                localStorage.co_genders_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {
                    //render_co_genders_list();
                    render_co_new_modal_genders_list();
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

}; window.addEventListener('DOMContentLoaded', co_get_genders );

/**
 * Obtiene los tipos de identificacion
 */
const co_get_id_type = async () => {

    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
                        target       : "plant_distribution-read_identificationtype",                                         
                        session_token: localStorage.session_token,
                        user_id      : this.state.data.fetched.user_id,
                        form_id      : co_form_id.value
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
                localStorage.co_identification_type_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {
                    //render_co_genders_list();                        
                    render_co_new_modal_ident_type_list();                        
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

}; window.addEventListener('DOMContentLoaded', co_get_id_type );

/**
 * Obtiene la distribucion en planta
 */
const co_get_plant_distibution = async () => {

    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
                        target : "plant_distribution-read_plantdistribution",                        
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,                        
                        form_id : co_form_id.value
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
                localStorage.co_plant_distribution_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_co_new_modal_plan_dist_list();
                    //render_reg_contact_dpto_list();
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

}; window.addEventListener('DOMContentLoaded', co_get_plant_distibution );

/**
 * 
 */
const get_co_search_filter_data = async () => {

    loading();

    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
        target          : "coworkers-read_fromfilters",        
        info_data       : JSON.parse(localStorage.co_search_info),
        selected_page   : localStorage.co_selected_page,
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : co_form_id.value
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

                localStorage.co_search_info_from_filter_data = JSON.stringify(response.data.fetched);
                localStorage.co_selected_page = JSON.stringify(response.data.pagination.next_page);
                
                setTimeout(() => {
                    render_co_info_table();                        
                }, 100);

                /* setTimeout( () => {
                    asign_click_event_to_co_table_row();                        
                }, 300); */

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
 * Renderiza la informacion consultada en la tabla
 */
const render_co_info_table = () => {

    let co_search_info_from_filter_data = JSON.parse(localStorage.co_search_info_from_filter_data);

    const co_table = document.querySelector('#co_table > tbody');

    let html = "";
    co_search_info_from_filter_data.forEach(item => {

        html += 
        
        `<tr key="${item.id}" onclick="handle_co_worker_details(${item.id})">
            <td name="id" value="${item.id}">${item.id}</td>
            <td name="identification_id" value="${item.identification_id}">${item.identification_id}</td>
            <td name="name" value="${item.name}">${item.name}</td>
            <td name="last_name" value="${item.last_name}">${item.last_name}</td>
            <td name="gender" value="${item.gender}">${item.gender}</td>
            <td name="birth_date" value="${item.birth_date}">${item.birth_date}</td>
            <td>
                <button 
                    type="button"
                    onclick="co_handle_edit_info(${item.id})"
                    tittle="Editar"
                    data-bs-toggle="modal"
                    data-bs-target="#new_coworker_modal_wrapper"
                    value="${item.id}"
                ><i class="fa fa-edit"></i></button>
                <button 
                    type="button"
                    onclick="co_delete_info(${item.id})"
                    tittle="Eliminar"
                    data-bs-toggle="modal"
                    data-bs-target="#co_del_coworker_modal_wrapper"
                    value="${item.id}"
                ><i class="fa fa-times"></i></button>
            </td>
        </tr>`;

        co_table.innerHTML = html;
    });
}

/**
 * Almacena en localstorage los datos de la fila actual 
 */
const storageCurrentRow = (coworker_id) => {

    var current_row = "";

    JSON.parse(localStorage.co_search_info_from_filter_data).forEach( item => {
        
        localStorage.co_view_current_row = "";

        if (item.id == coworker_id)
        {
            
            current_row = JSON.stringify({
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
                'department'            : item.department,
                'phone_extension'       : item.phone_extension,
                'job_email'             : item.job_email,
                'photo_path'            : item.photo_path,
            });            

            localStorage.co_view_current_row = current_row;

        }
        
    });

    return current_row;
}

/**
 * Muestra la imagen temporal en la informacion rapida 
 */
const co_show_temp_photo = (bool = true) =>{

    
    if (bool)
    {    
        document.querySelector(".co_photo_wrapper #photo_img_picture").setAttribute('class','');
        document.querySelector(".co_photo_wrapper #image_icon").setAttribute('class','user_icon no-show');
    }
    else
    {
        document.querySelector(".co_photo_wrapper #image_icon").setAttribute('class','user_icon');
        document.querySelector(".co_photo_wrapper #photo_img_picture").setAttribute('class','no-show');
    }
}

/**
 * Renderiza la imagen temporal en la informacion rapida 
 */
const render_co_photo_info_form = (coworker_id) =>{

    console.log('render_co_photo_info_form');
    
    let co_photo_info_current_row = JSON.parse(storageCurrentRow(coworker_id));
    
    console.log(co_photo_info_current_row);
    
    let photo_path = JSON.parse(co_photo_info_current_row.photo_path);
    
    let filename = photo_path.filename;
    let public_url = photo_path.public_url;

    if ( filename && public_url )
    {
        let photo_img_picture = document.querySelector(".co_photo_wrapper #photo_img_picture");
            photo_img_picture.src = `${public_url}/${filename}`;

        // ver input-file-cropper.js
        co_show_temp_photo(true);
    }
    else{
        co_show_temp_photo(false);
    }

}

/**
 * Renderiza la informacion del colaborador al seleccionarla en el area detallada 
 */
const render_co_view_form = (coworker_id) => {
    
    let co_view_current_row = JSON.parse(storageCurrentRow(coworker_id));

    //let co_view_current_row = JSON.parse(localStorage.co_view_current_row);            

    document.querySelector('#co_info_id').value = co_view_current_row.id;
    document.querySelector('#co_info_name').value = co_view_current_row.name;
    document.querySelector('#co_info_last_name').value = co_view_current_row.last_name;
    
    document.querySelector('#co_info_ident').value = co_view_current_row.identification_id;

    document.querySelector('#co_info_birth_date').value = co_view_current_row.birth_date;
    document.querySelector('#co_info_dpto').value = co_view_current_row.department;
    
    
    
    JSON.parse(localStorage.co_genders_category).forEach( item => {
        let co_info_gender = document.querySelector('#co_info_gender');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.gender;
            
            if (item.id == co_view_current_row.gender_id){
                option.selected = true;
            };
        
            co_info_gender.appendChild(option);
            
    });      

    JSON.parse(localStorage.co_identification_type_category).forEach( item => {
        
        let co_info_id_type = document.querySelector('#co_info_id_type');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.identification_type;
            
            if (item.id == co_view_current_row.identification_type_id){
                option.selected = true;
            };
        
            co_info_id_type.appendChild(option);
            
    });
};

const co_delete_info = (coworker_id) => {

    localStorage.co_del_single_id = coworker_id;

    setTimeout(() => {
        render_co_del_modal_single_info();
    }, 100);

};

const render_co_edit_modal_form = () => {

    let co_title_bar = document.querySelector('.co_title_bar');
        co_title_bar.innerHTML = 'EDITAR COLABORADOR';

    let co_modal_register_btn = document.querySelector('.co_modal_register_btn');
        co_modal_register_btn.innerHTML = 'ACTUALIZAR';

}

const co_handle_edit_info = (coworker_id) => {
    
    // ver edit-coworker-modal.js
    render_co_edit_view_form(coworker_id);
    render_co_edit_photo_view_form(coworker_id);

    render_co_edit_modal_form();
    
};

const handle_co_keyword_btn = () => {
    //console.log('handle_co_keyword_btn');

    let co_search_info = {};

    co_search_info.filter = document.querySelector('#co_filter_select').value;
    co_search_info.keyword = document.querySelector('#co_keyword_input').value;    

    localStorage.co_search_info = JSON.stringify(co_search_info);

    localStorage.co_selected_page = "1";
    localStorage.co_scroll_ajust = '0';

    clear_co_info_table();

    setTimeout(() => {
        get_co_search_filter_data();
    }, 100);

};

const clear_co_info_table = () => {

    const pt_dist_table = document.querySelectorAll('#co_table > tbody > tr');

    pt_dist_table.forEach(item => {
        item.remove();
    });

};

const handle_co_new_register_btn = () =>{

    let co_title_bar = document.querySelector('.co_title_bar');
        co_title_bar.innerHTML = "AGREGAR NUEVO COLABORADOR";

    let co_modal_register_btn = document.querySelector('.co_modal_register_btn');
        co_modal_register_btn.innerHTML = "REGISTRAR";

}

const co_asign_events = () => {

    const new_coworker_btn = document.querySelector('#new_coworker_btn');

    const   co_keywork_btn = document.querySelector('#co_keywork_btn');
            co_keywork_btn.addEventListener('click' , handle_co_keyword_btn );
    

    setTimeout(() => {
        new_coworker_btn.addEventListener( 'click' , ()=>{
            
            // ver new-coworker-modal.js
            clean_co_register_modal();

            handle_co_new_register_btn();
            
        } );
    }, 100);

}; window.addEventListener('DOMContentLoaded', co_asign_events );

const handle_co_worker_details = (coworker_id) => {
    
    console.log(coworker_id);

    render_co_view_form(coworker_id);
    render_co_photo_info_form(coworker_id);
}


const co_infinityScroll = (e) =>
{       
    let parent = e.parentNode;
    //console.log(e.scrollTop + e.offsetHeight - localStorage.co_scroll_ajust > parent.offsetHeight);
    if( e.scrollTop + e.offsetHeight - localStorage.co_scroll_ajust > parent.offsetHeight )
    {            
        //console.log( e.scrollTop + e.offsetHeight - localStorage.co_scroll_ajust > parent.offsetHeight );
        localStorage.co_scroll_ajust = localStorage.co_scroll_ajust + (e.scrollHeight < 900) ? e.scrollHeight : 0;            
        setTimeout(() => {
            if ( JSON.parse(localStorage.co_selected_page) !== "" )
            {
                get_co_search_filter_data();
            }
        }, 100);
    }        
}; let co_table_wrapper = document.querySelector(".co_table_wrapper"); co_table_wrapper.addEventListener( 'scroll' , (e)=>co_infinityScroll(co_table_wrapper),false);

