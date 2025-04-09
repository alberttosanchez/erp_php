-- drop procedure ijoven_sp_update_user_details;
delimiter //
CREATE  PROCEDURE ijoven_sp_update_user_details
( 
	in role_id_param 			int,
    in user_id_param 			int,
    in first_name_param 		varchar(50),
    in last_name_param 			varchar(50),
    in users_goverment_id_param varchar(13),
    in users_email_param 		varchar(100),
    in users_phone_param 		varchar(20),
    in gender_id_param 			int,
    in birth_date_param 		varchar(10)
)
begin
	DECLARE sql_error tinyint default false;     
    DECLARE counter_var int default null;    
    DECLARE old_date_var timestamp default null;    
    DECLARE new_date_var timestamp default null;    
	
    DECLARE old_first_name_var 			varchar(50) default null;
    DECLARE old_last_name_var 			varchar(50) default null;
    DECLARE old_users_goverment_id_var 	varchar(13) default null;
    DECLARE old_users_email_var 		varchar(100) default null;
    DECLARE old_users_phone_var 		varchar(20) default null;
    DECLARE old_gender_id_var 			int default null;
    DECLARE old_role_id_var 			int default null;
    DECLARE old_birth_date_var 			varchar(10) default null;
    
    DECLARE new_first_name_var 			varchar(50) default null;
    DECLARE new_last_name_var 			varchar(50) default null;
    DECLARE new_users_goverment_id_var 	varchar(13) default null;
    DECLARE new_users_email_var 		varchar(100) default null;
    DECLARE new_users_phone_var 		varchar(20) default null;
    DECLARE new_gender_id_var 			int default null;
    DECLARE new_role_id_var 			int default null;
    DECLARE new_birth_date_var 			varchar(10) default null;
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable.    
    
    #-- el nivel de la transaccion es serializable (bloquea las tablas mientras se ejecuta el procedimiento almacenado)
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en al base de datos.	
    
    -- verificamos que el usuario exista
    SELECT count(*)
    INTO counter_var
    FROM ijoven_users_login_info
    WHERE id = user_id_param
    and id_state < 3;  -- (new)    
    
    if NOT( counter_var = 1 ) then
		set sql_error = true;
        select "404" as "status";
        select "Usuario no existe." as "message";
	end if;    
    -- --------------------------------------
    if ( sql_error = false ) then
    
		-- obtenemos la ultima fecha de actualizacion
		SELECT 
			updated_at, 
            role_id             
		INTO 
			old_date_var,             
            old_role_id_var
		FROM ijoven_users_security_data
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)

		-- inicio de actualizacion de datos de usuario
		-- actualizo el perfil
		UPDATE ijoven_users_security_data
		SET 
			  role_id = role_id_param		    
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
    
		-- obtenemos la nueva fecha de actualizacion
		SELECT 
			updated_at,            
            role_id
		INTO 
			new_date_var,            
            new_role_id_var
		FROM ijoven_users_security_data
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)

		if NOT( UNIX_TIMESTAMP(new_date_var) > UNIX_TIMESTAMP(old_date_var) ) then
        
			if (
				( UNIX_TIMESTAMP(new_date_var) = UNIX_TIMESTAMP(old_date_var) ) and				
                new_role_id_var = old_role_id_var
            ) then
				set sql_error = false;
            else 
				set sql_error = true;
				select "403" as "status";
				select "registro no actualizado en security data" as "message";
            end if;
        			
        end if;
        
    end if;
    -- --------------------------------------
    if ( sql_error = false ) then
    
		-- obtenemos la ultima fecha de actualizacion
		SELECT 
			updated_at, 
            first_name, 
            last_name, 
            birth_date, 
            gender_id
		INTO 
			old_date_var, 
            old_first_name_var, 
            old_last_name_var, 
            old_birth_date_var, 
            old_gender_id_var
		FROM ijoven_users_profile
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)

		-- inicio de actualizacion de datos de usuario
		-- actualizo el perfil
		UPDATE ijoven_users_profile
		SET     
			first_name 			= UPPER(first_name_param),
			last_name 			= UPPER(last_name_param),
			birth_date 			= birth_date_param,
			gender_id 			= gender_id_param		    
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)
    
		-- obtenemos la nueva fecha de actualizacion
		SELECT 
			updated_at,
            first_name, 
            last_name, 
            birth_date, 
            gender_id
		INTO 
			new_date_var,
            new_first_name_var, 
            new_last_name_var, 
            new_birth_date_var, 
            new_gender_id_var
		FROM ijoven_users_profile
		WHERE user_id = user_id_param
        and id_state < 3;  -- (new)

		if NOT( UNIX_TIMESTAMP(new_date_var) > UNIX_TIMESTAMP(old_date_var) ) then
        
			if (
				( UNIX_TIMESTAMP(new_date_var) = UNIX_TIMESTAMP(old_date_var) ) and
				new_date_var = old_date_var and
                new_first_name_var = old_first_name_var and
                new_last_name_var = old_last_name_var and
                new_birth_date_var = old_birth_date_var and
                new_gender_id_var = old_gender_id_var
            ) then
				set sql_error = false;
            else 
				set sql_error = true;
				select "403" as "status";
				select "registro no actualizado en profile" as "message";
            end if;
        			
        end if;
        
    end if;
    
    if ( sql_error = false ) then

		-- obtenemos la ultima fecha de actualizacion
		SELECT 
			updated_at,
            users_email,
            users_phone,
            users_goverment_id
		INTO 
			old_date_var,
            old_users_email_var,
            old_users_phone_var,
            old_users_goverment_id_var            
		FROM ijoven_users_login_info
		WHERE id = user_id_param
        and id_state < 3;  -- (new)
        
		-- actualizo login info
		UPDATE ijoven_users_login_info
		SET     
			users_email 		= LOWER(users_email_param),
            users_phone 		= users_phone_param,
			users_goverment_id 	= REPLACE(users_goverment_id_param,"-","")
		WHERE id = user_id_param
        and id_state < 3;  -- (new)
    
		-- obtenemos la nueva fecha de actualizacion
		SELECT 
			updated_at,
            users_email,
            users_phone,
            users_goverment_id
		INTO 
			new_date_var,
            new_users_email_var,
            new_users_phone_var,
            new_users_goverment_id_var
		FROM ijoven_users_login_info
		WHERE id = user_id_param
        and id_state < 3;  -- (new)
                
        if NOT( UNIX_TIMESTAMP(new_date_var) > UNIX_TIMESTAMP(old_date_var) ) then
        
			if (
				( UNIX_TIMESTAMP(new_date_var) = UNIX_TIMESTAMP(old_date_var) ) and
				new_date_var = old_date_var and
                new_users_email_var = old_users_email_var and
                new_users_phone_var = old_users_phone_var and
				new_users_goverment_id_var = old_users_goverment_id_var
            ) then
				set sql_error = false;
            else         
				set sql_error = true;
				select "403" as "status";
				select "registro no actualizado en login" as "message";            
            end if;
            
        end if;
    
    end if;
    
	if ( sql_error = true ) then
		rollback;
	end if;
    
    if ( sql_error = false ) then
		select "200" as "status";
        select "Datos actualizados" as "message";
		commit;
	end if;
    
end //
delimiter ;

-- call ijoven_sp_update_user_details(1,'CARLOS2','perez','005-4567895-5','aesanchez@domain.local',3,'25-12-2020');