sign_out = async () => {
    console.log('sign-out');

    try {
        const server = await fetch( API_SIGN_OUT_URL ,{
            method: "POST",
            headers: {                                
                'Content-Type': 'application/json'       
            },
            body: JSON.stringify({
                destroy_session : true,
                user_id: localStorage.user_id
            })
        });
        
        const response = await server.text();
        console.log(response);

        if (server.status == 200)
        {
            localStorage.clear();
            go_home();
        }        
        else
        {
            go_home();
        }

    } catch (error) {
        
        console.log(error);
        go_home();

    }

}
this.sign_out();