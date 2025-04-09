#-- eliminar trigger
-- DROP TRIGGER update_log_to_users_profile;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER update_log_to_users_profile
AFTER UPDATE ON ijoven_users_profile 
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
            'ijoven_users_profile',
            old.user_id,
            'update',            
            concat(
				old.first_name,'|',
				old.last_name,'|',
				old.birth_date,'|',
				old.gender_id,'|',
                old.thumbnail,'|',
                old.id_state),
			'accion de actualizacion'
		);    
	END //
DELIMITER ;