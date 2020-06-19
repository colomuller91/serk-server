<?php
require_once '../db.php';
require_once 'login_functions.php';
$headers = getallheaders();
if (!initsession($headers['Authorization'])) print responseArray('401','No autorizado');

$category_id = (isset($_GET['id']) and !empty($_GET['id'])) ? $_GET['id'] : 0;
$name = (isset($_GET['name']) and !empty($_GET['name'])) ? $_GET['name'] : '';
$name = strtolower($name);

$sql_categories = "
            SELECT  *
            FROM categories 
            where (lower(name) like '$name' and '$name' != '') 
                   or (id = $category_id)
                   or (0 = $category_id and '' = '$name')
            order by name
";

try {
    $categories = $pdo -> query($sql_categories) -> fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    print $e->getMessage();
}

if (count($categories) > 0){
    responseArray('200', $categories);

}else{
    responseArray('200', 'No encontré el producto');
}
