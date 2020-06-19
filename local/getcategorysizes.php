<?php
require_once 'db.php';

$category = (isset($_GET['category'])) ? $_GET['category'] : 0;

$sql_categories = "
SELECT  si.id as size_id,
        si.name as size,
        false as enabled
FROM products pr
         join existences ex on ex.product_id = pr.id
         join sizes si on ex.size_id = si.id
WHERE pr.category_id = :category and ex.quantity > 0
group by si.id, si.name order by si.id
";

try {
    $stmt = $pdo -> prepare($sql_categories);
    $stmt -> execute(array('category' => $category));
    $categories = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}catch ( PDOException $e){
    print $e->getMessage();
}

print_r(json_encode($categories));
