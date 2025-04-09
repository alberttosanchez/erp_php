CREATE OR REPLACE VIEW view_users_info as
SELECT 
	p.user_id,
    l.users_name,
    p.first_name,
    p.last_name,
    l.users_goverment_id,
    l.users_email,
    l.users_phone,
    g.gender,
    p.gender_id, --
    r.role_name, --
    sd.role_id,
    sd.account_confirmed,
    p.birth_date,
    l.id_state
	FROM ijoven_users_login_info as l 
		JOIN ijoven_users_profile as p
			ON l.id = p.user_id
				JOIN ijoven_users_security_data as sd
					ON sd.user_id = p.user_id
						JOIN ijoven_cat_roles as r
							ON r.id = sd.role_id
								JOIN ijoven_cat_users_gender as g
									ON g.id = p.gender_id
                                    where l.id_state < 3;

			