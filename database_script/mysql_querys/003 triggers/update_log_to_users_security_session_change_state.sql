#-- eliminar trigger
-- DROP TRIGGER update_log_to_users_security_session;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER update_log_to_users_security_session
AFTER UPDATE ON ijoven_users_security_session
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
            'ijoven_users_security_session',
            old.user_id,
            'update',            
            concat(
            old.expire_session_id,'|',
            old.id_state),
			'accion de actualizacion'
		);    
	END //
DELIMITER ;