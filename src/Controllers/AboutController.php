<?php
declare(strict_types=1);

namespace App\Controllers;

class AboutController {
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
        $config_file = $root_path . '/announcement_config.json';
        $global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
        
        $data = $global_data;
        $is_admin = !empty($_SESSION['is_admin']);
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
        $header_file = $root_path . '/includes/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بـ About
        $view_file = __DIR__ . '/../Views/about.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>About View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن الخاصة بصفحة عن الشركة إن وجدت
        $admin_modals = $root_path . '/includes/admin_about_modals.php';
        if (!empty($is_admin) && file_exists($admin_modals)) {
            include_once $admin_modals;
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/includes/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
