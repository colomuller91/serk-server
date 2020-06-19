<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

error_reporting(E_ERROR);

ini_set('session.use_strict_mode', 1);


function initsession($token, $resMode = 0) {
    $token = str_replace(' ','',$token);
    session_id($token);
    session_start();

    if (!empty($_SESSION['time']) && $_SESSION['time'] > time() - 1000) {
        //session ok
        $expired = false;
        $_SESSION['time'] = time();
        if ($resMode == 0){
            return true;
        }
        responseArray('200', array('status' => 'ok', 'token' => $token));

    }else{
        if ($resMode == 0){
            return false;
        }
        //session expired or not logged
        session_destroy();
        $expired = true;
    }

    if (count($_SESSION) == 0 or $expired){
        //needs to login
        $resLogin = login();
        if ($resLogin){
            responseArray('200', array('status' => 'ok', 'token' => session_id() ));
        }else{
            session_destroy();
            responseArray('201','No autorizado');
        }

    }

}

function login(){
    $user = 'test';
    $pass = 'test';
    if ($_POST['username'] == $user and $_POST['password'] == $pass){
        session_destroy();
        session_start();

        $session_id = session_create_id('SerkAdmin-');
        session_id($session_id);
        $_SESSION['time'] = time();

        return true;

    }
    return false;
}

function responseArray($code, $body){
    print json_encode(array('code' => $code, 'body' => $body));
    exit;
}
