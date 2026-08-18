<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;
use App\Services\ImageUploader;
use PDO;
use Exception;

class CoverLetterController {
    
    public function index(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        $root_path = realpath(__DIR__ . '/../../../');
        $is_admin = !empty($_SESSION['is_logged_in']); // توحيد التحقق من جلسة الأدمن

        // استدعاء قاعدة البيانات إن وجدت لطريقة المعالجة والتحديث
        $db = null;
        if (class_exists('App\Core\Database') && method_exists('App\Core\Database', 'getConnection')) {
            $db = \App\Core\Database::getConnection();
        } elseif (isset($GLOBALS['db'])) {
            $db = $GLOBALS['db'];
        }

        // معالجة طلبات التحديث المرسلة من أدمن الموقع
        if ($db && $is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_coverletter') {
            
            // التحقق من رمز الـ CSRF إن وجد في المشروع
            if (function_exists('verify_csrf_token') && !verify_csrf_token($_POST['csrf_token'] ?? '')) {
                $_SESSION['error_message'] = 'غير مسموح بهذا الإجراء أو انتهت صلاحية الجلسة.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            try {
                $db->beginTransaction();

                $global_data = SiteModel::getSettings();
                $currentData = isset($global_data['coverletter_page']) ? json_decode($global_data['coverletter_page'], true) : [];
                $section = $_POST['section_type'] ?? '';

                // 1. تحديث صورة الهيرو
                if ($section === 'hero' && !empty($_FILES['hero_img']['name'])) {
                    if (class_exists('App\Services\ImageUploader') || class_exists('ImageUploader')) {
                        $uploaderClass = class_exists('App\Services\ImageUploader') ? 'App\Services\ImageUploader' : 'ImageUploader';
                        $uploadedImage = $uploaderClass::upload($_FILES['hero_img'], 'assets/img/education/', ['jpg', 'jpeg', 'png', 'webp']);
                        if ($uploadedImage) {
                            if (!empty($currentData['hero_img']) && method_exists($uploaderClass, 'delete')) {
                                $uploaderClass::delete($currentData['hero_img']);
                            }
                            $currentData['hero_img'] = $uploadedImage;
                        }
                    }
                } 
                // 2. تحديث مسار التنقل (Breadcrumb)
                elseif ($section === 'breadcrumb') {
                    $currentData['page_breadcrumb'] = trim($_POST['page_breadcrumb'] ?? '');
                    $currentData['page_breadcrumb_url'] = trim($_POST['page_breadcrumb_url'] ?? '');
                } 
                // 3. تحديث العنوان والوصف الرئيسي
                elseif ($section === 'main') {
                    $currentData['main_title'] = trim($_POST['main_title'] ?? '');
                    $currentData['main_desc'] = trim($_POST['main_desc'] ?? '');
                } 
                // 4. تحديث النصائح
                elseif ($section === 'advice') {
                    $currentData['advice_title'] = trim($_POST['advice_title'] ?? '');
                    $raw_points = $_POST['advice_points'] ?? [];
                    if (is_string($raw_points)) {
                        $raw_points = explode("\n", $raw_points);
                    }
                    $currentData['advice_points'] = array_values(array_filter(array_map('trim', $raw_points)));
                } 
                // 5. تحديث الملاحظات
                elseif ($section === 'notes') {
                    $currentData['note_title'] = trim($_POST['note_title'] ?? '');
                    $raw_notes = $_POST['notes'] ?? [];
                    if (is_string($raw_notes)) {
                        $raw_notes = explode("\n", $raw_notes);
                    }
                    $currentData['notes'] = array_values(array_filter(array_map('trim', $raw_notes)));
                }
                // 6. تحديث ملفات التحميل
                elseif ($section === 'download') {
                    $items = [];
                    if (!empty($_POST['dl_title']) && is_array($_POST['dl_title'])) {
                        for ($i = 0; $i < count($_POST['dl_title']); $i++) {
                            $items[] = [
                                'title' => trim($_POST['dl_title'][$i] ?? ''),
                                'sub' => trim($_POST['dl_sub'][$i] ?? ''),
                                'type' => trim($_POST['dl_type'][$i] ?? 'pdf'),
                                'file' => trim($_POST['dl_file'][$i] ?? '#')
                            ];
                        }
                    }
                    $currentData['download_items'] = $items;
                }

                $encodedData = json_encode($currentData, JSON_UNESCAPED_UNICODE);
                
                // حفظ البيانات المحدثة في جدول إعدادات أو صفحة الموقع
                if (method_exists('App\Models\SiteModel', 'updateSetting')) {
                    SiteModel::updateSetting('coverletter_page', $encodedData);
                } else {
                    $stmt = $db->prepare("UPDATE site_pages SET content_data = ? WHERE page_key = 'coverletter'");
                    $stmt->execute([$encodedData]);
                }

                $db->commit();
                $_SESSION['success_message'] = 'تم تحديث بيانات الصفحة بنجاح.';
                
            } catch (Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $_SESSION['error_message'] = 'حدث خطأ أثناء الحفظ: ' . $e->getMessage();
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // جلب البيانات والإعدادات العامة من قاعدة البيانات عبر SiteModel باستخدام getSettings المعتمدة في المشروع
        $global_data = SiteModel::getSettings();
        
        $cover_data = isset($global_data['coverletter_page']) ? json_decode($global_data['coverletter_page'], true) : [
            'page_breadcrumb'     => 'خطاب الطلب',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg1.jpg',
            'main_title'          => 'رسالة تعريف/خطاب طلب احترافي يدعم طلبك، أياً كان هدفك أو وجهتك',
            'main_desc'           => '',
            'advice_title'        => 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف',
            'advice_points'       => [],
            'note_title'          => 'ملاحظات هامة !!',
            'notes'               => [],
            'download_items'      => []
        ];

        $data = $global_data;
        $data['coverletter_page'] = $cover_data;

        $path_prefix = '/';

        // ملفات الـ CSS والـ JS الخاصة بالخدمة
        $page_css = [
            'assets/css/edu-services.css'
        ]; 

        $page_js = [];
        $custom_script = '';

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            require_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بالصفحة
        $view_file = __DIR__ . '/../../Views/edu-services/coverletter.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>CoverLetter View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/src/Views/edu-services/includes/admin_cover_modals.php';
        if (!empty($is_admin) && file_exists($admin_modals)) {
            require_once $admin_modals;
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            require_once $footer_file;
        }
    }
}
