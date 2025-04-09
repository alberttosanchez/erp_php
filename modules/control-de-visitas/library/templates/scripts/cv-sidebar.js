
clearSelectionInSideBar = () => {
    let menu_items = document.querySelectorAll('.left_sidebar .menu-list button');

    menu_items.forEach(item => {
        
        item.style.color = "initial";
        
    });
}

/**  
 * maneja el menu del sidebar seleccionando y abriendo los submenu
 */
const handle_sidebar_menu = () => {

    let url = window.location.href;

    let array_url = url.split("/");

    let selection = '';

    if (array_url.length > 4)
    {   
        selection = array_url[4];
    }
    else if (array_url.length == 4)
    {
        selection = 'dashboard';
    }
    
    const render_selection = (selection_id) => {
        const list_colletion = document.querySelectorAll('#module_sidebar li');    
        //console.log(list_colletion);
        list_colletion.forEach(element => {
            //console.log(element.id)
            if (element.id == selection_id)                    
            {
                const selected_DOM = document.getElementById(selection_id);
                      selected_DOM.setAttribute('class','selected');

                const list_item_parent = selected_DOM.parentElement.parentElement.parentElement;
                const wrap_parent = selected_DOM.parentElement;

                if (list_item_parent && wrap_parent)
                {
                    let classes = list_item_parent.classList.value;
                    if (classes.search('has_children') > -1)
                    {
                        list_item_parent.classList.toggle('rotate');
                    }
                    let wrapclasses = wrap_parent.classList.value;
                    if (wrapclasses.search('contract') > -1)
                    {
                        wrap_parent.classList.toggle('contract');
                    }
                }

            }
        });


    }

    switch (selection) {
        case 'dashboard':          render_selection('cv_dashboard');          break;
        case 'register':           render_selection('cv_register_visit');     break;
        case 'finalize_visit':     render_selection('cv_finalize_visit');     break;
        case 'visit_history':      render_selection('cv_history_visit');      break;
        case 'reports':            render_selection('cv_reports');            break;
        case 'coworkers':          render_selection('cv_coworkers');          break;
        case 'plant_distribution': render_selection('cv_plant_distribution'); break;
        case 'general':            render_selection('cv_general');            break;    
        default:break;
    }

}

/**
 * Muestra la vista (library/contents/...) cuyo id es enviado como parametro.
 */
/* show_info = (e) => {
    
    clearSelectionInSideBar();
    
    if (typeof e == 'object') e.style.color = 'dodgerblue';

    let elements = document.querySelectorAll('.content');

    elements.forEach(item => {
        
        item.style.display = "none";        
        
    });

    //para permitir enviar una cadena como parametro.
    if (typeof e == "string") {  e = { value : e }; }
    
        
    let content = document.querySelector('#'+e.value);    
        content.style.setProperty("display", "block", "important");        
    
    switch (e.value) {
        case 'cv_plant_distribution':
            get_pt_dist_level_access_list();
            get_pt_dist_floor_location_list();
            break;
        
        case 'cv_register_visit':
            co_get_plant_distibution();
            get_reg_level_access();
            get_reg_visit_reason();
        default:
            break;
    }
    
}; */ //window.addEventListener('DOMContentLoaded', show_info("cv_dashboard") ); //cv_dashboard

/**
 * abre o cierra de forma horizontal la barra vertical izquierda (sidebar)
 */
const expand_nav = ( status = "dinamic" ) => {

    const module_sidebar = document.querySelector('#module_sidebar');                    
    //const expand_btn = document.querySelectorAll('.menu_sidebar .expand_btn .expand_btn_icon'); 
    let sidebar_menu_button = document.querySelectorAll('.sidebar_menu_button');

    let toggle_nav = () => {
        
        module_sidebar.classList.toggle('contract');
        
        for (let i = 0; i < sidebar_menu_button.length; i++) {            
            sidebar_menu_button[i].classList.toggle('no-show');
        }        
    }

    if (status == 'load' && localStorage.expand_nav == "expanded")
    {
        toggle_nav();
    }
    else if (status == 'load')
    {        
        if ( typeof localStorage.expand_nav == "undefined" )
        {
            localStorage.expand_nav = "contract";
        }        
    }
    else
    {   
        
        toggle_nav();

        if ( localStorage.expand_nav == "contract")
        {
            localStorage.expand_nav = "expanded";
        }
        else
        {
            localStorage.expand_nav = "contract";
        }

    }
  
    handle_sidebar_menu();

}

window.addEventListener('DOMContentLoaded', expand_nav('load') );