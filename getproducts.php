<?php
require_once 'db.php';

$product_id = (isset($_GET['product_id']) and !empty($_GET['product_id'])) ? $_GET['product_id'] : 0;
if ($product_id>0){
    /* defaults */
    $category = 0;
    $sizes =  0;
    $nosize = 'true';
}else{
    $category = (isset($_GET['category'])) ? $_GET['category'] : 0;
    $sizes = (isset($_GET['sizes']) && !empty($_GET['sizes'])) ? $_GET['sizes'] : 0;
    $nosize = ($sizes == 0) ? 'true' : 'false';

    if (!$nosize){
        $sizes .= ',9';
    }
}

$sql_products = "
SELECT  pr.id,
        pr.name as name,
        pr.colors,
        pr.price,
        si.id as size_id,
        si.name as size,
        co.id as color_id,
        co.name as color,
        ex.quantity as qty,
        pr.category_id,
        ca.name as category_name,
        coalesce(di.discount_rate,0) as category_discount_rate,
        coalesce(dp.discount_rate,0) as product_discount_rate
FROM products pr
         join existences ex on ex.product_id = pr.id
         join sizes si on ex.size_id = si.id
         join categories ca on ca.id = pr.category_id
         join colors co on co.id = ex.color_id
         left join category_discounts di on di.category_id = ca.id and di.enabled=true
         left join product_discounts dp on dp.product_id = pr.id and dp.enabled=true
where ((ca.id = $category and ((si.id in ($sizes)) or $nosize) and ex.quantity > 0) 
   or pr.id = $product_id) and pr.visible
order by pr.name, si.id
";

try {
    $stmt = $pdo -> prepare($sql_products);
    $stmt -> execute(
    // array('category' => $category, 'nosize' => $nosize, 'productId' => $product_id)
    );
    $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
    print $e->getMessage();
}
$product_list = [];
foreach ($products as $id => $prod){
    $aplied_discount = ($prod['product_discount_rate'] > 0)
                            ? $prod['product_discount_rate']
                            : ( ($prod['category_discount_rate'] > 0)
                                    ? $prod['category_discount_rate']
                                    : 0 ) ;

    $product_list[$prod['id']]['id'] = $prod['id'];
    $product_list[$prod['id']]['name'] = $prod['name'];
    $product_list[$prod['id']]['price'] = roundToBase($prod['price'] - ( $prod['price'] * $aplied_discount ),10);
//    $product_list[$prod['id']]['colors'] = $prod['colors'];
    $product_list[$prod['id']]['sizes'][$prod['size_id']] = array('id' => $prod['size_id'],'name' => $prod['size']);
    $product_list[$prod['id']]['colors'][$prod['color_id']] = array('id' => $prod['color_id'],'name' => $prod['color']);
    $product_list[$prod['id']]['existences'][] = array('size_id' => $prod['size_id'], 'color_id' => $prod['color_id'], 'qty' => $prod['qty']);
    $product_list[$prod['id']]['category_id'] = $prod['category_id'];
    $product_list[$prod['id']]['category_name'] = $prod['category_name'];
}

foreach ($product_list as $id => $p_item){
    unset($product_list[$id]['sizes']);
    $product_list[$id]['sizes'] = array_values($p_item['sizes']);

    unset($product_list[$id]['colors']);
    $product_list[$id]['colors'] = array_values($p_item['colors']);

    $arr_images = array();
    if ($product_id>0){
        if (file_exists("images/products/$product_id")){
            foreach (glob("images/products/$product_id/*") as $imagen){
                if (strpos(strtolower($imagen),'big') !== false and is_file($imagen)){
                    $arr_images[] = "$imagen";
                }
            }
        }
        if (count($arr_images) == 0) $arr_images[] = 'images/products/placeholder.jpg';
        $product_list[$id]['images'] = $arr_images;
    }else{
        if (file_exists("images/products/$id")){
            foreach (glob("images/products/$id/1.*") as $imagen){
                $arr_images[] = "$imagen";
                break;
            }
        }
        if (count($arr_images) == 0) $arr_images[] = 'images/products/placeholder.jpg';
        $product_list[$id]['cover_image'] = $arr_images[0];
    }

}



usort($product_list, function($a,$b){ return $a['name'] > $b['name'];  });

print_r(json_encode(
        ($product_id > 0)
            ? array_shift($product_list)
            : $product_list)
);

function roundToBase($amount, $base){
    return round($amount,($base/-10));
}
