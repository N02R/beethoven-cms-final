<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // كود بسيط جداً للتحقق (مستقبلاً سنربطه بكلمة مرور مشفرة في القاعدة)
    if ($_POST['password'] === '1234') { 
        $_SESSION['is_admin'] = true;
        header('Location: /');
        exit;
    }
}
?>
<form method="POST">
    <input type="password" name="password" placeholder="كلمة مرور المدير">
    <button type="submit">دخول لوضع التعديل</button>
</form>
