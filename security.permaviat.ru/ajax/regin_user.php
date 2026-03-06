<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];
$password = $_POST['password'];

// проверка существования пользователя
$check = $mysqli->query("SELECT * FROM `users` WHERE `login`='$login'");
if($check->num_rows > 0) {
    echo "-1";
    exit();
}

// хешируем пароль
$hash = password_hash($password, PASSWORD_DEFAULT);

// сохраняем
$mysqli->query("INSERT INTO `users` (`login`, `password`, `roll`) VALUES ('$login', '$hash', 0)");

// получаем ID
$user = $mysqli->query("SELECT * FROM `users` WHERE `login`='$login'")->fetch_row();
$id = $user[0];

$_SESSION['user'] = $id;
echo $id;
?>