<?php
session_start();
include("./settings/connect_datebase.php");
include("./check_session.php");

// проверяем активную сессию
if(!checkActiveSession($mysqli)) {
    logoutUser($mysqli);
    header("Location: login.php");
    exit();
}

// проверяем роль пользователя
$user_query = $mysqli->query("SELECT `roll` FROM `users` WHERE `id` = ".$_SESSION['user']);
$user_read = $user_query->fetch_assoc();

// если админ - перенаправляем на админку
if($user_read['roll'] == 1) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE HTML>
<html>
    <head> 
        <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
        <meta charset="utf-8">
        <title> Личный кабинет </title>
        
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="top-menu">
            <a href=# class = "singin"><img src = "img/ic-login.png"/></a>
        
            <a href=#><img src = "img/logo1.png"/></a>
            <div class="name">
                <a href="index.php">
                    <div class="subname">БЗОПАСНОСТЬ ВЕБ-ПРИЛОЖЕНИЙ</div>
                    Пермский авиационный техникум им. А. Д. Швецова
                </a>
            </div>
        </div>
        <div class="space"> </div>
        <div class="main">
            <div class="content">
                <input type="button" class="button" value="Выйти" onclick="logout()"/>
                <div class="name" style="padding-bottom: 0px;">Личный кабинет</div>
                <div class="description">Добро пожаловать: 
                    <?php
                        $user_to_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']);
                        $user_to_read = $user_to_query->fetch_row();
                        
                        echo $user_to_read[1];
                    ?>
                    <br>Ваш идентификатор:
                    <?php
                        echo $user_to_read[0];
                    ?>
                </div>
            
                <div class="footer">
                    © КГАПОУ "Авиатехникум", 2020
                    <a href=#>Конфиденциальность</a>
                    <a href=#>Условия</a>
                </div>
            </div>
        </div>
        
        <script>
            function logout() {
                $.ajax({
                    url         : 'ajax/logout.php',
                    type        : 'POST',
                    data        : null,
                    cache       : false,
                    dataType    : 'html',
                    processData : false,
                    contentType : false, 
                    success: function (_data) {
                        location.reload();
                    },
                    error: function( ){
                        console.log('Системная ошибка!');
                    }
                });
            }
        </script>
    </body>
</html>