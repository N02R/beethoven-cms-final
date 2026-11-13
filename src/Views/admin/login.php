<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Multilingual Admin Login View (DE / EN / AR)
 * متوافق بالكامل مع نظام الحماية المركزي (Security::generateCsrfToken) وتحديث الكابتشا الفوري.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supportedLangs = ['de', 'en', 'ar'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLangs, true)) {
    $_SESSION['admin_login_lang'] = $_GET['lang'];
    $_SESSION['site_lang'] = $_GET['lang'];
}

$currentLang = $_SESSION['admin_login_lang'] ?? ($_SESSION['site_lang'] ?? 'de');
if (!in_array($currentLang, $supportedLangs, true)) {
    $currentLang = 'de';
}

$isRtl = ($currentLang === 'ar');
$dir   = $isRtl ? 'rtl' : 'ltr';

$langBadges = [
    'de' => 'DE',
    'en' => 'EN',
    'ar' => 'ع'
];

$translations = [
    'de' => [
        'page_title'        => 'Admin-Anmeldung - Beethoven City Services',
        'back_home'         => 'Zurück zur Hauptseite',
        'heading'           => 'Admin-Anmeldung',
        'subheading'        => 'Willkommen zurück! Bitte geben Sie Ihre Zugangsdaten ein.',
        'email_label'       => 'E-Mail-Adresse',
        'email_placeholder' => 'name@beethoven-services.de',
        'pass_label'        => 'Passwort',
        'captcha_label'     => 'Sicherheitsüberprüfung',
        'captcha_ph'        => 'Ergebnis',
        'submit_btn'        => 'Anmelden',
        'verifying'         => 'Wird überprüft...',
        'network_error'     => 'Verbindungsfehler zum Server. Bitte versuchen Sie es später erneut.',
        'lang_label'        => 'Sprache wählen'
    ],
    'en' => [
        'page_title'        => 'Admin Login - Beethoven City Services',
        'back_home'         => 'Back to Main Website',
        'heading'           => 'Admin Login',
        'subheading'        => 'Welcome back, please enter your credentials to continue.',
        'email_label'       => 'Email Address',
        'email_placeholder' => 'name@beethoven-services.de',
        'pass_label'        => 'Password',
        'captcha_label'     => 'Security Verification',
        'captcha_ph'        => 'Result',
        'submit_btn'        => 'Sign In',
        'verifying'         => 'Verifying...',
        'network_error'     => 'Server connection error, please try again later.',
        'lang_label'        => 'Select Language'
    ],
    'ar' => [
        'page_title'        => 'تسجيل دخول المشرفين - Beethoven City Services',
        'back_home'         => 'العودة للموقع الرئيسي',
        'heading'           => 'تسجيل دخول المشرف',
        'subheading'        => 'أهلاً بك مجدداً، يرجى إدخال بياناتك للمتابعة',
        'email_label'       => 'البريد الإلكتروني',
        'email_placeholder' => 'name@beethoven-services.de',
        'pass_label'        => 'كلمة المرور',
        'captcha_label'     => 'التحقق الأمني',
        'captcha_ph'        => 'الناتج',
        'submit_btn'        => 'دخول اللوحة',
        'verifying'         => 'جاري التحقق...',
        'network_error'     => 'حدث خطأ في الاتصال بالخادم، يرجى المحاولة لاحقاً.',
        'lang_label'        => 'اختر اللغة'
    ]
];

$t = $translations[$currentLang];
$logout_msg = isset($_GET['message']) ? trim((string)$_GET['message']) : '';
$activeCsrfToken = $csrf_token ?? \App\Core\Security::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($t['page_title'], ENT_QUOTES, 'UTF-8'); ?></title>
    
    <?php if ($isRtl): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            position: relative;
        }

        /* زر اللغة الزجاجي العائم على الخلفية */
        .floating-lang-wrapper {
            position: fixed;
            top: 24px;
            <?php echo $isRtl ? 'left: 24px;' : 'right: 24px;'; ?>
            z-index: 1050;
        }

        .lang-glass-box {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.25s ease;
            user-select: none;
        }

        .floating-lang-wrapper:hover .lang-glass-box,
        .lang-glass-box:focus {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.22);
        }

        .lang-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            <?php echo $isRtl ? 'left: 0;' : 'right: 0;'; ?>
            min-width: 155px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 6px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .floating-lang-wrapper::after {
            content: '';
            position: absolute;
            top: 46px;
            left: 0;
            right: 0;
            height: 12px;
        }

        .floating-lang-wrapper:hover .lang-dropdown-menu,
        .floating-lang-wrapper:focus-within .lang-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .lang-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            border-radius: 10px;
            color: #1e293b;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .lang-option:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .lang-option.active {
            background: #2563eb;
            color: #ffffff;
        }

        .lang-option .code-tag {
            font-size: 0.72rem;
            font-weight: 700;
            opacity: 0.75;
            padding: 2px 6px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.06);
        }

        .lang-option.active .code-tag {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
        }

        /* بطاقة تسجيل الدخول */
        .login-card-wrapper {
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 40px;
            position: relative;
        }

        .back-home-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 24px;
            transition: color 0.2s ease;
        }

        .back-home-btn:hover {
            color: #2563eb;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .captcha-badge {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            min-width: 120px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body>

<!-- مربع اللغة الزجاجي العائم في زاوية الخلفية -->
<div class="floating-lang-wrapper">
    <div class="lang-glass-box" tabindex="0" role="button" aria-label="<?php echo htmlspecialchars($t['lang_label'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($langBadges[$currentLang], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <div class="lang-dropdown-menu">
        <a href="/admin/login?lang=de" class="lang-option <?php echo $currentLang === 'de' ? 'active' : ''; ?>">
            <span>Deutsch</span>
            <span class="code-tag">DE</span>
        </a>
        <a href="/admin/login?lang=en" class="lang-option <?php echo $currentLang === 'en' ? 'active' : ''; ?>">
            <span>English</span>
            <span class="code-tag">EN</span>
        </a>
        <a href="/admin/login?lang=ar" class="lang-option <?php echo $currentLang === 'ar' ? 'active' : ''; ?>">
            <span>العربية</span>
            <span class="code-tag">AR</span>
        </a>
    </div>
</div>

<!-- بطاقة تسجيل الدخول -->
<div class="login-card-wrapper">
    <a href="/<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>/home" class="back-home-btn">
        <i class="fa-solid <?php echo $isRtl ? 'fa-arrow-right' : 'fa-arrow-left'; ?>"></i>
        <span><?php echo htmlspecialchars($t['back_home'], ENT_QUOTES, 'UTF-8'); ?></span>
    </a>

    <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($t['heading'], ENT_QUOTES, 'UTF-8'); ?></h3>
    <p class="text-muted mb-4 small"><?php echo htmlspecialchars($t['subheading'], ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if ($logout_msg !== ''): ?>
        <div class="alert alert-info text-center small mb-3">
            <?php echo htmlspecialchars($logout_msg, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <div id="login-alert" class="alert <?php echo !empty($error_msg) ? 'alert-danger' : 'd-none'; ?> text-center small mb-3">
        <?php echo !empty($error_msg) ? htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') : ''; ?>
    </div>

    <form id="loginForm" method="POST" action="/admin/login/process" novalidate>
        <input type="hidden" name="csrf_token" id="csrfTokenInput" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="ui_lang" value="<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3">
            <label for="emailInput" class="form-label small fw-bold"><?php echo htmlspecialchars($t['email_label'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="email" id="emailInput" class="form-control" name="email" required autocomplete="email" placeholder="<?php echo htmlspecialchars($t['email_placeholder'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="mb-3">
            <label for="passwordInput" class="form-label small fw-bold"><?php echo htmlspecialchars($t['pass_label'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="password" id="passwordInput" class="form-control" name="password" required autocomplete="current-password" placeholder="••••••••">
        </div>

        <?php if (isset($_SESSION['captcha_num1'], $_SESSION['captcha_num2'])): ?>
        <div class="mb-4">
            <label for="captchaInput" class="form-label small fw-bold"><?php echo htmlspecialchars($t['captcha_label'], ENT_QUOTES, 'UTF-8'); ?></label>
            <div class="d-flex gap-2 align-items-center">
                <div class="captcha-badge" id="captchaQuestion" dir="ltr">
                    <?php echo (int)$_SESSION['captcha_num1']; ?> + <?php echo (int)$_SESSION['captcha_num2']; ?> = ?
                </div>
                <input type="number" id="captchaInput" class="form-control" name="captcha" required autocomplete="off" placeholder="<?php echo htmlspecialchars($t['captcha_ph'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <?php endif; ?>

        <button type="submit" id="submitBtn" class="btn btn-primary-custom w-100"><?php echo htmlspecialchars($t['submit_btn'], ENT_QUOTES, 'UTF-8'); ?></button>
    </form>
</div>

<script>
const UI_TEXT = {
    verifying: <?php echo json_encode($t['verifying'], JSON_UNESCAPED_UNICODE); ?>,
    submitBtn: <?php echo json_encode($t['submit_btn'], JSON_UNESCAPED_UNICODE); ?>,
    networkError: <?php echo json_encode($t['network_error'], JSON_UNESCAPED_UNICODE); ?>
};

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('login-alert');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mx-2"></i> ' + UI_TEXT.verifying;
    alertBox.classList.add('d-none');

    const formData = new FormData(this);
    const csrfToken = document.getElementById('csrfTokenInput')?.value || '';

    try {
        const response = await fetch('/admin/login/process', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const rawText = await response.text();
        let result;
        try {
            result = JSON.parse(rawText);
        } catch (parseErr) {
            console.error('Server raw response:', rawText);
            throw new Error('Invalid JSON response from server');
        }

        alertBox.classList.remove('d-none');
        if (result.success) {
            alertBox.className = 'alert alert-success p-2 small text-center mb-3';
            alertBox.textContent = result.message;
            setTimeout(() => {
                window.location.href = result.redirect || '/admin/dashboard';
            }, 800);
        } else {
            alertBox.className = 'alert alert-danger p-2 small text-center mb-3';
            alertBox.textContent = result.message;
            btn.disabled = false;
            btn.textContent = UI_TEXT.submitBtn;

            if (result.new_captcha) {
                const capEl = document.getElementById('captchaQuestion');
                const capInput = document.getElementById('captchaInput');
                if (capEl) capEl.textContent = result.new_captcha;
                if (capInput) capInput.value = '';
            }

            if (response.status === 403) {
                setTimeout(() => window.location.reload(), 1200);
            }
        }
    } catch (error) {
        alertBox.classList.remove('d-none');
        alertBox.className = 'alert alert-danger p-2 small text-center mb-3';
        alertBox.textContent = UI_TEXT.networkError;
        btn.disabled = false;
        btn.textContent = UI_TEXT.submitBtn;
    }
});
</script>

</body>
</html>
