#-- eliminar trigger
-- DROP TRIGGER update_log_to_security_data;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER update_log_to_security_data
AFTER UPDATE ON ijoven_users_security_data
# se indica el momento (after=despues)de la ejecucion y en que evento (insert) ocurrira  
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
            'ijoven_users_security_data',
            old.user_id,
            'update',            
            concat(
				old.role_id,'|',
				old.account_confirmed,'|',
				old.account_status_id,'|',
                old.id_state), -- (new)
			'accion de actualizacion'
		);    
	END //
DELIMITER ;