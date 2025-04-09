<script>    

    this.state = {                    
        ...this.state,
        mod_icon : [],
        mod_icon_pag : {
            ...this.state.mod_icon_pag,
            counter         : "",
            selected_page   : "",                
        }
    };

    getModuleIcons = async () =>
    {        
        try {
            
            const server = await fetch( API_MODULES_URL ,{
                method : 'POST',
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({                                                     
                    target          : 'modules',
                    session_token   : this.state.data.fetched.session_token,
                    user_id         : this.state.data.fetched.user_id,
                    selected_page   : this.state.mod_icon_pag.selected_page,                    
                }) 
            });

            const response = await server.json();            
            
            //console.log(server);
            //console.log(response);
                        
            if ( server.status == 200 && typeof response.data !== 'undefined' )
            {

                this.state = {                    
                    ...this.state,
                    mod_icon        : [response.data.data],                    
                    mod_icon_pag    : {
                        ...response.data.pagination
                    }
                };
                
                //console.log(this.state.mod_icon);

                //this.cleanModulesIcons();
                /* this.cleanModuleIconsPagination(); */
                setTimeout(() => {
                    this.renderModuleIcons();
                    /* this.renderModuleIconsPagination(); */
                }, 100);

            }            
            else if ( server.status == 401 )
            {
                go_home();
            }
            else if ( server.status == 200 && response.status == "204" )
            {                
                this.state = {                    
                    ...this.state,
                    mod_icon : [],
                    mod_icon_pag : {
                        ...this.state.mod_icon_pag,
                        counter         : "",
                        selected_page   : "",                
                    }
                };
            }
            
        } catch (error) {
            
            console.log(error.message);

        }
    }; window.addEventListener( 'DOMContentLoaded' , getModuleIcons );
    
    setDefaultImage = (e) => {
        e.setAttribute('src', MODULE_DEFAULT_IMAGE );        
    }

    renderModuleIcons = () => {
        
        let module_icons_wrapper = document.querySelector('#module_icons_wrapper');

        this.state.mod_icon.map( (element) => {
            
            element.forEach( (item) => {

                if ( item['activation'] == "1" )
                {
                    let anchor = document.createElement('a');
                        anchor.setAttribute('key', item['id'] );
                        if ( item['islink'] == "1" )
                        {
                            anchor.setAttribute('href', item['web'].toLowerCase() );
                            anchor.setAttribute('target', "_blank" );
                        }
                        else                        
                        {
                            anchor.setAttribute('href', MODULES_ROUTE + ( item['name'].replaceAll(" ","-") ).toLowerCase() );
                        }
                        anchor.setAttribute('module-name', item['name'] );
                        anchor.setAttribute('version', item['version'] );
                        anchor.setAttribute('class', 'module_item box' );
                        anchor.setAttribute( 'style', 
                            'display:flex;'+
                            'justify-content:center;'+
                            'flex-direction:column;'+
                            'align-items:center;'+
                            'width: 120px;'+
                            'background-color:#ffffffcc;'+
                            'border-radius:5%;'+
                            'cursor: pointer;'
                        );
                    
                    let div_one = document.createElement('div');
                        div_one.setAttribute('class','module_item_header');
                        div_one.setAttribute('style','padding:5px;');

                        anchor.appendChild(div_one);
                                        
                    let image = document.createElement('img');                    
                    image.setAttribute('onerror', "setDefaultImage(this)" );
                    let thumbnail = MODULES_DIRECTORY + item['name'].replaceAll(" ","-") + '/thumbnail.jpg';
                    image.setAttribute('src', thumbnail.toLowerCase() );
                    image.setAttribute('alt', 'Imagen del Modulo' );
                    image.setAttribute('style', 
                            'width: 100%;'+
                            'height: auto;'+
                            'max-width: 60px;'+
                            'border-radius: 5%;'+
                            'box-shadow: #635b5b 3px 4px 4px;'
                        );
                    
                        div_one.appendChild(image);
                
                        let div_two = document.createElement('div');
                            div_two.setAttribute('class','module_item_body');
                            div_two.setAttribute('style', 
                                'display: flex;'+
                                'justify-content: center;'+
                                'flex-direction: column;'+
                                'align-items: center;'
                            );
                        
                            anchor.appendChild(div_two);
                        
                        let span = document.createElement('span');
                            span.setAttribute('style', 
                                'padding: 10px;'+
                                'text-align: center;'
                            );
                            
                            span.innerHTML = item['name'];

                            div_two.appendChild(span);
                            
                            module_icons_wrapper.appendChild(anchor);
                            
                }

            });
            
        });
        
    };    

</script>