DELIMITER //
CREATE TRIGGER update_log_to_cvmj_business_info
AFTER UPDATE ON cvmj_business_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_business_info',
            user_id_var,
            'update',            
            concat(
				old.business_name,'|',
				old.business_address,'|',
				old.business_zip_code,'|',
				old.business_floor_quanty),
			'accion de actualizar'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_business_info
AFTER INSERT ON cvmj_business_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_business_info',
            user_id_var,
            'insert',            
            concat(
				new.business_name,'|',
				new.business_address,'|',
				new.business_zip_code,'|',
				new.business_floor_quanty),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_business_info
BEFORE DELETE ON cvmj_business_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_business_info',
            user_id_var,
            'delete',            
            concat(
				old.business_name,'|',
				old.business_address,'|',
				old.business_zip_code,'|',
				old.business_floor_quanty),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_log_to_cvmj_plant_distribution
AFTER UPDATE ON cvmj_plant_distribution 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_plant_distribution',
            user_id_var,
            'update',            
            concat(
				old.department,'|',
				old.floor_location_id,'|',
				old.level_access_id),
			'accion de actualizar'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_plant_distribution
AFTER INSERT ON cvmj_plant_distribution 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_plant_distribution',
            user_id_var,
            'insert',            
            concat(
				new.department,'|',
				new.floor_location_id,'|',
				new.level_access_id),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_plant_distribution
BEFORE DELETE ON cvmj_plant_distribution 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        

        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_plant_distribution',
            user_id_var,
            'delete',            
            concat(
				old.department,'|',
				old.floor_location_id,'|',
				old.level_access_id),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_log_to_cvmj_coworkers
AFTER UPDATE ON cvmj_coworkers 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_coworkers',
            user_id_var,
            'update',            
            concat(
                old.name,'|',
                old.last_name,'|',
                old.gender_id,'|',                
                old.identification_id,'|',
                old.identification_type_id,'|',                
                old.birth_date),
			'accion de actualizar'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_coworkers
AFTER INSERT ON cvmj_coworkers 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_coworkers',
            user_id_var,
            'insert',            
            concat(
                new.name,'|',
                new.last_name,'|',
                new.gender_id,'|',                
                new.identification_id,'|',
                new.identification_type_id,'|',                
                new.birth_date),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_coworkers
BEFORE DELETE ON cvmj_coworkers 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        

        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_coworkers',
            user_id_var,
            'delete',            
            concat(
                old.name,'|',
                old.last_name,'|',
                old.gender_id,'|',                
                old.identification_id,'|',
                old.identification_type_id,'|',                
                old.birth_date),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_log_to_cvmj_job_info
AFTER UPDATE ON cvmj_job_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_job_info',
            user_id_var,
            'update', 
            concat(
                old.coworker_id,'|',
                old.job_department_id,'|',
                old.phone_extension,'|',                
                old.job_email),
			'accion de actualizacion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_job_info
AFTER INSERT ON cvmj_job_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_job_info',
            user_id_var,
            'insert', 
            concat(
                new.coworker_id,'|',
                new.job_department_id,'|',
                new.phone_extension,'|',                
                new.job_email),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_job_info
BEFORE DELETE ON cvmj_job_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_job_info',
            user_id_var,
            'delete',            
            concat(
                old.coworker_id,'|',
                old.job_department_id,'|',
                old.phone_extension,'|',                
                old.job_email),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_log_to_cvmj_visitants
AFTER UPDATE ON cvmj_visitants 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visitants',
            user_id_var,
            'update', 
            concat(
                old.name,'|',
                old.last_name,'|',
                old.gender_id,'|',                   
                old.birth_date,'|',
                old.last_visit_date,'|',
                old.photo_path),
			'accion de actualizar'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_visitants
AFTER INSERT ON cvmj_visitants 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visitants',
            user_id_var,
            'insert', 
            concat(
                new.name,'|',
                new.last_name,'|',
                new.gender_id,'|',                   
                new.birth_date,'|',
                new.last_visit_date,'|',
                new.photo_path),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_visitants
BEFORE DELETE ON cvmj_visitants 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visitants',
            user_id_var,
            'delete',
            concat(
                old.name,'|',
                old.last_name,'|',
                old.gender_id,'|',                   
                old.birth_date,'|',
                old.last_visit_date,'|',
                old.photo_path),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_log_to_cvmj_visit_info
AFTER UPDATE ON cvmj_visit_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visit_info',
            user_id_var,
            'update',
            concat(
                old.started_at,'|',
                old.ended_at,'|',
                old.week_day_id,'|',
                old.visitant_id,'|',
                old.coworker_id,'|',
                old.level_access_id,'|',
                old.has_gun,'|',
                old.gun_status_id,'|',
                old.reason_of_visit_id,'|',
                old.license_number,'|',
                old.license_type_id,'|',
                old.start_comments,'|',
                old.end_comments),
			'accion de actualizar'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_log_to_cvmj_visit_info
AFTER INSERT ON cvmj_visit_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;

		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visit_info',
            user_id_var,
            'insert',
            concat(
                new.started_at,'|',
                new.ended_at,'|',
                new.week_day_id,'|',
                new.visitant_id,'|',
                new.coworker_id,'|',
                new.level_access_id,'|',
                new.has_gun,'|',
                new.gun_status_id,'|',
                new.reason_of_visit_id,'|',
                new.license_number,'|',
                new.license_type_id,'|',
                new.start_comments,'|',
                new.end_comments),
			'accion de inserccion'
		);    
	END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_log_to_cvmj_visit_info
BEFORE DELETE ON cvmj_visit_info 

FOR EACH ROW
	BEGIN
        DECLARE user_id_var int DEFAULT NULL;        
        
        SELECT user_id 
            INTO user_id_var
            FROM sigpromj_database.sigpromj_users_security_session 
            WHERE updated_at 
            ORDER BY updated_at DESC LIMIT 1;
	
		INSERT INTO cvmj_log_for_tables (
			table_name,
            user_id,
			action_done,
            data_before_action,
            log_description
            )
			VALUES (
            'cvmj_visit_info',
            user_id_var,
            'delete',
            concat(
                old.started_at,'|',
                old.ended_at,'|',
                old.week_day_id,'|',
                old.visitant_id,'|',
                old.coworker_id,'|',
                old.level_access_id,'|',
                old.has_gun,'|',
                old.gun_status_id,'|',
                old.reason_of_visit_id,'|',
                old.license_number,'|',
                old.license_type_id,'|',
                old.start_comments,'|',
                old.end_comments),
			'accion de eliminar'
		);    
        
	END //
DELIMITER ;