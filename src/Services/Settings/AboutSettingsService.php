<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class AboutSettingsService
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
            'update_about_section',
            'update_about_counts',
            'update_about_partners',
            'update_about_team',
            'update_team',
            'update_about_services' // 🌟 تمت الإضافه بنجاح
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث قسم من نحن الرئيسي (About Section) مع دعم كامل لتعدد اللغات والحماية
        if ($action === 'update_about_section') {
            $targetLang = $_POST['about_lang'] ?? $_POST['targetLang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'about_section'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $aboutAllLangs = [];
            if ($dbRaw && is_string($dbRaw)) {
                $decodedDb = json_decode($dbRaw, true);
                if (is_array($decodedDb)) {
                    $aboutAllLangs = $decodedDb;
                }
            }

            $currentLangData = $aboutAllLangs[$targetLang] ?? ($aboutAllLangs['ar'] ?? []);
            $postAbout = $_POST['about'][$targetLang] ?? $_POST['about'] ?? [];

            // معالجة الصور مع الحفاظ على القديم إن لم يُرفع جديد
            $mainImg = $postAbout['old_main_img'] ?? ($currentLangData['main_img'] ?? '');
            if (isset($_FILES['about_main_img']) && $_FILES['about_main_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($currentLangData['main_img']) && str_starts_with($currentLangData['main_img'], 'assets/uploads/')) {
                    $this->deleteOldImageFile($currentLangData['main_img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_main_img']['tmp_name']);
                $mainImg = 'assets/uploads/' . $filename;
            }

            $subImg = $postAbout['old_sub_img'] ?? ($currentLangData['sub_img'] ?? '');
            if (isset($_FILES['about_sub_img']) && $_FILES['about_sub_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($currentLangData['sub_img']) && str_starts_with($currentLangData['sub_img'], 'assets/uploads/')) {
                    $this->deleteOldImageFile($currentLangData['sub_img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_sub_img']['tmp_name']);
                $subImg = 'assets/uploads/' . $filename;
            }

            $visionIcon = $postAbout['old_vision_icon'] ?? ($currentLangData['vision_icon'] ?? '');
            if (isset($_FILES['about_vision_icon']) && $_FILES['about_vision_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($currentLangData['vision_icon']) && str_starts_with($currentLangData['vision_icon'], 'assets/uploads/')) {
                    $this->deleteOldImageFile($currentLangData['vision_icon']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_vision_icon']['tmp_name']);
                $visionIcon = 'assets/uploads/' . $filename;
            }

            $messageIcon = $postAbout['old_message_icon'] ?? ($currentLangData['message_icon'] ?? '');
            if (isset($_FILES['about_message_icon']) && $_FILES['about_message_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($currentLangData['message_icon']) && str_starts_with($currentLangData['message_icon'], 'assets/uploads/')) {
                    $this->deleteOldImageFile($currentLangData['message_icon']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_message_icon']['tmp_name']);
                $messageIcon = 'assets/uploads/' . $filename;
            }

            $aboutAllLangs[$targetLang] = [
                'title'         => $postAbout['title'] ?? ($currentLangData['title'] ?? 'من نحن'),
                'desc'          => $postAbout['desc'] ?? ($currentLangData['desc'] ?? ''),
                'btn_text'      => $postAbout['btn_text'] ?? ($currentLangData['btn_text'] ?? 'قراءة المزيد'),
                'btn_url'       => $postAbout['btn_url'] ?? ($currentLangData['btn_url'] ?? '#'),
                'main_img'      => $mainImg,
                'sub_img'       => $subImg,
                'vision_title'  => $postAbout['vision_title'] ?? ($currentLangData['vision_title'] ?? 'رؤية الشركة'),
                'vision_desc'   => $postAbout['vision_desc'] ?? ($currentLangData['vision_desc'] ?? ''),
                'vision_icon'   => $visionIcon,
                'message_title' => $postAbout['message_title'] ?? ($currentLangData['message_title'] ?? 'رسالة الشركة'),
                'message_desc'  => $postAbout['message_desc'] ?? ($currentLangData['message_desc'] ?? ''),
                'message_icon'  => $messageIcon
            ];

            $jsonVal = json_encode($aboutAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'about_section', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 🌟 2. تحديث خدمات صفحة من نحن المستقلة (About Services) مع دعم تعدد اللغات ورفع الصور
        elseif ($action === 'update_about_services') {
            $targetLang = $_POST['targetLang'] ?? $_POST['about_services_lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'about_services'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $servicesAllLangs = [];
            if ($dbRaw && is_string($dbRaw)) {
                $decodedDb = json_decode($dbRaw, true);
                if (is_array($decodedDb)) {
                    $servicesAllLangs = $decodedDb;
                }
            }

            $currentLangData = $servicesAllLangs[$targetLang] ?? ($servicesAllLangs['ar'] ?? []);
            $postedServices = $_POST['about_services'][$targetLang] ?? $_POST['about_services'] ?? [];

            $secTitle = $postedServices['section_title'] ?? ($currentLangData['section_title'] ?? 'خدماتنا المميزة');
            $secSub   = $postedServices['section_subtitle'] ?? ($currentLangData['section_subtitle'] ?? '');
            $existingItems = $currentLangData['items'] ?? [];

            $finalItems = [];
            $rawItems = $postedServices['items'] ?? [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    // 🌟 التصحيح الجذري هنا لضمان جلب الصورة القديمة سواء جاءت عبر [img] أو [old_img]
                    $oldImg = $item['img'] ?? ($item['old_img'] ?? ($existingItems[$index]['img'] ?? ''));
                    $img = $oldImg;

                    // التحقق من رفع صورة جديدة للخدمة
                    if (isset($_FILES['about_services_img_' . $index]) && $_FILES['about_services_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['about_services_img_' . $index]['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'title' => trim($item['title'] ?? ''),
                        'url'   => trim($item['url'] ?? '#'),
                        'img'   => $img
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $servicesAllLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secSub,
                'items'            => $finalItems
            ];

            $jsonVal = json_encode($servicesAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'about_services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        elseif ($action === 'update_about_counts') {
            $targetLang = $_POST['targetLang'] ?? $_SESSION['site_lang'] ?? 'ar';

            // جلب البيانات القديمة الخزنة في قاعدة البيانات
            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'about_counts'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $countsAllLangs = [];
            if ($dbRaw && is_string($dbRaw)) {
                $decodedDb = json_decode($dbRaw, true);
                if (is_array($decodedDb)) $countsAllLangs = $decodedDb;
            }

            // استخراج العناصر القديمة للغة الحالية لضمان الاحتفاظ بالصور إن لم يتم رفع صور جديدة
            $currentLangData = $countsAllLangs[$targetLang] ?? [];
            $existingItems = $currentLangData['items'] ?? [];

            $postedItems = $_POST['about_counts'][$targetLang]['items'] ?? [];
            $finalItems = [];

            if (is_array($postedItems)) {
                foreach ($postedItems as $index => $item) {
                    $oldImg = $item['img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    // فحص رفع صورة جديدة للعداد
                    if (isset($_FILES['count_img_' . $index]) && $_FILES['count_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['count_img_' . $index]['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'number' => trim($item['number'] ?? ''),
                        'title'  => trim($item['title'] ?? ''),
                        'img'    => $img
                    ];
                }
            }

            // تحديث مصفوفة اللغة المستهدفة فقط مع الحفاظ على باقي اللغات
            $countsAllLangs[$targetLang] = [
                'items' => $finalItems
            ];

            $jsonVal = json_encode($countsAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'about_counts', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 4. تحديث الشركاء (Partners) مع دعم الهيكل متعدد اللغات
        elseif ($action === 'update_about_partners') {
            $targetLang = $_POST['targetLang'] ?? $_POST['partners_lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'partners'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $partnersAllLangs = [];
            if ($dbRaw && is_string($dbRaw)) {
                $decodedDb = json_decode($dbRaw, true);
                if (is_array($decodedDb)) {
                    $partnersAllLangs = $decodedDb;
                }
            }

            $currentLangData = $partnersAllLangs[$targetLang] ?? ($partnersAllLangs['ar'] ?? []);
            $postedPartners = $_POST['partners'][$targetLang] ?? $_POST['partners'] ?? [];

            $partnersTitle = $postedPartners['title'] ?? ($_POST['partners_title'] ?? ($currentLangData['title'] ?? 'شركاؤنا داخل وخارج ألمانيا'));
            $existingItems = $currentLangData['items'] ?? [];

            $finalItems = [];
            $rawItems = $postedPartners['items'] ?? $_POST['partner_items'] ?? [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    if (isset($_FILES['partner_img_' . $index]) && $_FILES['partner_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['partner_img_' . $index]['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = ['img' => $img];
                }
            } else {
                $finalItems = $existingItems;
            }

            $partnersAllLangs[$targetLang] = [
                'title' => $partnersTitle,
                'items' => $finalItems
            ];

            $jsonVal = json_encode($partnersAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'partners', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 5. تحديث فريق العمل (Team) مع دعم الهيكل متعدد اللغات والحماية الكاملة
        elseif ($action === 'update_about_team' || $action === 'update_team') {
            $targetLang = $_POST['targetLang'] ?? $_POST['team_lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'team'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $teamAllLangs = [];
            if ($dbRaw && is_string($dbRaw)) {
                $decodedDb = json_decode($dbRaw, true);
                if (is_array($decodedDb)) {
                    $teamAllLangs = $decodedDb;
                }
            }

            $currentLangData = $teamAllLangs[$targetLang] ?? ($teamAllLangs['ar'] ?? []);
            $postedTeam = $_POST['team'][$targetLang] ?? $_POST['team'] ?? [];

            $teamTitle = $postedTeam['title'] ?? ($_POST['team_title'] ?? ($currentLangData['title'] ?? 'فريق العمل'));
            $teamDesc  = $postedTeam['desc'] ?? ($_POST['team_desc'] ?? ($currentLangData['desc'] ?? ''));
            $existingItems = $currentLangData['items'] ?? [];

            $finalItems = [];
            $rawItems = $postedTeam['items'] ?? [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    if (isset($_FILES['team_img_' . $index]) && $_FILES['team_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && !str_contains($oldImg, 'default')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['team_img_' . $index]['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'name' => trim($item['name'] ?? ''),
                        'role' => trim($item['role'] ?? ''),
                        'img'  => $img
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $teamAllLangs[$targetLang] = [
                'title' => $teamTitle,
                'desc'  => $teamDesc,
                'items' => $finalItems
            ];

            $jsonVal = json_encode($teamAllLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'team', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
