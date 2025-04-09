-- drop procedure ijoven_sp_signup;
delimiter //
CREATE PROCEDURE ijoven_sp_signup
( 
	in user_name_param varchar(50), 
    in user_first_name_param varchar(50), 
    in user_last_name_param varchar(50),     
    in user_goverment_id_param varchar(13), 
    in user_gender_param int,
    in user_email_param varchar(100), 
    in user_phone_param varchar(12), 
    in user_token_param varchar(32) -- md5
)
begin	
	DECLARE sql_error tinyint default false; 
    
    DECLARE user_id_var int default null;
    DECLARE user_name_var varchar(50) default null;
    DECLARE user_first_name_var varchar(50) default null;
    DECLARE user_last_name_var varchar(50) default null;
    DECLARE user_goverment_id_var varchar(13) default null;
    DECLARE user_gender_var int default null;
    DECLARE user_email_var varchar(100) default null;
    DECLARE user_phone_var varchar(12) default null;
    DECLARE user_password_var varchar(255) default 'Mj123456'; -- debe ser cambiada
    DECLARE user_profile_id_var int default null;    
    DECLARE user_security_data_id_var int default null;
    DECLARE user_security_session_id_var int default null;
    DECLARE log_date_id_var int default null;
    DECLARE row_counter_before_var int default null;
    DECLARE row_counter_after_var int default null;
    DECLARE was_updated_var bool default false;
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable.    
    
    -- el nivel de la transaccion es serializable (bloquea las tablas mientras se ejecuta el procedimiento almacenado)
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en al base de datos.	
    
    -- eliminamos los signos de guion -
    set user_goverment_id_param = REPLACE(user_goverment_id_param,"-","");
    
    -- obtiene el nombre de usuario
    SELECT users_name
	INTO user_name_var
	FROM ijoven_users_login_info 
    WHERE users_name = user_name_param;
    -- and id_state < 3;  -- (new)
   
    -- obtiene el correo electronico
    SELECT users_email
	INTO user_email_var
	FROM ijoven_users_login_info 
    WHERE users_email = user_email_param;
    -- and id_state < 3;  -- (new)
    
    -- obtiene la cedula
    SELECT users_goverment_id
	INTO user_goverment_id_var
	FROM ijoven_users_login_info 
    WHERE users_goverment_id = user_goverment_id_param
    and id_state < 3;  -- (new)
        
	-- verifica si no hay errores.
	if sql_error = false then    
		-- compara el nombre de usuario con el nombre de usuario pasado por parametro
		-- si son iguales el usuario existe
		if user_name_param = user_name_var then			
			set sql_error = true;							
            -- 403 forbidden -> El usuario existe
            select "403" as "status"; 
		-- compara el correo electronico con el correo pasado por parametro
		-- si son iguales el correo existe
		elseif user_email_param = user_email_var then
			set sql_error = true;	            
            -- 403 forbidden -> El correo existe
            select "403" as "status"; 
        -- compara el correo electronico con el correo pasado por parametro
		-- si son iguales el correo existe
        elseif user_goverment_id_param = user_goverment_id_var then
			set sql_error = true;	            
            -- 403 forbidden -> La cedula existe
            select "403" as "status"; 
        -- de lo contrario ingresa los parametros en la base de datos
		-- en la tabla de users_login_info
        end if;
        
		if sql_error = false then
        
			-- obtiene la cedula de un usuario eliminado
			SELECT users_goverment_id
			INTO user_goverment_id_var
			FROM ijoven_users_login_info 
			WHERE users_goverment_id = user_goverment_id_param
			and id_state > 2;  -- (new)
        
			if user_goverment_id_param = user_goverment_id_var then
            
				#-- no se permitira reutilizar el nombre de usuario ni el correo electronico
                #-- de un usuario eliminado tendra que utilizar datos no existen en la BD
                #-- por otra parte el programador podra crear un CRUD para estos fines
                
				UPDATE ijoven_users_login_info 
					SET
					users_name = LOWER(user_name_param),
					users_email = LOWER(user_email_param),
					users_goverment_id = user_goverment_id_param,
					users_phone = user_phone_param,
					users_password = user_password_var,
					id_state = 1				
                WHERE users_goverment_id = user_goverment_id_param;
                
                set was_updated_var = true;
                
			else        
				INSERT INTO ijoven_users_login_info (
					id,
					users_name,
					users_email,
					users_goverment_id,
					users_phone,
					users_password,
					id_state
				)
				VALUES (
					default,
					LOWER(user_name_param),
					LOWER(user_email_param),
					user_goverment_id_param,
					user_phone_param,
					user_password_var,
					default
				); 
			end if;
            
            if sql_error = true then
				select '409' as "state";
                -- significa que esta insertando informacion en campos con unique key
			end if;
            
		end if;
	end if;
  
	if sql_error = false then    
		SELECT id,users_name
		INTO user_id_var, user_name_var
		FROM ijoven_users_login_info 
        WHERE users_name = user_name_param
        and id_state < 3;  -- (new)   
	
		-- si los nombre de usuario no son iguales el registro no ha sido agregado
		if user_name_var != user_name_param and ISNULL(user_name_var) and length(user_name_var)<1 then
			set sql_error = true;			
            -- 409 Conflict -> Registro en login info no agregado.
            select "409" as "status";  
		end if;            
	end if;
	-- 
    if sql_error = false and was_updated_var = false then
    
		if sql_error = false then
			SELECT count(*)
			INTO row_counter_before_var
			FROM ijoven_users_profile
			where id_state < 3;  -- (new)
			
			-- inserta un registro en la tabla perfil de usuario
			INSERT INTO ijoven_users_profile (
				id,
				user_id,
				first_name,
				last_name,
				gender_id
				) 
			VALUES (
				default,
				user_id_var,
				UPPER(user_first_name_param),
				UPPER(user_last_name_param),
				user_gender_param
			);            
			
			SELECT count(*)
			INTO row_counter_after_var
			FROM ijoven_users_profile
			where id_state < 3;  -- (new)
					
			-- obten el id de usuario del perfil de usuario 
			SELECT user_id
			INTO user_profile_id_var
			FROM ijoven_users_profile 
			WHERE user_id = user_id_var
			and id_state < 3;  -- (new)
			
			-- si el conteo actual mas el conteo anterior mas 1 NO son iguales        
			if NOT(row_counter_after_var = row_counter_before_var+1) then
				set sql_error = true;			
				-- 409 Conflict -> Registro en perfil no agregado.
				select "409" as "status";  
			-- si el id de usuario y el id de usuario del perfil no son iguales
			elseif NOT(user_id_var = user_profile_id_var)  then
				set sql_error = true;			
				-- 409 Conflict -> Registro en perfil no agregado.
				select "409" as "status";  
			end if;
		end if;
		
		if sql_error = false then
			SELECT count(*)
			INTO row_counter_before_var
			FROM ijoven_users_security_data
			where id_state < 3;  -- (new)
					
			INSERT INTO ijoven_users_security_data (
				id,
				user_id,
				role_id,
				account_status_id,
				token
			)
			VALUES (
				default,
				user_id_var,
				default, -- default role_id = 3 -> user
				default, -- default estatus = 1 -> active
				user_token_param -- token md5 de 32 caracteres
			); 
					
			SELECT count(*)
			INTO row_counter_after_var
			FROM ijoven_users_security_data
			where id_state < 3;  -- (new)
					
			SELECT user_id
			INTO user_security_data_id_var
			FROM ijoven_users_security_data 
			WHERE user_id = user_id_var
			and id_state < 3;  -- (new)
					
			if NOT(row_counter_after_var = row_counter_before_var+1) then
				set sql_error = true;			
				-- 409 Conflict -> Registro en security data no agregado.
				select "409" as "status";  
			elseif NOT(user_id_var = user_security_data_id_var)  then
				set sql_error = true;			
				-- 409 Conflict -> Registro en security data no agregado.
				select "409" as "status";  
			end if;            
		end if;
		
		if sql_error = false then
			SELECT count(*)
			INTO row_counter_before_var
			FROM ijoven_users_security_session
			where id_state < 3;  -- (new)
                
			INSERT INTO ijoven_users_security_session (
				id,
				user_id,
				session_token,
				expire_session_id
			)
			VALUES (
				default,
				user_id_var,
				default,
				2 -- default expire_session = 2 -> expirada
			); 

			SELECT count(*)
			INTO row_counter_after_var
			FROM ijoven_users_security_session
			where id_state < 3;  -- (new)
        
			SELECT user_id
			INTO user_security_session_id_var
			FROM ijoven_users_security_session 
			WHERE user_id = user_id_var
			and id_state < 3;  -- (new)
                
			if row_counter_before_var+1 != row_counter_after_var then
				set sql_error = true;
				-- 403 forbidden -> el usuario existe
				select "403" as "status";            		
			end if;        
		end if;
    
    elseif sql_error = false and was_updated_var = true then
    
		#-- se obtiene el id del usuario reactivado
		SELECT id
		INTO user_id_var
		FROM ijoven_users_login_info 
        WHERE users_goverment_id = user_goverment_id_param
        and id_state < 3;  -- (new) 
    
		#-- se actualizan las otras tablas a estado 1
		UPDATE ijoven_users_profile 
		SET id_state = 1
		WHERE user_id = user_id_var;
		
		UPDATE ijoven_users_security_data 
		SET id_state = 1
		WHERE user_id = user_id_var;
		
		UPDATE ijoven_users_security_session
		SET id_state = 1
		WHERE user_id = user_id_var;
    
		if sql_error = true then
			select '409' as "state";
			-- significa que el id de usuario no se encontro en las tablas
		end if;
        
    end if;
    -- 
	if sql_error = false then		
		commit;
        -- 200 OK -> transaccion correcta
		select "200" as "status";			
	end if;
	
	if sql_error = true then		
		rollback;        
	end if;
		
end //
delimiter ;
/*
call ijoven_sp_signup(
	'alberto.sanchez',
    'Alberto',
    'Sanchez',
    '402-0058185-7',
    '1',
    'albertosanchez@domain.local',
    '809-558-1121',
    '$2y$10$Ji3qD4q69SPywZlROXZkG.F7IDfUIAd72rafUbBNfwWIwVdS2lmbq'
    );
*/