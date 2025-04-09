# esta tabla no contiene un campo id_state
# ya que no es necesario almacenar los modulos que fueron eliminados en esta tabla
create table if not exists ijoven_module_status(
	id int not null auto_increment primary key,
    create_at timestamp default current_timestamp not null,
    update_at timestamp default current_timestamp on update now(),
    name varchar(100) not null unique,
    description  varchar(200) not null default "",
    version varchar(20) not null default "",
    author varchar(100) not null default "",
    web varchar(200) not null default "",
    activation int not null default 0,
    installed int not null default 0,
    islink int not null default 0,
    tables_name text,
    views_name text,
    store_procedures_name text 
    /* id_state int not null default 1,
		constraint ijoven_module_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id) */    
) engine InnoDB, auto_increment = 1;