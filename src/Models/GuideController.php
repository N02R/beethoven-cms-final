<?php
namespace App\Controllers;

use App\Models\GuideModel;

class GuideController {
    public function index() {
        // التحقق من صلاحية المشرف
        $is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        
        // جلب بيانات الدليل من الـ Model
        $guideData = GuideModel::getGuideData(); // أو الطريقة التي تستخدمينها لجلب الإعدادات

        $data = [
            'guide_title' => $guideData['guide_title'] ?? 'دليل بيتهوفن الشامل',
            'guide_desc'  => $guideData['guide_desc'] ?? '',
            'guide_items' => $guideData['guide_items'] ?? []
        ];

        // دمج البيانات العامة والخاصة وتمريرها للـ View عبر extract
        extract($data);
        
        // تضمين الـ View الأمامي
        require_once __DIR__ . '/../Views/guide.php';
    }
}
