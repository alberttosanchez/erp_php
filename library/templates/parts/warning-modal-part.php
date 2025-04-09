<script>

    warningModal = (action = 'close',title = 'AVISO',message) => {
    
        const modal = document.querySelector('#modal');
        
        // sebe asegurarse que exista el elemento del querySelector.
        const warnBox = document.querySelector('#modal > .warning_messageWrapper');

        if (action === 'open')
        {            
        
            if (warnBox)
            {                                
                warnBox.setAttribute('class','warning_messageWrapper');
            }

            const   msgTitleBox = document.querySelector('#modal .title');
                    msgTitleBox.innerHTML = title;

            const   msgBodyBox = document.querySelector('#modal .message');
                    msgBodyBox.innerHTML = message;
        }
        else
        {
            if (warnBox)
            {                                
                warnBox.setAttribute('class','warning_messageWrapper no-show');
            }
        }
    };

    window.addEventListener('DOMContentLoaded', warningModal );

</script>

<div id="modal">        
    <div class="warning_messageWrapper no-show">
        <div class="warning_messageWrap">
            <div class="warning_msgHeader">
                <p class="title"></p>
                <div class="closeBtn">
                    <button onclick="handleManageUsersClick(this)" name="confirmWarning" value="false" ></button>
                </div>
            </div>
            <div class="warning_msgBody">
                <p class="message"></p>
            </div>
            <div class="warning_msgFoo">
                <button onclick="handleManageUsersClick(this)" name="confirmWarning" value="true" class="btn btn-warning">Aceptar</button>
                <button onclick="handleManageUsersClick(this)" name="confirmWarning" value="false" class="btn btn-primary">Cancelar</button>
            </div>
        </div>
    </div>
</div>