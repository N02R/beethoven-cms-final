<?php
session_start();
// كلمة مرور بسيطة للتجربة، في المستقبل نستخدم Hash
$admin_password = "your_secret_password"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_admin'] = true;
        header("Location: /"); // العودة للرئيسية بعد الدخول
        exit;
    } else {
        $error = "كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<body>
    <form method="POST">
        <h2>دخول المدير</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <input type="password" name="password" placeholder="أدخل كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
</body>
</html>
