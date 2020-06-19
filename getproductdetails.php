<?php
require_once 'db.php';

$category = (isset($_GET['category'])) ? $_GET['category'] : 0;
$sizes = (isset($_GET['sizes']) && !empty($_GET['sizes'])) ? $_GET['sizes'] : 0;
$nosize = ($sizes == 0) ? 'true' : 'false';

$sql_products = "
SELECT  pr.id,
        pr.name as name,
        pr.colors,
        pr.price,
        si.id as size_id,
        si.name as size,
        ex.quantity as qty,
        pr.category_id,
        ca.name as category_name,
        di.discount_rate
FROM products pr
         join existences ex on ex.product_id = pr.id
         join sizes si on ex.size_id = si.id
         join categories ca on ca.id = pr.category_id
     left join discounts di on di.category_id = ca.id
where  pr.id
";

try {
    $stmt = $pdo -> prepare($sql_products);
    $stmt -> execute(
        array('category' => $category, 'nosize' => $nosize)
    );
    $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    print $e->getMessage();
}

$product_list = [];
//var_dump($products);
foreach ($products as $id => $prod){
    $product_list[$prod['id']]['id'] = $prod['id'];
    $product_list[$prod['id']]['name'] = $prod['name'];
    $product_list[$prod['id']]['price'] = $prod['price'];
    $product_list[$prod['id']]['colors'] = $prod['colors'];
    $product_list[$prod['id']]['sizes'][] = array('id' => $prod['size_id'],'name' => $prod['size'], 'qty' => $prod['qty']);
    $product_list[$prod['id']]['category_id'] = $prod['category_id'];
    $product_list[$prod['id']]['category_name'] = $prod['category_name'];
}

//print_r($product_list);
print_r(json_encode($product_list));


