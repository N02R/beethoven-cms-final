<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class UploadController
{
    /**
     * معالجة طلب رفع الصور العام عبر الـ AJAX واسترجاع رابط الصورة بصيغة JSON
     */
    public function upload(): void
    {
        $this->checkAdminAuth();

        header('Content-Type: application/json; charset=utf-8');

        // التأكد من أن الطلب من نوع POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'طريقة الطلب غير مسموح بها.']);
            exit;
        }

        // التحقق من رمز الحماية CSRF Token
        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? $_POST['csrf_token'] ?? '';

        if (!Security::verifyCsrfToken($token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'انصهار الجلسة أو خطأ في الـ CSRF Token']);
            exit;
        }

        try {
            // التحقق من وجود الملف في الطلب
            if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new InvalidArgumentException('لم يتم اختيار أي ملف للرفع.');
            }

            // تحديد مسار التخزين العام
            $rootPath = realpath(__DIR__ . '/../../../');
            $uploadDir = $rootPath . '/public/assets/uploads/';

            // استدعاء خدمة رفع ومعالجة الصور الموحدة
            $uploader = new ImageUploader($uploadDir);
            
            // تنفيذ الرفع ومعالجة الصورة (تتحقق من الأمان، تحول إلى WebP، وتحفظ في DB)
            $filename = $uploader->upload($_FILES['image']);
            
            $filePath = 'assets/uploads/' . $filename;

            // إرجاع الاستجابة الناجحة
            echo json_encode([
                'success' => true,
                'message' => 'تم رفع الصورة ومعالجتها بنجاح.',
                'filename' => $filename,
                'url' => $filePath
            ]);
            exit;

        } catch (InvalidArgumentException | RuntimeException $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        } catch (Exception $e) {
            error_log('Upload Controller Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'حدث خطأ غير متوقع أثناء معالجة الصورة.'
            ]);
            exit;
        }
    }

    /**
     * التحقق من صلاحيات المشرف
     */
    private function checkAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'غير مصرح بالوصول، يرجى تسجيل الدخول مجدداً.'
            ]);
            exit;
        }
    }
}
