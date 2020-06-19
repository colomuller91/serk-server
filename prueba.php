<?php
//date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once 'db.php';

var_dump( new DateTime());

foreach ($pdo->query('select current_timestamp') as $row) {
    var_dump($row);
}


?>
