<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
use App\Services\ImageUploader;
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
        
        // تهيئة خدمة رفع المعالجة الموحدة
        $imageUploader = new ImageUploader($uploadDir);

        try {
            $action = $_POST['action'] ?? '';

            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

            $currentSettingsStmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $currentSettings = $currentSettingsStmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            // 1. تحديث الشعار
            if ($action === 'update_logo') {
                if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                    $oldLogo = $currentSettings['site_logo_path'] ?? '';
                    $this->deleteOldImageFile($root_path, $oldLogo);

                    $filename = $imageUploader->processAndUploadFile($_FILES['logo_img']['tmp_name']);
                    $logoPath = 'assets/uploads/' . $filename;
                    
                    $stmt->execute(['k' => 'site_logo_path', 'v' => $logoPath, 'v_update' => $logoPath]);
                }
            }

            // 2. تحديث منصات التواصل الاجتماعي
            elseif ($action === 'update_social') {
                $socialData = $_POST['social'] ?? [];
                foreach ($socialData as $index => $item) {
                    $fileToCheck = $_FILES['social_img_' . $index] ?? ($_FILES['social'][$index]['img'] ?? null);
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
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
                    $oldAdData = json_decode($currentSettings['announcement'] ?? '', true);
                    if (!empty($oldAdData['image_path'])) {
                        $this->deleteOldImageFile($root_path, $oldAdData['image_path']);
                    }

                    $filename = $imageUploader->processAndUploadFile($_FILES['ad_image']['tmp_name']);
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

            // 7. تحديث قسم الهيرو (Hero)
            elseif ($action === 'update_hero') {
                $heroImg = $_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg';
                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    $oldHeroData = json_decode($currentSettings['hero'] ?? '', true);
                    if (!empty($oldHeroData['img'])) {
                        $this->deleteOldImageFile($root_path, $oldHeroData['img']);
                    }

                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
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
                    $fileToCheck = $_FILES['service_img_' . $index] ?? ($_FILES['services'][$index]['img'] ?? null);
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $servicesData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $servicesData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($servicesData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($servicesData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 9. تحديث فريق العمل العام أو الخاص بصفحة من نحن (Team)
            elseif ($action === 'update_about_team' || $action === 'update_team') {
                $teamTitle = $_POST['team_title'] ?? 'فريق العمل';
                $teamDesc  = $_POST['team_desc'] ?? '';
                
                $stmt->execute(['k' => 'team_title', 'v' => $teamTitle, 'v_update' => $teamTitle]);
                $stmt->execute(['k' => 'team_desc', 'v' => $teamDesc, 'v_update' => $teamDesc]);

                $teamData = $_POST['team'] ?? [];
                foreach ($teamData as $index => $item) {
                    $fileToCheck = $_FILES['team_img_' . $index] ?? ($_FILES['team'][$index]['img'] ?? null);
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $teamData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $teamData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($teamData[$index]['old_img']);
                }
                
                $jsonVal = json_encode(array_values($teamData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'team_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 14. تحديث قسم من نحن الرئيسي (About Section)
            elseif ($action === 'update_about_section') {
                $oldAboutData = json_decode($currentSettings['about_section'] ?? '', true) ?: [];

                // معالجة الصور (Main Image)
                $mainImg = $_POST['old_about_main_img'] ?? ($oldAboutData['main_img'] ?? '');
                if (isset($_FILES['about_main_img']) && $_FILES['about_main_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['main_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['main_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_main_img']['tmp_name']);
                    $mainImg = 'assets/uploads/' . $filename;
                }

                // معالجة الصور (Sub Image)
                $subImg = $_POST['old_about_sub_img'] ?? ($oldAboutData['sub_img'] ?? '');
                if (isset($_FILES['about_sub_img']) && $_FILES['about_sub_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['sub_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['sub_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_sub_img']['tmp_name']);
                    $subImg = 'assets/uploads/' . $filename;
                }

                // معالجة أيقونة الرؤية (Vision Icon)
                $visionIcon = $_POST['old_vision_icon'] ?? ($oldAboutData['vision_icon'] ?? '');
                if (isset($_FILES['about_vision_icon']) && $_FILES['about_vision_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['vision_icon'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['vision_icon']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_vision_icon']['tmp_name']);
                    $visionIcon = 'assets/uploads/' . $filename;
                }

                // معالجة أيقونة الرسالة (Message Icon)
                $messageIcon = $_POST['old_message_icon'] ?? ($oldAboutData['message_icon'] ?? '');
                if (isset($_FILES['about_message_icon']) && $_FILES['about_message_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['message_icon'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['message_icon']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_message_icon']['tmp_name']);
                    $messageIcon = 'assets/uploads/' . $filename;
                }

                $aboutData = [
                    'title'         => $_POST['about_title'] ?? 'من نحن',
                    'desc'          => $_POST['about_desc'] ?? '',
                    'btn_text'      => $_POST['about_btn_text'] ?? 'قراءة المزيد',
                    'btn_url'       => $_POST['about_btn_url'] ?? '#',
                    'main_img'      => $mainImg,
                    'sub_img'       => $subImg,
                    'vision_title'  => $_POST['vision_title'] ?? 'رؤية الشركة',
                    'vision_desc'   => $_POST['vision_desc'] ?? '',
                    'vision_icon'   => $visionIcon,
                    'message_title' => $_POST['message_title'] ?? 'رسالة الشركة',
                    'message_desc'  => $_POST['message_desc'] ?? '',
                    'message_icon'  => $messageIcon
                ];

                $jsonVal = json_encode($aboutData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'about_section', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 15. تحديث العدادات والإحصائيات في صفحة من نحن (About Counts)
            elseif ($action === 'update_about_counts') {
                $countsData = $_POST['counts'] ?? [];
                foreach ($countsData as $index => $item) {
                    $fileToCheck = $_FILES['count_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $countsData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $countsData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($countsData[$index]['old_img']);
                }
                
                $jsonVal = json_encode(array_values($countsData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'about_counts', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 16. تحديث الشركاء في صفحة من نحن (About Partners)
            elseif ($action === 'update_about_partners') {
                $partnersTitle = $_POST['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا';
                $stmt->execute(['k' => 'partners_title', 'v' => $partnersTitle, 'v_update' => $partnersTitle]);

                $partnersData = $_POST['partners'] ?? [];
                foreach ($partnersData as $index => $item) {
                    $fileToCheck = $_FILES['partner_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $partnersData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $partnersData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($partnersData[$index]['old_img']);
                }
                
                $jsonVal = json_encode(array_values($partnersData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'partners_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 10. تحديث الأسئلة الشائعة (FAQ)
            elseif ($action === 'update_faq') {
                $faqTitle = $_POST['faq_title'] ?? 'الأسئلة الشائعة';
                $stmt->execute(['k' => 'faq_title', 'v' => $faqTitle, 'v_update' => $faqTitle]);

                $faqData = $_POST['faq'] ?? [];
                $jsonVal = json_encode(array_values($faqData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'faq_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 11. تحديث التقييمات (Reviews)
            elseif ($action === 'update_reviews') {
                $reviewsTitle = $_POST['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
                $stmt->execute(['k' => 'reviews_title', 'v' => $reviewsTitle, 'v_update' => $reviewsTitle]);

                $reviewsData = $_POST['reviews'] ?? [];
                $jsonVal = json_encode(array_values($reviewsData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 12. تحديث المميزات (Choose)
            elseif ($action === 'update_choose') {
                $chooseTitle = $_POST['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي';
                $chooseDesc  = $_POST['choose_desc'] ?? '';
                $stmt->execute(['k' => 'choose_title', 'v' => $chooseTitle, 'v_update' => $chooseTitle]);
                $stmt->execute(['k' => 'choose_section_desc', 'v' => $chooseDesc, 'v_update' => $chooseDesc]);

                $chooseData = $_POST['choose'] ?? [];
                foreach ($chooseData as $index => $item) {
                    $fileToCheck = $_FILES['choose_img_' . $index] ?? ($_FILES['choose'][$index]['img'] ?? null);
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $chooseData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $chooseData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($chooseData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($chooseData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'choose_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 13. تحديث الدليل الشامل (Guide)
            elseif ($action === 'update_guide') {
                $guideTitle = $_POST['guide_title'] ?? 'دليل بيتهوفن الشامل';
                $guideDesc  = $_POST['guide_desc'] ?? '';
                $stmt->execute(['k' => 'guide_title', 'v' => $guideTitle, 'v_update' => $guideTitle]);
                $stmt->execute(['k' => 'guide_desc', 'v' => $guideDesc, 'v_update' => $guideDesc]);

                $guideData = $_POST['guide'] ?? [];
                foreach ($guideData as $index => $item) {
                    $fileToCheck = $_FILES['guide_img_' . $index] ?? ($_FILES['guide'][$index]['img'] ?? null);
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $guideData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $guideData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($guideData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($guideData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'guide_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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

        @session_start();
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
