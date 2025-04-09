#-- eliminar trigger
-- DROP TRIGGER update_log_to_users_loging_info;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER update_log_to_users_loging_info
AFTER UPDATE ON ijoven_users_login_info 
# se indica el momento (after=despues)de la ejecucion y en que evento (update) ocurrira  
FOR EACH ROW # se ejecutara por cada fila afectada
	BEGIN
		INSERT INTO ijoven_log_for_users_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'ijoven_users_login_info',
            old.id,
            'update',
            concat(
				old.users_name,'|',
				old.users_email,'|',
				old.users_goverment_id,'|',
                old.users_phone,'|',
				old.id_state), -- (new)
			'accion de actualizacion'
		);        
	END //
DELIMITER ;