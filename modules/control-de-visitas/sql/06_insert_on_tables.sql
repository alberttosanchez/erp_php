
INSERT INTO cvcat_visit_reason (reason_of_visit,reason_iso) VALUES
('CITA','CIT'),
('PERSONAL','PRN'),
('PROMOCION','PMC'),
('TECNICA','TCN'),
('OTROS','OTR');

INSERT INTO cvcat_license_type (license_type,license_iso) VALUES
('PORTE','PRT'),
('TENENCIA','TNC'),
('PORTE Y TENENCIA','PYT'),
('MILITAR','MLT'),
('PERMANENTE','PMT'),
('OTRO','OTR'),
('NO APLICA','NAP');

INSERT INTO cvcat_gun_status (gun_status,gun_status_iso) VALUES
('NO APLICA','NAP'),
('RETENIDA POR SEGURIDAD','RPS'),
('ENTREGADA AL VISITANTE','EAV'),
('OTROS','OTR');

INSERT INTO cvcat_week_days (week_day,week_day_iso,week_day_key) VALUES
('DOMINGO','DMG',0),
('LUNES','LNS',1),
('MARTES','MTS',2),
('MIERCOLES','MCL',3),
('JUEVES','JVS',4),
('VIERNES','VRN',5),
('SABADO','SBD',6);

INSERT INTO cvcat_guns_license (gun_license,gun_license_iso) VALUES
('USO PRIVADO (PORTE - TENENCIA)','UPT'),
('USO COMERCIAL (EMPRESAS)','UCE'),
('POLIGONOS DE TIRO','PDT'),
('ARTEFACTOS BLINDADOS','ATB'),
('SUSTANCIAS O ARTEFACTOS PIROTECNICOS','SAP'),
('ARMERIA Y TALLERES','AYT'),
('CACERIA','CCR'),
('USO O PROTECCION A MISIONES DIPLOMATICAS','UPM'),
('OFICIALES','OFL');