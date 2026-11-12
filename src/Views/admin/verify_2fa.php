<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Two-Factor Authentication View (BSI / OWASP Compliant)
 */
$isLocalDev = (($_ENV['APP_ENV'] ?? 'production') !== 'production');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>التحقق الثنائي (2FA) - Beethoven City Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .login-card { max-width: 420px; margin: 100px auto; background: #fff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; overflow: hidden; }
        .login-header { background: #1e293b; color: #fff; padding: 25px; text-align: center; }
        .btn-primary-custom { background: #2563eb; border: none; color: #fff; font-weight: 600; padding: 12px; width: 100%; border-radius: 10px; transition: 0.2s; }
        .btn-primary-custom:hover { background: #1d4ed8; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">
        <div class="login-header">
            <h4 class="mb-1"><i class="bi bi-shield-check text-info"></i> التحقق الثنائي (2FA)</h4>
            <p class="text-light opacity-75 small mb-0">أدخل رمز الأمان المرسل إلى بريدك الإلكتروني</p>
        </div>
        <div class="p-4">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger py-2 small text-center">
                    <?php echo htmlspecialchars((string)$error_message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if ($isLocalDev && !empty($_SESSION['2fa_code'])): ?>
                <div class="alert alert-warning py-2 small mb-3 text-center">
                    <i class="bi bi-exclamation-triangle"></i> [بيئة التطوير فقط]: الكود المؤقت هو: 
                    <strong><?php echo htmlspecialchars((string)$_SESSION['2fa_code'], ENT_QUOTES, 'UTF-8'); ?></strong>
                </div>
            <?php endif; ?>

            <form action="/admin/verify-2fa" method="POST" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['2fa_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="mb-3">
                    <label for="verificationCode" class="form-label fw-bold small">رمز التحقق المكون من 6 أرقام</label>
                    <input type="text" 
                           id="verificationCode"
                           class="form-control text-center fs-4 fw-bold" 
                           style="letter-spacing: 0.4rem;"
                           name="verification_code" 
                           inputmode="numeric" 
                           pattern="[0-9]{6}" 
                           maxlength="6" 
                           minlength="6"
                           required 
                           autofocus 
                           autocomplete="one-time-code"
                           placeholder="••••••">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تأكيد وتسجيل الدخول</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="/admin/login" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-right"></i> العودة لتسجيل الدخول
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
