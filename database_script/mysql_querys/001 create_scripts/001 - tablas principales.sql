CREATE SCHEMA IF NOT EXISTS ijoven_database;

USE ijoven_database;

CREATE TABLE IF NOT EXISTS ijoven_cat_data_state(
	id int not null auto_increment primary key,
    data_state varchar(50) not null,
    data_description varchar(200) default "",    
    data_iso varchar(10) default ""
) engine InnoDB, auto_increment = 1;

# no se debe crear un CRUD (Create, Read, Update, Delete) para esta tabla
INSERT INTO ijoven_cat_data_state (data_state,data_description,data_iso) VALUES
('active','La data esta disponible para cualquier uso.','ACT'),
('inactive','La data esta disponible pero no puede ser modificada.','ICT'),
('deleted','La data fue borrada y solo esta disponible para reportes.','DEL'),
('bloqued','La data existe en la base de datos pero no esta disponible.','BLO'),
('unavailable','La data no existe, este estado es para los log del sistema exclusivamente.','UVL');


# agregada al log
CREATE TABLE IF NOT EXISTS ijoven_users_login_info (
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    users_name varchar(50) not null unique,
    users_email varchar(100) not null unique,
    users_goverment_id varchar(11) not null unique, -- cedula de identidad    
    users_phone varchar(12) default "", -- teléfono movil
    users_password varchar(255) not null,
    id_state int not null default 1,    
		constraint ijoven_logininfo_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)    
) engine InnoDB, auto_increment = 1;

# agregada al log
CREATE TABLE IF NOT EXISTS ijoven_log_for_users_tables (
	id int not null auto_increment primary key,    
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    table_name varchar(100) not null,
    user_id int not null,    
    action_done varchar(30) not null, -- update or delete
    data_before_action varchar(250) default null,
    log_description varchar(200) default null,
    id_state int not null default 1,    
		constraint ijoven_logforusers_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)   
) engine InnoDB, auto_increment = 1;

create table if not exists ijoven_log_for_users_actions (
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),   
    user_id int not null,        
    log_description text,    
    root text
) engine InnoDB auto_increment = 1;