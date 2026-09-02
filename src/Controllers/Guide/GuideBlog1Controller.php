<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Models\SiteModel;
use App\Models\GuideBlog1Model;

class GuideBlog1Controller {
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

        $root_path = realpath(__DIR__ . '/../../../');

        $data = SiteModel::getGlobalData();

        // جلب بيانات المقال الأول من الإعدادات العامة أو قاعدة البيانات
        $blogData = [
            'blog_title'   => $data['guide_blog1_title'] ?? 'عنوان المقال الأول',
            'blog_desc'    => $data['guide_blog1_desc'] ?? '',
            'blog_content' => $data['guide_blog1_content'] ?? '',
            'blog_img'     => $data['guide_blog1_img'] ?? '',
        ];

        $data = array_merge($data, $blogData);

        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        $path_prefix = '/';
        $page_css = ['/assets/css/style.css', '/assets/css/guide.css'];

        extract($data);

        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        $view_file = $root_path . '/src/Views/guide/guide-blog1.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center text-muted'><h3>عذراً، صفحة المقال غير متوفرة حالياً.</h3></div>";
        }

        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
