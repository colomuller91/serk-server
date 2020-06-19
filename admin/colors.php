<?php
require_once '../db.php';
require_once 'login_functions.php';
$headers = getallheaders();
if (!initsession($headers['Authorization'])) print responseArray('401','No autorizado');

$color_id = (isset($_GET['id']) and !empty($_GET['id'])) ? $_GET['id'] : 0;
$name = (isset($_GET['name']) and !empty($_GET['name'])) ? $_GET['name'] : '';
$name = strtolower($name);

$sql_colors = "
            SELECT  *
            FROM colors 
            where (lower(name) like '$name' and '$name' != '') 
                   or (id = $color_id)
                   or (0 = $color_id and '' = '$name')
            order by name
";

try {
    $colors = $pdo -> query($sql_colors) -> fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    print $e->getMessage();
}

if (count($colors) > 0){
    responseArray('200', $colors);

}else{
    responseArray('200', 'No encontré el producto');
}
