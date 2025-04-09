this.state = {
    ...this.state,
    form : {
        ...this.state.form,        
        users_phone         : '',
        gender_id           : '',
        birth_date          : '',
        file_name           : '',
        file_type           : ''
    }
}

getProfileInfo = async () => {
        
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;

        try {
            
            // convertimos la url en un objeto
            let url = new URL( API_PROFILE_INFO_URL );
            // establecemos los parametros a enviar por GET
            let params = {
                session_token   : session_token,
                user_id         : user_id
            };
            // agregamos los parametros a la URL
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            const server = await fetch( url ,{
                method : "GET",
                headers : {
                    "Content-Type" : "application/json"
                },
            });

            const response = await server.json();
            //const response = await server.text();
            
            //console.log(server);
            //console.log(response);

            if( server.status == 200 )
            {
                state = {
                    ...this.state,
                    data : {
                        ...this.state.data,
                        fetched : {
                            ...this.state.data.fetched,
                            profile : response.data                        
                        }
                    }                    
                };
                setTimeout(() => {
                    document.querySelector('#profile_user_name').value = this.state.data.fetched.profile.users_name;
                    document.querySelector('#profile_first_name').value = this.state.data.fetched.profile.first_name;        
                    document.querySelector('#profile_last_name').value = this.state.data.fetched.profile.last_name;        
                    document.querySelector('#profile_goverment_id').value = this.state.data.fetched.profile.users_goverment_id;        
                    document.querySelector('#profile_email').value = this.state.data.fetched.profile.users_email;        
                    document.querySelector('#profile_phone').value = this.state.data.fetched.profile.users_phone;        
                    document.querySelector('#profile_birth_date').value = this.state.data.fetched.profile.birth_date;        
                    document.querySelector('#profile_gender').value = this.state.data.fetched.profile.gender;                
                }, 100);
            }
            else if ( server.status == 401 )
            {
                go_home();
            }
            else if ( server.status == 409 )
            {
                this.actionMessage(server.message,"warning");
                setTimeout(() => {
                    go_home();
                }, 5000);

            }
            else
            {
                go_home();
            }

        } catch (error) {
            console.log(error);
        }
    }
};

getGenders = async () =>
{
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;            
        try {
            
            const server = await fetch( API_CATEGORIES_URL ,{
                method : 'POST',
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    target          : 'gender',
                    session_token   : session_token,
                    user_id         : user_id                         
                }) 
            });
    
            const response = await server.json();
            //const response = await server.text();
    
            //console.log(response);

            if ( server.status == 200 )
            {                                
                state = {       
                    ...this.state,                        
                    genders : response.data,                                     
                };

                setTimeout(() => {
                    this.state.genders.forEach(item => {
                        let option = document.createElement('option');
                            option.setAttribute('key',item.id);
                            option.setAttribute('value',item.id);

                            if (item.id == this.state.data.fetched.profile.gender_id)
                            {                                
                                option.selected = true;
                            }
                            
                            option.innerHTML = item.gender;
                            
                        
                        let profile_gender = document.querySelector('#profile_gender');
                            profile_gender.appendChild(option);
                    });
                }, 300);
            }
            else if ( server.status == 401 )
            {
                go_home();
            }
            else if ( server.status == 409 )
            {
                console.log(response.message);
            }
            else
            {
                console.log("Error 500. Contacte al Administrador.");
            }

        }
        catch (error)
        {
            console.log(error);
        }
    }

};

updateProfile = async () => {

    this.loading();
    
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;
        
        try {
            
            const server = await fetch( API_UPDATE_USER_PROFILE_URL ,{
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'  
                },
                body : JSON.stringify({
                    target              : "user_profile",
                    session_token       : session_token,
                    session_user_id     : user_id,
                    user_id             : this.state.data.fetched.profile.user_id,
                    user_role_id        : this.state.data.fetched.profile.role_id,
                    users_name          : this.state.data.fetched.profile.users_name,
                    first_name          : this.state.data.fetched.profile.first_name,
                    last_name           : this.state.data.fetched.profile.last_name,
                    users_goverment_id  : this.state.data.fetched.profile.users_goverment_id,
                    users_email         : this.state.data.fetched.profile.users_email,
                    users_phone         : this.state.form.users_phone,
                    gender_id           : this.state.form.gender_id,
                    birth_date          : this.state.form.birth_date,                        
                })
            });

            const response = await server.json();
            
            console.log(response);

            this.loading();

            if ( server.status == 200 )
            {

                this.actionMessage(response.message,'warning');

            }
            else if ( server.status == 401 )
            {
                this.actionMessage(response.message,'warning');
                go_home();
            }
            else if ( server.status == 403 )
            {
                this.actionMessage(response.message,'warning');
            }
            else
            {
                this.actionMessage('Error Desconocido contacte al administrador.','warning');
            }


        } catch (error) {
            console.log(error);
        }
    }

}

handleProfileSubmit = () => {
    
    console.log("profile submited");

    state = {
        ...this.state,
        form : {
            ...this.state.form,
            users_phone         : document.querySelector('#profile_phone').value,
            gender_id           : document.querySelector('#profile_gender').value,
            birth_date          : document.querySelector('#profile_birth_date').value,
        }
    }

    setTimeout(() => {
        this.updateProfile();        
    }, 300);

    return false;
}

handlePhoneChange = (e) => {
    
    state = {
        ...this.state,
        form : {
            ...this.state.form,            
            [e.name] : this.formatMask(e.value,"000-000-0000")            
        }        
    };

    document.querySelector('#profile_phone').value = this.formatMask(e.value,"000-000-0000");
}

handleBDChange = (e) => {    

    state = {
        ...this.state,
        form : {
            ...this.state.form,            
            [e.name] : this.formatMask(e.value,"00-00-0000")            
        }        
    };
    
    document.querySelector('#profile_birth_date').value = this.formatMask(e.value,"00-00-0000");
    
}

handleChange = (e) =>
{
    
    state = {
        ...this.state,
        form : {
            ...this.state.form,            
            [e.name] : e.value            
        }        
    };
}

handleAvatarFormSubmit = () =>{
    return false;
}

handleAvatarChange = (e) => {                
    
    if(e.files[0].size !== 'undefined')
    {
        let ext = get_file_extension(e.files[0].name);
        // comprobamos que la imagen cumpla con el filtro
        if( 
            e.value !== "" && e.value !== null && 
            e.files[0].size < 2024000 &&
            ( 
                ( e.files[0].type == "image/jpeg" && (ext == "jpg" || ext == "jpeg" ) ) ||
                ( e.files[0].type == "image/png" && ext == "png" )   
            ) 
        )
        {
            // guardamos los datos en el estado
            this.state = {
                ...this.state,
                form : {
                    ...this.state.form,
                    avatar_file     : e.files[0],
                    file_type       : e.files[0].type,
                    file_name       : e.files[0].name,
                }
            };

            setTimeout(() => {
                
                // remueve el valor del input file
                document.querySelector('#avatar_file').value = "";

                this.uploadProfileAvatar();                
                
            }, 50);
        }
        else if (e.files[0].size > 2024000)
        {
            this.actionMessage("Solo imagenes menores a 2mb.","warning");
        }
        else
        {
            this.actionMessage("Agregue una imagen png o jpg.","warning");
        }
    }

}

uploadProfileAvatar = async () => {
    
    this.triggerSpinner("show");
    // obtenemos la extension del archivo a subir
    let ext = get_file_extension(this.state.form.file_name);

    if (
        ( this.state.form.file_type == "image/jpeg" && (ext == "jpg" || ext == "jpeg" ) ) ||
        ( this.state.form.file_type == "image/png" && ext == "png" )            
    )
    {            
        if(localStorage.user_id !== "" && localStorage.session_token !== "")
        {
            let session_token   = localStorage.session_token;
            let user_id         = localStorage.user_id;

            try {
                
                const   form = new FormData();
                        form.append('target', "upload_avatar");
                        form.append('session_token', session_token);
                        form.append('user_id', user_id);
                        form.append('user_role_id', this.state.data.fetched.role_id);
                        form.append('avatar_file', this.state.form.avatar_file);
                
                const   server = await fetch( API_UPLOAD_PROFILE_AVATAR_URL ,{
                    method : "POST",
                    body: form,
                });
                
                const response = await server.json();
                //const response = await server.text();
                
                //console.log(server);
                //console.log(response);
                
                if ( server.status == 200 )
                {   
                    this.getAvatar();                           
                }
                else if (server.status == 401)
                {
                    console.log(response);
                    go_home();
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
                    go_home();
                }

                this.triggerSpinner();                    

            } catch (error) {

                console.log(error);

                this.triggerSpinner();

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
        this.triggerSpinner();            
    }
    
}

removeAvatar = async () => {

    this.triggerSpinner("show");

    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;

        try {
            
            const server = await fetch( API_AVATAR_URL ,{
                method: "POST",
                headers : {
                    "Content-Type"  : "application/json"
                },
                body : JSON.stringify({
                    target          : "remove_avatar",
                    session_token   : session_token,
                    user_id         : user_id,
                })
            });

            //const response = await server.json();
            //console.log(server);
            //console.log(response);

            switch ( server.status ) {
                case 200:
                    
                    state = {
                        ...this.state,
                        data : {
                            ...this.state.data,
                            fetched : {
                                ...this.state.data.fetched,
                                avatar_file_name: "",
                            }
                        }
                    };

                    setTimeout(() => { 
                        this.getAvatar();                       
                    }, 300);
                    break;
                case 206:
                    
                    break;
                case 400:
                    
                    break;
                case 401:
                    console.log("No tiene autorizacion");
                    go_home();  
                    break;
                case 409:
                    console.log("Error. Contacte al administrador.");
                    break;
                default:
                    console.log("Error. Contacte al administrador.");
                    go_home();                       
                    break;
            }

        this.triggerSpinner();

        } catch (error) {
            console.log(error);
        }
    }
    else
    {
        go_home();
    }
}

window.addEventListener('load', getProfileInfo );    
window.addEventListener('load', getGenders );        