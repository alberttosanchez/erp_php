SELECT login.id, login.users_name, profile.first_name, profile.last_name, login.users_email, login.users_goverment_id
	FROM ijoven_users_login_info as login 
		JOIN ijoven_users_profile as profile ON login.id = profile.user_id
			-- WHERE profile.user_id = 1