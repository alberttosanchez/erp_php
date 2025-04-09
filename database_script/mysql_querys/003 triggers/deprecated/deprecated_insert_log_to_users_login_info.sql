#-- eliminar trigger
-- DROP TRIGGER insert_log_to_users_loging_info;
#-- crear un trigger que lleve un log de insercion de la tabla sigpromj_users_login_info.
DELIMITER //
CREATE TRIGGER insert_log_to_users_loging_info
AFTER INSERT ON sigpromj_users_login_info 
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
            'sigpromj_users_login_info',
            new.id,
            'insert',            
            concat(
				new.users_name,'|',
				new.users_email,'|',
				new.users_goverment_id,'|',
				new.users_phone),
			'accion de inserccion'
		);    
	END //
DELIMITER ;