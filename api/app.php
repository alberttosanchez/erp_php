<?php
    require_once('./functions.php');
    
    if( $_SERVER["REQUEST_METHOD"] == "POST")
    {    
        $jsonString = file_get_contents('php://input');
        $jsonObject = json_decode($jsonString);  
        $jsonObject->session_id = cleanData($jsonObject->session_id);    
        
        // si no hay sesion la inicia.
        session_id($jsonObject->session_id);
        if (session_id() == "")
        {            
            session_start();
        }
        
        echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "no-session";
    }
    else    
    {           
        die();
    }