<?php
session_start();
include("../settings/connect_datebase.php");

if(isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    // очищаем инфу о сессии в бд
    $mysqli->query("UPDATE `users` SET `session_token` = NULL, `last_activity` = NULL WHERE `id` = $user_id");
}

// очищаем сессию
$_SESSION = array();
session_destroy();

echo "success";
?>