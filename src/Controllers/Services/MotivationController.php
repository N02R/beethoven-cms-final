<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;
use App\Models\MotivationLetterModel;

class MotivationController {
    public function index(string $lang = 'de'): void {
        // 1. توحيد وحماية رمز اللغة
        $cleanLang = strtolower(trim(htmlspecialchars($lang, ENT_QUOTES, 'UTF-8')));
        if (!in_array($cleanLang, ['de', 'en', 'ar'], true)) {
            $cleanLang = strtolower(trim((string)($_SESSION['site_lang'] ?? 'de')));
            if (!in_array($cleanLang, ['de', 'en', 'ar'], true)) {
                $cleanLang = 'de';
            }
        }
        $_SESSION['site_lang'] = $cleanLang;
        $current_lang = $cleanLang;
        $current_dir  = ($current_lang === 'ar') ? 'rtl' : 'ltr';

        // 2. إدارة الجلسات بأمان تام
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        $root_path = realpath(__DIR__ . '/../../../');

        // 3. جلب البيانات العامة
        $data = SiteModel::getGlobalData();

        // 4. جلب بيانات خطاب الدافع واستخراج المصفوفة الداخلية مباشرة (لحل تداخل extract)
        $modelResult = MotivationLetterModel::getMotivationData($current_lang);
        $innerMotData = is_array($modelResult['motivation_data'] ?? null)
            ? $modelResult['motivation_data']
            : $modelResult;

        $data['motivation_page'] = $innerMotData;
        $data['motivation_data'] = $innerMotData;

        // 5. التحقق من صلاحية المشرف
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin']     = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name']   = $_SESSION['admin_name'] ?? 'المشرف';
        $data['current_lang'] = $current_lang;
        $data['current_dir']  = $current_dir;

        $path_prefix = '/';
        $page_css = ['/assets/css/style.css', '/assets/css/edu-services.css'];
        $page_js = [];

        extract($data);

        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        $view_file = $root_path . '/src/Views/edu-services/motivitionletter.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
