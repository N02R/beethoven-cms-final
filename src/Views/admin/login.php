<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Multilingual Admin Login View (DE / EN / AR)
 * متوافقة تماماً مع معايير الأمان الألمانية وتدعم تبديل اللغات الفوري
 */

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

$translations = [
    'de' => [
        'page_title'       => 'Admin-Anmeldung - Beethoven City Services',
        'back_home'        => 'Zurück zur Hauptseite',
        'heading'          => 'Admin-Anmeldung',
        'subheading'       => 'Willkommen zurück! Bitte geben Sie Ihre Zugangsdaten ein.',
        'email_label'      => 'E-Mail-Adresse',
        'email_placeholder'=> 'name@beethoven-services.de',
        'pass_label'       => 'Passwort',
        'captcha_label'    => 'Sicherheitsüberprüfung',
        'captcha_ph'       => 'Ergebnis',
        'submit_btn'       => 'Anmelden',
        'verifying'        => 'Wird überprüft...',
        'network_error'    => 'Verbindungsfehler zum Server. Bitte versuchen Sie es später erneut.',
        'lang_label'       => 'Sprache'
    ],
    'en' => [
        'page_title'       => 'Admin Login - Beethoven City Services',
        'back_home'        => 'Back to Main Website',
        'heading'          => 'Admin Login',
        'subheading'       => 'Welcome back, please enter your credentials to continue.',
        'email_label'      => 'Email Address',
        'email_placeholder'=> 'name@beethoven-services.de',
        'pass_label'       => 'Password',
        'captcha_label'    => 'Security Verification',
        'captcha_ph'       => 'Result',
        'submit_btn'       => 'Sign In',
        'verifying'        => 'Verifying...',
        'network_error'    => 'Server connection error, please try again later.',
        'lang_label'       => 'Language'
    ],
    'ar' => [
        'page_title'       => 'تسجيل دخول المشرفين - Beethoven City Services',
        'back_home'        => 'العودة للموقع الرئيسي',
        'heading'          => 'تسجيل دخول المشرف',
        'subheading'       => 'أهلاً بك مجدداً، يرجى إدخال بياناتك للمتابعة',
        'email_label'      => 'البريد الإلكتروني',
        'email_placeholder'=> 'name@beethoven-services.de',
        'pass_label'       => 'كلمة المرور',
        'captcha_label'    => 'التحقق الأمني',
        'captcha_ph'       => 'الناتج',
        'submit_btn'       => 'دخول اللوحة',
        'verifying'        => 'جاري التحقق...',
        'network_error'    => 'حدث خطأ في الاتصال بالخادم، يرجى المحاولة لاحقاً.',
        'lang_label'       => 'اللغة'
    ]
];

$t = $translations[$currentLang];
$logout_msg = isset($_GET['message']) ? trim((string)$_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo htmlspecialchars($t['page_title'], ENT_QUOTES, 'UTF-8'); ?></title>
    
    <?php if ($isRtl): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: <?php echo $isRtl ? "'Cairo', sans-serif" : "'Plus Jakarta Sans', system-ui, sans-serif"; ?>;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-card-wrapper {
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 36px 40px;
            position: relative;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 10px;
        }

        .back-home-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .back-home-btn:hover {
            color: #2563eb;
        }

        .lang-switcher {
            display: inline-flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            gap: 2px;
        }

        .lang-btn {
            padding: 4px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: 7px;
            text-decoration: none;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .lang-btn:hover {
            color: #1e293b;
        }

        .lang-btn.active {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
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

<div class="login-card-wrapper">
    <!-- الشريط العلوي: العودة للموقع + زر تبديل اللغة -->
    <div class="top-bar">
        <a href="/<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>/home" class="back-home-btn">
            <i class="fa-solid <?php echo $isRtl ? 'fa-arrow-right' : 'fa-arrow-left'; ?>"></i>
            <span><?php echo htmlspecialchars($t['back_home'], ENT_QUOTES, 'UTF-8'); ?></span>
        </a>

        <div class="lang-switcher" role="group" aria-label="<?php echo htmlspecialchars($t['lang_label'], ENT_QUOTES, 'UTF-8'); ?>">
            <a href="/admin/login?lang=de" class="lang-btn <?php echo $currentLang === 'de' ? 'active' : ''; ?>">DE</a>
            <a href="/admin/login?lang=en" class="lang-btn <?php echo $currentLang === 'en' ? 'active' : ''; ?>">EN</a>
            <a href="/admin/login?lang=ar" class="lang-btn <?php echo $currentLang === 'ar' ? 'active' : ''; ?>">AR</a>
        </div>
    </div>

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
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['login_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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
                <div class="captcha-badge" dir="ltr">
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

    try {
        const response = await fetch('/admin/login/process', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

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

            if (response.status === 403) {
                setTimeout(() => window.location.reload(), 1500);
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
