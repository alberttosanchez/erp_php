# agregado al log
CREATE TABLE IF NOT EXISTS ijoven_users_security_data (
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    user_id int not null, -- fk
		constraint ijoven_security_users
        foreign key (user_id)
        references ijoven_users_login_info(id)
        on delete cascade,
	role_id int not null default 3, -- fk -> 3 rol de usuario por defecto
		constraint ijoven_security_cat_roles
        foreign key (role_id)
        references ijoven_cat_roles(id),
	account_confirmed int not null default 0,
    account_status_id int not null default 1, -- fk --> 1 : active
		constraint ijoven_security_cat_account_status
        foreign key (account_status_id)
        references ijoven_cat_account_status(id),
    token varchar(32) default null, -- recibe un token md5 de 32 caracteres
    id_state int not null default 1,
		constraint ijoven_usdata_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)     
) engine InnoDB, auto_increment = 1;

# agregado al log
CREATE TABLE IF NOT EXISTS ijoven_users_profile(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    user_id int not null, -- fk
		constraint ijoven_profile_users
        foreign key (user_id)
        references ijoven_users_login_info(id)
        on delete cascade,
	first_name varchar(50) default "",
    last_name varchar(50) default "",
    birth_date varchar(10) default "",    
    gender_id int default 0, -- fk
		constraint ijoven_profile_gender
        foreign key (gender_id)
        references ijoven_cat_users_gender(id),
	thumbnail varchar(200) default "",
    id_state int not null default 1,
		constraint ijoven_uprofile_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)    
) engine InnoDB, auto_increment = 1;

CREATE TABLE IF NOT EXISTS ijoven_users_security_session(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    user_id int not null, -- fk
		constraint ijoven_session_users
        foreign key (user_id)
        references ijoven_users_login_info(id)
        on delete cascade,
	session_token varchar(128) default "",
    expire_session_id int default 0, -- 1 para true y 2 para false
		constraint ijoven_cat_expire_session
        foreign key (expire_session_id)
        references ijoven_cat_users_expire_session(id),
	id_state int not null default 1,
		constraint ijoven_usecsess_datastate
		foreign key (id_state)
		references ijoven_cat_data_state(id)        
) engine InnoDB, auto_increment = 1;