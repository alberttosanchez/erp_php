import { ValidateForms } from "./../../class/class.index.js";

(function(ValidateForms){

    const file = document.querySelector('#student_photo');

    const image = document.getElementById("img-original");

    const modal = document.getElementById("div-modal");

    const temp_photo = document.querySelector('.image_picture > img');

    if ( typeof localStorage.temporalImageKey === 'undefined' )
    {
        // guardamos un numero unico para la imagen temporal
        localStorage.temporalImageKey = Date.now().toString();
    }

    //si deseamos interactuar con el modal usando los metodos nativos de bootstrap5
    //debemos construirlo pasando el elemento. En nuestro caso .show() y .hide()
    const objmodal = new bootstrap.Modal(modal, {
        //que el modal no interactue con el teclado
        keyboard: false
    })

    file.addEventListener("change", function (ev) {

        const load_image = (url) => {
            image.src = url;
            //temp_photo.src = url;
            objmodal.show();
        };

        const files = ev.target.files;

        // si hay un archivo, entonces ...
        if ( files && files.length > 0 ) {

            const objfile = files[0];
            //el objeto file tiene las propiedades: name, size, type, lastmodified, lastmodifiedate
            
            //para poder visualizar el archivo de imagen lo debemos pasar a una url 
            //el objeto URL está en fase experimental así que si no existe usaria FileReader
            if ( URL ){

                //crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
                const url = URL.createObjectURL(objfile);
                load_image(url);

            }
            else if (FileReader) {

                const reader = new FileReader();

                reader.onload = (ev) => {
                    load_image(reader.result);
                };

                reader.readAsDataURL(objfile);

            }
        }

    });

    //el objeto cropper que habrá que crearlo y destruirlo. 
    //Crearlo al mostrar el modal y destruirlo al cerrarlo
    let cropper = null;
    modal.addEventListener("shown.bs.modal", function (){
        console.log("modal.on-show")
        //crea el marco de selección sobre el objeto $image
        cropper = new Cropper( image, {
            //donde se mostrará la parte seleccionada
            preview: document.getElementById("div-preview"),
            //3: indica que no se podrá seleccionar fuera de los límites
            viewMode: 3,
            //NaN libre elección, 1 cuadrado, proporción del lado horizontal con respecto al vertical
            aspectRatio: CROOPER_NEW_STUDENT_PROFILE_PIC_ASPECT_RATIO, // ex. 114 / 132
        });
    });

    modal.addEventListener("hidden.bs.modal", function (){
        file.value = "";
        console.log("modal.on-hide");
        cropper.destroy();
        cropper = null;
    });    

    const erase_temp_photo_btn = document.querySelector('#erase_temp_photo_btn');
    erase_temp_photo_btn.addEventListener('click', () => { 
        temp_photo.src = ""; 
        show_temp_photo(false);
    });

    const btncrop = document.getElementById("btn-crop");
    //configuramos el click del boton crop
    btncrop.addEventListener("click", function (){

        //render_style_on_temp_photo();
        
        //obtenemos la zona seleccionada
        const canvas = cropper.getCroppedCanvas();

        canvas.toBlob(function (blob){
            //el objeto blob (binary larege object) tiene las propiedades: size y type
            const reader = new FileReader();
            //se pasa el binario base64
            reader.readAsDataURL(blob);

            reader.onloadend = function (){
                const base64data = reader.result;
                //base64data es un string del tipo: data:image/png;base64,iVBORw0KGgoAAAA....
                console.log("base64data", base64data);
                
                const contentType = 'image/png';

                let blob_image = create_blob_img(base64data, contentType);

                render_blob_image_on_temp_new_student_photo(blob_image);

                let temporalImageKey = localStorage.temporalImageKey;

                //remove_temporal_image(temporalImageKey);
            
            };
        });
    });

    const show_temp_photo = (bool = true) =>{

        
        if (bool)
        {        
            removeClassOnElementsByArrayWithIds('no-show', ['image_picture']);        
            setClassOnElementsByArrayWithIds('no-show', ['image_icon']);
        }
        else
        {
            removeClassOnElementsByArrayWithIds('no-show', ['image_icon']);        
            setClassOnElementsByArrayWithIds('no-show', ['image_picture']);
        }
    }

    const render_blob_image_on_temp_new_student_photo = (blob_image) =>{
        
        const blobUrl = URL.createObjectURL(blob_image);          
        
        temp_photo.src = blobUrl;

        show_temp_photo(true);
    };

    const create_blob_img = (b64Data, contentType='', sliceSize=512) => {

        let arrayB64Data = b64Data.split(',');
        const byteCharacters = atob(arrayB64Data[1]);
        const byteArrays = [];
        
        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            const slice = byteCharacters.slice(offset, offset + sliceSize);
        
            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
            }
        
            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
        
        const blob = new Blob(byteArrays, {type: contentType});
        return blob;
    }

    const upload_temporal_image = async (image_file,key_for_image) => {

        //en mi caso estoy trabajando con php en el back pero puede ser cualquier url
        const url = "/index.php?f=crop_first&nohome=1"
        console.log(url);
        await fetch( url , {
            method: "POST",
            headers: {
                //si la respuesta del servidor no es un json saltará una excepción en js
                "Accept": "application/json",
                //le indica al servidor que se le enviará un json
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                image: image_file, // base 64
                key : key_for_image
            })
        })
        .then(response => response.json())
        .then(function (result){
            file.value = "";    //resetea el elemento input-file (file-upload)
            objmodal.hide();     //escondo el modal
            //result es algo como: {message:"image uploaded successfully.", file:"upload/uuid.png"}
            console.log(result);
            show_notification_message(result.message,'warning');

            //este es el img que está debajo del elemnto input-file
            const img = document.getElementById("img-uploaded");
            img.src = "/"+result.file;
            img.style.visibility = "visible";

            const span = document.getElementById("span-uploaded");
            span.innerText=img.src;
        });

    };

    const triggerPhotoSpinner = (bool = true) =>{

        if ( bool )
        {
            // carga el loading en el div de la foto de perfil.     
            triggerSpinner('show'); // -> ver functions.js del modulo
            // desabilita un elemento DOM por su id
            elementDisabledById('student_photo');
            elementClassDisabledById('span_student_photo');
        } 
        if ( ! bool )
        {
            triggerSpinner('no-show');        
            elementEnabledById('student_photo');
            elementClassEnabledById('span_student_photo');
        }

    }

    const remove_temporal_image = async (temporalImageKey) =>{

        console.log('temporalImageKey',temporalImageKey);

        triggerPhotoSpinner();

        try
        {    
            const server = await fetch( MODULE_API_URL ,{
                method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    target : "remove_temp_new_student_photo",
                    temp_image_key: temporalImageKey,
                    session_token: this.state.data.fetched.session_token,
                    user_id: this.state.data.fetched.user_id,            
                    role_id: this.state.data.fetched.role_id,                
                    form_id : document.querySelector('#new_student_form_id').value
                })                                          
            });

            const response = await server.text();

            //console.log(server);
            //console.log(response);
            
            if(server.status === 200)
            {

                if ( temporal_image_was_removed )
                {
                    localStorage.temporalImageKey = Date.now().toString();
                }

                setTimeout(() => {
                    upload_temporal_image(base64data,localStorage.temporalImageKey);                
                }, 100);

                this.state = {
                    ...this.state,
                    form:{
                        ...this.state.form,
                        LoginChangePassword__newPassword  : "",
                        LoginChangePassword__newPassword2 : "",                    
                        disabled: true,
                    }
                };

                this.inputToggleDisabled();

                //this.loading();

                this.actionMessage("La contraseña fue cambiada correctamente.","password");

                setTimeout(() => {                    
                    window.location.href = URL_BASE;
                }, 3000);
            }
            else if (server.status === 401)
            {
                console.log(response.message);
                window.location.href = URL_BASE;
            }
            else if(server.status === 409)
            {
                show_notification_message(response.message,'warning');
                //this.actionMessage(response.message,"password");
            }
            else
            {
                show_notification_message('El servicio no esta diponible, intente más tarde.','error');
                //this.actionMessage("El servicio no esta diponible, intente más tarde.","password");
                console.log("Error 500. contacte al administrador");
            }
            
        }
        catch (error)
        {
            //this.loading();
            show_notification_message('El servicio no esta diponible, intente más tarde.','error');
            //this.actionMessage("El servicio no esta diponible, intente más tarde.","password");
            console.log(error);        
        }

    }

    // enviar archivo a la carpeta temporal
    const push_reg_on_staging_temp_file = async (file_data) => {

        console.log(file_data);

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
                        console.log(response);
                        window.location.href = URL_BASE;
                    }
                    else if (server.status === 403)
                    {
                        console.log("estado:403"); 
                    }
                    else if (server.status === 409)
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

    function validate_form_fields(form,object_with_rules,array_with_custom_messages = []){

    }

    function validate_form(form){

        const Form = new ValidateForms;
        
        let response = Form.validateFormFields(form,{
            names                   : "string|min-length:3|max-length:100",
            last_name_one           : "string|min-length:3",
            //last_name_two           : "string|min-length:3",
            gender_id               : "integer|required",
            birth_date              : "date|length:10|min-years-old:18",
            student_photo           : "blob|required",
            id_code                 : "integer|required",
            id_type                 : "integer|required",
            id_issue_entity         : "integer|required",
            nationality_id          : "integer|required",
            issue_date              : "string|length:10",
            expire_date             : "string|length:10",
            country_of_residency_id : "integer|required",
            estate_id               : "integer|required",
            city_id                 : "integer|required",
            address_one             : "string|required",
            //address_two             : "",
            //zip_code                : "",
            //movil_phone             : "",
            //home_phone              : "",
        },[
            "El nombre debe tener al menos 3 y un máximo de 100 caracteres.",
            "El Primer Apellido debe tener al menos 3 caracteres.",
            "El Segundo Apellido debe tener al menos 3 caracteres.",
            "Selecione un genero.",
            "Su fecha de nacimiento debe ser mayor de 18 años.",
            "La foto de perfil es necesaria."

        ]);                

        return response;

    }

    const enabled_disabled_student_new_submit_btn = (condition = 'enabled') =>{

        const btn_student_new_register = document.querySelector('.btn_student_new_register');        
              btn_student_new_register.disabled = (condition == 'disabled') ? true : false;
    }

    const show_or_hidde_student_new_spinner_submit_btn = (condition = 'no-show') =>{

        if (condition === "show")
        {
            enabled_disabled_student_new_submit_btn('disabled');
            triggerSpinner("show","bcmj_spinner_box");
            toggle_class_on_dom_element_by_id('submit_text_btn','no-show');
            
        }
        else
        {
            enabled_disabled_student_new_submit_btn('enabled');
            triggerSpinner("no-show","bcmj_spinner_box");
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

    const handle_student_new_register_form = (ev) => {
        console.log('form send');
        ev.preventDefault();

        const form = new FormData(ev.target);
        console.log(form);
        show_or_hidde_student_new_spinner_submit_btn('show');
        
        setTimeout(() => {

            const result = validate_form(form)
            console.log(result);
            if ( result.status == "success" )
            {
                register_student_new(form);
            }
            else
            {
                put_focus_on_field_by_name(result.field_name,form);
                //console.log(result);
                show_notification_message(result.message,'warning');

                show_or_hidde_student_new_spinner_submit_btn('no-show');
            }
        }, 3000);

    }
    const bcmj_new_student_form = document.getElementById('bcmj_new_student_form');
        bcmj_new_student_form.addEventListener( 'submit', (ev) => handle_student_new_register_form(ev) );

}(ValidateForms));