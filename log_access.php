<?php
require_once 'db.php';

$public_ip_client = $_SERVER['REMOTE_ADDR'];
$tstamp = (new DateTime()) -> format('Y-m-d H:i:s');

$pdo -> query("INSERT INTO page_requests (ip, tstamp) VALUES ('$public_ip_client', '$tstamp')");

?>
