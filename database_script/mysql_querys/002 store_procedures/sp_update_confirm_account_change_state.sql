-- drop procedure ijoven_sp_confirm_account;
delimiter //
CREATE PROCEDURE ijoven_sp_confirm_account
( 
    in user_email_param varchar(100) 
)
begin
	DECLARE sql_error tinyint default false;     
    DECLARE old_status_var int default null;
    DECLARE new_status_var int default null;    
    DECLARE user_id_var int default null;    
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable. 
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en al base de datos.	
	
    -- verifica si no hay errores.
	if sql_error = false then
		
        -- obtengo el id de usuario
        SELECT id
        INTO user_id_var
        FROM ijoven_users_login_info
        WHERE users_email = user_email_param
        and id_state < 3;  -- (new)
        
        -- obtengo el estado de confirmacion previo
        SELECT account_confirmed
        INTO old_status_var
        FROM ijoven_users_security_data
        WHERE user_id = user_id_var
        and id_state < 3;  -- (new)
        
        -- actualizo el password
		UPDATE ijoven_users_security_data
		SET account_confirmed = 1
		WHERE user_id = user_id_var
        and id_state < 3;  -- (new)
        
         -- obtengo el estado de confirmacion posterior
        SELECT account_confirmed
        INTO new_status_var
        FROM ijoven_users_security_data
        WHERE user_id = user_id_var
        and id_state < 3;  -- (new)
		
        if NOT(old_status_var != new_status_var) then
			set sql_error = true;							            
            -- 403 Forbiden cuenta no confirmada
			select "403" as "status";
		end if;
        
    end if;
    
    if sql_error = false then		
		commit;
        -- 200 OK cuenta actualizada
		select "200" as "status";			
	end if;
	
	if sql_error = true then
		rollback;         
	end if;
    
end //
delimiter ;

-- call ijoven_sp_confirm_account('aesanchez@juventud.gob.do');