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
        } 
        // 4. تحديث النصائح والإرشادات (Advice / Tips)
        elseif (str_contains($action, '_advice') || str_contains($action, '_tips')) {
            $adviceTitle = $_POST['advice_title'] ?? ($_POST['tips_title'] ?? '');
            
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
            } else {
                $pageData['advice_title'] = $adviceTitle;
                $pageData['advice_points'] = $_POST['advice_points'] ?? [];
            }
        }
        // 5. تحديث مستويات اللغة الألمانية (Levels)
        elseif (str_contains($action, '_levels')) {
            $pageData['levels_section'] = [
                'title' => $_POST['levels_title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)',
                'levels_list' => $_POST['levels_list'] ?? []
            ];
        }
        // 6. تحديث مميزات دورات اللغة الألمانية (Features)
        elseif (str_contains($action, '_features')) {
            $pageData['features_section'] = [
                'title' => $_POST['features_title'] ?? 'مميزات دوراتنا',
                'features_list' => $_POST['features_list'] ?? []
            ];
        }
        // 7. تحديث الملاحظات (Notes)
        elseif (str_contains($action, '_notes') || str_contains($action, '_note')) {
            $pageData['note_title'] = $_POST['note_title'] ?? '';
            $pageData['notes'] = $_POST['notes'] ?? [];
            // دعم إضافي للملاحظة المفردة (كما في صفحة البرامج الإنجليزية)
            if (isset($_POST['note_highlight'])) {
                $pageData['note_highlight'] = $_POST['note_highlight'];
            }
            if (isset($_POST['note_text'])) {
                $pageData['note_text'] = $_POST['note_text'];
            }
        } 
        // 8. تحديث الروابط الخاصة بصفحة الفحص (Check Links)
        elseif (str_contains($action, '_links')) {
            $pageData['links_intro']       = $_POST['links_intro'] ?? '';
            $pageData['anabin_url']        = $_POST['anabin_url'] ?? '';
            $pageData['uniassist_url']     = $_POST['uniassist_url'] ?? '';
            $pageData['uni_contact_intro'] = $_POST['uni_contact_intro'] ?? '';
            $pageData['condition_1']       = $_POST['condition_1'] ?? '';
            $pageData['condition_2']       = $_POST['condition_2'] ?? '';
            $pageData['conclusion_text']   = $_POST['conclusion_text'] ?? '';
        }
        // 9. تحديث الملفات والتحميلات (Downloads)
        elseif (str_contains($action, '_downloads')) {
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
