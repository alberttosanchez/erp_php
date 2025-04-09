<script>
    
    check_session = async () =>    
    {
        console.log("check_session");
        try {
            
            const server = await fetch( API_SESSION_URL ,{
                method: "POST",
                headers: {                                
                    'Content-Type': 'application/json'       
                },
                body: JSON.stringify({
                    target          : localStorage.target, 
                    user_id         : localStorage.user_id,
                    session_token   : localStorage.session_token
                })              
            });
            
            //const response = await server.text();
            const response = await server.json();
            //console.log(response);
            // verifica si el estado del servidor es 200
            if ( server.status == 200 )
            {
                let app = document.querySelector('#Main__wrapper > .App__page');
                    app.style.display = "block";
            }                        
            else if ( server.status !== 200 )            
            {
                not_found();
            }

        } catch (error) {

            console.log(error);
            not_found();

        }
    
    }

    window.addEventListener('DOMContentLoaded', this.check_session() );    
    
</script>