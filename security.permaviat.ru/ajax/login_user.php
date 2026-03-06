<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];
$password = $_POST['password'];

$login = $mysqli->real_escape_string($login);

// ищем пользователя
$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='$login' LIMIT 1");

if($query_user->num_rows == 1) {
    $user_read = $query_user->fetch_assoc();
    
    if(password_verify($password, $user_read['password'])){
        
        // генерируем код
        $code = sprintf("%06d", random_int(0, 999999));
        
        // сохраняем в сессию
        $_SESSION['temp_user_id'] = $user_read['id'];
        $_SESSION['auth_code'] = $code;
        $_SESSION['code_expire'] = time() + 600;
        $_SESSION['login_email'] = $login;
        
        // возвращаем код на фронт
        echo "code_sent|" . $code;
        
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>