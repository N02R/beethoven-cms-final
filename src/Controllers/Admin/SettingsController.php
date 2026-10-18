<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;
use App\Services\Settings\AboutSettingsService;
use App\Services\Settings\ContactSettingsService;
use App\Services\Settings\EduSettingsService;
use App\Services\Settings\HeaderSettingsService;
use App\Services\Settings\JobSettingsService;
use App\Services\Settings\GuideBlog1SettingsService;
use App\Services\Settings\PageContentSettingsService;
use App\Services\Settings\HomeSettingsService;
use Exception;
use PDO;

class SettingsController
{
    public function save(): void
    {
        $this->checkAdminAuth();

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
            exit;
        }

        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? $_POST['csrf_token'] ?? '';

        if (!Security::verifyCsrfToken($token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'انصهار الجلسة أو خطأ في الـ CSRF Token']);
            exit;
        }

        try {
            $pdo = \App\Config\Database::getConnection();
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
            exit;
        }

        $root_path = realpath(__DIR__ . '/../../../');
        $uploadDir = $root_path . '/public/assets/uploads/';
        
        $imageUploader = new ImageUploader($uploadDir);

        try {
            $action = $_POST['action'] ?? '';

            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

            $currentSettingsStmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $currentSettings = $currentSettingsStmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            // 1. فحص هيدر الموقع
            $headerService = new HeaderSettingsService($root_path, $imageUploader);
            if ($headerService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات الهيدر وتحديث الصور بنجاح.']);
                exit;
            }

            // 2. فحص الصفحات الفردية
            $pageService = new PageContentSettingsService($root_path, $imageUploader);
            if ($pageService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ التغييرات وتحديث الصور بنجاح.']);
                exit;
            }

            // 3. فحص قسم من نحن (About & Team)
            $aboutService = new AboutSettingsService($root_path, $imageUploader);
            if ($aboutService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات قسم من نحن وتحديث الصور بنجاح.']);
                exit;
            }

            // 4. فحص أقسام التعليم العالي (Edu)
            $eduService = new EduSettingsService($root_path, $imageUploader);
            if ($eduService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات أقسام التعليم العالي وتحديث الصور بنجاح.']);
                exit;
            }

            // 5. فحص أقسام فرص العمل والتوظيف (Job)
            $jobService = new JobSettingsService($root_path, $imageUploader);
            if ($jobService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات أقسام فرص العمل وتحديث الصور بنجاح.']);
                exit;
            }

            // 6. فحص أقسام تواصل معنا (Contact)
            $contactService = new ContactSettingsService($root_path, $imageUploader);
            if ($contactService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات تواصل معنا وتحديث الصور بنجاح.']);
                exit;
            }

            // 7. فحص أقسام مقال الدليل الأول (GuideBlog1)
            $guideBlog1Service = new GuideBlog1SettingsService($root_path, $imageUploader);
            if ($guideBlog1Service->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات مقال الدليل وتحديث الصور بنجاح.']);
                exit;
            }

            // 8. فحص أقسام الصفحة الرئيسية (Home: Hero, Services, FAQ, Reviews, Choose, Guide)
            $homeService = new HomeSettingsService($root_path, $imageUploader);
            if ($homeService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات الصفحة الرئيسية وتحديث الصور بنجاح.']);
                exit;
            }

            // 9. تحديث اللغات العامة
            if ($action === 'update_languages') {
                $langData = $_POST['lang'] ?? [];
                $jsonVal = json_encode(array_values($langData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'languages', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 10. تحديث الإعلان (Announcement) مع دعم اللغات المتعددة
            elseif ($action === 'update_announcement') {
                $targetLang = $_POST['announcement_lang'] ?? 'ar';

                $rawAnnouncementSetting = $currentSettings['announcement'] ?? '';
                $announcements = json_decode($rawAnnouncementSetting, true);
                if (!is_array($announcements)) {
                    $announcements = [];
                    if (!empty($rawAnnouncementSetting)) {
                        $legacyData = json_decode($rawAnnouncementSetting, true);
                        if (is_array($legacyData)) {
                            $announcements['ar'] = $legacyData;
                        }
                    }
                }

                $oldAdImage = $announcements[$targetLang]['image_path'] ?? ($_POST['old_ad_image'] ?? 'assets/img/default-ad.png');
                
                if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($announcements[$targetLang]['image_path'])) {
                        $this->deleteOldImageFile($root_path, $announcements[$targetLang]['image_path']);
                    }

                    $filename = $imageUploader->processAndUploadFile($_FILES['ad_image']['tmp_name']);
                    $adImage = 'assets/uploads/' . $filename;
                } else {
                    $adImage = $oldAdImage;
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

                $announcements[$targetLang] = $adData;

                $jsonVal = json_encode($announcements, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'announcement', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 11. تحديث الفوتر العام وروابط العمود الثالث
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

                $footerCol3Data = $_POST['col3'] ?? [];
                foreach ($footerCol3Data as $index => $item) {
                    $fileToCheck = $_FILES['col3_img_' . $index] ?? ($_FILES['col3'][$index]['img'] ?? null);
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $footerCol3Data[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $footerCol3Data[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($footerCol3Data[$index]['old_img']);
                }
                $jsonCol3Val = json_encode(array_values($footerCol3Data), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'footer_col3_links', 'v' => $jsonCol3Val, 'v_update' => $jsonCol3Val]);
            }
            else {
                // منع النجاح الوهمي في حال لم يتطابق أي إجراء
                $pdo->rollBack();
                http_response_code(400);
                echo json_encode([
                    'success' => false, 
                    'error' => 'الإجراء المطلوب غير معروف أو غير مبرمج (Action: ' . htmlspecialchars($action) . ')'
                ]);
                exit;
            }

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ التغييرات وتحديث الصور بصيغة WebP بنجاح.'
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

    private function deleteOldImageFile(string $rootPath, string $imagePath): void
    {
        if (!empty($imagePath) && str_starts_with($imagePath, 'assets/uploads/')) {
            $fullPath = $rootPath . '/public/' . $imagePath;
            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }
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
