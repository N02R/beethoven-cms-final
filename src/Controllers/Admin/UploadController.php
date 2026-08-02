<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;
use InvalidArgumentException;
use Exception;

class UploadController {

    public function uploadImage(): void {
        header('Content-Type: application/json; charset=utf-8');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
                return;
            }

            // التحقق من CSRF عبر الكلاس الجديد Security
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Security::verifyCsrfToken($csrfToken)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Security token validation failed.']);
                return;
            }

            if (!isset($_FILES['image'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'No image file provided.']);
                return;
            }

            $uploadDir = dirname(__DIR__, 3) . '/storage/uploads/';

            // إمكانية إمرار $pdo إذا كانت الدالة تتطلب حفظ السجل بالـ DB
            $uploader = new ImageUploader($uploadDir);
            $filename = $uploader->upload($_FILES['image']);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Image uploaded, validated, and converted to WebP successfully.',
                'data' => [
                    'filename' => $filename
                ]
            ]);

        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        } catch (Exception $e) {
            error_log("Image Upload Exception: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'An internal server error occurred.']);
        }
    }
}