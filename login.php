<?php
// 1. بدء الجلسة بأمان
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إذا كان المستخدم مسجل دخول بالفعل كأدمن، يتم توجيهه مباشرة للوحة التحكم
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/admin_dashboard.php");
    exit();
}

$error_message = "";

// 2. معالجة طلب تسجيل الدخول عند إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تضمين ملف الاتصال بقاعدة البيانات
    // require_once 'includes/db_connect.php'; 
    
    $username = trim(htmlspecialchars(strip_tags($_POST['username'])));
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        
        // بيئة اختبار مؤقتة (Demo) لكي تتمكني من التجربة فوراً:
        if ($username === "admin" && $password === "admin123") {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['username'] = "نور المسؤول";
            $_SESSION['role'] = "admin";
            
            session_regenerate_id(true);
            
            header("Location: admin/admin_dashboard.php");
            exit();
        } else {
            $error_message = "بيانات الدخول غير صحيحة (للتجربة استخدم: admin / admin123).";
        }

    } else {
        $error_message = "يرجى ملء جميع الحقول المطلوبة.";
    }
}
?> <!-- هنا كان الخطأ؛ قمنا بإغلاق وسم PHP بأمان ليتمكن الخادم من قراءة الـ HTML التالي -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول || Beethoven City Services</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
      background: #ffffff;
      overflow: hidden;
      max-width: 420px;
      width: 100%;
    }
    .login-header {
      background-color: #0d6efd;
      color: #fff;
      padding: 30px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="login-card p-0">
  <div class="login-header">
    <h4 class="mb-1 fw-bold">Beethoven City Services</h4>
    <p class="small text-white-50 mb-0">بوابة الدخول الآمنة للنظام الإداري</p>
  </div>
  
  <div class="card-body p-4">
    <?php if (!empty($error_message)): ?>
      <div class="alert alert-danger text-center small p-2" role="alert">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="username" class="form-label fw-semibold text-secondary">اسم المستخدم</label>
        <input type="text" class="form-control p-2.5" id="username" name="username" placeholder="ادخل اسم المستخدم" required>
      </div>
      
      <div class="mb-4">
        <label for="password" class="form-label fw-semibold text-secondary">كلمة المرور</label>
        <input type="password" class="form-control p-2.5" id="password" name="password" placeholder="••••••••" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary p-2.5 fw-bold">تسجيل الدخول الآمن</button>
      </div>
    </form>
  </div>
  <div class="card-footer bg-light text-center py-3">
    <a href="index.php" class="text-decoration-none small text-muted">← العودة للموقع الرئيسي</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
