-- estados de registros en tablas
create table if not exists arch_cat_status(
	id int not null auto_increment,
    status_name varchar(50) not null unique,
    status_iso varchar(10) default '' unique,
    status_description varchar(100) default '',
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_cat_first_level_category (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    category_name varchar(100) not null default '',
    category_slug varchar(100) default '',
    category_description varchar(200) default '',
    category_status_id int not null default 1,
        constraint status_first_lv_category
        foreign key (category_status_id)
        references arch_cat_status(id),
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_cat_second_level_category (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    category_first_level_id int not null,
        constraint sec_first_lv_category
        foreign key (category_first_level_id)
        references arch_cat_first_level_category(id),
    subcategory_name varchar(100) not null default '',
    subcategory_slug varchar(100) default '',
    subcategory_description varchar(200) default '',
    category_status_id int not null default 1,
        constraint status_second_category
        foreign key (category_status_id)
        references arch_cat_status(id),    
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_cat_third_level_category (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    category_second_level_id int not null,
        constraint third_sec_lv_category
        foreign key (category_second_level_id)
        references arch_cat_second_level_category(id),
    subcategory_name varchar(100) not null default '',
    subcategory_slug varchar(100) default '',
    subcategory_description varchar(200) default '',
    category_status_id int not null default 1,
        constraint status_third_category
        foreign key (category_status_id)
        references arch_cat_status(id), 
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_cat_four_level_category (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    category_third_level_id int not null,
        constraint four_third_lv_category
        foreign key (category_third_level_id)
        references arch_cat_third_level_category(id),
    subcategory_name varchar(100) not null default '',
    subcategory_slug varchar(100) default '',
    subcategory_description varchar(200) default '',
    category_status_id int not null default 1,
        constraint status_four_category
        foreign key (category_status_id)
        references arch_cat_status(id), 
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_cat_five_level_category (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),    
    category_four_level_id int not null,
        constraint fivefour_lv_category
        foreign key (category_four_level_id)
        references arch_cat_four_level_category(id),
    subcategory_name varchar(100) not null default '',
    subcategory_slug varchar(100) default '',
    subcategory_description varchar(200) default '',
    category_status_id int not null default 1,
        constraint status_five_category
        foreign key (category_status_id)
        references arch_cat_status(id), 
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_post_data (
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(), 
    post_title varchar(100) not null default '',
    post_content text,
    post_description varchar (200) not null default '',
    author_id int not null,  
    author_full_name varchar(100) not null default '',
    files_path text,
    public_uri text,
    category_level varchar(20) not null default "",        
    category_id int default 0,
    post_publication_state varchar(10) default 'on',
    id_status int not null default 1,
        constraint poststatus_data
        foreign key (id_status)
        references arch_cat_status(id), 
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists arch_settings(
	id int not null auto_increment,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    json_options text,
    primary key (id)
) engine InnoDB auto_increment=1;