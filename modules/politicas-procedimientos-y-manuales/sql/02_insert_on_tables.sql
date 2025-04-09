INSERT INTO arch_cat_status (id,status_name,status_iso,status_description) VALUES
(1,'ACTIVO','ACT','Totalmente accesible y editable.'),
(2,'RESTRINGIDO','RTG','Accesible pero no editable.'),
(3,'ELIMINADO','ELM','Existe solo para fines de reporte.'),
(4,'INACCESIBLE','INA','Existe en la BD pero no se utiliza para ningun fin.');

INSERT INTO arch_settings (id,json_options) VALUES(1, '{"printer":{"allow":true},"uploads":{"allow":true,"filter":false,"slug_allowed":"*","file_extensions": "pdf,doc,docx,xls,xlsx"},"downloads":{"allow":true,"filter":false,"slug_allowed":"*","file_extensions":"pdf,doc,docx,xls,xlsx"}}');
