/**
 * Muestra la vista (library/templates/...) cuyo id es enviado como parametro.
 */
show_info = (target_section = '') => {
 
    if ( target_section === '' )
    {   
        let was_selected = false;

        let windowURI = window.location.href;
        let array = windowURI.split('/');
        target_section = (typeof array[4] == 'undefined') ? "" : array[4]; 
        target_section = target_section.split('?')[0];
        target_section = target_section.split('#')[0];
        //console.log(target_section);

        const submenu_anchors = document.querySelectorAll('.sub_menu li > a');

        //console.log(submenu_anchors);
        
        // agrega la clase al submenu en pantalla en el sidebar
        for (let i = 0; i < submenu_anchors.length; i++) {

            let href = submenu_anchors[i].href;  
                //console.log('href',href);
                array = href.split('/');
            let anchor_target = array[4]; 

            if ( target_section === anchor_target )
            {
                submenu_anchors[i].classList.toggle('selected');                
                was_selected = true;
            }
            else if( ! was_selected && i+1 === submenu_anchors.length ){                
                target_section = 'landing_section';                
            }
            
        }
    }

    //clearSelectionInSideBar();
    
    //if (typeof e === 'object') e.style.color = 'dodgerblue';

    let elements = document.querySelectorAll('.section');

    elements.forEach(item => {        
        item.style.display = "none";
    });

    let content = document.querySelector(`#${target_section}`);    
        content.style.setProperty("display", "flex", "important");        
    
   /*  switch (e.value) {
        case 'cv_plant_distribution':
            get_pt_dist_level_access_list();
            get_pt_dist_floor_location_list();
            break;
    
        default:
            break;
    } */
    
}; window.addEventListener('DOMContentLoaded', show_info() ); //show_info("landing_section"

/**
 * abre o cierra de forma horizontal la barra vertical izquierda (sidebar)
 */
const expand_nav = ( status = "dinamic" ) => {

    const menu_sidebar = document.querySelector('.menu_sidebar');                    
    const expand_btn = document.querySelectorAll('.menu_sidebar .expand_btn .expand_btn_icon'); 

    let toggle_nav = () => {

        menu_sidebar.classList.toggle('expanded');
        
        for (let i = 0; i < expand_btn.length; i++) {
            expand_btn[i].classList.toggle('no-show');
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
  

}

//-------------------------------------------------------------------
// controles del sidebar
//-------------------------------------------------------------------
const menu_anchors = document.querySelectorAll('.menu_sidebar .menu > li > a');

for (let i = 0; i < menu_anchors.length; i++) { 

    menu_anchors[i].addEventListener( 'click', (ev)=> {                
        menu_anchors[i].parentElement.classList.toggle('closed');
        menu_anchors[i].parentElement.classList.toggle('opened');
    });

}

const submenu_lis = document.querySelectorAll('.menu_sidebar .sub_menu > li');

for (let i = 0; i < submenu_lis.length; i++) { 
    
    
    submenu_lis[i].addEventListener( 'click', (ev)=> {
        
        //console.log(ev);

        setTimeout(() => {
            let baseURI = ev.target.baseURI;
            let array = baseURI.split('#');
            let section_target = array[1];

            show_info(section_target);                    
        }, 100);
        
    });
}

const submenu_anchors = document.querySelectorAll('.menu_sidebar .sub_menu > li > a');

for (let i = 0; i < submenu_anchors.length; i++) {                    
    submenu_anchors[i].addEventListener( 'click', (ev)=> {                         
        for (let i = 0; i < submenu_anchors.length; i++) {
            submenu_anchors[i].setAttribute('class','');
        }
        submenu_anchors[i].classList.toggle('selected');
    });
} 

window.addEventListener('DOMContentLoaded', expand_nav('load') );