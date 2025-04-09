this.state = {
    ...this.state,
    form : {
        ...this.state.form,
        active_action : "",
        action_inactive : "",
        action_uninstall : "",
        target : ""
    },
    modules: [],            
    mod_pagination : {
        ...this.state.mod_pagination,
        counter         : "",
        selected_page   : "",                
    },
    file_data : {
        ...this.state.file_data,
        zip_file        : "",
        file_type       : "",
        file_name       : "",
    }, 
}

barStatus = (param = "") => {
    
    let action_id = ( this.state.form.action_active !== "" ) ? this.state.form.action_active : ( this.state.form.action_uninstall !== "" ) ? this.state.form.action_uninstall : ( this.state.form.action_inactive !== "" ) ? this.state.form.action_inactive : "";
    
    let progress_bar = document.getElementById("loading-"+action_id);
    
    if ( progress_bar )
    {
        if (param !== "")
        {
            progress_bar.attributes["bar-status"].value = param;
        }
        
        let progress_bar_status = progress_bar.attributes["bar-status"].value;
    
        return progress_bar_status;
    }


}

cleanManageModules = () => {
    let modules = document.querySelectorAll('#modules_table tbody > tr');

    modules.forEach( item => {
        item.remove();
    });
}

debuger = async () =>
{
            
    
        
        const server = await fetch( API_MODULES_URL ,{
            method : 'POST',
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({                                                     
                target          : this.state.form.target,
                session_token   : this.state.data.fetched.session_token,
                user_id         : this.state.data.fetched.user_id,
                selected_page   : this.state.mod_pagination.selected_page,
                action_active   : this.state.form.action_active,
                action_inactive : this.state.form.action_inactive,
                action_uninstall: this.state.form.action_uninstall,                    
            }) 
        });
            
        const res = await server.text();
            
        console.log(res);
    
}

removeModuleFromList = () => 
{
    this.state.modules.map( (element) => {
 
        
        element.forEach( (item) => {
            
            if ( this.state.form.action_uninstall == item.id )
            {                
                
                let module = document.querySelector('#' + ( item['name'].replaceAll(' ','-') ).toLowerCase() );
                    
                    // deshabilitamos el boton desinstalar
                    module.children[0].children[1].children[2].children[0].disabled = true;

                    setTimeout(() => {
                        
                        // aplicamos la clase desvanecer
                        module.classList.toggle('dispel');
    
                        setTimeout(() => {
                            module.remove();                    
                        }, 1500);

                    }, 100);

            }
    
        })

    });
}

renderNoModulesMessage = () => {
    //console.log("renderNoModuleMessage");
    const modules_table_body = document.querySelector('#modules_table tbody');

    let tr = `<tr id="no_modules"><td>No hay modulos para mostrar.</td></tr>`; 

    modules_table_body.innerHTML = tr;
}

handleModules = async () =>
{
    //this.debuger();

    if ( this.state.form.target == "modules_action" || this.state.form.target == "module_uninstall" )  
    {
        this.barStatus('initialized');

        this.triggerLoadingBar();
    }

    try {
        
        const server = await fetch( API_MODULES_URL ,{
            method : 'POST',
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({                                                     
                target          : this.state.form.target,
                session_token   : this.state.data.fetched.session_token,
                user_id         : this.state.data.fetched.user_id,
                selected_page   : this.state.mod_pagination.selected_page,
                action_active   : this.state.form.action_active,
                action_inactive : this.state.form.action_inactive,
                action_uninstall: this.state.form.action_uninstall,                    
            }) 
        });

        const response = await server.json();            
        
        //console.log(response);
        //console.log(server);
        
        if ( server.status == 200 ){

            if ( response.target == "module_uninstall" && response.uninstalled == "true" )
            {            
                this.barStatus('completed');            
                this.removeModuleFromList();                
            }            
            else if ( response.target == "modules" && response.status == "204" )
            {
                console.log("modulos no obtenidos");
                // modulos no obtenidos
                this.state = {                    
                    ...this.state,                    
                    modules         : "",                    
                    mod_pagination  : "",
                    form            : {
                        ...this.state.form,                        
                        button_disabled : false,
                        target : "",
                    }
                };
                
                this.cleanManageModules();
                this.cleanModulePagination();
                setTimeout(() => {
                    this.renderNoModulesMessage();                    
                }, 100);
            }
            else if ( response.target == "modules" && response.status == "200" ){
                this.state = {                    
                    ...this.state,                    
                    modules         : [response.data.data],                    
                    mod_pagination  : response.data.pagination,
                    form            : {
                        ...this.state.form,                        
                        button_disabled : false,
                        target : "",
                    }
                };
                
                //console.log(this.state.modules);
    
                this.cleanManageModules();
                this.cleanModulePagination();
                setTimeout(() => {
                    this.renderManageModules();
                    this.renderModulePagination();
                }, 100);
            }
            else if ( response.target == "module_action" && response.status == "200" )
            {
                this.barStatus('completed');

                this.state = {                    
                    ...this.state,                                        
                    form : {
                        ...this.state.form,                                                
                        button_disabled : false,
                        target : "modules",
                    },
                    modules : [],
                    mod_pagination : {
                        ...this.state.mod_pagination,
                        counter         : "",
                        selected_page   : "",                
                    }
                };

                setTimeout(() => {
                    this.handleModules();                    
                }, 100);
            }
        } 
        else if ( server.status == 401 )
        {
            go_home();
        }
        else if ( server.status == 409 )
        {
            if ( response.target == "module_uninstall" )
            {            
                this.barStatus('failed');            
                this.actionMessage(response.message,'warning');                  
                //this.removeModuleFromList();
            }
        }
        else if ( server.status == 500 )
        {
            this.actionMessage(response.message,'warning'); 

            this.state = {                    
                ...this.state,
                modules : [],
                mod_pagination : {
                    ...this.state.mod_pagination,
                    counter         : "",
                    selected_page   : "",                
                }
            };

            this.cleanManageModules();
            this.cleanModulePagination();
            setTimeout(() => {
                this.renderManageModules();
                this.renderModulePagination();
            }, 100);
        }
        else if ( server.status == 503)
        {
            console.log(response);
            this.actionMessage(response.message,'warning');  
        }
        
    } catch (error) {
        
        this.barStatus('failed');

        this.state = {
            ...this.state,
            form : {
                ...this.state.form,                    
                button_disabled : false,
            }
        };

        console.log(error);
    }
}

moduleAction = (e) => {
    //e.persist();
    //console.log(e);

    if ( e.innerText == "Activar" )
    {            
        this.state = {
            ...this.state,
            form : {
                ...this.state.form,
                action_active   : e.value,
                action_inactive : "",
                action_uninstall: "",
                button_disabled : true,
                target          : "modules_action"
            }                
        };

    }
    else if ( e.innerText == "Desactivar" )
    {
        this.state = {
            ...this.state,
            form : {
                ...this.state.form,
                action_active       : "",
                action_inactive     : e.value,
                action_uninstall    : "",
                button_disabled     : true,
                target              : "modules_action"
            }
        };
    }
    

    setTimeout(() => {
        this.handleModules();
    }, 100);

}

moduleUninstall = (e) => {
    
    //console.log("uninstall: "+e.target.value);

    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            action_active   : "",
            action_inactive : "",
            action_uninstall: e.value,
            target : "module_uninstall"
        }
    };

    setTimeout(() => {
        this.handleModules();
    }, 300);
}

triggerLoadingBar = () => {
    
    let action_id = ( this.state.form.action_active !== "" ) ? this.state.form.action_active : ( this.state.form.action_uninstall !== "" ) ? this.state.form.action_uninstall : ( this.state.form.action_inactive !== "" ) ? this.state.form.action_inactive : "";
    
    let progress_bar = document.getElementById("loading-"+action_id);
    //console.log( this.barStatus() );
    if (this.barStatus() == "initialized")
    {            
        
        let action_id = ( this.state.form.action_active !== "" ) ? this.state.form.action_active : ( this.state.form.action_uninstall !== "" ) ? this.state.form.action_uninstall : ( this.state.form.action_inactive !== "" ) ? this.state.form.action_inactive : "";

        this.fill_bar = () => {
            
            let progress_bar = document.getElementById("loading-"+action_id);
                
            let progress_bar_width = parseInt(progress_bar.attributes["bar-width"].value);
            //console.log(progress_bar_width,this.barStatus());

            if ( this.barStatus() == "completed" && progress_bar_width <=80 )
            {   
                progress_bar.setAttribute('style','display:inline-table;background:red;width:100%;height:5px;transition:0.3s');
                
            }
            else if ( this.barStatus() == "initialized")
            {                    

                setTimeout(() => {
                    
                    progress_bar_width++;
                    
                    progress_bar.setAttribute('style','display:inline-table;background:red;width: '+progress_bar_width.toString()+'%;height:5px;transition:0.5s');                                                          
                    
                    progress_bar.attributes["bar-width"].value = progress_bar_width;                       
                                            
                    if (progress_bar_width <=80 && this.barStatus() == "initialized" )
                    {
                        this.fill_bar();                                                    
                    }
                    else if ( this.barStatus() == "completed")
                    {           
                        
                        progress_bar.setAttribute('style','display:inline-table;background:red;width:100%;height:5px;transition:0.3s');
                        
                    }
                    else if ( this.barStatus() == "failed" )
                    {     
                                    
                        progress_bar.setAttribute('style','display:inline-table;background:red;width:0%;height:5px;transition:0.3s');
                    }

                }, 50);
            }
            else if ( this.barStatus() == "completed")
            {           
                
                progress_bar.setAttribute('style','width: 100%;transition:0.3s');
                
            }
            else if ( this.barStatus() == "failed" )
            {     
                                
                progress_bar.setAttribute('style','width: 0%;transition:0.3s');
            }

        }
                        
        this.fill_bar();               
                
    }
    else if ( this.barStatus() == "completed")
    {           
        
        progress_bar.setAttribute('style','width: 100%;transition:0.3s');
        
    }
    else if ( this.barStatus() == "failed" )
    {               
        progress_bar.setAttribute('style','width: 0%;transition:0.3s');
    }

}

renderManageModules = () => {

    this.state.modules.map( (x) => {
        x.map(element => {
            
            let tr = document.createElement('tr');
                tr.setAttribute( 'key' , element['id'] );
                tr.setAttribute( 'id' , (element['name'].replaceAll(" ","-")).toLowerCase() );
                tr.setAttribute( 'class' , 'module_container' );
                tr.setAttribute( 'style' , 'display:flex;flex-wrap:wrap;' );
            
            let td_one = document.createElement('td');
                td_one.setAttribute( 'style' , "width:291px;" );
                
                tr.appendChild(td_one);
               
            let parr_one = document.createElement('p');
                td_one.appendChild(parr_one);

            let bold = document.createElement('b');
                parr_one.appendChild(bold);

            let span_one = document.createElement('span');
                span_one.setAttribute('class','module_name');
                span_one.innerHTML = element['name'];

                bold.appendChild(span_one);
               
            let parr_two = document.createElement('p');                    
                td_one.appendChild(parr_two);

            let span_two = document.createElement('span');
                span_two.setAttribute('class','module_action_active');
                parr_two.appendChild(span_two);

            let btn_one = document.createElement('button');
                btn_one.setAttribute('type','button');
                btn_one.setAttribute('onclick','moduleAction(this)');
                btn_one.setAttribute('value', element['id'] );
                btn_one.disabled = this.state.form.button_disabled;
                btn_one.innerHTML = ( parseInt(element['activation']) == 1 ) ? 'Desactivar' : 'Activar';

                span_two.appendChild(btn_one);
                                   
            let span_three = document.createElement('span');
                span_three.innerHTML = " | ";                    

                parr_two.appendChild(span_three); 
                     
            let span_four = document.createElement('span');
                span_four.setAttribute('class','module_action_uninstall');

                parr_two.appendChild(span_four);

            let btn_two = document.createElement('button');
                btn_two.setAttribute('type','button');
                btn_two.setAttribute('onclick','moduleUninstall(this)');
                btn_two.setAttribute('value', element['id'] );
                btn_two.disabled = this.state.form.button_disabled;
                btn_two.innerHTML = 'Desinstalar';

                span_four.appendChild(btn_two);
            
            let td_two = document.createElement('td');
                td_two.setAttribute( 'style' , 'width: calc(100% - 291px);' );

                tr.appendChild(td_two);

                /*<td2 style={{width:"calc(100% - 200px)"}}>*/
            let parr_three = document.createElement('p');
                
                td_two.appendChild(parr_three);

            let span_five = document.createElement('span');
                span_five.setAttribute( 'class' , 'module_description' );
                span_five.innerHTML = element['description'];

                parr_three.appendChild(span_five);

                    /*<p3><span5 class="module_description">{element['description']}</span></p>*/

            let parr_four = document.createElement('p');

                td_two.appendChild(parr_four);

            let span_six = document.createElement('span');
                span_six.innerHTML = "Version: ";

                parr_four.appendChild(span_six);

            let span_seven = document.createElement('span');
                span_seven.setAttribute('id','module_version');
                span_seven.setAttribute('class','module_version');
                span_seven.innerHTML = element['version'];
                
                parr_four.appendChild(span_seven);

            let span_eight = document.createElement('span');
                span_eight.innerHTML =" | Autor: ";

                parr_four.appendChild(span_eight);

            let span_nine = document.createElement('span');
                span_nine.setAttribute('id','module_author');
                span_nine.setAttribute('class','module_author');
                span_nine.innerHTML = element['author'];

                parr_four.appendChild(span_nine);

            let span_ten = document.createElement('span');
                span_ten.innerHTML =" | Web: ";

                parr_four.appendChild(span_ten);

            let span_eleven = document.createElement('span');
                span_eleven.setAttribute('id','module_web');
                span_eleven.setAttribute('class','module_web');

                parr_four.appendChild(span_eleven);

            let anchor = document.createElement('a');
                anchor.setAttribute('href', element['web'] );
                anchor.setAttribute('target', '_target' );
                anchor.setAttribute('rel', 'noopener noreferrer' );
                anchor.innerHTML = element['web'];

                span_eleven.appendChild(anchor);
            
                    /*<p4><span6>Version: </span>*/

                        /*<span7 
                            id="module_version" 
                            class="module_version"
                        >{element['version']}</span>
                        <span8> | Autor: </span>
                        <span9 
                            id="module_author" 
                            class="module_author"
                            >{element['author']}</span>
                        <span10> | Web: </span>
                        <span11 
                            id="module_web" 
                            class="module_web"
                        ><a href={element['web']} target="_blank" rel="noopener noreferrer">{element['web']}</a>
                        </span></i>
                    </p>
                </td>   */         
            let td_three = document.createElement('td');
                td_three.setAttribute( 'bar-status' , 'inherit' );
                td_three.setAttribute( 'bar-width' , '0' );
                td_three.setAttribute( 'id' , 'loading-'+element['id'] );
                td_three.setAttribute( 'style' , "display:inline-table;background:red;width:0%;height:5px" );

                tr.appendChild(td_three);
                /*<td3 
                    bar-status="inherit" 
                    bar-width="0" 
                    id={`loading-${element['id']}`} 
                    style={{display:"inline-table",background:"red",width:"0%",height:"5px"}}
                    >
                </td>
            </tr> */
            let tbody = document.querySelector('#modules_table tbody');
                tbody.appendChild(tr);
        });
    });

}

getModules = () => {
    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            target : "modules",
        }
    };

    setTimeout(() => {
        this.handleModules();            
    }, 100);
}; 

consoleMessage = (text_msg) =>
{        
    let message = document.createElement('p');
        message.setAttribute('class','message');
        message.innerHTML = text_msg;
    
    let consoleLine = document.querySelector("#ProgressConsole");        
    
        consoleLine.appendChild(message);   
}

/**
 * Inhabilita el boton para cargar el arhivo zip mientras lo verifica.
 */
disabledSubmitInstallModule = (param) => {

    let lbl_btn = document.getElementsByClassName('moduleInstall_zip_label');
    let submit_btn = document.getElementById('module_zip');

    if ( param == 'true' )
    {
        lbl_btn[0].classList.toggle('label_disabled');

        submit_btn.setAttribute('el-control','yes');

        submit_btn.disabled = true;            
    }
    else
    {            
        lbl_btn[0].classList.toggle('label_disabled');

        submit_btn.setAttribute('el-control','no');

        submit_btn.disabled = false;
    }
}

handleInputInstallModuleChange = (e) => {

    if( typeof e.files[0] !== "undefined" )
    {
        let ext = get_file_extension(e.files[0].name);
        // comprobamos que el archivo comprimido cumpla con el filtro
        if( 
            e.value !== "" && e.value !== null &&
            //e.target.files[0].size < 2024000 &&
            ( 
                ( ( e.files[0].type == "application/x-zip-compressed" ||
                    e.files[0].type == "application/zip" ) && ( ext == "zip" )
                )              
            ) 
        )
        {                

            this.disabledSubmitInstallModule('true');

            // guardamos los datos en el estado
            this.state = {
                ...this.state,
                file_data : {
                    ...this.state.file_data,
                    zip_file        : e.files[0],
                    file_type       : e.files[0].type,
                    file_name       : e.files[0].name,
                }
            };
            
            // mostramos un mensaje indicativo
            setTimeout(() => {

                this.consoleMessage("Archivo ZIP seleccionado: "+this.state.file_data.file_name);                    

                setTimeout(() => {

                    this.consoleMessage("Descomprimiendo archivo...");                    
                    
                    //sending file
                    this.uploadingModule();

                }, 1000);

            }, 50);                

        }            
        else
        {
            this.consoleMessage("Solo se permiten archivos ZIP.");
        }

        // remueve el valor del input file
        document.querySelector('#module_zip').value = "";
    }

    this.state = {
        ...this.state,
        form : {
            ...this.state.form,
            [e.name] : e.value
        }
    };

    /* setTimeout(() => {
                
    }, 50); */
}

handleInstallModuleSubmit = () => {
    return false;                
}

handleInputInstallModuleClick = (e) => {

    let submit_btn = document.getElementById('module_zip');

    // el-control es una atributo (personalizado) para verificar la proteccion disabled                    
    if (submit_btn.attributes['el-control'].value == 'yes'){ e.preventDefault(); }
    
    if (submit_btn.disabled == true ) { e.preventDefault(); }
    
}

uploadingModule = async () => {
    
    localStorage.module_status = "";

    this.barStatusInstallModule('initialized');

    this.triggerLoadingBarInstallModule();
    
    if(localStorage.user_id !== "" && localStorage.session_token !== "")
    {
        let session_token   = localStorage.session_token;
        let user_id         = localStorage.user_id;

        try {
            
            const   form = new FormData();
                    form.append('target', "upload_module");
                    form.append('session_token', session_token);
                    form.append('user_id', user_id);
                    form.append('user_role_id', this.state.data.fetched.role_id);
                    form.append('zip_file', this.state.file_data.zip_file);
            
            const   server = await fetch( API_UPLOAD_MODULE_ZIP_FILE_URL ,{
                method : "POST",
                body: form,
            });
            
            const response = await server.json();
                            
            //console.log(server);
            //console.log(response);
            
            if ( server.status == 200 )
            {   

                //console.log("estado:200");

                this.consoleMessage(response.message);

                this.barStatusInstallModule('completed');
                
                localStorage.module_status = "loaded";

                setTimeout(() => {
                    localStorage.show_manage = "true";
                    window.location.href = URL_BASE + "/manage";
                }, 1000);

            }
            else if (server.status == 401)
            {
                console.log("estado:401");                                        
                
                this.consoleMessage(response.message);
                
                this.barStatusInstallModule('failed');

                go_home();
            }
            else if (server.status == 403)
            {
                console.log("estado:403");                                        
                
                this.consoleMessage(response.message);
                
                this.barStatusInstallModule('failed');
                
            }
            else if (server.status == 200)
            {
                if (response.status == 400)
                {
                    this.consoleMessage('Error desconocido, contacte a su administrador de sistemas.');      
                }
                else
                {
                    this.consoleMessage(response.message);  
                }

                this.barStatusInstallModule('failed');                 
                
            }
            else if (server.status == 409)
            {
                console.log("estado:409");                        
                this.consoleMessage(response.message);
                this.barStatusInstallModule('failed');
            }
            else
            {
                console.log(response);
                //this.consoleMessage(response);
                this.barStatusInstallModule('failed');
                go_home();
            }

            //this.triggerSpinner();   
            this.disabledSubmitInstallModule('false');                 

        } catch (error) {

            console.log(error);
            //this.consoleMessage(error);
            this.triggerSpinner();

        }

    }
    else
    {
        go_home();
    }
    
    
}

barStatusInstallModule = (param = "") => {
    
    let progress_bar = document.getElementById("progress-bar");

    if (param !== "")
    {
        progress_bar.attributes["bar-status"].value = param;
    }
    
    let progress_bar_status = progress_bar.attributes["bar-status"].value;

    return progress_bar_status;

}

triggerLoadingBarInstallModule = () => {

    let progress_bar = document.getElementById("progress-bar");

    if (this.barStatusInstallModule() == "initialized")
    {            
        
        this.fill_bar = () => {
            
            let progress_bar = document.getElementById("progress-bar");
            let progress_bar_width = parseInt(progress_bar.attributes["bar-width"].value);
            

            if ( this.barStatusInstallModule() == "completed" && progress_bar_width <=80 )
            {   
                progress_bar.setAttribute('style','width:100%;transition:0.3s');
            }
            else if ( this.barStatusInstallModule() == "initialized")
            {                    

                setTimeout(() => {

                    progress_bar_width++;

                    progress_bar.setAttribute('style','width: '+progress_bar_width.toString()+'%;transition:0.3s');                        

                    progress_bar.attributes["bar-width"].value = progress_bar_width;                       
                                            
                    if (progress_bar_width <=80 && this.barStatusInstallModule() == "initialized" )
                    {
                        this.fill_bar();                                                    
                    }
                    else if ( this.barStatusInstallModule() == "completed")
                    {           
                        
                        progress_bar.setAttribute('style','width: 100%;transition:0.3s');
                        
                    }
                    else if ( this.barStatusInstallModule() == "failed" )
                    {     
                                    
                        progress_bar.setAttribute('style','width: 0%;transition:0.3s');
                    }

                }, 50);
            }
            else if ( this.barStatusInstallModule() == "completed")
            {           
                
                progress_bar.setAttribute('style','width: 100%;transition:0.3s');
                
            }
            else if ( this.barStatusInstallModule() == "failed" )
            {     
                                
                progress_bar.setAttribute('style','width: 0%;transition:0.3s');
            }

        }
                        
        this.fill_bar();               
                
    }
    else if ( this.barStatusInstallModule() == "completed")
    {           
        
        progress_bar.setAttribute('style','width: 100%;transition:0.3s');
        
    }
    else if ( this.barStatusInstallModule() == "failed" )
    {               
        progress_bar.setAttribute('style','width: 0%;transition:0.3s');
    }

}