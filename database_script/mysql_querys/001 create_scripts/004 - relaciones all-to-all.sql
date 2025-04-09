
/*
-- Entidad debil
CREATE TABLE IF NOT EXISTS ijoven_log_users_login
(
	id int not null auto_increment primary key,
    users_id int not null,
		constraint users_login_log
        foreign key (users_id)
        references ijoven_users_login_info(id),
	log_id int not null,
		constraint log_users_login
        foreign key (log_id)
        references ijoven_log(id)
) engine InnoDB, auto_increment = 1;

-- Entidad debil
CREATE TABLE IF NOT EXISTS ijoven_log_users_profile
(
	id int not null auto_increment primary key,
    profiles_id int not null,
		constraint users_profile_log
        foreign key (profile_id)
        references ijoven_users_profile(id),
	log_id int not null,
		constraint log_users_profile
        foreign key (log_id)
        references ijoven_log(id)
) engine InnoDB, auto_increment = 1;

-- Entidad debil
CREATE TABLE IF NOT EXISTS ijoven_log_users_security_data
(
	id int not null auto_increment primary key,
    security_data_id int not null,
		constraint users_sec_data_log
        foreign key (security_data_id)
        references ijoven_users_security_data(id),
	log_id int not null,
		constraint log_users_sec_data
        foreign key (log_id)
        references ijoven_log(id)
) engine InnoDB, auto_increment = 1;

-- Entidad debil
CREATE TABLE IF NOT EXISTS ijoven_log_users_security_session
(
	id int not null auto_increment primary key,
    security_session_id int not null,
		constraint users_sec_session_log
        foreign key (security_session_id)
        references ijoven_users_security_session(id),
	log_id int not null,
		constraint log_users_sec_session
        foreign key (log_id)
        references ijoven_log(id)
) engine InnoDB, auto_increment = 1;
*/