-- drop procedure ijoven_sp_insert_security_token;
delimiter //
CREATE PROCEDURE ijoven_sp_insert_security_token
( 
    in security_token_param varchar(32) , 
    in user_id_param int 
)
begin
	DECLARE sql_error tinyint default false; 
    DECLARE old_security_token_var varchar(32) default null;
    DECLARE new_security_token_var varchar(32) default null;
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
		SELECT token 
        INTO old_security_token_var
        FROM ijoven_users_security_data
        WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
		
        if ISNULL(old_security_token_var) then
			set sql_error = true;
            select '403' as 'status';
			select "old_token no encontrado." as "message";
		end if;
    end if;
    
    if sql_error = false then
    
		-- obtengo el token viejo
		SELECT user_id 
        INTO user_id_var
        FROM ijoven_users_security_data
        WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
		
        if ISNULL(user_id_var) then
			set sql_error = true;
            select '404' as 'status';
			select "user_id no encontrado." as "message";
		end if;
    end if;
    
    if sql_error = false then
        
        -- actualizo el token
		UPDATE ijoven_users_security_data
		SET token = security_token_param
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
        
        -- obtengo el token nuevo
        SELECT token , user_id
        INTO new_security_token_var, user_id_var
        FROM ijoven_users_security_data
        WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
        
        if ISNULL(new_security_token_var) then
			set sql_error = true;
            select '404' as 'status';
			select "new_token no encontrado." as "message";
		end if;        
    end if;
    
    if sql_error = false then
		if (old_security_token_var = new_security_token_var) then
			set sql_error = true;
            select '403' as 'status';
			select "token no actualizado." as "message";
        end if;
    end if;
    
    if sql_error = false then
		if (user_id_var != user_id_param) then
			set sql_error = true;
            select '403' as 'status';
			select "user_id diferentes." as "message";
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

-- call ijoven_sp_insert_security_token('55fd775037175f12d9bc0d63c3f8fd12',1);