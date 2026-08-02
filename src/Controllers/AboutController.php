<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;
use App\Models\AboutModel;

class AboutController {
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

        // 1. جلب بيانات الهيدر والفوتر العامة لكل الموقع عبر SiteModel
        $data = SiteModel::getGlobalData();

        // 2. جلب بيانات صفحة عن الشركة (About) ودمجها مع البيانات العامة
        $aboutData = class_exists('App\Models\AboutModel') ? AboutModel::getAboutData() : [];
        $data = array_merge($data, $aboutData);

        // فحص حالة تسجيل الدخول كـ Admin وفق مفاتيح الجلسة المعتمدة في النظام
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات لاستخدامها في الـ Views
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // متغيرات إضافية ومسارات
        $path_prefix = '/';

        // تعريف ملفات الـ CSS والـ JS الخاصة بصفحة من نحن (مع دعم Swiper)
        $page_css = [
            '/assets/css/swiper-bundle.min.css',
            '/assets/css/about.css',
            '/assets/css/responsive-about.css'
        ]; 

        $page_js = [
            '/assets/js/swiper-bundle.min.js'
        ];

        // سكربت تهيئة الـ Swiper الخاص بالسلايدر
        $custom_script = '
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".mySwiper", {
              slidesPerView: 1,
              spaceBetween: 20,
              rtl: true,
              navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
              },
              breakpoints: {
                576: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
                1400: { slidesPerView: 4 },
                1800: { slidesPerView: 5 }
              },
              autoplay: {
                delay: 3000,
                disableOnInteraction: false,
              }
            });
          });
        </script>';

        // 1. استدعاء الهيدر المشترك
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء الـ View الخاص بـ About
        $view_file = __DIR__ . '/../Views/about.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>About View file not found.</h3></div>";
        }

        // 3. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found.</div>";
        }
    }
}
