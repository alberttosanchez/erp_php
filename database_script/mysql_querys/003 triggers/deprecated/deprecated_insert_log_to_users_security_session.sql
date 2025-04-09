#-- eliminar trigger
-- DROP TRIGGER insert_log_to_users_security_session;
#-- crear un trigger que lleve un log de insercion de la tabla sigpromj_users_login_info.
DELIMITER //
CREATE TRIGGER insert_log_to_users_security_session
AFTER INSERT ON sigpromj_users_security_session 
# se indica el momento (after=despues)de la ejecucion y en que evento (insert) ocurrira  
FOR EACH ROW # se ejecutara por cada fila afectada
	BEGIN
		INSERT INTO sigpromj_log_for_users_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'sigpromj_users_security_session',
            new.user_id,
            'insert',            
            concat(new.expire_session_id),
			'accion de inserccion'
		);    
	END //
DELIMITER ;