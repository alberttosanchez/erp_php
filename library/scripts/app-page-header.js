
this.state = {
    ...this.state,
    data : {        
        ...this.state.data,
        fetched : {
            ...this.state.data.fetched,
            avatar_file_name : "",
        }
    }
}

showAdminMenu = () =>{
    
    if ( this.state.data.fetched.role_id == "1" && 
         this.state.data.fetched.role_super == "1"
    )
    {

        let main_menu_list_item = document.querySelector(".main_menu > ul > li:first-child");

        let list_item = document.createElement('li');

        let anchor = document.createElement('a');
            anchor.setAttribute('href', URL_BASE + '/manage');
        
        let span_one = document.createElement('span');
            span_one.innerHTML = 'Administrar';

        let span_two = document.createElement('span');
            span_two.setAttribute('class','tool-icon');

        let icon = document.createElement('i');
            icon.setAttribute('class','fa fa-tools');

            span_two.appendChild(icon);

            anchor.appendChild(span_two);
            anchor.appendChild(span_one);

            list_item.appendChild(anchor);

            main_menu_list_item.parentNode.insertBefore(list_item,main_menu_list_item.nextSibling);
    }
};

showUserNameInHeader = () => {
    
    let user_span = document.querySelector(".user_name");

    if (typeof this.state.data.fetched.users_name !== "undefined" &&
        this.state.data.fetched.users_name !== "" &&
        this.state.data.fetched.user_id !== ""
    )
    {
        user_span.innerHTML = this.state.data.fetched.users_name;
    }
    else
    {
        user_span.innerHTML = "Guess";
    }

};

showAvatarInHeader = () =>{
  
    let avatar_icon = document.querySelector('.avatar_icon');
    let avatar_picture = document.querySelector('.avatar_picture');
    
    if (typeof this.state.data.fetched.avatar_file_name != "undefined" &&
        this.state.data.fetched.avatar_file_name != "" &&
        this.state.data.fetched.user_id != ""
    )
    {
        let avatar_path = "/"+this.state.data.fetched.user_id+"/avatar/"+this.state.data.fetched.avatar_file_name+"?version="+Date.now();
        let avatar = PROFILE_USERS_URL + avatar_path;
    
        if ( avatar_icon !== null && avatar_picture == null )
        {
            avatar_icon.remove();

            setTimeout(() => {
                
                let img = document.createElement('img');
                    img.setAttribute('src',avatar);
                    img.setAttribute('alt','profile');
                    img.setAttribute('width','100%');
                    img.setAttribute('height','100%');
        
                let span = document.createElement('span');
                    span.setAttribute('class','avatar_picture');
        
                    span.appendChild(img);
        
                let menu_button = document.querySelector(".menu_button");
                    menu_button.appendChild(span);
    
            }, 300);

        }
        else if ( avatar_icon !== null && avatar_picture !== null )
        {
            avatar_icon.remove();

            let avatar_img = document.querySelector('.avatar_picture > img');
                avatar_img.setAttribute('src',avatar);
        }
        else if ( avatar_picture !== null )
        {            
            let avatar_img = document.querySelector('.avatar_picture > img');
                avatar_img.setAttribute('src',avatar);
        }
        else if ( avatar_picture == null )
        {
            setTimeout(() => {
                
                let img = document.createElement('img');
                    img.setAttribute('src',avatar);
                    img.setAttribute('alt','profile');
                    img.setAttribute('width','100%');
                    img.setAttribute('height','100%');
        
                let span = document.createElement('span');
                    span.setAttribute('class','avatar_picture');
        
                    span.appendChild(img);
        
                let menu_button = document.querySelector(".menu_button");
                    menu_button.appendChild(span);
    
            }, 300);
        }
                    
    }
    else
    {   
        
        if ( avatar_icon !== null )
        {
            avatar_icon.remove();
        }
        else if ( avatar_picture !== null )
        {
            avatar_picture.remove();
        }

        setTimeout(() => {
            
            let i = document.createElement('i');
                i.setAttribute('class','fa fa-user');                
    
            let span = document.createElement('span');
                span.setAttribute('class','avatar_icon');
    
                span.appendChild(i);
                
            let menu_button = document.querySelector(".menu_button");
                menu_button.appendChild(span);

        }, 300);
    }

    
};

toggleMenu = () => {    
    let main_menu = document.querySelector('#main_menu');
        main_menu.classList.toggle('no-show');
};

// ver app-page.js
window.addEventListener('DOMContentLoaded', getAvatar );
window.addEventListener('DOMContentLoaded', showUserNameInHeader );
window.addEventListener('load', showAdminMenu );