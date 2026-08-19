<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\MotivationModel;
use App\Core\ImageUploader;

class MotivationController {

    /**
     * عرض صفحة خطاب الدافع / التحفيز
     */
    public function index(): void {
        // التحقق من صلاحيات الأدمن
        $is_admin = $_SESSION['is_admin'] ?? false;
        $path_prefix = '/';

        // جلب البيانات عبر الموديل
        $motivation_page = MotivationModel::getMotivationData();

        $data = [
            'motivation_page' => $motivation_page
        ];

        // استدعاء ملف الـ View الخاص بالصفحة
        $view_file = __DIR__ . '/../../Views/edu-services/motivitionletter.php';
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            http_response_code(404);
            echo "View file not found.";
        }
    }

    /**
     * معالجة تحديث بيانات صفحة خطاب الدافع (للأدمن)
     */
    public function update(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // التحقق من صلاحيات الأدمن
        if (empty($_SESSION['is_admin'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section_type = $_POST['section_type'] ?? '';

            // جلب البيانات الحالية باستخدام الموديل
            $motivation_data = MotivationModel::getMotivationData();

            // معالجة التعديل حسب القسم المرسل
            if ($section_type === 'breadcrumb') {
                $motivation_data['page_breadcrumb'] = trim($_POST['page_breadcrumb'] ?? '');
                $motivation_data['page_breadcrumb_url'] = trim($_POST['page_breadcrumb_url'] ?? '#');
            } 
            elseif ($section_type === 'hero') {
                // معالجة رفع صورة الهيرو الجديدة إذا وجدت
                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    $uploaded_path = ImageUploader::upload($_FILES['hero_img']);
                    if ($uploaded_path) {
                        $motivation_data['hero_img'] = $uploaded_path;
                    }
                }
                if (!empty($_POST['hero_position'])) {
                    $motivation_data['hero_position'] = trim($_POST['hero_position']);
                }
            }
            elseif ($section_type === 'main') {
                $motivation_data['main_title'] = trim($_POST['main_title'] ?? '');
                $motivation_data['main_desc'] = trim($_POST['main_desc'] ?? '');
            }
            elseif ($section_type === 'advice') {
                $motivation_data['advice_section']['title'] = trim($_POST['advice_title'] ?? '');
                
                // تحويل النصائح القادمة من textarea إلى مصفوفة عبر الـ explode وتنقيتها
                if (!empty($_POST['advice_points'])) {
                    $points = explode("\n", $_POST['advice_points']);
                    $motivation_data['advice_section']['items'] = array_values(array_filter(array_map('trim', $points)));
                } else {
                    $motivation_data['advice_section']['items'] = [];
                }
            }
            // يمكن إضافة أقسام أخرى مثل download_items حسب الحاجة

            // تشفير البيانات وحفظها عبر الموديل
            $json_encoded = json_encode($motivation_data, JSON_UNESCAPED_UNICODE);
            MotivationModel::updateMotivationData($json_encoded);

            // العودة للصفحة بعد اتمام التحديث بنجاح
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/education/motivationletter'));
            exit;
        }
    }
}
