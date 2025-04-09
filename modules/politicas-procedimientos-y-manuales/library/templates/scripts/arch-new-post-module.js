import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){
    
    let files_object = [];

    // enviar archivo a la carpeta temporal
    const push_reg_on_staging_temp_file = async (file_data) => {

        //console.log(file_data);

        reg_trigger_spinner("show");
        // obtenemos la extension del archivo a subir
        let ext = get_file_extension(file_data.file_name);

        if (
            ( file_data.file_type === "image/jpeg" && (ext === "jpg" || ext === "jpeg" ) ) ||
            ( file_data.file_type === "image/png" && ext === "png" )            
        )
        {            
            if(localStorage.user_id !== "" && localStorage.session_token !== "")
            {
                let session_token   = localStorage.session_token;
                let user_id         = localStorage.user_id;

                try {
                    
                    const   form = new FormData();
                            form.append('target', "temporal_visit_picture");
                            form.append('session_token', session_token);
                            form.append('user_id', user_id);
                            form.append('user_role_id', this.state.data.fetched.role_id);
                            form.append('public_file', file_data.public_file);
                    
                    const   server = await fetch( API_UPLOAD_PUBLIC_FILE_URL ,{
                        method : "POST",
                        body: form,
                    });
                    
                    const response = await server.json();                    
                    //const response = await server.text();
                    
                    //console.log(server);
                    //console.log(response);
                    
                    if ( server.status === 200 )
                    {   
                        //console.log('archivo temporal creado');

                        //guardamos el nombre del archivo temporal con su extension
                        localStorage.temp_file_name = response.data;

                        setTimeout(() => {
                            render_reg_temp_file();
                        }, 100);
                    }
                    else if (server.status === 401)
                    {
                        //console.log(response);
                        go_home();
                    }
                    else if (server.status === 403)
                    {
                        //console.log("estado:403"); 
                    }
                    else if (server.status === 409)
                    {
                        //console.log("estado:409");                        
                    }
                    else
                    {
                        //console.log(response);
                        go_home();
                    }

                    reg_trigger_spinner();                    

                } catch (error) {

                    //console.log(error);

                    reg_trigger_spinner();

                }

            }
            else
            {
                go_home();
            }
        }
        else
        {
            this.actionMessage("Agregue una imagen png o jpg.","warning");
            reg_trigger_spinner();            
        }
        
    }    
    
    const validate_form = (form) => {

        const Form = new ValidateForms;
        
        let response = Form.validateFormFields(form,{
            post_title              : "string|min-length:3|max-length:100",            
            post_description        : "string|min-length:3|max-length:100",            
        },[
            "El titulo debe tener al menos 3 y un máximo de 100 caracteres.",            
            "La descripcion debe tener al menos 3 y un máximo de 100 caracteres.",            
        ]);                

        return response;

    }

    const enabled_disabled_arch_new_submit_btn = (condition = 'enabled') =>{

        const btn_arch_new_register = document.querySelector('.btn_arch_new_register');        
              btn_arch_new_register.disabled = (condition == 'disabled') ? true : false;
    }

    const show_or_hidde_arch_new_spinner_submit_btn = (condition = 'no-show') =>{

        if (condition === "show")
        {
            enabled_disabled_arch_new_submit_btn('disabled');
            triggerSpinner("show","arch_spinner_box");
            toggle_class_on_dom_element_by_id('submit_text_btn','no-show');
            
        }
        else
        {
            enabled_disabled_arch_new_submit_btn('enabled');
            triggerSpinner("no-show","arch_spinner_box");
            toggle_class_on_dom_element_by_id('submit_text_btn','no-show');
        }
    }

    const put_focus_on_field_by_name = (field_name,form) => {
        //console.log(field_name);

        let elements = [];
        if (typeof field_name !== 'undefined')
        {
            elements = document.getElementsByName(field_name);
            
            for (let i = 0; i < elements.length; i++) {                
                elements[i].focus();                
            }
        }
        else
        {
            //console.log(form.entries());

            let focused = false;
            for (const iterator of form.entries()) {
                                
                elements = document.getElementsByName(iterator[0]);                
                //console.log(elements);
                for (let i = 0; i < elements.length; i++) {

                    if ( elements[i].value.length == 0 && focused == false)
                    {
                        //console.log(elements[i]);
                        elements[i].focus();
                        focused = true;
                    }
                }
            }
        }        
    }

    // registrar el formulario y sus anexos en la base de datos
    const register_arch_new = () => {
        upload_post_files();
    }

    const options_passed = () => {

        if (localStorage.allow_upload_filter == "true")
        {
            if ( localStorage.upload_slug_allowed.length > 0)            
            {
                let array_upload_slug_allowed = localStorage.upload_slug_allowed.split(',');

                if (array_upload_slug_allowed.length > 0)
                {
                    const post_categories = document.getElementById('post_categories');

                    let slug_found = false;
                    for (let i = 0; i < array_upload_slug_allowed.length; i++) {
                        
                        if (post_categories.value == array_upload_slug_allowed[i])
                        {
                            slug_found = true;
                        }
                        
                    }

                    if ( ! slug_found )
                    {
                        show_notification_message('No esta permitido publicar en la categoria seleccionada.','error');
                        return false;
                    }                    

                }
                
            }

        }        
        
        return true;

    }

    const handle_arch_new_register_form = (ev) => {
        //console.log('form send');
        ev.preventDefault();

        const form = new FormData(ev.target);
        
        show_or_hidde_arch_new_spinner_submit_btn('show');
       
        setTimeout(() => {

            const result = validate_form(form);
            
            //console.log(result);

            if ( result.status == "success" )
            {
                if ( options_passed() )
                {                
                    register_arch_new();                
                }
                else
                {
                    show_or_hidde_arch_new_spinner_submit_btn('no-show');
                }
            }
            else
            {
                put_focus_on_field_by_name(result.field_name,form);
                
                show_notification_message(result.message,'warning');

                show_or_hidde_arch_new_spinner_submit_btn('no-show');
            }
        }, 100);

    }

    /*const createMirrorArrayWithFiles = () =>{

        
        let input_files = document.querySelector("input[type=file].input_drop_zone");

        // quitar archivos del input file
        let fileListArr = Array.from(input_files.files);

        //console.log(fileListArr);
        //console.log(files_object);

    }*/

    const removeDragData = (ev) => {

        //createMirrorArrayWithFiles();

        //console.log('Removing drag data')

        if (typeof ev.dataTransfer != 'undefined' && ev.dataTransfer.items) {
            // Use DataTransferItemList interface to remove the drag data
            ev.dataTransfer.items.clear();
        } else {
            // Use DataTransfer interface to remove the drag data
            ev.target.value = "";
        }
    }

    const barStatusUploadPostFile = (key = "", param = "") => {
    
        let action_id = ( key !== "" ) ? key : "";
        
        let progress_bar = document.getElementById("loading-"+action_id);
            
            //console.log(progress_bar);

        if ( progress_bar )
        {
            if (param !== "")
            {
                progress_bar.attributes["bar-status"].value = param;
            }
            
            let progress_bar_status = progress_bar.attributes["bar-status"].value;
        
            return progress_bar_status;
        }    
    
    }
    
    const triggerLoadingBarPostFile = (key="") => {
    
        let action_id = ( key !== "" ) ? key : "";
    
        let progress_bar = document.getElementById("loading-"+action_id);
        //console.log( barStatusUploadPostFile(action_id) );
        if (barStatusUploadPostFile(action_id) == "initialized")
        {            
            
            let action_id = (key !== "" ) ? key : "";

            const fill_bar = () => {
                
                let progress_bar = document.getElementById("loading-"+action_id);
                    
                let progress_bar_width = parseInt(progress_bar.attributes["bar-width"].value);
                //console.log(progress_bar_width,this.barStatus());

                if ( barStatusUploadPostFile(action_id) == "completed" && progress_bar_width <=80 )
                {   
                    progress_bar.setAttribute('style','display:inline-table;background:#ffb100;width:100%;height:5px;transition:0.3s');
                    
                }
                else if ( barStatusUploadPostFile(action_id) == "initialized")
                {                    

                    setTimeout(() => {
                        
                        progress_bar_width++;
                        
                        progress_bar.setAttribute('style','display:inline-table;background:#ffb100;width: '+progress_bar_width.toString()+'%;height:5px;transition:0.5s');                                                          
                        
                        progress_bar.attributes["bar-width"].value = progress_bar_width;                       
                                                
                        if (progress_bar_width <=80 && barStatusUploadPostFile(action_id) == "initialized" )
                        {
                            fill_bar();                                                    
                        }
                        else if ( barStatusUploadPostFile(action_id) == "completed")
                        {           
                            
                            progress_bar.setAttribute('style','display:inline-table;background:#157347;width:100%;height:5px;transition:0.3s');
                            
                        }
                        else if ( barStatusUploadPostFile(action_id) == "failed" )
                        {     
                                        
                            progress_bar.setAttribute('style','display:inline-table;background:#ffb100;width:0%;height:5px;transition:0.3s');
                        }

                    }, 50);
                }
                else if ( barStatusUploadPostFile(action_id) == "completed")
                {           
                    
                    //progress_bar.setAttribute('style','width: 100%;transition:0.3s');
                    
                }
                else if ( barStatusUploadPostFile(action_id) == "failed" )
                {     
                                    
                    //progress_bar.setAttribute('style','width: 0%;transition:0.3s');
                }

            }
                            
            fill_bar();               
                    
        }
        else if ( barStatusUploadPostFile(action_id) == "completed")
        {           
            
            progress_bar.setAttribute('style','width: 100%;transition:0.3s');
            
        }
        else if ( barStatusUploadPostFile(action_id) == "failed" )
        {               
            progress_bar.setAttribute('style','width: 0%;transition:0.3s');
        }
        
    }

    const resursive_loading_bar = (files_object,status,counter=0) => {
        
        elementDisabledByClass('arch_btn_action');   

        const recusirve_loading = (files_object,status,counter) => {
        
            setTimeout(() => { 
                if (status == 'initialized' )
                {
                    barStatusUploadPostFile(counter,'initialized');                
                }
                else if (status == 'completed' )
                {
                    barStatusUploadPostFile(counter,'completed');                
                }
                else if ( status ==  "failed" )
                {
                    barStatusUploadPostFile(counter,'failed');
                }
    
                triggerLoadingBarPostFile(counter);                
            }, 1000);

            if (counter <= files_object.length)
            {
                counter++; //file_index++;
                setTimeout(() => {
                    recusirve_loading(files_object,status,counter);                            
                }, 100);
            }
        }

        recusirve_loading(files_object,status,counter)
    }

    const get_category_level_from_selected_element = () => {
        let select_opt = document.querySelectorAll('#post_categories option');

        let found = false;
        let level="";
        select_opt.forEach( opt => {
            if ( opt.selected == true  && !found )
            {
                //console.log(opt.attributes['level'].value);
                found = true;
                level = opt.attributes['level'].value;
            }
        });
        return level;
    }

    const get_category_level_id_from_selected_element = () => {
        let select_opt = document.querySelectorAll('#post_categories option');
        
        let found = false;
        let level="";
        select_opt.forEach( opt => {
            if ( opt.selected == true && !found )
            {
                //console.log(opt.attributes['id_lv'].value);                
                found = true;
                level = opt.attributes['id_lv'].value;
            }
        });

        return level;
    }

    const upload_post_files = async () => {
        
        resursive_loading_bar(files_object,'initialized');
        
        try {

            //console.log(files_object);

            let formData = new FormData(arch_new_arch_form);
            
            formData.append("cat_level",get_category_level_from_selected_element());
            formData.append("cat_id_lv",get_category_level_id_from_selected_element());

            formData.append("session_token",localStorage.session_token);
            formData.append("user_id",localStorage.user_id);
            formData.append("target","post-create");
            
            formData.delete("drop_zone[]");

            files_object.forEach(file => {
                formData.append("drop_zone[]", file );                    
            });

            const server_response = await fetch(ARCH_API_URL,{
                method : "post",
                /*headers : {
                    //"Content-Type" : "multipart/form-data",                        
                },*/
                body: formData
            })

            const json = await server_response.json();
            
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    resursive_loading_bar(files_object,'completed');
                    show_notification_message(json.message,'success');
                    
                    setTimeout(() => {                     
                        clear_arch_category_form();
                        setTimeout(() => {
                            //localStorage.post_edit_data = JSON.stringify(json.data.fetched[0]);
                            window.location.assign(ARCH_URI_BASE+"/arch_edit/post_id="+json.data.fetched[0].id);
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }, 100);
                    }, 5000);
                    
                    break;
                case 401:
                    //console.log("401");
                    show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    show_notification_message(json.message,'warning');
                    break;
                case 403:
                    //console.log("403");
                    show_notification_message(json.message,'warning');
                    elementEnabledByClass('arch_btn_action');  
                    resursive_loading_bar(files_object,'failed');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    break;
                case 406:
                    //console.log("406");
                    show_notification_message(json.message,'warning');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    break;
                case 409:
                    //console.log('409',json.message);
                    show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde');
                default:
                    break;
            }

            

        } catch (error) {
            //console.log(error.message);
            elementEnabledByClass('arch_btn_action');  
            resursive_loading_bar(files_object,'failed');
            show_or_hidde_arch_new_spinner_submit_btn('hidde');
            show_notification_message('Error 500, contacte al administrador de sistemas.','error');            
        }            
        
        

    }
    
    const renderFilesList = () => {
        
        const arch_new_file_table = document.querySelector(".arch_new_file_table");
        const arch_new_file_table_body = document.querySelector(".arch_new_file_table .tbody");
        

        let tr = "";

        if ( files_object.length > 0 )
        {
            for (const key in files_object) {
            
                tr += `<div class="tr" key="${(parseInt(key)+1)}">`;                
                    tr +=   `<span>${(parseInt(key)+1)}</span>`;
                    tr +=   `<span>${files_object[key]['name']}</span>`;
                    tr +=   `<span class="arch_actions_buttons_wrap">`;
                        //tr +=   `<ul class="unstyled-list">`;
                            tr +=   `<button class="arch_btn_action" type="button" id="btn_action_${(parseInt(key)+1)}" key="${(parseInt(key)+1)}"><span class="close_icon"><i class="fas fa-times"></i> Quitar</span></button>`;
                        //tr +=   `</ul>`;
                    tr +=   `</span>`;                
                tr += `</div>`;
                tr +=   `<div class="tr"
                            class="arch_loading_bar new_category_file_loading_bar"
                            bar-status="inherit" 
                            bar-width="0" 
                            id="loading-${(parseInt(key)+1)}" 
                            style='display:inline-table;background:red;width:0%;height:2px'
                            >
                        </div>`;
            }    
            
        }
        
        arch_new_file_table_body.innerHTML = tr;

        if ( arch_new_file_table.className.indexOf("no-show") > -1 )
        {
            arch_new_file_table.classList.toggle('no-show');
        }

        // agregamos el evento click para disparar la funcion removeFileByKey
        setTimeout(() => {

            let btn_actions = document.querySelectorAll('.arch_btn_action');

            btn_actions.forEach( item => {
                item.addEventListener('click', (ev) =>{
                    let key = ev.target.parentElement.attributes['key'].value;
                    removeFileByKey((key-1));
                });            
            });

            // iniciamos la carga de los archivos al directorio temporal
            //upload_post_files();

        }, 100);

    }    

    const get_upload_options = async () => {
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
            
            console.log(json);

            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    //show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        const arch_settings_data = json.data.fetched;
                        let json_options = JSON.parse(arch_settings_data[0].json_options);                        
                        console.log(json_options);
                        localStorage.allow_upload_files = JSON.stringify(json_options.uploads.allow);
                        localStorage.upload_slug_allowed = json_options.uploads.slug_allowed;
                        localStorage.allow_upload_filter = JSON.stringify(json_options.uploads.filter);
                        localStorage.allow_upload_file_extensions = json_options.uploads.file_extensions;
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
                    localStorage.allow_upload_file_extensions = "";
                    break;
            }

            return [];

        } catch (error) {
            console.log(error.message);  
            localStorage.allow_upload_file_extensions = "";              
        } 
    }

    const handleDrop = (ev) => {

        //console.log('File(s) dropped');                               
        ev.preventDefault();
        ev.target.parentElement.classList.remove('over');         

        //console.log(ev);

        // instanciamos la clase Files
        const files = new Files; let ext;
        
        // extensiones a buscar        
        let arr = [];
        if ( localStorage.allow_upload_file_extensions.length > 0 )
        {

            let allow_upload_file_extensions = localStorage.allow_upload_file_extensions.split(','); 
            
            if ( allow_upload_file_extensions.length > 0 && localStorage.allow_upload_filter == "true" )
            {
                arr = allow_upload_file_extensions;
            }
            else
            {
                // extensiones por defecto
                arr = ['pdf','doc','docx','xls','xlsx'];
            }
        }

        let max_file_name_length = 75;

        if (
            typeof ev.dataTransfer != 'undefined' &&
            ev.dataTransfer.items
            ) {

            // Use DataTransferItemList interface to access the file(s)
            let counter = 0;
            for (var i = 0; i < ev.dataTransfer.items.length; i++) {

                // If dropped items aren't files, reject them
                if (ev.dataTransfer.items[i].kind === 'file') {

                    // si files_object esta vacio
                    if (files_object.length == 0)
                    {
                        let file = ev.dataTransfer.items[i].getAsFile();
                            // obtenemos la extension del archivo de su nombre
                            ext = files.get_file_extension(file.name).toLowerCase();
                        
                        if ( file.name.length <= max_file_name_length)
                        {
                            if ( ext && arr.indexOf(ext) > -1 )
                            {
                                files_object[counter] = ev.dataTransfer.items[i].getAsFile();
                                counter++;

                            }
                            else
                            {
                                show_notification_message(`"${file.name}", no es un archivo permitido.`,'warning');
                            }
                        }
                        else
                        {
                            show_notification_message(`El nombre del archivo "${file.name}", sobrepasa los ${max_file_name_length} caracteres, reduzcalo.`,'warning');
                        }
                        
                    }
                    else
                    {
                        let  found = false;
                        
                        files_object.forEach( item => {
                            let file = ev.dataTransfer.items[i].getAsFile();

                            ext = files.get_file_extension(file.name).toLowerCase();                            
                            
                            // si obtienes la extension y esta en las permitidas
                            // si el nombre no esta en el objeto introduce el item en el objeto  
                            if ( ext && item.name == file.name )
                            {
                                found = true;                            
                            }
                               

                        });                           
                        
                        // el largo del nombre de archivo es menor al maximo permitido
                        if ( ev.dataTransfer.items[i].getAsFile().name.length <= max_file_name_length)
                        {
                            // si obtienes la extension y esta en las permitidas
                            if ( ext && arr.indexOf(ext) > -1 )
                            {                                
                                // si no lo encuentra agregalo
                                if ( ! found ){
                                    files_object[files_object.length] = ev.dataTransfer.items[i].getAsFile();
                                }
                            }
                            else
                            {
                                show_notification_message(`"${ev.dataTransfer.items[i].getAsFile().name}", no es un archivo permitido.`,'warning');
                            }
                        }
                        else
                        {
                            show_notification_message(`El nombre del archivo "${ev.dataTransfer.items[i].getAsFile().name}", sobrepasa los ${max_file_name_length} caracteres, reduzcalo.`,'warning');
                        }
                        
                    }

                }

            }

        } else {

            // Use DataTransfer interface to access the file(s)
            let counter=0;
            for (var i = 0; i < ev.target.files.length; i++) {

                // si files_object esta vacio
                if (files_object.length == 0)
                {
                    let file = ev.target.files[i];
                         ext = files.get_file_extension(file.name).toLowerCase();
                        
                        if ( file.name.length <= max_file_name_length)
                        {
                            if ( ext && arr.indexOf(ext) > -1 )
                            {
                                files_object[counter] = ev.target.files[i];                
                                counter++;
                            }
                            else
                            {
                                show_notification_message(`"${file.name}", no es un archivo permitido.`,'warning');    
                            }
                        }
                        else
                        {
                            show_notification_message(`El nombre del archivo "${file.name}", sobrepasa los ${max_file_name_length} caracteres, reduzcalo.`,'warning');
                        }
                }
                else 
                {
                    let  found = false;
                    // instanciamos la clase Files
                    const files = new Files; let ext;
                    files_object.forEach( item => {

                        ext = files.get_file_extension(ev.target.files[i].name).toLowerCase();
                                                                                    
                        // si el nombre no esta en el objeto introduce el item en el objeto                    
                        if ( item.name == ev.target.files[i].name )
                        {
                            found= true;
                        }                        

                    });
                    
                    if( ev.target.files[i].name.length <= max_file_name_length )
                    {
                        if ( ext && arr.indexOf(ext) > -1 )
                        {   
                            // si no lo encuentra agregalo
                            if ( ! found ){                    
                                files_object[files_object.length] = ev.target.files[i];
                            }
                        }
                        else
                        {
                            show_notification_message(`"${ev.target.files[i].name}", no es un archivo permitido.`,'warning');    
                        }
                    }
                    else
                    {
                        show_notification_message(`El nombre del archivo "${ev.target.files[i].name}", sobrepasa los ${max_file_name_length} caracteres, reduzcalo.`,'warning');
                    }
                    
                }

            }

        }
        
        renderFilesList();

        setTimeout(() => {
            removeDragData(ev);                        
        }, 500);

        //console.log(files_object)
    }

    const removeFileByKey = (key) => {       

       /*  let input_files = document.querySelector("input[type=file].input_drop_zone");
        // quitar archivos del input file
        const fileListArr = Array.from(input_files.files);

        fileListArr.splice(key, 1);

        //console.log(fileListArr) */

        files_object.splice(key,1);

        setTimeout(() => {
            renderFilesList();        
        }, 100);
        
    }

    const disabled_arch_category_form = (bool = true) =>{

        setTimeout(() => {
            toggle_class_on_dom_element_by_id('submit_category_text_btn','no-show');
            toggle_class_on_dom_element_by_id('arch_category_spinner_box','no-show');                    

            if ( bool )
            {
                triggerSpinner("show","arch_category_spinner_box");
                elementDisabledById('arch_new_category');
                elementDisabledById('arch_new_category_btn');
            }
            else
            {
                triggerSpinner("no-show","arch_category_spinner_box");
                elementEnabledById('arch_new_category');
                elementEnabledById('arch_new_category_btn');
            }
            
        }, 100);


    }

    const clear_arch_category_form = () => {
        document.getElementById('arch_new_category').value = "";
        localStorage.new_category = "";
        localStorage.category_slug_selected = "";
        localStorage.all_categories = "";        
    }

    const get_all_categories = async () =>{

        try {
            
            const server = await fetch( API_MANAGE_DB_URL ,{
                method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    target          : "get", 
                    session_token   : localStorage.session_token,
                    user_id         : localStorage.user_id,
                    table_name      : ARCH_VIEW_ALL_CATEGORIES_TABLE,
                    selected_page   : 1,
                    filter          : "id_lv1"                        
                })              
            });
    
            const response = await server.json();
    
            switch (server.status) {
                case 200:
                                            
                    if (response.message == 'datos obtenidos'){
                        //console.log(response.data);
                        localStorage.all_categories = JSON.stringify(response.data);                        
                    }
                    else if(response.message == 'no data')
                    {
                        //console.log(`Las categorias no pudieron ser obtenidas.`);
                    }                        
                    break;
            
                default:
                    break;
            }       

            setTimeout(() => {
                render_category_list();
            }, 100);
        }
        catch (error)
        {
            //console.log(error);
        }
    }

    // reorganiza el array de categorias para mostrarlo ordenadamente
    const get_category_ordened_object = () => {

        let new_obj_lv1 = [];
        // recorremos el array crudo (raw_object) para ordernalo en el nivel 1
        JSON.parse(localStorage.all_categories).fetched.forEach( (item,key) => { 
            
            if ( new_obj_lv1.length == 0)
            {                
                new_obj_lv1 = [
                    {
                        id_lv1  :   item.id_lv1,
                        cat_name:   item.category_name_lv1,
                        cat_slug:   item.category_slug_lv1,
                        lv2     : []
                    },
                ]                
            }
            else
            {
                let found = false;  
                new_obj_lv1.forEach( ( item_lv1,key_lv1 ) => {
                    
                    if ( item_lv1.id_lv1 == item.id_lv1 )
                    {
                        found = true;                        
                    }
                })


                if ( ! found ){
                    let obj = {
                        id_lv1  :   item.id_lv1,
                        cat_name:   item.category_name_lv1,
                        cat_slug:   item.category_slug_lv1,
                        lv2     : []
                    }
                
                new_obj_lv1.push(obj);
                }
            }

        });
        
            let new_obj_lv2 = [];        
            // recorremos el array crudo (raw_object) para ordernalo en el nivel 2
            JSON.parse(localStorage.all_categories).fetched.forEach( (upcoming_item,upcoming_key) => { 
               
                let found = false;  
                new_obj_lv1.forEach( ( item_lv1,key_lv1 ) => {
                                    
                    if ( item_lv1.id_lv1 == upcoming_item.id_lv1)
                    {
                        if ( item_lv1.lv2.length == 0 && upcoming_item.id_lv2 != "")
                        {
                            let obj = {
                                id_lv2  :   upcoming_item.id_lv2,
                                cat_name:   upcoming_item.category_name_lv2,
                                cat_slug:   upcoming_item.category_slug_lv2,
                                lv3     : []
                            }
                            item_lv1.lv2.push(obj);
                        }
                        else
                        {
                            
                            item_lv1.lv2.forEach( (item_lv2,key_lv2) => {
        
                                let found = false;  
                                item_lv1.lv2.forEach( ( item_lv2,key_lv2 ) => {
                                    
                                    if ( item_lv2.id_lv2 == upcoming_item.id_lv2 || upcoming_item.id_lv2 == "")
                                    {
                                        found = true;                        
                                    }
                                })
    
                                if ( ! found ){
                                    let obj = {
                                        id_lv2  :   upcoming_item.id_lv2,
                                        cat_name:   upcoming_item.category_name_lv2,
                                        cat_slug:   upcoming_item.category_slug_lv2,
                                        lv3     : []
                                    }
                                
                                    item_lv1.lv2.push(obj);
                                }
        
                            })
    
                        }
    
                    }
                })                    
    
            });        
        
                let new_obj_lv3 = [];            
                // recorremos el array crudo (raw_object) para ordernalo en el nivel 3
                JSON.parse(localStorage.all_categories).fetched.forEach( (upcoming_item,upcoming_key) => { 
                
                    let found = false;  
                    new_obj_lv1.forEach( ( item_lv1,key_lv1 ) => {
                        
                        if ( item_lv1.id_lv1 == upcoming_item.id_lv1)
                        {
                            
                            item_lv1.lv2.forEach( (item_lv2,key_lv2) =>{
        
                                if ( item_lv2.id_lv2 == upcoming_item.id_lv2)
                                {
                                    //console.log("lv3",item_lv2.lv3.length == 0, upcoming_item.id_lv3);
                                    if ( item_lv2.lv3.length == 0 && upcoming_item.id_lv3 != "")
                                    {
                                        let obj = {
                                            id_lv3  :   upcoming_item.id_lv3,
                                            cat_name:   upcoming_item.category_name_lv3,
                                            cat_slug:   upcoming_item.category_slug_lv3,
                                            lv4     : []
                                        }
                                        item_lv2.lv3.push(obj);
                                    }
                                    else
                                    {
                                        
                                        item_lv2.lv3.forEach( (item_lv3,key_lv3) => {
                                            
                                            let found = false;
                                            //console.log(item_lv2.lv3)  
                                            item_lv2.lv3.forEach( ( item_lv3,key_lv3 ) => {
                                                
                                                if ( item_lv3.id_lv3 == upcoming_item.id_lv3 || upcoming_item.id_lv3 == "")
                                                {
                                                    found = true;                        
                                                }
                                            })
                
                                            if ( ! found ){
                                                let obj = {
                                                    id_lv3  :   upcoming_item.id_lv3,
                                                    cat_name:   upcoming_item.category_name_lv3,
                                                    cat_slug:   upcoming_item.category_slug_lv3,
                                                    lv4     : []
                                                }
                                            
                                                item_lv2.lv3.push(obj);
                                            }
                    
                                        })
                
                                    }                        
                                }
        
                            })
        
                        }
                    })                    
        
                });        
        
                    let new_obj_lv4 = [];        
                    // recorremos el array crudo (raw_object) para ordernalo en el nivel 4
                    JSON.parse(localStorage.all_categories).fetched.forEach( (upcoming_item,upcoming_key) => { 
                    
                        let found = false;  
                        new_obj_lv1.forEach( ( item_lv1,key_lv1 ) => {
                            
                            if ( item_lv1.id_lv1 == upcoming_item.id_lv1)
                            {                    
                                item_lv1.lv2.forEach( (item_lv2,key_lv2) =>{
            
                                    if ( item_lv2.id_lv2 == upcoming_item.id_lv2)
                                    {
                                        item_lv2.lv3.forEach( (item_lv3,key_lv3) =>{
                                            
                                            if ( item_lv3.lv4.length == 0 && upcoming_item.id_lv4 != "")
                                            {
                                                let obj = {
                                                    id_lv4  :   upcoming_item.id_lv4,
                                                    cat_name:   upcoming_item.category_name_lv4,
                                                    cat_slug:   upcoming_item.category_slug_lv4,
                                                    lv5     : []
                                                }
                                                item_lv3.lv4.push(obj);
                                            }
                                            else
                                            {
                                                
                                                item_lv3.lv4.forEach( (item_lv4,key_lv4) => {
                                                    
                                                    let found = false;  
                                                    //console.log(item_lv3.lv4)
                                                    item_lv3.lv4.forEach( ( item_lv4,key_lv4 ) => {
                                                        if ( item_lv4.id_lv4 == upcoming_item.id_lv4 || upcoming_item.id_lv4 == "")
                                                        {
                                                            found = true;                        
                                                        }
                                                    })
                        
                                                    if ( ! found ){
                                                        let obj = {
                                                            id_lv4  :   upcoming_item.id_lv4,
                                                            cat_name:   upcoming_item.category_name_lv4,
                                                            cat_slug:   upcoming_item.category_slug_lv4,
                                                            lv5     : []
                                                        }
                                                    
                                                        item_lv3.lv4.push(obj);
                                                    }
                            
                                                })
                        
                                            }                        
                                        })
                                    }
            
                                })
            
                            }
                        })                    
            
                    });                    
        
                        let new_obj_lv5 = [];        
                        // recorremos el array crudo (raw_object) para ordernalo en el nivel 5
                        JSON.parse(localStorage.all_categories).fetched.forEach( (upcoming_item,upcoming_key) => { 
                        
                            let found = false;  
                            new_obj_lv1.forEach( ( item_lv1,key_lv1 ) => {
                                
                                if ( item_lv1.id_lv1 == upcoming_item.id_lv1)
                                {                    
                                    item_lv1.lv2.forEach( (item_lv2,key_lv2) =>{
                
                                        if ( item_lv2.id_lv2 == upcoming_item.id_lv2)
                                        {
                                            item_lv2.lv3.forEach( (item_lv3,key_lv3) =>{
                                                
                                                if ( item_lv3.id_lv3 == upcoming_item.id_lv3)
                                                {
                                                    item_lv3.lv4.forEach( (item_lv4,key_lv4) =>{
                                                        
                                                        if ( item_lv4.id_lv4 == upcoming_item.id_lv4)
                                                        {
                                                            if ( item_lv4.lv5.length == 0 && upcoming_item.id_lv5 != "" )
                                                            {
                                                                let obj = {
                                                                    id_lv5  :   upcoming_item.id_lv5,
                                                                    cat_name:   upcoming_item.category_name_lv5,
                                                                    cat_slug:   upcoming_item.category_slug_lv5,
                                                                    //lv6     : []
                                                                }
                                                                item_lv4.lv5.push(obj);
                                                            }
                                                            else
                                                            {
                                                                
                                                                item_lv4.lv5.forEach( (item_lv5,key_lv5) => {
                                                                    
                                                                    let found = false;  
                                                                    item_lv4.lv5.forEach( ( item_lv5,key_lv5 ) => {
                                                                        
                                                                        if ( item_lv5.id_lv5 == upcoming_item.id_lv5 || upcoming_item.id_lv5 == "")
                                                                        {
                                                                            found = true;                        
                                                                        }
                                                                    })
                                        
                                                                    if ( ! found ){
                                                                        let obj = {
                                                                            id_lv5  :   upcoming_item.id_lv5,
                                                                            cat_name:   upcoming_item.category_name_lv5,
                                                                            cat_slug:   upcoming_item.category_slug_lv5,
                                                                            //lv6     : []
                                                                        }
                                                                    
                                                                        item_lv4.lv5.push(obj);
                                                                    }
                                            
                                                                })
                                        
                                                            }

                                                        }

                                                        
                                                    })
                
                                                }
                                            })
                                        }
                
                                    })
                
                                }
                            })                    
                
                        });
        
        //console.log(new_obj_lv1);
        return new_obj_lv1;

    }

    const render_category_list = () => {

        //console.log('render_category_list');

        const categories = document.getElementById('post_categories');

        let opt  = `<optgroup label="Categorías"></optgroup>`;
            opt += `<option key="" level="lv0" id_lv="" value="">(sin categoria)</option>`;

        if (typeof localStorage.all_categories != 'undefined' && localStorage.all_categories.length > 0 )
        {
            
            const ordened_object = get_category_ordened_object();
            
            //console.log(ordened_object);
            ordened_object.forEach( (item,key) => {                
                                
                let category_slug_selected="";

                if ( typeof localStorage.new_category != 'undefined' && localStorage.new_category.length > 0)
                {
                    category_slug_selected = localStorage.category_slug_selected;
                }                
                //console.log(category_slug_selected);
                let selected=(category_slug_selected == item.cat_slug)?"selected":"";
                opt += `<option class="lv1" level="lv1" id_lv="${item.id_lv1}" value="${item.cat_slug}" ${selected}>${item.cat_name}</option>`;

                if ( item.lv2.length > 0)
                {
                    item.lv2.forEach( (item_lv2,key_lv2) => {
                        selected=(category_slug_selected == item_lv2.cat_slug)?"selected":"";
                        opt += `<option class="lv2" level="lv2" id_lv="${item_lv2.id_lv2}" value="${item_lv2.cat_slug}" ${selected}>&nbsp;&nbsp;${item_lv2.cat_name}</option>`;
    
                        if ( item_lv2.lv3.length > 0)
                        {
                            item_lv2.lv3.forEach( (item_lv3,key_lv3) => {
                                selected=(category_slug_selected == item_lv3.cat_slug)?"selected":"";
                                opt += `<option level="lv3" class="lv3" id_lv="${item_lv3.id_lv3}" value="${item_lv3.cat_slug}" ${selected}>&nbsp;&nbsp;&nbsp;&nbsp;${item_lv3.cat_name}</option>`;
        
                                if ( item_lv3.lv4.length > 0)
                                {
                                    item_lv3.lv4.forEach( (item_lv4,key_lv4) => {
                                        selected=(category_slug_selected == item_lv4.cat_slug)?"selected":"";
                                        opt += `<option class="lv4" level="lv4" id_lv="${item_lv4.id_lv4}" value="${item_lv4.cat_slug}" ${selected}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item_lv4.cat_name}</option>`;
            
                                        if ( item_lv4.lv5.length > 0)
                                        {
                                            item_lv4.lv5.forEach( (item_lv5,key_lv5) => {
                                                selected=(category_slug_selected == item_lv5.cat_slug)?"selected":"";
                                                opt += `<option class="lv5" level="lv5" id_lv="${item_lv5.id_lv5}" value="${item_lv5.cat_slug}" ${selected}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${item_lv5.cat_name}</option>`;

                                            });
                                        }

                                    });
                                }

                            })
                        }
                    })
                }
            });

            categories.innerHTML = opt;
            
            
            
        }
        setTimeout(() => {
            clear_arch_category_form();
            disabled_arch_category_form(false);                   
        }, 500);
    }

    const push_category = async () => {
        
        //console.log("push_category");
        
        try {
            
            if ( ! get_category_table_name() )
            {
                show_notification_message(`No se pueden agregar más subniveles. Elija un nivel superior.`,'error');
                disabled_arch_category_form(false);
            }
            else
            {
                const server = await fetch( ARCH_API_URL ,{
                    method: "POST",
                    headers: {                                
                        'Content-Type': 'application/json'       
                    },
                    body: JSON.stringify({
                        target          : "categories-create", 
                        session_token   : localStorage.session_token,
                        user_id         : localStorage.user_id,
                        table_name      : get_category_table_name(),
                        selected_page   : 1,
                        filter          : get_category_field_slug(),
                        info_data       : JSON.parse(localStorage.new_category),
                        form_id         : document.getElementById('form_id').value
                    })              
                });
                
                //const response = await server.text();
                const response = await server.json();
                //console.log(response);
                // verifica si el estado del servidor es 200
    
                switch (server.status) {
                    case 200:
                        get_all_categories();
                        show_notification_message(`${response.message}`,'success');
                        //disabled_arch_category_form(false);                                             
                        break;
                
                    default:
                        //disabled_arch_category_form(false);
                        show_notification_message(`Error, contacte al administrador de sistemas.`,'error');
                        break;
                }
    
                if ( server.status !== 200 )            
                {
                    not_found();
                }
                
            }

            

        } catch (error) {

            //console.log(error);
            not_found();

        }
    }

    const get_category_table_name = () => {

        const categories = document.getElementById('post_categories');
        
        const categories_opt = document.querySelectorAll('#post_categories option');

        let level="lv0";
        categories_opt.forEach( (item,key) =>{

            if ( item.value == categories.value )
            {
                level = item.attributes['level'].value;
            }

        });

        //console.log(level);

        switch (level) {
            case 'lv1':
                return ARCH_SECOND_LV_CATEGORY_TABLE;
                break;
            case 'lv2':
                return ARCH_THIRD_LV_CATEGORY_TABLE;
                break;
            case 'lv3':
                return ARCH_FOUR_LV_CATEGORY_TABLE;
                break;
            case 'lv4':
                return ARCH_FIVE_LV_CATEGORY_TABLE;
                break;
            case 'lv5':
                //show_notification_message(`No se pueden agregar más subniveles. Elija un nivel superior.`,'error');  
                return false;
                break;
            default:
                return ARCH_FIRST_LV_CATEGORY_TABLE;
                break;
        }
    }

    const get_category_field_name = () => {
        
        const categories = document.getElementById('post_categories');
        
        const categories_opt = document.querySelectorAll('#post_categories option');

        let level="lv0";
        categories_opt.forEach( (item,key) =>{

            if ( item.value == categories.value )
            {
                level = item.attributes['level'].value;
            }
            
        });
        //console.log('get_category_field_name: ',level);
        switch (level) {
            case 'lv1':
                return "subcategory_name";
                break;
            case 'lv2':
                return "subcategory_name";
                break;
            case 'lv3':
                return "subcategory_name";
                break;
            case 'lv4':
                return "subcategory_name";
                break;
            case 'lv5':
                return false;
                break;
            default:
                return "category_name";
                break;
        }
    }

    const get_category_field_slug = () => {
        
        const categories = document.getElementById('post_categories');
        
        const categories_opt = document.querySelectorAll('#post_categories option');

        let level="lv0";
        categories_opt.forEach( (item,key) =>{

            if ( item.value == categories.value )
            {
                level = item.attributes['level'].value;
            }
            
        });
        //console.log('get_category_field_name: ',level);
        switch (level) {
            case 'lv1':
                return "subcategory_slug";
                break;
            case 'lv2':
                return "subcategory_slug";
                break;
            case 'lv3':
                return "subcategory_slug";
                break;
            case 'lv4':
                return "subcategory_slug";
                break;
            case 'lv5':
                return "";
                break;
            default:
                return "category_slug";
                break;
        }
    }

    const get_category_slug = async (slug_name) => {

        //console.log("get_category_slug");
        
        if (typeof sessionStorage.counter == 'undefined')
        {
            sessionStorage.counter = "1";
        }

        try {
            
            if ( ! get_category_table_name() )
            {
                show_notification_message(`No se pueden agregar más subniveles. Elija un nivel superior.`,'error');
                disabled_arch_category_form(false);
            }
            else
            {
                let body = JSON.stringify({
                    target          : "get", 
                    session_token   : localStorage.session_token,
                    user_id         : localStorage.user_id,
                    table_name      : get_category_table_name(),
                    filter          : get_category_field_slug(),
                    keyword         : slug_name,
                    selected_page   : 1                        
                });
    
                const server = await fetch( API_MANAGE_DB_URL ,{
                    method: "POST",
                    headers: {                                
                        'Content-Type': 'application/json'       
                    },
                    body: body           
                });
        
                //console.log(body);
    
                const response = await server.json();
        
                switch (server.status) {
                    case 200:
                                                
                        if (response.message == 'datos obtenidos'){
                            //console.log(response.data);
    
                            if (
                                slug_name !== response.data.fetched[0].category_slug &&
                                slug_name !== response.data.fetched[0].subcategory_slug
                                )
                            {     
                                localStorage.category_slug_selected = slug_name;
                                sessionStorage.counter = "1";
                                push_category();
                            }
                            else
                            {
                                let array_slug_name = slug_name.split("-");
                                let new_slug_name = `${array_slug_name[0]}-${sessionStorage.counter}`;
                                sessionStorage.counter = (parseInt(sessionStorage.counter)+1).toString();
    
                                localStorage.new_category = JSON.stringify({
                                    ...JSON.parse(localStorage.new_category),
                                    [get_category_field_slug()] : new_slug_name                                
                                });

                                localStorage.category_slug_selected = new_slug_name;

                                setTimeout(() => {
                                    get_category_slug(new_slug_name);                                
                                }, 500);
                            }                        
                        }
                        else if(response.message == 'no data')
                        {
                            sessionStorage.counter = "1";
                            push_category();                    
                        }                        
                        break;
                
                    default:
                        break;
                }
            }

        }
        catch (error)
        {
            //console.log(error);
        }
    }

    const handle_arch_new_submenu = () => {
        clear_arch_category_form();
        disabled_arch_category_form(true);
        get_all_categories();
    }

    const get_category_parent_id = () => {

        const categories = document.getElementById('post_categories');
        
        const categories_opt = document.querySelectorAll('#post_categories option');

        let level="lv0"; let id;
        categories_opt.forEach( (item,key) =>{

            if ( item.value == categories.value )
            {
                level = item.attributes['level'].value;
                id = item.attributes['id_lv'].value;
            }

        });

        let cat_parent_field_name = "";
        switch (level) {
            case 'lv1':
                cat_parent_field_name = "category_first_level_id";
                break;
            case 'lv2':
                cat_parent_field_name = "category_second_level_id";
                break;
            case 'lv3':
                cat_parent_field_name = "category_third_level_id";
                break;
            case 'lv4':
                cat_parent_field_name = "category_four_level_id";
                break;
            case 'lv5':
                cat_parent_field_name = false;
                break;
            default:
                cat_parent_field_name = false;
                break;
        }

            if ( cat_parent_field_name.length > 0 )
            {
                let new_category = {
                    ...JSON.parse(localStorage.new_category),                
                    [cat_parent_field_name] : id,     
                };
                
                localStorage.new_category = JSON.stringify(new_category);                
            }
                        
            return true;
    }

    const get_category_parent_field_name = () =>{

        const categories = document.getElementById('post_categories');
        
        const categories_opt = document.querySelectorAll('#post_categories option');

        let level="lv0";
        categories_opt.forEach( (item,key) =>{

            if ( item.value == categories.value )
            {
                level = item.attributes['level'].value;
            }

        });

        let cat_parent_field_name = "";
        switch (level) {
            case 'lv1':
                cat_parent_field_name = "category_first_level_id";
                break;
            case 'lv2':
                cat_parent_field_name = "category_second_level_id";
                break;
            case 'lv3':
                cat_parent_field_name = "category_third_level_id";
                break;
            case 'lv4':
                cat_parent_field_name = "category_four_level_id";
                break;
            case 'lv5':
                cat_parent_field_name = false;
                break;
            default:
                cat_parent_field_name = false;
                break;
        }

        if ( cat_parent_field_name.length > 0 )
        {                
            let new_category = {
                ...JSON.parse(localStorage.new_category),                
                [cat_parent_field_name] : "",                
            };
            
            localStorage.new_category = JSON.stringify(new_category);                
        }

        return true;        
    }

    const handle_arch_new_category = (ev) => {
        
        //console.log("handle_arch_new_category");

        const arch_new_category = document.getElementById('arch_new_category');

        if ( arch_new_category )
        {            
            disabled_arch_category_form(true);
            
            if ( arch_new_category.value.length >= 3)
            {
                //console.log("arch_new_category.value.length", arch_new_category.value.length);

                let slug_name = arch_new_category.value.trim().toLowerCase().replace(" ","-");
    
                localStorage.category_slug_selected = slug_name;

                localStorage.new_category = JSON.stringify({
                    [get_category_field_name()] : arch_new_category.value.trim().capitalize(),
                    [get_category_field_slug()] : slug_name,                    
                });
                        
                if ( get_category_parent_field_name() ) {

                    if ( get_category_parent_id() )
                    {
                        setTimeout(() => {                            
                            //console.log(JSON.parse(localStorage.new_category));
                            get_category_slug(slug_name);
                        }, 100);
                    }
                }
                
            }
            else
            {
                show_notification_message(`El nombre de la nueva categoria no puede tener menos de 3 caracteres.`,'error');
                
                disabled_arch_category_form(false);
            }
        }

    }
    
    /*** DOMContentLoaded Events */
    
    window.addEventListener('DOMContentLoaded', handle_arch_new_submenu );
    window.addEventListener('DOMContentLoaded', get_upload_options() );
        
    /*** Click Events */

    const arch_new_submenu = document.getElementById('arch_new_submenu');
          arch_new_submenu.addEventListener('click', handle_arch_new_submenu );
          
    const arch_new_category_btn = document.getElementById('arch_new_category_btn');
        arch_new_category_btn.addEventListener( 'click', (ev) => handle_arch_new_category(ev) );

    /*** Keydown Events */

    const arch_new_category = document.getElementById('arch_new_category');
          arch_new_category.addEventListener( 'keydown', (ev) => eventKeydownOnlyLetterNumbersDashesAndSpaces(ev) );

    /*** Submit Events */

    const arch_new_arch_form = document.getElementById('arch_new_arch_form');
          arch_new_arch_form.addEventListener( 'submit', (ev) => handle_arch_new_register_form(ev) );
    
    /*** Mousedown Events */

    const drop_zone_label = document.getElementById("drop_zone_label");
          drop_zone_label.addEventListener( 'mousedown', (ev)=> {                            
          drop_zone_label.setAttribute('style','cursor:grabbing');
    });

    /*** Mouseup Events */

    drop_zone_label.addEventListener('mouseup', (ev)=> {                            
        drop_zone_label.setAttribute('style','cursor:grab');
    });  

    /*** Dragover Events */

    // se activa cuando esta encima de la zona valida
    drop_zone_label.addEventListener('dragover', (ev)=> {                            
        //console.log('File(s) in drop zone');                           
        ev.preventDefault();                                
        ev.target.parentElement.classList.add('over');                                
    });

    /*** Dragleave Events */

    // se activa cuando se sale de la zona valida
    drop_zone_label.addEventListener("dragleave", (ev) => {
        ev.preventDefault();  
        ev.target.parentElement.classList.remove('over');   
    });

    /*** Change Events */

    let input_files = document.querySelector("input[type=file].input_drop_zone");
        // se activa cuando se suelta el archivo encima de la zona
        input_files.addEventListener('change', (ev)=> {
            
            if (localStorage.allow_upload_files == "true" )
            {
                handleDrop(ev);
            }
            else
            {
                show_notification_message('No esta permitido subir archivos','warning')
            }
        });

    /*** Drop Events */

    // se activa cuando se suelta el archivo encima de la zona
    drop_zone_label.addEventListener('drop', (ev)=> {
        if ( localStorage.allow_upload_files == "true" )
        {
            handleDrop(ev);
        }
        else
        {
            show_notification_message('No esta permitido subir archivos','warning')
        }
    });

}(ValidateForms,Files));