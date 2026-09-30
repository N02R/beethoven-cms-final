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

            // 0.أ. فحص هيدر الموقع
            $headerService = new HeaderSettingsService($root_path, $imageUploader);
            if ($headerService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات الهيدر وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.ب. فحص الصفحات الفردية
            $pageService = new PageContentSettingsService($root_path, $imageUploader);
            if ($pageService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ التغييرات وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.ج. فحص قسم من نحن (About & Team)
            $aboutService = new AboutSettingsService($root_path, $imageUploader);
            if ($aboutService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات قسم من نحن وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.د. فحص أقسام التعليم العالي (Edu)
            $eduService = new EduSettingsService($root_path, $imageUploader);
            if ($eduService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات أقسام التعليم العالي وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.هـ. فحص أقسام فرص العمل والتوظيف (Job)
            $jobService = new JobSettingsService($root_path, $imageUploader);
            if ($jobService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات أقسام فرص العمل وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.و. فحص أقسام تواصل معنا (Contact)
            $contactService = new ContactSettingsService($root_path, $imageUploader);
            if ($contactService->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات تواصل معنا وتحديث الصور بنجاح.']);
                exit;
            }

            // 0.ز. فحص أقسام مقال الدليل الأول (GuideBlog1)
            $guideBlog1Service = new GuideBlog1SettingsService($root_path, $imageUploader);
            if ($guideBlog1Service->handleAction($action, $pdo, $currentSettings)) {
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات مقال الدليل وتحديث الصور بنجاح.']);
                exit;
            }

            // 4. تحديث اللغات
            if ($action === 'update_languages') {
                $langData = $_POST['lang'] ?? [];
                $jsonVal = json_encode(array_values($langData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'languages', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 5. تحديث الإعلان (Announcement) مع دعم اللغات المتعددة (ar, en, de)
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

            // 6. تحديث الفوتر العام وروابط العمود الثالث
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

            // 7. تحديث قسم الهيرو (Hero) مع دعم اللغات المتعددة (ar, en, de) وبدون فقدان البيانات
            elseif ($action === 'update_hero') {
                $targetLang = $_POST['hero_lang'] ?? 'ar';

                $rawHeroSetting = $currentSettings['hero'] ?? '';
                $heroAllLangs = json_decode($rawHeroSetting, true);
                if (!is_array($heroAllLangs)) {
                    $heroAllLangs = [];
                    if (!empty($rawHeroSetting)) {
                        $legacyData = json_decode($rawHeroSetting, true);
                        if (is_array($legacyData)) {
                            $heroAllLangs['ar'] = $legacyData;
                        }
                    }
                }

                $oldHeroImg = $heroAllLangs[$targetLang]['img'] ?? ($heroAllLangs['img'] ?? ($_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg'));
                
                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldHeroImg) && !str_contains($oldHeroImg, 'default') && $oldHeroImg !== 'assets/img/hero-bg.jpg') {
                        $this->deleteOldImageFile($root_path, $oldHeroImg);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                } else {
                    $heroImg = $oldHeroImg;
                }

                $postedHero = $_POST['hero'][$targetLang] ?? [];

                $langHeroData = [
                    'title'    => $postedHero['title'] ?? ($_POST['hero_title'] ?? ''),
                    'desc'     => $postedHero['desc'] ?? ($_POST['hero_desc'] ?? ''),
                    'btn_text' => $postedHero['btn_text'] ?? ($_POST['hero_btn_text'] ?? ''),
                    'btn_url'  => $postedHero['btn_url'] ?? ($_POST['hero_btn_url'] ?? '#'),
                    'img'      => $heroImg
                ];

                $heroAllLangs[$targetLang] = $langHeroData;
                $heroAllLangs['img'] = $heroImg;

                $jsonVal = json_encode($heroAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 8. تحديث الخدمات (Services) مع دعم اللغات المتعددة (ar, en, de)
            elseif ($action === 'update_services') {
                $targetLang = $_POST['services_lang'] ?? $_POST['lang'] ?? 'ar';

                $rawServicesSetting = $currentSettings['services'] ?? '';
                $servicesAllLangs = json_decode($rawServicesSetting, true);
                if (!is_array($servicesAllLangs)) {
                    $servicesAllLangs = [];
                }

                $servicesData = $_POST['services'] ?? [];
                $existingLangData = $servicesAllLangs[$targetLang]['items'] ?? [];

                foreach ($servicesData as $index => $item) {
                    $fileToCheck = $_FILES['service_img_' . $index] ?? ($_FILES['services'][$index]['img'] ?? null);
                    $oldImg = $item['old_img'] ?? ($existingLangData[$index]['img'] ?? '');

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg)) {
                            $this->deleteOldImageFile($root_path, $oldImg);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $servicesData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $servicesData[$index]['img'] = $oldImg;
                    }
                    unset($servicesData[$index]['old_img']);
                }

                // توحيد المفاتيح (title, desc, items) لتتطابق تماماً مع HomeModel
                $servicesAllLangs[$targetLang] = [
                    'title' => $_POST['services_title'] ?? '',
                    'desc'  => $_POST['services_desc'] ?? '',
                    'items' => array_values($servicesData)
                ];

                $jsonVal = json_encode($servicesAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 10. تحديث الأسئلة الشائعة (FAQ) مع دعم اللغات المتعددة
            elseif ($action === 'update_faq') {
                $targetLang = $_POST['faq_lang'] ?? $_POST['lang'] ?? 'ar';

                $rawFaqSetting = $currentSettings['faq_items'] ?? '';
                $faqAllLangs = json_decode($rawFaqSetting, true);
                if (!is_array($faqAllLangs)) {
                    $faqAllLangs = [];
                    if (!empty($rawFaqSetting)) {
                        $legacyData = json_decode($rawFaqSetting, true);
                        if (is_array($legacyData)) {
                            $faqAllLangs['ar'] = [
                                'faq_title' => $currentSettings['faq_title'] ?? 'الأسئلة الشائعة',
                                'items'     => $legacyData
                            ];
                        }
                    }
                }

                $faqData = $_POST['faq'] ?? [];
                $faqAllLangs[$targetLang] = [
                    'faq_title' => $_POST['faq_title'] ?? 'الأسئلة الشائعة',
                    'items'     => array_values($faqData)
                ];

                $jsonVal = json_encode($faqAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'faq_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 11. تحديث التقييمات (Reviews) مع دعم اللغات المتعددة
            elseif ($action === 'update_reviews') {
                $targetLang = $_POST['reviews_lang'] ?? $_POST['lang'] ?? 'ar';

                $rawReviewsSetting = $currentSettings['reviews_items'] ?? '';
                $reviewsAllLangs = json_decode($rawReviewsSetting, true);
                if (!is_array($reviewsAllLangs)) {
                    $reviewsAllLangs = [];
                    if (!empty($rawReviewsSetting)) {
                        $legacyData = json_decode($rawReviewsSetting, true);
                        if (is_array($legacyData)) {
                            $reviewsAllLangs['ar'] = [
                                'reviews_title' => $currentSettings['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا',
                                'items'         => $legacyData
                            ];
                        }
                    }
                }

                $reviewsData = $_POST['reviews'] ?? [];
                $reviewsAllLangs[$targetLang] = [
                    'reviews_title' => $_POST['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا',
                    'items'         => array_values($reviewsData)
                ];

                $jsonVal = json_encode($reviewsAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 12. تحديث المميزات (Choose) مع دعم اللغات المتعددة والصور
            elseif ($action === 'update_choose') {
                $targetLang = $_POST['choose_lang'] ?? $_POST['lang'] ?? 'ar';

                $rawChooseSetting = $currentSettings['choose_items'] ?? '';
                $chooseAllLangs = json_decode($rawChooseSetting, true);
                if (!is_array($chooseAllLangs)) {
                    $chooseAllLangs = [];
                    if (!empty($rawChooseSetting)) {
                        $legacyData = json_decode($rawChooseSetting, true);
                        if (is_array($legacyData)) {
                            $chooseAllLangs['ar'] = [
                                'choose_title'        => $currentSettings['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي',
                                'choose_section_desc' => $currentSettings['choose_section_desc'] ?? '',
                                'items'               => $legacyData
                            ];
                        }
                    }
                }

                $chooseData = $_POST['choose'] ?? [];
                $existingLangData = $chooseAllLangs[$targetLang]['items'] ?? [];

                foreach ($chooseData as $index => $item) {
                    $fileToCheck = $_FILES['choose_img_' . $index] ?? ($_FILES['choose'][$index]['img'] ?? null);
                    $oldImg = $item['old_img'] ?? ($existingLangData[$index]['img'] ?? '');

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg)) {
                            $this->deleteOldImageFile($root_path, $oldImg);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $chooseData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $chooseData[$index]['img'] = $oldImg;
                    }
                    unset($chooseData[$index]['old_img']);
                }

                $chooseAllLangs[$targetLang] = [
                    'choose_title'        => $_POST['choose_title'] ?? '',
                    'choose_section_desc' => $_POST['choose_desc'] ?? '',
                    'items'               => array_values($chooseData)
                ];

                $jsonVal = json_encode($chooseAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'choose_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 13. تحديث الدليل الشامل (Guide) مع دعم اللغات المتعددة والصور
            elseif ($action === 'update_guide') {
                $targetLang = $_POST['guide_lang'] ?? $_POST['lang'] ?? 'ar';

                $rawGuideSetting = $currentSettings['guide_items'] ?? '';
                $guideAllLangs = json_decode($rawGuideSetting, true);
                if (!is_array($guideAllLangs)) {
                    $guideAllLangs = [];
                    if (!empty($rawGuideSetting)) {
                        $legacyData = json_decode($rawGuideSetting, true);
                        if (is_array($legacyData)) {
                            $guideAllLangs['ar'] = [
                                'guide_title' => $currentSettings['guide_title'] ?? 'دليل بيتهوفن الشامل',
                                'guide_desc'  => $currentSettings['guide_desc'] ?? '',
                                'items'       => $legacyData
                            ];
                        }
                    }
                }

                $guideData = $_POST['guide'] ?? [];
                $existingLangData = $guideAllLangs[$targetLang]['items'] ?? [];

                foreach ($guideData as $index => $item) {
                    $fileToCheck = $_FILES['guide_img_' . $index] ?? ($_FILES['guide'][$index]['img'] ?? null);
                    $oldImg = $item['old_img'] ?? ($existingLangData[$index]['img'] ?? '');

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg)) {
                            $this->deleteOldImageFile($root_path, $oldImg);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $guideData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $guideData[$index]['img'] = $oldImg;
                    }
                    unset($guideData[$index]['old_img']);
                }

                $guideAllLangs[$targetLang] = [
                    'guide_title' => $_POST['guide_title'] ?? '',
                    'guide_desc'  => $_POST['guide_desc'] ?? '',
                    'items'       => array_values($guideData)
                ];

                $jsonVal = json_encode($guideAllLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'guide_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
