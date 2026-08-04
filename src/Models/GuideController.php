<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;
use App\Models\HomeModel;

class GuideController {
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
        $root_path = realpath(__DIR__ . '/../../');

        // 1. جلب البيانات العامة والشاملة من قاعدة البيانات عبر SiteModel
        $data = SiteModel::getGlobalData();

        // 2. تجهيز وفك ترميز بيانات الدليل الشامل من الإعدادات العامة
        $guide_items_raw = $data['guide_items'] ?? '[]';
        $guide_items = is_string($guide_items_raw) ? json_decode($guide_items_raw, true) : $guide_items_raw;

        $guideData = [
            'guide_title' => $data['guide_title'] ?? 'دليل بيتهوفن الشامل',
            'guide_desc'  => $data['guide_desc'] ?? '',
            'guide_items' => is_array($guide_items) ? $guide_items : []
        ];

        // دمج بيانات الدليل مع البيانات العامة
        $data = array_merge($data, $guideData);

        // فحص حالة تسجيل الدخول كـ Admin
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات لاستخدامها في الـ Views
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // متغيرات إضافية قد تحتاجها الـ View
        $path_prefix = '/';
        $page_css = ['/assets/css/style.css'];

        // استخراج المتغيرات لتكون جاهزة للاستخدام المباشر داخل الـ View
        extract($data);

        // 1. استدعاء الهيدر المشترك
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء ملف الـ View الرئيسي (guide.php)
        $view_file = __DIR__ . '/../Views/guide.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class=' py-3 text-danger'>Footer file not found.</div>";
        }
    }
}
