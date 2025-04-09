-- estados de registros en tablas
create table if not exists bcmj_cat_status(
	id int not null auto_increment,
    status_name varchar(50) not null unique,
    status_iso varchar(10) default '' unique,
    status_descripcion varchar(100) default '',
    primary key (id)
) engine InnoDB auto_increment=1;

-- generos sexuales
create table if not exists bcmj_cat_genders(
	id int not null auto_increment,
    gender_name varchar(50) not null unique,
    gender_iso varchar(10) default '' unique,
    gender_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_genders_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;

-- Tabla Situacion Laboral
create table if not exists bcmj_cat_job_status(
	id int not null auto_increment,
    job_status_name varchar(50) not null unique,
    job_status_iso varchar(10) default '' unique,
    job_status_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_jobstatus_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;

-- Paises del mundo
create table if not exists bcmj_cat_countries (
	id int NOT NULL AUTO_INCREMENT,
	country_iso varchar(2) DEFAULT NULL,
	country_name varchar(80) DEFAULT NULL,
    status_id int not null default 2,
		constraint bcmj_countries_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
	PRIMARY KEY (id)
) engine InnoDB auto_increment=1;


-- tabla zonas regionales (para provincias que esta agrupadas por esta categoria)
create table if not exists bcmj_cat_zones(
	id int not null auto_increment,
    zone_name varchar(50) not null unique,
    zone_iso varchar(10) default '' unique,
    zone_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_zones_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;


-- tabla provincias
create table if not exists bcmj_cat_provinces(
	id int not null auto_increment,
    country_id int not null,
		constraint bcmj_statecountry
        foreign key (country_id)
        references bcmj_cat_countries(id)
        on delete cascade,
	zone_id int not null,
		constraint bcmj_provincezones
        foreign key (zone_id)
        references bcmj_cat_zones(id)
        on delete cascade,
    province_name varchar(50) not null unique,
    province_iso varchar(10) default '' unique,
    province_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_provinces_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;

-- tabla ciudades
create table if not exists bcmj_cat_cities(
	id int not null auto_increment,
    province_id int not null,
		constraint bcmj_provincecities
        foreign key (province_id)
        references bcmj_cat_provinces(id),	
    city_name varchar(50) not null unique,
    city_iso varchar(10) default '' unique,
    city_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_cities_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,    
    primary key (id)
) engine InnoDB auto_increment=1;

-- tabla que contiene el estado legal de los usuarios respecto al matrimonio
create table if not exists bcmj_cat_civil_status(
	id int not null auto_increment,
	civil_status varchar(50) not null unique,
    civil_status_iso varchar(10) default '' unique,
    civil_status_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_civilstatus_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,  
    primary key (id)
) engine InnoDB auto_increment=1;

-- tabla que contiene el tipo de identificacion de su identidad
create table if not exists bcmj_cat_identification_type(
	id int not null auto_increment,
	ident_type varchar(50) not null unique,
    ident_type_iso varchar(10) default '' unique,
    ident_type_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_identtype_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,  
    primary key (id)
) engine InnoDB auto_increment=1;

-- tabla que contiene los usuarios de los estudiantes (postulantes)
create table if not exists bcmj_applicants(
	id int not null auto_increment,
    first_name varchar(100) not null,
    middle_name varchar(100) default '',
    last_name_one varchar(100) not null,
    last_name_two varchar(100) default '',
    birth_date varchar(10) not null default '',
    gender_id int not null,
		constraint bcmj_applicantsgenders
        foreign key (gender_id)
        references bcmj_cat_genders(id)
        on delete cascade,
	status_id int not null default 1,
		constraint bcmj_applicantstatus
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,      
    primary key (id)
) engine InnoDB auto_increment=1;

-- tabla que contiene los datos personales de los estudiantes (postulantes)
create table if not exists bcmj_applicants_details(
	id int not null auto_increment,
    applicant_id int not null unique,
		constraint bcmj_detailsapplicants
        foreign key (applicant_id)
        references bcmj_applicants(id)
        on delete cascade,
	nationality_id int not null default 65, -- 65 Dominican Republic
		constraint bcmj_countryapplicants
        foreign key (nationality_id)
        references bcmj_cat_countries(id)
        on delete cascade,	
	civil_status_id int not null default 1, -- 1 soltero
		constraint bcmj_civilstatuspplicants
        foreign key (civil_status_id)
        references bcmj_cat_civil_status(id)
        on delete cascade,	
    job_status_id  int not null default 2, -- 2 desempleado
		constraint bcmj_jobstatuspplicants
        foreign key (job_status_id)
        references bcmj_cat_job_status(id)
        on delete cascade,	
	status_id int not null default 1, -- 1 activo
		constraint bcmj_appdetailstatus
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,  
    primary key (id)
) engine InnoDB auto_increment=1;

-- datos de los aplicantes respecto a su numero de identidad
create table if not exists bcmj_applicants_ident_data(
	id int not null auto_increment,
    applicant_id int not null unique,
		constraint bcmj_identdataplicants
        foreign key (applicant_id)
        references bcmj_applicants(id)
        on delete cascade,
	identitication_code varchar(50) not null,
    ident_type_id int not null,
		constraint bcmj_identdatatype
        foreign key (ident_type_id)
        references bcmj_cat_identification_type(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;

-- direccion de los aplicantes (el aplicante puede tener varias direcciones, pero solo una activa,
-- las demas en estado 3 eliminado para fines de reportes e historial) 
create table if not exists bcmj_applicants_address(
	id int not null auto_increment,
    address_one varchar(100) default '',
    address_two varchar(100) default '',    
    city_id int not null,    -- la ciudad esta adata a una provincia y este a su vez a un pais
		constraint bcmj_citiesappaddress
        foreign key (city_id)
        references bcmj_cat_cities(id)
        on delete cascade, 
    city_zone varchar(100) default '', -- nombre del barrio, paraje o sector (campo no controlado)
    zip_code varchar(10) default '',
    status_id int not null default 1, -- 1 activo
		constraint bcmj_appaddresstatus
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,  
    primary key (id)
) engine InnoDB auto_increment=1;



-- tabla que contiene el tipo de identificacion de su identidad
create table if not exists bcmj_cat_phone_operators(
	id int not null auto_increment,
    phone_operator_country_id int not null default 65, -- 65 Dominican Republic
		constraint bcmj_phonperacountry
        foreign key (phone_operator_country_id)
        references bcmj_cat_countries(id), 
	phone_operator_name varchar(50) not null unique,
    phone_operator_iso varchar(10) default '' unique,
    phone_operator_description varchar(100) default '',     
    status_id int not null default 2,
		constraint bcmj_phoneoperator_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,  
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists bcmj_cat_contry_phone_prefix(
	id int not null auto_increment,
	country_id int not null,
		constraint bcmj_phoneprefixcountry
        foreign key (country_id)
        references bcmj_cat_countries(id), 
	phone_prefix varchar(10) not null default '+1',
    phone_prefix_iso varchar(10) unique,
    phone_prefix_description varchar(10) default '',
    status_id int not null default 2,
		constraint bcmj_phoneprefixcountry_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade, 
	primary key (id)
) engine InnoDB auto_increment=1;

-- tabla de contiene los numeros de telefonos de los postulantes actualies y anteriores
create table if not exists bcmj_applicants_phones(
	id int not null auto_increment,
    applicant_id int not null,
		constraint bcmj_phoneapplicants
        foreign key (applicant_id)
        references bcmj_applicants(id)
        on delete cascade,        
	phone_prefix_id int not null,
		constraint bcmj_phonprefixoperappts
        foreign key (phone_prefix_id)
        references bcmj_cat_contry_phone_prefix(id),        
	phone_number varchar(50) not null,
    phone_operator_id int not null,
		constraint bcmj_phoneoperappts
        foreign key (phone_operator_id)
        references bcmj_cat_phone_operators(id),
    phone_type_id int not null,
		constraint bcmj_phonetypeappts
        foreign key (phone_type_id)
        references bcmj_cat_identification_type(id)
        on delete cascade,
	status_id int not null default 1,
		constraint bcmj_phoneappts_status
        foreign key (status_id)
        references bcmj_cat_status(id),
    primary key (id)
) engine InnoDB auto_increment=1;

-- Tabla Situacion Laboral
create table if not exists bcmj_cat_email_status(
	id int not null auto_increment,
    email_status_name varchar(50) not null unique,
    email_status_iso varchar(10) default '' unique,
    email_status_descripcion varchar(100) default '',
    status_id int not null default 2,
		constraint bcmj_emailstatus_status
        foreign key (status_id)
        references bcmj_cat_status(id)
        on delete cascade,
    primary key (id)
) engine InnoDB auto_increment=1;

create table if not exists bcmj_applicants_emails(
	id int not null auto_increment,
	applicant_id int not null,
		constraint bcmj_emailapplicants
		foreign key (applicant_id)
		references bcmj_applicants(id),
	email varchar(100) not null,
    email_prefence_id int not null,
		constraint bcmj_emailprefeappts
		foreign key (email_prefence_id)
		references bcmj_cat_email_status(id)
        on delete cascade,    
	status_id int not null default 1,
		constraint bcmj_emailappts_status
        foreign key (status_id)
        references bcmj_cat_status(id),
    primary key (id)
) engine InnoDB auto_increment=1;