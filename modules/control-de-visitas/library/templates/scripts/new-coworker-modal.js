

clean_co_get_plant_distibution = () => {
    let co_modal_dpto_opc = document.querySelectorAll('#co_modal_dpto > option');
        co_modal_dpto_opc.forEach( item => { item.remove(); });
};

render_co_new_modal_genders_list = () => {

    let co_modal_gender = document.querySelector('#co_modal_gender');

    JSON.parse(localStorage.co_genders_category).forEach( item => {
    
        let option = document.createElement('option');
            option.setAttribute('key', item.id);
            option.setAttribute('value', item.id);
            option.innerHTML = item.gender.capitalize();

            co_modal_gender.appendChild(option);

    });

};

render_co_new_modal_ident_type_list = () => {   
    let co_modal_type_id = document.querySelector('#co_modal_type_id');

    JSON.parse(localStorage.co_identification_type_category).forEach( item => {
    
        let option = document.createElement('option');
            option.setAttribute('key', item.id);
            option.setAttribute('value', item.id);
            option.innerHTML = item.identification_type.capitalize();

            co_modal_type_id.appendChild(option);

    });
};

render_co_new_modal_plan_dist_list = () => {
    
    let co_modal_dpto = document.querySelector('#co_modal_dpto');

    JSON.parse(localStorage.co_plant_distribution_category).forEach( item => {
    
        let option = document.createElement('option');
            option.setAttribute('key', item.id);
            option.setAttribute('value', item.id);
            option.innerHTML = item.department.capitalize();

            co_modal_dpto.appendChild(option);

    });
};

co_new_modal_asign_events = () => {

    const   co_modal_register_btn = document.querySelector('#co_modal_register_btn');
            co_modal_register_btn.addEventListener( 'click' , () => {
                                
                if (co_modal_register_btn.innerText != 'ACTUALIZAR')
                {
                    handle_co_modal_register_btn();
                }
                else
                {                    
                    handle_co_modal_update_btn();
                }
            
            });

}; window.addEventListener('DOMContentLoaded', co_new_modal_asign_events );

const close_co_register_modal = () =>{
    var modal = bootstrap.Modal.getInstance(document.getElementById('new_coworker_modal_wrapper'));
        modal.hide();
}

const push_co_register_modal_data = async () => {
    
    loading();
    
    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
        target          : "coworkers-put_single",        
        info_data       : JSON.parse(localStorage.co_register_obj_data),
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
                //actionMessage( response.message.capitalize() , 'warning' );
                if ( response.message == 'Datos Guardados' )
                {
                    show_notification_message("Datos guardados correctamente.","success");
                    clean_co_register_modal();
                    close_co_register_modal();
                }
                else if ( response.message == 'Error. Faltan parametros.' )
                {
                    show_notification_message("Llene todos los campos.","warning");                        
                }
                else
                {
                    show_notification_message( response.message.capitalize() ,"warning");
                }
                break;
            
            case 401:
                window.location.href = URL_BASE+"/";   
                break;
        
            default:
                show_notification_message( response.message.capitalize() ,"error");
                break;
        }
    } catch (error) {

        show_notification_message( error ,"error");
        console.log(error);

    }

};

const push_co_update_modal_data = async () => {
    
    loading();
    
    const co_form_id = document.getElementById('co_form_id');

    let body_data = JSON.stringify({                
        target          : "coworkers-update_single",        
        info_data       : JSON.parse(localStorage.co_update_obj_data),
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
                //actionMessage( response.message.capitalize() , 'warning' );
                if ( response.message == 'Datos Actualizados' )
                {
                    show_notification_message("Datos actualizados correctamente.","success");
                    clean_co_register_modal();
                    close_co_register_modal();
                    handle_co_keyword_btn();
                }                
                else
                {
                    show_notification_message( response.message.capitalize() ,"warning");
                }
                break;
            
            case 401:
                window.location.href = URL_BASE+"/";   
                break;
        
            default:
                show_notification_message( response.message.capitalize() ,"error");
                break;
        }
    } catch (error) {

        show_notification_message( error ,"error");
        console.log(error);

    }

};

/**
 * Limpia el modal
 */
const clean_co_register_modal = () => {
    
    localStorage.base64data = "";

    let new_coworker_modal_wrapper_inputs = document.querySelectorAll('#new_coworker_modal_wrapper input');
        new_coworker_modal_wrapper_inputs.forEach( item => {
            item.value = "";
        });

    let new_coworker_modal_wrapper_selects = document.querySelectorAll('#new_coworker_modal_wrapper select');
        new_coworker_modal_wrapper_selects.forEach( item => {
            item.value = "";
        });

    let photo_img_picture = document.getElementById('photo_img_picture');
        photo_img_picture.src = "";

    // ver input-file-cropper.js
    show_temp_photo(false);
};

const handle_co_modal_register_btn = () => {
    
    let co_modal_name       = document.querySelector('#co_modal_name').value.toUpperCase();
    let co_modal_last_name  = document.querySelector('#co_modal_last_name').value.toUpperCase();
    let co_modal_gender_id  = document.querySelector('#co_modal_gender').value;
    let co_modal_id         = document.querySelector('#co_modal_identification_id').value.toUpperCase();
    let co_modal_type_id    = document.querySelector('#co_modal_type_id').value;
    let co_modal_birth_date = document.querySelector('#co_modal_birth_date').value;
    let co_moda_photo_pic   = document.querySelector('#photo_img_picture').src;
    let co_modal_dpto_id    = document.querySelector('#co_modal_dpto').value;
    let co_modal_job_title  = document.querySelector('#co_modal_job_title').value.toUpperCase();
    let co_modal_phone_ext  = document.querySelector('#co_modal_phone_ext').value;
    let co_modal_email      = document.querySelector('#co_modal_email').value.toLowerCase();
    let co_modal_row_id     = document.querySelector('#co_modal_row_id').value;
    
    let co_register_obj_data = {
        name                    : co_modal_name,
        last_name               : co_modal_last_name,
        gender_id               : co_modal_gender_id,
        identification_id       : co_modal_id.trim(),
        identification_type_id  : co_modal_type_id,
        birth_date              : co_modal_birth_date,
        coworker_id             : co_modal_row_id,
        job_department_id       : co_modal_dpto_id,
        job_title               : co_modal_job_title,
        phone_extension         : co_modal_phone_ext,
        job_email               : co_modal_email,
        image_src               : co_moda_photo_pic,
        image_b64               : localStorage.base64data       
    };

    localStorage.co_register_obj_data = JSON.stringify(co_register_obj_data);        
  
    setTimeout(() => {
        push_co_register_modal_data();
    }, 100);        

};

const handle_co_modal_update_btn = () => {
    
    let co_modal_id                 = document.querySelector('#co_modal_id').value.toUpperCase();
    let co_modal_name               = document.querySelector('#co_modal_name').value.toUpperCase();
    let co_modal_last_name          = document.querySelector('#co_modal_last_name').value.toUpperCase();
    let co_modal_gender_id          = document.querySelector('#co_modal_gender').value;
    let co_modal_identification_id  = document.querySelector('#co_modal_identification_id').value.toUpperCase();
    let co_modal_type_id            = document.querySelector('#co_modal_type_id').value;
    let co_modal_birth_date         = document.querySelector('#co_modal_birth_date').value;
    let co_moda_photo_pic           = document.querySelector('#photo_img_picture').src;
    let co_modal_dpto_id            = document.querySelector('#co_modal_dpto').value;
    let co_modal_job_title          = document.querySelector('#co_modal_job_title').value.toUpperCase();
    let co_modal_phone_ext          = document.querySelector('#co_modal_phone_ext').value;
    let co_modal_email              = document.querySelector('#co_modal_email').value.toLowerCase();
    let co_modal_row_id             = document.querySelector('#co_modal_row_id').value;
    
    let co_update_obj_data = {
        id                      : co_modal_id,
        name                    : co_modal_name,
        last_name               : co_modal_last_name,
        gender_id               : co_modal_gender_id,
        identification_id       : co_modal_identification_id.trim(),
        identification_type_id  : co_modal_type_id,
        birth_date              : co_modal_birth_date,
        coworker_id             : co_modal_row_id,
        job_department_id       : co_modal_dpto_id,
        job_title               : co_modal_job_title,
        phone_extension         : co_modal_phone_ext,
        job_email               : co_modal_email,
        image_src               : co_moda_photo_pic,
        image_b64               : localStorage.base64data       
    };

    localStorage.co_update_obj_data = JSON.stringify(co_update_obj_data);        
  
    setTimeout(() => {
        push_co_update_modal_data();
    }, 100);        

};
