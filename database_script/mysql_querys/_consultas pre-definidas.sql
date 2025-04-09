-- consulta para obtener el log de ijoven_users_login_info (usuarios) de roles de usuarios
SELECT * FROM ijoven_users_login_info as user JOIN ijoven_log_relations as rel
	ON user.id = rel.user_id JOIN ijoven_log_date as log
		WHERE log.id = rel.log_id;
        -- ON log.id = rel.log_id
        -- WHERE user_id = 2		

-- consulta para obtener el log de ijoven_cat_roles (roles) de usuarios
SELECT * FROM ijoven_cat_roles as rol JOIN ijoven_log_relations as rel
	ON rol.id = rel.cat_id JOIN ijoven_log_date as log
		WHERE log.id = rel.log_id;
		
    
