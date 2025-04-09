this.state = {        
    data : {
        param : {
            loading : false
        },
    },
    form : {
        LoginChangePassword__newPassword  : "",
        LoginChangePassword__newPassword2 : "",
        security_token  : "",
        user_email      : "",
        input_disabled  : false
    }
}

// muestra un mensaje de fallo de inicio de sesion
actionMessage = (text_msg,type) =>
{
    let child = document.querySelector("#message-box > span");

    if ( child == null )
    {
        let span_message = document.createElement('span');
            span_message.setAttribute('class','message');
            span_message.innerHTML = text_msg;
        
        document.getElementById("message-box").appendChild(span_message);
    }
    else
    {
        child.innerHTML = text_msg;
    }

    child = document.querySelector("#message-box > span");
    
    switch (type)
    {
        case "warning":        
            setTimeout(() => {
                child.setAttribute('class','warning-message slow-show');            
                setTimeout(() => {
                    child.setAttribute('class','warning-message');                
                }, 10000);
            }, 100);
            break;
        case "recovery":
            setTimeout(() => {
                child.setAttribute('class','recovery-message slow-show');            
                setTimeout(() => {
                    child.setAttribute('class','recovery-message');                
                }, 10000);
            }, 100);
            break;
        case "password":
                setTimeout(() => {
                    child.setAttribute('class','password-message slow-show');            
                    setTimeout(() => {
                        child.setAttribute('class','password-message');                
                    }, 10000);
                }, 100);
                break;
        default:
            break;
    }        

}

changePassword = async () =>     
{   
    // carga el loading...     
    this.loading();

    try
    {    
        const server = await fetch( API_CHANGE_PASSWORD_URL ,{
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({
                target : "update_password",
                new_password: this.state.form.LoginChangePassword__newPassword,    
                security_token: this.state.form.security_token,
                user_email: this.state.form.user_email
            })                                          
        });

        const response = await server.text();

        //console.log(server);
        //console.log(response);
        
        if(server.status == 200)
        {
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

            this.loading();

            this.actionMessage("La contraseña fue cambiada correctamente.","password");

            setTimeout(() => {                    
                go_home();
            }, 3000);
        }
        else if (server.status == 401)
        {
            console.log(response.message);
            go_home();
        }
        else if(server.status == 409)
        {
            this.actionMessage(response.message,"password");
        }
        else
        {
            this.actionMessage("El servicio no esta diponible, intente más tarde.","password");
            console.log("Error 500. contacte al administrador");
        }
        
    }
    catch (error)
    {
        this.loading();

        this.actionMessage("El servicio no esta diponible, intente más tarde.","password");
        console.log(error);
    }
}

getDataFromURL = () => {
    
    const url = window.location.href;        
    const arrayUrl = url.split("-");

    if (arrayUrl[1] !== null && arrayUrl[2] !== null ) 
    {
        this.security_token = arrayUrl[1];
        this.user_email = arrayUrl[2];

        this.state = {
            ...this.state,
            form:{
                ...this.state.form,
                security_token  : this.security_token,
                user_email      : this.user_email
            }
        };
    }

}

componentDidMount = () => {

    this.loading();
    
    this.getDataFromURL();

    setTimeout(() => {
        this.validateSecurityToken();
        this.inputToggleDisabled();
    }, 600);

}; window.addEventListener('DOMContentLoaded', componentDidMount );

handleChangePassSubmit = () => {
    return false;
}

handleChangePassBtnClick = () => {

    if(
            this.state.form.LoginChangePassword__newPassword !== null && 
            this.state.form.LoginChangePassword__newPassword2 !== null && 
            this.state.form.LoginChangePassword__newPassword !== "" && 
            this.state.form.LoginChangePassword__newPassword2 !== "" && 
            this.state.form.LoginChangePassword__newPassword.length > 7 &&
            this.state.form.LoginChangePassword__newPassword2.length > 7 &&
        (this.state.form.LoginChangePassword__newPassword == this.state.form.LoginChangePassword__newPassword2)
        
    )
    {
        this.changePassword();
    }
    else
    {
        this.actionMessage("La contraseña no cumple con los parametros de seguridad.","password");
    }    
}

handleInputChangePassChange = (e) => {
    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            [e.name] : e.value
        }
    }        
    document.getElementById([e.name]).value = [e.value];
}

inputToggleDisabled = () => {

    let inputs = document.querySelectorAll('#LoginChangePassword__form input');

    inputs.forEach( item => {

        if ( item.disabled == true)
        {
            item.disabled = false;
        }
        else
        {
            item.disabled = true;
        }

    });
}

validateSecurityToken = async () => {
    
    try {            
        const server = await fetch( API_VALIDATE_SECURITY_TOKEN_URL ,{
            method: "post",
            headers: {                                
                'Content-Type': 'application/json'       
            }, 
            body: JSON.stringify({                             
                security_token : this.state.form.security_token,
                user_email : this.state.form.user_email
            })                         
        });

        //const response = await server.json();            
                
        //console.log(server);
        //console.log(response);

        // verifica si es el estado es OK
        if(server.status !== 200 )
        {
            go_home();
        }

    } catch (error) {
        console.log(error.message);
        go_home();
    }
}