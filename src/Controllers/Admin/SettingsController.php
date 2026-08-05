<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
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

        try {
            $pdo = \App\Config\Database::getConnection();
        } catch (\Exception $e) {
            error_log("Database connection failed in index: " . $e->getMessage());
            die("Database connection failed.");
        }

        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $settings = [
            'site_title' => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email' => $rawSettings['site_email'] ?? '',
            'site_logo'  => $rawSettings['site_logo'] ?? '',
        ];

        // تجهيز مصفوفة البيانات ($data) لعرض الإعدادات الحالية بما فيها قسم فريق العمل داخل المودلز
        $data = [
            'team_title'   => $rawSettings['team_title'] ?? 'فريق العمل',
            'team_desc'    => $rawSettings['team_desc'] ?? '',
            'team_members' => json_decode($rawSettings['team_items'] ?? '[]', true)
        ];

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
     * حفظ وتحديث الإعدادات مع تحويل الصور تلقائياً إلى WebP
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

        // التحقق من حماية الـ CSRF للطلبات
        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? $_POST['csrf_token'] ?? '';

        if (!Security::verifyCsrfToken($token)) {
            // إرجاع خطأ 403 إذا فشل التحقق
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

        try {
            $action = $_POST['action'] ?? '';

            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

            // جلب الإعدادات الحالية للتمكن من حذف الصور القديمة عند التحديث
            $currentSettingsStmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $currentSettings = $currentSettingsStmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            // مسار المجلد المطلوب بدقة داخل public/assets/uploads/
            $uploadDir = $root_path . '/public/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // 1. تحديث الشعار
            if ($action === 'update_logo') {
                if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                    $oldLogo = $currentSettings['site_logo_path'] ?? '';
                    $this->deleteOldImageFile($root_path, $oldLogo);

                    $filename = 'logo_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['logo_img']['tmp_name'], $uploadDir . $filename);
                    $logoPath = 'assets/uploads/' . $filename;
                    
                    $stmt->execute(['k' => 'site_logo_path', 'v' => $logoPath, 'v_update' => $logoPath]);
                }
            }

            // 2. تحديث منصات التواصل الاجتماعي
            elseif ($action === 'update_social') {
                $socialData = $_POST['social'] ?? [];
                foreach ($socialData as $index => $item) {
                    if (isset($_FILES['social_img_' . $index]) && $_FILES['social_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $filename = 'social_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['social_img_' . $index]['tmp_name'], $uploadDir . $filename);
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

                    $filename = 'ad_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['ad_image']['tmp_name'], $uploadDir . $filename);
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
                    if (isset($_FILES['col3_img_' . $index]) && $_FILES['col3_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $filename = 'footer_col3_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['col3_img_' . $index]['tmp_name'], $uploadDir . $filename);
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

                    $filename = 'hero_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['hero_img']['tmp_name'], $uploadDir . $filename);
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
                        $filename = 'service_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['service_img_' . $index]['tmp_name'], $uploadDir . $filename);
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
                        $filename = 'choose_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['choose_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $chooseData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $chooseData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($chooseData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($chooseData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'choose_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // 10. تحديث التقييمات (Reviews)
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
                        $filename = 'guide_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['guide_img_' . $index]['tmp_name'], $uploadDir . $filename);
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

            // ==========================================
            // 13. تحديث قسم من نحن الرئيسي (About Section)
            // ==========================================
            elseif ($action === 'update_about_section') {
                $oldAboutData = json_decode($currentSettings['about_section'] ?? '{}', true);

                $mainImg = $_POST['old_about_main_img'] ?? ($oldAboutData['main_img'] ?? '');
                if (isset($_FILES['about_main_img']) && $_FILES['about_main_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['main_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['main_img']);
                    }
                    $filename = 'about_main_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['about_main_img']['tmp_name'], $uploadDir . $filename);
                    $mainImg = 'assets/uploads/' . $filename;
                }

                $subImg = $_POST['old_about_sub_img'] ?? ($oldAboutData['sub_img'] ?? '');
                if (isset($_FILES['about_sub_img']) && $_FILES['about_sub_img']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['sub_img'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['sub_img']);
                    }
                    $filename = 'about_sub_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['about_sub_img']['tmp_name'], $uploadDir . $filename);
                    $subImg = 'assets/uploads/' . $filename;
                }

                $visionIcon = $_POST['old_vision_icon'] ?? ($oldAboutData['vision_icon'] ?? '');
                if (isset($_FILES['about_vision_icon']) && $_FILES['about_vision_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['vision_icon'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['vision_icon']);
                    }
                    $filename = 'vision_icon_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['about_vision_icon']['tmp_name'], $uploadDir . $filename);
                    $visionIcon = 'assets/uploads/' . $filename;
                }

                $messageIcon = $_POST['old_message_icon'] ?? ($oldAboutData['message_icon'] ?? '');
                if (isset($_FILES['about_message_icon']) && $_FILES['about_message_icon']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldAboutData['message_icon'])) {
                        $this->deleteOldImageFile($root_path, $oldAboutData['message_icon']);
                    }
                    $filename = 'message_icon_' . time() . '.webp';
                    $this->convertToWebpAndSave($_FILES['about_message_icon']['tmp_name'], $uploadDir . $filename);
                    $messageIcon = 'assets/uploads/' . $filename;
                }

                $aboutData = [
                    'title'        => $_POST['about_title'] ?? '',
                    'desc'         => $_POST['about_desc'] ?? '',
                    'btn_text'     => $_POST['about_btn_text'] ?? '',
                    'btn_url'      => $_POST['about_btn_url'] ?? '',
                    'vision_title' => $_POST['vision_title'] ?? '',
                    'vision_desc'  => $_POST['vision_desc'] ?? '',
                    'message_title'=> $_POST['message_title'] ?? '',
                    'message_desc' => $_POST['message_desc'] ?? '',
                    'main_img'     => $mainImg,
                    'sub_img'      => $subImg,
                    'vision_icon'  => $visionIcon,
                    'message_icon' => $messageIcon
                ];

                $jsonVal = json_encode($aboutData, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'about_section', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // 14. تحديث فريق العمل (Team) - [محدث ومصحح بدقة]
            // ==========================================
            elseif ($action === 'update_about_team') {
                $teamTitle = $_POST['team_title'] ?? '';
                $teamDesc  = $_POST['team_desc'] ?? '';
                
                // حفظ عنوان وصف القسم الرئيسي لفريق العمل
                $stmt->execute(['k' => 'team_title', 'v' => $teamTitle, 'v_update' => $teamTitle]);
                $stmt->execute(['k' => 'team_desc', 'v' => $teamDesc, 'v_update' => $teamDesc]);

                $teamData = $_POST['team'] ?? [];
                
                foreach ($teamData as $index => $item) {
                    // معالجة رفع الصورة لكل عضو بناءً على التسمية team_img_<?php echo $index; 
                    $fileKey = 'team_img_' . $index;
                    
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                        // حذف الصورة القديمة إن وجدت
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = 'team_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES[$fileKey]['tmp_name'], $uploadDir . $filename);
                        $teamData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        // الاحتفاظ بالصورة القديمة إذا لم يتم رفع صورة جديدة
                        $teamData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    
                    // تنظيف حقل old_img المؤقت قبل التخزين في قاعدة البيانات
                    unset($teamData[$index]['old_img']);
                    
                    // التأكد من الحقول النصية (name, role)
                    $teamData[$index]['name'] = $item['name'] ?? '';
                    $teamData[$index]['role'] = $item['role'] ?? '';
                }

                $jsonVal = json_encode(array_values($teamData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'team_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // 15. تحديث الإحصائيات (Counts)
            // ==========================================
            elseif ($action === 'update_about_counts') {
                $countsData = $_POST['counts'] ?? [];
                foreach ($countsData as $index => $item) {
                    if (isset($_FILES['count_img_' . $index]) && $_FILES['count_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = 'count_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['count_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $countsData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $countsData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($countsData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($countsData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'about_counts', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }

            // ==========================================
            // 16. تحديث الشركاء (Partners)
            // ==========================================
            elseif ($action === 'update_about_partners') {
                $partnersTitle = $_POST['partners_title'] ?? '';
                $stmt->execute(['k' => 'partners_title', 'v' => $partnersTitle, 'v_update' => $partnersTitle]);

                $partnersData = $_POST['partners'] ?? [];
                foreach ($partnersData as $index => $item) {
                    if (isset($_FILES['partner_img_' . $index]) && $_FILES['partner_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (!empty($item['old_img'])) {
                            $this->deleteOldImageFile($root_path, $item['old_img']);
                        }
                        $filename = 'partner_' . $index . '_' . time() . '.webp';
                        $this->convertToWebpAndSave($_FILES['partner_img_' . $index]['tmp_name'], $uploadDir . $filename);
                        $partnersData[$index]['img'] = 'assets/uploads/' . $filename;
                    } else {
                        $partnersData[$index]['img'] = $item['old_img'] ?? '';
                    }
                    unset($partnersData[$index]['old_img']);
                }
                $jsonVal = json_encode(array_values($partnersData), JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'partners_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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

    /**
     * دالة مساعدة لتحويل أي صورة مرفوعة إلى صيغة WebP وحفظها في المسار المحدد
     */
    private function convertToWebpAndSave(string $tmpPath, string $destination): void
    {
        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            throw new Exception('الملف المرفوع ليس صورة صالحة.');
        }

        $mimeType = $imageInfo['mime'];
        $image = null;

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($tmpPath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($tmpPath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($tmpPath);
                break;
            default:
                throw new Exception('صيغة الصورة غير مدعومة. يرجى رفع JPG, PNG أو WebP.');
        }

        if (!$image) {
            throw new Exception('فشل في معالجة وفك تشفير الصورة.');
        }

        $success = @imagewebp($image, $destination, 85);
        @imagedestroy($image);

        if (!$success) {
            throw new Exception('فشل في حفظ الصورة بصيغة WebP الجديدة.');
        }
    }

    /**
     * دالة مساعدة لحذف الصور القديمة من السيرفر عند رفع صور بديلة
     */
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
