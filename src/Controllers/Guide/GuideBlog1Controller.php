<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Models\GuideBlog1Model;

class GuideBlog1Controller {
    public function index(): void {
        $is_admin = $_SESSION['is_admin'] ?? false;
        
        // معالجة طلبات الحفظ والتحديث القادمة من لوحة التحكم
        if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST') {
            GuideBlog1Model::updateData($_POST, $_FILES);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        // جلب البيانات من الجدول المستقل لعرضها في الصفحة
        $guide_data = GuideBlog1Model::getData();
        $page_css = ['/assets/css/education.css'];

        require_once __DIR__ . '/../../Views/layouts/header.php';
        require_once __DIR__ . '/../../Views/guide/guide-blog1.php';
        require_once __DIR__ . '/../../Views/layouts/footer.php';
    }
}
