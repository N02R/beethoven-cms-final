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

        // تم تصحيح اسم الجدول هنا ليطابق global_settings بدلاً من site_settings
        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث قسم الهيرو (Hero)
        if ($action === 'update_hero') {
            $targetLang = $_POST['targetLang'] ?? $_POST['hero_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawHeroSetting = $currentSettings['hero'] ?? '';
            $heroAllLangs = is_string($rawHeroSetting) ? json_decode($rawHeroSetting, true) : (is_array($rawHeroSetting) ? $rawHeroSetting : []);
            
            if (!is_array($heroAllLangs)) {
                $heroAllLangs = [];
            }

            if (isset($heroAllLangs['title']) && !isset($heroAllLangs['ar'])) {
                $oldData = $heroAllLangs;
                $heroAllLangs = ['ar' => $oldData];
            }

            $oldHeroImg = $_POST['old_hero_img'] 
                        ?? $heroAllLangs[$targetLang]['img'] 
                        ?? $heroAllLangs['img'] 
                        ?? 'assets/img/hero-bg.jpg';
            
            if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldHeroImg) && !str_contains($oldHeroImg, 'default') && $oldHeroImg !== 'assets/img/hero-bg.jpg' && $oldHeroImg !== 'assets/img/home/home1.png') {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $oldHeroImg;
            }

            $postedHero = $_POST['hero'][$targetLang] ?? $_POST['hero'] ?? [];

            $heroAllLangs[$targetLang] = [
                'title'    => $postedHero['title'] ?? ($_POST['hero_title'] ?? ''),
                'desc'     => $postedHero['desc'] ?? ($_POST['hero_desc'] ?? ''),
                'btn_text' => $postedHero['btn_text'] ?? ($_POST['hero_btn_text'] ?? ''),
                'btn_url'  => $postedHero['btn_url'] ?? ($_POST['hero_btn_url'] ?? '#'),
                'img'      => $heroImg
            ];

            $heroAllLangs['img'] = $heroImg;

            $jsonVal = json_encode($heroAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 2. تحديث قسم الخدمات (Services)
        elseif ($action === 'update_services') {
            $targetLang = $_POST['targetLang'] ?? $_POST['services_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawServicesSetting = $currentSettings['services'] ?? '';
            $servicesAllLangs = is_string($rawServicesSetting) ? json_decode($rawServicesSetting, true) : (is_array($rawServicesSetting) ? $rawServicesSetting : []);
            
            if (!is_array($servicesAllLangs)) {
                $servicesAllLangs = [];
            }

            if (isset($servicesAllLangs['title']) && !isset($servicesAllLangs['ar'])) {
                $oldData = $servicesAllLangs;
                $servicesAllLangs = ['ar' => $oldData];
            }

            $postedServices = $_POST['services'][$targetLang] ?? $_POST['services'] ?? [];

            $secTitle = $postedServices['section_title'] ?? ($postedServices['title'] ?? ($_POST['services_section_title'] ?? $_POST['services_title'] ?? 'خدماتنا المميزة'));
            $secDesc  = $postedServices['section_subtitle'] ?? ($postedServices['desc'] ?? ($_POST['services_section_subtitle'] ?? $_POST['services_desc'] ?? ''));

            $servicesAllLangs[$targetLang] = [
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'items'            => []
            ];

            $items = $postedServices['items'] ?? $_POST['service_items'] ?? $_POST['services_items'] ?? [];
            if (is_array($items)) {
                foreach ($items as $index => $item) {
                    $oldImg = $item['old_img'] ?? ($servicesAllLangs[$targetLang]['items'][$index]['img'] ?? '');
                    $img = $oldImg;

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

        // 3. تحديث الأسئلة الشائعة (FAQ)
        elseif ($action === 'update_faq') {
            $targetLang = $_POST['targetLang'] ?? $_POST['faq_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawFaqSetting = $currentSettings['faq_items'] ?? '';
            $faqAllLangs = is_string($rawFaqSetting) ? json_decode($rawFaqSetting, true) : (is_array($rawFaqSetting) ? $rawFaqSetting : []);
            if (!is_array($faqAllLangs)) {
                $faqAllLangs = [];
            }

            if (isset($faqAllLangs['title']) && !isset($faqAllLangs['ar'])) {
                $oldData = $faqAllLangs;
                $faqAllLangs = ['ar' => $oldData];
            }

            $faqTitle = $_POST['faq_title'][$targetLang] ?? ($_POST['faq_title'] ?? ($_POST['faq'][$targetLang]['title'] ?? 'الأسئلة الشائعة'));
            $faqDesc  = $_POST['faq_desc'][$targetLang] ?? ($_POST['faq_desc'] ?? ($_POST['faq'][$targetLang]['desc'] ?? ''));

            $rawItems = $_POST['faq'][$targetLang]['items'] ?? ($_POST['faq']['items'] ?? ($_POST['faq'] ?? ($_POST['faq_items'] ?? [])));
            $processedItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_numeric($index) || !is_array($item)) continue;

                    $processedItems[] = [
                        'question' => $item['question'] ?? ($item['title'] ?? ''),
                        'answer'   => $item['answer'] ?? ($item['desc'] ?? '')
                    ];
                }
            }

            $faqAllLangs[$targetLang] = [
                'title'     => $faqTitle,
                'desc'      => $faqDesc,
                'faq_title' => $faqTitle,
                'items'     => $processedItems
            ];

            $jsonVal = json_encode($faqAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'faq_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 4. تحديث التقييمات وفيديوهات العملاء (Reviews)
        elseif ($action === 'update_reviews') {
            $targetLang = $_POST['targetLang'] ?? $_POST['reviews_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawReviewsSetting = $currentSettings['reviews_items'] ?? '';
            $reviewsAllLangs = is_string($rawReviewsSetting) ? json_decode($rawReviewsSetting, true) : (is_array($rawReviewsSetting) ? $rawReviewsSetting : []);
            if (!is_array($reviewsAllLangs)) {
                $reviewsAllLangs = [];
            }

            // قراءة بيانات اللغة الحالية لحمايتها من التصفير
            $currentLangData = $reviewsAllLangs[$targetLang] ?? [];

            // استخراج ما تم إرساله من الفورم
            $postedReviews = $_POST['reviews'][$targetLang] ?? $_POST['reviews'] ?? [];

            // قراءة العنوان (نص نقي)
            $reviewsTitle = trim((string)($postedReviews['title'] ?? ($_POST['reviews_title'] ?? ($currentLangData['title'] ?? 'شاهد ماذا يقول عملاؤنا عنا'))));

            // قراءة الفيديوهات
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $finalItems = [];

            if (isset($postedReviews['items']) && is_array($postedReviews['items'])) {
                foreach ($postedReviews['items'] as $item) {
                    if (!is_array($item)) continue;
                    $url = trim((string)($item['url'] ?? ''));
                    if (!empty($url)) {
                        $finalItems[] = ['url' => $url];
                    }
                }
            } else {
                $finalItems = $existingItems;
            }

            // بناء كائن اللغة الحالية وحفظه
            $reviewsAllLangs[$targetLang] = [
                'title' => $reviewsTitle,
                'items' => $finalItems
            ];

            $jsonVal = json_encode($reviewsAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 5. تحديث قسم المميزات (Choose Us) مع حماية ضد فقدان البيانات
        elseif ($action === 'update_choose') {
            $targetLang = $_POST['targetLang'] ?? $_POST['choose_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawChooseSetting = $currentSettings['choose'] ?? '';
            $chooseAllLangs = is_string($rawChooseSetting) ? json_decode($rawChooseSetting, true) : (is_array($rawChooseSetting) ? $rawChooseSetting : []);
            
            if (!is_array($chooseAllLangs)) {
                $chooseAllLangs = [];
            }

            // الاحتفاظ بالبيانات القديمة للغة الحالية لعدم تصفيرها بالخطأ
            $currentLangData = $chooseAllLangs[$targetLang] ?? [];

            $postedChoose = $_POST['choose'][$targetLang] ?? $_POST['choose'] ?? [];

            // العنوان: إن وصل جديد نأخذه، وإلا نحتفظ بالقديم
            $secTitle = $postedChoose['section_title'] 
                     ?? ($_POST['choose_section_title'] 
                     ?? ($currentLangData['section_title'] ?? ($currentLangData['title'] ?? 'ما الذي يميز بيتهوفن سيتي')));
                     
            $secDesc  = $postedChoose['section_subtitle'] 
                     ?? ($_POST['choose_section_subtitle'] 
                     ?? ($currentLangData['section_subtitle'] ?? ($currentLangData['desc'] ?? '')));

            $existingItems = $currentLangData['items'] ?? [];

            // تجهيز عناصر القائمة
            $finalItems = [];

            // إذا تم إرسال items في الـ POST نعالجها
            if (isset($postedChoose['items']) && is_array($postedChoose['items'])) {
                foreach ($postedChoose['items'] as $index => $item) {
                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    if (isset($_FILES['choose_img_' . $index]) && $_FILES['choose_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default') && !str_starts_with($oldImg, 'assets/img/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['choose_img_' . $index]['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'title' => trim($item['title'] ?? ''),
                        'desc'  => trim($item['desc'] ?? ''),
                        'img'   => $img,
                        'url'   => $item['url'] ?? '#'
                    ];
                }
            } else {
                // إذا لم يتم إرسال items مطلقاً في الـ POST (مثل عند تعديل العنوان فقط)، لا نمسح البطاقات القديمة!
                $finalItems = $existingItems;
            }

            // حفظ المصفوفة المدمجة للغة
            $chooseAllLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'items'            => $finalItems
            ];

            $jsonVal = json_encode($chooseAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'choose', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 6. تحديث الدليل الشامل (Guide)
        elseif ($action === 'update_guide') {
            $targetLang = $_POST['targetLang'] ?? $_POST['guide_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawGuideSetting = $currentSettings['guide_items'] ?? '';
            $guideAllLangs = is_string($rawGuideSetting) ? json_decode($rawGuideSetting, true) : (is_array($rawGuideSetting) ? $rawGuideSetting : []);
            if (!is_array($guideAllLangs)) {
                $guideAllLangs = [];
            }

            if (isset($guideAllLangs['title']) && !isset($guideAllLangs['ar'])) {
                $oldData = $guideAllLangs;
                $guideAllLangs = ['ar' => $oldData];
            }

            $guideTitle = $_POST['guide_title'][$targetLang] ?? ($_POST['guide_title'] ?? ($_POST['guide'][$targetLang]['title'] ?? ''));
            $guideDesc  = $_POST['guide_desc'][$targetLang] ?? ($_POST['guide_desc'] ?? ($_POST['guide'][$targetLang]['desc'] ?? ''));

            $rawItems = $_POST['guide'][$targetLang]['items'] ?? ($_POST['guide']['items'] ?? ($_POST['guide'] ?? ($_POST['guide_items'] ?? [])));
            $existingLangItems = $guideAllLangs[$targetLang]['items'] ?? [];
            $processedItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_numeric($index) || !is_array($item)) continue;

                    $oldImg = $item['old_img'] ?? ($existingLangItems[$index]['img'] ?? '');
                    $finalImg = $oldImg;

                    $fileTmp = null;
                    if (isset($_FILES['guide_img_' . $index]) && $_FILES['guide_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['guide_img_' . $index]['tmp_name'];
                    } elseif (isset($_FILES['guide']['tmp_name'][$targetLang]['items'][$index]['img']) && $_FILES['guide']['error'][$targetLang]['items'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['guide']['tmp_name'][$targetLang]['items'][$index]['img'];
                    }

                    if ($fileTmp) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default') && !str_starts_with($oldImg, 'assets/img/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileTmp);
                        $finalImg = 'assets/uploads/' . $filename;
                    }

                    $processedItems[] = [
                        'title' => $item['title'] ?? '',
                        'desc'  => $item['desc'] ?? '',
                        'link'  => $item['link'] ?? ($item['url'] ?? '#'),
                        'img'   => $finalImg
                    ];
                }
            }

            $guideAllLangs[$targetLang] = [
                'title'       => $guideTitle,
                'desc'        => $guideDesc,
                'guide_title' => $guideTitle,
                'guide_desc'  => $guideDesc,
                'items'       => $processedItems
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
