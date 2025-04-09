clean_co_edit_modal = () => {

    localStorage.base64data = "";
    
    let co_modal_inputs = document.querySelectorAll('.co_modal_body_wrapper input');    
        co_modal_inputs.forEach( item => {
            item.value = "";
            item.innerHTML = "";
        });
    
    let co_modal_selects = document.querySelectorAll('.co_modal_body_wrapper select');
        co_modal_selects.forEach( item => {        
            item.value = "";
        });        

    let photo_img_picture = document.getElementById('photo_img_picture');
        photo_img_picture.src = "";

    // ver input-file-cropper.js
    show_temp_photo(false);

};

handle_co_edit_modal_register_btn = () => {

    let co_edit_modal_single_data_one = {
        'id'                    : document.querySelector('#co_edit_modal_id').value,
        'name'                  : document.querySelector('#co_edit_modal_name').value.toUpperCase(),
        'last_name'             : document.querySelector('#co_edit_modal_last_name').value.toUpperCase(),
        'gender_id'             : document.querySelector('#co_edit_modal_gender').value,
        'identification_id'     : document.querySelector('#co_edit_modal_ident').value,
        'identification_type_id': document.querySelector('#co_edit_modal_type_id').value,
        'birth_date'            : document.querySelector('#co_edit_modal_birth_date').value,
    };
    let co_edit_modal_single_data_two = {
        'coworker_id'       : document.querySelector('#co_edit_modal_id').value,
        'job_department_id' : document.querySelector('#co_edit_modal_dpto').value,
        'job_title'         : document.querySelector('#co_edit_modal_job_title').value.toUpperCase(),
        'phone_extension'   : document.querySelector('#co_edit_modal_phone_ext').value,
        'job_email'         : document.querySelector('#co_edit_modal_email').value.toLowerCase(),
    };
    
    console.log(co_edit_modal_single_data_one);

    localStorage.co_edit_modal_single_data_one = JSON.stringify(co_edit_modal_single_data_one);
    localStorage.co_edit_modal_single_data_two = JSON.stringify(co_edit_modal_single_data_two);

    setTimeout(() => {
        update_co_edit_modal_single_data_one();
    }, 100);
};

const render_co_edit_photo_view_form = (coworker_id) =>{

    console.log('render_co_edit_photo_view_form');
    
    let co_edit_photo_current_row = JSON.parse(storageCurrentRow(coworker_id));
    
    
    console.log(co_edit_photo_current_row);
    
    let photo_path = JSON.parse(co_edit_photo_current_row.photo_path);
    
    let filename = photo_path.filename;
    let public_url = photo_path.public_url;

    if ( filename && public_url )
    {
        let photo_img_picture = document.getElementById('photo_img_picture');
            photo_img_picture.src = `${public_url}/${filename}`;
    
        // ver input-file-cropper.js
        show_temp_photo(true);        
    }

}

const render_co_edit_view_form = (coworker_id) => {

    clean_co_edit_modal(); 

    console.log('render_edit_co_modal');

    let co_edit_current_row = JSON.parse(storageCurrentRow(coworker_id));
    
    console.log(co_edit_current_row);

    document.querySelector('#co_modal_id').value                = co_edit_current_row.id;
    document.querySelector('#co_modal_name').value              = co_edit_current_row.name;
    document.querySelector('#co_modal_last_name').value         = co_edit_current_row.last_name;
    document.querySelector('#co_modal_gender').value            = co_edit_current_row.gender_id;
    
    document.querySelector('#co_modal_dpto').value              = co_edit_current_row.job_department_id;

    document.querySelector('#co_modal_identification_id').value = co_edit_current_row.identification_id;
    document.querySelector('#co_modal_type_id').value           = co_edit_current_row.identification_type_id;
    
    document.querySelector('#co_modal_birth_date').value        = co_edit_current_row.birth_date;
    document.querySelector('#co_modal_job_title').value         = co_edit_current_row.job_title;
    document.querySelector('#co_modal_phone_ext').value         = co_edit_current_row.phone_extension;
    document.querySelector('#co_modal_email').value             = co_edit_current_row.job_email;
    
    document.querySelector('#co_modal_row_id').value            = co_edit_current_row.id;
    

    JSON.parse(localStorage.co_genders_category).forEach( item => {
        let co_modal_gender = document.querySelector('#co_modal_gender');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.gender;
            
            if (item.id == co_edit_current_row.gender_id){
                co_modal_gender.value = item.id;
            };            
            
    });      

    JSON.parse(localStorage.co_identification_type_category).forEach( item => {
        let co_modal_type_id = document.querySelector('#co_modal_type_id');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.identification_type;
            
            if (item.id == co_edit_current_row.identification_type_id){
                co_modal_type_id.value = item.id
            };            
    });
    
    
    JSON.parse(localStorage.co_plant_distribution_category).forEach( item => {

        let co_modal_dpto = document.querySelector('#co_modal_dpto');
    
        let option = document.createElement('option');
            option.setAttribute('key', item.id);
            option.setAttribute('value', item.id);
            option.innerHTML = item.department.capitalize();

            if (item.id == co_edit_current_row.job_department_id){
                co_modal_dpto.value = item.id;
            };  
    });
    
    /* JSON.parse(localStorage.level_access_data).forEach( item => {
        let pt_dist_edit_modal_level_access = document.querySelector('#pt_dist_edit_modal_level_access');

        let option = document.createElement('option');
            option.value = item.id;
            option.setAttribute('key', item.id);
            option.innerHTML = item.level_access;
            
            if (item.level_access == co_edit_current_row.level_access){
                option.selected = true;
            };
        
            pt_dist_edit_modal_level_access.appendChild(option);
            
    }); */
};

asign_co_edit_modal_events = () => {

    const co_edit_modal_register_btn = document.querySelector('#co_edit_modal_register_btn');
            co_edit_modal_register_btn.addEventListener( 'click', handle_co_edit_modal_register_btn );

}; //window.addEventListener( 'DOMContentLoaded', asign_co_edit_modal_events )

