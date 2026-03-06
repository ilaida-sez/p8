<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Подтверждение кода</title>
    <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-menu">
        <a href=#><img src="img/logo1.png"/></a>
        <div class="name">
            <a href="index.php">
                <div class="subname">БЗОПАСНОСТЬ ВЕБ-ПРИЛОЖЕНИЙ</div>
                Пермский авиационный техникум им. А. Д. Швецова
            </a>
        </div>
    </div>
    <div class="space"></div>
    <div class="main">
        <div class="content">
            <div class="login">
                <div class="name">Подтверждение входа</div>
                <div class="sub-name">Введите 6-значный код:</div>
                <input type="text" id="code" maxlength="6" placeholder="000000">
                <input type="button" class="button" value="Подтвердить" onclick="verify()">
                <img src="img/loading.gif" class="loading" style="display:none;">
                <div id="message" style="margin-top:10px; color:red;"></div>
            </div>
        </div>
    </div>
    <script>
        function verify() {
            var code = document.getElementById('code').value;
            if(code.length != 6) {
                document.getElementById('message').innerText = 'Введите 6 цифр';
                return;
            }

            $('.loading').show();
            var data = new FormData();
            data.append('code', code);

            $.ajax({
                url: 'ajax/verify_code.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('.loading').hide();
                    if(res == 'success') {
                        window.location.href = 'user.php';
                    } else if(res == 'invalid') {
                        document.getElementById('message').innerText = 'Неверный код';
                    } else if(res == 'expired') {
                        document.getElementById('message').innerText = 'Код истёк';
                    } else if(res == 'no_code') {
                        document.getElementById('message').innerText = 'Сессия истекла, войдите заново';
                        setTimeout(() => { window.location.href = 'login.php'; }, 2000);
                    } else {
                        document.getElementById('message').innerText = 'Ошибка: ' + res;
                    }
                }
            });
        }
    </script>
</body>
</html>