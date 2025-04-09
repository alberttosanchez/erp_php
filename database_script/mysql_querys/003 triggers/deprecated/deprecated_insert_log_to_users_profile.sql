#-- eliminar trigger
-- DROP TRIGGER insert_log_to_users_profile;
#-- crear un trigger que lleve un log de insercion de la tabla sigpromj_users_login_info.
DELIMITER //
CREATE TRIGGER insert_log_to_users_profile
AFTER INSERT ON sigpromj_users_profile 
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
            new.user_id,
            'insert',            
            concat(
				new.first_name,'|',
				new.last_name,'|',
				new.birth_date,'|',
				new.gender_id,'|',
                new.thumbnail),
			'accion de inserccion'
		);    
	END //
DELIMITER ;