#-- eliminar trigger
-- DROP TRIGGER update_log_to_users_profile;
#-- crear un trigger que lleve un log de insercion de la tabla sigpromj_users_login_info.
DELIMITER //
CREATE TRIGGER update_log_to_users_profile
AFTER UPDATE ON sigpromj_users_profile 
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
            'sigpromj_users_profile',
            old.user_id,
            'update',            
            concat(
				old.first_name,'|',
				old.last_name,'|',
				old.birth_date,'|',
				old.gender_id,'|',
                old.thumbnail),
			'accion de actualizacion'
		);    
	END //
DELIMITER ;