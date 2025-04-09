#-- eliminar trigger
-- DROP TRIGGER delete_log_to_users_loging_info;
#-- crear un trigger que lleve un log de insercion de la tabla ijoven_users_login_info.
DELIMITER //
CREATE TRIGGER delete_log_to_users_loging_info
BEFORE DELETE ON ijoven_users_login_info 
# se indica el momento (after=despues)de la ejecucion y en que evento (insert) ocurrira  
FOR EACH ROW # se ejecutara por cada fila afectada
	BEGIN
        DECLARE first_name_var varchar(50) DEFAULT NULL;
        DECLARE last_name_var varchar(50) DEFAULT NULL;
        DECLARE birth_date_var varchar(10) DEFAULT NULL;
        DECLARE gender_id_var int DEFAULT NULL;
        DECLARE thumbnail_var varchar(200) DEFAULT NULL;
        DECLARE role_id_var int DEFAULT NULL;
        DECLARE account_confirmed_var int DEFAULT NULL;
        DECLARE account_status_id_var int DEFAULT NULL;
        DECLARE expire_session_id_var int DEFAULT NULL;
    
		SELECT 	p.first_name,
				p.last_name,
				p.birth_date,
				p.gender_id,
                p.thumbnail,
				sd.role_id,
                sd.account_confirmed,
                sd.account_status_id,
                ss.expire_session_id
		INTO	first_name_var,
				last_name_var,
				birth_date_var,
				gender_id_var,
                thumbnail_var,
				role_id_var,
                account_confirmed_var,
                account_status_id_var,
                expire_session_id_var
                FROM ijoven_users_profile as p 
                JOIN ijoven_users_security_data as sd 
                ON p.user_id = sd.user_id and p.id_state < 3 and sd.id_state < 3
                JOIN ijoven_users_security_session as ss
                ON sd.user_id = ss.user_id and sd.id_state < 3 and ss.id_state < 3
                WHERE p.user_id = old.id
                and p.id_state < 3;
                
		INSERT INTO ijoven_log_for_users_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'ijoven_users_login_info|profile|security_data|security_session',
            old.id,
            'delete',
            concat(
				old.users_name,'|',
				old.users_email,'|',
				old.users_goverment_id,'|',
				old.users_phone,'|',
                first_name_var,'|',
                last_name_var,'|',
                birth_date_var,'|',
                gender_id_var,'|',
                thumbnail_var,'|',
				role_id_var,'|',
                account_confirmed_var,'|',
                account_status_id_var,'|',
                expire_session_id_var,'|',
                old.id_state),
			'accion de eliminar'
		);
        
	END //
DELIMITER ;