

const render_reg_temp_file = () => {
            
    let public_image_temp_path = PUBLIC_TEMP_URL + "/" + localStorage.temp_file_name + "?version="+Date.now();
    //console.log(public_image_temp_path);

    let user_icon_element = document.querySelector('#visitant_details_container .photo_pic_box span.user_icon');
            user_icon_element.setAttribute('class', 'user_icon no-show');

    let visitant_photo_element = document.querySelector('#visitant_details_container .photo_pic_box img');
            visitant_photo_element.setAttribute('src', public_image_temp_path );

        setTimeout(() => {
            visitant_photo_element.setAttribute('class', 'show' );
        }, 100);
};

const reg_trigger_spinner = (target = "no-show") =>{

    const snipper = document.querySelector(".spinner_box");            

    if(snipper)
    {
        if (target == "show")
        {
            snipper.setAttribute("class","spinner_box");
        }
        else if( target == "no-show")
        {
            snipper.setAttribute("class","spinner_box no-show");
        }
    }

}

const handle_reg_visitant_photo_change = (e) => {

    //console.log('handle_reg_visitant_photo_change');

    if(typeof e.target.files[0] !== 'undefined')
    {
        let ext = get_file_extension(e.target.files[0].name);
        // comprobamos que la imagen cumpla con el filtro
        if( 
            e.target.value !== "" && e.target.value !== null && 
            e.target.files[0].size < 2024000 &&
            ( 
                ( e.target.files[0].type == "image/jpeg" && (ext == "jpg" || ext == "jpeg" ) ) ||
                ( e.target.files[0].type == "image/png" && ext == "png" )   
            ) 
        )
        {
            // guardamos los datos en un objeto                
            file_data = {                    
                public_file     : e.target.files[0],
                file_type       : e.target.files[0].type,
                file_name       : e.target.files[0].name,
            };                

            setTimeout(() => {
                push_reg_on_staging_temp_file(file_data);
            }, 50);
        }
        else if (e.target.files[0].size > 2024000)
        {
            this.actionMessage("Solo imagenes menores a 2mb.","warning");
        }
        else
        {
            this.actionMessage("Agregue una imagen png o jpg.","warning");
        }
    }        

}; 

const handle_reg_remove_photo_btn = () => {
    
    reg_trigger_spinner("show");

    let user_icon_element = document.querySelector('#visitant_details_container .photo_pic_box span.user_icon');
            user_icon_element.setAttribute('class', 'user_icon');

    let visitant_photo_element = document.querySelector('#visitant_details_container .photo_pic_box img');
            visitant_photo_element.setAttribute('src', "" );

    // remueve el valor del input file
    document.querySelector('#visitant_photo').value = "";
    localStorage.temp_file_name = "empty";

    setTimeout(() => {
        visitant_photo_element.setAttribute('class', 'no-show' );

        reg_trigger_spinner();
    }, 100);
};

    // enviar archivo a la carpeta temporal
const push_reg_on_staging_temp_file = async (file_data) => {

    console.log(file_data);

    reg_trigger_spinner("show");
    // obtenemos la extension del archivo a subir
    let ext = get_file_extension(file_data.file_name);

    if (
        ( file_data.file_type == "image/jpeg" && (ext == "jpg" || ext == "jpeg" ) ) ||
        ( file_data.file_type == "image/png" && ext == "png" )            
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
                
                if ( server.status == 200 )
                {   
                    //console.log('archivo temporal creado');

                    //guardamos el nombre del archivo temporal con su extension
                    localStorage.temp_file_name = response.data;

                    setTimeout(() => {
                        render_reg_temp_file();
                    }, 100);
                }
                else if (server.status == 401)
                {
                    console.log(response);
                    window.location.href = URL_BASE;
                }
                else if (server.status == 403)
                {
                    console.log("estado:403"); 
                }
                else if (server.status == 409)
                {
                    console.log("estado:409");                        
                }
                else
                {
                    console.log(response);
                    window.location.href = URL_BASE+"/";
                }

                reg_trigger_spinner();                    

            } catch (error) {

                console.log(error);

                reg_trigger_spinner();

            }

        }
        else
        {
            window.location.href = URL_BASE+"/";
        }
    }
    else
    {
        this.actionMessage("Agregue una imagen png o jpg.","warning");
        reg_trigger_spinner();            
    }
    
}

const clear_reg_cat_gun = () => {

    let reg_cat_gun_options = document.querySelectorAll('#reg_cat_gun > option');

    reg_cat_gun_options.forEach( item => {
        item.remove();
    });

};

const clear_reg_cat_gun_status = () => {
    let reg_cat_gun_status_options = document.querySelectorAll('#reg_gun_status > option');

    reg_cat_gun_status_options.forEach( item => {
        item.remove();
    });
};

const render_reg_genders_list = () => {
    JSON.parse(localStorage.reg_genders_category).forEach( item => {
        let reg_genders = document.querySelector('#reg_genders');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);                
            option.innerHTML = item.gender;

            // omite el valor 3: otro
            if ( item.id != 3)
            {
                reg_genders.appendChild(option);
            }
            
    });   
};

const render_reg_ident_type_list = () => {
    JSON.parse(localStorage.reg_identification_type_category).forEach( item => {
        let reg_identification_type = document.querySelector('#reg_identification_type');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.identification_type;
                        
            reg_identification_type.appendChild(option);
            
    });        
};

const render_reg_coworker_department = (el) => {
    //console.log(el.value);
    
    JSON.parse(localStorage.reg_coworkers_category).forEach( item => {            
        
        let contact_dpto = document.querySelector('#contact_dpto');

        if ( el.value == item.id )
        {
            contact_dpto.value = item.department;
        }            
            
    });
            
};

const clean_reg_level_access_list = () => {        
    let reg_access_level_options = document.querySelectorAll('#reg_access_level > option');            
    for (let i = 0; i < reg_access_level_options.length; i++) {            
        reg_access_level_options[i].remove();
    }
}

const render_reg_level_access_list = () => {

    clean_reg_level_access_list();

    setTimeout(() => {
        let reg_access_level = document.querySelector('#reg_access_level');
        let option = document.createElement('option');
            option.value="";
            option.selected=true;
            option.innerHTML = " -- Nivel de Acceso -- ";
            reg_access_level.appendChild(option);

        JSON.parse(localStorage.reg_level_access_category).forEach( item => {

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.level_access;
                            
                reg_access_level.appendChild(option);
                
        });            
    }, 500);
};

const clean_reg_visit_reason_list = () => {
    let reg_visit_concert_opt = document.querySelectorAll('#reg_visit_concert > option');
        reg_visit_concert_opt.forEach(element => {
            element.remove();
        });
}

const render_reg_visit_reason_list = () => {

    clean_reg_visit_reason_list();

    setTimeout(() => {
        let reg_visit_concert = document.querySelector('#reg_visit_concert');
        let option = document.createElement('option');
            option.value="";
            option.innerHTML = "<option selected> -- Motivo de la Visita -- </option>";
            reg_visit_concert.appendChild(option);

        JSON.parse(localStorage.reg_visit_reason_category).forEach( item => {

            let option = document.createElement('option');
                option.value = item.id;
                option.setAttribute('key', item.id);
                option.innerHTML = item.reason_of_visit;
                            
                reg_visit_concert.appendChild(option);
                
        });            
    }, 500);
};

const render_reg_has_gun = (el) => {
            
    let reg_cat_gun = document.querySelector('#reg_cat_gun');
    let reg_gun_code = document.querySelector('#reg_gun_code');
    let reg_gun_status = document.querySelector('#reg_gun_status');

    //clear_reg_cat_gun();
    //clear_reg_cat_gun_status();
    
    if ( el.value == "1" )
    {

        reg_cat_gun.disabled = false;
        reg_gun_code.disabled = false;
        reg_gun_status.disabled = false;

        setTimeout(() => {
            //get_reg_guns_license();                
            //get_reg_guns_status();
        }, 300);

    }            
    else
    {
        reg_cat_gun.disabled = true;
        reg_gun_code.disabled = true;
        reg_gun_status.disabled = true;
    }
    
};

const render_reg_guns_license_list = () => {
    JSON.parse(localStorage.reg_guns_license_category).forEach( item => {
        let reg_cat_gun = document.querySelector('#reg_cat_gun');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.gun_license;
                        
            reg_cat_gun.appendChild(option);
            
    });
};

const render_reg_guns_status_list = () => {
    JSON.parse(localStorage.reg_guns_status_category).forEach( item => {
        let reg_gun_status = document.querySelector('#reg_gun_status');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.gun_status;
                        
            reg_gun_status.appendChild(option);
            
    });
};

const render_reg_visit_single_data = () => {

    reg_trigger_spinner('show');
    //console.log(JSON.parse(localStorage.reg_visit_single_data));

    JSON.parse(localStorage.reg_visit_single_data).forEach( item => {
                    
        document.querySelector('#reg_visitant_id').value = item.id;
                document.querySelector('#reg_name').value = item.name;
            document.querySelector('#reg_last_name').value = item.last_name;
        
        let reg_genders_opc = document.querySelectorAll('#reg_genders > option');

            reg_genders_opc.forEach( opc => {
            
                if ( opc.value == item.gender_id ) opc.selected = true;

            });

            document.querySelector('#reg_birth_date').value = item.birth_date;
            document.querySelector('#reg_last_visit_date').value = item.last_visit_date;
            
            // photo-box

            let filename = JSON.parse(item.photo_path).filename;
            let public_url = JSON.parse(item.photo_path).public_url;

            let public_photo = public_url + filename;

            if (filename.length > 0)
            {
                const photo_img_picture = document.getElementById('photo_img_picture');
                        photo_img_picture.src = public_photo;
                show_temp_photo(true);
            }            
            else
            {
                show_temp_photo(false);
            }

    });

    reg_trigger_spinner();
};

const is_document_type_selected = () => {
                
    const reg_filter_option = document.querySelectorAll("input[name=reg_filter_option]");

    let bool = false;

    if (reg_filter_option.length > 0){

        reg_filter_option.forEach( item => {

            if (item.checked == true) {     

                bool = true;
            }
        });

    }
            
    return bool;
}

const focus_dom_element_by_id = ( string_with_element_id = "" ) => {

    let element = document.getElementById(string_with_element_id);

    if (element){
        element.focus();
    }
}

const handle_reg_search_visitant_btn = () => {
    
    // limpiamos la data anterior
    localStorage.reg_visit_single_data = "";

    clear_photo_path_base64data();

    if ( is_document_type_selected() ){        

        let el = document.querySelector('#reg_search_visit_input');

        if (el.value.length > 3)
        {

            let reg_visitant_search_info = {
                'ident_number'  : document.querySelector('#reg_search_visit_input').value.trim(),
                'ident_type_id' : "",
            };
            
            let reg_identification_id = document.querySelector('#reg_identification_id');
                reg_identification_id.value = reg_visitant_search_info.ident_number;
                
            let reg_filter_option = document.querySelectorAll('input[name=reg_filter_option]')
    
                reg_filter_option.forEach( item => {                
                    if ( item.checked ) reg_visitant_search_info.ident_type_id = item.value.trim();
                });       
                
                localStorage.reg_visitant_search_info = JSON.stringify(reg_visitant_search_info);        
                //console.log(localStorage.reg_visitant_search_info);
    
                let reg_identification_type = document.querySelector('#reg_identification_type');            
    
            setTimeout(() => {                
                for (let i = 0; i < reg_identification_type.children.length; i++) {                    
                    if ( reg_identification_type.children[i].value == JSON.parse(localStorage.reg_visitant_search_info).ident_type_id ) reg_identification_type.children[i].selected = true;                    
                }                
            }, 100);
                
    
            setTimeout(() => {
                check_reg_visit_ident();            
            }, 300);

        }
    }
    else if ( document.getElementById('reg_input_result').placeholder != "VISITANTE ACTIVO" )
    {
        show_notification_message( "Seleccione un tipo de documento." ,'warning');

        focus_dom_element_by_id('reg_search_visit_input');
    }


};

const handle_reg_coworkers_list_select = () => {
    let reg_business_contact_select = document.querySelector('#reg_business_contact');
        reg_business_contact_select.addEventListener( 'change',(el) => render_reg_coworker_department(reg_business_contact) );   
};
    
const handle_query_input_keyup = () => {
    
    let el = document.querySelector('#reg_search_visit_input');

    let reg_search_visitant_btn = document.querySelector('#reg_search_visitant_btn');

    if ( is_document_type_selected ){

        if (el.value.length > 3)
        {
            el.parentNode.setAttribute('style','');
            reg_search_visitant_btn.setAttribute('class','');
            reg_search_visitant_btn.disabled = false;
        }
        else
        {
            el.parentNode.setAttribute('style','border: 1px solid lightgrey');
            reg_search_visitant_btn.setAttribute('class','btn_disabled');
            reg_search_visitant_btn.disabled = true;
        } 
    }
    else{
        show_notification_message("Selecione un tipo de documento.","warning");

        focus_dom_element_by_id('reg_search_visit_input');
    }
};

const handle_reg_btn_success = () => {

    localStorage.reg_visit_info = "";

    let reg_visitant_info = {};
    
    // capturamos los datos a enviar para registrar la visita

    reg_visitant_info.id_visitant = "";

    var visitant_info = "";

    if ( localStorage.reg_visit_single_data != 'undefined' && localStorage.reg_visit_single_data.length > 0 )
    {
        if ( visitant_info = JSON.parse(localStorage.reg_visit_single_data)[0] )
        {
            reg_visitant_info.id_visitant = visitant_info.id_visitant;
            reg_visitant_info.photo_path  = visitant_info.photo_path;
        }        
    }
    
    // informacion del visitante
    reg_visitant_info.name                   = document.querySelector('#reg_name').value.toUpperCase();
    reg_visitant_info.last_name              = document.querySelector('#reg_last_name').value.toUpperCase();
    reg_visitant_info.gender_id              = document.querySelector('#reg_genders').value;
    reg_visitant_info.birth_date             = document.querySelector('#reg_birth_date').value; 
    reg_visitant_info.ident_number           = document.querySelector('#reg_identification_id').value;
    reg_visitant_info.ident_type_id          = document.querySelector('#reg_identification_type').value;

    // informacion de la visita        
    reg_visitant_info.week_day_id            = document.querySelector('#reg_week_day_id').value;
    //reg_visitant_info.coworker_id          = document.querySelector('#reg_business_contact').value;
    reg_visitant_info.raw_coworker_full_name = document.querySelector('#raw_coworker_full_name').value.toUpperCase();
    reg_visitant_info.raw_coworker_dpt_id    = document.querySelector('#contact_dpto_from_select').value;
    reg_visitant_info.level_access_id        = document.querySelector('#reg_access_level').value;
    reg_visitant_info.has_gun                = document.querySelector('#reg_has_gun').value;
    reg_visitant_info.gun_status_id          = document.querySelector('#reg_gun_status').value;
    reg_visitant_info.reason_of_visit_id     = document.querySelector('#reg_visit_concert').value;
    reg_visitant_info.license_type_id        = document.querySelector('#reg_cat_gun').value;
    reg_visitant_info.license_number         = document.querySelector('#reg_gun_code').value;
    reg_visitant_info.start_comments         = document.querySelector('#visit_observations').value;
    reg_visitant_info.base64data             = localStorage.base64data;

    const photo_img_picture = document.getElementById('photo_img_picture');

    reg_visitant_info.image_src             = photo_img_picture.src;
   

    localStorage.reg_visitant_info = JSON.stringify(reg_visitant_info);
    
    

    setTimeout(() => {
        push_reg_visitant_info();
    }, 300);

};

/**
 * Verifica que todos los campos esten correctamente y habilita o no el boton de registro.
 */
const validate_reg_fields = () => {        

    let reg_btn_success = document.querySelector('#reg_btn_success');
    
    reg_fields_obj = {
        reg_name                : document.querySelector('#reg_name').value,
        reg_last_name           : document.querySelector('#reg_last_name').value,
        reg_genders             : document.querySelector('#reg_genders').value,
        reg_identification_id   : document.querySelector('#reg_identification_id').value,
        reg_identification_type : document.querySelector('#reg_identification_type').value,
        reg_birth_date          : document.querySelector('#reg_birth_date').value,            
        //reg_business_contact    : document.querySelector('#reg_business_contact').value,
        raw_coworker_full_name  : document.querySelector('#raw_coworker_full_name').value.toUpperCase(),
        //contact_dpto            : document.querySelector('#contact_dpto').value,
        contact_dpto            : document.querySelector('#contact_dpto_from_select').value,
        raw_coworker_dpt_id     : document.querySelector('#contact_dpto_from_select').value,
        reg_access_level        : document.querySelector('#reg_access_level').value,
        reg_visit_concert       : document.querySelector('#reg_visit_concert').value,
                
    };
        
    setTimeout(() => {
            
        let counter = 0;

        // contamos los valores vacios
        for (let i in reg_fields_obj ) { 
            counter = ( reg_fields_obj[i].length > 0) ? counter : 1;
        }

        for (let i in reg_fields_obj ) {                        
            

            if ( reg_fields_obj[i].length > 0 && counter == 0 )
            {                                
                reg_btn_success.disabled = false;

                let reg_has_gun = document.querySelector('#reg_has_gun');

                if ( parseInt(reg_has_gun.value) > 0)
                {
                    let reg_cat_gun    = document.querySelector('#reg_cat_gun');
                    let reg_gun_code   = document.querySelector('#reg_gun_code');
                    let reg_gun_status = document.querySelector('#reg_gun_status');

                    
                    if ( 
                        reg_cat_gun.value.length > 0 && 
                        reg_gun_code.value.length > 0 && 
                        reg_gun_status.value.length > 0 &&
                        reg_access_level.value.length > 0 &&
                        reg_visit_concert.value.length > 0
                    )
                    {
                        reg_btn_success.disabled = false;
                    }
                    else
                    {
                        reg_btn_success.disabled = true;
                    }
                }  

            }
            else
            {
                reg_btn_success.disabled = true;
            }

        }
                

    }, 300);            

};


const clean_reg_visit_single_data = (visitant_status = 'new_visitant') => {


    // limpiamos el objeto.
    localStorage.reg_visit_info = "";
        
    const reg_input_result = document.getElementById('reg_input_result');
    
    if ( reg_input_result && reg_input_result.placeholder != "USUARIO NO REGISTRADO." ){

        // limpiamos el input de documento de identidad
        let reg_identification_id = document.querySelector('#reg_identification_id');
            reg_identification_id.value = "";

        // limpiamos el tipo de documento.
        const reg_filter_option = document.querySelectorAll("input[name=reg_filter_option]");        
                reg_filter_option.forEach( item => {
                    item.checked = false;
                });
    }
    else if (visitant_status == 'new_visitant')
    {
        // limpiamos el input de nombres
        let reg_name = document.querySelector('#reg_name');        
            reg_name.value = "";

        // limpiamos el input de apellidos
        let reg_last_name = document.querySelector('#reg_last_name');
            reg_last_name.value = "";
        
        
        // limpiamos el input ultima visita
        let reg_last_visit_date = document.querySelector('#reg_last_visit_date');
            reg_last_visit_date.value = "";
            
    }

    //remover foto -> ver input-file-cropper.js
    show_temp_photo(false);

    // deshabilitar boton de registro
    let reg_btn_success = document.querySelector('#reg_btn_success');
        reg_btn_success.disabled = true;

    // deshabilitar input codigo de licencia
    let reg_gun_code = document.querySelector('#reg_gun_code');
        reg_gun_code.disabled = true;

    let cv_register_wrapper_inputs = document.querySelectorAll('#cv_register_wrapper input');
    let cv_register_wrapper_selects = document.querySelectorAll('#cv_register_wrapper select');

    // limpiar textarea observaciones al iniciar la visita
    let visit_observations = document.querySelector('#visit_observations');
        visit_observations.value = "";

    counter=0;
    cv_register_wrapper_inputs.forEach( item => {

        if (counter > 3 && (counter < 8 || counter > 8) && (counter < 12 || counter > 13)  ) item.value = ""; counter++;
            // se agrego el valor 2023-03-20 fijo en el campo fecha nacimiento
        if (item.id == "reg_birth_date")
        {
                item.value = "2023-03-20"; 
        }
    });

    // recoger array de elementos select
    cv_register_wrapper_selects.forEach( item => {
        
        for (let i=0; i< item.children.length; i++) {                

            // se agrego el valor 3 fijo en el campo sexo
            //if ( item.id == "reg_genders") item.value = "3";

            if ( item.id == "reg_cat_gun") item.disabled = true;
            if ( item.id == "reg_gun_status") item.disabled = true;

            if (item.id !== 'reg_identification_type' )
            {
                if (i == 0) item.children[i].selected = true;
                
                if (item.id == 'reg_has_gun')
                {
                    if ( i == 1) item.children[i].selected = true;
                }
            }

        };

    }); 

    get_current_week_day();

};

/**
 * Desabilita los campos del formulario
 */
const disable_register_form = () => {
    let cv_register_wrapper_inputs = document.querySelectorAll('#cv_register_wrapper input');
    let cv_register_wrapper_selects = document.querySelectorAll('#cv_register_wrapper select');
    
    let reg_btn_success = document.getElementById('reg_btn_success');
        reg_btn_success.disabled = true;

    let counter = 0;
    cv_register_wrapper_inputs.forEach( item => {

        if (counter > 3 && counter != 13) {
            item.value = "";
            item.disabled = true;
        } 
        counter++;        
            
    });

    // recoger array de elementos select
    cv_register_wrapper_selects.forEach( item => {
                
        for (let i=0; i< item.children.length; i++) {                

            item.disabled = true;

        };        

    }); 

    clean_reg_visit_single_data();
}

/**
 * Habilita los campos del formualario
 * @param {string} visitant_status 
 */
const enable_register_form = (visitant_status = 'register') => {
    let cv_register_wrapper_inputs = document.querySelectorAll('#cv_register_wrapper input');
    let cv_register_wrapper_selects = document.querySelectorAll('#cv_register_wrapper select');
    
    let counter = 0;
    cv_register_wrapper_inputs.forEach( item => {

        if ( counter != 7 && counter != 8 && counter != 9 && counter != 18)
        {
            item.disabled = false;
        }
        counter++;   
    });
    
    // habilitamos el input file de la imagen
    let input_file_cropper = document.getElementById('input_file_cropper');
        input_file_cropper.disabled =false;

    counter = 0;
    // recoger array de elementos select
    cv_register_wrapper_selects.forEach( select => {        

        if (counter != 1 && counter != 7 && counter != 8)
        {
            select.disabled = false;                  
        }            
        counter++;
        

    }); 

    if (visitant_status == 'new_visitant') clean_reg_visit_single_data('new_visitant');
}

/**
 * Verifica el estado del visitante por su documento de identidad.
 */
const check_reg_visit_ident = async () => {

    loading();
    
    const reg_form_id = document.getElementById('reg_form_id');


    let body_data = JSON.stringify({                
        target          : "register_visitants-read_single",
        info_data       : JSON.parse(localStorage.reg_visitant_search_info),
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,        
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

                const reg_input_result = document.getElementById('reg_input_result');
                
                if (response.message == "Usuario registrado.")
                {                    
                    localStorage.reg_visit_single_data = JSON.stringify(response.data.fetched);
                    
                    reg_input_result.setAttribute('placeholder', response.message.toUpperCase() );
                    
                    render_reg_visit_single_data();
                    enable_register_form('register');                    
                }
                else if (response.message == "Usuario no registrado.")
                {
                    reg_input_result.setAttribute('placeholder', response.message.toUpperCase() );
                    
                    enable_register_form('new_visitant');
                }
                else if (response.message == "El visitante esta activo.")
                {
                    reg_input_result.setAttribute('placeholder', response.message.toUpperCase() );
                    
                    disable_register_form();
                    
                }
                else if (response.message == "Usuario Deshabilitado.")
                {
                    reg_input_result.setAttribute('placeholder', response.message.toUpperCase() );
                    
                    disable_register_form();
                    
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

// limpiar lista de colaboradores
const clear_reg_coworkers_list = () => {
    let coworkers_list = document.querySelectorAll('#reg_business_contact > option');

    if ( coworkers_list.length > 1 )
    {
        for (const key in coworkers_list) {

            if (key > 0) coworkers_list[key].remove();

        }
    }

    render_reg_coworkers_list();
}

// renderizar lista de colaboradores
const render_reg_coworkers_list = () => {
    JSON.parse(localStorage.reg_coworkers_category).forEach( item => {
        let reg_business_contact = document.querySelector('#reg_business_contact');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.name + " " + item.last_name;
                        
            reg_business_contact.appendChild(option);
            
    });
    setTimeout(() => {
        handle_reg_coworkers_list_select();
    }, 100);
};

// obtener lista de colaboradores
const get_reg_coworker_list = async () => {
    let body_data = JSON.stringify({                
                        target : "category",
                        table_name : CVVW_COWORKER_INFO_TABLE,
                        //info_data: JSON.parse(localStorage.pt_dist_info),                            
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id
                    });
    
    //console.log(body_data);

    try {

        let url = API_MANAGE_DB_URL;
        
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
                localStorage.reg_coworkers_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    clear_reg_coworkers_list();
                    //render_reg_coworkers_list();
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
}; //window.addEventListener( 'DOMContentLoaded' , get_reg_coworker_list );

// Escuchar Evento lista de colaboradores
/* let cv_register_visit_btn = document.querySelector('#cv_register_visit_btn');
    cv_register_visit_btn.addEventListener('click', get_reg_coworker_list ); */

/**
 * Obtiene los tipos de generos
 */
const get_reg_genders = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
                        target       : "categories-read_genders",                        
                        session_token: localStorage.session_token,
                        user_id      : this.state.data.fetched.user_id,                        
                        form_id      : reg_form_id.value
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
                localStorage.reg_genders_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_genders_list();
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

}; window.addEventListener('DOMContentLoaded', get_reg_genders );

/**
 * Obtiene los tipos de identificacion
 */
const get_reg_identification_type = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
                        target       : "categories-read_identificationtype",                                         
                        session_token: localStorage.session_token,
                        user_id      : this.state.data.fetched.user_id,
                        form_id      : reg_form_id.value
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
                localStorage.reg_identification_type_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_ident_type_list();                        
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

}; window.addEventListener('DOMContentLoaded', get_reg_identification_type );

/**
 * Obtiene los niveles de accesso
 */
const get_reg_level_access = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "categories-read_levelaccess",                
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,
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
                localStorage.reg_level_access_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_level_access_list();
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
}; window.addEventListener( 'DOMContentLoaded' , get_reg_level_access );

/**
 * Obtiene la razon de la visita
 */
const get_reg_visit_reason = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "categories-read_visitreason",                
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,
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
                localStorage.reg_visit_reason_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_visit_reason_list();
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
}; window.addEventListener( 'DOMContentLoaded' , get_reg_visit_reason );

/**
 * Obtiene los tipos de licencias
 */
const get_reg_guns_license = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "categories-read_gunslicense",        
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,
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
                localStorage.reg_guns_license_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_guns_license_list();
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
}; window.addEventListener( 'DOMContentLoaded' , get_reg_guns_license );

/**
 * Obtiene el estatus del arma
 */
const get_reg_guns_status = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "categories-read_gunstatus",        
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,
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
                localStorage.reg_guns_status_category = JSON.stringify(response.data.fetched);
                setTimeout(() => {                        
                    render_reg_guns_status_list();
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
}; window.addEventListener( 'DOMContentLoaded' , get_reg_guns_status );

const get_current_week_day = () => {

    const date = new Date();

    localStorage.today = date.getDay()+1;

    setTimeout(() => {
        render_current_week_day();
    }, 100);

}; window.addEventListener( 'DOMContentLoaded' , get_current_week_day );

/**
 * Obtiene la lista de distribucion en planta
 */
const co_reg_plant_distibution = async () => {

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
                        target : "categories-read_plantdistribution",                        
                        session_token: localStorage.session_token,
                        user_id: this.state.data.fetched.user_id,                        
                        form_id : reg_form_id.value
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
                    render_reg_contact_dpto_list();                    
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

}; window.addEventListener('DOMContentLoaded', co_reg_plant_distibution );

const render_current_week_day = () => {
    document.querySelector('#reg_week_day_id').value = localStorage.today;
};

const render_reg_visit_data_fetched = () => {

    JSON.parse(localStorage.reg_visit_data_fetched).forEach( item => {
        document.querySelector('#reg_visitant_id').value = item.id;
    });

    setTimeout(() => {                        
        set_reg_visitant_info_two();
    }, 300);

};

const clear_reg_contact_dpto_list = () => {
    const contact_dpto_from_select_all = document.querySelectorAll('#contact_dpto_from_select > option');

    for (let i = 0; i < contact_dpto_from_select_all.length; i++) {            
        contact_dpto_from_select_all[i].remove();
    }
}

const render_reg_contact_dpto_list = () => {

    //clear_reg_contact_dpto_list();

    setTimeout(() => {
        let co_plant_distribution_category = JSON.parse(localStorage.co_plant_distribution_category);

        const contact_dpto_from_select = document.getElementById('contact_dpto_from_select');

        //let optionDOM = `<option value="" selected> -- Seleccione un Departamento -- </option>`;
        let optionDOM = ``;
        for (const key in co_plant_distribution_category) {                
            optionDOM += `<option value="${co_plant_distribution_category[key].id}">${co_plant_distribution_category[key].department}</option>`;                
        }

        contact_dpto_from_select.innerHTML = optionDOM;
        
        setTimeout(() => {
            $("#contact_dpto_from_select").trigger("chosen:updated");                            
        }, 1000);

    }, 500);

}

const push_reg_visitant_info = async () => {

    loading();
    
    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "register_visitants-put_single",
        info_data       : JSON.parse(localStorage.reg_visitant_info),
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,        
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
        //const response = await server.text();

        console.log(response);
        
        loading();

        
        switch (server.status) {
            case 200:
                
                if (response.message == "Datos Guardados")
                {                    
                    // ver dashboard.js
                    get_reg_db_active_visitants();
                    setTimeout(() => {
                        show_notification_message(response.message.capitalize(),'success');
                        disable_register_form();
                        show_print_visitant_name_modal();
                    }, 50);
                }                               
                else if (response.message == "El visitante esta activo.")
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

const show_print_visitant_name_modal = () => {
    console.log('print tag5')
    const gral_setting_options_info = JSON.parse(localStorage.gral_setting_options_info);

    gral_setting_options_info.forEach( (item, key, array) => {
        
        //if ( item.printer_id_status == "1" && (array.length-1 == key) )
        if ( array.length - 1 == key && item.printer_id_status == 1 )
        {

            $('#vt_name_modal_wrapper').modal('show');
            launch_modal_visitant_register_data();

        }

    });

}

/**
 * Limpia el localstorage que contiene la imagen temporal
 */
const clear_photo_path_base64data = () => {

    console.log('clear_photo_path_base64data');
    
    localStorage.base64data = "";
    const photo_img_picture = document.getElementById('photo_img_picture');
          photo_img_picture.src = "";
    
        
}

/**
 * Guarda un objeto con los visitantes activos en localstorage
 */
const get_reg_db_active_visitants = async () => {
                
    //loading();

    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "dashboard-read_visitants",
        info_data       : JSON.parse('{}'),
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,        
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

        //loading();

        switch (server.status) {
            case 200:
                if (response.message == "Datos Recuperados")
                {
                    localStorage.db_active_visitants_data_fetched = JSON.stringify(response.data);
                }
                else if (response.message == "no data")
                {
                    //actionMessage('No hay Resultados'.capitalize(),'warning');
                }
                else
                {
                    //window.location.href = URL_BASE+"/";
                }


                break;
            
            case 401:
                //window.location.href = URL_BASE+"/";   
                break;
        
            default:
                actionMessage(response.message.capitalize(),'warning');
                break;
        }
    } catch (error) {

        console.log(error);

    }
};

const get_general_settings = async () => {
    
    const reg_form_id = document.getElementById('reg_form_id');

    let body_data = JSON.stringify({                
        target          : "general_settings-read",                
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : reg_form_id.value,
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
                localStorage.gral_setting_options_info = JSON.stringify(response.data.fetched);                
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
}; window.addEventListener( 'DOMContentLoaded' , get_general_settings );

const assign_reg_events = () => {
    
    //inicializacion
    localStorage.temp_file_name = "";
    localStorage.reg_visitant_info_one = "";
    clear_photo_path_base64data();

    // habilitar o no campos para detalles del arma
    let reg_has_gun = document.querySelector('#reg_has_gun');
        reg_has_gun.addEventListener( 'change',(el) => render_reg_has_gun(reg_has_gun) );   
        
    let reg_search_visitant_btn = document.querySelector('#reg_search_visitant_btn');
        reg_search_visitant_btn.addEventListener('click', handle_reg_search_visitant_btn );

    // maneja el registro del formulario
    let reg_btn_success = document.querySelector('#reg_btn_success');
        reg_btn_success.addEventListener('click', handle_reg_btn_success );

    let reg_search_visit_input = document.querySelector('#reg_search_visit_input');
        reg_search_visit_input.addEventListener( 'keyup' , handle_query_input_keyup );

    /* Eventos para validar el formulario  */

    let visitant_details_container_inputs = document.querySelectorAll('#visitant_details_container input');

        visitant_details_container_inputs.forEach( item => {
            item.addEventListener('keyup' , validate_reg_fields )
        });

    let visitant_details_container_selects = document.querySelectorAll('#visitant_details_container select');

        visitant_details_container_selects.forEach( item => {
            item.addEventListener('change' , validate_reg_fields )
        });

    let visit_details_container_inputs = document.querySelectorAll('#visit_details_container input');

        visit_details_container_inputs.forEach( item => {
            item.addEventListener('keyup' , validate_reg_fields )
        });

    let visit_details_container_selects = document.querySelectorAll('#visit_details_container select');

        visit_details_container_selects.forEach( item => {
            item.addEventListener('change' , validate_reg_fields )
        });

    // asigna un evento de click para limpiar el localstorange de la imagen temporal
    let erase_temp_photo_btn = document.getElementById('erase_temp_photo_btn');
        erase_temp_photo_btn.addEventListener('click', clear_photo_path_base64data );

    // estiliza el select para el departamento del colaborador. Ver -> render_reg_contact_dpto_list()
    $("#contact_dpto_from_select").chosen();
        
}; window.addEventListener( 'DOMContentLoaded' , assign_reg_events );