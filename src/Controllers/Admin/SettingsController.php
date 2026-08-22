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

                $mainImg = $_POST['old_about_main_img'] ?? ($oldAboutData['main_img'] ?? '');
                if (isset($_FILES['about_main_img']) && $_FILES['about_main_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['main_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['main_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_main_img']['tmp_name']);
                    $mainImg = 'assets/uploads/' . $filename;
                }

                $subImg = $_POST['old_about_sub_img'] ?? ($oldAboutData['sub_img'] ?? '');
                if (isset($_FILES['about_sub_img']) && $_FILES['about_sub_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['sub_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['sub_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_sub_img']['tmp_name']);
                    $subImg = 'assets/uploads/' . $filename;
                }

                $visionIcon = $_POST['old_vision_icon'] ?? ($oldAboutData['vision_icon'] ?? '');
                if (isset($_FILES['about_vision_icon']) && $_FILES['about_vision_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['vision_icon'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['vision_icon']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['about_vision_icon']['tmp_name']);
                    $visionIcon = 'assets/uploads/' . $filename;
                }

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

            // 17. تحديث هيرو التعليم العالي (Edu Hero)
            elseif ($action === 'update_edu_hero') {
                $oldEduHeroData = json_decode($currentSettings['edu_hero'] ?? '', true) ?: [];

                $heroImg = $_POST['old_edu_hero_img'] ?? ($oldEduHeroData['img'] ?? 'assets/img/education/hero.jpg');
                if (isset($_FILES['edu_hero_img']) && $_FILES['edu_hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldEduHeroData['img'])) {
                        $this->deleteOldImageFile($root_path, $oldEduHeroData['img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['edu_hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $eduHeroData = [
                    'title'    => $_POST['edu_hero_title'] ?? '',
                    'desc'     => $_POST['edu_hero_desc'] ?? '',
                    'btn_text' => $_POST['edu_hero_btn_text'] ?? 'ابدأ الآن',
                    'btn_url'  => $_POST['edu_hero_btn_url'] ?? '#',
                    'img'      => $heroImg
                ];
                $jsonVal = json_encode($eduHeroData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'edu_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 18. تحديث لماذا الدراسة في ألمانيا (Edu Why)
            elseif ($action === 'update_edu_why') {
                $eduWhyTitle = $_POST['edu_why_title'] ?? '';
                $eduWhyDesc  = $_POST['edu_why_desc'] ?? '';
                $stmt->execute(['k' => 'edu_why_title', 'v' => $eduWhyTitle, 'v_update' => $eduWhyTitle]);
                $stmt->execute(['k' => 'edu_why_desc', 'v' => $eduWhyDesc, 'v_update' => $eduWhyDesc]);

                $eduWhyData = $_POST['edu_why'] ?? [];
                foreach ($eduWhyData as $index => $item) {
                    $fileToCheck = $_FILES['edu_why_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $eduWhyData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $eduWhyData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($eduWhyData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($eduWhyData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'edu_why_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 19. تحديث خطوات الرحلة - Timeline (Edu Timeline)
            elseif ($action === 'update_edu_timeline') {
                $eduTimelineTitle = $_POST['edu_timeline_title'] ?? '';
                $eduTimelineDesc  = $_POST['edu_timeline_desc'] ?? '';
                $stmt->execute(['k' => 'edu_timeline_title', 'v' => $eduTimelineTitle, 'v_update' => $eduTimelineTitle]);
                $stmt->execute(['k' => 'edu_timeline_desc', 'v' => $eduTimelineDesc, 'v_update' => $eduTimelineDesc]);

                $eduTimelineData = $_POST['edu_timeline'] ?? [];
                usort($eduTimelineData, function($a, $b) {
                    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                });

                foreach ($eduTimelineData as $index => $item) {
                    $fileToCheck = $_FILES['edu_timeline_icon_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_icon'])) {
                            $this->deleteOldImageFile($root_path, $item['old_icon']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $eduTimelineData[$index]['icon'] = 'assets/uploads/' . $filename;
                    } else {
                        $eduTimelineData[$index]['icon'] = $item['old_icon'] ?? '';
                    }
                    unset($eduTimelineData[$index]['old_icon']);
                }
                $jsonVal = json_encode(array_values($eduTimelineData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'edu_timeline_steps', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 20. تحديث خدمات التعليم (Edu Services)
            elseif ($action === 'update_edu_services') {
                $eduServicesTitle = $_POST['edu_services_title'] ?? '';
                $eduServicesDesc  = $_POST['edu_services_desc'] ?? '';
                $stmt->execute(['k' => 'edu_services_title', 'v' => $eduServicesTitle, 'v_update' => $eduServicesTitle]);
                $stmt->execute(['k' => 'edu_services_desc', 'v' => $eduServicesDesc, 'v_update' => $eduServicesDesc]);

                $eduServicesData = $_POST['edu_services'] ?? [];
                foreach ($eduServicesData as $index => $item) {
                    $fileToCheck = $_FILES['edu_service_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $eduServicesData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $eduServicesData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($eduServicesData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($eduServicesData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'edu_services_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // قسم تحديث صفحة الوصول (Arrival Page)
            // ==========================================
            elseif ($action === 'update_arrival_breadcrumb') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                $oldArrivalData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
                $oldArrivalData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';

                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_arrival_hero') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                $heroImg = $_POST['old_img'] ?? ($oldArrivalData['hero_img'] ?? '');

                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldArrivalData['hero_img'])) {
                        $this->deleteOldImageFile($root_path, $oldArrivalData['hero_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $oldArrivalData['hero_img'] = $heroImg;
                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_arrival_main') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                $oldArrivalData['main_title'] = $_POST['main_title'] ?? '';
                $oldArrivalData['main_desc'] = $_POST['main_desc'] ?? '';

                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_arrival_advice') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                $oldArrivalData['advice_title'] = $_POST['advice_title'] ?? '';
                $oldArrivalData['advice_points'] = $_POST['advice_points'] ?? [];

                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_arrival_notes') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                $oldArrivalData['note_title'] = $_POST['note_title'] ?? '';
                $oldArrivalData['notes'] = $_POST['notes'] ?? [];

                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_arrival_downloads') {
                $oldArrivalData = json_decode($currentSettings['arrival_page'] ?? '', true) ?: [];
                
                $types = $_POST['download_types'] ?? [];
                $titles = $_POST['download_titles'] ?? [];
                $subs = $_POST['download_subs'] ?? [];
                $files = $_POST['download_files'] ?? [];

                $downloadItems = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadItems[] = [
                        'type'  => $types[$i] ?? 'PDF',
                        'title' => $titles[$i] ?? '',
                        'sub'   => $subs[$i] ?? '',
                        'file'  => $files[$i] ?? '#'
                    ];
                }

                $oldArrivalData['download_items'] = $downloadItems;
                $jsonVal = json_encode($oldArrivalData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'arrival_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // قسم تحديث صفحة الفحص والمراجعة (Check Page)
            // ==========================================
            elseif ($action === 'update_check_breadcrumb') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                $oldCheckData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
                $oldCheckData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';

                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_check_hero') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                $heroImg = $_POST['old_img'] ?? ($oldCheckData['hero_img'] ?? '');

                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldCheckData['hero_img'])) {
                        $this->deleteOldImageFile($root_path, $oldCheckData['hero_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $oldCheckData['hero_img'] = $heroImg;
                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_check_main') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                $oldCheckData['main_title'] = $_POST['main_title'] ?? '';
                $oldCheckData['main_desc'] = $_POST['main_desc'] ?? '';

                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_check_advice') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                $oldCheckData['advice_title'] = $_POST['advice_title'] ?? '';
                $oldCheckData['advice_points'] = $_POST['advice_points'] ?? [];

                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_check_notes') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                $oldCheckData['note_title'] = $_POST['note_title'] ?? '';
                $oldCheckData['notes'] = $_POST['notes'] ?? [];

                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            // الإضافة الجديدة لمعالجة تحديث الروابط والشروط لصفحة الفحص
            elseif ($action === 'update_check_links') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                
                $oldCheckData['links_intro']       = $_POST['links_intro'] ?? '';
                $oldCheckData['anabin_url']        = $_POST['anabin_url'] ?? '';
                $oldCheckData['uniassist_url']     = $_POST['uniassist_url'] ?? '';
                $oldCheckData['uni_contact_intro'] = $_POST['uni_contact_intro'] ?? '';
                $oldCheckData['condition_1']       = $_POST['condition_1'] ?? '';
                $oldCheckData['condition_2']       = $_POST['condition_2'] ?? '';
                $oldCheckData['conclusion_text']   = $_POST['conclusion_text'] ?? '';

                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_check_downloads') {
                $oldCheckData = json_decode($currentSettings['check_page'] ?? '', true) ?: [];
                
                $types = $_POST['download_types'] ?? [];
                $titles = $_POST['download_titles'] ?? [];
                $subs = $_POST['download_subs'] ?? [];
                $files = $_POST['download_files'] ?? [];

                $downloadItems = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadItems[] = [
                        'type'  => $types[$i] ?? 'PDF',
                        'title' => $titles[$i] ?? '',
                        'sub'   => $subs[$i] ?? '',
                        'file'  => $files[$i] ?? '#'
                    ];
                }

                $oldCheckData['download_items'] = $downloadItems;
                $jsonVal = json_encode($oldCheckData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'check_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 21. تحديث هيرو فرص العمل والتوظيف (Job Hero)
            elseif ($action === 'update_job_hero') {
                $oldJobHeroData = json_decode($currentSettings['job_hero'] ?? '', true) ?: [];

                $heroImg = $_POST['old_img'] ?? ($oldJobHeroData['img'] ?? 'assets/img/job/hero.jpg');
                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldJobHeroData['img'])) {
                        $this->deleteOldImageFile($root_path, $oldJobHeroData['img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $jobHeroData = [
                    'title'    => $_POST['title'] ?? '',
                    'desc'     => $_POST['desc'] ?? '',
                    'btn_text' => $_POST['btn_text'] ?? 'ابدأ الآن',
                    'btn_url'  => $_POST['btn_url'] ?? '#',
                    'img'      => $heroImg
                ];
                $jsonVal = json_encode($jobHeroData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'job_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 22. تحديث قسم لماذا التدريب معنا (Job Why)
            elseif ($action === 'update_job_why') {
                $jobWhyTitle = $_POST['why_title'] ?? '';
                $jobWhyDesc  = $_POST['why_desc'] ?? '';
                $stmt->execute(['k' => 'job_why_title', 'v' => $jobWhyTitle, 'v_update' => $jobWhyTitle]);
                $stmt->execute(['k' => 'job_why_desc', 'v' => $jobWhyDesc, 'v_update' => $jobWhyDesc]);

                $jobWhyData = $_POST['items'] ?? [];
                foreach ($jobWhyData as $index => $item) {
                    $fileToCheck = $_FILES['why_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $jobWhyData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $jobWhyData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($jobWhyData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($jobWhyData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'job_why_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 23. تحديث برامج وأنواع التدريب المهني (Job Program Types)
            elseif ($action === 'update_job_program') {
                $jobProgramTitle = $_POST['program_title'] ?? '';
                $jobProgramDesc  = $_POST['program_desc'] ?? '';
                $stmt->execute(['k' => 'job_program_title', 'v' => $jobProgramTitle, 'v_update' => $jobProgramTitle]);
                $stmt->execute(['k' => 'job_program_desc', 'v' => $jobProgramDesc, 'v_update' => $jobProgramDesc]);

                $jobProgramData = $_POST['programs'] ?? [];
                foreach ($jobProgramData as $index => $item) {
                    $fileToCheck = $_FILES['prog_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $jobProgramData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $jobProgramData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    
                    $jobProgramData[$index]['is_dark'] = isset($item['is_dark']) ? 1 : 0;
                    unset($jobProgramData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($jobProgramData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'job_program_types', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 24. تحديث خطوات ومسار التدريب والتوظيف (Job Timeline)
            elseif ($action === 'update_job_timeline') {
                $jobTimelineTitle = $_POST['timeline_title'] ?? '';
                $jobTimelineDesc  = $_POST['timeline_desc'] ?? '';
                $stmt->execute(['k' => 'job_timeline_title', 'v' => $jobTimelineTitle, 'v_update' => $jobTimelineTitle]);
                $stmt->execute(['k' => 'job_timeline_desc', 'v' => $jobTimelineDesc, 'v_update' => $jobTimelineDesc]);

                $jobTimelineData = $_POST['steps'] ?? [];
                usort($jobTimelineData, function($a, $b) {
                    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                });

                foreach ($jobTimelineData as $index => $item) {
                    $fileToCheck = $_FILES['steps_icon_' . $index] ?? null;

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_icon'])) {
                            $this->deleteOldImageFile($root_path, $item['old_icon']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $jobTimelineData[$index]['icon'] = 'assets/uploads/' . $filename;
                    } else {
                        $jobTimelineData[$index]['icon'] = $item['old_icon'] ?? '';
                    }
                    unset($jobTimelineData[$index]['old_icon']);
                }
                
                $jsonVal = json_encode(array_values($jobTimelineData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'job_timeline_steps', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 25. تحديث كروت الخدمات المهتمة (Job Services)
            elseif ($action === 'update_job_services') {
                $jobServicesTitle = $_POST['services_title'] ?? '';
                $jobServicesDesc  = $_POST['services_desc'] ?? '';
                $stmt->execute(['k' => 'job_services_title', 'v' => $jobServicesTitle, 'v_update' => $jobServicesTitle]);
                $stmt->execute(['k' => 'job_services_desc', 'v' => $jobServicesDesc, 'v_update' => $jobServicesDesc]);

                $jobServicesData = $_POST['services'] ?? [];
                foreach ($jobServicesData as $index => $item) {
                    $fileToCheck = $_FILES['srv_img_' . $index] ?? null;
                    
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $jobServicesData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $jobServicesData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($jobServicesData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($jobServicesData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'job_services_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 26. تحديث صورة الهيرو لصفحة تواصل معنا (Contact Hero)
            elseif ($action === 'update_contact_hero') {
                $oldHeroImg = $_POST['old_contact_hero_img'] ?? ($currentSettings['contact_hero_img'] ?? '');
                $heroImg = $oldHeroImg;

                if (isset($_FILES['contact_hero_img']) && $_FILES['contact_hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldHeroImg)) {
                        $this->deleteOldImageFile($root_path, $oldHeroImg);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['contact_hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $stmt->execute(['k' => 'contact_hero_img', 'v' => $heroImg, 'v_update' => $heroImg]);
            }

            // 27. تحديث معلومات وأيقونات التواصل (Contact Info & Icons)
            elseif ($action === 'update_contact_info') {
                $title = $_POST['contact_info_title'] ?? 'معلومات التواصل';
                $desc  = $_POST['contact_info_desc'] ?? '';
                $addr  = $_POST['contact_address'] ?? '';
                $email = $_POST['contact_email'] ?? '';
                $phone = $_POST['contact_phone'] ?? '';

                $stmt->execute(['k' => 'contact_info_title', 'v' => $title, 'v_update' => $title]);
                $stmt->execute(['k' => 'contact_info_desc', 'v' => $desc, 'v_update' => $desc]);
                $stmt->execute(['k' => 'contact_address', 'v' => $addr, 'v_update' => $addr]);
                $stmt->execute(['k' => 'contact_email', 'v' => $email, 'v_update' => $email]);
                $stmt->execute(['k' => 'contact_phone', 'v' => $phone, 'v_update' => $phone]);

                $oldAddrIcon = $_POST['old_contact_address_icon'] ?? ($currentSettings['contact_address_icon'] ?? '');
                $addrIcon = $oldAddrIcon;
                if (isset($_FILES['contact_address_icon']) && $_FILES['contact_address_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAddrIcon)) {
                        $this->deleteOldImageFile($root_path, $oldAddrIcon);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['contact_address_icon']['tmp_name']);
                    $addrIcon = 'assets/uploads/' . $filename;
                }
                $stmt->execute(['k' => 'contact_address_icon', 'v' => $addrIcon, 'v_update' => $addrIcon]);

                $oldEmailIcon = $_POST['old_contact_email_icon'] ?? ($currentSettings['contact_email_icon'] ?? '');
                $emailIcon = $oldEmailIcon;
                if (isset($_FILES['contact_email_icon']) && $_FILES['contact_email_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldEmailIcon)) {
                        $this->deleteOldImageFile($root_path, $oldEmailIcon);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['contact_email_icon']['tmp_name']);
                    $emailIcon = 'assets/uploads/' . $filename;
                }
                $stmt->execute(['k' => 'contact_email_icon', 'v' => $emailIcon, 'v_update' => $emailIcon]);

                $oldPhoneIcon = $_POST['old_contact_phone_icon'] ?? ($currentSettings['contact_phone_icon'] ?? '');
                $phoneIcon = $oldPhoneIcon;
                if (isset($_FILES['contact_phone_icon']) && $_FILES['contact_phone_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldPhoneIcon)) {
                        $this->deleteOldImageFile($root_path, $oldPhoneIcon);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['contact_phone_icon']['tmp_name']);
                    $phoneIcon = 'assets/uploads/' . $filename;
                }
                $stmt->execute(['k' => 'contact_phone_icon', 'v' => $phoneIcon, 'v_update' => $phoneIcon]);
            }

            // 28. تحديث قسم الواتساب والتواصل المباشر (WhatsApp Section)
            elseif ($action === 'update_whatsapp_section') {
                $waText   = $_POST['whatsapp_text'] ?? '';
                $waUrl    = $_POST['whatsapp_url'] ?? '';
                $waBtnTxt = $_POST['whatsapp_btn_txt'] ?? 'تواصل عبر الواتساب';

                $stmt->execute(['k' => 'whatsapp_text', 'v' => $waText, 'v_update' => $waText]);
                $stmt->execute(['k' => 'whatsapp_url', 'v' => $waUrl, 'v_update' => $waUrl]);
                $stmt->execute(['k' => 'whatsapp_btn_txt', 'v' => $waBtnTxt, 'v_update' => $waBtnTxt]);
            }

            // ==========================================
            // 29-34. قسم تحديث صفحة خطاب الطلب (Cover Letter)
            // ==========================================
            elseif ($action === 'update_cover_breadcrumb') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                $oldCoverData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
                $oldCoverData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';

                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cover_hero') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                $heroImg = $_POST['old_img'] ?? ($oldCoverData['hero_img'] ?? '');

                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldCoverData['hero_img'])) {
                        $this->deleteOldImageFile($root_path, $oldCoverData['hero_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $oldCoverData['hero_img'] = $heroImg;
                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cover_main') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                $oldCoverData['main_title'] = $_POST['main_title'] ?? '';
                $oldCoverData['main_desc'] = $_POST['main_desc'] ?? '';

                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cover_advice') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                $oldCoverData['advice_title'] = $_POST['advice_title'] ?? '';
                $oldCoverData['advice_points'] = $_POST['advice_points'] ?? [];

                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cover_notes') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                $oldCoverData['note_title'] = $_POST['note_title'] ?? '';
                $oldCoverData['notes'] = $_POST['notes'] ?? [];

                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cover_downloads') {
                $oldCoverData = json_decode($currentSettings['coverletter_page'] ?? '', true) ?: [];
                
                $types = $_POST['download_types'] ?? [];
                $titles = $_POST['download_titles'] ?? [];
                $subs = $_POST['download_subs'] ?? [];
                $files = $_POST['download_files'] ?? [];

                $downloadItems = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadItems[] = [
                        'type'  => $types[$i] ?? 'PDF',
                        'title' => $titles[$i] ?? '',
                        'sub'   => $subs[$i] ?? '',
                        'file'  => $files[$i] ?? '#'
                    ];
                }

                $oldCoverData['download_items'] = $downloadItems;
                $jsonVal = json_encode($oldCoverData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'coverletter_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // 35-40. قسم تحديث صفحة السيرة الذاتية (CV Page)
            // ==========================================
            elseif ($action === 'update_cv_breadcrumb') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                $oldCvData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
                $oldCvData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';

                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cv_hero') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                $heroImg = $_POST['old_img'] ?? ($oldCvData['hero_img'] ?? '');

                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldCvData['hero_img'])) {
                        $this->deleteOldImageFile($root_path, $oldCvData['hero_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $oldCvData['hero_img'] = $heroImg;
                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cv_main') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                $oldCvData['main_title'] = $_POST['main_title'] ?? '';
                $oldCvData['main_desc'] = $_POST['main_desc'] ?? '';

                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cv_advice') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                $oldCvData['advice_title'] = $_POST['advice_title'] ?? '';
                $oldCvData['advice_points'] = $_POST['advice_points'] ?? [];

                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cv_notes') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                $oldCvData['note_title'] = $_POST['note_title'] ?? '';
                $oldCvData['notes'] = $_POST['notes'] ?? [];

                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_cv_downloads') {
                $oldCvData = json_decode($currentSettings['cv_page'] ?? '', true) ?: [];
                
                $types = $_POST['download_types'] ?? [];
                $titles = $_POST['download_titles'] ?? [];
                $subs = $_POST['download_subs'] ?? [];
                $files = $_POST['download_files'] ?? [];

                $downloadItems = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadItems[] = [
                        'type'  => $types[$i] ?? 'PDF',
                        'title' => $titles[$i] ?? '',
                        'sub'   => $subs[$i] ?? '',
                        'file'  => $files[$i] ?? '#'
                    ];
                }

                $oldCvData['download_items'] = $downloadItems;
                $jsonVal = json_encode($oldCvData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'cv_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // 41-46. قسم تحديث صفحة خطاب الدافع (Motivation Page)
            // ==========================================
            elseif ($action === 'update_motivation_breadcrumb') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                $oldMotivData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
                $oldMotivData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';

                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_motivation_hero') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                $heroImg = $_POST['old_img'] ?? ($oldMotivData['hero_img'] ?? '');

                if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldMotivData['hero_img'])) {
                        $this->deleteOldImageFile($root_path, $oldMotivData['hero_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                    $heroImg = 'assets/uploads/' . $filename;
                }

                $oldMotivData['hero_img'] = $heroImg;
                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_motivation_main') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                $oldMotivData['main_title'] = $_POST['main_title'] ?? '';
                $oldMotivData['main_desc'] = $_POST['main_desc'] ?? '';

                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_motivation_advice') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                $adviceTitle = $_POST['advice_title'] ?? '';
                $adviceItems = $_POST['advice_items'] ?? [];

                $oldMotivData['advice_section'] = [
                    'title' => $adviceTitle,
                    'items' => is_array($adviceItems) ? $adviceItems : []
                ];

                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_motivation_notes') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                $oldMotivData['note_title'] = $_POST['note_title'] ?? '';
                $oldMotivData['notes'] = $_POST['notes'] ?? [];

                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
            elseif ($action === 'update_motivation_downloads') {
                $oldMotivData = json_decode($currentSettings['motivation_page'] ?? '', true) ?: [];
                
                $types = $_POST['download_types'] ?? [];
                $titles = $_POST['download_titles'] ?? [];
                $subs = $_POST['download_subs'] ?? [];
                $files = $_POST['download_files'] ?? [];

                $downloadItems = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadItems[] = [
                        'type'  => $types[$i] ?? 'PDF',
                        'title' => $titles[$i] ?? '',
                        'sub'   => $subs[$i] ?? '',
                        'file'  => $files[$i] ?? '#'
                    ];
                }

                $oldMotivData['download_items'] = $downloadItems;
                $jsonVal = json_encode($oldMotivData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'motivation_page', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
