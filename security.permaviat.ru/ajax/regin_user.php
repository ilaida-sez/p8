<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];
$password = $_POST['password'];

if(strpos($login, '@') === false) {
    echo "invalid_email";
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO `users` (`login`, `password`, `roll`) VALUES ('$login', '$hash', 0)";
$mysqli->query($sql);

if($mysqli->affected_rows > 0) {
    $res = $mysqli->query("SELECT * FROM `users` WHERE `login`='$login'");
    $user = $res->fetch_row();
    $_SESSION['user'] = $user[0];
    echo $user[0];
} else {
    echo "db_error";
}
?>