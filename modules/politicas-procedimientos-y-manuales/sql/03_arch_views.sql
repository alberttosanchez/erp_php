CREATE OR REPLACE VIEW arch_view_all_categories AS
SELECT ifnull(fl.id,"") as 'id_lv1',
	ifnull(fl.category_name,"") as 'category_name_lv1',
    ifnull(fl.category_slug,"") as 'category_slug_lv1',
    ifnull(fl.category_description,"") as 'category_description_lv1',
    ifnull(fl.category_status_id,"") as 'status_id_lv1',    
    ifnull(sl.id,"") as 'id_lv2',
	ifnull(sl.subcategory_name,"") as 'category_name_lv2',
    ifnull(sl.subcategory_slug,"") as 'category_slug_lv2',
    ifnull(sl.subcategory_description,"") as 'category_description_lv2',
    ifnull(sl.category_first_level_id,"") as 'parentlv1_id',
    ifnull(sl.category_status_id,"") as 'status_id_lv2',    
    ifnull(tl.id,"") as 'id_lv3',
	ifnull(tl.subcategory_name,"") as 'category_name_lv3',
    ifnull(tl.subcategory_slug,"") as 'category_slug_lv3',
    ifnull(tl.subcategory_description,"") as 'category_description_lv3',
    ifnull(tl.category_second_level_id,"") as 'parentlv2_id',
    ifnull(tl.category_status_id,"") as 'status_id_lv3',    
    ifnull(frl.id,"") as 'id_lv4',
	ifnull(frl.subcategory_name,"") as 'category_name_lv4',
    ifnull(frl.subcategory_slug,"") as 'category_slug_lv4',
    ifnull(frl.subcategory_description,"") as 'category_description_lv4',
    ifnull(frl.category_third_level_id,"") as 'parentlv3_id',
    ifnull(frl.category_status_id,"") as 'status_id_lv4',    
    ifnull(fvl.id,"") as 'id_lv5',
	ifnull(fvl.subcategory_name,"") as 'category_name_lv5',
    ifnull(fvl.subcategory_slug,"") as 'category_slug_lv5',
    ifnull(fvl.subcategory_description,"") as 'category_description_lv5',
    ifnull(fvl.category_four_level_id,"") as 'parentlv4_id',
    ifnull(fvl.category_status_id,"") as 'status_id_lv5'    
FROM arch_cat_first_level_category AS fl
LEFT JOIN arch_cat_second_level_category AS sl  ON  sl.category_first_level_id = fl.id
LEFT JOIN arch_cat_third_level_category  AS tl  ON tl.category_second_level_id = sl.id
LEFT JOIN arch_cat_four_level_category   AS frl ON frl.category_third_level_id = tl.id
LEFT JOIN arch_cat_five_level_category   AS fvl ON  fvl.category_four_level_id = frl.id;

CREATE OR REPLACE VIEW arch_view_all_combine_categories AS (
SELECT 
	created_at,
	category_name AS "category_name",
	category_slug AS "category_slug",
	category_description AS "category_description",
	category_status_id AS "category_status_id",
    ifnull(id,"") as "id_lv1",
    ifnull(NULL,"") AS "id_lv2",
    ifnull(NULL,"") AS "id_lv3",
    ifnull(NULL,"") AS "id_lv4",
    ifnull(NULL,"") AS "id_lv5"
	FROM arch_cat_first_level_category    
	UNION ALL    
SELECT
	created_at,
	subcategory_name AS "category_name",
	subcategory_slug AS "category_slug",
	subcategory_description AS "category_description",
	category_status_id AS "category_status_id",    
    ifnull(NULL,"") AS "id_lv1",
    ifnull(id,"") as "id_lv2",    
    ifnull(NULL,"") AS "id_lv3",
    ifnull(NULL,"") AS "id_lv4",
    ifnull(NULL,"") AS "id_lv5"
    FROM arch_cat_second_level_category 
	UNION ALL    
SELECT
	created_at,
	subcategory_name AS "category_name",
	subcategory_slug AS "category_slug",
	subcategory_description AS "category_description",
	category_status_id AS "category_status_id",    
    ifnull(NULL,"") AS "id_lv1",
    ifnull(NULL,"") AS "id_lv2",
    ifnull(id,"") as "id_lv3",        
    ifnull(NULL,"") AS "id_lv4",
    ifnull(NULL,"") AS "id_lv5"
    FROM arch_cat_third_level_category 
	UNION ALL
SELECT
	created_at,
	subcategory_name AS "category_name",
	subcategory_slug AS "category_slug",
	subcategory_description AS "category_description",
	category_status_id AS "category_status_id",    
    ifnull(NULL,"") AS "id_lv1",
    ifnull(NULL,"") AS "id_lv2",
    ifnull(NULL,"") AS "id_lv3",
    ifnull(id,"") as "id_lv4",
    ifnull(NULL,"") AS "id_lv5"
    FROM arch_cat_four_level_category 
	UNION ALL
SELECT
	created_at,
	subcategory_name AS "category_name",
	subcategory_slug AS "category_slug",
	subcategory_description AS "category_description",
	category_status_id AS "category_status_id",       
    ifnull(NULL,"") AS "id_lv1",
    ifnull(NULL,"") AS "id_lv2",
    ifnull(NULL,"") AS "id_lv3",
    ifnull(NULL,"") AS "id_lv4",    
	ifnull(id,"") as "id_lv5"
    FROM arch_cat_five_level_category
);

CREATE OR REPLACE VIEW arch_view_all_post AS
SELECT 
	pdt.id,
    pdt.created_at,
    post_title,
    post_content,
    post_description,
    author_id,
    author_full_name,    
    ifnull(lv1.category_slug,ifnull(lv2.subcategory_slug,ifnull(lv3.subcategory_slug,ifnull(lv4.subcategory_slug,ifnull(lv5.subcategory_slug,""))))) as 'category_slug',
    category_id,
    category_level,    
    ifnull(lv1.category_name,"") as 'post_category_name_lv1',
    ifnull(lv2.subcategory_name,"") as 'post_category_name_lv2',
    ifnull(lv3.subcategory_name,"") as 'post_category_name_lv3',
    ifnull(lv4.subcategory_name,"") as 'post_category_name_lv4',
    ifnull(lv5.subcategory_name,"") as 'post_category_name_lv5'
    FROM arch_post_data as pdt
LEFT JOIN arch_cat_first_level_category as lv1 on lv1.id = pdt.category_id and pdt.category_level = "lv1"
LEFT JOIN arch_cat_second_level_category as lv2 on lv2.id = pdt.category_id and pdt.category_level = "lv2"
LEFT JOIN arch_cat_third_level_category as lv3 on lv3.id = pdt.category_id and pdt.category_level = "lv3"
LEFT JOIN arch_cat_four_level_category as lv4 on lv4.id = pdt.category_id and pdt.category_level = "lv4"
LEFT JOIN arch_cat_five_level_category as lv5 on lv5.id = pdt.category_id and pdt.category_level = "lv5";