<?php // target : post-consult

/** Procedemos a obtener el atributo para filtrar los post a mostrar del objeto json */

$current_page = isset($jsonObject->current_page) ? cleanData(trim_double($jsonObject->current_page)) : ""; 

$search = isset($jsonObject->search) ? cleanData(trim_double($jsonObject->search)) : ""; 

$has_filter = false;

if ( isset($search) && $search != "" )
{
    $has_filter = true;
}

/** verificamos que el filter de post exista o devuelve faltan parametros */

if ( !isset($current_page) || empty($current_page) || $current_page  == "" )
{
    // Si el id de formulario no coincide con el id de session la session no es la misma
    // entonces resultado 400.
    on_exception_server_response(400,'Error. Faltan parametros.',$target);
    die();
} 

$current_page = ((int)$current_page > 1) ? $current_page : 1;

/** Procedemos a verificar que el usuario sea administrador o soporte */

/* if ( !isset($user_is_admin_or_support) || empty($user_is_admin_or_support) || !$user_is_admin_or_support )
{
    // Si el usuario no es administrador o soporte entonces resultado 400.
    on_exception_server_response(401,'Error. No esta autorizado para realizar esta acción.',$target);
    die();
} */

/** Procedemos a obtener los datos de la base de datos mediante el id del post */

    // instanciamos la clase ManageDB
    $ManageDB = new Library\Classes\ManageDB;

    if ( $has_filter )
    {
        $filter = "post_title";
        $keyword = $search;
    }
    else
    {
        $filter = "";
        $keyword = "";
    }
    
    // obtenemos los datos del usuario activo
    $result = $ManageDB->get_table_rows(
        $table_name = 'arch_view_all_post',
        $filter = $filter,
        $keyword = $keyword,            
        $limit = POST_LIMIT_PER_PAGE,
        $selected_page = (string)$current_page,
        $array_fields = false,
        $order_by = 'id',
        $order_dir = 'ASC',
        $filter_between = "",
        $array_between = false,
        $strict_mode = true
    );

// si el array fetched no esta seteado y el array esta vacio
if ( ! isset($result['fetched']) )
{
    on_exception_server_response(409,'Error. Contacte al administrador de sistemas.',$target);
    die();
}
/*
function organize_abc_data($data = []){
   

    $abc_letters = ['0-9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z'];

    $array_organized = [];
    
    foreach ($abc_letters as $letter) {

        $array_organized[$letter] = [];        

        // recorremos la data
        for ($i=0; $i < count($data) ; $i++) { 
            
            // obtenemos la primera letra del titulo de cada pos
            $first_letter = strtolower(substr($data[$i]["post_title"],0,1));            
            
            // comparamos la primera letra con el array del abecedario
            if ( $first_letter == $letter )
            {   
                // ingresamos la data en la letra correspondiente
                array_push($array_organized[$letter],$data[$i]);                    
            }
        }

        // eliminamos los index asociativos vacios
        if ( count($array_organized[$letter]) == 0)
        {
            unset($array_organized[$letter]);
        }
        

    }

    return $array_organized;
}

$result['fetched'] = organize_abc_data($result['fetched']); 
*/

// enviamos la respuesta y los datos obtenidos
$response = [
    'status'    => '200',
    'message'   => 'Post Recuperado',
    'data'      => $result
];

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
$response = json_encode($response);
echo $response;