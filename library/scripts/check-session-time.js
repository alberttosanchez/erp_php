const destroy_wsmb = () => {

    let warning_session_message_box = document.getElementById('warning_session_message_box');
    if ( warning_session_message_box ) warning_session_message_box.remove();

    check_session();
    clearInterval(sessionStorage.sbm_interval);
    sessionStorage.interval = (SESSION_INTERVAL / 1000);
}

const show_previous_close_session_message = () =>{
   
    let interval = sessionStorage.interval;

    const div = document.createElement('div');
          div.setAttribute('id','warning_session_message_box');
          div.setAttribute('class','warning_session_message_box');
          //div.setAttribute('onclick',`destroy_wsmb()`);
          

    let title = "AVISO";
    let message = `La sesión se cerrará en aproximadamente <span id="smb_interval_time">${interval}</span>. Presione Continuar para extender el tiempo.`;    
    let body = document.querySelector('body');
    let dom = "";
        
        dom +=      `<div class="smb_wrapper">`;
        dom +=          `<div class="smb_top_bar">`;
        dom +=              `<h5>${title}</h5>`;        
        dom +=              `<button class="sbm_close_btn" type="button" onclick="destroy_wsmb()">`;                 
        dom +=                  `<span><i class="fas fa-times"></i></span>`;                 
        dom +=              `</button>`;                 
        dom +=          `</div>`;
        dom +=          `<div class="smb_body">`;
        dom +=              `<p>${message}</p>`;        
        dom +=              `<button class="sbm_btn btn-warning" type="button" onclick="destroy_wsmb()">`;                 
        dom +=                  `<span>Continuar</span>`;                 
        dom +=              `</button>`;
        dom +=          `</div>`;
        dom +=      `</div>`;
        
        div.innerHTML = dom;

        body.appendChild(div);

        setTimeout(() => {
            const sbm_interval = setInterval(() => {
                let smb_interval_time = document.getElementById('smb_interval_time');
        
                if ( smb_interval_time )
                {
                    let interval = sessionStorage.interval;        
                    setTimeout(() => { smb_interval_time.innerHTML = interval; }, 100);
                }
            }, 1000 );
            
            sessionStorage.sbm_interval = sbm_interval;
        }, 100);
}

const session_counter_down = (session_interval,session_warning_interval) => {

    sessionStorage.interval = parseInt(session_interval / 1000); // : (int) seconds

    const intervalsetted = setInterval( () => {
                
        if (sessionStorage.interval > 0)
        {                
            //console.log(sessionStorage.interval);

            if ( sessionStorage.interval == (session_warning_interval / 1000) ){
                show_previous_close_session_message();
            } 

            sessionStorage.interval--;
        }
        else
        {
            clearInterval(intervalsetted);
            setTimeout(() => { close_session(); }, 100);
        }
    
        
    }, 1000);

}

window.addEventListener( 'DOMContentLoaded', () =>{ session_counter_down(SESSION_INTERVAL,SESSION_WARNING_INTERVAL); });