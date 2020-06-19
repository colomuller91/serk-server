<?php

require_once '../db.php';
require_once 'login_functions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

error_reporting(E_ERROR);

ini_set('session.use_strict_mode', 1);

$headers = getallheaders();
$_POST = json_decode(file_get_contents("php://input"), true);

initsession(str_replace(' ','',$headers['Authorization']) ,1);

//axios sent post data through php://input



