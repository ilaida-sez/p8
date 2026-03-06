<?php
session_start();
include("../settings/connect_datebase.php");

$code = $_POST['code'];

if(!isset($_SESSION['auth_code']) || !isset($_SESSION['code_expire']) || !isset($_SESSION['temp_user_id'])) {
    echo "no_code";
    exit();
}

if(time() > $_SESSION['code_expire']) {
    echo "expired";
    exit();
}

if($code != $_SESSION['auth_code']) {
    echo "invalid";
    exit();
}

$user_id = $_SESSION['temp_user_id'];
$user = $mysqli->query("SELECT * FROM `users` WHERE `id`=$user_id")->fetch_assoc();

if($user) {
    // генерируем токен сессии
    $session_token = bin2hex(random_bytes(32));
    $current_time_db = date('Y-m-d H:i:s');
    
    // обновляем данные в бд
    $mysqli->query("UPDATE `users` SET 
        `session_token` = '$session_token',
        `last_activity` = '$current_time_db'
        WHERE `id` = {$user['id']}");
    
    $_SESSION['user'] = $user['id'];
    $_SESSION['session_token'] = $session_token;
    
    unset($_SESSION['auth_code']);
    unset($_SESSION['code_expire']);
    unset($_SESSION['temp_user_id']);
    unset($_SESSION['login_email']);
    
    echo "success";
} else {
    echo "user_not_found";
}
?>