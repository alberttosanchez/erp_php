import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){

    //////////////////////////////////////////////////////////////////////////

    /** Variables y Constantes */


    //////////////////////////////////////////////////////////////////////////

    /*** Action functions */

    const add_form_action_events = () => {

        const category_search_form = document.querySelector('#category_search_form');        
        const search_element = document.querySelector('#search');

        const submit_category_form = () => {

            category_anchor.href = `arch_category?search=${search_element.value}`;
    
            localStorage.cat_consulted_value = search_element.value;
    
            setTimeout(() => {
                category_search_form.submit();                    
            }, 500);

        }

        window.addEventListener('keydown', (event) => {
            
            //console.log(event);

            if (event.keyCode == 13)
            {
                submit_category_form();
            }

        });


        category_anchor.addEventListener('click', (event) => submit_category_form() );
    }

    const add_cat_edit_action_events = () => {

        ///////////////////////////////////////////////////////////////////////


        const cat_edit_name_inputs = document.querySelectorAll('.cat_edit_name');
        
        if (cat_edit_name_inputs.length > 0)
        {
            cat_edit_name_inputs.forEach( item => {
                       
                
                item.addEventListener( 'keydown', (event) => {
            
                    let input_category_id = item.id.split(`cat_edit_name_`)[1];

                    let event_passed = eventKeydownOnlyLetterNumbersDashesAndSpaces(event);

                    if ( event_passed )
                    {
                        const cat_edit_slug_input = document.querySelector(`#cat_edit_slug_${input_category_id}`);
                        
                        if (cat_edit_slug_input){  
                            setTimeout(() => {
                                cat_edit_slug_input.value = event.target.value.replaceAll(" ","-").toLowerCase();
                            }, 50);                      
                        }
                    }
                    
                });            

            }); 
        }

        ////////////////////////////////////////////////////////////////////////////

        const cat_edit_slug_inputs = document.querySelectorAll('.cat_edit_slug');

        if (cat_edit_slug_inputs.length > 0)
        {

            cat_edit_slug_inputs.forEach( item => {

                item.addEventListener( 'keydown', (event) => {
                            
                    //console.log(event);
        
                    eventKeydownOnlyLetterNumbersAndDashes(event);
        
                });

            });

        }

        ///////////////////////////////////////////////////////////////////////////////

        const edit_cat_btns = document.querySelectorAll('.edit_cat_btn');

        if (edit_cat_btns.length > 0)
        {

            edit_cat_btns.forEach( item => {

                item.addEventListener('click' , (event) => {

                    let edit_cat_btn_id = item.id.split(`edit_cat_btn_`)[1];

                    item.disabled = true;

                    item.classList.toggle('disabled_btn');
                    
                    elementDisabledById(`cancel_cat_btn_${edit_cat_btn_id}`);
                    toggleClassOnElementsByArrayWithIds('disabled_btn', [`cancel_cat_btn_${edit_cat_btn_id}`]);
                    toggleClassOnElementsByArrayWithIds('no-show', [`edit_cat_text_${edit_cat_btn_id}`]);
                    toggleClassOnElementsByArrayWithIds('no-show', [`edit_cat_spinner_${edit_cat_btn_id}`]);

                    setTimeout(() => {
                        update_category_from_form(edit_cat_btn_id);
                    }, 100);
                });

            });

        }

    }

    const add_actions_events = () =>
    {
        const category_to_delete = document.querySelectorAll(".category_to_delete");
        
        if (category_to_delete.length > 0)
        {
            category_to_delete.forEach( item => {
                
                let was_delete = false;                
                item.addEventListener( 'click', (event) => {
            
                    //console.log(item);
                    
                    let category_level = item.id.split('_category_delete_')[0];
                    let category_id = item.id.split('_category_delete_')[1];

                    was_delete = confirm(`Esta a punto de Eliminar la categoría cuyo ID es: ${category_id}`)
                    

                    if ( was_delete )
                    {
                        delete_category_from_post(category_level,category_id);
                    }
            
                });
            });

        }

        //////////////////////

        const category_to_edit = document.querySelectorAll(".category_to_edit");
        
        if (category_to_edit.length > 0)
        {
            category_to_edit.forEach( item => {
                                                
                item.addEventListener( 'click', (event) => {
            
                    //console.log(item);
                    
                    let category_id = item.id.split('category_edit_')[1];
                    let category_level = item.getAttribute('lv');

                    render_edit_category_form(category_id,category_level);
            
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

    const get_categories_list = async () => {

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
                    target        : "categories-read_all",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id,
                    current_page  : localStorage.current_page,
                    search        : (typeof localStorage.cat_consulted_value != 'undefined') ? localStorage.cat_consulted_value : ""
                })
            })

            const json = await server_response.json();
            
            //console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.category_data = JSON.stringify(json.data.fetched);
                        localStorage.cateory_pagination = JSON.stringify(json.data.pagination);
                        setTimeout(() => {                     
                            render_category_data();
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
                    // show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                    // show_or_hidde_arch_new_spinner_submit_btn('hidde'); 
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

    const update_category_from_form = async (category_id) => {
        
        //console.log('update_category_from_form');
        try {

            const cat_edit_form = document.querySelector(`#category_edit_from_${category_id}`);

            //console.log(cat_edit_form);

            if (cat_edit_form)
            {
                const form = new FormData(cat_edit_form);

                const category_name = form.get(`cat_edit_name_${category_id}`);
                const category_slug = form.get(`cat_edit_slug_${category_id}`).toLowerCase();
                const cat_description = form.get(`cat_edit_description_${category_id}`);
                const form_id = document.querySelector('#form_id');
                
                //console.log(category_name,category_slug,cat_description);

                const server_response = await fetch(ARCH_API_URL,{
                    method : "POST",
                    headers : {
                        "Content-Type" : "application/json",                        
                    },
                    body: JSON.stringify({
                        target        : "categories-update",
                        session_token : localStorage.session_token,
                        user_id       : localStorage.user_id,
                        form_id       : form_id.value,
                        category_id   : category_id,
                        category_name : category_name,
                        category_slug : category_slug,
                        category_description : cat_description                    
                    })
                })
    
                const json = await server_response.json();
    
                //console.log(json);

                switch (server_response.status) {
                    case 200:
                        //console.log("200");                    
                        
                        show_notification_message(json.message,'success');
                        
                        break;
                    case 401:
                        //console.log("401");
                        show_notification_message(json.message,'error');
                        break;
                    case 403:
                        //console.log("403");
                        show_notification_message(json.message,'error');
                        
                        break;
                    case 406:
                        //console.log("406");                    
                        break;
                    case 409:
                        //console.log('409',json.message);
                        // show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                        // show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    default:
                        break;
                }

                elementEnabledById(`edit_cat_btn_${category_id}`);
                elementEnabledById(`cancel_cat_btn_${category_id}`);
                toggleClassOnElementsByArrayWithIds('disabled_btn', [`cancel_cat_btn_${category_id}`]);
                toggleClassOnElementsByArrayWithIds('no-show', [`edit_cat_text_${category_id}`]);
                toggleClassOnElementsByArrayWithIds('no-show', [`edit_cat_spinner_${category_id}`]);


            }
              

        } catch (error) {
            //console.log(error);
        }

    }

    //////////////////////////////////////////////////////////////////////////

    /** Delete Functions */

   const delete_category_from_post = async (category_level,category_id) => {

        try {

            const form_id = document.querySelector('#form_id');

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target        : "categories-delete",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id,
                    category_level: category_level, 
                    category_id   : category_id, 
                    form_id       : form_id.value                   
                })
            })

            const json = await server_response.json();
            //console.log(json);
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.category_data = JSON.stringify(json.data.fetched);
                        localStorage.cateory_pagination = JSON.stringify(json.data.pagination);
                        setTimeout(() => {
                            removeDOMElementWithStyleByElementId(`${category_level}_category_id_${category_id}`);
                        }, 100);
                    }, 100);

                    
                    break;
                case 401:
                    //console.log("401");
                    show_notification_message(json.message,'error');
                    break;
                case 403:
                    //console.log("403");
                    show_notification_message(json.message,'error');                    
                    break;
                case 406:
                    //console.log("406");
                    show_notification_message(json.message,'error');                       
                    break;
                case 409:
                    //console.log('409',json.message);
                    show_notification_message(json.message,'error');   
                    // show_or_hidde_arch_new_spinner_submit_btn('hidde');
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

        if (typeof localStorage.cat_consulted_value != 'undefined')
        {
            const search_element = document.querySelector('#search');
                  search_element.value = localStorage.cat_consulted_value;                   
        }
    }

    const render_category_search_form = () => {

        const arch_category_search_container = document.querySelector('#arch_category_search_container');

        let html = `<form id="category_search_form" class="category_search_form">`;
                html += `<div class="form-group">`;
                    html += `<input title="Escriba el nombre de la categoria" id="search" name="search" type="text" placeholder="Escriba el nombre de la categoria"/>`;
                    html += `<a href="" title="Buscar" id="category_anchor" name="category_anchor"><span><i class="fas fa-search"></i> Buscar</span></a>`;
                html += `</div>`;
            html += `</form>`;

            arch_category_search_container.innerHTML = html;

        setTimeout(() => {
            add_form_action_events();
        }, 100);

    }

   const render_category_pagination = () => {

        let pagination = JSON.parse(localStorage.cateory_pagination);

        //console.log(pagination);
        
        const arch_category_pagination = document.querySelector('#arch_category_pagination');

        let html  = `<ul id="submenu_pagination" class="unstyled-list d-flex">`;

            if ( pagination.prev_page > pagination.first_page && pagination.prev_page != "")
            {
                html    += `<li><a href="arch_category?p=${pagination.first_page}">${pagination.first_page}</a></li>`;
            }
            
                if ( pagination.prev_page >= pagination.first_page && pagination.prev_page != "" ) {
                    html    += `<li><a href="arch_category?p=${pagination.prev_page}">${pagination.prev_page}</a></li>`;
                }
                
                html    += `<li  class="current_page"><span>${pagination.current_page}</span></li>`;

                if ( pagination.next_page <= pagination.last_page && pagination.next_page != "")
                {
                    html    += `<li><a href="arch_category?p=${pagination.next_page}">${pagination.next_page}</a></li>`;
                }

            if ( pagination.next_page < pagination.last_page && pagination.next_page != "")
            {
                html    += `<li><a href="arch_category?p=${pagination.last_page}">${pagination.last_page}</a></li>`;
            }
            
            html += `</ul>`;


            arch_category_pagination.innerHTML = html;
    }

    const render_category_data = () => {

        const category_data = JSON.parse(localStorage.category_data);
        //console.log(category_data);

        const arch_category_table_container = document.querySelector("#arch_category_table_container");
        
        let html = `<table id="category_table" class="table category_table table-striped table-hover table-responsive">`;        
            html += `<thead>`;
                html += `<tr>`;                    
                    html += `<th>Fecha Creación</th>`;
                    html += `<th>Nombre</th>`;
                    html += `<th>Slug</th>`;
                    html += `<th>Descripción</th>`;
                    html += `<th>Acción</th>`;
                html += `</tr>`;
            html += `</thead>`;
            html += `<tbody id="category_tbody">`;
            html += `</tbody>`;
        html += `</table>`; 
        
        arch_category_table_container.innerHTML = html;
        
        html = "";
        let counter=0;
        
        moment.locale('es');

        for (const key in category_data) {
            
            const file_path = ( category_data[key].hasOwnProperty('files_path') ) ? JSON.parse(category_data[key].files_path) : {};
            //console.log(file_path);
            
            let path = "";
            if ( file_path.hasOwnProperty('path') )
            {
                path = file_path.path;
            }
            
            // invocamos la libreria momento para humanizar la fecha
            let friendly_date = moment(category_data[key]['created_at']).fromNow();

            let lv1 = category_data[key]['id_lv1'];
            let lv2 = category_data[key]['id_lv2'];
            let lv3 = category_data[key]['id_lv3'];
            let lv4 = category_data[key]['id_lv4'];
            let lv5 = category_data[key]['id_lv5'];

            let category_level_id = (lv1 != "" ) ? lv1 : (lv2 != "") ? lv2 : (lv3 != "") ? lv3 : (lv4 != "") ? lv4 : (lv5 != "") ? lv5 : "";
            
            let category_level = "";

            for (const index in category_data[key]) {
                               
                if ( index == "id_lv1" && category_data[key]['id_lv1'] != "" ) { category_level = index }
                if ( index == "id_lv2" && category_data[key]['id_lv2'] != "" ) { category_level = index }
                if ( index == "id_lv3" && category_data[key]['id_lv3'] != "" ) { category_level = index }
                if ( index == "id_lv4" && category_data[key]['id_lv4'] != "" ) { category_level = index }
                if ( index == "id_lv5" && category_data[key]['id_lv5'] != "" ) { category_level = index }

            }


            html += `<tr id="${category_level}_category_id_${category_level_id}" lv="${category_level}" key="${category_data[key]['category_slug']}">`;
            
            
            html += `<td>${category_data[key]['created_at']}<br>(${friendly_date})</td>`;
            html += `<td>${category_data[key]['category_name']}</td>`;
            html += `<td>${category_data[key]['category_slug']}</td>`;
            html += `<td>${category_data[key]['category_description']}</td>`;
            html += `<td id="edit_button_cat_${category_level_id}_${category_level}">`;
            html += `<span title="Editar Categoria"><a class="category_to_edit"     id="${category_level}_category_edit_${category_level_id}" lv="${category_level}" href="#editar"><i class="far fa-edit"></i></a></span>`;            
            html += `<span title="Eliminar Categoria"><a class="category_to_delete" id="${category_level}_category_delete_${category_level_id}" lv="${category_level}" href="#eliminar"><i class="fas fa-times"></i></a></span>`;
            html += `</td>`;
            html += `</tr>`;
            
        }
       
        setTimeout(() => {
            const category_tbody = document.querySelector("#category_tbody");
            category_tbody.innerHTML= html;

            setTimeout(() => {
                add_actions_events();
                render_category_pagination();
            }, 100);
        }, 100);
        

    }

    const render_edit_category_form = (category_id = "", category_level = "") => {

        //console.log("category_id",category_id);
        //console.log("category_level",category_level);

        let DOMExist = document.getElementById(`editing_cat_${category_id}_with_${category_level}`);

        // si el elemento no existe
        if ( ! DOMExist )            
        {
            const category_parent_tr = document.querySelector(`#${category_level}_category_id_${category_id}`);
            
            let category_data = JSON.parse(localStorage.category_data);

            if (category_parent_tr)
            {
                let html = "";

                for (const key in category_data) {

                    if (category_data[key][category_level] == category_id)
                    {           
                                                    
                        html +=     `<td colspan="5" style="display:table-cell;position:absolute;width:100%;">`;
                        html +=         `<form id="category_edit_from_${category_id}_with_${category_level}" class="category_edit_form" action="" method="post" style="display:flex;">`;

                        html +=             `<div style="padding-right:10px">`;
                        html +=                 `<span style="padding:10px">Escriba la nueva categoria y el slug:</span>`;
                        html +=                 `<div>`;
                        html +=                     `<input type="text" id="cat_edit_name_${category_id}_with_${category_level}" name="cat_edit_name_${category_id}_with_${category_level}" class="cat_edit_name" placeholder="Nombre de Categoría" style="width:100%;padding:5px 10px;margin-right:10px" value="${category_data[key].category_name}"/>`;
                        html +=                     `<input type="text" id="cat_edit_slug_${category_id}_with_${category_level}" name="cat_edit_slug_${category_id}_with_${category_level}" class="cat_edit_slug" placeholder="Slug de categoría" style="width:100%;padding:5px 10px;" value="${category_data[key].category_slug}"/>`;
                        html +=                 `</div>`;
                        html +=                     `<input type="text" id="cat_edit_description_${category_id}_with_${category_level}" name="cat_edit_description_${category_id}_with_${category_level}" class="cat_edit_description" placeholder="Descripción de categoría" style="width:100%;padding:5px 10px;" value="${category_data[key].category_description}"/>`;
                        html +=             `</div>`;
                        html +=             `<div style="display: flex;flex-direction: column;flex-wrap: wrap;justify-content: center;align-items: center;">`;
                        html +=                 `<button type="button" id="edit_cat_btn_${category_id}_with_${category_level}" class="edit_cat_btn" style="width:87px;padding:5px 10px;margin:5px">`;
                        html +=                     `<span id="edit_cat_text_${category_id}_with_${category_level}" class="">Editar</span>`;
                        html +=                     `<div id="edit_cat_spinner_${category_id}_with_${category_level}" class="spinner-border no-show" role="status">`;
                        html +=                         `<span class="sr-only">Loading...</span>`;
                        html +=                     `</div>`;
                        html +=                 `</button>`;
                        html +=                 `<button type="button" id="cancel_cat_btn_${category_id}_with_${category_level}" onclick="removeDOMElementWithStyleByElementId('editing_cat_${category_id}_with_${category_level}')" style="width:87px;padding:5px 10px;margin:5px">Cerrar</button>`;
                        html +=             `</div>`;

                        html +=         `</form>`;
                        html +=     `</td>`;
                        
                    }
                    
                };

                const tr = document.createElement('tr');
                      tr.setAttribute('class','editing_category no-show');
                      tr.setAttribute('id',`editing_cat_${category_id}_with_${category_level}`);
                      //tr.setAttribute('style','display:block;height:100%;max-height:0;overflow:hidden;transition: 0.3s;');

                      tr.innerHTML = html;

                category_parent_tr.after(tr);
                    
                showROWElementWithStyleByElementId(`editing_cat_${category_id}_with_${category_level}`, "down");
                
                
                add_cat_edit_action_events();
                
            }
        }
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
    
    window.addEventListener('DOMContentLoaded', get_categories_list() );
    window.addEventListener('DOMContentLoaded', render_category_search_form() );
    window.addEventListener('DOMContentLoaded', render_search_input_filter() );
    

}(ValidateForms,Files));