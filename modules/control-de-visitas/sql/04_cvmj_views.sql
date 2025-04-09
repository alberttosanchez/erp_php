CREATE OR REPLACE VIEW cvvw_plant_distribution as
SELECT 
    pt.id,
    pt.department, 
    fl.floor_location, 
    la.level_access,
    pt.id_state
	FROM cvmj_plant_distribution as pt
		INNER JOIN cvcat_floor_location as fl ON pt.floor_location_id = fl.id
		INNER JOIN cvcat_level_access as la ON pt.level_access_id = la.id
        ORDER BY pt.id ASC;

CREATE OR REPLACE VIEW cvvw_coworker_info AS 
SELECT co.id,co.name,
    co.last_name,
    co.gender_id,
    gen.gender,
    co.identification_id,
    co.identification_type_id,
    idt.identification_type,
    co.birth_date,
    job.job_department_id,
    pd.department,
    job.job_title,
    job.phone_extension,
    job.job_email,
    co.photo_path,
    co.id_state
    FROM cvmj_coworkers as co
        join cvmj_job_info as job on co.id = job.coworker_id
        join cvcat_genders as gen on co.gender_id = gen.id
        join cvcat_identification_type as idt on co.identification_type_id = idt.id
        join cvmj_plant_distribution as pd on job.job_department_id = pd.id
        ORDER BY co.id ASC;
 
CREATE OR REPLACE VIEW cvmj_view_visitant_and_visit AS 
	SELECT 
		vi.id as visit_id,
		it.id_visitant,
        vi.started_at,
        vi.ended_at,
        it.ident_number, 
        it.ident_type_id,
        idt.identification_type,
        vt.name, 
        vt.last_name, 
        vt.gender_id, 
        gd.gender,
        vt.birth_date, 
        vt.last_visit_date,
        vi.id as 'visit_info_id',
        vi.week_day_id,
        wd.week_day,
        vi.coworker_id,
        cw.name as 'cw_name',
        cw.last_name as 'cw_last_name',
        vi.raw_coworker_full_name as 'cw_raw_full_name',        
        vi.level_access_id,
        la.level_access,
        vi.has_gun,
        vi.gun_status_id,
        gs.gun_status,        
        vi.reason_of_visit_id,
        vr.reason_of_visit,
        vi.license_number,
        vi.license_type_id,
        gl.gun_license,
        vi.start_comments,
        vi.end_comments,
        vi.visit_state,
        ji.job_department_id,
        pd.department,
        vi.raw_coworker_dpt_id,
        ptd.department as 'cw_raw_department',
        pd.floor_location_id,
        fl.floor_location,
        ji.job_title,
        ji.phone_extension,
        ji.job_email,
        it.id_state
    from cvmj_identification_type as it
		JOIN cvmj_visitants as vt ON vt.id = it.id_visitant
        JOIN cvmj_visit_info as vi ON vi.visitant_id = vt.id
        JOIN cvcat_guns_license as gl ON vi.license_type_id = gl.id
        JOIN cvmj_job_info as ji ON ji.coworker_id = vi.coworker_id
        JOIN cvmj_plant_distribution as pd ON pd.id = ji.job_department_id
        JOIN cvmj_plant_distribution as ptd ON ptd.id = vi.raw_coworker_dpt_id
        JOIN cvmj_coworkers as cw ON cw.id = vi.coworker_id
        JOIN cvcat_genders as gd ON gd.id = vt.gender_id
        JOIN cvcat_week_days as wd ON wd.id = vi.week_day_id
        JOIN cvcat_level_access as la ON la.id = vi.level_access_id
        JOIN cvcat_gun_status AS gs ON gs.id = vi.gun_status_id
        JOIN cvcat_visit_reason AS vr ON vr.id = vi.reason_of_visit_id
        JOIN cvcat_floor_location AS fl ON fl.id = pd.floor_location_id 
        JOIN cvcat_identification_type AS idt ON idt.id = it.ident_type_id
        ORDER BY vi.started_at DESC;

CREATE OR REPLACE VIEW cvvw_visitant_info as
    SELECT 
        v.id as id_visitant, 
        v.name, v.last_name, 
        v.gender_id, 
        gd.gender, 
        v.birth_date, 
        it.ident_number, 
        vit.identification_type, 
        v.last_visit_date,
        v.photo_path,
        v.id_state
    from cvmj_visitants as v    
	    JOIN cvcat_genders as gd on gd.id = v.gender_id
		JOIN cvmj_identification_type as it on it.id_visitant = v.id
		JOIN cvcat_identification_type as vit on vit.id = it.ident_type_id
        ORDER BY v.id ASC;