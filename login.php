<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إذا كان المستخدم مسجلاً دخوله بالفعل، يتم توجيهه للرئيسية
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error_message = '';

// معالجة بيانات النموذج عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // إعداد اسم المستخدم وكلمة المرور (يمكنكِ تغييرهما هنا)
    // كلمة المرور الافتراضية هنا هي: bcs_admin_2026
    $correct_username = 'admin';
    $correct_password_hash = password_hash('bcs_admin_2026', PASSWORD_DEFAULT); 

    // التحقق من صحة البيانات
    if ($username === $correct_username && password_verify($password, $correct_password_hash)) {
        // إنشاء الجلسة بنجاح
        $_SESSION['is_logged_in'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $username;
        
        // التوجيه للرئيسية
        header("Location: index.php");
        exit;
    } else {
        $error_message = 'اسم المستخدم أو كلمة المرور غير صحيحة!';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCS || تسجيل الدخول</title>
  <!-- استدعاء Bootstrap 5 المعتمد في مشروعك -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body {
        background-color: #f8f9fa;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        width: 100%;
        max-width: 400px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 12px;
    }
    .btn-login {
        background-color: #0d6efd;
        border: none;
        transition: all 0.2s;
    }
    .btn-login:hover {
        background-color: #0b5ed7;
    }
  </style>
</head>
<body>

<div class="card login-card p-4">
    <div class="text-center mb-4">
        <!-- لوجو الموقع الافتراضي -->
        <img src="assets/img/logo.png" alt="شعار بيتهوفن سيتي" height="60" class="mb-2" onerror="this.style.display='none'">
        <h4 class="fw-bold mt-2">لوحة التحكم</h4>
        <p class="text-muted small">سجل دخولك لإدارة الشعار والإعلانات</p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger py-2 small text-center" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label small fw-bold">اسم المستخدم</label>
            <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label small fw-bold">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary btn-login w-100 py-2 fw-bold">تسجيل الدخول</button>
    </form>
</div>

</body>
</html>
