<?php
require_once '../db.php';
require_once 'login_functions.php';
$headers = getallheaders();
if (!initsession($headers['Authorization'])) responseArray('401','No autorizado');

responseArray('200', 'Autorizado');

