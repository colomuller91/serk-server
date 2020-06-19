<?php
require_once '../db.php';
require_once 'login_functions.php';
$headers = getallheaders();
//var_dump($headers);
if (!initsession($headers['Authorization'])) print responseArray('401','No autorizado');

$product_id = (isset($_GET['id']) and !empty($_GET['id'])) ? $_GET['id'] : 0;
$name = (isset($_GET['name']) and !empty($_GET['name'])) ? $_GET['name'] : '';


$sql_products = "
            SELECT  *
            FROM products 
            where (lower(name) like '%toronto%' and '$name' != '') or id = $product_id
            order by name
            limit 10
";

try {
    $stmt = $pdo -> prepare($sql_products);
    $stmt -> execute();
    $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    print $e->getMessage();
}

if (count($products) > 0 and $product_id > 0){
    $sql = "SELECT *
            FROM existences where  product_id = $product_id
            order by id";
    try {
        $existences = $pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        print $e->getMessage();
    }

    $sql = "SELECT * from product_discounts where product_id = $product_id";
    try {
        $discount = $pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        print $e->getMessage();
    }

    $product = array(
        'product' => $products[0],
        'existences' => $existences,
        'discount' => $discount[0]
    );

    responseArray('200', $product);

}else{
    responseArray('404', 'No encontré el producto');
}
