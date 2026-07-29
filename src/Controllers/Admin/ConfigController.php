<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Services\ConfigManager;

class ConfigController
{
    private ConfigManager $configManager;

    public function __construct()
    {
        // تحديد المسارات المطلقة لملف الإعدادات ومجلد الرفع في النظام الجديد
        $jsonPath = __DIR__ . '/../../../announcement_config.json';
        $uploadDir = __DIR__ . '/../../../public/assets/files';

        $this->configManager = new ConfigManager($jsonPath, $uploadDir);
    }

    /**
     * حفظ وتحديث إعدادات ملف JSON عبر AJAX
     */
    public function save(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        // 1. التحقق من طريقة الطلب POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'طريقة الطلب غير مسموح بها', 405);
        }

        // 2. التحقق من تسجيل دخول الأدمن
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['is_logged_in'])) {
            $this->jsonResponse(false, 'غير مصرح بالوصول، يرجى تسجيل الدخول أولاً', 401);
        }

        // 3. التحقق من رمز الحماية CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$csrfToken || !hash_equals($_SESSION['settings_csrf'] ?? '', $csrfToken)) {
            $this->jsonResponse(false, 'رمز الحماية غير صالح (CSRF Error)', 403);
        }

        // 4. تحميل البيانات الحالية وقراءة الأكشن
        $data = $this->configManager->load();
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'update_job_agreements_card':
                if (!isset($data['job_search_package_page'])) {
                    $data['job_search_package_page'] = [];
                }

                $itemTitle = trim($_POST['item_title'] ?? 'عرض واتفاقيات العمل');
                $itemSub   = trim($_POST['item_sub'] ?? 'Example');
                $itemType  = strtolower($_POST['item_type'] ?? 'pdf');
                $oldFile   = trim($_POST['old_file'] ?? 'assets/files/job_search_agreement.pdf');

                $filePath = $this->configManager->handleFileUpload('item_file', $oldFile);

                $data['job_search_package_page']['download_item'] = [
                    'type'  => in_array($itemType, ['word', 'docx'], true) ? 'word' : 'pdf',
                    'title' => $itemTitle,
                    'sub'   => $itemSub,
                    'file'  => $filePath
                ];
                break;

            // هنا يمكنك إضافة أية حالات (Cases) أخرى كانت موجودة في save_config_old.php

            default:
                $this->jsonResponse(false, 'الإجراء المطلوب غير معروف', 400);
                break;
        }

        // 5. حفظ البيانات وإرجاع النتيجة
        if ($this->configManager->save($data)) {
            $this->jsonResponse(true, 'تم حفظ وتحديث الإعدادات بنجاح!');
        } else {
            $this->jsonResponse(false, 'حدث خطأ أثناء حفظ الملف، يرجى التحقق من أذونات المجلدات', 500);
        }
    }

    private function jsonResponse(bool $success, string $message, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
