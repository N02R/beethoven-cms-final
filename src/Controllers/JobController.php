<?php
declare(strict_types=1);

namespace App\Controllers;

use PDO;
use App\Config\Database;

class JobController
{
    public function index(): void
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            // استخراج المتغيرات بقيم افتراضية لضمان عدم ظهور أي تحذيرات
            $job_hero = json_decode($rawSettings['job_hero'] ?? '{}', true) ?: [];
            
            $job_why_title = $rawSettings['job_why_title'] ?? 'لماذا التدريب والتوظيف معنا؟';
            $job_why_desc = $rawSettings['job_why_desc'] ?? '';
            $job_why_items = json_decode($rawSettings['job_why_items'] ?? '[]', true) ?: [];

            $job_program_title = $rawSettings['job_program_title'] ?? 'برامج التدريب';
            $job_program_desc = $rawSettings['job_program_desc'] ?? '';
            $job_program_types = json_decode($rawSettings['job_program_types'] ?? '[]', true) ?: [];

            $job_timeline_title = $rawSettings['job_timeline_title'] ?? 'خطوات التقديم';
            $job_timeline_desc = $rawSettings['job_timeline_desc'] ?? '';
            $job_timeline_steps = json_decode($rawSettings['job_timeline_steps'] ?? '[]', true) ?: [];

            $job_services_title = $rawSettings['job_services_title'] ?? 'خدمات التدريب والتوظيف';
            $job_services_desc = $rawSettings['job_services_desc'] ?? '';
            $job_services_items = json_decode($rawSettings['job_services_items'] ?? '[]', true) ?: [];

        } catch (\Exception $e) {
            error_log("Job Controller Error: " . $e->getMessage());
            $job_hero = [];
            $job_why_title = $job_why_desc = $job_program_title = $job_program_desc = $job_timeline_title = $job_timeline_desc = $job_services_title = $job_services_desc = '';
            $job_why_items = $job_program_types = $job_timeline_steps = $job_services_items = [];
        }

        // تعريف المتغيرات المطلوبة في الـ View لمنع تحذيرات الـ Undefined variable
        $path_prefix = ''; 
        $is_admin = !empty($_SESSION['admin_logged_in']);

        // مسار ملف الـ View
        $view_file = __DIR__ . '/../Views/job.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "View file not found.";
        }
    }
}
