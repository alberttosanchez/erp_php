<div class="notification_wrapper">
    <!-- <div class="notificacion_box_content content box no-show" onclick="remove(this)> -->
        <!-- <div class="content_box"> -->
            <!-- <p class="message"></p> -->
            <!-- <span class="notification_icon"><i class="fas fa-exclamation"></i></span> -->
        <!-- </div> -->
    <!-- </div> -->
</div>
<script>

    remove = (element) => {        

        let key = element.attributes['key'].value;
       
        clearTimeout(NOTIFICACION_BOX_TIMEOUT);
       
        close_notification_message(key);
        
    }

    create_notification_dom_elements = ( message = '', color ) =>
    {

        switch (color) {
            case 'warning':
                color = '#f39d0c';
                icon_class = 'fas fa-exclamation';
                break;
            case 'error':
                color = '#f30c0c';
                icon_class = 'fas fa-times';
                break;
            case 'success':
                color = '#1f9f1d';
                icon_class = 'fas fa-check';
                break;
            default:
                color = '#f39d0c';
                icon_class = 'fas fa-exclamation';
                break;
        }

        const notification_wrapper = document.querySelector('.notification_wrapper'); 
        
        if ( sessionStorage.keyBox ) 
        {
            sessionStorage.keyBox = parseInt(sessionStorage.keyBox) + 1;
        }
        else { sessionStorage.keyBox = 1; }

        let notification_box = document.createElement('div');
            notification_box.setAttribute('class',`notificacion_box_content content box no-show box-${sessionStorage.keyBox}`);
            notification_box.setAttribute('onclick','remove(this)');
            notification_box.setAttribute('key', sessionStorage.keyBox );
            notification_box.setAttribute('style', `background-color:${color}` );
        
        let content_box  = `<div class="content_box">`;
            content_box +=   `<p class="message">${message}</p>`;
            content_box +=   `<span class="notification_icon" style="color:${color}"><i class="${icon_class}"></i></span>`;
            content_box += `</div>`;
                    
            notification_box.innerHTML = content_box;

            notification_wrapper.appendChild(notification_box);

        return sessionStorage.keyBox;
    }

    show_notification_message = ( message = '', color = 'warning', timeDuration = 35 ) => {
        
       let key = create_notification_dom_elements( message, color );

        setTimeout(() => {
            
            const notification_message = document.querySelector(`.notificacion_box_content.box-${key}`);      
                  notification_message.classList.toggle('no-show');
    
                setTimeout(() => {
                    
                notification_message.classList.toggle('expanding');
        
                let messageText = document.querySelector(`.notificacion_box_content.box-${key} .message`);
                    messageText.innerHTML = message;
        
                    const notification_time = setTimeout(() => {
            
                        notification_message.classList.toggle('expanding');
                        notification_message.classList.toggle('expanded');
            
                        NOTIFICACION_BOX_TIMEOUT = setTimeout(() => {
                            close_notification_message(key);                
                        }, timeDuration*1000 );
            
                    }, 300);
    
                }, 300);

        }, 300);
    }

    close_notification_message = (key) => {
        
        const notificacion_box = document.querySelector(`.notificacion_box_content.box-${key}`);
       
        setTimeout(() => {
            notificacion_box.classList.toggle('expanded');
            notificacion_box.classList.toggle('expanding');
    
            setTimeout(() => {

                notificacion_box.classList.toggle('expanding');
                
                let messageText = document.querySelector(`.notificacion_box_content.box-${key} .message`);

                if ( messageText) messageText.innerHTML = '';

                setTimeout(() => {
                    notificacion_box.setAttribute('class',`notificacion_box_content content box box-${key} no-show`);
                    if ( notificacion_box ) notificacion_box.remove();                    
                }, 300);

            }, 300);            
        }, 100);
           
        
    }
    
</script>