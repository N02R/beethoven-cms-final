<?php
// ابدئي الجلسة فقط إذا لم تكن قد بدأت بالفعل
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إذا كان المدير داخلاً بالفعل، لا داعي لصفحة الدخول، وجهيه للرئيسية
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: /");
    exit;
}

$admin_password = "your_secret_password"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_admin'] = true;
        // تأكدي ألا يكون هناك أي "طباعة" (echo) أو مسافات قبل هذا السطر
        header("Location: /"); 
        exit;
    } else {
        $error = "كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head><meta charset="UTF-8"><title>دخول المدير</title></head>
<body>
    <form method="POST">
        <h2>دخول المدير</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <input type="password" name="password" placeholder="أدخل كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
</body>
</html>
