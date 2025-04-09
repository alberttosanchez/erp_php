
CREATE TABLE IF NOT EXISTS cvcat_data_state(
	id int not null auto_increment primary key,
    data_state varchar(50) not null,
    data_description varchar(200) default "",    
    data_iso varchar(10) default ""
) engine InnoDB, auto_increment = 1;

INSERT INTO cvcat_data_state (data_state,data_description,data_iso) VALUES
('active','La data esta disponible para cualquier uso.','ACT'),
('inactive','La data esta disponible pero no puede ser modificada.','ICT'),
('deleted','La data fue borrada y solo esta disponible para reportes.','DEL'),
('bloqued','La data existe en la base de datos pero no esta disponible.','BLO'),
('unavailable','La data no existe, este estado es para los log del sistema exclusivamente.','UVL');

create table if not exists cvmj_business_info (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    business_name varchar(100) default "",
    business_phone varchar(20) default "",
    business_address varchar(200) default "",
    business_zip_code varchar(20) default "",
    business_floor_quanty varchar(3) default "",
    id_state int not null default 1,
		constraint businfo_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_floor_location (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    floor_location varchar(50) default "",
    floor_iso varchar(10) default "",
    id_state int not null default 1,
		constraint floorloc_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_level_access (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    level_access varchar(10) default "",
    level_access_iso varchar(10) default "",
    id_state int not null default 1,
		constraint levelacc_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_plant_dist_filter (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    filter_name varchar(50) default "",
    filter_iso varchar(10) default "",
    id_state int not null default 1,
		constraint plandfilter_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_plant_distribution (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    department varchar(100) default "",
    floor_location_id int not null,
    constraint floor_location_to_cat
        foreign key (floor_location_id)
        references cvcat_floor_location(id),    
    level_access_id int not null,
    constraint level_access_to_cat
        foreign key (level_access_id)
        references cvcat_level_access(id),
	id_state int not null default 1,
		constraint plandist_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_genders (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    gender varchar(50) default "",
    gender_iso varchar(10) default "",
    id_state int not null default 1,
		constraint genders_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_identification_type (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    identification_type varchar(50) default "",
    identification_type_iso varchar(10) default "",
    id_state int not null default 1,
		constraint identtype_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_coworkers (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    name varchar(100) default "",
    last_name varchar(100) default "",
    gender_id int not null,
    constraint gender_to_cat
        foreign key (gender_id)
        references cvcat_genders(id), 
    identification_id varchar(30) not null unique,
    identification_type_id int not null,
    constraint identification_type_to_cat
        foreign key (identification_type_id)
        references cvcat_identification_type(id), 
    birth_date varchar(20) default "",
    photo_path text,
    id_state int not null default 1,
		constraint coworkers_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_job_info (
    id int not null auto_increment primary key,    
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    coworker_id int not null unique,
    constraint coworker_to_table
        foreign key (coworker_id)
        references cvmj_coworkers(id)
        ON DELETE CASCADE, 
    job_department_id int not null,
    constraint job_department_to_plant
        foreign key (job_department_id)
        references cvmj_plant_distribution(id), 
    job_title varchar(50) default "",
    phone_extension varchar(10) default "",
    job_email varchar(100) default "",
    id_state int not null default 1,
		constraint jobinfo_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_visitants(
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    name varchar(100) not null default "",
    last_name varchar(100) not null default "",
    gender_id int not null default 1,
        constraint gender_visitant_to_cat
            foreign key (gender_id)
            references cvcat_genders(id),    
    birth_date varchar(20) default "",
    last_visit_date timestamp default current_timestamp,
    photo_path text,
    id_state int not null default 1,
		constraint visitants_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_identification_type (
	id int not null auto_increment primary key,
	id_visitant int not null unique,
		constraint ident_type_to_visitants
            foreign key (id_visitant)
            references cvmj_visitants(id),
    ident_number varchar(30) not null,
    ident_type_id int not null,
        constraint ident_type_to_ctg
            foreign key (ident_type_id)
            references cvcat_identification_type(id),
	id_state int not null default 1,
		constraint ident_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_setting(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    printer_id_status  int not null default 1,
		constraint printer_datastate
        foreign key (printer_id_status)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

INSERT INTO cvmj_setting (printer_id_status) VALUES (1);