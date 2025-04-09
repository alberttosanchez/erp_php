
let app = document.querySelector('#Main__wrapper > .App__page');
    app.style.display = "none";

check_role = async () => {

    try {
        
        // peticion sincronizada al servidor
        var server = new XMLHttpRequest();
        server.open('POST', API_CHECK_ROLE_URL, false);  // `false` makes the request synchronous
        server.send(JSON.stringify({
            user_id : this.state.data.fetched.user_id
        }));

        //console.log(server);

        switch (server.status) {
            case 200:
                // show admin info                
                let app = document.querySelector('#Main__wrapper > .App__page');
                    app.style.display = "block";
                break;

            case 204:
                not_found();
                break;

            case 401:
                not_found();
                break;
        
            default:
                not_found();
                break;
        }

    } catch (error) {
        
        console.log(error);

    }
}

this.check_role();