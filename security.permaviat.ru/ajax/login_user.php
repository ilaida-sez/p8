<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];
$password = $_POST['password'];

// ищем пользователя по логину
$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");

$id = -1;
if($user_read = $query_user->fetch_assoc()) {
    if(password_verify($password, $user_read['password'])) {
        $id = $user_read['id'];
    }
}

if($id != -1) {
    $_SESSION['user'] = $id;
}
echo md5(md5($id));
?>