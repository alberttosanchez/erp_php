this.state = {
    ...this.state,
    data : {
        ...this.state.data,
        fetched : {
            ...this.state.data.fetched,
            queryResult : [],
            filters : [],
        }
    },
    form: {
        ...this.state.form,
        filter_selected             : "",
        keyword                     : "",
        actionViewButton            : "",
        actionEditButton            : "",
        actionDeleteButton          : "",
        genders                     : [],
        roles                       : [],
        confirmWarning              : "",            
        newUsers_users_name         : "",
        newUsers_first_name         : "",
        newUsers_last_name          : "",
        newUsers_users_goverment_id : "",
        newUsers_users_email        : "",
        newUsers_users_phone        : "",
        newUsers_gender             : "", 
        btn_disabled                : false               
    },                
    userDetails : {
        ...this.state.userDetails,
        users_role          : "",
        users_name          : "",
        first_name          : "",
        last_name           : "",
        users_goverment_id  : "",
        users_email         : "",
        users_phone         : "",
        gender              : "",
        gender_id           : "",
        birth_date          : "",
        account_confirmed   : "0",        
    },
    pagination : {
        ...this.state.pagination,
        counter         : "",
        selected_page   : "",                
    }
};

cleanUsersTable = () => {
    let elements = document.querySelectorAll('#settingUsers_table tbody > tr');
    for (let i = 0; i < elements.length; i++) {
        elements[i].remove();                
    }
}

cleanUserDetailsForm = () => {

    let inputs = document.querySelectorAll('.details_container input');

        for (let i = 0; i < inputs.length; i++) {
            inputs[i].value = "";
            inputs[i].innerHTML = "";
        }
    
    let details_rol = document.querySelectorAll('#details_rol > option');
        
        for (let i = 0; i < details_rol.length; i++) {                        
            details_rol[i].remove();                        
        }

    let details_gender = document.querySelectorAll('#details_gender > option');

        for (let i = 0; i < details_gender.length; i++) {                        
            details_gender[i].remove();                        
        }
}

cleanNewUserForm = () => {
    let inputs = document.querySelectorAll('.newUsers__details input');

        for (let i = 0; i < inputs.length; i++) {
            inputs[i].value = "";
            inputs[i].innerHTML = "";
        }
    
    let new_user_rol_gender = document.querySelectorAll('#newUsers_gender > option');

        for (let i = 0; i < new_user_rol_gender.length; i++) {                        
            new_user_rol_gender[i].remove();                        
        }
    
    this.state.form.newUsers_users_name = "";
    this.state.form.newUsers_first_name = "";
    this.state.form.newUsers_last_name = "";
    this.state.form.newUsers_users_goverment_id = "";
    this.state.form.newUsers_gender = "";
    this.state.form.newUsers_users_email = "";
    this.state.form.newUsers_users_phone = "";
}

deleteUser = async () => {

    this.loading();

    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;

        try {
            
            const server = await fetch( API_DELETE_USER_URL ,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body: JSON.stringify({
                    session_token       : session_token,
                    user_id             : user_id,
                    user_to_delete_id   : this.state.form.actionDeleteButton
                })
            });

            const response = await server.json();

            //console.log(server);
            //console.log(response);

            switch (server.status) {
                case 200:
                    // Acepted
                    this.actionMessage(response.message,'warning');

                    this.state = {
                        ...this.state,
                        queryResult : []
                    };
                    
                    this.cleanUserDetailsForm();

                    break;
                case 401:
                    // Unauthorized                    
                    this.actionMessage(response.message,'warning');
                    this.sessionDestroy();
                    //this.destroyCookie();
                    setTimeout(() => {
                        go_home();
                    }, 100);
                    break;
                case 406:
                    // Not Acceptable
                    this.actionMessage(response.message,'warning');
                    break;
                case 409:
                    // Conflict
                    this.actionMessage(response.message,'warning');
                    break;
                case 503:
                    // Service Unavailable
                    this.actionMessage(response.message,'warning');                    
                    break;
                default:
                    go_home();
                    break;
            }
            
            this.loading();

        } catch (error) {

            console.log(error);
            
            this.loading();
        }
    }

}

filters = () => {

    let filter_selected = document.querySelector('#filter_selected');

    this.state.data.fetched.filters.forEach(item => {
                    
        let option = document.createElement('option');
            option.setAttribute('key',item.id);
            option.setAttribute('value',item.db_field);
            option.innerHTML = item.search_filter;

            filter_selected.appendChild(option);

    });
};

getQueryFilters = async () =>
{
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token = localStorage.session_token;
        let user_id = localStorage.user_id;

        try {
            
            const server = await fetch( API_CATEGORIES_URL ,{
                method : 'POST',
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({                                 
                    target          : 'query_filters',
                    session_token   : session_token,
                    user_id         : user_id                    
                }) 
            });
    
            const response = await server.json();
            //const response = await server.text();
    
            //console.log(server);
            //console.log(response);

            if ( server.status == 200 )
            {                                
                this.state ={                    
                    ...this.state,   
                    data : {
                        ...this.state.data,
                        fetched : {
                            ...this.state.data.fetched,
                            filters : response.data,
                        }
                    }                                     
                };

                setTimeout(() => {
                    this.filters();                        
                }, 300);
            }
            else if (server.status == 409)
            {
                console.log(response.message);
                
            }
            else if (server.status == 401)
            {
                go_home();
            }
            else {
                console.log("Error 500. Contacte al administrador.");                
            }

        } catch (error) {
            console.log(error);
        }
    }

}; window.addEventListener('DOMContentLoaded', getQueryFilters );

getUsersInfo = async () =>
{
    this.loading();
    //this.debuger();
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;

        console.log(this.state.form.keyword);
        try {
            
            const server = await fetch( API_SETTING_USER_INFO_URL ,{
                method:'POST',
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({                    
                    db_field        : this.state.form.filter_selected,
                    keyword         : this.state.form.keyword,
                    selected_page   : this.state.pagination.selected_page,
                    action_view     : this.state.form.actionViewButton,
                    action_edit     : this.state.form.actionEditButton,
                    action_delete   : this.state.form.actionDeleteButton,
                    session_token   : session_token,
                    user_id         : user_id
                }) 
            });
            
            const response = await server.json();                
            //const response = await server.text();                

            console.log(server);                
            console.log(response);
            
            if ( 
                server.status == 200 && 
                typeof response.opc == "undefined"
            )
            { 
                //console.log(typeof response.result == "undefined");
                console.log(response.data);
                
                this.state = {
                    ...this.state,
                    form : {
                        ...this.state.form,
                        actionViewButton : "",
                        actionEditButton : "",
                    },
                    data : {
                        ...this.state.data,
                        fetched : {
                            ...this.state.data.fetched,
                            queryResult : response.data,
                        }
                    }, 
                    pagination  : {                         
                        ...response.pagination,
                    }
                };

                setTimeout(() => {
                    
                    if ( Object.getOwnPropertyNames(this.state.data.fetched.queryResult).length > 1 )    
                    {   
                        this.queryResult();
                        setTimeout(() => {
                            this.renderPagination();                                                            
                        }, 100);
                    }

                }, 300);
        
            }                
            else if (                                
                server.status == 200 && response.opc == "view"
            )
            {
                
                this.state = {
                    ...this.state,
                    form : {
                        ...this.state.form,
                        actionViewButton : "",
                        actionEditButton : "",
                        disabled : true,
                        user_id             : response.data.user_id,
                        role_id             : response.data.role_id,
                        role_name           : response.data.role_name,
                        users_name          : response.data.users_name,
                        first_name          : response.data.first_name,
                        last_name           : response.data.last_name,
                        users_goverment_id  : response.data.users_goverment_id,
                        users_email         : response.data.users_email,
                        users_phone         : response.data.users_phone,
                        gender              : response.data.gender,
                        gender_id           : response.data.gender_id,
                        birth_date          : response.data.birth_date,
                    },                    
                    userDetails : response.data,
                };

                setTimeout(() => {
                    this.renderUserDetailsForm();
                }, 300);
            }
            else if (                                
                server.status == 200 && response.opc == "edit"
            )
            {
                this.state = {
                    ...this.state,
                    form : {
                        ...this.state.form,
                        actionViewButton : "",
                        actionEditButton : "",
                        disabled : false,
                        user_id             : response.data.user_id,
                        role_id             : response.data.role_id,
                        role_name           : response.data.role_name,
                        users_name          : response.data.users_name,
                        first_name          : response.data.first_name,
                        last_name           : response.data.last_name,
                        users_goverment_id  : response.data.users_goverment_id,
                        users_email         : response.data.users_email,
                        users_phone         : response.data.users_phone,
                        gender              : response.data.gender,
                        gender_id           : response.data.gender_id,
                        birth_date          : response.data.birth_date,

                    },         
                    userDetails : response.data,
                };

                setTimeout(() => {
                    this.renderUserDetailsForm();
                }, 300);

            }
            else if (                                
                server.status == 200 && response.opc == "delete"
            )
            {    
                // el archivo usuario fue eliminado.

                this.state = {
                    ...this.state,
                    queryResult : false,
                };
            }
            // si no esta autorizado te saca del sistema
            else if ( server.status == 206 )
            {
                this.actionMessage(response.message,'warning');
                
                this.state = {
                    ...this.state,
                    queryResult : false,
                };

                go_home();
            }
            // si no esta autorizado te saca del sistema
            else if (
                server.status == 401 
            )
            {
                go_home();
            }                    

            this.loading();

        } catch (error) {
            
            console.log(error);

            this.loading();
        }
    }

}

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
    
            //console.log(response);

            if ( server.status == 200 )
            {                                
                this.state = {       
                    ...this.state,
                    data : {
                        ...this.state.data,                    
                        fetched : {
                            ...this.state.data.fetched,
                            genders : response.data,
                        }
                    }             
                };
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

getRol = async () =>
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
                    target          : 'role',
                    session_token   : session_token,
                    user_id         : user_id                         
                }) 
            });
    
            const response = await server.json();
    
            console.log(response);

            if ( server.status == 200 )
            {                                
                this.state = {       
                    ...this.state,
                    form : {
                        ...this.state.form,                    
                        roles : response.data,
                    }             
                };
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

handleManageUsersClick = (e) => {
    
    this.state = {
        ...this.state,
        form : {                
            ...this.state.form,                
            actionViewButton    : "",
            actionEditButton    : "",                              
            [e.name]     : e.value,
        }
    };
    
    setTimeout(() => {
        if( this.state.form.actionViewButton.length > 0 ||
            this.state.form.actionEditButton.length > 0 ||
            this.state.form.actionDeleteButton.length > 0
        )
        {                
            console.log("view: "+this.state.form.actionViewButton);
            console.log("edit: "+this.state.form.actionEditButton);             
            console.log("delete: "+this.state.form.actionDeleteButton);

            if (this.state.form.confirmWarning == "true")
            {
                console.log('confirmWarning: true');
                this.deleteUser();    
                this.warningModal(); 
                this.state = {
                    ...this.state,
                    form: {
                        ...this.state.form,
                        confirmWarning: "",
                        actionDeleteButton: "",
                    }
                };
                setTimeout(() => {
                    this.cleanUsersTable();
                    this.cleanPagination();
                    this.getUsersInfo();                        
                }, 300);
            }
            else if (this.state.form.confirmWarning == "false")
            {
                console.log('confirmWarning: false');
                this.state = {
                    ...this.state,
                    form: {
                        ...this.state.form,
                        confirmWarning: "",
                        actionDeleteButton: "",
                    }
                };
                this.warningModal();
            }
            else
            {
                if(parseInt(this.state.form.actionEditButton) > 0 || parseInt(this.state.form.actionViewButton) > 0)
                {   
                    this.getGenders();
                    this.getRol();
                    this.getUsersInfo();
                }

                if(parseInt(this.state.form.actionDeleteButton) > 0)
                {
                    this.warningModal(
                        'open',
                        'MENSAJE',
                        'Esta a punto de eliminar el usuario seleccionado. No podrá recuperar los datos después de esta acción.'
                    );
                }

            }
        }
    }, 50);
}

handleSubmitFilter = () => {
    
    setTimeout(() => {            
        this.cleanUsersTable();
        this.cleanPagination();
        this.getUsersInfo();
    }, 300);
    return false;

}

handleFilterChange = (e) => {
    
    this.state = {
        ...this.state,
        form : {                
            ...this.state.form,
            [e.name] : e.value,
        }
    };

}

handleSubmitDetails = () => {

    this.state = {
        ...this.state,
        queryResult : []
    };

    this.updateUserData();

    return false;
}

handleBirthDate = (e) => {       
    let user_birthDate = formatMask(e.value,"00-00-0000");
    this.state = {
        ...this.state,
        form : {                
            ...this.state.form,
            [e.name] : user_birthDate,
        }
    }; 
    document.getElementById(e.id).value = this.state.form.birth_date;
}

handlePhone = (e) => {       
    let user_phone = formatMask(e.value,"000-000-0000");
    //console.log(e.name+" "+e.value);
    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            [e.name]  : user_phone,
        }
    }; 
    document.getElementById(e.id).value = this.state.form[e.name];
}

handleId = (e) => {
    let goverment_id = formatMask(e.value,"000-0000000-0");
    //console.log(e.name+" "+e.value);
    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            [e.name]  : goverment_id,
        }
    };
    document.getElementById(e.id).value = this.state.form[e.name];
}

handleUserChange = (e) => {
    //console.log('handleUserChange');
    //console.log(e.name+" "+e.value);
    this.state = {
        ...this.state,
        form : {                
            ...this.state.form,
            [e.name] : e.value,
        }
    };
    document.getElementById(e.id).value = this.state.form[e.name];
}

handleActivationButton = () => {        
    this.sendActivationMessage();
}

handleNewUSerSubmit = () => {
    
    if ( (this.state.form.newUsers_users_name.trim() !== "") &&
         (this.state.form.newUsers_first_name.trim() !== "") &&
         (this.state.form.newUsers_last_name.trim() !== "") &&
         (this.state.form.newUsers_users_goverment_id.trim() !== "") &&
         (this.state.form.newUsers_gender.trim() !== "") &&
         (this.state.form.newUsers_users_email.trim() !== "") &&
         (this.state.form.newUsers_users_phone.trim() !== "")
    )
    {
        console.log('regitro');
        this.signUp();
    }
    else
    {
        console.log('MESSAGE');
        this.actionMessage('Complete todos los campos','warning');
    }

    return false;
}

queryResult = () => {    
   
    this.state.data.fetched.queryResult.forEach(element => {
    
        let tr = document.createElement('tr');
            tr.setAttribute('key',element.user_id);
            tr.setAttribute('class','table_row');
        
        let td_one = document.createElement('td');
            td_one.innerHTML = element.user_id;

        let td_two = document.createElement('td');
            td_two.innerHTML = element.users_name;

        let td_three = document.createElement('td');
            td_three.innerHTML = element.first_name;

        let td_four = document.createElement('td');
            td_four.innerHTML = element.last_name;

        let td_five = document.createElement('td');
            td_five.innerHTML = element.users_goverment_id;

        let td_six = document.createElement('td');
            td_six.innerHTML = element.users_email;

        let td_seven = document.createElement('td');

        let div = document.createElement('div');

        let span_one = document.createElement('span');

        let abbr_one = document.createElement('abbr');
            abbr_one.setAttribute('title','Ver');            
            
        let button_one = document.createElement('button');
            button_one.setAttribute('onclick','handleManageUsersClick(this)');
            button_one.setAttribute('id','actionViewButton');
            button_one.setAttribute('class','actionBtn');
            button_one.setAttribute('type','button');
            button_one.setAttribute('name','actionViewButton');
            button_one.setAttribute('value',element.user_id);
        
        let spanActionBtn = document.createElement('span');
            spanActionBtn.setAttribute('class','spanActionBtn');

        let icon_ActionBtn = document.createElement('i');
            icon_ActionBtn.setAttribute('class','fas fa-search-plus');
            
            spanActionBtn.appendChild(icon_ActionBtn);

            button_one.appendChild(spanActionBtn);

            abbr_one.appendChild(button_one);
            span_one.appendChild(abbr_one);
            
            div.appendChild(span_one);

        let span_two = document.createElement('span');

        let abbr_two = document.createElement('abbr');            
            abbr_two.setAttribute('title','Editar');

        let button_two = document.createElement('button');
            button_two.setAttribute('onclick','handleManageUsersClick(this)');
            button_two.setAttribute('id','actionEditButton');
            button_two.setAttribute('class','actionBtn');
            button_two.setAttribute('type','button');
            button_two.setAttribute('name','actionEditButton');
            button_two.setAttribute('value',element.user_id);
        
            spanActionBtn = document.createElement('span');
            spanActionBtn.setAttribute('class','spanActionBtn');

            icon_ActionBtn = document.createElement('i');
            icon_ActionBtn.setAttribute('class','fas fa-user-edit');
            
            spanActionBtn.appendChild(icon_ActionBtn);

            button_two.appendChild(spanActionBtn);

            abbr_two.appendChild(button_two);
            span_two.appendChild(abbr_two);
            
            div.appendChild(span_two);
            
        let span_three = document.createElement('span');

        let abbr_three = document.createElement('abbr');
            abbr_three.setAttribute('title','Eliminar');

        let button_three = document.createElement('button');
            button_three.setAttribute('onclick','handleManageUsersClick(this)');
            button_three.setAttribute('id','actionDeleteButton');
            button_three.setAttribute('class','actionBtn');
            button_three.setAttribute('type','button');
            button_three.setAttribute('name','actionDeleteButton');
            button_three.setAttribute('value',element.user_id);

            spanActionBtn = document.createElement('span');
            spanActionBtn.setAttribute('class','spanActionBtn');

            icon_ActionBtn = document.createElement('i');
            icon_ActionBtn.setAttribute('class','fas fa-ban');
            
            spanActionBtn.appendChild(icon_ActionBtn);

            button_three.appendChild(spanActionBtn);

            abbr_three.appendChild(button_three);
            span_three.appendChild(abbr_three);

            div.appendChild(span_three);

            td_seven.appendChild(div);

            tr.appendChild(td_one);
            tr.appendChild(td_two);
            tr.appendChild(td_three);
            tr.appendChild(td_four);
            tr.appendChild(td_five);
            tr.appendChild(td_six);
            tr.appendChild(td_seven);

            tbody = document.querySelector('tbody');                
            tbody.appendChild(tr);
    });
    
}

renderUserDetailsForm = () => {

    this.cleanUserDetailsForm();

    let details__activationWrapper = document.querySelector('#details__activationWrapper');
        details__activationWrapper.setAttribute('class', (this.state.userDetails.account_confirmed == "1" || this.state.userDetails.users_role == "") ? "details__activationWrapper no-show" : "details__activationWrapper");

    let details__activationWrapper_button = document.querySelector('#details__activationWrapper > div > button.details_activartionButton');
        details__activationWrapper_button.disabled = this.state.userDetails.account_confirmed == "1" ? true : false;

    let details_user_name = document.querySelector('#details_user_name');
        details_user_name.value = this.state.userDetails.users_name;
        /* details_user_name.disabled = this.state.form.disabled; */

    let details_rol = document.querySelector('#details_rol');
        details_rol.value = this.state.userDetails.role_id;
        details_rol.disabled = this.state.form.disabled;

    let details_first_name = document.querySelector('#details_first_name');
        details_first_name.value = this.state.userDetails.first_name;
        details_first_name.disabled = this.state.form.disabled;

    let details_last_name = document.querySelector('#details_last_name');
        details_last_name.value = this.state.userDetails.last_name;
        details_last_name.disabled = this.state.form.disabled;

    let details_goverment_id = document.querySelector('#details_goverment_id');
        details_goverment_id.value = this.state.userDetails.users_goverment_id;
        details_goverment_id.disabled = this.state.form.disabled;

    let details_email = document.querySelector('#details_email');
        details_email.value = this.state.userDetails.users_email;
        details_email.disabled = this.state.form.disabled;
        
    let details_phone = document.querySelector('#details_phone');
        details_phone.value = this.state.userDetails.users_phone;
        details_phone.disabled = this.state.form.disabled;

    let details_birth_date = document.querySelector('#details_birth_date');
        details_birth_date.value = this.state.userDetails.birth_date;
        details_birth_date.disabled = this.state.form.disabled;
        
    let details_gender = document.querySelector('#details_gender');
        details_gender.value = this.state.userDetails.gender_id;    
        details_gender.disabled = this.state.form.disabled;

    let details_btn_submit = document.querySelector('#details_btn_submit');
        details_btn_submit.disabled = this.state.form.disabled;

    setTimeout(() => {
        
        this.state.form.roles.forEach( item => {
    
            let details_rol = document.querySelector('#details_rol');
            
            let option = document.createElement('option');
                option.value = item.id;
                option.innerHTML = item.role_name;
                option.selected = ( this.state.userDetails.role_id == item.id ) ? true : false;
                
                details_rol.appendChild(option);
            
        });

        this.state.data.fetched.genders.forEach( item => {

            let details_gender = document.querySelector('#details_gender');

            let option = document.createElement('option');
                option.value = item.id;
                option.innerHTML = item.gender;
                option.selected = ( this.state.userDetails.gender_id == item.id ) ? true : false;
                
                details_gender.appendChild(option);
            
        });

    }, 600);
}

renderNewUserForm = () => {

    this.getGenders();

    let newUsers_gender = document.querySelector('#newUsers_gender');
    
    setTimeout( () => {
        do {
            
            this.state.data.fetched.genders.forEach( (item) => {
                
                let option = document.createElement('option');
                    option.setAttribute('key',item.id);
                    option.value = item.id;
                    option.innerHTML = item.gender;
                    
                    newUsers_gender.appendChild(option);
    
            });
        } while (typeof this.state.data.fetched.genders == 'undefined');
        
    }, 600);

}; window.addEventListener('DOMContentLoaded', renderNewUserForm );

sendActivationMessage = async () => {

    this.loading();

    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;
        
        try {
            
            const server = await fetch( API_SEND_ACTIVATION_MESSAGE_URL ,{
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'  
                },
                body : JSON.stringify({
                    target              : "active_message",
                    session_token       : session_token,
                    session_user_id     : user_id,                        
                    user_name           : this.state.userDetails.users_name,
                    user_email          : this.state.userDetails.users_email,
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
                console.log("Error. 401");
                go_home();
            }
            else if ( server.status == 409 )
            {
                this.actionMessage(response.message,'warning');                 
            }
            else
            {
                go_home();
            }
            
        }
        catch (error)
        {
            this.loading();
            console.log(error);
        }
    }
}

signUp = async () => {

    this.loading();

    try {
        
        const server = await fetch( API_SIGN_UP_URL ,{
            method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    users_name          : this.state.form.newUsers_users_name.toLowerCase(),
                    first_name          : this.state.form.newUsers_first_name.toUpperCase(),
                    last_name           : this.state.form.newUsers_last_name.toUpperCase(),
                    users_goverment_id  : this.state.form.newUsers_users_goverment_id,
                    gender              : this.state.form.newUsers_gender,
                    users_email         : this.state.form.newUsers_users_email.toLowerCase(),
                    users_phone         : this.state.form.newUsers_users_phone,
                    admin_id            : this.state.data.fetched.user_id ? this.state.data.fetched.user_id : ""
                })
        });

        const response = await server.json();
        //const response = await server.text();               

        if ( server.status == 200 )
        {                
            this.state = {
                ...this.state,
                form: {
                    ...this.state.form,                    
                    btn_disabled        : true,                
                }                    
            };           

            this.actionMessage('Nuevo Usuario Creado Correctamente.','warning');
            //this.cleanNewUserForm();
            this.renderNewUserForm();
            console.log('status: '+server.status);
        }
        else if ( server.status == 403 )
        {
            this.actionMessage('El usuario ya existe.','warning');
            console.log('status:'+server.status);
        } 
        else if ( server.status == 409 )
        {
            this.actionMessage(response.message,'warning');            
            console.log('status:'+server.status);
        }                
        else if (server.status == 401 )
        {
            console.log('status:'+server.status);
            go_home();
        }
        else // excepciones no controladas
        {
            this.actionMessage('Error, contacte al administrador de Sistemas.','warning');
            console.log('status:'+server.status);
        }

        this.loading();

    } catch (error) {
        this.actionMessage('El usuario no pudo ser creado, contacte al administrador de Sistemas.','warning');            
        console.log(error);                        
        this.loading();
    }
}

updateUserData = async () => {

    this.loading();

    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;
        
        try {
            
            const server = await fetch( API_UPDATE_USER_DATA_URL ,{
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'  
                },
                body : JSON.stringify({
                    target              : "user_details",
                    session_token       : session_token,
                    session_user_id     : user_id,
                    user_id             : this.state.form.user_id,
                    user_role_id        : this.state.form.role_id,
                    users_name          : this.state.form.users_name,
                    first_name          : this.state.form.first_name,
                    last_name           : this.state.form.last_name,
                    users_goverment_id  : this.state.form.users_goverment_id,
                    users_email         : this.state.form.users_email,
                    users_phone         : this.state.form.users_phone,
                    gender_id           : this.state.form.gender_id,
                    birth_date          : this.state.form.birth_date,                        
                })
            });

            const response = await server.json();
            //const response = await server.text();

            console.log(response);

            this.loading();

            if ( server.status == 200 )
            {

                this.actionMessage(response.message,'warning');

            }
            else if ( server.status == 401 )
            {
                //this.actionMessage(response.message,'warning');
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