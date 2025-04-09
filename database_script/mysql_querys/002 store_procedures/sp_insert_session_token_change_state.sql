-- drop procedure ijoven_sp_insert_session_token;
delimiter //
CREATE PROCEDURE ijoven_sp_insert_session_token
( 
    in session_token_param varchar(100) , 
    in user_id_param int 
)
begin
	DECLARE sql_error tinyint default false; 
    DECLARE old_session_token_var varchar(100) default null;
    DECLARE new_session_token_var varchar(100) default null;
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
    
		-- obtengo el token viejo
		SELECT session_token 
        INTO old_session_token_var
        FROM ijoven_users_security_session 
        WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
       
        -- actualizo el token
		UPDATE ijoven_users_security_session 
		SET session_token = session_token_param, expire_session_id = 1
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
        
        -- obtengo el token nuevo
        SELECT session_token 
        INTO new_session_token_var
        FROM ijoven_users_security_session 
        WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
        
        if old_session_token_var = new_session_token_var or length(new_session_token_var) != 9 then
			set sql_error = true;
            select '403' as 'status';
			select "Registro en security_session no actualizado." as "message";		
		end if;
    
    end if;
    
    if sql_error = false then		
		commit;
        select '200' as 'status';
		select "Registro actualizado" as "message";			
	end if;
	
	if sql_error = true then
		rollback;        
	end if;
    
end //
delimiter ;

-- call ijoven_sp_insert_token('987654321',4);