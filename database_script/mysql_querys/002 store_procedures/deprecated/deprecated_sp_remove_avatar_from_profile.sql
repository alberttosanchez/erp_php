-- drop procedure sigpromj_sp_remove_avatar_from_profile;
delimiter //
CREATE PROCEDURE sigpromj_sp_remove_avatar_from_profile
( 	
    in user_id_param int
)
begin
	DECLARE sql_error tinyint default false;     
    DECLARE counter_var int default null;    
    
    DECLARE old_date_var timestamp default null;    
    DECLARE new_date_var timestamp default null;    	
    
    DECLARE old_image_name_var varchar(256) default null;        
    DECLARE new_image_name_var varchar(256) default null;
        
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
    FROM sigpromj_users_login_info
    WHERE id = user_id_param;    
    
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
            thumbnail
		INTO 
			old_date_var,
            old_image_name_var
		FROM sigpromj_users_profile
		WHERE user_id = user_id_param;

		-- inicio de actualizacion de la ruta de la foto de perfil
		-- actualizo el perfil
		UPDATE sigpromj_users_profile
		SET 
			  thumbnail = ""
		WHERE user_id = user_id_param;
    
		-- obtenemos la nueva fecha de actualizacion
		SELECT 
			updated_at,
            thumbnail
		INTO 
			new_date_var,
            new_image_name_var
		FROM sigpromj_users_profile
		WHERE user_id = user_id_param;

		if NOT( UNIX_TIMESTAMP(new_date_var) > UNIX_TIMESTAMP(old_date_var) ) then
        
			if (
				( UNIX_TIMESTAMP(new_date_var) = UNIX_TIMESTAMP(old_date_var) ) and				
                new_image_name_var = old_image_name_var
            ) then
				set sql_error = false;
            else 
				set sql_error = true;
				select "403" as "status";
				select "registro no actualizado en profile" as "message";
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

-- call sigpromj_sp_remove_avatar_from_profile(33);