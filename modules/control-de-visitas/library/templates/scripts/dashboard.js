

const render_db_active_visitants_data_fetched = () => {

    const db_tbody = document.querySelector('#cv_dashboard_table_resume table tbody');

    let d = new Date();
    let current_date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
    
    JSON.parse(localStorage.db_active_visitants_data_fetched).fetched.forEach( item => {
        
        if ( item.visit_state == '1' )
        {
            let t_row = document.createElement('tr');
                t_row.setAttribute('key' , item.id_visitant );

            let td_one = document.createElement('td');
                td_one.innerHTML = item.name + " " + item.last_name;
            
            let td_two = document.createElement('td');                
                td_two.innerHTML = item.ident_number;
            
            let td_three = document.createElement('td');
                //td_three.innerHTML = item.department + " - " + item.floor_location;
                td_three.innerHTML = item.cw_raw_department + " - " + item.floor_location;
            
            let td_four = document.createElement('td');
                //td_four.innerHTML = item.cw_name + " " + item.cw_last_name;
                td_four.innerHTML = item.cw_raw_full_name;

            let td_five = document.createElement('td');
                td_five.innerHTML = item.started_at;
            
            let td_six = document.createElement('td');
                td_six.innerHTML = item.ended_at;
            
                t_row.appendChild(td_one);
                t_row.appendChild(td_two);
                t_row.appendChild(td_three);
                t_row.appendChild(td_four);
                t_row.appendChild(td_five);
                t_row.appendChild(td_six);

                db_tbody.appendChild(t_row);
        }            

    });
};

/**
 * Guarda un objeto con los visitantes activos en localstorage
 */
const get_db_active_visitants = async () => {
                
    //loading();

    const dasboard_form_id = document.getElementById('dasboard_form_id');

    let body_data = JSON.stringify({                
        target          : "dashboard-read_visitants",
        info_data       : JSON.parse('{}'),
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id,
        form_id         : dasboard_form_id.value,
        //table_name      : CVMJ_VIEW_VISITANT_AND_VISIT_TABLE,
        //selected_page   : '1',
    });

    try {

        let url = CV_API_URL;
        
        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: body_data, 
        });

        const response = await server.json();    
        
        //console.log(response);

        //loading();

        switch (server.status) {
            case 200:
                if (response.message == "Datos Recuperados")
                {
                    localStorage.db_active_visitants_data_fetched = JSON.stringify(response.data);

                    setTimeout(() => {
                        render_db_active_visitants_data_fetched();
                    }, 100);

                }
                else if (response.message == "no data")
                {
                    //actionMessage('No hay Resultados'.capitalize(),'warning');
                }
                else
                {
                    //window.location.href = URL_BASE+"/";
                }


                break;
            
            case 401:
                //window.location.href = URL_BASE+"/";   
                break;
        
            default:
                actionMessage(response.message.capitalize(),'warning');
                break;
        }
    } catch (error) {

        console.log(error);

    }
};

/* const get_db_active_visitants = async () => {
                
    //loading();

    let body_data = JSON.stringify({                
        target          : "get_today_visitants_data",
        table_name      : CVMJ_VIEW_VISITANT_AND_VISIT_TABLE,
        info_data       : JSON.parse('{}'),
        selected_page   : '1',
        session_token   : localStorage.session_token,
        user_id         : this.state.data.fetched.user_id
    });

    try {

        let url = API_MANAGE_DB_URL;
        
        const server = await fetch( url, {
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: body_data, 
        });

        const response = await server.json();    
        
        //console.log(response);

        //loading();

        switch (server.status) {
            case 200:
                if (response.message == "datos obtenidos")
                {
                    localStorage.db_active_visitants_data_fetched = JSON.stringify(response.data);

                    setTimeout(() => {
                        render_db_active_visitants_data_fetched();
                    }, 100);

                }
                else if (response.message == "no data")
                {
                    //actionMessage('No hay Resultados'.capitalize(),'warning');
                }
                else
                {
                    window.location.href = URL_BASE+"/";
                }


                break;
            
            case 401:
                window.location.href = URL_BASE+"/";   
                break;
        
            default:
                actionMessage(response.message.capitalize(),'warning');
                break;
        }
    } catch (error) {

        console.log(error);

    }
}; */

const clean_db_active_visitants_table = () => {
    let db_tbody_rows = document.querySelectorAll('#cv_dashboard_table_resume table tbody > tr');
        db_tbody_rows.forEach( item => {
            item.remove();
        });
};

assign_start_events = () => {

    get_db_active_visitants();

}; window.addEventListener( 'DOMContentLoaded' , assign_start_events );

