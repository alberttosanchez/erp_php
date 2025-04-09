import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){

    //////////////////////////////////////////////////////////////////////////

    /** Variables y Constantes */


    //////////////////////////////////////////////////////////////////////////

    /*** Action functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Get Functions */

    const get_single_post_id_from_url = () => {

        let url = decodeURIComponent(window.location.href);

        let array_url = url.split("?");

        let post_id = "";
        array_url.forEach( (item,key) => {

            if ( item.indexOf("=") > -1 )
            {         
                //console.log("post_id:",item.substring(item.indexOf("=")+1));       
                post_id = item.substring(item.indexOf("=")+1);
            }
        });
        
        return post_id;
        
    }

    const get_user_rol = async () => {
                        
        try {

            const server_response = await fetch(API_INDEX_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target        : "person-user_rol",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id                    
                })
            })

            const json = await server_response.json();
            
            switch (server_response.status) {
                case 200:
                                        
                    setTimeout(() => {
                        const user_rol = json.data.rol_id;
                        setTimeout(() => {                     
                            render_single_post(user_rol);
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

    const get_single_post = async () => {
        
        // obtenemos el valos de la variable all_post_filter de la url actual.
        let post_id = get_single_post_id_from_url();
        
        if ( post_id == "" || null == post_id || isNaN(post_id) || post_id < 1 )
        {            
            not_found();
        }        

        localStorage.post_id = post_id;

        try {

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target : "post-read",
                    session_token : localStorage.session_token,
                    user_id : localStorage.user_id,
                    post_id : post_id
                })
            })

            const json = await server_response.json();
            //console.log(json);
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.single_post_data = JSON.stringify(json.data.fetched);
                        setTimeout(() => {                     
                            get_user_rol();
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

    const get_selected_file = (post_id,file_key,file_name) => {

        try {
            
            // Crear un nuevo objeto FormData
            var formData = new FormData();

            formData.append('target', "files-show_or_download");
            formData.append('user_id', localStorage.user_id);
            formData.append('session_token', localStorage.session_token);
            formData.append('post_id', post_id);
            formData.append('file_key', file_key);
            formData.append('file_name', file_name);

            // Crear un formulario oculto para enviar el FormData
            var hiddenForm = document.createElement('form');
            hiddenForm.method = 'post';
            hiddenForm.style.display = 'none';

            // Agregar la URL de destino al formulario FormData
            hiddenForm.action = ARCH_API_URL;
            // Abrir en una nueva ventana            
            hiddenForm.target = '_blank';

            // Agregar cada entrada del FormData al formulario oculto
            formData.forEach(function(value, key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                hiddenForm.appendChild(input);
            });

            // Agregar el formulario oculto al cuerpo del documento
            document.body.appendChild(hiddenForm);
            
            // Enviar el formulario oculto
            hiddenForm.submit();

        } catch (error) {
            //console.log(error.message);                  
        } 
    }
    

    //////////////////////////////////////////////////////////////////////////

    /*** Push (Insert) Functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Update Functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Handle Functions */


    const handle_download_post_btn = (post_files,btn_classes) => {

        /* let counter=0;
        for (const key in post_files) {
                        
            html += `<tr key="${key}">`;
            html += `<td>${++counter}</td>`;            
            html += `<td>${post_files[key]['file_name']}</td>`;
            html += `<td>${sizeInMB(post_files[key]['file_size'])}</td>`;
            //html += `<td><a href="${post_attached.path}/${post_files[key]['file_name']}" target="_blank">Descargar</a></td>`;
            html += `<td><button id="btn_file_id_${key}" class="btn_post_id_${post_id}">${field}</button></td>`;
            html += `</tr>`;
            
        } */
        console.log(btn_classes);
        const download_post_btn_DOMelements = document.querySelectorAll(`.${btn_classes}`);

        console.log(download_post_btn_DOMelements);
        
        if (download_post_btn_DOMelements.length > 0)
        {
            for (let i = 0; i < download_post_btn_DOMelements.length; i++) {
                
                download_post_btn_DOMelements[i].addEventListener('click', (ev) => {
                    
                    let file_key = ev.target.attributes.key.value.replace('btn_file_key_','');
                    
                    let file_name = "";                    
                    for (let i = 0; i < post_files.length; i++) {

                        if ( i == file_key)
                        {
                            file_name = post_files[i].file_name;
                        }
                        
                    }

                    let post_id = ev.target.classList.value.replace('download_post_id_btn_','');
                    
                    console.log('post_id',post_id);
                    console.log('file_key',file_key);
                    console.log('file_name',file_name);

                    get_selected_file(post_id,file_key,file_name);
                });
                
            }
        }

    }

    ///////////////////////////////////////////////////////////////////////////
    
    /*** Render Functions */

    const render_single_post = (user_rol) => {
        
        const single_post_data = JSON.parse(localStorage.single_post_data);
        console.log('user_rol',user_rol);
        console.log(single_post_data);

        const post_wrapper = document.querySelector("#post_wrapper");
        
        const post_title_box = document.querySelector("#post_title_box");
        const post_date_box = document.querySelector("#post_date_box");

        const post_content_box = document.querySelector("#post_content_box");
        const post_attached_box = document.querySelector("#post_attached_box");

        
        for (const key in single_post_data) {

            var post_id = single_post_data[key]['id'];
            var post_title = single_post_data[key]['post_title'];
            var post_date = single_post_data[key]['created_at'];
            var post_content = single_post_data[key]['post_content'];
            var post_attached = JSON.parse(single_post_data[key]['files_path']);
            var download_allowed = JSON.parse(single_post_data[key]['download_allowed']);
            console.log(download_allowed);
        }
        
        let html = "";               

        // titulo del post
        html += `<h1>${post_title}</h1>`;
        post_title_box.innerHTML = html;

        // invocamos la libreria momento para humanizar la fecha
        moment.locale('es');
        let friendly_date = moment(post_date).fromNow();

        html = `<small><span style="font-weight:bold;">Fecha de Publicación:</span> ${post_date} (${friendly_date.capitalize()})</small>`;
        post_date_box.innerHTML = html;

        html = `<div><p>${post_content}</p></div>`;
        post_content_box.innerHTML = html;                
        
        //console.log(post_attached);
        
        let field = ( (user_rol == "1") || (user_rol == "2") || ( download_allowed ) ) ? "descargar" : "Visualizar";
        const post_files = (post_attached.hasOwnProperty("post_files")) ? post_attached.post_files: "";
        
        html = `<div style="font-weight:bold;">Archivos Adjuntos:</div>`;

        if (post_files.length < 1 )
        {
            html += "<div><span>No contiene archivos adjuntos.</span></div>";
        }
        else
        {
            // tabla con archivos adjuntos
            html += `<table id="attached_table" class="table table-striped table-hover table-responsive">`;
                html += `<thead>`;
                    html += `<tr>`;
                        html += `<th>#</th>`;
                        html += `<th>Titulo</th>`;
                        html += `<th>Peso</th>`;
                        html += `<th>${field}</th>`;
                    html += `</tr>`;
                html += `</thead>`;
                html += `<tbody id="attached_tbody">`;
                html += `</tbody>`;
            html += `</table>`;            
        }

        post_attached_box.innerHTML = html;

        if (post_files.length > 0 )
        {
            html = "";
            let counter=0;
            for (const key in post_files) {
                            
                html += `<tr key="${key}">`;
                html += `<td>${++counter}</td>`;            
                html += `<td>${post_files[key]['file_name']}</td>`;
                html += `<td>${sizeInMB(post_files[key]['file_size'])}</td>`;
                //html += `<td><a href="${post_attached.path}/${post_files[key]['file_name']}" target="_blank">Descargar</a></td>`;
                html += `<td><button type="button"  key="btn_file_key_${key}" id="download_post_btn_id_${key}" class="download_post_id_btn_${post_id}">${field}</button></td>`;
                html += `</tr>`;
                
            }
             
            //console.log(html);

            let btn_classes = `download_post_id_btn_${post_id}`;
                
            setTimeout(() => {
                const attached_tbody = document.querySelector("#attached_tbody");    
                attached_tbody.innerHTML= html;
                setTimeout(() => {
                    handle_download_post_btn(post_files,btn_classes);
                }, 100);
            }, 300);
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
       
    window.addEventListener('DOMContentLoaded', get_single_post() );

}(ValidateForms,Files));
