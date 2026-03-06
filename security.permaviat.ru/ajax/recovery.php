<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];

$user = $mysqli->query("SELECT * FROM `users` WHERE `login`='$login'")->fetch_row();
$id = $user ? $user[0] : -1;

if($id != -1) {
    $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
    $password = "";
    for($i = 0; $i < 10; $i++) {
        $password .= $chars[rand(0, strlen($chars)-1)];
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $mysqli->query("UPDATE `users` SET `password`='$hash' WHERE `login`='$login'");
    
}

echo $id;
?>
Danielle2006/