import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){

    //////////////////////////////////////////////////////////////////////////

    /** Variables y Constantes */

    //////////////////////////////////////////////////////////////////////////

    /*** Action functions */
    
    //////////////////////////////////////////////////////////////////////////

    /*** Get Functions */

    const get_options = async () => {
        //console.log("get_options");
        
        try {

            const form_id = document.getElementById("form_id").value;

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target        : "settings-read_all",
                    session_token : localStorage.session_token,
                    user_id       : localStorage.user_id,
                    form_id       : form_id,
                    
                })
            })

            const json = await server_response.json();
            
            //console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.arch_settings_data = JSON.stringify(json.data.fetched);                        
                        localStorage.arch_settings_pagination = JSON.stringify(json.data.pagination);
                        setTimeout(() => {                     
                            render_settings_data();
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

    const update_printer_options = async () => {

        const new_printer_options = JSON.parse(localStorage.new_printer_options);
        //console.log(`new_printer_options`,new_printer_options);

        try {

            const form_id = document.getElementById("form_id").value;

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target          : "settings-update_printer",
                    session_token   : localStorage.session_token,
                    user_id         : localStorage.user_id,
                    form_id         : form_id,
                    printer_options : JSON.stringify(new_printer_options)
                })
            });

            const json = await server_response.json();
            
            //console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");
                    //show_notification_message(json.message,'success');
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
                default:
                    break;
            }

        } catch (error) {
            //console.log(error.message);                  
        } 
    }

    const update_uploads_options = async () => {

        const new_file_upload_options = JSON.parse(localStorage.new_upload_options);
        
        console.log(new_file_upload_options);

        try {

            const form_id = document.getElementById("form_id").value;

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target                  : "settings-update_files_upload",
                    session_token           : localStorage.session_token,
                    user_id                 : localStorage.user_id,
                    form_id                 : form_id,
                    file_upload_options     : JSON.stringify(new_file_upload_options)
                })
            });

            const json = await server_response.json();
            
            //console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");
                    //show_notification_message(json.message,'success');
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
                default:
                    break;
            }

        } catch (error) {
            console.log(error.message);                  
        } 

    }

    const update_downloads_options = async () => {

        const new_file_download_options = JSON.parse(localStorage.new_download_options);
        
        console.log(new_file_download_options);

        try {

            const form_id = document.getElementById("form_id").value;

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target                  : "settings-update_files_download",
                    session_token           : localStorage.session_token,
                    user_id                 : localStorage.user_id,
                    form_id                 : form_id,
                    file_download_options   : JSON.stringify(new_file_download_options)
                })
            });

            const json = await server_response.json();
            
            //console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");
                    //show_notification_message(json.message,'success');
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
                default:
                    break;
            }

        } catch (error) {
            console.log(error.message);                  
        } 
    }

    //////////////////////////////////////////////////////////////////////////

    /** Delete Functions */

   
    //////////////////////////////////////////////////////////////////////////

    /*** Handle Functions */

    const handle_keydown_from_slug_tags = () => {

        const slug_allowed_input = document.getElementById('slug_allowed_input');

        slug_allowed_input.addEventListener( 'keydown', (event) => {
                         
            eventKeydownOnlyLetterNumbersCommasAndDashes(event);

        });

    }

    const handle_keydown_from_upload_slug_tags = () => {

        const upload_slug_allowed_input = document.getElementById('upload_slug_allowed_input');

        upload_slug_allowed_input.addEventListener( 'keydown', (event) => {
                         
            eventKeydownOnlyLetterNumbersCommasAndDashes(event);

        });

    }

    const handle_keydown_from_upload_file_extensions = () => {

        const upload_files_extensions_input = document.getElementById('upload_files_extensions_input');

        upload_files_extensions_input.addEventListener( 'keydown', (event) => {
                         
            eventKeydownOnlyLetterNumbersCommasAndDashes(event);

        });
        
    }

    const handle_keydown_from_file_extensions = () => {

        const files_extensions_input = document.getElementById('files_extensions_input');

        files_extensions_input.addEventListener( 'keydown', (event) => {
                         
            eventKeydownOnlyLetterNumbersCommasAndDashes(event);

        });

    }

    const handle_upload_file_extensions_for_update = () => {
        
        let new_upload_options = JSON.parse(localStorage.new_upload_options);

        // capturamos las extensiones del formulario
        let array_new_file_extensions = document.getElementsByClassName('upload_file_extensions_button');
                                            
        // creamos una cadena con los valores de las extensiones
        let new_file_extensions="";
        for (let i = 0; i < array_new_file_extensions.length; i++) {
            
            if ( array_new_file_extensions[i].value.length > 0 )                                        
            {
                if ( i < (array_new_file_extensions.length-1) )
                {
                    new_file_extensions+= `${array_new_file_extensions[i].value},`;
                }
                else if ( i == (array_new_file_extensions.length-1) )
                {
                    new_file_extensions+= `${array_new_file_extensions[i].value}`;
                }
            }
        }
        
        console.log(new_file_extensions);

        // agregamos las extensiones a la cadena oculta
        let files_extensions_allowed_DOMElement = document.getElementById('upload_files_extensions_allowed');                                                                        
        files_extensions_allowed_DOMElement.value = new_file_extensions;
        
        // guardamos las nuevas exntesiones en el objeto
        new_upload_options.file_extensions = new_file_extensions;

        setTimeout(() => {
            localStorage.new_upload_options = JSON.stringify(new_upload_options);
            setTimeout(() => {
                update_uploads_options();                                
            }, 50);
        }, 50);      
    }

    const handle_file_extensions_for_update = () => {
        
        let new_download_options = JSON.parse(localStorage.new_download_options);

        // capturamos las extensiones del formulario
        let array_new_file_extensions = document.getElementsByClassName('file_extensions_button');
                                            
        // creamos una cadena con los valores de las extensiones
        let new_file_extensions="";
        for (let i = 0; i < array_new_file_extensions.length; i++) {
            
            if ( array_new_file_extensions[i].value.length > 0 )                                        
            {
                if ( i < (array_new_file_extensions.length-1) )
                {
                    new_file_extensions+= `${array_new_file_extensions[i].value},`;
                }
                else if ( i == (array_new_file_extensions.length-1) )
                {
                    new_file_extensions+= `${array_new_file_extensions[i].value}`;
                }
            }
        }
        
        console.log(new_file_extensions);

        // agregamos las extensiones a la cadena oculta
        let files_extensions_allowed_DOMElement = document.getElementById('files_extensions_allowed');                                                                        
        files_extensions_allowed_DOMElement.value = new_file_extensions;
        
        // guardamos las nuevas exntesiones en el objeto
        new_download_options.file_extensions = new_file_extensions;

        setTimeout(() => {
            localStorage.new_download_options = JSON.stringify(new_download_options);
            setTimeout(() => {
                update_downloads_options();                                
            }, 50);
        }, 50);      
    }

    const handle_upload_remove_file_extension_button = (btn) => {

        btn.addEventListener('click', (event) => {
                        
            
            // removemos la extension del DOM
            removeDOMElementWithStyleByElementId(btn.getAttribute('key'));
            
            setTimeout(() => {
                
                handle_upload_file_extensions_for_update();
                                                                  
            }, 300);
        });

        return btn;
    }

    const handle_remove_file_extension_button = (btn) => {

        btn.addEventListener('click', (event) => {
                        
            
            // removemos la extension del DOM
            removeDOMElementWithStyleByElementId(btn.getAttribute('key'));
            
            setTimeout(() => {
                
                handle_file_extensions_for_update();
                                                                  
            }, 300);
        });

        return btn;
    }

    const handle_upload_add_file_extensions_button = () => {

        const upload_file_extensions_add_button = document.getElementById('upload_file_extensions_add_button');

        upload_file_extensions_add_button.addEventListener('click', (event) => {

            const upload_files_extensions_input = document.getElementById('upload_files_extensions_input');

            let new_file_extension = upload_files_extensions_input.value;

            upload_files_extensions_input.value = "";

            render_upload_from_file_extensions_button(new_file_extension);

            setTimeout(() => {
                handle_upload_file_extensions_for_update();
            }, 100);

        });

    }

    const handle_add_file_extensions_button = () => {

        const file_extensions_add_button = document.getElementById('file_extensions_add_button');

        file_extensions_add_button.addEventListener('click', (event) => {

            const files_extensions_input = document.getElementById('files_extensions_input');

            let new_file_extension = files_extensions_input.value;

            files_extensions_input.value = "";

            render_from_file_extensions_button(new_file_extension);

            setTimeout(() => {
                handle_file_extensions_for_update();
            }, 100);

        });

    }

    const handle_upload_slug_tags_for_update = () => {

        let new_upload_options = JSON.parse(localStorage.new_upload_options);

        // capturamos las extensiones del formulario
        let array_new_upload_slug_tags = document.getElementsByClassName('upload_slug_tags_button');
                                            
        // creamos una cadena con los valores de las extensiones
        let new_upload_slug_tags="";
        for (let i = 0; i < array_new_upload_slug_tags.length; i++) {
            
            if ( array_new_upload_slug_tags[i].value.length > 0 )                                        
            {
                if ( i < (array_new_upload_slug_tags.length-1) )
                {
                    new_upload_slug_tags+= `${array_new_upload_slug_tags[i].value},`;
                }
                else if ( i == (array_new_upload_slug_tags.length-1) )
                {
                    new_upload_slug_tags+= `${array_new_upload_slug_tags[i].value}`;
                }
            }
        }
        
        console.log(new_upload_slug_tags);

        // agregamos las extensiones a la cadena oculta
        let update_slug_allowed_DOMElement = document.getElementById('upload_slug_allowed');                                                                        
        update_slug_allowed_DOMElement.value = new_upload_slug_tags;
        
        // guardamos las nuevas exntesiones en el objeto
        new_upload_options.slug_allowed = new_upload_slug_tags;

        setTimeout(() => {
            localStorage.new_upload_options = JSON.stringify(new_upload_options);
            setTimeout(() => {
                update_uploads_options();                                
            }, 50);
        }, 50);   
    }

    const handle_slug_tags_for_update = () => {

        let new_download_options = JSON.parse(localStorage.new_download_options);

        // capturamos las extensiones del formulario
        let array_new_slug_tags = document.getElementsByClassName('slug_tags_button');
                                            
        // creamos una cadena con los valores de las extensiones
        let new_slug_tags="";
        for (let i = 0; i < array_new_slug_tags.length; i++) {
            
            if ( array_new_slug_tags[i].value.length > 0 )                                        
            {
                if ( i < (array_new_slug_tags.length-1) )
                {
                    new_slug_tags+= `${array_new_slug_tags[i].value},`;
                }
                else if ( i == (array_new_slug_tags.length-1) )
                {
                    new_slug_tags+= `${array_new_slug_tags[i].value}`;
                }
            }
        }
        
        console.log(new_slug_tags);

        // agregamos las extensiones a la cadena oculta
        let slug_allowed_DOMElement = document.getElementById('slug_allowed');                                                                        
            slug_allowed_DOMElement.value = new_slug_tags;
        
        // guardamos las nuevas exntesiones en el objeto
        new_download_options.slug_allowed = new_slug_tags;

        setTimeout(() => {
            localStorage.new_download_options = JSON.stringify(new_download_options);
            setTimeout(() => {
                update_downloads_options();                                
            }, 50);
        }, 50);      
    }

    const handle_upload_remove_slug_button = (btn) => {

        btn.addEventListener('click', (event) => {
                        
            
            // removemos la extension del DOM
            removeDOMElementWithStyleByElementId(btn.getAttribute('key'));
            
            setTimeout(() => {
                
                handle_upload_slug_tags_for_update();
                                                                  
            }, 300);
        });

        return btn;
    }

    
    const handle_remove_slug_button = (btn) => {

        btn.addEventListener('click', (event) => {
                        
            
            // removemos la extension del DOM
            removeDOMElementWithStyleByElementId(btn.getAttribute('key'));
            
            setTimeout(() => {
                
                handle_slug_tags_for_update();
                                                                  
            }, 300);
        });

        return btn;
    }
    
    const handle_upload_add_slug_button = () => {

        const upload_slugs_add_button = document.getElementById('upload_slugs_add_button');

        upload_slugs_add_button.addEventListener('click', (event) => {

            const upload_slug_allowed_input = document.getElementById('upload_slug_allowed_input');

            let new_tag = upload_slug_allowed_input.value;
            
            upload_slug_allowed_input.value = "";

            render_upload_from_slugs_tags_button(new_tag);

            setTimeout(() => {
                handle_upload_slug_tags_for_update();
            }, 100);
        });

    }

    const handle_add_slug_button = () => {

        const slugs_add_button = document.getElementById('slugs_add_button');

        slugs_add_button.addEventListener('click', (event) => {

            const slug_allowed_input = document.getElementById('slug_allowed_input');

            let new_tag = slug_allowed_input.value;
            
            slug_allowed_input.value = "";

            render_from_slugs_tags_button(new_tag);

            setTimeout(() => {
                handle_slug_tags_for_update();
            }, 100);
        });

    }

    ///////////////////////////////////////////////////////////////////////////
    
    /*** Render Functions */
    
    const render_printer_options = (printer_options) => {

        const section_printer_wrap = document.querySelector("#section_printer_wrap");
        
        
        let is_printer_allow = ( printer_options.allow == true ) ? 'on' : 'off';
        let is_printer_allow_option_checked = ( printer_options.allow == true ) ? true : false;
        

        let html = `<div id="printer_options_container" class="printer_options_container">`;        
                html += `<h4><span class="printer_option_title"><small><i class="fas fa-print"></i> Impresión</small><span></h4>`;                               
                html += `<div class="printer_option_settings">`;
                    html += `<span>Permitir Impresión</span>`;
                    html += `<label for="print">`;
                        html += `<span class="swite_btn printer_option selected"></span>`;
                        html += `<input type="checkbox" id="print" name="print" class="no-show" checked="${is_printer_allow_option_checked}" value="${is_printer_allow}">`;
                    html += `</label>`;
                html += `</div>`;
        html += `</div>`; 
        
        section_printer_wrap.innerHTML = html;

        let print_value = document.querySelector('.swite_btn.printer_option');

        if ( printer_options.allow == false)
        {
            print_value.setAttribute('class','swite_btn printer_option');

        }
        
        setTimeout(() => {
            
            const printDOMElement = document.getElementById('print');

            printDOMElement.addEventListener('change', (ev) => {
                
                //console.log('print value: ',printDOMElement.value);

                localStorage.new_printer_options = `{ "print_allow" : ${ (printDOMElement.value == 'on') ? "true" : "false" } }`;

                setTimeout(() => {
                    update_printer_options();                    
                }, 50);

            });
        
        }, 50);
        
    }

    const render_upload_file_extensions_tags = (uploads_options) => {
        
        let upload_files_extensions_container = document.querySelector('#upload_files_extensions_container');

        let file_extensions = uploads_options.file_extensions;
        
        let array_file_extensions = (file_extensions.length > 0) ? file_extensions.split(',') : file_extensions;

        if ( array_file_extensions.length > 0)
        {
            array_file_extensions.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `upload_extension_key_${key}`);
                    span.setAttribute('class',`upload_file_extensions_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`upload_extension_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`upload_file_extensions_button upload_extension_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un evento al boton
                btn = handle_upload_remove_file_extension_button(btn);
                
                span.appendChild(btn);

                upload_files_extensions_container.appendChild(span);
            });
        }
    }

    const render_file_extensions_tags = (download_options) => {

        let files_extensions_container = document.querySelector('#files_extensions_container');

        let file_extensions = download_options.file_extensions;
        
        let array_file_extensions = (file_extensions.length > 0) ? file_extensions.split(',') : file_extensions;

        if ( array_file_extensions.length > 0)
        {
            array_file_extensions.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `extension_key_${key}`);
                    span.setAttribute('class',`file_extensions_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`extension_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`file_extensions_button extension_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un evento al boton
                btn = handle_remove_file_extension_button(btn);
                
                span.appendChild(btn);

                files_extensions_container.appendChild(span);
            });
        }
    }

    const render_upload_from_file_extensions_button = (new_file_extension) => {

        let upload_files_extensions_container = document.querySelector('#upload_files_extensions_container');

        //let slug_allowed = download_options.slug_allowed;
        
        let array_file_extension = (new_file_extension.length > 0) ? new_file_extension.split(',') : new_file_extension;

        if ( array_file_extension.length > 0)
        {
            array_file_extension.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `upload_extension_key_${key}`);
                    span.setAttribute('class',`upload_file_extensions_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`upload_extension_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`upload_file_extensions_button upload_extension_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_upload_remove_file_extension_button(btn);
                
                span.appendChild(btn);

                upload_files_extensions_container.appendChild(span);
            });

        } 
        
    }

    const render_from_file_extensions_button = (new_file_extension) => {

        let slug_tags_container = document.querySelector('#files_extensions_container');

        //let slug_allowed = download_options.slug_allowed;
        
        let array_slug_allowed = (new_file_extension.length > 0) ? new_file_extension.split(',') : new_file_extension;

        if ( array_slug_allowed.length > 0)
        {
            array_slug_allowed.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `extension_key_${key}`);
                    span.setAttribute('class',`file_extensions_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`extension_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`file_extensions_button extension_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_remove_file_extension_button(btn);
                
                span.appendChild(btn);

                slug_tags_container.appendChild(span);
            });

        } 
        
    }

    const render_upload_from_slugs_tags_button = (new_tag) => {

        let slug_tags_container = document.querySelector('#upload_slug_tags_container');

        //let slug_allowed = download_options.slug_allowed;
        
        let array_slug_allowed = (new_tag.length > 0) ? new_tag.split(',') : new_tag;

        if ( array_slug_allowed.length > 0)
        {
            array_slug_allowed.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `upload_slug_key_${key}`);
                    span.setAttribute('class',`upload_slug_tag_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`upload_slug_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`upload_slug_tags_button upload_slug_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_upload_remove_slug_button(btn);
                
                span.appendChild(btn);

                slug_tags_container.appendChild(span);
            });

        } 
        
    }

    const render_from_slugs_tags_button = (new_tag) => {

        let slug_tags_container = document.querySelector('#slug_tags_container');

        //let slug_allowed = download_options.slug_allowed;
        
        let array_slug_allowed = (new_tag.length > 0) ? new_tag.split(',') : new_tag;

        if ( array_slug_allowed.length > 0)
        {
            array_slug_allowed.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `slug_key_${key}`);
                    span.setAttribute('class',`slug_tag_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`slug_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`slug_tags_button slug_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_remove_slug_button(btn);
                
                span.appendChild(btn);

                slug_tags_container.appendChild(span);
            });

        } 
        
    }

    const render_upload_slug_tags = (uploads_options) => {
        
        let upload_slug_tags_container = document.querySelector('#upload_slug_tags_container');

        let slug_allowed = uploads_options.slug_allowed;
        
        let array_slug_allowed = (slug_allowed.length > 0) ? slug_allowed.split(',') : slug_allowed;

        if ( array_slug_allowed.length > 0)
        {
            array_slug_allowed.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `upload_slug_key_${key}`);
                    span.setAttribute('class',`upload_slug_tag_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`upload_slug_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`upload_slug_tags_button upload_slug_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_upload_remove_slug_button(btn);
                
                span.appendChild(btn);

                upload_slug_tags_container.appendChild(span);
            });

        } 
    }

    const render_slug_tags = (download_options) => {

        let slug_tags_container = document.querySelector('#slug_tags_container');

        let slug_allowed = download_options.slug_allowed;
        
        let array_slug_allowed = (slug_allowed.length > 0) ? slug_allowed.split(',') : slug_allowed;

        if ( array_slug_allowed.length > 0)
        {
            array_slug_allowed.forEach( (element,key) => {
                
                let html = "";                                
                let span = document.createElement('span');
                    span.setAttribute('id', `slug_key_${key}`);
                    span.setAttribute('class',`slug_tag_text`);

                html += `<span>${element} </span>`;

                span.innerHTML = html;

                let btn = document.createElement('button');
                    btn.setAttribute('key',`slug_key_${key}`);
                    btn.setAttribute('type',`button`);
                    btn.setAttribute('class',`slug_tags_button slug_key_${key}`);
                    btn.setAttribute('value',`${element}`);

                html = `<i class="fas fa-times"></i>`;

                btn.innerHTML = html;

                // agregamos un eventos al boton
                btn = handle_remove_slug_button(btn);
                
                span.appendChild(btn);

                slug_tags_container.appendChild(span);
            });

        } 
        
    }

    const render_upload_options_data = (uploads_options,is_upload_file_filter_option_checked) => {

        // cargamos las opciones del objeto 

        let file_upload_value = document.querySelector('.swite_btn.file_upload_option');

        if ( uploads_options.allow == false)
        {
            file_upload_value.setAttribute('class','swite_btn file_upload_option');
        }

        let file_filter_value = document.querySelector('.swite_btn.file_upload_filter_option');

        if ( is_upload_file_filter_option_checked == false )
        {
            file_filter_value.setAttribute('class','swite_btn file_upload_filter_option');
        }
        
        setTimeout(() => {
            
            localStorage.new_upload_options = JSON.stringify(uploads_options);

            const input_file_upload_options = document.querySelectorAll('.input_file_upload_option');

            //console.log(input_file_download_options);

            if ( input_file_upload_options.length > 0)
            {
                input_file_upload_options.forEach( input_option => {

                    input_option.addEventListener('change', (ev) => {

                        let new_upload_options = JSON.parse(localStorage.new_upload_options);
                                                    
                            new_upload_options.allow = (input_option.value == 'on' && input_option.name == 'file_upload') ? true : (input_option.name == 'file_upload') ? false : new_upload_options.allow;                            
                            new_upload_options.filter = (input_option.value == 'on' && input_option.name == 'upload_file_filter') ? true : (input_option.name == 'upload_file_filter') ? false : new_upload_options.filter;
                            
                        setTimeout(() => {
                            localStorage.new_upload_options = JSON.stringify(new_upload_options);
                            setTimeout(() => {
                                update_uploads_options();                                
                            }, 50);
                        }, 50);
                    });

                });
            }
            
            // Slug tags
            render_upload_slug_tags(uploads_options);

            // File Extensions tags
            render_upload_file_extensions_tags(uploads_options);
        
        }, 50);

    }

    const render_download_options_data = (download_options,is_file_filter_option_checked) => {
        
        // cargamos las opciones del objeto 

        let file_download_value = document.querySelector('.swite_btn.file_download_option');

        if ( download_options.allow == false)
        {
            file_download_value.setAttribute('class','swite_btn file_download_option');
        }

        let file_filter_value = document.querySelector('.swite_btn.file_filter_option');

        if ( is_file_filter_option_checked == false )
        {
            file_filter_value.setAttribute('class','swite_btn file_filter_option');
        }
        
        setTimeout(() => {
            
            localStorage.new_download_options = JSON.stringify(download_options);

            const input_file_download_options = document.querySelectorAll('.input_file_download_option');

            //console.log(input_file_download_options);

            if ( input_file_download_options.length > 0)
            {
                input_file_download_options.forEach( input_option => {

                    input_option.addEventListener('change', (ev) => {

                        let new_download_options = JSON.parse(localStorage.new_download_options);
                                                    
                            new_download_options.allow = (input_option.value == 'on' && input_option.name == 'file_download') ? true : (input_option.name == 'file_download') ? false : new_download_options.allow;                            
                            new_download_options.filter = (input_option.value == 'on' && input_option.name == 'file_filter') ? true : (input_option.name == 'file_filter') ? false : new_download_options.filter;
                            
                        setTimeout(() => {
                            localStorage.new_download_options = JSON.stringify(new_download_options);
                            setTimeout(() => {
                                update_downloads_options();                                
                            }, 50);
                        }, 50);
                    });

                });
            }
            
            // Slug tags
            render_slug_tags(download_options);

            // File Extensions tags
            render_file_extensions_tags(download_options);
        
        }, 50);

    }

    const render_handles_to_upload_add_slug_and_file_extensions_buttons = () => {
         // agregamos un evento para agregar los slugs
         handle_upload_add_slug_button();

         // agregamos un evento para agregar las file extensions
         handle_upload_add_file_extensions_button();
 
    }

    const render_handles_to_add_slug_and_file_extensions_buttons = () => {

        // agregamos un evento para agregar los slugs
        handle_add_slug_button();

        // agregamos un evento para agregar las file extensions
        handle_add_file_extensions_button();

    }

    const render_uploads_options = (uploads_options) => {

        console.log(uploads_options);

        const section_upload_wrap = document.querySelector("#section_upload_wrap");
        
        
        let is_file_upload_allow = ( uploads_options.allow == true ) ? 'on' : 'off';
        let is_file_upload_allow_option_checked = ( uploads_options.allow == true ) ? true : false;
        
        let is_file_filter_enabled = ( uploads_options.filter == true ) ? 'on' : 'off';
        let is_upload_file_filter_option_checked = ( uploads_options.filter == true ) ? true : false;

        let slug_allowed = uploads_options.slug_allowed;

        let file_extensions = uploads_options.file_extensions;

        let html = `<div id="upload_options_container" class="upload_options_container">`;        
                html += `<h4><span class="upload_option_title"><small><i class="fas fa-file-upload"></i> Opciones de Subida</small><span></h4>`;                               
                html += `<div class="upload_option_settings">`;

                html += `<div class="d-flex flex-column">`;
                    html += `<div class="form-group d-flex justify-content-between">`;
                        html += `<span>Permitir subidas</span>`;
                        html += `<label for="file_upload">`;
                            html += `<span class="swite_btn file_upload_option selected"></span>`;
                            html += `<input type="checkbox" id="file_upload" name="file_upload" class="no-show input_file_upload_option" checked="${is_file_upload_allow_option_checked}" value="${is_file_upload_allow}">`;
                        html += `</label>`;
                    html += `</div>`;

                    html += `<div class="form-group d-flex justify-content-between">`;
                        html += `<span>Permitir Filtros</span>`;
                        html += `<label for="upload_file_filter">`;
                            html += `<span class="swite_btn file_upload_filter_option selected"></span>`;
                            html += `<input type="checkbox" id="upload_file_filter" name="upload_file_filter" class="no-show input_file_upload_option" checked="${is_upload_file_filter_option_checked}" value="${is_file_filter_enabled}">`;
                        html += `</label>`;
                        html += `</div>`;

                    html += `<div class="form-group">`;
                        html += `<span>Slugs permitidos:</span>`;
                        html += `<label for="upload_slug_allowed" class="d-flex justify-content-between">`;                           
                            html += `<input type="text" id="upload_slug_allowed_input" name="upload_slug_allowed_input" class="form-control" value="">`;
                            html += `<input type="hidden" id="upload_slug_allowed" name="upload_slug_allowed" class="" value="${slug_allowed}">`;
                            html += `<button id="upload_slugs_add_button" type="button" class="btn btn-success">Agregar</button>`;
                        html += `</label>`;
                        html += `<small style="color:grey">Escriba cada slug separado por una coma (,). Ejemplo: libro,historia,herramientas</small>`;
                        html += `<div id="upload_slug_tags_container" class="upload_slug_tags_container d-flex f-wrap">`;
                            //html += `<span class="update_slug_tags">${slug_allowed}</span>`;                            
                        html += `</div>`;
                    html += `</div>`;
                    
                    html += `<div class="form-group">`;
                        html += `<span>Extensiones de archivos permitidos:</span>`;
                        html += `<label for="upload_files_extensions_allowed" class="d-flex justify-content-between">`;                           
                            html += `<input type="text" id="upload_files_extensions_input" name="upload_files_extensions_input" class="form-control" value="">`;
                            html += `<input type="hidden" id="upload_files_extensions_allowed" name="upload_files_extensions_allowed" class="" value="${file_extensions}">`;
                            html += `<button id="upload_file_extensions_add_button" type="button" class="btn btn-success">Agregar</button>`;
                        html += `</label>`;
                        html += `<small style="color:grey">Escriba cada extensión de archivo separada por una coma (,). Ejemplo: docx,xlsx,txt</small>`;
                        html += `<div id="upload_files_extensions_container" class="upload_files_extensions_container d-flex f-wrap">`;
                            //html += `<span class="update_slug_tags">${slug_allowed}</span>`;                            
                        html += `</div>`;
                    html += `</div>`;

                html += `</div>`;
        html += `</div>`; 
        
        section_upload_wrap.innerHTML = html;
        
        render_upload_options_data(uploads_options,is_upload_file_filter_option_checked);

        handle_keydown_from_upload_slug_tags();

        handle_keydown_from_upload_file_extensions(); 

        render_handles_to_upload_add_slug_and_file_extensions_buttons();
        
    }

    const render_download_options = (download_options) => {

        //console.log(download_options);
        const section_download_wrap = document.querySelector("#section_download_wrap");
        
        
        let is_file_download_allow = ( download_options.allow == true ) ? 'on' : 'off';
        let is_file_download_allow_option_checked = ( download_options.allow == true ) ? true : false;
        
        let is_file_filter_enabled = ( download_options.filter == true ) ? 'on' : 'off';
        let is_file_filter_option_checked = ( download_options.filter == true ) ? true : false;

        let slug_allowed = download_options.slug_allowed;

        let file_extensions = download_options.file_extensions;

        let html = `<div id="download_options_container" class="download_options_container">`;        
                html += `<h4><span class="download_option_title"><small><i class="fas fa-file-download"></i> Opciones de Descargas</small><span></h4>`;                               
                html += `<div class="download_option_settings">`;

                html += `<div class="d-flex flex-column">`;
                    html += `<div class="form-group d-flex justify-content-between">`;
                        html += `<span>Permitir descargas</span>`;
                        html += `<label for="file_download">`;
                            html += `<span class="swite_btn file_download_option selected"></span>`;
                            html += `<input type="checkbox" id="file_download" name="file_download" class="no-show input_file_download_option" checked="${is_file_download_allow_option_checked}" value="${is_file_download_allow}">`;
                        html += `</label>`;
                    html += `</div>`;

                    html += `<div class="form-group d-flex justify-content-between">`;
                        html += `<span>Permitir Filtros</span>`;
                        html += `<label for="file_filter">`;
                            html += `<span class="swite_btn file_filter_option selected"></span>`;
                            html += `<input type="checkbox" id="file_filter" name="file_filter" class="no-show input_file_download_option" checked="${is_file_filter_option_checked}" value="${is_file_filter_enabled}">`;
                        html += `</label>`;
                        html += `</div>`;

                    html += `<div class="form-group">`;
                        html += `<span>Slugs permitidos:</span>`;
                        html += `<label for="slug_allowed" class="d-flex justify-content-between">`;                           
                            html += `<input type="text" id="slug_allowed_input" name="slug_allowed_input" class="form-control" value="">`;
                            html += `<input type="hidden" id="slug_allowed" name="slug_allowed" class="" value="${slug_allowed}">`;
                            html += `<button id="slugs_add_button" type="button" class="btn btn-success">Agregar</button>`;
                        html += `</label>`;
                        html += `<small style="color:grey">Escriba cada slug separado por una coma (,). Ejemplo: libro,historia,herramientas</small>`;
                        html += `<div id="slug_tags_container" class="slug_tags_container d-flex f-wrap">`;
                            //html += `<span class="slug_tags">${slug_allowed}</span>`;                            
                        html += `</div>`;
                    html += `</div>`;
                    
                    html += `<div class="form-group">`;
                        html += `<span>Extensiones de archivos permitidos:</span>`;
                        html += `<label for="files_extensions_allowed" class="d-flex justify-content-between">`;                           
                            html += `<input type="text" id="files_extensions_input" name="files_extensions_input" class="form-control" value="">`;
                            html += `<input type="hidden" id="files_extensions_allowed" name="files_extensions_allowed" class="" value="${file_extensions}">`;
                            html += `<button id="file_extensions_add_button" type="button" class="btn btn-success">Agregar</button>`;
                        html += `</label>`;
                        html += `<small style="color:grey">Escriba cada extensión de archivo separada por una coma (,). Ejemplo: docx,xlsx,txt</small>`;
                        html += `<div id="files_extensions_container" class="files_extensions_container d-flex f-wrap">`;
                            //html += `<span class="slug_tags">${slug_allowed}</span>`;                            
                        html += `</div>`;
                    html += `</div>`;

                html += `</div>`;
        html += `</div>`; 
        
        section_download_wrap.innerHTML = html;
        
        handle_keydown_from_slug_tags();

        handle_keydown_from_file_extensions(); 

        render_download_options_data(download_options,is_file_filter_option_checked);

        render_handles_to_add_slug_and_file_extensions_buttons();
    }

    const render_settings_data = () => {

        const arch_settings_data = JSON.parse(localStorage.arch_settings_data);        
        const json_options = JSON.parse(arch_settings_data[0].json_options);
        console.log(json_options);

        render_printer_options(json_options.printer);
        
        render_download_options(json_options.downloads);

        render_uploads_options(json_options.uploads);
        
        const swite_btn = document.querySelectorAll('.swite_btn');

        if (swite_btn.length > 0)
        {
            swite_btn.forEach(element => {
                element.addEventListener('click', (ev)=>{                                    
                    let check_element = ev.target.parentElement.children[1];
                    
                    ev.target.classList.toggle('selected');
                                                        
                    if ( check_element.value == 'on' )
                    {
                        check_element.value = 'off';                                        
                    }
                    else
                    {
                        check_element.value = 'on';                                        
                    }                                    
                });                       
            });
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
    
    window.addEventListener('DOMContentLoaded', get_options() );
    
    

}(ValidateForms,Files));