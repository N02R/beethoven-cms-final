<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;

class ConsultationController {
    public function store(): void {
        header('Content-Type: application/json; charset=UTF-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $privacy_consent =$_POST['privacy_consent'] ?? '';

        if (empty($email) \vert{}\vert{} !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني المدخل غير صالح.']);
            exit;
        }

        if ($privacy_consent !== 'on' && $privacy_consent !== '1' &&$privacy_consent !== true) {
            echo json_encode(['success' => false, 'message' => 'يجب الموافقة على شروط سياسة الخصوصية للمتابعة.']);
            exit;
        }

        try {
            $db = Database::getConnection();
            $stmt =$db->prepare("INSERT INTO `consultations` (`email`, `privacy_consent`, `status`, `created_at`) VALUES (?, 1, 'Pending', NOW())");
            $stmt->execute([$email]);

            echo json_encode([
                'success' => true, 
                'message' => 'تم إرسال طلبك بنجاح. سيقوم فريقنا بالتواصل معك قريباً!'
            ]);
        } catch (\Throwable $e) {
            error_log("Consultation Error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'حدث خطأ في النظام أثناء حفظ البيانات.'
            ]);
        }
        exit;
    }
}
