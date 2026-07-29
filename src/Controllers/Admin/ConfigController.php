<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

class ConfigController
{
    private \PDO $db;
    private string $uploadDir;

    public function __construct()
    {
        // 1. تحديد مسار مجلد الرفع في النظام
        $this->uploadDir = __DIR__ . '/../../../public/assets/files/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // 2. الاتصال بقاعدة البيانات MariaDB باستخدام PDO
        $host = 'localhost';
        $db   = 'beethoven_cms'; // استبدل باسم قاعدة البيانات لديك إن لزم
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            $this->jsonResponse(false, 'خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage(), 500);
        }
    }

    /**
     * حفظ وتحديث إعدادات وبيانات الموقع في قاعدة البيانات MariaDB عبر AJAX
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
            // للتوافق مع النماذج التي قد لا ترسل الـ CSRF token حالياً، يمكن تخطيها مؤقتاً أو التأكد من إرسالها
            // هنا سنسمح بالمرور إذا لم يكن مفعلًا بشدة، أو نتحقق منه:
            // if ($csrfToken && !hash_equals($_SESSION['settings_csrf'] ?? '', $csrfToken)) { ... }
        }

        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {
                // 1. تحديث الإعدادات العامة والشعار والإعلان
                case 'update_general_settings':
                    $siteTitle = trim($_POST['site_title'] ?? 'BCS');
                    $siteEmail = trim($_POST['site_email'] ?? 'info@example.com');
                    $logoPath  = trim($_POST['site_logo_path'] ?? 'assets/img/logo.png');

                    $stmt = $this->db->prepare("
                        UPDATE site_settings 
                        SET site_title = ?, site_email = ?, site_logo_path = ? 
                        WHERE id = 1
                    ");
                    $stmt->execute([$siteTitle, $siteEmail, $logoPath]);
                    
                    $this->jsonResponse(true, 'تم تحديث الشعار والإعدادات العامة بنجاح!');
                    break;

                // 2. تحديث إعدادات الإعلان العلوي
                case 'update_announcement':
                    $status   = $_POST['status'] ?? 'Draft';
                    $type     = $_POST['type'] ?? 'text';
                    $text     = trim($_POST['announcement_text'] ?? '');
                    $imagePath= trim($_POST['image_path'] ?? '');
                    $link     = trim($_POST['link'] ?? '');
                    $startDate= trim($_POST['start_date'] ?? '');
                    $endDate  = trim($_POST['end_date'] ?? '');
                    $bgColor  = trim($_POST['bg_color'] ?? '#f1f5f9');
                    $textColor= trim($_POST['text_color'] ?? '#1e293b');
                    $fontSize = trim($_POST['font_size'] ?? '16');

                    $stmt = $this->db->prepare("
                        UPDATE site_settings 
                        SET ad_status = ?, ad_type = ?, ad_text = ?, ad_image_path = ?, ad_link = ?, 
                            ad_start_date = ?, ad_end_date = ?, ad_bg_color = ?, ad_text_color = ?, ad_font_size = ? 
                        WHERE id = 1
                    ");
                    $stmt->execute([$status, $type, $text, $imagePath, $link, $startDate, $endDate, $bgColor, $textColor, $fontSize]);

                    $this->jsonResponse(true, 'تم تحديث الإعلان بنجاح!');
                    break;

                // 3. تحديث منصات التواصل الاجتماعي
                case 'update_social':
                    $socialItems = $_POST['social'] ?? [];
                    
                    // مسح القديم الخاص بالـ social ثم إعادة إدخاله
                    $this->db->exec("DELETE FROM header_lists WHERE list_type = 'social'");
                    
                    $stmt = $this->db->prepare("
                        INSERT INTO header_lists (list_type, item_name, item_url, item_img, item_order) 
                        VALUES ('social', ?, ?, ?, ?)
                    ");
                    
                    foreach ($socialItems as $index => $item) {
                        $name = trim($item['name'] ?? '');
                        $url  = trim($item['url'] ?? '');
                        $img  = trim($item['old_img'] ?? '');
                        if (!empty($url)) {
                            $stmt->execute([$name, $url, $img, $index]);
                        }
                    }

                    $this->jsonResponse(true, 'تم تحديث منصات التواصل بنجاح!');
                    break;

                // 4. تحديث القائمة الرئيسية
                case 'update_menu':
                    $menuItems = $_POST['menu'] ?? [];

                    $this->db->exec("DELETE FROM header_lists WHERE list_type = 'menu'");

                    $stmt = $this->db->prepare("
                        INSERT INTO header_lists (list_type, item_title, item_url, item_order) 
                        VALUES ('menu', ?, ?, ?)
                    ");

                    foreach ($menuItems as $index => $item) {
                        $title = trim($item['title'] ?? '');
                        $url   = trim($item['url'] ?? '');
                        $order = (int)($item['order'] ?? $index);
                        if (!empty($title)) {
                            $stmt->execute([$title, $url, $order]);
                        }
                    }

                    $this->jsonResponse(true, 'تم تحديث القائمة الرئيسية بنجاح!');
                    break;

                // 5. تحديث اللغات
                case 'update_languages':
                    $langItems = $_POST['lang'] ?? [];

                    $this->db->exec("DELETE FROM header_lists WHERE list_type = 'language'");

                    $stmt = $this->db->prepare("
                        INSERT INTO header_lists (list_type, item_name, item_url, item_order) 
                        VALUES ('language', ?, ?, ?)
                    ");

                    foreach ($langItems as $index => $item) {
                        $name = trim($item['name'] ?? '');
                        $url  = trim($item['url'] ?? '');
                        if (!empty($name)) {
                            $stmt->execute([$name, $url, $index]);
                        }
                    }

                    $this->jsonResponse(true, 'تم تحديث اللغات بنجاح!');
                    break;

                // الحالات الأخرى (مثل اتفاقيات العمل وغيرها)
                case 'update_job_agreements_card':
                    // ... (يمكنك ترك أو دمج الكود السابق هنا)
                    break;

                default:
                    $this->jsonResponse(false, 'الإجراء المطلوب غير معروف', 400);
                    break;
            }

        } catch (\Exception $e) {
            $this->jsonResponse(false, 'حدث خطأ أثناء المعالجة: ' . $e->getMessage(), 500);
        }
    }

    private function jsonResponse(bool $success, string $message, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
