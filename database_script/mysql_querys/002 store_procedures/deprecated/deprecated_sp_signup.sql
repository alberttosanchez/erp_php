-- drop procedure sigpromj_sp_signup;
delimiter //
CREATE PROCEDURE sigpromj_sp_signup
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
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable.    
    
    -- el nivel de la transaccion es serializable (bloquea las tablas mientras se ejecuta el procedimiento almacenado)
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en al base de datos.	
    
    -- obtiene el nombre de usuario
    SELECT users_name
	INTO user_name_var
	FROM sigpromj_users_login_info WHERE users_name = user_name_param;
    
    -- obtiene el correo electronico
    SELECT users_email
	INTO user_email_var
	FROM sigpromj_users_login_info WHERE users_email = user_email_param;
    
    -- obtiene la cedula
    SELECT users_goverment_id
	INTO user_goverment_id_var
	FROM sigpromj_users_login_info WHERE users_goverment_id = user_goverment_id_param;
           
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
		else
        
			INSERT INTO sigpromj_users_login_info (
				id,
                users_name,
                users_email,
                users_goverment_id,
                users_phone,
                users_password
			)
			VALUES (
				default,
                LOWER(user_name_param),
                LOWER(user_email_param),
                REPLACE(user_goverment_id_param,"-",""),
                user_phone_param,
                user_password_var
			); 			        
		end if;
	end if;

	if sql_error = false then    
		SELECT id,users_name
		INTO user_id_var, user_name_var
		FROM sigpromj_users_login_info WHERE users_name = user_name_param;   
	
		-- si los nombre de usuario no son iguales el registro no ha sido agregado
		if user_name_var != user_name_param and ISNULL(user_name_var) and length(user_name_var)<1 then
			set sql_error = true;			
            -- 409 Conflict -> Registro en login info no agregado.
            select "409" as "status";  
		end if;            
	end if;
	
	if sql_error = false then
		SELECT count(*)
		INTO row_counter_before_var
		FROM sigpromj_users_profile;
        
		-- inserta un registro en la tabla perfil de usuario
		INSERT INTO sigpromj_users_profile (
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
		FROM sigpromj_users_profile;
		        
        -- obten el id de usuario del perfil de usuario 
		SELECT user_id
		INTO user_profile_id_var
		FROM sigpromj_users_profile 
        WHERE user_id = user_id_var;
		
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
		FROM sigpromj_users_security_data;
                
		INSERT INTO sigpromj_users_security_data (
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
		FROM sigpromj_users_security_data;
		        
		SELECT user_id
		INTO user_security_data_id_var
		FROM sigpromj_users_security_data WHERE user_id = user_id_var;
		        
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
        FROM sigpromj_users_security_session;
                
		INSERT INTO sigpromj_users_security_session (
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
		FROM sigpromj_users_security_session;
        
		SELECT user_id
		INTO user_security_session_id_var
		FROM sigpromj_users_security_session WHERE user_id = user_id_var;
                
        if row_counter_before_var+1 != row_counter_after_var then
			set sql_error = true;
            -- 403 forbidden -> el usuario existe
            select "403" as "status";            		
        end if;        
	end if;
    
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

call sigpromj_sp_signup(
	'albertOsanchez',
    'Alberto',
    'Sanchez',
    '402-0040093-1',
    '1',
    'aesanchez@domain.local',
    '809-558-4515',
    '25d55ad283aa400af464c76d713c07ad'
    );