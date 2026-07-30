<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\SiteSettings;
use Exception;

class ConfigController
{
    public function save(): void
    {
        // التأكد من أن الطلب POST وحماية النظام
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة.']);
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');

        try {
            // تجميع البيانات القادمة من نموذج الإعدادات
            $data = $_POST;

            // معالجة الملف المرفوع إذا وجد (مثل الشعار أو الملفات المرفقة)
            if (isset($_FILES['item_file']) && $_FILES['item_file']['error'] === UPLOAD_ERR_OK) {
                // يمكنك استخدام الـ ImageUploader أو FileUploader الخاص بك هنا
                // كمثال بسيط لحفظ مسار الملف:
                $uploadDir = dirname(__DIR__, 3) . '/public/assets/files/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $filename = time() . '_' . basename($_FILES['item_file']['name']);
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['item_file']['tmp_name'], $targetPath)) {
                    $data['item_file_path'] = 'assets/files/' . $filename;
                }
            } else {
                // الاحتفاظ بالملف القديم إذا لم يتم رفع ملف جديد
                if (isset($_POST['old_file'])) {
                    $data['item_file_path'] = $_POST['old_file'];
                }
            }

            // تنفيذ الحفظ في قاعدة البيانات عبر الـ Model
            $success = SiteSettings::updateSettings($data);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'تم حفظ وتحديث البيانات في قاعدة البيانات بنجاح!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'لم يتم إجراء أي تغييرات أو فشل التحديث.'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'حدث خطأ في السيرفر: ' . $e->getMessage()
            ]);
        }
    }
}
