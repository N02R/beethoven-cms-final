<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;

class AnnouncementController {

    private string $configFile;

    public function __construct() {
        // التحقق من صلاحيات الأدمن لأي طلب يمر عبر هذا الكنترولر
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'غير مصرح'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $this->configFile = __DIR__ . '/../../../announcement_config.json';
    }

    public function save(): void {
        header('Content-Type: application/json; charset=utf-8');

        // التحقق من طريقة الطلب POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'طلب غير صالح'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // التحقق من رمز حماية الـ CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCsrfToken($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'انتهت صلاحية الجلسة أو رمز التحقق غير صالح'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // قراءة ملف التكوين الحالي أو إنشاء مصفوفة افتراضية
        $data = ['announcement' => []];
        if (file_exists($this->configFile)) {
            $jsonContent = file_get_contents($this->configFile);
            $decoded = json_decode($jsonContent, true);
            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'update_announcement') {
            // تنظيف وتطهير المدخلات النصية
            $data['announcement']['type']              = trim($_POST['type'] ?? '');
            $data['announcement']['announcement_text'] = trim($_POST['announcement_text'] ?? '');
            
            // التحقق من صحة الأكواد اللونية (Hex Colors)
            $bgColor   = trim($_POST['bg_color'] ?? '');
            $textColor = trim($_POST['text_color'] ?? '');
            $data['announcement']['bg_color']   = preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $bgColor) ? $bgColor : '#ffffff';
            $data['announcement']['text_color'] = preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $textColor) ? $textColor : '#000000';

            // التحقق من صحة الرابط
            $link = trim($_POST['link'] ?? '');
            $data['announcement']['link'] = ($link !== '' && $link !== '#') ? filter_var($link, FILTER_SANITIZE_URL) : '#';
            
            $data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? '1' : '0';

            // معالجة رفع الصورة باستخدام خدمة ImageUploader الموحدة
            if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadedPath = ImageUploader::upload($_FILES['ad_image']);
                    if ($uploadedPath) {
                        $data['announcement']['announcement_image_path'] = $uploadedPath;
                    }
                } catch (\Exception $e) {
                    // في حال فشل رفع الصورة يمكن تسجيل الخطأ أو تجاهله مع الاحتفاظ بالبيانات النصية
                }
            }
        }

        // حفظ البيانات بصيغة JSON آمنة
        $jsonEncoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($jsonEncoded !== false && file_put_contents($this->configFile, $jsonEncoded, LOCK_EX) !== false) {
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'فشل الحفظ'], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}
