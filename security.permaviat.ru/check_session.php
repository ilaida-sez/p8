<?php
function checkActiveSession($mysqli) {
    if(!isset($_SESSION['user']) || !isset($_SESSION['session_token'])) {
        return false;
    }
    
    $user_id = $_SESSION['user'];
    $session_token = $_SESSION['session_token'];
    
    // получаем инфу о сессии из базы
    $query = $mysqli->query("SELECT `session_token`, `last_activity` FROM `users` WHERE `id` = $user_id LIMIT 1");
    
    if($query->num_rows == 1) {
        $user_data = $query->fetch_assoc();
        
        // проверяем токен сессии
        if(empty($user_data['session_token']) || $user_data['session_token'] !== $session_token) {
            return false;
        }
        
        // проверяем время активности (30 минут)
        $last_activity_time = strtotime($user_data['last_activity']);
        $current_time = time();
        
        if(empty($user_data['last_activity']) || ($current_time - $last_activity_time) > 1800) {
            return false;
        }
        
        // обновляем время последней активности
        $current_time_db = date('Y-m-d H:i:s');
        $mysqli->query("UPDATE `users` SET `last_activity` = '$current_time_db' WHERE `id` = $user_id");
        
        return true;
    }
    
    return false;
}

function logoutUser($mysqli) {
    if(isset($_SESSION['user'])) {
        $user_id = $_SESSION['user'];
        // очищаем инфу о сессии в бд
        $mysqli->query("UPDATE `users` SET `session_token` = NULL, `last_activity` = NULL WHERE `id` = $user_id");
    }

    // очищаем сессию
    $_SESSION = array();
    session_destroy();
}
?>