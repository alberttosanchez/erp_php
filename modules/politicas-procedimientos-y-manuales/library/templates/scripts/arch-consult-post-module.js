import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){

    //////////////////////////////////////////////////////////////////////////

    /** Variables y Constantes */


    //////////////////////////////////////////////////////////////////////////

    /*** Action functions */

    const add_form_action_events = () => {

        const consult_search_form = document.querySelector('#consult_search_form');
        const consult_button = document.querySelector('#consult_button');
        const search_element = document.querySelector('#search');

        const submit_consulted_form = () => {

            consult_anchor.href = `arch_consult?search=${search_element.value}`;
    
            localStorage.consulted_value = search_element.value;
    
            setTimeout(() => {
                consult_search_form.submit();                    
            }, 500);

        }

        window.addEventListener('keydown', (event) => {
            
            //console.log(event);

            if (event.keyCode == 13)
            {
                submit_consulted_form();
            }

        });


        consult_anchor.addEventListener('click', (event) => submit_consulted_form() );
    }

    const add_actions_events = () =>
    {
        const consulted_post_delete = document.querySelectorAll(".consulted_post_delete");
        
        if (consulted_post_delete.length > 0)
        {
            consulted_post_delete.forEach( item => {
                
                let was_delete = false;                
                item.addEventListener( 'click', (event) => {
            
                    //console.log(item);
                    
                    let post_id = item.id.split('delete_')[1];

                    was_delete = confirm(`Esta a punto de Eliminar la entrada cuyo ID es: ${post_id}`)
                    

                    if ( was_delete )
                    {
                        delete_consult_post(post_id);
                    }
            
                });
            });

        }


    }

    //////////////////////////////////////////////////////////////////////////

    /*** Get Functions */

    const get_current_page_from_url = (current_page = "") => {

        current_page = (window.location.href.indexOf("=") > -1) ? window.location.href.split("=")[1] : "1";

        return ( isNaN(current_page) ) ? "1" : current_page;
    }

    const get_consult_all_post_list = async () => {

        localStorage.current_page = get_current_page_from_url();

        if ( typeof localStorage.current_page == "undefined" || 
             null == localStorage.current_page || 
             localStorage.current_page == "" )
        {
            localStorage.current_page = "1";
        }     

        try {

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target        : "post-consult",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id,
                    current_page  : localStorage.current_page,
                    search        : (typeof localStorage.consulted_value != 'undefined') ? localStorage.consulted_value : ""
                })
            })

            const json = await server_response.json();
            //console.log(json);
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.consult_data = JSON.stringify(json.data.fetched);
                        localStorage.consult_pagination = JSON.stringify(json.data.pagination);
                        setTimeout(() => {                     
                            render_consult_data();
                        }, 100);
                    }, 100);

                    
                    break;
                case 401:
                    //console.log("401");
                    //show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    break;
                case 403:
                    //console.log("403");
                    
                    
                    break;
                case 406:
                    //console.log("406");                    
                    break;
                case 409:
                    //console.log('409',json.message);
                    /* show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde'); */
                default:
                    break;
            }

            

        } catch (error) {
            //console.log(error.message);                  
        }  
    }

    //////////////////////////////////////////////////////////////////////////

    /*** Push (Insert) Functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Update Functions */


    //////////////////////////////////////////////////////////////////////////

    /** Delete Functions */

    const delete_consult_post = async (post_id) => {

        try {

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target        : "post-delete",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id,
                    post_id       : post_id,                    
                })
            })

            const json = await server_response.json();
            //console.log(json);
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.consult_data = JSON.stringify(json.data.fetched);
                        localStorage.consult_pagination = JSON.stringify(json.data.pagination);
                        setTimeout(() => {                     
                            removeDOMElementWithStyleByElementId(`post_id_${post_id}`);
                        }, 100);
                    }, 100);

                    
                    break;
                case 401:
                    //console.log("401");
                    show_notification_message(json.message,'error');
                    break;
                case 403:
                    //console.log("403");
                    
                    
                    break;
                case 406:
                    //console.log("406");                    
                    break;
                case 409:
                    //console.log('409',json.message);
                    /* show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde'); */
                default:
                    break;
            }

            

        } catch (error) {
            //console.log(error.message);                  
        }  

    }

    //////////////////////////////////////////////////////////////////////////

    /*** Handle Functions */


    ///////////////////////////////////////////////////////////////////////////
    
    /*** Render Functions */

    const render_search_input_filter = () => {

        if (typeof localStorage.consulted_value != 'undefined')
        {
            const search_element = document.querySelector('#search');
                  search_element.value = localStorage.consulted_value;                  
        }
    }

    const render_consult_search_form = () => {

        const arch_consult_search_container = document.querySelector('#arch_consult_search_container');

        let html = `<form id="consult_search_form" class="consult_search_form">`;
                html += `<div class="form-group">`;
                    html += `<input title="Escriba el titulo de la entrada" id="search" name="search" type="text" placeholder="Escriba el titulo de la entrada"/>`;
                    html += `<a href="" title="Buscar" id="consult_anchor" name="consult_anchor"><span><i class="fas fa-search"></i> Buscar</span></a>`;
                html += `</div>`;
            html += `</form>`;

            arch_consult_search_container.innerHTML = html;

        setTimeout(() => {
            add_form_action_events();
        }, 100);

    }

    const render_consult_pagination = () => {

        let pagination = JSON.parse(localStorage.consult_pagination);

        //console.log(pagination);
        
        const arch_consult_pagination = document.querySelector('#arch_consult_pagination');

        let html  = `<ul id="submenu_pagination" class="unstyled-list d-flex">`;

            if ( pagination.prev_page > pagination.first_page && pagination.prev_page != "")
            {
                html    += `<li><a href="arch_consult?p=${pagination.first_page}">${pagination.first_page}</a></li>`;
            }
            
                if ( pagination.prev_page >= pagination.first_page && pagination.prev_page != "" ) {
                    html    += `<li><a href="arch_consult?p=${pagination.prev_page}">${pagination.prev_page}</a></li>`;
                }
                
                html    += `<li  class="current_page"><span>${pagination.current_page}</span></li>`;

                if ( pagination.next_page <= pagination.last_page && pagination.next_page != "")
                {
                    html    += `<li><a href="arch_consult?p=${pagination.next_page}">${pagination.next_page}</a></li>`;
                }

            if ( pagination.next_page < pagination.last_page && pagination.next_page != "")
            {
                html    += `<li><a href="arch_consult?p=${pagination.last_page}">${pagination.last_page}</a></li>`;
            }
            
            html += `</ul>`;


            arch_consult_pagination.innerHTML = html;
    }

    const render_consult_data = () => {

        const consulted_data = JSON.parse(localStorage.consult_data);
        //console.log(consulted_data);

        const arch_consult_table_container = document.querySelector("#arch_consult_table_container");
        
        let html = `<table id="consulted_table" class="table consulted_table table-striped table-hover table-responsive">`;        
            html += `<thead>`;
                html += `<tr>`;
                    html += `<th>Post ID</th>`;
                    html += `<th>Fecha Creación</th>`;
                    html += `<th>Titulo</th>`;
                    html += `<th>Categoría</th>`;
                    html += `<th>Autor</th>`;
                    html += `<th>Acción</th>`;
                html += `</tr>`;
            html += `</thead>`;
            html += `<tbody id="consulted_tbody">`;
            html += `</tbody>`;
        html += `</table>`; 
        
        arch_consult_table_container.innerHTML = html;
        
        html = "";
        let counter=0;
        
        moment.locale('es');

        for (const key in consulted_data) {
            
            const file_path = ( consulted_data[key].hasOwnProperty('files_path') ) ? JSON.parse(consulted_data[key].files_path) : {};
            //console.log(file_path);
            
            let path = "";
            if ( file_path.hasOwnProperty('path') )
            {
                path = file_path.path;
            }
            
            // invocamos la libreria momento para humanizar la fecha
            let friendly_date = moment(consulted_data[key]['created_at']).fromNow();

            html += `<tr id="post_id_${consulted_data[key]['id']}" key="${consulted_data[key]['id']}">`;
            html += `<td>${consulted_data[key]['id']}</td>`;            
            html += `<td>${consulted_data[key]['created_at']}<br>(${friendly_date})</td>`;
            html += `<td><a href="arch_post?id=${consulted_data[key]['id']}" target="_blank">${consulted_data[key]['post_title']}</a></td>`;

            let post_category_name = "";
            if ( consulted_data[key]['post_category_name_lv1'].length > 0 ) { post_category_name = consulted_data[key]['post_category_name_lv1']}
            if ( consulted_data[key]['post_category_name_lv2'].length > 0 ) { post_category_name = consulted_data[key]['post_category_name_lv2']}
            if ( consulted_data[key]['post_category_name_lv3'].length > 0 ) { post_category_name = consulted_data[key]['post_category_name_lv3']}
            if ( consulted_data[key]['post_category_name_lv4'].length > 0 ) { post_category_name = consulted_data[key]['post_category_name_lv4']}
            if ( consulted_data[key]['post_category_name_lv5'].length > 0 ) { post_category_name = consulted_data[key]['post_category_name_lv5']}
            
            html += `<td>${post_category_name}</td>`;
            html += `<td>${consulted_data[key]['author_full_name']}</td>`;
            html += `<td>`;
            html += `<span title="Editar Entrada"><a key="${consulted_data[key]['id']}" href="arch_edit/post_id=${consulted_data[key]['id']}"><i class="far fa-edit"></i></a></span>`;            
            html += `<span title="Eliminar Entrada"><a class="consulted_post_delete" id="delete_${consulted_data[key]['id']}" key="${consulted_data[key]['id']}" href="#eliminar"><i class="fas fa-times"></i></a></span>`;
            html += `</td>`;
            html += `</tr>`;
            
        }
       
        setTimeout(() => {
            const consulted_tbody = document.querySelector("#consulted_tbody");
            consulted_tbody.innerHTML= html;

            setTimeout(() => {
                add_actions_events();
                render_consult_pagination();
            }, 100);
        }, 100);
        

    }

    //////////////////////////////////////////////////////////////////////////
    
    /*** Events */

    
    /**************************** */
    /*** Keydown Events */

    
    /**************************** */
    /*** Submit Events */

    
    /**************************** */
    /*** Mousedown Events */

    
    /**************************** */
    /*** Mouseup Events */

        

    /**************************** */
    /*** Dragover Events */

    
    
    /**************************** */
    /*** Dragleave Events */

    

    /**************************** */
    /*** Change Events */

    

    /**************************** */
    /*** Drop Events */


    /*** DOMContentLoaded Events */
    
    window.addEventListener('DOMContentLoaded', get_consult_all_post_list() );
    window.addEventListener('DOMContentLoaded', render_consult_search_form() );
    window.addEventListener('DOMContentLoaded', render_search_input_filter() );
    

}(ValidateForms,Files));