<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

error_reporting(E_ERROR);

date_default_timezone_set('America/Argentina/Buenos_Aires');

$host = 'serk.com.ar';
$port = '3306';
$database = '';
$user = '';
$pass = '';

try {


    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $_SESSION['user'] = $user;
    $_SESSION['pass'] = $pass;

}catch(PDOException $e) {

    die (json_encode(
            array('code' => 0,
                  'msg' => 'Verifique sus credenciales',
                  'server_data' => $e -> getMessage()
        )));
}
