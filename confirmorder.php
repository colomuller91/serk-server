<?php
require_once 'db.php';
require_once 'sendmail.php';

$order = json_decode(file_get_contents("php://input"), true);

if (!$order) exit();

$nextval = "SELECT AUTO_INCREMENT
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'u650673078_serk'
AND TABLE_NAME = 'orders'";

foreach ($pdo->query($nextval) as $row) {
    $newOrderId = $row['AUTO_INCREMENT'];
}
$sql_insert_order = "
    INSERT INTO orders(id, client_firstname, client_lastname, client_phone, datetime)
    VALUES ( $newOrderId, 
    '".$order['client']['firstname']."', 
    '".$order['client']['lastname']."', 
    '".$order['client']['phone']."',
     current_timestamp );
";

try {
    $stmt = $pdo -> prepare($sql_insert_order);
    $res = $stmt -> execute(
       /* array('firstname' => $order['client']['firstname'],
              'lastname' => $order['client']['lastname'],
              'phone' => $order['client']['phone'] )*/
    );
    if (!$res){
        print ( json_encode( ['status' => false, 'message' => 'Error al crear orden', 'error' => $pdo->errorInfo(), 'sql' => $sql_insert_order] ) );
    }
}catch (PDOException $e){
    print $e->getMessage();
}

if ($res){
    foreach ($order['orderItems'] as $item){

        $sql_insert_item = "
            INSERT INTO orders_item(order_id, product_id, size_id, color_id, quantity, price)
            VALUES ($newOrderId, 
            ".$item['id'].",
            ".$item['size'].",
            ".$item['color'].",
            ".$item['quantity'].",
            ".$item['price']."
            );
        ";

        try {
            $stmt = $pdo -> prepare($sql_insert_item);
            $res = $stmt -> execute(
          //    array('product' => $item['id'], 'size' => $item['size'], 'qty' => $item['quantity'], 'price' => $item['price'] )
            );

            if (!$res){
                print ( json_encode( ['status' => false, 'message' => 'Error al crear detalle de orden', 'error' => $pdo->errorInfo(),'sql' => $sql_insert_item] ) );
            }

        }catch (PDOException $e){
            print $e->getMessage();
        }
    }
}


if ($res){
    send_mail($newOrderId, $order);
}

?>
