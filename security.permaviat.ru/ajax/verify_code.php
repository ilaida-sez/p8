<?php
session_start();
include("../settings/connect_datebase.php");

$code = $_POST['code'];

// Проверяем правильные имена переменных
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
    $_SESSION['user'] = $user['id'];
    unset($_SESSION['auth_code']);
    unset($_SESSION['code_expire']);
    unset($_SESSION['temp_user_id']);
    unset($_SESSION['login_email']);
    echo "success";
} else {
    echo "user_not_found";
}
?>