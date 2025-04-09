DELIMITER $$
CREATE PROCEDURE ijoven_sp_delete_module
( 
	in module_id_param int     
)
begin
	DECLARE sql_error tinyint default false; 
    DECLARE counter_var int default null;    
    
    DECLARE CONTINUE handler for sqlexception # declara un manejado de errores
    set sql_error = true; #  si ocurre error asigna true a la variable.    
    
    #-- el nivel de la transaccion es serializable (bloquea las tablas mientras se ejecuta el procedimiento almacenado)
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    
    start transaction; # -- indica el inicio de las transacciones
	#-- las transacciones se utilizan para hacer
	#-- insert,
	#-- update,
	#-- delete en la base de datos.	
    
    -- verificamos que el modulo exista
    SELECT count(*)
    INTO counter_var
    FROM ijoven_module_status
    WHERE id = module_id_param;
    
    if NOT( counter_var = 1 ) then
		set sql_error = true;
        select "404" as "status";
        select "Modulo no existe." as "message";
	end if;
    
    -- inicio de eliminacion del modulo en tabla
    -- se debe crear triggers (disparador) para almacenar un log con la informacion borrada
    if ( sql_error = false ) then 
		        
        DELETE
        FROM ijoven_module_status
        WHERE id = module_id_param;
        
    end if;
    
    -- verificamos que los registros se hayan borrado de las tablas
    if ( sql_error = false ) then 
		
        SELECT count(*)
        INTO counter_var
        FROM ijoven_module_status
        WHERE id = module_id_param;
        
        if (counter_var = 1) then
			set sql_error = true;
            select "404" as "status";
            select "Modulo no eliminado." as "message";
        end if;
        
    end if;
    
   
    if ( sql_error = true ) then
		rollback;
	end if;
    
    if ( sql_error = false ) then
		select "200" as "status";
        select "Modulo Eliminado" as "message";
		commit;
	end if;
    
end$$
DELIMITER ;
