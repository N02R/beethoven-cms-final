<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* حاوية عرض الصورة كخلفية تملأ الشاشة بالكامل */
.bg-container {
    width: 100vw;
    height: 100vh;
    background: url('assets/img/dashboard.png') no-repeat center center;
    background-size: cover;
}

    </style>
</head>
<body>

<div class="bg-container"></div>

</body>
</html>
