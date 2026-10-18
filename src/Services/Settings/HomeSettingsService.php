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

        // 4. تحديث التقييمات (Reviews)
        elseif ($action === 'update_reviews') {
            $targetLang = $_POST['targetLang'] ?? $_POST['reviews_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawReviewsSetting = $currentSettings['reviews_items'] ?? '';
            $reviewsAllLangs = is_string($rawReviewsSetting) ? json_decode($rawReviewsSetting, true) : (is_array($rawReviewsSetting) ? $rawReviewsSetting : []);
            if (!is_array($reviewsAllLangs)) {
                $reviewsAllLangs = [];
            }

            if (isset($reviewsAllLangs['title']) && !isset($reviewsAllLangs['ar'])) {
                $oldData = $reviewsAllLangs;
                $reviewsAllLangs = ['ar' => $oldData];
            }

            $reviewsTitle = $_POST['reviews_title'][$targetLang] ?? ($_POST['reviews_title'] ?? ($_POST['reviews'][$targetLang]['title'] ?? 'شاهد ماذا يقول عملاؤنا عنا'));
            $reviewsDesc  = $_POST['reviews_desc'][$targetLang] ?? ($_POST['reviews_desc'] ?? ($_POST['reviews'][$targetLang]['desc'] ?? ''));

            $rawItems = $_POST['reviews'][$targetLang]['items'] ?? ($_POST['reviews']['items'] ?? ($_POST['reviews'] ?? ($_POST['reviews_items'] ?? [])));
            $existingLangItems = $reviewsAllLangs[$targetLang]['items'] ?? [];
            $processedItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_numeric($index) || !is_array($item)) continue;

                    $oldImg = $item['old_img'] ?? ($existingLangItems[$index]['img'] ?? ($existingLangItems[$index]['avatar'] ?? ''));
                    $finalImg = $oldImg;

                    $fileTmp = null;
                    if (isset($_FILES['review_img_' . $index]) && $_FILES['review_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['review_img_' . $index]['tmp_name'];
                    } elseif (isset($_FILES['reviews']['tmp_name'][$targetLang]['items'][$index]['img']) && $_FILES['reviews']['error'][$targetLang]['items'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['reviews']['tmp_name'][$targetLang]['items'][$index]['img'];
                    }

                    if ($fileTmp) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default') && !str_starts_with($oldImg, 'assets/img/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileTmp);
                        $finalImg = 'assets/uploads/' . $filename;
                    }

                    $processedItems[] = [
                        'name'    => $item['name'] ?? ($item['title'] ?? ''),
                        'role'    => $item['role'] ?? '',
                        'comment' => $item['comment'] ?? ($item['desc'] ?? ''),
                        'rating'  => $item['rating'] ?? '5',
                        'img'     => $finalImg,
                        'avatar'  => $finalImg
                    ];
                }
            }

            $reviewsAllLangs[$targetLang] = [
                'title'         => $reviewsTitle,
                'desc'          => $reviewsDesc,
                'reviews_title' => $reviewsTitle,
                'items'         => $processedItems
            ];

            $jsonVal = json_encode($reviewsAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 5. تحديث المميزات (Choose Us)
        elseif ($action === 'update_choose') {
            $targetLang = $_POST['targetLang'] ?? $_POST['choose_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $rawChooseSetting = $currentSettings['choose_items'] ?? '';
            $chooseAllLangs = is_string($rawChooseSetting) ? json_decode($rawChooseSetting, true) : (is_array($rawChooseSetting) ? $rawChooseSetting : []);
            if (!is_array($chooseAllLangs)) {
                $chooseAllLangs = [];
            }

            if (isset($chooseAllLangs['title']) && !isset($chooseAllLangs['ar'])) {
                $oldData = $chooseAllLangs;
                $chooseAllLangs = ['ar' => $oldData];
            }

            // جلب البيانات القديمة للغة المستهدفة لحمايتها في حال إرسال قيمة فارغة
            $oldLangData = $chooseAllLangs[$targetLang] ?? [];
            $oldTitle = $oldLangData['title'] ?? $oldLangData['choose_title'] ?? '';
            $oldDesc  = $oldLangData['desc']  ?? $oldLangData['choose_section_desc'] ?? '';

            // 1. استخراج العنوان الجديد مع الوقاية من القيم الفارغة
            $inputTitle = $_POST['choose_title'][$targetLang] 
                       ?? ($_POST['choose_title'] 
                       ?? ($_POST['choose'][$targetLang]['title'] 
                       ?? ($_POST['choose']['title'] ?? null)));

            if (is_array($inputTitle)) {
                $inputTitle = $inputTitle[$targetLang] ?? reset($inputTitle);
            }

            $chooseTitle = (!is_null($inputTitle) && trim((string)$inputTitle) !== '') 
                         ? trim((string)$inputTitle) 
                         : $oldTitle;

            // 2. استخراج الوصف الجديد مع الوقاية من القيم الفارغة
            $inputDesc  = $_POST['choose_desc'][$targetLang] 
                       ?? ($_POST['choose_desc'] 
                       ?? ($_POST['choose'][$targetLang]['desc'] 
                       ?? ($_POST['choose']['desc'] ?? null)));

            if (is_array($inputDesc)) {
                $inputDesc = $inputDesc[$targetLang] ?? reset($inputDesc);
            }

            $chooseDesc  = (!is_null($inputDesc) && trim((string)$inputDesc) !== '') 
                         ? trim((string)$inputDesc) 
                         : $oldDesc;

            // 3. معالجة عناصر المميزات (items)
            $rawItems = $_POST['choose'][$targetLang]['items'] 
                     ?? ($_POST['choose']['items'] 
                     ?? ($_POST['choose'] ?? []));

            if (!is_array($rawItems)) {
                $rawItems = [];
            }

            $existingLangData = $oldLangData['items'] ?? [];
            $processedItems = [];

            foreach ($rawItems as $index => $item) {
                if (!is_numeric($index) || !is_array($item)) {
                    continue;
                }

                $oldImg = $item['old_img'] ?? ($existingLangData[$index]['img'] ?? '');
                $finalImg = $oldImg;

                $fileTmp = null;
                $fileErr = UPLOAD_ERR_NO_FILE;

                if (isset($_FILES['choose_img_' . $index]) && $_FILES['choose_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['choose_img_' . $index]['tmp_name'];
                    $fileErr = $_FILES['choose_img_' . $index]['error'];
                } elseif (isset($_FILES['choose']['tmp_name'][$targetLang]['items'][$index]['img']) && $_FILES['choose']['error'][$targetLang]['items'][$index]['img'] === UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['choose']['tmp_name'][$targetLang]['items'][$index]['img'];
                    $fileErr = $_FILES['choose']['error'][$targetLang]['items'][$index]['img'];
                } elseif (isset($_FILES['choose']['tmp_name'][$index]['img']) && $_FILES['choose']['error'][$index]['img'] === UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['choose']['tmp_name'][$index]['img'];
                    $fileErr = $_FILES['choose']['error'][$index]['img'];
                }

                if ($fileTmp && $fileErr === UPLOAD_ERR_OK) {
                    if (!empty($oldImg) && !str_contains($oldImg, 'default') && !str_starts_with($oldImg, 'assets/img/')) {
                        $this->deleteOldImageFile($oldImg);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileTmp);
                    $finalImg = 'assets/uploads/' . $filename;
                }

                $processedItems[] = [
                    'title' => $item['title'] ?? '',
                    'desc'  => $item['desc'] ?? '',
                    'img'   => $finalImg
                ];
            }

            // 4. دمج البيانات والحفظ النهائي
            $chooseAllLangs[$targetLang] = [
                'title'               => $chooseTitle,
                'desc'                => $chooseDesc,
                'choose_title'        => $chooseTitle,
                'choose_section_desc' => $chooseDesc,
                'items'               => $processedItems
            ];

            $jsonVal = json_encode($chooseAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'choose_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
