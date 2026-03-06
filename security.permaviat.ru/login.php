<?php
session_start();
include("./settings/connect_datebase.php");

//если пользователь уже авторизован, перенаправляем на нужную страницу
if (isset($_SESSION['user'])) {

    include("./check_session.php");
    if(checkActiveSession($mysqli)) {
        $user_query = $mysqli->query("SELECT `roll` FROM `users` WHERE `id` = ".$_SESSION['user']);
        $user_read = $user_query->fetch_assoc();
        
        if($user_read['roll'] == 0) {
            header("Location: user.php");
        } else if($user_read['roll'] == 1) {
            header("Location: admin.php");
        }
        exit();
    } else {
        logoutUser($mysqli);
    }
}
?>
<html>
<head> 
    <meta charset="utf-8">
    <title> Авторизация </title>
    
    <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-menu">
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
            <div class = "login">
                <div class="name">Авторизация</div>
            
                <div class = "sub-name">Логин:</div>
                <input name="_login" type="text" placeholder="" onkeypress="return PressToEnter(event)"/>
                <div class = "sub-name">Пароль:</div>
                <input name="_password" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
                
                <a href="regin.php">Регистрация</a>
                <br><a href="recovery.php">Забыли пароль?</a>
                <input type="button" class="button" value="Войти" onclick="LogIn()"/>
                <img src = "img/loading.gif" class="loading"/>
            </div>
            
            <div class="footer">
                © КГАПОУ "Авиатехникум", 2020
                <a href=#>Конфиденциальность</a>
                <a href=#>Условия</a>
            </div>
        </div>
    </div>
    
    <script>
        function LogIn() {
            var loading = document.getElementsByClassName("loading")[0];
            var button = document.getElementsByClassName("button")[0];
            
            var _login = document.getElementsByName("_login")[0].value;
            var _password = document.getElementsByName("_password")[0].value;
            
            if(_login == "" || _password == "") {
                alert("Заполните все поля");
                return;
            }
            
            if(_login.indexOf('@') == -1) {
                alert("Введите корректный email (должен содержать @)");
                return;
            }
            
            loading.style.display = "block";
            button.className = "button_diactive";
            
            var data = new FormData();
            data.append("login", _login);
            data.append("password", _password);
            
            $.ajax({
                url: 'ajax/login_user.php',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'html',
                processData: false,
                contentType: false,
                success: function(res) {
                    
                    if(res && res.startsWith('code_sent')) {
                        let parts = res.split('|');
                        let code = parts[1];
                        
                        alert('Код подтверждения: ' + code + '\n\nВведите его на следующей странице');
                        
                        window.location.href = 'verify_code.php';
                        
                    } else if(res == "password_expired") {
                        alert("Ваш пароль истек. Необходимо сменить пароль.");
                        window.location.href = 'change_password_page.php';
                    } else if(res == "already_logged_in") {
                        loading.style.display = "none";
                        button.className = "button";
                        
                        var confirmLogout = confirm("Вы уже авторизованы в другом браузере/устройстве. " +
                                                "Хотите завершить предыдущую сессию и войти здесь?\n\n" +
                                                "Если вы выберете 'Отмена', вход будет невозможен.");
                        
                        if(confirmLogout) {
                            forceLogoutAndLogin(_login, _password);
                        }
                    } else if(res == "mail_error") {
                        alert("Ошибка отправки email. Попробуйте позже.");
                        location.reload();
                    } else if(res == "error") {
                        alert("Логин или пароль неверный.");
                        location.reload();
                    } else {
                        alert("Неизвестная ошибка: " + res);
                        location.reload();
                    }
                    
                    loading.style.display = "none";
                    button.className = "button";
                },
                error: function() {
                    console.log('Системная ошибка!');
                    alert("Системная ошибка!");
                    loading.style.display = "none";
                    button.className = "button";
                }
            });
        }

        function forceLogoutAndLogin(login, password) {
            var loading = document.getElementsByClassName("loading")[0];
            var button = document.getElementsByClassName("button")[0];
            
            loading.style.display = "block";
            button.className = "button_diactive";
            
            var data = new FormData();
            data.append("login", login);
            data.append("password", password);
            data.append("force", "true");
            
            $.ajax({
                url: 'ajax/force_login.php',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'html',
                processData: false,
                contentType: false,
                success: function(res) {
                    
                    if(res && res.startsWith('code_sent')) {
                        let parts = res.split('|');
                        let code = parts[1];
                        
                        alert('Код подтверждения: ' + code + '\n\nВведите его на следующей странице');
                        
                        window.location.href = 'verify_code.php';
                        
                    } else if(res == "password_expired") {
                        alert("Ваш пароль истек. Необходимо сменить пароль.");
                        window.location.href = 'change_password_page.php';
                    } else if(res == "error") {
                        alert("Логин или пароль неверный.");
                        location.reload();
                    } else {
                        alert("Ошибка: " + res);
                        location.reload();
                    }
                    
                    loading.style.display = "none";
                    button.className = "button";
                },
                error: function() {
                    loading.style.display = "none";
                    button.className = "button";
                    alert("Системная ошибка!");
                }
            });
        }
        
        function PressToEnter(e) {
            if (e.keyCode == 13) {
                var _login = document.getElementsByName("_login")[0].value;
                var _password = document.getElementsByName("_password")[0].value;
                
                if(_password != "" && _login != "") {
                    LogIn();
                }
            }
        }
        
    </script>
</body>
</html>