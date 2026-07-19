<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Core\SessionManager;
use App\Services\AuthService;

SessionManager::startSecureSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthService();
    if ($auth->login($_POST['username'], $_POST['password'])) {
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "بيانات الدخول غير صحيحة!";
    }
}
?>
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
</body>
</html>
