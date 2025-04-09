# agregada al log
CREATE TABLE IF NOT EXISTS ijoven_cat_roles(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    role_name varchar(50) not null,
    role_description varchar(200) default "",
    role_super int default 0,
    role_white int default 0,
    role_read int default 0,
    role_edit int default 0,
    role_delete int default 0,
    id_state int not null default 1,
    constraint ijoven_catroles_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)     
) engine InnoDB, auto_increment = 1;

INSERT INTO ijoven_cat_roles (id,role_name,role_description,role_super,role_white,role_read,role_edit,role_delete) VALUES 
(1,'ADMIN','ADMINISTRADOR - ACCESO TOTAL',1,1,1,1,1), 
(2,'SUPPORT','SOPORTE - ACCESO TOTAL POR DEBAJO DE ADMIN',0,1,1,1,1),
(3,'USER','USUARIO - ACCESO TOTAL TOTAL A SU CUENTA',0,1,1,1,1), 
(4,'VISITOR','VISITANTE - ACCESO DE LECTURA',0,1,0,0,0); 

# agregado al log
CREATE TABLE IF NOT EXISTS ijoven_cat_users_gender(
	id int not null auto_increment primary key,
    gender varchar(20) default "",
    gender_description varchar(100) default "",
    gender_iso varchar(5) default "",
    id_state int not null default 1,
		constraint ijoven_catusergender_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)    
) engine InnoDB, auto_increment = 1;

INSERT INTO ijoven_cat_users_gender (id,gender,gender_description,gender_iso) VALUES
(1,'HOMBRE','HOMBRE, MACHO DE UNA ESPECIOE DE MAMIFEROS','MLE'),
(2,'MUJER','HEMBRA DE UNA ESPECIE DE MAMIFEROS','FEM'),
(3,'INDEFINIDO','NO DA DETALLES DE SU SEXO','UNF'),
(4,'OTRO','SU SEXO ES DISTINTO A HOMBRE O MUJER','OTR');

CREATE TABLE IF NOT EXISTS ijoven_cat_users_expire_session(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    expire_session bool default true not null,
    expire_description varchar(100) default "",
    expire_session_iso varchar(5) default "",
    id_state int not null default 1,
		constraint ijoven_catuexpsession_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)   
) engine InnoDB, auto_increment = 1;

INSERT INTO ijoven_cat_users_expire_session (id,expire_session,expire_description,expire_session_iso) VALUES
(1,true,'SIGNIFICA QUE LA SESION ESTA ACTIVA.','TRE'),
(2,false,'SIGNIFICA QUE LA SESION HA EXPIRADO.','FLS');


CREATE TABLE IF NOT EXISTS ijoven_cat_account_status(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    account_status varchar(20) not null,
    account_status_description varchar(150) default "",
    account_status_iso varchar(5) default "",
    id_state int not null default 1,
		constraint ijoven_cataccount_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)     
) engine InnoDB, auto_increment = 1;

INSERT INTO ijoven_cat_account_status (id,account_status,account_status_description,account_status_iso) VALUES
(1,'ACTIVE','SIGNIFICA QUE LA CUENTA FUNCIONA CON NORMALIDAD.','ACT'),
(2,'INACTIVE','SIGNIFICA QUE LA CUENTA TIENE UN TIEMPO DETERMINADO (DEFINIDO POR EL ADMINISTRADOR) SIN USAR.','INACT'),
(3,'SUSPENDE','SIGNIFICA QUE LA CUENTA VIOLO LAS NORMAS DE USO DE LA APP Y ESTA SUSPENDIDA POR TIEMPO INDETERMINADO.','SPD'),
(4,'BLOCKED','SIGNIFICA QUE LA CUENTA NO PUEDE VOLVER A USARSE.','BLK');

CREATE TABLE IF NOT EXISTS ijoven_cat_users_search_filter(
	id int not null auto_increment primary key,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update now(),
    search_filter varchar(50) not null,    
    db_field varchar(50) not null,
    search_filter_description varchar(150) default "",
    search_filter_iso varchar(5) default "",
    id_state int not null default 1,
		constraint ijoven_usearch_datastate
        foreign key (id_state)
        references ijoven_cat_data_state(id)   
) engine InnoDB, auto_increment = 1;

INSERT INTO ijoven_cat_users_search_filter (id,search_filter,db_field,search_filter_description,search_filter_iso) VALUES
(1,'ID','user_id','ID DE USUARIO','UID'),
(2,'USUARIO','users_name','NOMBRE DE USUARIO (NICKNAME-APODO)','USR'),
(3,'NOMBRES','first_name','NOMBRES REALES DE LA PERSONA','NMB'),
(4,'APELLIDOS','last_name','APELLIDOS REALES DE LA PERSONA','APL'),
(5,'CEDULA','users_goverment_id','NUMERO DE CEDULA DE IDENTIDAD','CDL'),
(6,'CORREO ELECTRONICO','users_email','CORREO ELECTRONICO INSTITUCIONAL PREFERIBLEMENTE','CET');
