<script>
    
    this.state = {
        ...this.state,
        form: {
            LoginRestore__email: "",
        }
    };
    
    cleanRecoveryForm = () => {
        let LoginRestore__email = document.getElementById('LoginRestore__email');

        if ( LoginRestore__email !== null) {
            LoginRestore__email.value = "";
        }

    }

    const sendMailForRestorePassword = async () => {

        // carga el loading...     
        this.loading();

        try {
            
            const url = API_INDEX_URL;

            let options = {
                method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    user_email: this.state.form.LoginRestore__email,
                    target: "login-restore_password"                    
                }) 
            }

            const server = await fetch(url,options);

            const response = await server.json();

            this.loading();

            switch (server.status) {

                case 200:
                    
                    show_notification_message(response.message,"warning");
                    this.cleanRecoveryForm();

                    this.state = {
                        ...this.state,
                        form:{
                            LoginRestore__email: "",
                        }
                    };

                    break;                
                default:
                    show_notification_message(response.message,"error");
                    break;
            }

        } catch (error) {
            console.log(error);
            this.loading();

            if (error.message === "Failed to fetch")
            {
                show_notification_message("No se pudo conectar al servidor. Contacte a su administrador de sistemas.","error");
            }
            else
            {
                show_notification_message("El servicio no esta disponible en este momento. Contacte a su administrador de sistemas.","error");
            }
        }
    }

    handleRecoveryClick = () => 
    { 
        
        if(
            this.state.form.LoginRestore__email !== null && 
            this.state.form.LoginRestore__email !== "" && 
            this.state.form.LoginRestore__email.length > 2 &&
            /^[a-zA-Z0-9\.]+@+[a-zA-Z0-9]+.+[A-z]/i.test(this.state.form.LoginRestore__email)
        )
        {
            sendMailForRestorePassword();
        }
        else
        {
            show_notification_message("Escriba un correo electronico válido.","warning");
        }        
    }

    handleRecoveryChange = (e) =>
    {
        
        this.state ={
            ...this.state,
            form: {
                ...this.state.form,
                [e.name]: e.value
            }            
        };
    }

    handleRecoverySubmit = () =>
    {        
        return false;
    }
</script>
<div class="LoginRecovery__box">                                     
    <form onsubmit="return handleRecoverySubmit()" class="LoginRecovery__body LoginRecovery__form" action="" method="post">
        <label class="LoginRecovery__formUserInput" for="LoginRestore__email">
            <span>Correo electrónico</span>
            <div class="LoginRecovery__user_group_wrap">
                <span class="icon-user"><i class="far fa-envelope"></i></span>
                <input 
                    onkeyup="handleRecoveryChange(this)" 
                    onchange="handleRecoveryChange(this)"
                    class="form-control LoginRestore__email" 
                    type="text" 
                    id="LoginRestore__email" 
                    name="LoginRestore__email" 
                    value="" 
                    placeholder="Ingrese su Correo Electrónico" 
                    maxlength="50"
                />
            </div>
        </label>                    
        <button onclick="handleRecoveryClick(this)" class="btn btn-secondary LoginRecovery_button" type="button">Recuperar</button>                                                    
    </form>                                                                    
    <div class="LoginRecovery__footer">
        <p><a href="<?php echo URL_BASE.'/login'; ?>">Iniciar Sesión</Link></p>
    </div>          
</div>   