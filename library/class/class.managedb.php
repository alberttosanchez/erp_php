<?php

    namespace Library\Classes;

    class ManageDB extends Conexion {

        private static $instance;
        private $conn;
        private $active_session;
        private $Session;
        private $limit = ROWS_PER_PAGE;
        
        // El constructor se invoca automaticamente al instanciar la clase
        // obtenemos la conexion a la base de datos
        public function __construct() {
            $this->conn = $this->get(DB_CONFIG);

            //instanciamos la clase sesion en la propiedad.
            $this->Session = Session::singleton();            
        }
        
        // Se extiende la clase a Session mediante un metodo magico
        // fake "extends Session" using magic function
        public function __call($method, $args)
        {
            $this->Session->$method($args[0]);
        }

        public function check_session()
        {
            // veficamos el token y el user id en la base de datos
            // si son correctos actualizamos el tiempo del token y devolvemos los datos enviados
            // si no es falso            
            $this->active_session = $this->Session->verify_token_and_id_in_db($this->conn,$GLOBALS['array_token_and_user_id']);
         
            if ($this->active_session == false){
                return false;
            }
            return true;
        }

        # ----------------------------------------------------   
        # método singleton
        public static function singleton() {       
            
            if (!isset(self::$instance)) {
                $myclass = __CLASS__; # __CLASS__ devuelve el nombre de esta clase.
                self::$instance = new $myclass;
            } 
    
            return self::$instance;
    
        }

        //------------------------------------------------------
        
        // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
        // de los contrario false.
        public function get_table_rows(
            $table_name,
            $filter = "",
            $keyword = "",            
            $limit = 1000,
            $selected_page = '1',
            $array_fields = false,
            $order_by = 'id',
            $order_dir = 'ASC',
            $filter_between = "",
            $array_between = false,
            $strict_mode = false
        )
        {   
            // var_dump($selected_page);
            $current_page = ( (int)$selected_page <= 1 ) ? 1 : (int)$selected_page;
            // var_dump($current_page); die();
            $start_pos = ($current_page == 1 ) ? 0 : ($limit * $current_page) - $limit;
            // var_dump($start_pos); die();
            $order_by = !empty($order_by) ? $order_by : 'id';
            
            $new_limit = "$start_pos , $limit";

            // var_dump($new_limit);
            if ($start_pos == $limit && $current_page == 1){
                $new_limit = $limit;
            }
            # SELECT * FROM ".DEFAULT_DATABASE.".cvmj_view_visitant_and_visit 
        	# where id_visitant = 1 and CAST(started_at AS DATE) between '2022-05-04%' and '2022-05-05%';

                  
            if ( 
                !empty($filter)             && !empty($filter_between) && 
                !empty($keyword)            && !empty($table_name) &&                 
                ( is_array($array_between)  && count($array_between) > 1)
            )
            {                
                $query = "SELECT * FROM $table_name WHERE $filter = $keyword ";

                $query .= "and CAST($filter_between AS DATE) BETWEEN ";
                $query .= "'$array_between[0]%' AND ";
                $query .= "'$array_between[1]%' ";
                
                $query .= "ORDER BY $order_by $order_dir LIMIT $new_limit";                
            }
            elseif ( 
                !empty($table_name) && ( is_array($filter) && count($filter) > 1 ) && 
                ( is_array($keyword) && count($keyword) > 1 ) && $array_fields == false && $strict_mode == false
            )
            {
                
                $query = "SELECT * FROM $table_name WHERE ";

                $counter = 0;
                foreach ($filter as $field) {

                    if ($counter+1 !== count($keyword) )
                    {
                        $query .= $field . " like '".$keyword[$counter]."%' AND "; 
                    }
                    else 
                    {
                        $query .= $field . " like '".$keyword[$counter]."%' "; 
                    }
                    $counter++;
                }
                $query .= "ORDER BY $order_by $order_dir LIMIT $new_limit";                
                //echo json_encode($query); die();
            }
            // nombre de tabla - array filtro > 1 - array keyword > 1 - array campos flaso
            elseif ( 
                !empty($table_name) && ( is_array($filter) && count($filter) > 1 ) && 
                ( is_array($keyword) && count($keyword) > 1 ) && $array_fields == false && $strict_mode
            )
            {
                
                $query = "SELECT * FROM $table_name WHERE ";

                $counter = 0;
                foreach ($filter as $field) {

                    if ($counter+1 !== count($keyword) )
                    {
                        $query .= $field . " = '".$keyword[$counter]."' AND "; 
                    }
                    else 
                    {
                        $query .= $field . " = '".$keyword[$counter]."' "; 
                    }
                    $counter++;
                }
                $query .= "ORDER BY $order_by $order_dir LIMIT $new_limit";                
                //echo json_encode($query); die();
            }
            elseif ( !empty($table_name) && !empty($filter) && !empty($keyword) && $array_fields == false  && $strict_mode)
            {
                $query = "SELECT * FROM $table_name WHERE $filter = '$keyword' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }            
            elseif ( !empty($table_name) && !empty($filter) && $filter == 'id' && !empty($keyword) && $array_fields == false)
            {
                $query = "SELECT * FROM $table_name WHERE $filter LIKE '$keyword' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            # consulta que inicie con un numero de 0-9
            elseif ( !empty($table_name) && !empty($filter) && !empty($keyword) && $keyword == "0-9" && $array_fields == false)
            {
                $query = "SELECT * FROM $table_name WHERE $filter REGEXP '^[0-9]' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) && !empty($filter) && !empty($keyword) && $array_fields == false && $strict_mode)
            {
                $query = "SELECT * FROM $table_name WHERE $filter = '$keyword' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) && !empty($filter) && !empty($keyword) && strlen($keyword) == 1 && $array_fields == false)
            {
                $query = "SELECT * FROM $table_name WHERE $filter LIKE '$keyword%' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) && !empty($filter) && !empty($keyword) && $array_fields == false)
            {
                $query = "SELECT * FROM $table_name WHERE $filter LIKE '%$keyword%' ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) && !empty($filter) && empty($keyword) )
            {
                $query = "SELECT * FROM $table_name ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) && empty($filter) && empty($keyword) )
            {
                $query = "SELECT * FROM $table_name ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            elseif ( !empty($table_name) )
            {
                $query = "SELECT * FROM $table_name ORDER BY $order_by $order_dir LIMIT $new_limit";
            }
            else
            {
                return false;
            }            

            //var_dump($query);

            $statement = $this->conn->prepare($query);

            $statement->execute();

            $result = $statement->fetchAll();

            $array_pagination = $this->get_pagination($table_name, $query, $current_page, $limit);

            $result = $this->merge_pagination_and_result($array_pagination, $result);
            
            //var_dump($result); die();

            return (is_array($result) && count($result) > 0) ? $result : false;

        }
        
        // combina el array de la paginacion con el resultado de la consulta.
        private function merge_pagination_and_result($array_pagination, $result)
        {
            /*
                $array_pagination = [
                    
                        'counter'           =>  $count_result[0][0],
                        'limit'             =>  $limit,
                        'first_page'        =>  $first_page,
                        'current_page'      =>  $current_page,
                        'last_page'         =>  $last_page,
                        'next_page'         =>  $next_page,
                        'prev_page'         =>  $prev_page,
                        'star_pos'          =>  $start_pos,
                        'pages'             =>  $pages
                    
                ];
            */

            $pagination = [
                'pagination'    => $array_pagination,
                'fetched'       => $result,
            ];

            return $pagination;

        }

        // devuelve un array con la paginacion
        private function get_pagination(
            $table_name = "",
            $query = "",
            $current_page = 1,            
            $limit = null
        )
        {
            //var_dump($query);            

            

            // limite de registros a mostrar por pagina
            // ROWS_PER_PAGE -> ver config.php
            $this->limit = !empty($limit) ? $limit : 5;

            // posicion inicial de la consulta
            //$start_pos = 0;
            
            //$current_page = ( (int)$selected_page <= 1 ) ? 1 : (int)$selected_page;
            
            $start_pos = ($current_page == 1 ) ? 0 : ($this->limit * $current_page) - $this->limit;
                           
            $query = str_replace('*', 'count(*)', $query);

            $array_query = explode('LIMIT', $query);
            //var_dump($array_query);

            // si es mayor que 1 encontro el delimitador
            if ( count($array_query) > 1)
            {
                $query = trim($array_query[0]);
            }
            
            //var_dump($query); die();

            $statement = $this->conn->prepare($query);

            $statement->execute();
            $count_result = $statement->fetchAll();

            if (isset( $count_result[0][0] ) )
            {
                // numero pagina siguiente
                $next_page      = ( ($current_page * $this->limit) >= $count_result[0][0] ) ? "" : $current_page+1;

                // numero pagina anterior
                $prev_page      = ($current_page-1 <= 0) ? "" : $current_page-1;

                // indica cantidad de paginas a mostrar
                $pages      = ceil($count_result[0][0] / $this->limit);
                $first_page = 1;
                $last_page  = $pages;
    
                $pagination = [
                    
                        'counter'           =>  $count_result[0][0],
                        'limit'             =>  $this->limit,
                        'first_page'        =>  $first_page,
                        'current_page'      =>  $current_page,
                        'last_page'         =>  $last_page,
                        'next_page'         =>  $next_page,
                        'prev_page'         =>  $prev_page,
                        'star_pos'          =>  $start_pos,
                        'pages'             =>  $pages
                    
                    //'data'          =>  $result
                ];
                
                return $pagination;            
            }
            return [];
        }

        // devuelve todos los campos de una tabla ordenada por el ID y limitada optionalmente,
        // de los contrario false.
       /*  public function get_table_rows($table_name = "", $selected_page = '0', $limit = 1000)
        {
            
            if ( $table_name !== "" )
            {
                $query = "SELECT * FROM $table_name ORDER BY id ASC LIMIT $limit";                
            }            
            
            $statement = $this->conn->prepare($query);

            $statement->execute();

            $result = $statement->fetchAll();
            
            $array_pagination = $this->get_pagination($table_name, $query, $selected_page);

            $result = $this->merge_pagination_and_result($array_pagination, $result);
            
            return isset($result) ? $result : false;

        } */
        //------------------------------------------------------
        // Verifica que exista una fila en la tabla indicada que coincida con elos datos enviados 
        // por el array asociativo mediante la condicion where y el campo ID de lo contrario
        // devuelve la ultima fila agregada ordenada por el id.
       /*  public function get_table_row($table_name = "", $field_id = "", $selected_page = '0', $limit = "")
        {            
            $this->limit = isset($limit) & !empty($limit) ? $limit : $this->limit;

            if ( $table_name !== "" && isset($field_id) && strlen($field_id) > 0 )
            {
                $query = "SELECT * FROM $table_name WHERE id = " . $field_id . " ORDER BY id DESC LIMIT 1, " . $this->limit;
            }
            else if ( $table_name !== "" )
            {
                $query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1," . $this->limit;                
            }
            
            $statement = $this->conn->prepare($query);

            $statement->execute();

            $result = $statement->fetchAll();

            $array_pagination = $this->get_pagination($table_name, $query, $selected_page);

            $result = $this->merge_pagination_and_result($array_pagination, $result);
            
            return isset($result[0]) ? $result[0] : false;

        } */
        //------------------------------------------------------
        // Verifica que exista una nueva fila en la tabla indicada que coincida con los datos enviados 
        // por el array asociativo mediante la condicion where y el campo ID
        public function row_exists($table_name = "", $array = [], $filter = 'id', $assoc_index = 'id')
        {
            //var_dump($array); die();

            if ($table_name !== "" && is_array($array) && count($array) > 0 && isset($array[$filter]) )
            {
                $array[$filter] = ( gettype($array[$filter]) == 'string' ) ? "'".(string)$array[$filter]."'" : $array[$filter];

                $query = "SELECT * FROM $table_name WHERE $filter = $array[$filter] ORDER BY id DESC LIMIT 1";                

                //var_dump($query);

                $statement = $this->conn->prepare($query);
                $statement->execute();

                $result = $statement->fetchAll();
                
                //var_dump($result); die();

                if ( is_array($result) )
                {
                    foreach ($result as $item) {

                        //var_dump($item); die();

                        if ( is_array($item) && count($item) > 0  )
                        {
                            return true;
                        }
                        
                    }
                }
            }

            return false;

        }

        //------------------------------------------------------
        // Verifica que exista una nueva fila en la tabla indicada que coincida con los datos enviados 
        // por el array asociativo
        private function new_row_exists($table_name = "", $array = [])
        {
            
            //var_dump($array);

            if ($table_name !== "" && count($array) > 0)
            {
                
                $query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1";
                $statement = $this->conn->prepare($query);
                $statement->execute();
                //var_dump($query); die();
                $result = $statement->fetchAll();
                
                //var_dump($result);
                // agregamos el ID obtenido a la data original para la comparacion
                $array['id'] = (count($result) > 0) ? $result[0]['id'] : 0;
                
                if ( is_array($result) )
                {
                    foreach ($result as $item) {

                        if ( is_array($item) && count($item) > 0  )
                        {
                            // comparamos dos array asociativos y devolvemos las coincidencias
                            // que coincidan tanto en clave y valor como en nombre asociativo y valor
                            $new_array = array_intersect_assoc($item, $array);

                            //var_dump($new_array);

                            // Si el nuevo array coincide con el array enviado devuelve true
                            if( count($new_array) == count($array) )
                            {
                                return true;
                            }
                        }
                        
                    }
                }
            }

            return false;

        }

        # ----------------------------------------------------
        // inserta un registro en una tabla de la base de datos
        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
        // devuelve true de los contrario false
        public function insert($table_name = "", $array = [] )
        {
           
            if ($this->conn && ( is_array($array) && count($array) > 0 ) )
            {
                //var_dump($array);

                $i_counter = 0; $u_counter = 0; $query = "";
    
                $query .= "INSERT INTO $table_name (";
                
                // recorre el array agregando los campos de la tabla a la consulta
                foreach($array as $key => $value)
                {
                    if ( $u_counter+1 !== count($array) && ($key !== 'id') )
                    {
                        $query .= "$key, ";
                    }
                    else if ( $key !== 'id' )
                    {
                        $query .= "$key) VALUES (";
                    }
                    $u_counter++;
                }
    
                // recorre el array agregando los valores de cada campo de la tabla a la consulta
                foreach($array as $key => $value)
                {
                    if ( $i_counter+1 !== count($array) && $key !== 'id' )
                    {
                        $query .= is_string($value) ? "'$value', " : "$value, ";
                    }
                    else if ( $key !== 'id' )
                    {
                        $query .= is_string($value) ? "'$value')" : $value.")";
                    }
                    $i_counter++;
                }
                
                //var_dump($query); 

                try {
                    
                    $statement = $this->conn->prepare($query);                
                    $statement->execute();
                    
                    return true;
                    
                } catch (PDOException $e) {
                    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
                    die();                    
                }
                
            }

            return false;

        }

        //------------------------------------------------------
        // Verifica que exista un fila en la tabla indicada coincida con los datos enviados 
        // por el array asociativo mediante el campo id
        private function row_was_updated($table_name = "", $array = [])
        {            
            if ($table_name !== "" && count($array) > 0 && ( isset($array['id']) || isset($array['coworker_id']) || isset($array['id_visitant']) ) )
            {                  
                if ( isset($array['id']) )
                {
                    $query = "SELECT * FROM $table_name WHERE id = " . $array['id'] . " ORDER BY id DESC LIMIT 1";
                }
                else if ( isset($array['id_visitant']) )
                {
                    $query = "SELECT * FROM $table_name WHERE id_visitant = " . $array['id_visitant'] . " ORDER BY id DESC LIMIT 1";
                }
                else if ( isset($array['coworker_id']) )
                {
                    $query = "SELECT * FROM $table_name WHERE coworker_id = " . $array['coworker_id'] . " ORDER BY id DESC LIMIT 1";
                }
                                
                $statement = $this->conn->prepare($query);
                $statement->execute();

                $result = $statement->fetchAll();
                    
                

                if ( is_array($result) )
                {
                    foreach ($result as $item) {

                        if ( is_array($item) && count($item) > 0  )
                        {

                            // comparamos dos array asociativos y devolvemos las coincidencias
                            // que coincidan en clave como en nombre asociativo
                            $new_array = array_intersect_key($item, $array);
                            
                            // Si el nuevo array coincide con el array enviado devuelve true
                            if( count($new_array) == count($array) )
                            {
                                return true;
                            }
                        }
                        
                    }
                }
            }

            return false;

        }


        # ----------------------------------------------------
        // actualiza todos los registros en una tabla de la base de datos que cumplan una condicion
        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
        // devuelve true de los contrario false
        public function updateAll($table_name = "", $fields_array = [] , $where_conditions = [] )
        {

            if ( 
                $this->conn && 
                ( is_array($fields_array) && count($fields_array) > 0 ) && 
                ( is_array($where_conditions) &&  count($where_conditions) > 0 )
               )
            {


                $i_counter = 0; $query = "";
    
                $multi_query = "UPDATE $table_name SET ";
                
                $mysqli_conn = $this->get_mysqli(DB_CONFIG);

                // recorre el array agregando los campos de la tabla a la consulta
                foreach($fields_array as $key => $value)
                {
                                    
                    if ($i_counter+1 < count($fields_array) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value', " : "$value, ";

                    }
                    elseif ( $i_counter+1 == count($fields_array) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value' " : "$value ";
                    }
                    $i_counter++;
                }
                
                $query .= " WHERE ";

                $i_counter = 0;
                foreach($where_conditions as $key => $value)
                {
                                    
                    if ($i_counter+1 < count($where_conditions) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value' AND " : "$value AND ";

                    }
                    elseif ( $i_counter+1 == count($where_conditions) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value'" : "$value";
                    }
                    $i_counter++;
                }                

                $multi_query .= $query;

                //var_dump($multi_query); die();

                if ($mysqli_conn)
                {                                        
                    $result = $mysqli_conn->multi_query($multi_query);                                    
                    return true;
                }
                
            }

            return false;

        }

        # ----------------------------------------------------
        // actualiza un registro en una tabla de la base de datos
        // recibe el nombre de la tabla y una array asociativo array('nombre_campo' => 'valor_campo')
        // devuelve true de los contrario false
        public function update($table_name = "", $array = [] )
        {

            if (
                $this->conn && 
                ( count($array) > 0 ) && 
                ( isset($array['id']) || isset($array['coworker_id']) || isset($array['id_visitant']) ) 
                )
            {
                
                $id = isset( $array['id'] ) ? $array['id'] : '';
                $coworker_id = isset($array['coworker_id']) ? $array['coworker_id'] : '';
                $id_visitant = isset($array['id_visitant']) ? $array['id_visitant'] : '';

                $i_counter = 0; $query = "";
    
                $query .= "UPDATE $table_name SET ";
                
                // recorre el array agregando los campos de la tabla a la consulta
                foreach($array as $key => $value)
                {
                    if ( $i_counter+1 !== count($array) && ( $key !== "id" || $key !== "coworker_id" || $key !== "id_visitant" ) )
                    {                        
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value', " : "$value, ";
                    }
                    else if ( $i_counter+1 == count($array) && $key !== "id_visitant" && isset($array['id_visitant']) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value' " : "$value ";
                        $query .= "WHERE id_visitant = " . $id_visitant;
                    }
                    else if ( $i_counter+1 == count($array) && $key !== "coworker_id" && isset($array['coworker_id']) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value' " : "$value ";
                        $query .= "WHERE coworker_id = " . $coworker_id;
                    }
                    else if ( $i_counter+1 == count($array) && $key !== "id" && isset($array['id']) )
                    {
                        $query .= "$key = ";
                        $query .= is_string($value) ? "'$value' " : "$value ";
                        $query .= "WHERE id = ";
                        $query .= is_string($id) ? "'$id'" : "$id";
                    }
                    $i_counter++;
                }
                
                //if ( $table_name == "cvmj_identification_type" ){ var_dump($query); die();}
                                    
                $statement = $this->conn->prepare($query);
                $statement->execute();

                // verifica que la fila este actualizada
                if ( $this->row_was_updated($table_name, $array) )
                {
                    return true;
                }
                
            }

            return false;

        }
        // cuenta las filas de una tabla
        // devuelve el valor de los contrario false
        private function count_rows($table_name)
        {
            $query = "";

            if ( $this->conn && isset($table_name) )
            {
                $query .= "SELECT count(*) FROM $table_name";

                $statement = $this->conn->prepare($query);                
                $statement->execute();

                $result = $statement->fetchAll();

                if ( is_array($result) && count($result) > 0)
                {
                    return $result[0][0];
                }
            }

            return false;
        }

        # ----------------------------------------------------
        // elimina una fila de una tabla por el campo indicado y la palabra clave exacta.
        // devuelve true de los contrario false
        public function delete_table_row(
            $table_name,
            $keyword,
            $filter
            )
        {
            
            //var_dump($filter); die();

            if ($this->conn && isset($table_name) && isset($keyword) && isset($filter)  )
            {
                // cuenta las filas de una tabla
                $start_count_rows = $this->count_rows($table_name);

                //var_dump($start_count_rows); 

                $query = "";

                if ( isset($keyword) && isset($filter) )
                {
                    $query .= "DELETE FROM $table_name WHERE $filter = $keyword";
                }
                    
                //var_dump($query);

                $statement = $this->conn->prepare($query);                
                $statement->execute();

                // cuenta las filas de una tabla
                $end_count_rows = $this->count_rows($table_name);

                //var_dump( (int)($start_count_rows-1) == (int)$end_count_rows ); die();

                // verifica que la fila este eliminada
                if ( (int)($start_count_rows-1) == (int)$end_count_rows )
                {
                    return true;
                }
                
            }

            return false;

        }

        // Crear una base de datos y sus tablas con los datos a traves de un conjunto de 
        // archivos .sql o un unico archivo sql.
        // si la base de datos existe crear las tablas y sus registros
        // tambien crear todo las vistas, procedimientos, disparadores, etc.
        // recibe la ruta que contiene los archivos scripts
        public function create_schema_by_files_scripts( $array_with_sql_scripts_files )
        {
            // incrementamos el tiempo de ejecucion del escript en segundos
            set_time_limit(300); // 300 = 5 minutos
            
            if ( $this->conn && is_array( $array_with_sql_scripts_files) && count($array_with_sql_scripts_files) > 0 )
            {
                try {
                    
                    // se invoca un a conexion mysqli para poder crear los trigger y store procedure;
                    // con PDO no se puede hacer esto.
                    $mysqli_conn = $this->get_mysqli(DB_CONFIG);

                    foreach ( $array_with_sql_scripts_files as $sql_file ) {
                        
                        if ( is_file($sql_file) )
                        {
                            $sql_file_opened = fopen( $sql_file , 'r' );
                            $sql_script = "";
                            while( !feof($sql_file_opened) ) {                                
                                $getLine = fgets($sql_file_opened);
                                // saltamos las lineas de comentarios
                                if ( ! (stripos($getLine,"--") > -1 || stripos($getLine,"#") > -1) )
                                {                                    
                                    $sql_script = $sql_script.$getLine;
                                }
                            }
    
                            fclose($sql_file_opened);

                            //removemos el doble espaciado innesario, ex. "  " -> " ". ver functions.php
                            $sql_script = trim_double($sql_script);

                            //removemos los espacios en blanco al inicio-fin y saltos de linea: ex. \n\r    
                            $query = trim(preg_replace('/\s\s+/', ' ', $sql_script));

                            $start = "create table"; $end = ";";
                            $end_length = 0; $delimiter_was_set = false;                            
                            while (strlen($query) > 0 || $end_length > -1 ) {
                                
                                $query = trim($query);
                                
                                $end_length = -1;
                                
                                // tables
                                if ( stripos( $query, 'create table' ) > -1 && stripos( $query, 'create table' ) < 24 )
                                {
                                    $start = "create table"; $end = ";";
                                    $end_length = strlen($end);                                    
                                }
                                // create or replace view
                                else if ( 
                                    stripos( $query, 'create or replace view' ) > -1 && stripos( $query, 'create or replace view' ) < 24 )
                                {
                                    $start = "create or replace view"; $end = ";";
                                    $end_length = strlen($end);                                    
                                }
                                // create view
                                else if ( stripos( $query, 'create view' ) > -1 && stripos( $query, 'create view' ) < 24)
                                {
                                    $start = "create view"; $end = ";";
                                    $end_length = strlen($end);                                    
                                }
                                // insert to tables
                                else if ( stripos( $query, 'insert into' ) > -1 && stripos( $query, 'insert into' ) < 24 )
                                {
                                    $start = "insert into"; $end = ";";
                                    $end_length = strlen($end);                                    
                                }
                                // trigger
                                else if ( stripos( $query, 'DELIMITER //' ) > -1 && stripos( $query, 'DELIMITER //' ) < 24  )
                                {
                                    $start = "DELIMITER //"; $end = "DELIMITER ;";
                                    $delimiter = substr($start,strlen("DELIMITER "));
                                    $end_length = strlen($end);                                    
                                }
                                // store_procedure
                                else if ( stripos( $query, 'DELIMITER $$' ) > -1 && stripos( $query, 'DELIMITER $$' ) < 24 )
                                {
                                    $start = "DELIMITER $$"; $end = "DELIMITER ;";
                                    $delimiter = substr($start,strlen("DELIMITER "));
                                    $end_length = strlen($end);                                    
                                }                                

                                $start_pos = stripos($query,$start);
                                $end_pos = stripos($query,$end)+$end_length;
                                
                                $query_to_execute = substr($query, $start_pos,$end_pos);
                                $query = str_ireplace($query_to_execute,'',$query);
                                
                                $query = trim($query);
                                
                                //var_dump('execute',$query_to_execute);
                                //var_dump("query",$query);

                                if ( stripos($query_to_execute, "DELIMITER") > -1)
                                {
                                    // se eliminan de la cande los DELIMITER
                                    $query_to_execute = trim(substr($query_to_execute, strlen($start)));
                                    $query_to_execute = trim(substr($query_to_execute, 0,strlen($query_to_execute)-strlen($end)));
                                    $query_to_execute = str_ireplace('END '.$delimiter,'END ;',$query_to_execute);                                    
                                   
                                    if ($mysqli_conn)
                                    {                                        
                                        $result = $mysqli_conn->multi_query($query_to_execute);                                    
                                    }
                                }
                                else
                                {                                    
                                    $statement = $this->conn->prepare($query_to_execute);                
                                    $result = $statement->execute();
                                }                                
                                
                                
                                if ( strlen($query) == 0 ) {  $end_length = -1;  }                                
                                
                            }
                        }
                    };
                  
                    if (is_object($mysqli_conn) && $mysqli_conn )
                    {
                        // se cierra la conexion mysqli abierta.
                        $this->close_mysqli($mysqli_conn);
                    }

                    return true;

                } catch (PDOException $e) {
                    return false;
                }
                
            }

            return false;
        }

        // elimina los constraint de las tablas indicadas en base de datos mysql
        // deprecated -> cuando se elimina una tabla automaticamente se eliminan los constraint
        private function remove_constraints_from_module_tables( $result ){
            
            try {

                    function recursive_drop_constraints($conn,$array_with_entities_names,$key,$c)
                    {
                                           
                        foreach ($array_with_entities_names as $entity_name) {
                            
                            if ( $key == 0 && isset($entity_name) )
                            {                            
                                $query = "SELECT 
                                    TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                                    FROM
                                        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                                            WHERE
                                                REFERENCED_TABLE_SCHEMA = '".DB_CONFIG['db_name']."' AND
                                                    REFERENCED_TABLE_NAME = '$entity_name'";
    
                                $sh = $conn->prepare($query);
    
                                $sh->execute(); 
                                $response = $sh->fetchAll();
                                
                                //var_dump($result); die();
    
                                if ($response !== false && count($response) > 0){
                                    
                                    //var_dump($result); die();
    
                                    for ($i=0; $i < count($response) ; $i++) { 
                                        
                                        foreach ($response[$i] as $asoc_name => $table_name ) {                                    
    
                                            //var_dump($result[$i]['CONSTRAINT_NAME']); die();
    
                                            $query = "ALTER TABLE ".DB_CONFIG['db_name'].".$table_name DROP constraint ".$response[$i]['CONSTRAINT_NAME'].";";
                                            //var_dump($query); die();
    
                                            $sh = $conn->prepare($query);
                                            $sh->execute();
                                            //$sh = $sh->fetch();
                                        }
                                    }
    
                                }                                                                
                                
                            }
                               
                        }
                        
                        return true;
                    }

                    foreach ($result[0] as $key => $value) {
                            
                        if ( is_numeric($key) && strlen($value) > 0 ) 
                        {
                            $string_with_entities_names = $value;
    
                            $array_with_entities_names = array_reverse( explode( ',' , $string_with_entities_names ) );     
                            
                            $c=0;                            
                            return recursive_drop_constraints($this->conn,$array_with_entities_names,$key,$c);
                        }

                    }                   

                    return false;

            } catch (PDOException $e) {
                
                return false;
            }


        }

        // obtiene un array con las cadenas que contienen los nombres de tablas guardadas en la base de datos.
        // devuelve un array si los encuentra de lo contrario false
        private function get_module_entities_names_by_module_id( $module_id )
        {
            try {                

                $query = "SELECT 
                                tables_name,
                                views_name,
                                store_procedures_name
                            FROM 
                            " . PREFIX . "module_status
                            WHERE 
                                id = '$module_id' ";

                $statement = $this->conn->prepare($query);                
                $statement->execute();

                $result = $statement->fetchAll();

                if ( is_array($result) && count($result) > 0 && isset($result[0]) )
                {
                    return $result;
                }
                
                return false;

            } catch (PDOException $e) {
                
                return false;
            } 
        }
        // eliminar las entidades de la base de datos que fueron creadas por el modulo 
        // en cuestion. Recibe el Id del modulo.
        // devuelve true de lo contrario false
        public function remove_module_schema_from_database( $module_id )
        {          
            //ini_set('memory_limit','1024M');

            try {

                $result = $this->get_module_entities_names_by_module_id( $module_id );

                if ( is_array($result) && count($result) > 0 && isset($result[0]) )
                {
                    // deprecated -> cuando se elimina la tabla automaticamente se eliminan los constraint
                    //$constraints_were_removed = $this->remove_constraints_from_module_tables( $result );
                    $constraints_were_removed = true;
                   
                    if ( $constraints_were_removed )
                    {
                        
                        function recursive_execute($conn,$array_with_entities_names,$key,$c)
                        {

                            $entity_name = isset($array_with_entities_names[$c]) ? $array_with_entities_names[$c] : null;
                            //var_dump($entity_name); die();                    
                            if ( $key == 0 || $key == 1 && isset($entity_name) )
                            {                            
                                $sh = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE TABLE_SCHEMA = '".DB_CONFIG['db_name']."' and TABLE_NAME = '$entity_name';");
                                $sh->execute();
                                $sh = $sh->fetch();                                    
                                
                                if ( $sh[0] == 1 ) {
                                    // my_table exists                                    
                                    if ( $key == 0 ) {$query = "DROP TABLE ";}
                                    if ( $key == 1 ) {$query = "DROP VIEW ";}
                                    
                                    $query .= $entity_name;
                                    
                                    $statement = $conn->prepare($query);                
                                    $statement->execute();
                                    $response = $statement->fetch();
                                    
                                }

                                if ($c+1 <= count($array_with_entities_names) )
                                {
                                    $c++;
                                    recursive_execute($conn,$array_with_entities_names,$key,$c);
                                }
                            }
    
                            if ( $key == 2 )
                            {
                                
                                $sh = $conn->prepare("SELECT COUNT(*) FROM information_schema.routines WHERE ROUTINE_NAME = $entity_name");
                                $sh->execute(); $sh = $sh->fetch();
                                if ( $sh[0] == 1 && isset($entity_name) ){
                                    // el store procedure existe
                                    if ($key == 2 ) {$query = "DROP STORE PROCEDURE ";}
    
                                    $query .= $entity_name;
                                    
                                    $statement = $conn->prepare($query);                
                                    $statement->execute();
                                    
                                }
                                
                                if ($c+1 <= count($array_with_entities_names) )
                                {
                                    $c++;
                                    recursive_execute($conn,$array_with_entities_names,$key,$c);
                                }
                                
                            }
    
                            
                        }
                        //var_dump($result); die();
                        foreach ($result[0] as $key => $value) {
                            
                            if ( is_numeric($key) && strlen($value) > 0 ) 
                            {
                                $string_with_entities_names = $value;
                                
                                $array_with_entities_names = array_reverse( explode( ',' , $string_with_entities_names ) );     
                                $c=0;
                                
                                recursive_execute($this->conn,$array_with_entities_names,$key,$c);
                            } 
    
                        }
    
                        return true;
                    }
                }

                return false;

            } catch (PDOException $e) {
                
                return false;
            }

        }

    }