
if ( typeof localStorage.state != 'undefined' )
{
    state = JSON.parse(localStorage.state);
}
else
{
    state = "";
}


clearSelectionInSideBar = () => {
    let menu_items = document.querySelectorAll('.left_sidebar .menu-list button');

    menu_items.forEach(item => {
        
        item.style.color = "white";
        
    });
}

show_info = (e) => {

    clearSelectionInSideBar();
    
    if (typeof e == 'object') e.style.color = 'red';
    
    let elements = document.querySelectorAll('.content');

    elements.forEach(item => {
        
        item.style.display = "none";
        
    });

    //para permitir enviar una cadena como parametro.
    if (typeof e == "string") {  e = { value : e }; }
    
        
    let content = document.querySelector('#'+e.value);    
        content.style.setProperty("display", "block", "important");
    
    
}; 

expand_or_contract_sidebar = () =>{

    let sidebar_wrapper = document.querySelector('.MenuLeftSidebar__wrapper');            
        sidebar_wrapper.classList.toggle('contract');

    let sidebar_menu_button = document.querySelectorAll('.sidebar-menu-button');

    for (let i = 0; i < sidebar_menu_button.length; i++) {            
        sidebar_menu_button[i].classList.toggle('no-show');
    }
    setTimeout(() => {
        let menu_title = document.querySelector(".top_sidebar_wrap > h2");
            menu_title.classList.toggle('no-show');
    }, 300);
}

expand_or_contract_sub_menu_in_sidebar = (e) =>{
    
    let sidebar_sub_menu_list = e.nextElementSibling.firstElementChild;
        sidebar_sub_menu_list.classList.toggle('contract');
        // rota el pseudo elemento que indica existencia de submenu
        e.parentElement.classList.toggle('rotate');
}

getAvatar = async () => {
    
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
                    target          : "get_avatar",
                    session_token   : session_token,
                    user_id         : user_id,
                })
            });

            const response = await server.json();
            //const response = await server.text();

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
                                avatar_file_name: response.data,
                            }
                        }
                    };

                    setTimeout(() => {
                        this.showAvatarInHeader();
                        this.showAvatarInForm();                        
                    }, 300);

                    break;
                case 206:
                    
                    setTimeout(() => {
                        this.showAvatarInHeader();                                                
                        this.showAvatarInForm();
                    }, 300);

                    break;
                case 400:
                    close_session();
                    break;
                case 401:
                    console.log("No tiene autorizacion");
                    close_session();
                    break;
                case 409:
                    console.log("Error. Contacte al administrador.");
                    close_session();
                    break;
                default:
                    console.log("Error. Contacte al administrador.");
                    close_session();
                    break;
            }

            
            
        } catch (error) {
            console.log(error);
        }
    }
    else
    {
        close_session();
    }
    
}; 

showAvatarInForm = () =>{
    
    setTimeout(() => {
        
        let avatar_icon = document.querySelector("#avatar_icon_form");
        let avatar_box = document.querySelector("#avatar_box");
        let avatar_span_img = document.querySelector('.avatar_span_img');

        if (typeof this.state.data.fetched.avatar_file_name != "undefined" &&
        this.state.data.fetched.avatar_file_name != "" &&
        this.state.data.fetched.user_id != ""
        )
        {                                
            
            let avatar_path = "/"+this.state.data.fetched.user_id+"/avatar/"+this.state.data.fetched.avatar_file_name+"?version="+Date.now();
            let avatar = PROFILE_USERS_URL + avatar_path;
        
            if ( avatar_span_img )
            {
                avatar_span_img.remove();
            }
            
            if ( avatar_icon )
            {
                avatar_icon.remove();
            }            
            
            if ( avatar_box )
            {
                
                setTimeout(() => {
                    
                    let img = document.createElement('img');                
                        img.setAttribute('src',avatar);
                        img.setAttribute('src',avatar);
                        img.setAttribute('alt','profile');
                        img.setAttribute('width','100%');
                        img.setAttribute('height','100%');
            
                    let span = document.createElement('span');
                        span.setAttribute('class','avatar_span_img');
                        span.appendChild(img);
                    
                        avatar_box.appendChild(span);
                    
    
                }, 300);

            }
        }
        else
        {
            if ( avatar_span_img )
            {
                avatar_span_img.remove();                
            }

            if ( ! avatar_icon && avatar_box )
            { 
                
                let i = document.createElement('i');
                    i.setAttribute('class','fa fa-user');                
        
                let span = document.createElement('span');
                    span.setAttribute('id','avatar_icon_form');
                    span.setAttribute('class','avatar_icon_form');
        
                    span.appendChild(i);
                                    
                    avatar_box.appendChild(span);                
                
            }
            
        }

    }, 300);
};

// muestra un mensaje de fallo de inicio de sesion
actionMessage = (text_msg,type) =>
{
    let child = document.querySelector("#users_message > span");

    if ( child == null )
    {
        let span_message = document.createElement('span');
            span_message.setAttribute('class','message');
            span_message.innerHTML = text_msg;
        
        document.getElementById("users_message").appendChild(span_message);
    }
    else
    {
        child.innerHTML = text_msg;
    }

    child = document.querySelector("#users_message > span");
    
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
// ver functions.js
window.addEventListener('DOMContentLoaded', loading );