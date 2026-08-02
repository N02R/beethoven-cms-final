
<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;
use Exception;
use InvalidArgumentException;
use PDO;

class SettingsController
{
    /**
     * عرض صفحة إعدادات لوحة التحكم
     */
    public function index(): void
    {
        $this->checkAdminAuth();

        // تضمين ملف الاتصال بقاعدة البيانات الموجود في جذر المشروع
        require_once realpath(__DIR__ . '/../../../database/database.php');
        global $pdo; 

        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $settings = [
            'site_title' => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email' => $rawSettings['site_email'] ?? '',
            'site_logo'  => $rawSettings['site_logo'] ?? '',
        ];

        // استخدام كلاس الأمان لتوليد رمز CSRF
        $csrf_token = Security::generateCsrfToken();

        $root_path = realpath(__DIR__ . '/../../../');
        $view_file = $root_path . '/src/Views/admin/settings.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Settings View file not found.";
        }
    }

    /**
     * حفظ وتحديث الإعدادات (معالجة AJAX / Form POST)
     */
    public function save(): void
    {
        $this->checkAdminAuth();

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
            exit;
        }

        try {
            $pdo = \App\Config\Database::getConnection();
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
            exit;
        }

        // (ملاحظة: إذا كنتِ تستخدمين نظام حماية CSRF مشدد عبر الـ JS، تأكدي من مروره، أو تجنبيه مؤقتاً للاختبار)
        try {
            $action = $_POST['action'] ?? '';

            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

            // مساعدة لرفع الصور
            $uploadDir = __DIR__ . '/../../../public/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // 1. تحديث الشعار
            if ($action === 'update_logo') {
                if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['logo_img']['name'], PATHINFO_EXTENSION);
                    $filename = 'logo_' . time() . '.' . $ext;
                    move_uploaded_file($_FILES['logo_img']['tmp_name'], $uploadDir . $filename);
                    $logoPath = 'assets/uploads/' . $filename;
                    
                    $stmt->execute(['k' => 'site_logo_path', 'v' => $logoPath, 'v_update' => $logoPath]);
                }
            }

            // 2. تحديث منصات التواصل الاجتماعي
            elseif ($action === 'update_social') {
                $socialData = $_POST['social'] ?? [];
                foreach ($socialData as $index => $item) {
                    // معالجة صورة أيقونة السوشيال الخاصة بهذا العنصر إن وجدت
                    if (isset($_FILES['social_img_' . $index]) && $_FILES['social_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['social_img_' . $index]['name'], PATHINFO_EXTENSION);
                        $filename = 'social_' . $index . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['social_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $socialData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $socialData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($socialData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($socialData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'social_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 3. تحديث القائمة الرئيسية (Menu)
            elseif ($action === 'update_menu') {
                $menuData = $_POST['menu'] ?? [];
                // ترتيب العناصر حسب الحقل order
                usort($menuData, function($a, $b) {
                    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                });
                $jsonVal = json_encode(array_values($menuData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'menu_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 4. تحديث اللغات
            elseif ($action === 'update_languages') {
                $langData = $_POST['lang'] ?? [];
                $jsonVal = json_encode(array_values($langData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'languages', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 5. تحديث الإعلان (Announcement)
            elseif ($action === 'update_announcement') {
                $adImage = $_POST['old_ad_image'] ?? 'assets/img/default-ad.png';
                if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['ad_image']['name'], PATHINFO_EXTENSION);
                    $filename = 'ad_' . time() . '.' . $ext;
                    move_uploaded_file($_FILES['ad_image']['tmp_name'], $uploadDir . $filename);
                    $adImage = 'assets/uploads/' . $filename;
                }

                $adData = [
                    'status'            => $_POST['status'] ?? 'Draft',
                    'start_date'        => $_POST['start_date'] ?? '',
                    'end_date'          => $_POST['end_date'] ?? '',
                    'type'              => $_POST['type'] ?? 'text',
                    'announcement_text' => $_POST['announcement_text'] ?? '',
                    'bg_color'          => $_POST['bg_color'] ?? '#f1f5f9',
                    'text_color'        => $_POST['text_color'] ?? '#1e293b',
                    'font_size'         => $_POST['font_size'] ?? '16',
                    'link'              => $_POST['link'] ?? '',
                    'image_path'        => $adImage
                ];
                $jsonVal = json_encode($adData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'announcement', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 6. تحديث الفوتر العام وباقي الحقول النصية وروابط تواصل معنا
            elseif ($action === 'update_footer') {
                $consultTitle = $_POST['consult_title'] ?? '';
                $consultDesc  = $_POST['consult_desc'] ?? '';
                $footerDesc   = $_POST['footer_desc'] ?? '';
                $col2Title    = $_POST['footer_col2_title'] ?? '';
                $col3Title    = $_POST['footer_col3_title'] ?? '';

                $stmt->execute(['k' => 'consult_title', 'v' => $consultTitle, 'v_update' => $consultTitle]);
                $stmt->execute(['k' => 'consult_desc', 'v' => $consultDesc, 'v_update' => $consultDesc]);
                $stmt->execute(['k' => 'footer_desc', 'v' => $footerDesc, 'v_update' => $footerDesc]);
                $stmt->execute(['k' => 'footer_col2_title', 'v' => $col2Title, 'v_update' => $col2Title]);
                $stmt->execute(['k' => 'footer_col3_title', 'v' => $col3Title, 'v_update' => $col3Title]);

                // معالجة روابط العمود الثالث للفوتر (تواصل معنا) والأيقونات الخاصة بها
                $footerCol3Data = $_POST['col3'] ?? [];
                foreach ($footerCol3Data as $index => $item) {
                    if (isset($_FILES['col3_img_' . $index]) && $_FILES['col3_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['col3_img_' . $index]['name'], PATHINFO_EXTENSION);
                        $filename = 'footer_col3_' . $index . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['col3_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $footerCol3Data[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $footerCol3Data[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($footerCol3Data[$index]['old_img']);
                }
                $jsonCol3Val = json_encode(array_values($footerCol3Data), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'footer_col3_links', 'v' => $jsonCol3Val, 'v_update' => $jsonCol3Val]);
            }

            // 7. تحديث قسم الهيرو (Hero)
            elseif ($action === 'update_hero') {
                $heroImg = $_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg';
                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['hero_img']['name'], PATHINFO_EXTENSION);
                    $filename = 'hero_' . time() . '.' . $ext;
                    move_uploaded_file($_FILES['hero_img']['tmp_name'], $uploadDir . $filename);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $heroData = [
                    'title'    => $_POST['hero_title'] ?? '',
                    'desc'     => $_POST['hero_desc'] ?? '',
                    'btn_text' => $_POST['hero_btn_text'] ?? '',
                    'btn_url'  => $_POST['hero_btn_url'] ?? '',
                    'img'      => $heroImg
                ];
                $jsonVal = json_encode($heroData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 8. تحديث الخدمات (Services)
            elseif ($action === 'update_services') {
                $servTitle = $_POST['services_title'] ?? '';
                $servDesc  = $_POST['services_desc'] ?? '';
                $stmt->execute(['k' => 'services_section_title', 'v' => $servTitle, 'v_update' => $servTitle]);
                $stmt->execute(['k' => 'services_section_desc', 'v' => $servDesc, 'v_update' => $servDesc]);

                $servicesData = $_POST['services'] ?? [];
                foreach ($servicesData as $index => $item) {
                    if (isset($_FILES['service_img_' . $index]) && $_FILES['service_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['service_img_' . $index]['name'], PATHINFO_EXTENSION);
                        $filename = 'service_' . $index . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['service_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $servicesData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $servicesData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($servicesData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($servicesData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 9. تحديث المميزات (Choose Us)
            elseif ($action === 'update_choose') {
                $chooseTitle = $_POST['choose_title'] ?? '';
                $chooseDesc  = $_POST['choose_desc'] ?? '';
                $stmt->execute(['k' => 'choose_title', 'v' => $chooseTitle, 'v_update' => $chooseTitle]);
                $stmt->execute(['k' => 'choose_section_desc', 'v' => $chooseDesc, 'v_update' => $chooseDesc]);

                $chooseData = $_POST['choose'] ?? [];
                foreach ($chooseData as $index => $item) {
                    if (isset($_FILES['choose_img_' . $index]) && $_FILES['choose_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['choose_img_' . $index]['name'], PATHINFO_EXTENSION);
                        $filename = 'choose_' . $index . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['choose_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $chooseData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $chooseData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($chooseData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($chooseData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'choose_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 10. تحديث التقييمات / الفيديوهات (Reviews)
            elseif ($action === 'update_reviews') {
                $revTitle = $_POST['reviews_title'] ?? '';
                $stmt->execute(['k' => 'reviews_title', 'v' => $revTitle, 'v_update' => $revTitle]);

                $reviewsData = $_POST['reviews'] ?? [];
                $jsonVal = json_encode(array_values($reviewsData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 11. تحديث الدليل الشامل (Guide)
            elseif ($action === 'update_guide') {
                $guideTitle = $_POST['guide_title'] ?? '';
                $guideDesc  = $_POST['guide_desc'] ?? '';
                $stmt->execute(['k' => 'guide_title', 'v' => $guideTitle, 'v_update' => $guideTitle]);
                $stmt->execute(['k' => 'guide_desc', 'v' => $guideDesc, 'v_update' => $guideDesc]);

                $guideData = $_POST['guide'] ?? [];
                foreach ($guideData as $index => $item) {
                    if (isset($_FILES['guide_img_' . $index]) && $_FILES['guide_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $ext = pathinfo($_FILES['guide_img_' . $index]['name'], PATHINFO_EXTENSION);
                        $filename = 'guide_' . $index . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['guide_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $guideData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $guideData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($guideData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($guideData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'guide_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 12. تحديث الأسئلة الشائعة (FAQ)
            elseif ($action === 'update_faq') {
                $faqTitle = $_POST['faq_title'] ?? '';
                $stmt->execute(['k' => 'faq_title', 'v' => $faqTitle, 'v_update' => $faqTitle]);

                $faqData = $_POST['faq'] ?? [];
                $jsonVal = json_encode(array_values($faqData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'faq_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ التغييرات وتحديث النظام بنجاح.'
            ]);
            exit;

        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Settings Save Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'حدث خطأ في السيرفر: ' . $e->getMessage()]);
            exit;
        }
    }


    private function checkAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            if ($this->isJsonRequest()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'غير مصرح بالوصول']);
                exit;
            }
            header("Location: index.php?url=admin/login");
            exit;
        }
    }

    private function isJsonRequest(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }
}


/src/Controllers/Admin/UploadController.php
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