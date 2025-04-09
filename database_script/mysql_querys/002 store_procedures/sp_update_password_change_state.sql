-- drop procedure ijoven_sp_update_password;
delimiter //
CREATE PROCEDURE ijoven_sp_update_password
( 
    in new_password_param varchar(255) , 
    in user_id_param int 
)
begin
	DECLARE sql_error tinyint default false;     
    DECLARE old_hash_var varchar(255) default null;
    DECLARE new_hash_var varchar(255) default null;
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
		
        -- obtengo la nueva fecha de actualizacion
        SELECT users_password
        INTO old_hash_var
        FROM ijoven_users_login_info
        WHERE id = user_id_param
        and id_state < 3;  -- (new)
        
        -- actualizo el password
		UPDATE ijoven_users_login_info
		SET users_password = new_password_param
		WHERE id = user_id_param
        and id_state < 3;  -- (new)
        
        -- obtengo la nueva fecha de actualizacion
        SELECT users_password
        INTO new_hash_var
        FROM ijoven_users_login_info
        WHERE id = user_id_param
        and id_state < 3;  -- (new)
		
        if NOT(old_hash_var != new_hash_var) or length(new_hash_var) < 60 then
			set sql_error = true;	
            select "403" as "status";
			select "Contraseña no actualizada." as "message";
		end if;
        
    end if;
    
    if sql_error = false then		
		commit;
        select "200" as "status";
		select "Contraseña actualizada" as "message";			
	end if;
	
	if sql_error = true then
		rollback;         
	end if;
    
end //
delimiter ;

-- call ijoven_sp_update_password('$2y$10$q9gIxLqcSezkGu.or8bjPu60v14kMxjtqBYZnB1JXElrWWDCUi1i2',1);