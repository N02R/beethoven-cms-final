<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;
use App\Models\HomeModel;

class HomeController {

    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];
    private const SESSION_IDLE_TIMEOUT = 1800; // 30 دقيقة

    public function index(string $langCode = 'de'): void {
        // 1. التحقق الصارم من اللغة وفق القائمة البيضاء (منع Path Traversal)
        $current_lang = in_array($langCode, self::SUPPORTED_LANGS, true)
            ? $langCode
            : (in_array($_SESSION['site_lang'] ?? '', self::SUPPORTED_LANGS, true) ? $_SESSION['site_lang'] : 'de');

        // 2. إدارة الجلسات وتأمينها
        if (session_status() === PHP_SESSION_NONE) {
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                        || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => $isSecure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }

        // 3. حفظ اللغة وتحديد الاتجاه وتحميل ملف الترجمة في مصفوفة $lang
        $_SESSION['site_lang'] = $current_lang;
        $current_dir = ($current_lang === 'ar') ? 'rtl' : 'ltr';

        $lang_file = __DIR__ . '/../Lang/' . $current_lang . '.php';
        if (!file_exists($lang_file)) {
            $lang_file = __DIR__ . '/../Lang/de.php';
        }
        $lang = file_exists($lang_file) ? require $lang_file : [];
        if (!is_array($lang)) {
            $lang = [];
        }
        $lang_data = $lang; // توافق عكسي مع أي ملف قديم يستخدم $lang_data

        // 4. التحقق الأمني من جلسة المشرف (البصمة + انتهاء الخمول)
        $is_logged_in = !empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        if ($is_logged_in) {
            $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
            $lastActivity     = (int)($_SESSION['LAST_ACTIVITY'] ?? time());

            if (
                (!empty($_SESSION['user_agent_hash']) && !hash_equals($_SESSION['user_agent_hash'], $currentAgentHash))
                || (time() - $lastActivity > self::SESSION_IDLE_TIMEOUT)
            ) {
                unset($_SESSION['is_logged_in'], $_SESSION['role'], $_SESSION['admin_id'], $_SESSION['admin_name']);
                $is_logged_in = false;
            } else {
                $_SESSION['LAST_ACTIVITY'] = time();
            }
        }

        $user_role = $_SESSION['role'] ?? '';
        $is_admin  = $is_logged_in && in_array($user_role, ['admin', 'super_admin'], true);

        // 5. توليد رمز CSRF الموحد للجلسة
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];

        // 6. جلب بيانات الموقع العامة + بيانات الصفحة الرئيسية
        $data = SiteModel::getGlobalData();
        $homeData = HomeModel::getHomeData();
        if (is_array($homeData)) {
            $data = array_merge($data, $homeData);
        }

        // إتاحة حالة المشرف واللغة ورمز الأمان داخل مصفوفة البيانات
        $data['is_admin']     = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name']   = $_SESSION['admin_name'] ?? 'Admin';
        $data['csrf_token']   = $csrf_token;
        $data['current_lang'] = $current_lang;
        $data['current_dir']  = $current_dir;

        // متغيرات العرض المشتركة
        $path_prefix = '/';
        $page_css    = ['/assets/css/style.css'];

        // تعريف ثابت الحماية لملفات العرض (Views)
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        // 7. تنفيذ دورة العرض المتسلسلة (Header -> View -> Footer)
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        $view_file = __DIR__ . '/../Views/home.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            http_response_code(404);
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        $footer_file = __DIR__ . '/../Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }

    public function switchLang(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $targetLang = $_GET['lang'] ?? 'de';
        if (in_array($targetLang, self::SUPPORTED_LANGS, true)) {
            $_SESSION['site_lang'] = $targetLang;
            $_SESSION['admin_login_lang'] = $targetLang;
        } else {
            $targetLang = 'de';
        }
        
        // منع ثغرة Open Redirect بالتحقق من أن الرابط المرجعي ينتمي لنفس الموقع
        $redirect_url = '/' . $targetLang . '/home';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $refererParts = parse_url($_SERVER['HTTP_REFERER']);
            $currentHost  = $_SERVER['HTTP_HOST'] ?? '';
            if (
                is_array($refererParts)
                && (empty($refererParts['host']) || strcasecmp($refererParts['host'], $currentHost) === 0)
            ) {
                $path = $refererParts['path'] ?? '/home';
                $cleanPath = preg_replace('#^/(ar|en|de)(/|$)#i', '/', $path);
                $cleanPath = '/' . ltrim((string)$cleanPath, '/');
                $redirect_url = '/' . $targetLang . ($cleanPath === '/' ? '/home' : $cleanPath);
            }
        }

        header('Location: ' . $redirect_url);
        exit;
    }
}
