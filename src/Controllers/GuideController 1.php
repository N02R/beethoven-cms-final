<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;

class GuideController {
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

        // السطر 32 وما حوله هنا يعتمد على SiteModel حصراً
        $data = SiteModel::getGlobalData();

        $guide_items_raw = $data['guide_items'] ?? '[]';
        $guide_items = is_string($guide_items_raw) ? json_decode($guide_items_raw, true) : $guide_items_raw;

        $guideData = [
            'guide_title' => $data['guide_title'] ?? 'دليل بيتهوفن الشامل',
            'guide_desc'  => $data['guide_desc'] ?? '',
            'guide_items' => is_array($guide_items) ? $guide_items : []
        ];

        $data = array_merge($data, $guideData);

        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        $path_prefix = '/';
        $page_css = ['/assets/css/style.css'];

        extract($data);

        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        $view_file = __DIR__ . '/../Views/guide.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        }

        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
