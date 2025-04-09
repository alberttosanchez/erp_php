
create table if not exists cvcat_visit_reason(
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    reason_of_visit varchar(50) default "",
    reason_iso varchar(10) default "",
    id_state int not null default 1,
		constraint vistreason_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_license_type (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    license_type varchar(50) default "",
    license_iso varchar(10) default "",
    id_state int not null default 1,
		constraint licentype_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_gun_status (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    gun_status varchar(50) default "",
    gun_status_iso varchar(10) default "",
    id_state int not null default 1,
		constraint gunstatus_datastate
		foreign key (id_state)
		references cvcat_data_state(id)
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_week_days (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    week_day varchar(30) default "",
    week_day_iso varchar(10) default "",
    week_day_key varchar(10) default "",
    id_state int not null default 1,
		constraint weekdays_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvcat_guns_license (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    gun_license varchar(50) default "",
    gun_license_iso varchar(10) default "",
    id_state int not null default 1,
		constraint gunslicense_datastate
		foreign key (id_state)
		references cvcat_data_state(id)    
) engine InnoDB, auto_increment = 1;

create table if not exists cvmj_visit_info (
    id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    started_at timestamp default current_timestamp,
    ended_at timestamp NULL ON UPDATE current_timestamp(),
    week_day_id int not null,
        constraint week_day_to_cat
            foreign key (week_day_id)
            references cvcat_week_days(id),
    visitant_id int not null,
        constraint visitant_to_table
            foreign key (visitant_id)
            references cvmj_visitants(id)
            ON DELETE CASCADE,
    coworker_id int not null,
        constraint cowork_to_cowork_tb
            foreign key (coworker_id)
            references cvmj_coworkers(id),
    raw_coworker_full_name varchar(300) default "",
    raw_coworker_dpt_id int not null,
    level_access_id int not null,
        constraint lv_access_to_lv_tb
            foreign key (level_access_id)
            references cvcat_level_access(id),
    has_gun varchar(2) default "",
    gun_status_id int not null,
        constraint gun_status_to_cat
            foreign key (gun_status_id)
            references cvcat_gun_status(id),
    reason_of_visit_id int not null,
        constraint reason_visit_to_cat
            foreign key (reason_of_visit_id)
            references cvcat_visit_reason(id),
    license_number varchar(30) default "",
    license_type_id int not null,
		constraint license_type_to_cat
            foreign key (license_type_id)
            references cvcat_guns_license(id),
    start_comments varchar(300) default "",
    end_comments varchar(300) default "",
    visit_state int not null default 0,
    id_state int not null default 1,
		constraint vinfo_datastate
		foreign key (id_state)
		references cvcat_data_state(id)
) engine InnoDB, auto_increment = 1;

CREATE TABLE IF NOT EXISTS cvmj_log_for_tables (
	id int not null auto_increment primary key,    
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    table_name varchar(100) not null,
    user_id int not null,    
    action_done varchar(30) not null,
    data_before_action varchar(250) default null,
    log_description varchar(200) default null,   
    id_state int not null default 1,
		constraint logfortables_datastate
		foreign key (id_state)
		references cvcat_data_state(id)        
) engine InnoDB, auto_increment = 1;