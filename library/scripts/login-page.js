    state = {
        form : {
            submited : false,
            Login__password : "",
            Login__userName : "",
            btn_disabled : true            
        },
        data : {
            param : {
                loading : true,
            }            
        }
    };
        
    /** 
     * TO_DO
     * Muestra un mensaje de fallo de inicio de sesion
     * codigo obsoleto usar show_notification_message()
     */
    loginValidationMessage = (text_msg,type) =>
    {
        let message = document.createElement('span');
            message.setAttribute('class','message');
            message.innerHTML = text_msg;
        
        let message_box = document.getElementById("message-box");

        if (message_box.childElementCount > 0)
        {
            for (let u = 0; u < message_box.children.length; u++) {
                message_box.children[u].remove();                
            }
        }
        
            message_box.appendChild(message);
        
        let child = document.querySelector("#message-box > span");
        
        switch (type)
        {
            case "warning":        
                setTimeout(() => {
                    child.setAttribute('class','warning-message slow-show');            
                    setTimeout(() => {
                        child.setAttribute('class','warning-message');                
                    }, 3000);
                }, 100);
                break;
            case "recovery":
                setTimeout(() => {
                    child.setAttribute('class','recovery-message slow-show');            
                    setTimeout(() => {
                        child.setAttribute('class','recovery-message');                
                    }, 3000);
                }, 100);
                break;
            case "password":
                    setTimeout(() => {
                        child.setAttribute('class','password-message slow-show');            
                        setTimeout(() => {
                            child.setAttribute('class','password-message');                
                        }, 3000);
                    }, 100);
                    break;
            default:
                break;
        }        

    }

    getLoginData = async () =>    
    {

        console.log("getLoginData");
        
        if (this.state.data.param.loading == false)
        {
            this.loading();
        }

        try {
            
            const server = await fetch( API_SIGNIN_LOGIN_URL ,{
                method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    login_user      : this.state.form.Login__userName ?? "", 
                    login_password  : this.state.form.Login__password ?? ""
                })              
            });
            
            //const response = await server.text();
            const response = await server.json();

            //console.log(response);
            // verifica si el estado del servidor es 200
            if ( server.status == 200 && response.status == "200" )            
            {
                // compruebe el nombre de usuario suminsitrado con el de la base de datos.
                // Tomese en cuenta que no se verifica el password, eso lo hace el servidor
                // ver signup.php
                if (                       
                    ( response.data.users_name == this.state.form.Login__userName )         ||
                    ( response.data.users_email == this.state.form.Login__userName )        ||
                    ( response.data.users_goverment_id == this.state.form.Login__userName )
                )
                { 
                    this.state = {
                        ...this.state,
                        form : {
                            ...this.state.form,
                            submited : true,
                            Login__userName : "",
                            Login__password : ""
                        },
                        data : {
                            ...this.state.data,
                            fetched : response.data,
                            param : {
                                ...this.state.data.param,
                                session_started : true,                            
                            }
                        }
                    };
                    
                    setTimeout(() => {                                
                        localStorage.session_token  = this.state.data.fetched.session_token;
                        localStorage.user_id        = this.state.data.fetched.user_id;
        
                        if (this.state.data.fetched.account_confirmed == "1") // eliminar true
                        {
                            // verifica el estado para confirmar los datos
                            if( this.state.data.param.session_started == true )
                            {
                                console.log("login_signin...");            
                                
                                // convertimos la data a string y guardamos
                                localStorage.state = JSON.stringify(this.state);
                                localStorage.target = "url";
                                
                                // url a enviar
                                let url = URL_BASE + '/app'; 
                                
            
                                if (this.state.data.param.loading == true)
                                {
                                    this.loading();
                                }                            
                                // redirecciona a la url indicada
                                // window.location.href = url;
                                // envia los datos por post a la url indicada
                                let params = JSON.parse(localStorage.state).data.fetched;
                                setTimeout(() => {
                                    post(url,params);                                    
                                }, 500);
                            }
                            else
                            {
                                if (this.state.data.param.loading == true)
                                {
                                    this.loading();
                                }
            
                                show_notification_message("Usuario y/o contraseña incorrectos.","warning");
                            }
                        }
                        else
                        {
                            if (this.state.data.param.loading == true)
                            {
                                this.loading();
                            }
        
                            show_notification_message("Debe confirmar su correo electrónico para iniciar sesión.","warning");
                        }
                    }, 50);
                }
                            
            }            
            else if ( server.status == 403 )
            {
                console.log("server status: " + server.status + " - No Content");            
                show_notification_message("usuario y/o contraseña incorrectos.","warning");
            }
            else if ( server.status == 409 || server.status == 503 )
            {
                show_notification_message("No pudo contactar al servidor. Contacte a su administrador.","warning");
            }
            else
            {   
                console.log("server status: " + server.status + " - No Content");            
                show_notification_message("usuario y/o contraseña incorrectos.","warning");
                
                this.state = {
                    ...this.state,
                    data : {
                        ...this.state.data,
                        fetched : response.data,
                        param : {
                            ...this.state.data.param,
                            session_started : false,                            
                        }
                    }
                }; 
            }

            if (this.state.data.param.loading == true)
            {
                this.loading();
            }

        } catch (error) {

            console.log(error); 

            if (this.state.data.param.loading == true)
            {
                this.loading();
            }

            console.log(error.message);

            if(error.message == 'Failed to fetch')
            {
                show_notification_message("El servidor no pudo ser contactado. Contacte a su administrador de sistemas.","warning");
            }
            else
            {
                show_notification_message("El servicio no esta disponible. Contacte a su administrador de sistemas.","warning");
            }

        }

        return false;
    
    }

    handle_login_submit = () =>
    {        
        return false;
    }

    handle_login_click_button = () =>
    {        
        console.log("boton presionado");
        getLoginData();
    } 

    handle_login_form_change = (e) =>
    {   
        this.state = {
            ...this.state,
            form : {
                ...this.state.form,       
                [e.name] : e.value
            }     
        }
        
        
        setTimeout(() => {
            // verifica el los inputs cumplen la condicion y lo habilita o deshabilita
            if (this.state.form.Login__userName.length > 2 && this.state.form.Login__password.length > 2)
            {
                this.state = {
                    ...this.state,
                    form : {
                        ...this.state.form,
                        btn_disabled : false
                    }
                }
            }
            else
            {
                this.state = {
                    ...this.state,
                    form : {
                        ...this.state.form,
                        btn_disabled : true
                    }
                }
            }
            
            enable_submit_button();

        }, 100);

    }    

    enable_submit_button = () =>{
        
        let login_submit_button = document.querySelector("#submit_login_button");
            login_submit_button.disabled = this.state.form.btn_disabled;

    }

    handleClick = () =>
    {
        console.log("boton clickeado");
    } 

    // Al cargar toda la pagina detener spinner del loading
    window.addEventListener('DOMContentLoaded', loading );