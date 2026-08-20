<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;
use App\Services\ImageUploader;
use PDO;
use Exception;

class MotivationController {
    
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
        $is_admin = !empty($_SESSION['is_logged_in']) || !empty($_SESSION['is_admin']); // توحيد التحقق من جلسة الأدمن

        // استدعاء قاعدة البيانات إن وجدت لطريقة المعالجة والتحديث
        $db = null;
        if (class_exists('App\Core\Database') && method_exists('App\Core\Database', 'getConnection')) {
            $db = \App\Core\Database::getConnection();
        } elseif (isset($GLOBALS['db'])) {
            $db = $GLOBALS['db'];
        }

        // معالجة طلبات التحديث المرسلة من أدمن الموقع
        if ($db && $is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_motivation') {
            
            // التحقق من رمز الـ CSRF إن وجد في المشروع
            if (function_exists('verify_csrf_token') && !verify_csrf_token($_POST['csrf_token'] ?? '')) {
                $_SESSION['error_message'] = 'غير مسموح بهذا الإجراء أو انتهت صلاحية الجلسة.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            try {
                $db->beginTransaction();

                $global_data = SiteModel::getSettings();
                $currentData = isset($global_data['motivation_page']) ? json_decode($global_data['motivation_page'], true) : [];
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
                    if (!empty($_POST['hero_position'])) {
                        $currentData['hero_position'] = trim($_POST['hero_position']);
                    }
                } 
                // 2. تحديث مسار التنقل (Breadcrumb)
                elseif ($section === 'breadcrumb') {
                    $currentData['page_breadcrumb'] = trim($_POST['page_breadcrumb'] ?? '');
                    $currentData['page_breadcrumb_url'] = trim($_POST['page_breadcrumb_url'] ?? '#');
                } 
                // 3. تحديث العنوان والوصف الرئيسي
                elseif ($section === 'main') {
                    $currentData['main_title'] = trim($_POST['main_title'] ?? '');
                    $currentData['main_desc'] = trim($_POST['main_desc'] ?? '');
                } 
                // 4. تحديث النصائح
                elseif ($section === 'advice') {
                    if (!isset($currentData['advice_section']) || !is_array($currentData['advice_section'])) {
                        $currentData['advice_section'] = [];
                    }
                    $currentData['advice_section']['title'] = trim($_POST['advice_title'] ?? '');
                    $raw_points = $_POST['advice_points'] ?? [];
                    if (is_string($raw_points)) {
                        $raw_points = explode("\n", $raw_points);
                    }
                    if (is_array($raw_points)) {
                        $currentData['advice_section']['items'] = array_values(array_filter(array_map('trim', $raw_points)));
                    } else {
                        $currentData['advice_section']['items'] = [];
                    }
                } 
                // 5. تحديث ملفات التحميل
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
                    SiteModel::updateSetting('motivation_page', $encodedData);
                } else {
                    $stmt = $db->prepare("UPDATE site_pages SET content_data = ? WHERE page_key = 'motivation'");
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

        // جلب البيانات والإعدادات العامة من قاعدة البيانات عبر SiteModel
        $global_data = SiteModel::getSettings();
        
        $motivation_data = isset($global_data['motivation_page']) ? json_decode($global_data['motivation_page'], true) : [
            'page_breadcrumb'     => 'خطاب الدافع / التحفيز',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg3.png',
            'hero_position'       => 'center center',
            'main_title'          => '',
            'main_desc'           => '',
            'advice_section'      => [
                'title' => 'نصائح سريعة لكتابة خطاب الدافع',
                'items' => []
            ],
            'download_items'      => []
        ];

        $data = $global_data;
        $data['motivation_page'] = $motivation_data;

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
        $view_file = __DIR__ . '/../../Views/edu-services/motivitionletter.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Motivation Letter View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/src/Views/edu-services/includes/admin_motivation_modals.php';
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
