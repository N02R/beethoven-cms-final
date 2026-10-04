<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class HomeSettingsService
{
    private string $rootPath;
    private ImageUploader $imageUploader;

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = $rootPath;
        $this->imageUploader = $imageUploader;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $allowedActions = [
            'update_hero', 
            'update_services', 
            'update_faq', 
            'update_reviews', 
            'update_choose', 
            'update_guide'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث قسم الهيرو (Hero) مع دعم اللغات المتعددة وبدون فقدان البيانات
        if ($action === 'update_hero') {
            $targetLang = $_POST['hero_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawHeroSetting = $currentSettings['hero'] ?? '';
            $heroAllLangs = is_string($rawHeroSetting) ? json_decode($rawHeroSetting, true) : (is_array($rawHeroSetting) ? $rawHeroSetting : []);
            
            if (!is_array($heroAllLangs)) {
                $heroAllLangs = [];
            }

            // تحويل البيانات القديمة التي لم تكن مقسمة حسب اللغات إن وجدت
            if (isset($heroAllLangs['title']) && !isset($heroAllLangs['ar'])) {
                $oldData = $heroAllLangs;
                $heroAllLangs = ['ar' => $oldData];
            }

            // تحديد مسار الصورة القديمة للغة الحالية أو للـ Hero العام
            $oldHeroImg = $_POST['old_hero_img'] 
                        ?? $heroAllLangs[$targetLang]['img'] 
                        ?? $heroAllLangs['img'] 
                        ?? 'assets/img/hero-bg.jpg';
            
            if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                // حذف الصورة القديمة إذا لم تكن افتراضية
                if (!empty($oldHeroImg) && !str_contains($oldHeroImg, 'default') && $oldHeroImg !== 'assets/img/hero-bg.jpg' && $oldHeroImg !== 'assets/img/home/home1.png') {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $oldHeroImg;
            }

            $postedHero = $_POST['hero'][$targetLang] ?? $_POST['hero'] ?? [];

            // إعداد بيانات اللغة الحالية
            $heroAllLangs[$targetLang] = [
                'title'    => $postedHero['title'] ?? ($_POST['hero_title'] ?? ''),
                'desc'     => $postedHero['desc'] ?? ($_POST['hero_desc'] ?? ''),
                'btn_text' => $postedHero['btn_text'] ?? ($_POST['hero_btn_text'] ?? ''),
                'btn_url'  => $postedHero['btn_url'] ?? ($_POST['hero_btn_url'] ?? '#'),
                'img'      => $heroImg
            ];

            // تحديث الصورة العامة كبديل احتياطي
            $heroAllLangs['img'] = $heroImg;

            $jsonVal = json_encode($heroAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 2. تحديث قسم الخدمات (Services) مع دعم اللغات المتعددة والصور بطريقة شاملة للتوافقية
        elseif ($action === 'update_services') {
            $targetLang = $_POST['services_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawServicesSetting = $currentSettings['services'] ?? '';
            $servicesAllLangs = is_string($rawServicesSetting) ? json_decode($rawServicesSetting, true) : (is_array($rawServicesSetting) ? $rawServicesSetting : []);
            
            if (!is_array($servicesAllLangs)) {
                $servicesAllLangs = [];
            }

            // تحويل الهيكلية القديمة غير المترجمة إن وجدت
            if (isset($servicesAllLangs['title']) && !isset($servicesAllLangs['ar'])) {
                $oldData = $servicesAllLangs;
                $servicesAllLangs = ['ar' => $oldData];
            }

            $postedServices = $_POST['services'][$targetLang] ?? $_POST['services'] ?? [];

            // تحديد العنوان والوصف الرئيسي مع مرونة المفاتيح والقيم الافتراضية
            $secTitle = $postedServices['section_title'] ?? ($postedServices['title'] ?? ($_POST['services_section_title'] ?? $_POST['services_title'] ?? 'خدماتنا المميزة'));
            $secDesc  = $postedServices['section_subtitle'] ?? ($postedServices['desc'] ?? ($_POST['services_section_subtitle'] ?? $_POST['services_desc'] ?? ''));

            // معالجة البيانات النصية للقسم بتمرير المفاتيح الجديدة والقديمة معاً لضمان التوافقية
            $servicesAllLangs[$targetLang] = [
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'items'            => []
            ];

            // استخراج العناصر بحسب هيكليات الـ POST المختلفة لتجنب فقد البيانات
            $items = $postedServices['items'] ?? $_POST['service_items'] ?? $_POST['services_items'] ?? [];
            if (is_array($items)) {
                foreach ($items as $index => $item) {
                    $oldImg = $item['old_img'] ?? ($servicesAllLangs[$targetLang]['items'][$index]['img'] ?? '');
                    $img = $oldImg;

                    // فحص كافة مسارات الصور المرفوعة المحتملة
                    $fileTmp = null;
                    $fileErr = UPLOAD_ERR_NO_FILE;

                    if (isset($_FILES['service_items']['tmp_name'][$index]['img']) && $_FILES['service_items']['error'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_items']['tmp_name'][$index]['img'];
                        $fileErr = $_FILES['service_items']['error'][$index]['img'];
                    } elseif (isset($_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img']) && $_FILES['services']['error'][$targetLang]['items'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img'];
                        $fileErr = $_FILES['services']['error'][$targetLang]['items'][$index]['img'];
                    } elseif (isset($_FILES['service_img_' . $index]) && $_FILES['service_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_img_' . $index]['tmp_name'];
                        $fileErr = $_FILES['service_img_' . $index]['error'];
                    }

                    // معالجة الرفع واستبدال الملف القديم
                    if ($fileTmp && $fileErr === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileTmp);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $servicesAllLangs[$targetLang]['items'][] = [
                        'title' => $item['title'] ?? 'عنوان الخدمة',
                        'desc'  => $item['desc'] ?? '',
                        'icon'  => $item['icon'] ?? 'bi bi-concierge-bell',
                        'img'   => $img,
                        'url'   => $item['url'] ?? '#'
                    ];
                }
            }

            $jsonVal = json_encode($servicesAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 3. تحديث الأسئلة الشائعة (FAQ) مع دعم اللغات المتعددة
        elseif ($action === 'update_faq') {
            $targetLang = $_POST['faq_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

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

        // 4. تحديث التقييمات (Reviews) مع دعم اللغات المتعددة
        elseif ($action === 'update_reviews') {
            $targetLang = $_POST['reviews_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

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

        // 5. تحديث المميزات (Choose) مع دعم اللغات المتعددة والصور
        elseif ($action === 'update_choose') {
            $targetLang = $_POST['choose_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

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
                    if (!empty($oldImg) && !str_contains($oldImg, 'default')) {
                        $this->deleteOldImageFile($oldImg);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
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

        // 6. تحديث الدليل الشامل (Guide) مع دعم اللغات المتعددة والصور
        elseif ($action === 'update_guide') {
            $targetLang = $_POST['guide_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

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
                    if (!empty($oldImg) && !str_contains($oldImg, 'default')) {
                        $this->deleteOldImageFile($oldImg);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
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

        return true;
    }

    private function deleteOldImageFile(string $imagePath): void
    {
        if (!empty($imagePath) && str_starts_with($imagePath, 'assets/uploads/')) {
            $fullPath = $this->rootPath . '/public/' . $imagePath;
            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
