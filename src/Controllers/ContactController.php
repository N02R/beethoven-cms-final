<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;
use App\Models\ContactModel;

class ContactController {
    public function index(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        $root_path = realpath(__DIR__ . '/../../');

        // 1. جلب البيانات العامة (الهيدر والفوتر)
        $globalData = SiteModel::getGlobalData();

        // 2. جلب بيانات صفحة التواصل الخاصة
        $contactData = ContactModel::getContactData();

        // 3. دمج البيانات معاً
        $data = array_merge($globalData, $contactData);

        // التحقق من صلاحيات المشرف
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        $path_prefix = '/';
        $page_css = ['/assets/css/style.css'];

        // تحويل مفاتيح المصفوفة إلى متغيرات مستقلة للـ View
        extract($data);

        // تضمين الهيدر
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // تضمين الـ View الأساسي لصفحة التواصل
        $view_file = __DIR__ . '/../Views/contact.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        }

        // تضمين مودلز الإدارة الخاصة بالصفحة (إذا كان المشرف مسجلاً للدخول)
        if ($is_admin) {
            $modals_file = __DIR__ . '/../Views/admin/admin_contact_modals.php';
            if (file_exists($modals_file)) {
                require_once $modals_file;
            }
        }

        // تضمين الفوتر
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
