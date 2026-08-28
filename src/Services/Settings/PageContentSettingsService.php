<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class PageContentSettingsService
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
        $dbKey = $this->resolveDbKey($action);
        if (!$dbKey) {
            return false;
        }

        $pageData = json_decode($currentSettings[$dbKey] ?? '', true) ?: [];
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث مسار التنقل (Breadcrumb)
        if (str_contains($action, '_breadcrumb')) {
            $pageData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
            $pageData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';
        } 
        // 2. تحديث صورة الهيرو (Hero)
        elseif (str_contains($action, '_hero')) {
            $heroImg = $_POST['old_img'] ?? ($pageData['hero_img'] ?? '');
            if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($pageData['hero_img'])) {
                    $this->deleteOldImageFile($pageData['hero_img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }
            $pageData['hero_img'] = $heroImg;
            $pageData['hero_position'] = $_POST['hero_position'] ?? 'center center';
        } 
        // 3. تحديث المحتوى الرئيسي (Main)
        elseif (str_contains($action, '_main')) {
            $pageData['main_title'] = $_POST['main_title'] ?? '';
            $pageData['main_desc'] = $_POST['main_desc'] ?? '';
            
            // دعم إضافي لقسم الأهمية إذا وجد ضمن النموذج الرئيسي (خاص بصفحة الضمانات المالية مثلاً)
            if (isset($_POST['importance_title'])) {
                $pageData['importance_title'] = $_POST['importance_title'];
            }
            if (isset($_POST['importance_desc'])) {
                $pageData['importance_desc'] = $_POST['importance_desc'];
            }
        } 
        // 4. تحديث النصائح والإرشادات (Advice / Tips) أو نصائح تكلفة المعيشة أو أهداف الدورة التأسيسية
        elseif (str_contains($action, '_advice') || str_contains($action, '_tips') || str_contains($action, '_goals')) {
            $adviceTitle = $_POST['advice_title'] ?? ($_POST['tips_title'] ?? ($_POST['goals_title'] ?? ''));
            
            if ($dbKey === 'motivation_page') {
                $adviceItems = $_POST['advice_items'] ?? [];
                $pageData['advice_section'] = [
                    'title' => $adviceTitle,
                    'items' => is_array($adviceItems) ? $adviceItems : []
                ];
            } elseif ($dbKey === 'germanlang_page') {
                $pageData['tips_section'] = [
                    'title' => $_POST['tips_title'] ?? 'نصائح للنجاح في الدراسة بالألمانية',
                    'tips_list' => $_POST['tips_list'] ?? []
                ];
            } elseif ($dbKey === 'living_cost_page') {
                $pageData['tips_section'] = [
                    'title' => $_POST['tips_title'] ?? 'نصائح لتقليل النفقات',
                    'items' => is_array($_POST['tips_items'] ?? null) ? $_POST['tips_items'] : []
                ];
            } elseif ($dbKey === 'foundation_page' && str_contains($action, '_goals')) {
                $pageData['goals_title'] = $adviceTitle;
                $goalsItemsRaw = $_POST['goals_items'] ?? [];
                $pageData['goals_items'] = is_array($goalsItemsRaw) ? array_values(array_filter(array_map('trim', $goalsItemsRaw), fn($val) => $val !== '')) : [];
            } else {
                $pageData['advice_title'] = $adviceTitle;
                $pageData['advice_points'] = $_POST['advice_points'] ?? [];
            }
        }
        // 5. تحديث مستويات اللغة الألمانية (Levels) أو المحتوى الدراسي والتفصيلي في التأسيسية (Learning)
        elseif (str_contains($action, '_levels') || str_contains($action, '_learning')) {
            if ($dbKey === 'foundation_page' && str_contains($action, '_learning')) {
                $pageData['learning_title'] = $_POST['learning_title'] ?? '';
                $pageData['learning_intro'] = $_POST['learning_intro'] ?? '';
                $pageData['learning_p1'] = $_POST['learning_p1'] ?? '';
                $pageData['learning_p2'] = $_POST['learning_p2'] ?? '';
            } else {
                $pageData['levels_section'] = [
                    'title' => $_POST['levels_title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)',
                    'levels_list' => $_POST['levels_list'] ?? []
                ];
            }
        }
        // 6. تحديث مميزات دورات اللغة الألمانية (Features) أو أنواع دورات السنة التحضيرية (Courses)
        elseif (str_contains($action, '_features') || str_contains($action, '_courses')) {
            if ($dbKey === 'foundation_page' && str_contains($action, '_courses')) {
                $pageData['courses_title'] = $_POST['courses_title'] ?? 'أنواع دورات السنة التحضيرية';
                $coursesItemsRaw = $_POST['courses_items'] ?? [];
                $pageData['courses_items'] = is_array($coursesItemsRaw) ? array_values(array_filter(array_map('trim', $coursesItemsRaw), fn($val) => $val !== '')) : [];
            } else {
                $pageData['features_section'] = [
                    'title' => $_POST['features_title'] ?? 'مميزات دوراتنا',
                    'features_list' => $_POST['features_list'] ?? []
                ];
            }
        }
        // 7. تحديث الملاحظات (Notes / Note) أو خيارات الضمان المالي / الحساب المغلق أو ارتباط الجامعات والأنواع في التأسيسية
        elseif (str_contains($action, '_notes') || str_contains($action, '_note') || str_contains($action, '_options') || str_contains($action, '_account') || str_contains($action, '_unitype') || str_contains($action, '_types')) {
            if (str_contains($action, '_options')) {
                $pageData['options_title'] = $_POST['options_title'] ?? '';
                $pageData['options_items'] = $_POST['options_items'] ?? [];
            } elseif (str_contains($action, '_account')) {
                $pageData['account_title'] = $_POST['account_title'] ?? '';
                $pageData['account_points'] = $_POST['account_points'] ?? [];
            } elseif ($dbKey === 'living_cost_page' && str_contains($action, '_notes')) {
                $pageData['notes_section'] = [
                    'title' => $_POST['notes_title'] ?? 'ملاحظات هامة !!',
                    'items' => is_array($_POST['notes_items'] ?? null) ? $_POST['notes_items'] : []
                ];
            } elseif ($dbKey === 'foundation_page' && str_contains($action, '_unitype')) {
                $pageData['uni_type_title'] = $_POST['uni_type_title'] ?? '';
                $pageData['uni_type_intro'] = $_POST['uni_type_intro'] ?? '';
                $pageData['uni_public'] = $_POST['uni_public'] ?? '';
                $pageData['uni_applied'] = $_POST['uni_applied'] ?? '';
            } elseif ($dbKey === 'foundation_page' && str_contains($action, '_types')) {
                $pageData['types_title'] = $_POST['types_title'] ?? 'أنواع السنة التحضيرية في ألمانيا';
                $pageData['type_public_desc'] = $_POST['type_public_desc'] ?? '';
                $pageData['type_private_desc'] = $_POST['type_private_desc'] ?? '';
            } elseif ($dbKey === 'foundation_page' && str_contains($action, '_notes')) {
                $pageData['notes_title'] = $_POST['notes_title'] ?? 'ملاحظات هامة !!';
                $notesItemsRaw = $_POST['notes_items'] ?? [];
                $pageData['notes_items'] = is_array($notesItemsRaw) ? array_values(array_filter(array_map('trim', $notesItemsRaw), fn($val) => $val !== '')) : [];
            } else {
                $pageData['note_title'] = $_POST['note_title'] ?? '';
                if (isset($_POST['notes'])) {
                    $pageData['notes'] = $_POST['notes'];
                }
                if (isset($_POST['note_highlight'])) {
                    $pageData['note_highlight'] = $_POST['note_highlight'];
                }
                if (isset($_POST['note_text'])) {
                    $pageData['note_text'] = $_POST['note_text'];
                }
                if (isset($_POST['note_texts'])) {
                    $cleanNotes = array_map('trim', $_POST['note_texts']);
                    $pageData['notes_list'] = array_values(array_filter($cleanNotes));
                } elseif (isset($_POST['notes_list'])) {
                    $cleanNotes = array_map('trim', $_POST['notes_list']);
                    $pageData['notes_list'] = array_values(array_filter($cleanNotes));
                }
            }
        } 
        // 8. تحديث الروابط الخاصة بصفحة الفحص (Check Links) أو قسم الروابط والشركات أو اختبار القبول والـ FSP في التأسيسية
        elseif (str_contains($action, '_links') || str_contains($action, '_examfsp')) {
            if ($dbKey === 'foundation_page' && str_contains($action, '_examfsp')) {
                $pageData['exam_title'] = $_POST['exam_title'] ?? '';
                $pageData['exam_desc'] = $_POST['exam_desc'] ?? '';
                $pageData['fsp_title'] = $_POST['fsp_title'] ?? '';
                $pageData['fsp_desc'] = $_POST['fsp_desc'] ?? '';
            } elseif ($dbKey === 'health_page') {
                $pageData['expert_note'] = $_POST['expert_note'] ?? '';
                
                $linkTitles = $_POST['link_titles'] ?? [];
                $linkUrls = $_POST['link_urls'] ?? [];
                $linkActives = $_POST['link_actives'] ?? [];

                $insuranceLinks = [];
                for ($i = 0; $i < count($linkTitles); $i++) {
                    $insuranceLinks[] = [
                        'title'  => $linkTitles[$i] ?? '',
                        'url'    => $linkUrls[$i] ?? '#',
                        'active' => in_array((string)$i, array_map('strval', $linkActives), true)
                    ];
                }
                $pageData['insurance_links'] = $insuranceLinks;
            } elseif ($dbKey === 'financial_page') {
                $linkTexts = $_POST['link_texts'] ?? [];
                $linkUrls = $_POST['link_urls'] ?? [];
                $linkActives = $_POST['link_actives'] ?? [];

                $serviceLinks = [];
                for ($i = 0; $i < count($linkTexts); $i++) {
                    $isActive = isset($linkActives[$i]) && (string)$linkActives[$i] === '1';

                    $serviceLinks[] = [
                        'text'   => $linkTexts[$i] ?? '',
                        'url'    => $linkUrls[$i] ?? '#',
                        'active' => $isActive
                    ];
                }
                $pageData['service_links'] = $serviceLinks;
            } else {
                $pageData['links_intro']       = $_POST['links_intro'] ?? '';
                $pageData['anabin_url']        = $_POST['anabin_url'] ?? '';
                $pageData['uniassist_url']     = $_POST['uniassist_url'] ?? '';
                $pageData['uni_contact_intro'] = $_POST['uni_contact_intro'] ?? '';
                $pageData['condition_1']       = $_POST['condition_1'] ?? '';
                $pageData['condition_2']       = $_POST['condition_2'] ?? '';
                $pageData['conclusion_text']   = $_POST['conclusion_text'] ?? '';
            }
        }
        // 9. تحديث الملفات والتحميلات (Downloads & Cards) أو قسم الأهمية والوثائق في الصحة
        elseif (str_contains($action, '_downloads') || str_contains($action, '_cards') || str_contains($action, '_importance') || str_contains($action, '_documents')) {
            if ($dbKey === 'offers_page') {
                $titles  = $_POST['card_titles'] ?? [];
                $subs    = $_POST['card_subs'] ?? [];
                $files   = $_POST['card_files'] ?? [];
                $types   = $_POST['card_types'] ?? [];
                $actives = $_POST['card_actives'] ?? [];

                $downloadCards = [];
                for ($i = 0; $i < count($titles); $i++) {
                    $downloadCards[] = [
                        'title'  => $titles[$i] ?? '',
                        'file'   => $files[$i] ?? '#',
                        'sub'    => $subs[$i] ?? '',
                        'type'   => $types[$i] ?? 'PDF',
                        'active' => isset($actives[$i]) && $actives[$i] == '1'
                    ];
                }
                $pageData['download_cards'] = $downloadCards;
            } elseif ($dbKey === 'health_page') {
                if (str_contains($action, '_importance')) {
                    $pageData['importance_section'] = [
                        'title' => $_POST['importance_title'] ?? 'لماذا التأمين الصحي مهم؟',
                        'items' => is_array($_POST['importance_items'] ?? null) ? $_POST['importance_items'] : []
                    ];
                } elseif (str_contains($action, '_documents')) {
                    $pageData['documents_section'] = [
                        'title' => $_POST['documents_title'] ?? 'الوثائق المكملة',
                        'items' => is_array($_POST['documents_items'] ?? null) ? $_POST['documents_items'] : []
                    ];
                }
            } else {
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
                $pageData['download_items'] = $downloadItems;
            }
        }
        // 10. تحديث شروط الاستفادة (Who) - خاصة بصفحة البرامج الإنجليزية
        elseif (str_contains($action, '_who')) {
            $pageData['who_title'] = $_POST['who_title'] ?? 'من يمكنه الاستفادة من هذه البرامج';
            $pageData['who_subtitle'] = $_POST['who_subtitle'] ?? 'كل من يستوفي الشروط التالية:';
            $pageData['who_items'] = $_POST['who_items'] ?? [];
        }
        // 11. تحديث متطلبات اللغة (Lang) - خاصة بصفحة البرامج الإنجليزية
        elseif (str_contains($action, '_lang')) {
            $pageData['lang_title'] = $_POST['lang_title'] ?? 'متطلبات اللغة بشكل عام';
            $pageData['lang_points'] = $_POST['lang_points'] ?? [];
        }
        // 12. تحديث نصائح التقديم (Tips) الخاصة بصفحة السنة التحضيرية
        elseif ($dbKey === 'foundation_page' && str_contains($action, '_tips')) {
            $pageData['tips_title'] = $_POST['tips_title'] ?? 'نصائح مهمة قبل التقديم';
            $tipsItemsRaw = $_POST['tips_items'] ?? [];
            $pageData['tips_items'] = is_array($tipsItemsRaw) ? array_values(array_filter(array_map('trim', $tipsItemsRaw), fn($val) => $val !== '')) : [];
        }

        $jsonVal = json_encode($pageData, JSON_UNESCAPED_UNICODE);
        $stmt->execute(['k' => $dbKey, 'v' => $jsonVal, 'v_update' => $jsonVal]);
        return true;
    }

    private function resolveDbKey(string $action): ?string
    {
        if (str_starts_with($action, 'update_arrival_')) return 'arrival_page';
        if (str_starts_with($action, 'update_check_')) return 'check_page';
        if (str_starts_with($action, 'update_cover_')) return 'coverletter_page';
        if (str_starts_with($action, 'update_cv_')) return 'cv_page';
        if (str_starts_with($action, 'update_motivation_')) return 'motivation_page';
        if (str_starts_with($action, 'update_german_')) return 'germanlang_page';
        if (str_starts_with($action, 'update_english_')) return 'englishlang_page';
        if (str_starts_with($action, 'update_offers_')) return 'offers_page';
        if (str_starts_with($action, 'update_health_')) return 'health_page';
        if (str_starts_with($action, 'update_financial_')) return 'financial_page';
        if (str_starts_with($action, 'update_living_')) return 'living_cost_page';
        if (str_starts_with($action, 'update_foundation_')) return 'foundation_page';
        return null;
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
