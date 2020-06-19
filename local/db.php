<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

error_reporting(E_ERROR);
/*
$sesid = str_replace('.','',$_SERVER['REMOTE_ADDR']) ;
session_id($sesid);
session_start();

$user = isset($_POST['user']) ? $_POST['user'] : $_SESSION['user'];
$pass = isset($_POST['pass']) ? $_POST['pass'] : $_SESSION['pass'];

if (!$user or !$pass){
    die (json_encode(array('code' => 0, 'msg' => 'Error, debe completar usuario y contraseña')));
}*/

$host = '127.0.0.1';
$port = '3306';
$database = 'serk';
$user = 'root';
$pass = 'hola123';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
/*    $pdo = new PDO("mysql:dbname=$database;
                           host=$host;
                           port=$port;
                           user=$user;
                           password=$pass");*/

    $_SESSION['user'] = $user;
    $_SESSION['pass'] = $pass;

}catch(PDOException $e) {

    die (json_encode(
            array('code' => 0,
                  'msg' => 'Verifique sus credenciales',
                  'server_data' => $e -> getMessage()
        )));
}
