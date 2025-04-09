INSERT INTO cvcat_floor_location (floor_location,floor_iso) VALUES
('PRIMER NIVEL' , 'PNL'),
('SEGUNDO NIVEL', 'SNL'),
('TERCER NIVEL' , 'TNL'),
('CUARTO NIVEL' , 'CNL'),
('QUINTO NIVEL' , 'QNL');

INSERT INTO cvcat_level_access (level_access,level_access_iso) VALUES
('LEVEL A','LVA'),
('LEVEL B','LVB'),
('LEVEL C','LVC');

INSERT INTO cvcat_plant_dist_filter (filter_name,filter_iso) VALUES
('id','IDN'),
('departamento','DPT'),
('ubicación','UBC'),
('nivel de acceso','NDA');

INSERT INTO cvcat_genders ( gender , gender_iso ) VALUES
('HOMBRE','HMB'),
('MUJER','MJR'),
('OTRO','OTR');

INSERT INTO cvcat_identification_type (identification_type,identification_type_iso) VALUES
('CEDULA','CDL'),
('PASAPORTE','PPT'),
('OTRO','OTR');