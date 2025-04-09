import { ValidateForms, Files } from "../../class/class.index.js";

(function(ValidateForms,Files){

    //////////////////////////////////////////////////////////////////////////

    /** Variables y Constantes */


    //////////////////////////////////////////////////////////////////////////

    /*** Action functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Get Functions */

    const get_all_post_filter_from_url = () => {

        let url = decodeURIComponent(window.location.href);

        let array_url = url.split("?");

        let all_post_filter = "";
        array_url.forEach( (item,key) => {

            if ( item.indexOf("=") > -1 )
            {         
                //console.log("all_post_filter:",item.substring(item.indexOf("=")+1));       
                all_post_filter = item.substring(item.indexOf("=")+1);
            }
        });
        
        return all_post_filter;
        
    }

    const get_all_post_list = async () => {
        
        // obtenemos el valos de la variable all_post_filter de la url actual.
        let all_post_filter = get_all_post_filter_from_url();
        
        if ( all_post_filter == "" || null == all_post_filter )
        {
            all_post_filter = "all";
        }        

        localStorage.all_post_filter = all_post_filter;

        try {

            const server_response = await fetch(ARCH_API_URL,{
                method : "POST",
                headers : {
                    "Content-Type" : "application/json",                        
                },
                body: JSON.stringify({
                    target : "post-read_all",
                    session_token : localStorage.session_token,
                    user_id : localStorage.user_id,
                    filter : all_post_filter
                })
            })

            const json = await server_response.json();
            //console.log(json);
            switch (server_response.status) {
                case 200:
                    //console.log("200");                    
                    
                    show_notification_message(json.message,'success');
                    
                    setTimeout(() => {
                        localStorage.all_post_data = JSON.stringify(json.data.fetched);
                        setTimeout(() => {                     
                            render_all_post_list();
                        }, 100);
                    }, 100);

                    
                    break;
                case 401:
                    //console.log("401");
                    //show_or_hidde_arch_new_spinner_submit_btn('hidde');
                    break;
                case 403:
                    //console.log("403");
                    
                    
                    break;
                case 406:
                    //console.log("406");                    
                    break;
                case 409:
                    //console.log('409',json.message);
                    /* show_notification_message('Error 409, contacte al administrador de sistemas.','error');
                    show_or_hidde_arch_new_spinner_submit_btn('hidde'); */
                default:
                    break;
            }

            

        } catch (error) {
            //console.log(error.message);                  
        }  
    }

    //////////////////////////////////////////////////////////////////////////

    /*** Push (Insert) Functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Update Functions */


    //////////////////////////////////////////////////////////////////////////

    /*** Handle Functions */


    ///////////////////////////////////////////////////////////////////////////
    
    /*** Render Functions */

    const render_all_post_list = () => {

        let all_post_filter = localStorage.all_post_filter;
        
        const all_post_data = JSON.parse(localStorage.all_post_data);

        //console.log(all_post_data);

        const arch_all_post_list_wrapper = document.querySelector("#arch_all_post_list_wrapper");

        let html = "<ul class='unstyled-list' style='margin-left:20px'>";

        if (all_post_data.length < 1)
        {
            html += `<li>No hay entradas para mostrar.</li>`;
        }
        else
        {
            for (const key in all_post_data) {
    
                let letter_was_show = false;
                all_post_data[key].map( (item,index) => {
                    
                    if ( !letter_was_show )
                    {
                        html += `<li><h2 style="font-weight:bold">${key.toUpperCase()}<h2></li>`;
                        letter_was_show = true;
                    }
    
                    html += `<li key="${item['id']}"><a href="arch_post?id=${item['id']}" style="text-decoration:none;">${item['post_title']}</a></li>`;
    
                });
            }
        }

        html += `</ul>`;  
        arch_all_post_list_wrapper.innerHTML = html;

    }

    const render_abc_list = () => {

        const arch_all_abc_wrapper = document.querySelector('#arch_all_abc_wrapper');

        let abc_letters = ['all','0-9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z'];

        let abc_elements = "";
            abc_elements += `<ul id="abc_submenu" class="abc_submenu submenu unstyled-list">`;
        
            abc_letters.map( (letter, key) => {
                
                abc_elements += `<li key="${key}"><a href="?all_post_filter=${letter}">${(letter == "all") ? "Todo" : letter}</a></li>`;            

            });

        abc_elements += `</ul>`;
        arch_all_abc_wrapper.innerHTML = abc_elements;

    }

    //////////////////////////////////////////////////////////////////////////
    
    /*** Events */

    
    /**************************** */
    /*** Keydown Events */

    
    /**************************** */
    /*** Submit Events */

    
    /**************************** */
    /*** Mousedown Events */

    
    /**************************** */
    /*** Mouseup Events */

        

    /**************************** */
    /*** Dragover Events */

    
    
    /**************************** */
    /*** Dragleave Events */

    

    /**************************** */
    /*** Change Events */

    

    /**************************** */
    /*** Drop Events */


    /*** DOMContentLoaded Events */
    
    window.addEventListener('DOMContentLoaded', render_abc_list() );
    window.addEventListener('DOMContentLoaded', get_all_post_list() );

}(ValidateForms,Files));