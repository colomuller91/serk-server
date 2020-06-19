<?php
require_once '../db.php';
require_once 'login_functions.php';
$headers = getallheaders();
//var_dump($headers);
if (!initsession($headers['Authorization'])) print responseArray('401','No autorizado');

responseArray('333', 'Lo que sea');
