<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Multilingual Two-Factor Authentication View (DE / EN / AR)
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

$langBadges = [
    'de' => 'DE',
    'en' => 'EN',
    'ar' => 'ع'
];

$translations = [
    'de' => [
        'page_title'    => 'Zwei-Faktor-Authentifizierung (2FA) - Beethoven City Services',
        'heading'       => 'Zwei-Faktor-Authentifizierung',
        'subheading'    => 'Geben Sie den 6-stelligen Sicherheitscode ein, der an Ihre E-Mail gesendet wurde.',
        'dev_notice'    => '[Entwicklungsmodus]: Ihr temporärer Code lautet:',
        'code_label'    => '6-stelliger Bestätigungscode',
        'submit_btn'    => 'Bestätigen & Anmelden',
        'back_login'    => 'Zurück zur Anmeldung',
        'lang_label'    => 'Sprache wählen'
    ],
    'en' => [
        'page_title'    => 'Two-Factor Authentication (2FA) - Beethoven City Services',
        'heading'       => 'Two-Factor Authentication',
        'subheading'    => 'Enter the 6-digit security code sent to your email address.',
        'dev_notice'    => '[Development Mode]: Your temporary code is:',
        'code_label'    => '6-Digit Verification Code',
        'submit_btn'    => 'Verify & Sign In',
        'back_login'    => 'Back to Login',
        'lang_label'    => 'Select Language'
    ],
    'ar' => [
        'page_title'    => 'التحقق الثنائي (2FA) - Beethoven City Services',
        'heading'       => 'التحقق الثنائي (2FA)',
        'subheading'    => 'أدخل رمز الأمان المكون من 6 أرقام المرسل إلى بريدك الإلكتروني',
        'dev_notice'    => '[بيئة التطوير فقط]: الكود المؤقت الخاص بك هو:',
        'code_label'    => 'رمز التحقق المكون من 6 أرقام',
        'submit_btn'    => 'تأكيد وتسجيل الدخول',
        'back_login'    => 'العودة لتسجيل الدخول',
        'lang_label'    => 'اختر اللغة'
    ]
];

$t = $translations[$currentLang];
$isLocalDev = (($_ENV['APP_ENV'] ?? 'production') !== 'production');
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
            position: relative;
        }

        /* مربع اللغة الزجاجي العائم */
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
        }

        .lang-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            <?php echo $isRtl ? 'left: 0;' : 'right: 0;'; ?>
            min-width: 155px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
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

        .otp-card-wrapper {
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 40px;
        }

        .shield-badge {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 16px;
        }

        .otp-input {
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            letter-spacing: 0.45rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
        }

        .otp-input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 14px;
            width: 100%;
            border-radius: 12px;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body>

<!-- مربع اللغة الزجاجي العائم -->
<div class="floating-lang-wrapper">
    <div class="lang-glass-box" tabindex="0" role="button" aria-label="<?php echo htmlspecialchars($t['lang_label'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($langBadges[$currentLang], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <div class="lang-dropdown-menu">
        <a href="/admin/verify-2fa?lang=de" class="lang-option <?php echo $currentLang === 'de' ? 'active' : ''; ?>">
            <span>Deutsch</span>
            <span class="code-tag">DE</span>
        </a>
        <a href="/admin/verify-2fa?lang=en" class="lang-option <?php echo $currentLang === 'en' ? 'active' : ''; ?>">
            <span>English</span>
            <span class="code-tag">EN</span>
        </a>
        <a href="/admin/verify-2fa?lang=ar" class="lang-option <?php echo $currentLang === 'ar' ? 'active' : ''; ?>">
            <span>العربية</span>
            <span class="code-tag">AR</span>
        </a>
    </div>
</div>

<div class="otp-card-wrapper">
    <div class="text-center mb-4">
        <div class="shield-badge">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($t['heading'], ENT_QUOTES, 'UTF-8'); ?></h4>
        <p class="text-muted small mb-0"><?php echo htmlspecialchars($t['subheading'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger py-2 small text-center mb-3">
            <?php echo htmlspecialchars((string)$error_message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if ($isLocalDev && !empty($_SESSION['2fa_code'])): ?>
        <div class="alert alert-warning py-2 small mb-3 text-center">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($t['dev_notice'], ENT_QUOTES, 'UTF-8'); ?> 
            <strong><?php echo htmlspecialchars((string)$_SESSION['2fa_code'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>
    <?php endif; ?>

    <form action="/admin/verify-2fa" method="POST" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['2fa_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-4">
            <label for="verificationCode" class="form-label fw-bold small"><?php echo htmlspecialchars($t['code_label'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="text" 
                   id="verificationCode"
                   class="form-control otp-input" 
                   dir="ltr"
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

        <button type="submit" class="btn btn-primary-custom"><?php echo htmlspecialchars($t['submit_btn'], ENT_QUOTES, 'UTF-8'); ?></button>
    </form>
    
    <div class="text-center mt-4">
        <a href="/admin/login" class="text-muted small text-decoration-none fw-semibold">
            <i class="fa-solid <?php echo $isRtl ? 'fa-arrow-right' : 'fa-arrow-left'; ?> mx-1"></i>
            <?php echo htmlspecialchars($t['back_login'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
    </div>
</div>

</body>
</html>
