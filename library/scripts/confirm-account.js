state = {
    form: {                
        security_token  : "",
        user_email      : "",                
    }
};         

getConfirmData = () => {

    const url = window.location.href;        
    const arrayUrl = url.split("-");

    if (arrayUrl[1] !== null && arrayUrl[2] !== null ) 
    {
        this.security_token = arrayUrl[1];
        this.user_email = arrayUrl[2];

        this.state = {
            form:{
                ...this.state.form,
                security_token : this.security_token,
                user_email : this.user_email
            }
        };

        setTimeout(() => {
            this.confirmAccount();
        }, 300);
    }
    else
    {
        go_home();
    }
}; window.addEventListener('DOMContentLoaded', getConfirmData );

confirmAccount = async () => {

    try {            
        const server = await fetch( API_CONFIRM_ACCOUNT_URL ,{
            method: "post",
            headers: {                                
                'Content-Type': 'application/json'       
            }, 
            body: JSON.stringify({                             
                security_token : this.state.form.security_token,
                user_email : this.state.form.user_email
            })                         
        });
        
        const response = await server.json();

        console.log(server);
        console.log(response);
        
        // verifica si es un objeto vacio
        if( server.status == 200 )
        {                
            document.querySelector('#Main__wrapper').classList.toggle('no-show');

            setTimeout(() => {
                go_home();
            }, 5000);

        }
        else if ( server.status == 204 )
        {
            not_found();
        }
        else if ( server.status == 401 )
        {
            document.write("Datos invalidos...");
            setTimeout(() => {
                go_home();
            }, 3000);
        }
        else if ( server.status == 409 )
        {
            not_found();
        }
        else if ( server.status == 503 )
        {
            document.write("Error al contactar servidor. Contacte al administrador de sistemas.");                
            setTimeout(() => {
                go_home();
            }, 10000);
        }

    } catch (error) {
        console.log(error);
        go_home();
    }
}