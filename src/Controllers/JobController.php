<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;
use App\Models\JobModel;

class JobController {
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

        // جلب البيانات العامة وبيانات صفحة الوظائف
        $data = SiteModel::getGlobalData();
        $jobData = JobModel::getJobData(); 
        $data = array_merge($data, $jobData);

        // التحقق من صلاحيات المشرف
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        $path_prefix = '/';
        $page_css = ['/assets/css/style.css', '/assets/css/jobs.css'];

        extract($data);

        // 1. الهيدر
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. الـ View لصفحة الـ Job
        $view_file = __DIR__ . '/../Views/job.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. الفوتر
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
