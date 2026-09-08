<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\GuideBlogOneModel;
use PDO;

class GuideBlogOneController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        // استدعاء نموذج البيانات الخاص بالصفحة
        $model = new GuideBlogOneModel($this->pdo);
        $settings = $model->getSettings();

        // التحقق مما إذا كان المستخدم مسجلاً لدخول الأدمن لعرض أزرار وتعديلات الـ CMS
        $isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

        // تمرير البيانات إلى ملف الـ View
        $pageTitle = $settings['main_breadcrumb'] ?? 'الدليل الشامل - الدراسة في ألمانيا';
        
        include __DIR__ . '/../../Views/guide/guide-blog1.php';
    }
}
