-- drop procedure ijoven_sp_insert_log;
delimiter //
CREATE PROCEDURE ijoven_sp_insert_log( in user_name_param varchar(50), in user_email_param varchar(100), in user_pass_param varchar(64), in user_token_param varchar(128) )
begin	
	DECLARE sql_error tinyint default false; 
    DECLARE user_name_var varchar(50) default null;
    DECLARE user_email_var varchar(100) default null;        
    DECLARE user_id_var int default null;
    DECLARE user_profile_id_var int default null;    
    DECLARE user_security_data_id_var int default null;
    DECLARE user_security_session_id_var int default null;
    DECLARE log_date_id_var int default null;
    DECLARE row_counter_before_var int default null;
    DECLARE row_counter_after_var int default null;
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable.    
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en al base de datos.	
    
    -- obtiene el nombre de usuario
    SELECT users_name
	INTO user_name_var
	FROM ijoven_users_login_info WHERE users_name = user_name_param;
    
    -- obtiene el correo electronico
    SELECT users_email
	INTO user_email_var
	FROM ijoven_users_login_info WHERE users_email = user_email_param;
           
	-- verifica si no hay errores.
	if sql_error = false then		    
		-- compara el nombre de usuario con el nombre de usuario pasado por parametro
        -- si son iguales el usuario existe
		if user_name_param = user_name_var then
			set sql_error = true;				
			select "El usuario existe." as "error_msg";
		-- compara el correo electronico con el correo pasado por parametro
        -- si son iguales el correo existe
		elseif user_email_param = user_email_var then
			set sql_error = true;				
			select "El correo existe." as "error_msg";
		-- de lo contrario ingresa los parametros en la base de datos
        -- en la tabla de usuarios
		else			
			INSERT INTO ijoven_users_login_info (id,users_name,users_email,users_password)
			VALUES (default,user_name_param,user_email_param,user_pass_param); 			
		end if;
	end if;

	if sql_error = false then    
		SELECT users_name
		INTO user_name_var
		FROM ijoven_users_login_info WHERE users_name = user_name_param;   
	
		-- si los nombre de usuario no son iguales el registro no ha sido agregado
		if user_name_var != user_name_param and user_name_var = null and length(user_name_var)<1 then
			set sql_error = true;							
			select "Registro en login info no agregado." as "error_msg";
		end if;            
	end if;
	 
	 
	if sql_error = false then            
		SELECT id 
		INTO user_id_var
		FROM ijoven_users_login_info WHERE users_name = user_name_var;
		
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_date;
		
        -- si el id de usuario existe
		if NOT(user_id_var is null) OR user_id_var != "" then
			-- insertar un registro al log de fecha -> ijoven_log_date
			INSERT INTO ijoven_log_date (id,create_at)
			VALUES (default,default);
		else 
			set sql_error = true;				
			select "Registro en log date no agregado." as "error_msg";
		end if;
	end if;
	
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_log_date;
		
        -- si el conteo actual mas el conteo anterior mas 1 son iguales
		if row_counter_after_var = row_counter_before_var+1 then
			-- obten el id de log de fecha recien insertado
			SELECT id
			INTO log_date_id_var
			FROM ijoven_log_date ORDER BY id DESC LIMIT 1;
		else
			set sql_error = true;				
			select "Registro en log date no agregado." as "error_msg";
		end if;            
	end if;
	
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_relations;
		-- si el id de usuario existe y el id de log de fecha existe
		if NOT(user_id_var is null) AND NOT(log_date_id_var is null) then
			-- inserta un registro en la tabla log de relaciones para vincularlos
			INSERT INTO ijoven_log_relations (id,log_id,user_id)
			VALUES (default,log_date_id_var,user_id_var);                 
		else
			set sql_error = true;				
			select "Registro log relations no agregado." as "error_msg";
		end if;
	end if;
	
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_log_relations;
        
		 -- si el conteo actual mas el conteo anterior mas 1 NO son iguales
		if not(row_counter_after_var = row_counter_before_var+1) then				
			set sql_error = true;
			select "Registro log relations no agregado." as "error_msg";
		end if;
	end if;

	if sql_error = false then
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_users_profile;
        
		-- inserta un registro en la tabla perfil de usuario
		INSERT INTO ijoven_users_profile (id,user_id) 
		VALUES (default,user_id_var);            
							   
		SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_users_profile;
		
        -- obten el id de usuario del perfil de usuario 
		SELECT user_id
		INTO user_profile_id_var
		FROM ijoven_users_profile WHERE user_id = user_id_var;
		
        -- si el conteo actual mas el conteo anterior mas 1 NO son iguales        
		if NOT(row_counter_after_var = row_counter_before_var+1) then
			set sql_error = true;
			select "Registro en perfil no agregado." as "error_msg";
		-- si el id de usuario y el id de usuario del perfil no son iguales
		elseif NOT(user_id_var = user_profile_id_var)  then
			set sql_error = true;
			select "Registro en perfil no agregado." as "error_msg";
		end if;
	end if;
	
    # INSERTAR LOG DATE PARA PROFILE--------------------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_date;
		
        INSERT INTO ijoven_log_date (id) VALUES (default);
        
        SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_log_date;       
		        
        -- si el conteo actual mas el conteo anterior mas 1 son iguales
		if row_counter_after_var = row_counter_before_var+1 then
			-- obten el id de log de fecha recien insertado
			SELECT id
			INTO log_date_id_var
			FROM ijoven_log_date ORDER BY id DESC LIMIT 1;
		else
			set sql_error = true;				
			select "Registro en log date no agregado." as "error_msg";
		end if;            
	end if;
	#------------------------------------------------------------------------
    # REGISTRO EN LOG RELATIONS PARA PROFILE---------------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_relations;
        
		-- si el id de usuario existe y el id de log de fecha existe
		if NOT(user_id_var is null) AND NOT(log_date_id_var is null) then
			-- inserta un registro en la tabla log de relaciones para vincularlos
			INSERT INTO ijoven_log_relations (id,log_id,profile_id)
			VALUES (default,log_date_id_var,user_id_var);                 
		else
			set sql_error = true;				
			select "Registro en log-relations no agregado." as "error_msg";
		end if;
	end if;
    # -------------------------------------------------------------------
    
	if sql_error = false then
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_users_security_data;
                
		INSERT INTO ijoven_users_security_data (id,user_id,role_id,token)
		VALUES (default,user_id_var,default,user_token_param); -- default role_id = 3 -> user
		        
		SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_users_security_data;
		        
		SELECT user_id
		INTO user_security_data_id_var
		FROM ijoven_users_security_data WHERE user_id = user_id_var;
		        
		if NOT(row_counter_after_var = row_counter_before_var+1) then
			set sql_error = true;
			select "Registro en security data no agregado." as "error_msg";
		elseif NOT(user_id_var = user_security_data_id_var)  then
			set sql_error = true;
			select "Registro en security data no agregado." as "error_msg";
		end if;            
	end if;
	
    # INSERTAR LOG DATE PARA SECURITY DATA-------------------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_date;
		
        INSERT INTO ijoven_log_date (id) VALUES (default);
        
        SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_log_date;       
		        
        -- si el conteo actual mas el conteo anterior mas 1 son iguales
		if row_counter_after_var = row_counter_before_var+1 then
			-- obten el id de log de fecha recien insertado
			SELECT id
			INTO log_date_id_var
			FROM ijoven_log_date ORDER BY id DESC LIMIT 1;
		else
			set sql_error = true;				
			select "Registro en log date para security data no agregado." as "error_msg";
		end if;            
	end if;
	#------------------------------------------------------------------------
    # REGISTRO EN LOG RELATIONS PARA SECURITY DATA---------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_relations;
        
		-- si el id de usuario existe y el id de log de fecha existe
		if NOT(user_id_var is null) AND NOT(log_date_id_var is null) then
			-- inserta un registro en la tabla log de relaciones para vincularlos
			INSERT INTO ijoven_log_relations (id,log_id,security_data_id)
			VALUES (default,log_date_id_var,user_id_var);                 
		else
			set sql_error = true;				
			select "Registro en log-relations para security data no agregado." as "error_msg";
		end if;
	end if;
    # -------------------------------------------------------------------
    
    if sql_error = false then
		SELECT count(*)
		INTO row_counter_before_var
        FROM ijoven_users_security_session;
		    
		INSERT INTO ijoven_users_security_session (id,user_id,session_token,expire_session_id,update_at)
		VALUES (default,user_id_var,default,1,default); -- default expire_session = 1 -> true
				
		SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_users_security_session;
			
		SELECT user_id
		INTO user_security_session_id_var
		FROM ijoven_users_security_session WHERE user_id = user_id_var;
                
        if row_counter_before_var+1 != row_counter_after_var then
			set sql_error = true;
            select "Registro no agregado en security session." as "error_msg";            		
        end if;        
	end if;
    
     # INSERTAR LOG DATE PARA SECURITY SESSION---------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_date;		
                
        INSERT INTO ijoven_log_date (id) VALUES (default);
        
        SELECT count(*)
		INTO row_counter_after_var
		FROM ijoven_log_date;       
					
        -- si el conteo actual mas el conteo anterior mas 1 son iguales
		if row_counter_after_var = row_counter_before_var+1 then
			-- obten el id de log de fecha recien insertado
			SELECT id
			INTO log_date_id_var
			FROM ijoven_log_date ORDER BY id DESC LIMIT 1;
		else
			set sql_error = true;				
			select "Registro en log date para security session no agregado." as "error_msg";
		end if;            
	end if;
	#------------------------------------------------------------------------
    # REGISTRO EN LOG RELATIONS PARA SECURITY SESSION------------------------
	if sql_error = false then    
		SELECT count(*)
		INTO row_counter_before_var
		FROM ijoven_log_relations;
        
		-- si el id de usuario existe y el id de log de fecha existe
		if NOT(user_id_var is null) AND NOT(log_date_id_var is null) then
			-- inserta un registro en la tabla log de relaciones para vincularlos
			INSERT INTO ijoven_log_relations (id,log_id,security_session_id)
			VALUES (default,log_date_id_var,user_id_var);                 
		else
			set sql_error = true;				
			select "Registro en log-relations para security session no agregado." as "error_msg";
		end if;
	end if;
    # -------------------------------------------------------------------
	if sql_error = false then		
		commit;
		select "Registro agregado" as "true_msg";			
	end if;
	
	if sql_error = true then
		rollback;        
	end if;
		
end //
delimiter ;

call ijoven_sp_insert_log('albertosanchez','sanchez@domain.local','123456','$1-f651ef14werf74rg46rfg4er7ere7r');
