<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;
use App\Models\ServiceCostModel;

class ServiceCostController {
    public function index(string $lang = 'de'): void {
        // حماية مخرجات اللغة المعروضة
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        // إدارة الجلسات بأمان تام
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        // تحديد مسار الجذر للمشروع
        $root_path = realpath(__DIR__ . '/../../../');

        // 1. جلب بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع
        $data = SiteModel::getGlobalData();

        // 2. جلب بيانات صفحة قائمة أسعار الخدمات عبر المودل المخصص وتوفيرها بالتسميات المتوافقة
        $pricelist_data_array = ServiceCostModel::getServiceCostData();
        $data['pricelist_page'] = $pricelist_data_array;
        $data['pricelist_data'] = $pricelist_data_array; // لضمان التوافق التام مع الحقول داخل الـ Modals والـ View

        // فحص حالة تسجيل الدخول كـ Admin وفق مفاتيح الجلسة المعتمدة
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات لاستخدامها في الـ Views
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // متغيرات إضافية لتحديد ملفات الـ CSS والـ JS الخاصة بالصفحة
        $path_prefix = '/';
        $page_css = ['/assets/css/style.css', '/assets/css/edu-services.css'];
        $page_js = [];

        // تفكيك مصفوفة البيانات لتحويل مفاتيحها إلى متغيرات مستقلة داخل ملفات الـ View
        extract($data);

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء ملف الـ View الخاص بصفحة قائمة أسعار الخدمات (services-cost.php)
        $view_file = $root_path . '/src/Views/edu-services/services-cost.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. استدعاء مودلز لوحة التحكم الخاصة بالصفحة (إذا كان المستخدم مشرفاً ومتاحة)
        if ($is_admin) {
            $modals_file = $root_path . '/src/Views/edu-services/includes/admin_pricelist_modals.php';
            if (file_exists($modals_file)) {
                include_once $modals_file;
            }
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found.</div>";
        }
    }
}
