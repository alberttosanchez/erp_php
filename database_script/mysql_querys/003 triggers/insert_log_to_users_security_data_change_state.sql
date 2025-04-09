#-- eliminar trigger
-- DROP TRIGGER insert_log_to_users_security_data;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER insert_log_to_users_security_data
AFTER INSERT ON ijoven_users_security_data
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
            new.user_id,
            'insert',            
            concat(
				new.role_id,'|',
				new.account_confirmed,'|',
				new.account_status_id,'|',
                new.id_state
				),
			'accion de inserccion'
		);    
	END //
DELIMITER ;