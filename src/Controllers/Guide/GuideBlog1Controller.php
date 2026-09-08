<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Models\GuideBlogOneModel;
use PDO;

class GuideBlog1Controller
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo !== null) {
            $this->pdo = $pdo;
        } else {
            $dbClass = 'App\\Config\\Database';
            if (class_exists($dbClass) && method_exists($dbClass, 'getConnection')) {
                $this->pdo = $dbClass::getConnection();
            } else {
                global $pdo;
                if ($pdo instanceof PDO) {
                    $this->pdo = $pdo;
                } else {
                    $dbPath = __DIR__ . '/../../../database/database.sqlite';
                    $this->pdo = file_exists($dbPath) ? new PDO('sqlite:' . $dbPath) : new PDO('mysql:host=localhost;dbname=beethoven_db', 'root', '');
                }
            }
        }
    }

    public function index(): void
    {
        $model = new GuideBlogOneModel($this->pdo);
        $settings = $model->getSettings();

        $isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

        $pageTitle = $settings['main_breadcrumb'] ?? 'الدليل الشامل - الدراسة في ألمانيا';
        
        $viewFile = __DIR__ . '/../../Views/guide/guide-blog1.php';
        if (!file_exists($viewFile)) {
            $viewFile = __DIR__ . '/../../../src/Views/guide/guide-blog1.php';
        }

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "خطأ: ملف العرض (View) غير موجود في المسار المحدد.";
        }
    }
}
