<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;
use Throwable;

class PageContentSettingsService
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    private string $rootPath;
    private ImageUploader $imageUploader;
    private array $filesToDelete = [];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = rtrim($rootPath, '/\\');
        $this->imageUploader = $imageUploader;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $dbKey = $this->resolveDbKey($action);
        if (!$dbKey) {
            return false;
        }

        $this->filesToDelete = [];
        $targetLang = $this->resolveTargetLang(['targetLang', 'page_lang', 'lang']);

        try {
            // 1. جلب البيانات الحالية للصفحة من جدول pages الجديد
            $allLangs = $this->fetchPageData($pdo, $dbKey);
            $pageData = (isset($allLangs[$targetLang]) && is_array($allLangs[$targetLang]))
                ? $allLangs[$targetLang]
                : [];

            // 2. معالجة تحديثات صفحة الاستقبال (Arrival Page)
            if ($dbKey === 'arrival_page') {
                $postedArrival = is_array($_POST['arrival_data'][$targetLang] ?? null)
                    ? $_POST['arrival_data'][$targetLang]
                    : (is_array($_POST['arrival_data'] ?? null) ? $_POST['arrival_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedArrival['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedArrival['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg9.png');
                    $submittedOldImg = (string)($postedArrival['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('arrival_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg9.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedArrival['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedArrival['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_tips')) {
                    $pageData['advice_title'] = trim((string)($postedArrival['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $pageData['advice_desc'] = trim((string)($postedArrival['advice_desc'] ?? ($pageData['advice_desc'] ?? '')));
                    $tipsRaw = $postedArrival['tips'] ?? ($_POST['tips'] ?? []);
                    $pageData['tips'] = is_array($tipsRaw) ? array_values(array_filter(array_map('trim', $tipsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedArrival['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedArrival['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                }
            }

            // 3. معالجة خاصة لصفحة BCS Bachelor Package
            elseif ($dbKey === 'bachelor_package_page') {
                $postedBachelor = is_array($_POST['bachelor_data'][$targetLang] ?? null)
                    ? $_POST['bachelor_data'][$targetLang]
                    : (is_array($_POST['bachelor_data'] ?? null) ? $_POST['bachelor_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedBachelor['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedBachelor['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_content')) {
                    $pageData['main_title'] = trim((string)($postedBachelor['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedBachelor['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                    $pageData['password_label'] = trim((string)($postedBachelor['password_label'] ?? ($pageData['password_label'] ?? '')));
                    $pageData['btn_text'] = trim((string)($postedBachelor['btn_text'] ?? ($pageData['btn_text'] ?? '')));
                    $pageData['btn_url'] = $this->sanitizeLinkUrl((string)($postedBachelor['btn_url'] ?? ($pageData['btn_url'] ?? '#')));
                }
            }

            // 4. معالجة خاصة لصفحة تحقق من شهاداتك التعليمية (Check Certificate Page)
            elseif ($dbKey === 'check_page') {
                $postedCheck = is_array($_POST['check_data'][$targetLang] ?? null)
                    ? $_POST['check_data'][$targetLang]
                    : (is_array($_POST['check_data'] ?? null) ? $_POST['check_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCheck['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCheck['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg13.png');
                    $submittedOldImg = (string)($postedCheck['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('check_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg13.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCheck['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCheck['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedCheck['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedCheck['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_links')) {
                    $pageData['links_intro']       = trim((string)($postedCheck['links_intro'] ?? ($pageData['links_intro'] ?? '')));
                    $pageData['anabin_url']        = $this->sanitizeLinkUrl((string)($postedCheck['anabin_url'] ?? ($pageData['anabin_url'] ?? '')));
                    $pageData['uniassist_url']     = $this->sanitizeLinkUrl((string)($postedCheck['uniassist_url'] ?? ($pageData['uniassist_url'] ?? '')));
                    $pageData['uni_contact_intro'] = trim((string)($postedCheck['uni_contact_intro'] ?? ($pageData['uni_contact_intro'] ?? '')));
                    $pageData['condition_1']       = trim((string)($postedCheck['condition_1'] ?? ($pageData['condition_1'] ?? '')));
                    $pageData['condition_2']       = trim((string)($postedCheck['condition_2'] ?? ($pageData['condition_2'] ?? '')));
                    $pageData['conclusion_text']   = trim((string)($postedCheck['conclusion_text'] ?? ($pageData['conclusion_text'] ?? '')));
                }
            }

            // 5. معالجة خاصة لصفحة الدورة التحضيرية لشهادات اللغة الألمانية (Courses Page)
            elseif ($dbKey === 'courses_page') {
                $postedCourses = is_array($_POST['courses_data'][$targetLang] ?? null)
                    ? $_POST['courses_data'][$targetLang]
                    : (is_array($_POST['courses_data'] ?? null) ? $_POST['courses_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCourses['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCourses['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg12.png');
                    $submittedOldImg = (string)($postedCourses['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('courses_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg12.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCourses['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCourses['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_goals')) {
                    $pageData['goals_title'] = trim((string)($postedCourses['goals_title'] ?? ($pageData['goals_title'] ?? '')));
                    $goalsRaw = $postedCourses['goals'] ?? ($_POST['goals'] ?? []);
                    $pageData['goals'] = is_array($goalsRaw) ? array_values(array_filter(array_map('trim', $goalsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_warning')) {
                    $pageData['warning_text'] = trim((string)($postedCourses['warning_text'] ?? ($pageData['warning_text'] ?? '')));
                } 
                elseif (str_contains($action, '_cost')) {
                    $pageData['cost_title'] = trim((string)($postedCourses['cost_title'] ?? ($pageData['cost_title'] ?? '')));
                    $costItemsRaw = $postedCourses['cost_items'] ?? [];
                    $cleanCostItems = [];
                    if (is_array($costItemsRaw)) {
                        foreach ($costItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $descVal  = trim((string)($item['desc'] ?? ''));
                            if ($titleVal !== '' || $descVal !== '') {
                                $cleanCostItems[] = [
                                    'title' => $titleVal,
                                    'desc'  => $descVal,
                                ];
                            }
                        }
                    }
                    $pageData['cost_items'] = $cleanCostItems;
                }
            }

            // 6. معالجة خاصة لصفحة خطاب الطلب (Cover Letter Page)
            elseif ($dbKey === 'coverletter_page') {
                $postedCover = is_array($_POST['cover_data'][$targetLang] ?? null)
                    ? $_POST['cover_data'][$targetLang]
                    : (is_array($_POST['cover_data'] ?? null) ? $_POST['cover_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCover['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCover['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg1.jpg');
                    $submittedOldImg = (string)($postedCover['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('cover_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg1.jpg';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCover['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCover['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_advice')) {
                    $pageData['advice_title'] = trim((string)($postedCover['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $adviceRaw = $postedCover['advice_points'] ?? ($_POST['advice_points'] ?? []);
                    $pageData['advice_points'] = is_array($adviceRaw) ? array_values(array_filter(array_map('trim', $adviceRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedCover['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedCover['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_download')) {
                    $dlItemsRaw = $postedCover['download_items'] ?? [];
                    $cleanDlItems = [];
                    if (is_array($dlItemsRaw)) {
                        foreach ($dlItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $subVal   = trim((string)($item['sub'] ?? ''));
                            $typeVal  = strtolower(trim((string)($item['type'] ?? 'pdf')));
                            $typeVal  = in_array($typeVal, ['pdf', 'word'], true) ? $typeVal : 'pdf';
                            $fileVal  = $this->sanitizeLinkUrl((string)($item['file'] ?? '#'));

                            if ($titleVal !== '' || $fileVal !== '#') {
                                $cleanDlItems[] = [
                                    'title' => $titleVal,
                                    'sub'   => $subVal,
                                    'type'  => $typeVal,
                                    'file'  => $fileVal,
                                ];
                            }
                        }
                    }
                    $pageData['download_items'] = $cleanDlItems;
                }
            }

            // 7. معالجة خاصة لصفحة السيرة الذاتية (CV Page)
            elseif ($dbKey === 'cv_page') {
                $postedCv = is_array($_POST['cv_data'][$targetLang] ?? null)
                    ? $_POST['cv_data'][$targetLang]
                    : (is_array($_POST['cv_data'] ?? null) ? $_POST['cv_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCv['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCv['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg2.jpg');
                    $submittedOldImg = (string)($postedCv['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('cv_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg2.jpg';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCv['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCv['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_advice')) {
                    $pageData['advice_title'] = trim((string)($postedCv['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $adviceRaw = $postedCv['advice_points'] ?? ($_POST['advice_points'] ?? []);
                    $pageData['advice_points'] = is_array($adviceRaw) ? array_values(array_filter(array_map('trim', $adviceRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_download')) {
                    $dlItemsRaw = $postedCv['download_items'] ?? [];
                    $cleanDlItems = [];
                    if (is_array($dlItemsRaw)) {
                        foreach ($dlItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $subVal   = trim((string)($item['sub'] ?? ''));
                            $typeVal  = strtolower(trim((string)($item['type'] ?? 'pdf')));
                            $typeVal  = in_array($typeVal, ['pdf', 'word'], true) ? $typeVal : 'pdf';
                            $fileVal  = $this->sanitizeLinkUrl((string)($item['file'] ?? '#'));

                            if ($titleVal !== '' || $fileVal !== '#') {
                                $cleanDlItems[] = [
                                    'title' => $titleVal,
                                    'sub'   => $subVal,
                                    'type'  => $typeVal,
                                    'file'  => $fileVal,
                                ];
                            }
                        }
                    }
                    $pageData['download_items'] = $cleanDlItems;
                }
            }

            // 8. معالجة خاصة لصفحة البرامج الدراسية باللغة الإنجليزية (English Language Page)
            elseif ($dbKey === 'englishlang_page') {
                $postedEnglish = is_array($_POST['english_data'][$targetLang] ?? null)
                    ? $_POST['english_data'][$targetLang]
                    : (is_array($_POST['english_data'] ?? null) ? $_POST['english_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedEnglish['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedEnglish['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg5.png');
                    $submittedOldImg = (string)($postedEnglish['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('english_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg5.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedEnglish['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedEnglish['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_who')) {
                    $pageData['who_title'] = trim((string)($postedEnglish['who_title'] ?? ($pageData['who_title'] ?? '')));
                    $pageData['who_subtitle'] = trim((string)($postedEnglish['who_subtitle'] ?? ($pageData['who_subtitle'] ?? '')));
                    $whoRaw = $postedEnglish['who_items'] ?? ($_POST['who_items'] ?? []);
                    $pageData['who_items'] = is_array($whoRaw) ? array_values(array_filter(array_map('trim', $whoRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_lang')) {
                    $pageData['lang_title'] = trim((string)($postedEnglish['lang_title'] ?? ($pageData['lang_title'] ?? '')));
                    $langRaw = $postedEnglish['lang_points'] ?? ($_POST['lang_points'] ?? []);
                    $pageData['lang_points'] = is_array($langRaw) ? array_values(array_filter(array_map('trim', $langRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_note')) {
                    $pageData['note_highlight'] = trim((string)($postedEnglish['note_highlight'] ?? ($pageData['note_highlight'] ?? '')));
                    $pageData['note_text'] = trim((string)($postedEnglish['note_text'] ?? ($pageData['note_text'] ?? '')));
                }
            }

            // 9. معالجة خاصة لصفحة الضمانات المالية والحساب البنكي المغلق (Financial Page)
            elseif ($dbKey === 'financial_page') {
                $postedFinancial = is_array($_POST['financial_data'][$targetLang] ?? null)
                    ? $_POST['financial_data'][$targetLang]
                    : (is_array($_POST['financial_data'] ?? null) ? $_POST['financial_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedFinancial['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedFinancial['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg7.png');
                    $submittedOldImg = (string)($postedFinancial['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('financial_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg7.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title']       = trim((string)($postedFinancial['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc']        = trim((string)($postedFinancial['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                    $pageData['importance_title'] = trim((string)($postedFinancial['importance_title'] ?? ($pageData['importance_title'] ?? '')));
                    $pageData['importance_desc']  = trim((string)($postedFinancial['importance_desc'] ?? ($pageData['importance_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_options')) {
                    $pageData['options_title'] = trim((string)($postedFinancial['options_title'] ?? ($pageData['options_title'] ?? '')));
                    $optsRaw = $postedFinancial['options_items'] ?? ($_POST['options_items'] ?? []);
                    $pageData['options_items'] = is_array($optsRaw) ? array_values(array_filter(array_map('trim', $optsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_account')) {
                    $pageData['account_title'] = trim((string)($postedFinancial['account_title'] ?? ($pageData['account_title'] ?? '')));
                    $accRaw = $postedFinancial['account_points'] ?? ($_POST['account_points'] ?? []);
                    $pageData['account_points'] = is_array($accRaw) ? array_values(array_filter(array_map('trim', $accRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_links')) {
                    $linksRaw = $postedFinancial['service_links'] ?? [];
                    $cleanLinks = [];
                    if (is_array($linksRaw)) {
                        foreach ($linksRaw as $item) {
                            if (!is_array($item)) continue;
                            $textVal   = trim((string)($item['text'] ?? ''));
                            $urlVal    = $this->sanitizeLinkUrl((string)($item['url'] ?? '#'));
                            $activeVal = !empty($item['active']) ? 1 : 0;

                            if ($textVal !== '') {
                                $cleanLinks[] = [
                                    'text'   => $textVal,
                                    'url'    => $urlVal,
                                    'active' => $activeVal,
                                ];
                            }
                        }
                    }
                    $pageData['service_links'] = $cleanLinks;
                }
            }

            // 10. معالجة خاصة لصفحة الدورة التأسيسية / السنة التحضيرية (Foundation / Studienkolleg Page)
            elseif ($dbKey === 'foundation_page') {
                $postedStk = is_array($_POST['stk_data'][$targetLang] ?? null)
                    ? $_POST['stk_data'][$targetLang]
                    : (is_array($_POST['stk_data'] ?? null) ? $_POST['stk_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedStk['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedStk['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/serviceimg11.png');
                    $submittedOldImg = (string)($postedStk['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('stk_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/serviceimg11.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedStk['hero_position'] ?? ($pageData['hero_position'] ?? 'center -20rem')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center -20rem';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedStk['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedStk['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_goals')) {
                    $pageData['goals_title'] = trim((string)($postedStk['goals_title'] ?? ($pageData['goals_title'] ?? '')));
                    $goalsRaw = $postedStk['goals_items'] ?? ($_POST['goals_items'] ?? []);
                    $pageData['goals_items'] = is_array($goalsRaw) ? array_values(array_filter(array_map('trim', $goalsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_learning')) {
                    $pageData['learning_title'] = trim((string)($postedStk['learning_title'] ?? ($pageData['learning_title'] ?? '')));
                    $pageData['learning_intro'] = trim((string)($postedStk['learning_intro'] ?? ($pageData['learning_intro'] ?? '')));
                    $pageData['learning_p1']    = trim((string)($postedStk['learning_p1'] ?? ($pageData['learning_p1'] ?? '')));
                    $pageData['learning_p2']    = trim((string)($postedStk['learning_p2'] ?? ($pageData['learning_p2'] ?? '')));
                } 
                elseif (str_contains($action, '_courses')) {
                    $pageData['courses_title'] = trim((string)($postedStk['courses_title'] ?? ($pageData['courses_title'] ?? '')));
                    $coursesRaw = $postedStk['courses_items'] ?? ($_POST['courses_items'] ?? []);
                    $pageData['courses_items'] = is_array($coursesRaw) ? array_values(array_filter(array_map('trim', $coursesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_uni_type')) {
                    $pageData['uni_type_title'] = trim((string)($postedStk['uni_type_title'] ?? ($pageData['uni_type_title'] ?? '')));
                    $pageData['uni_type_intro'] = trim((string)($postedStk['uni_type_intro'] ?? ($pageData['uni_type_intro'] ?? '')));
                    $pageData['uni_public']     = trim((string)($postedStk['uni_public'] ?? ($pageData['uni_public'] ?? '')));
                    $pageData['uni_applied']    = trim((string)($postedStk['uni_applied'] ?? ($pageData['uni_applied'] ?? '')));
                } 
                elseif (str_contains($action, '_types')) {
                    $pageData['types_title']       = trim((string)($postedStk['types_title'] ?? ($pageData['types_title'] ?? '')));
                    $pageData['type_public_desc']  = trim((string)($postedStk['type_public_desc'] ?? ($pageData['type_public_desc'] ?? '')));
                    $pageData['type_private_desc'] = trim((string)($postedStk['type_private_desc'] ?? ($pageData['type_private_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['notes_title'] = trim((string)($postedStk['notes_title'] ?? ($pageData['notes_title'] ?? '')));
                    $notesRaw = $postedStk['notes_items'] ?? ($_POST['notes_items'] ?? []);
                    $pageData['notes_items'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_exam_fsp')) {
                    $pageData['exam_title'] = trim((string)($postedStk['exam_title'] ?? ($pageData['exam_title'] ?? '')));
                    $pageData['exam_desc']  = trim((string)($postedStk['exam_desc'] ?? ($pageData['exam_desc'] ?? '')));
                    $pageData['fsp_title']  = trim((string)($postedStk['fsp_title'] ?? ($pageData['fsp_title'] ?? '')));
                    $pageData['fsp_desc']   = trim((string)($postedStk['fsp_desc'] ?? ($pageData['fsp_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_tips')) {
                    $pageData['tips_title'] = trim((string)($postedStk['tips_title'] ?? ($pageData['tips_title'] ?? '')));
                    $tipsRaw = $postedStk['tips_items'] ?? ($_POST['tips_items'] ?? []);
                    $pageData['tips_items'] = is_array($tipsRaw) ? array_values(array_filter(array_map('trim', $tipsRaw), fn($val) => $val !== '')) : [];
                }
            }

            // 11. معالجة خاصة لصفحة متطلبات التأشيرة (General Visa Page)
            elseif ($dbKey === 'general_page') {
                $postedVisa = is_array($_POST['visa_data'][$targetLang] ?? null)
                    ? $_POST['visa_data'][$targetLang]
                    : (is_array($_POST['visa_data'] ?? null) ? $_POST['visa_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedVisa['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedVisa['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg14.png');
                    $submittedOldImg = (string)($postedVisa['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('visa_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg14.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedVisa['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedVisa['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedVisa['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $incomingSec = is_array($postedVisa['notes_section'] ?? null) ? $postedVisa['notes_section'] : [];
                    $existingSec = is_array($pageData['notes_section'] ?? null) ? $pageData['notes_section'] : [];
                    $notesListRaw = $incomingSec['notes_list'] ?? ($_POST['notes_list'] ?? []);

                    $pageData['notes_section'] = [
                        'title'      => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'notes_list' => is_array($notesListRaw)
                            ? array_values(array_filter(array_map('trim', $notesListRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                } 
                elseif (str_contains($action, '_download')) {
                    $dlItemsRaw = $postedVisa['download_items'] ?? [];
                    $cleanDlItems = [];
                    if (is_array($dlItemsRaw)) {
                        foreach ($dlItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $typeVal  = strtolower(trim((string)($item['type'] ?? 'pdf')));
                            $typeVal  = in_array($typeVal, ['pdf', 'word'], true) ? $typeVal : 'pdf';
                            $fileVal  = $this->sanitizeLinkUrl((string)($item['file'] ?? '#'));

                            if ($titleVal !== '' || $fileVal !== '#') {
                                $cleanDlItems[] = [
                                    'title' => $titleVal,
                                    'type'  => $typeVal,
                                    'file'  => $fileVal,
                                ];
                            }
                        }
                    }
                    $pageData['download_items'] = $cleanDlItems;
                }
            }

            // 12. معالجة خاصة لصفحة دورات اللغة الألمانية (German Language Page)
            elseif ($dbKey === 'germanlang_page') {
                $postedGerman = is_array($_POST['german_data'][$targetLang] ?? null)
                    ? $_POST['german_data'][$targetLang]
                    : (is_array($_POST['german_data'] ?? null) ? $_POST['german_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedGerman['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedGerman['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg4.png');
                    $submittedOldImg = (string)($postedGerman['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('german_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg4.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedGerman['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedGerman['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedGerman['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_levels')) {
                    $incomingSec = is_array($postedGerman['levels_section'] ?? null) ? $postedGerman['levels_section'] : [];
                    $existingSec = is_array($pageData['levels_section'] ?? null) ? $pageData['levels_section'] : [];
                    $listRaw = $incomingSec['levels_list'] ?? ($_POST['levels_list'] ?? []);

                    $pageData['levels_section'] = [
                        'title'       => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'levels_list' => is_array($listRaw)
                            ? array_values(array_filter(array_map('trim', $listRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                } 
                elseif (str_contains($action, '_features')) {
                    $incomingSec = is_array($postedGerman['features_section'] ?? null) ? $postedGerman['features_section'] : [];
                    $existingSec = is_array($pageData['features_section'] ?? null) ? $pageData['features_section'] : [];
                    $listRaw = $incomingSec['features_list'] ?? ($_POST['features_list'] ?? []);

                    $pageData['features_section'] = [
                        'title'         => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'features_list' => is_array($listRaw)
                            ? array_values(array_filter(array_map('trim', $listRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                } 
                elseif (str_contains($action, '_tips')) {
                    $incomingSec = is_array($postedGerman['tips_section'] ?? null) ? $postedGerman['tips_section'] : [];
                    $existingSec = is_array($pageData['tips_section'] ?? null) ? $pageData['tips_section'] : [];
                    $listRaw = $incomingSec['tips_list'] ?? ($_POST['tips_list'] ?? []);

                    $pageData['tips_section'] = [
                        'title'     => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'tips_list' => is_array($listRaw)
                            ? array_values(array_filter(array_map('trim', $listRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                }
            }

            // 13. معالجة خاصة لصفحة التأمين الصحي (Health Page)
            elseif ($dbKey === 'health_page') {
                $postedHealth = is_array($_POST['health_data'][$targetLang] ?? null)
                    ? $_POST['health_data'][$targetLang]
                    : (is_array($_POST['health_data'] ?? null) ? $_POST['health_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedHealth['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedHealth['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg6.png');
                    $submittedOldImg = (string)($postedHealth['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('health_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg6.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedHealth['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedHealth['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedHealth['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_tips')) {
                    $pageData['advice_title'] = trim((string)($postedHealth['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $tipsRaw = $postedHealth['tips'] ?? ($_POST['tips'] ?? []);
                    $pageData['tips'] = is_array($tipsRaw) ? array_values(array_filter(array_map('trim', $tipsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedHealth['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedHealth['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_links')) {
                    $pageData['intro_desc'] = trim((string)($postedHealth['intro_desc'] ?? ($pageData['intro_desc'] ?? '')));
                    $linksRaw = $postedHealth['links'] ?? [];
                    $cleanLinks = [];
                    if (is_array($linksRaw)) {
                        foreach ($linksRaw as $item) {
                            if (!is_array($item)) continue;
                            $textVal   = trim((string)($item['text'] ?? ''));
                            $urlVal    = $this->sanitizeLinkUrl((string)($item['url'] ?? '#'));
                            $activeVal = !empty($item['active']) ? 1 : 0;

                            if ($textVal !== '') {
                                $cleanLinks[] = [
                                    'text'   => $textVal,
                                    'url'    => $urlVal,
                                    'active' => $activeVal,
                                ];
                            }
                        }
                    }
                    $pageData['links'] = $cleanLinks;
                }
            }

            // 14. معالجة خاصة لصفحة تكلفة المعيشة في ألمانيا (Living Page)
            elseif ($dbKey === 'living_page') {
                $postedLiving = is_array($_POST['living_data'][$targetLang] ?? null)
                    ? $_POST['living_data'][$targetLang]
                    : (is_array($_POST['living_data'] ?? null) ? $_POST['living_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedLiving['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedLiving['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg8.png');
                    $submittedOldImg = (string)($postedLiving['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('living_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg8.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedLiving['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedLiving['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedLiving['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_tips')) {
                    $incomingSec = is_array($postedLiving['tips_section'] ?? null) ? $postedLiving['tips_section'] : [];
                    $existingSec = is_array($pageData['tips_section'] ?? null) ? $pageData['tips_section'] : [];
                    $listRaw = $incomingSec['items'] ?? ($_POST['items'] ?? []);

                    $pageData['tips_section'] = [
                        'title' => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'items' => is_array($listRaw)
                            ? array_values(array_filter(array_map('trim', $listRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                } 
                elseif (str_contains($action, '_notes')) {
                    $incomingSec = is_array($postedLiving['notes_section'] ?? null) ? $postedLiving['notes_section'] : [];
                    $existingSec = is_array($pageData['notes_section'] ?? null) ? $pageData['notes_section'] : [];
                    $listRaw = $incomingSec['items'] ?? ($_POST['items'] ?? []);

                    $pageData['notes_section'] = [
                        'title' => trim((string)($incomingSec['title'] ?? ($existingSec['title'] ?? ''))),
                        'items' => is_array($listRaw)
                            ? array_values(array_filter(array_map('trim', $listRaw), fn($val) => $val !== ''))
                            : [],
                    ];
                }
            }

            // 15. معالجة خاصة لصفحة باقة التدريب الطبي (Medical Packages Page)
            elseif ($dbKey === 'medical_packages_page') {
                $postedMedical = is_array($_POST['medical_data'][$targetLang] ?? null)
                    ? $_POST['medical_data'][$targetLang]
                    : (is_array($_POST['medical_data'] ?? null) ? $_POST['medical_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedMedical['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedMedical['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/job/servicesimg3.png');
                    $submittedOldImg = (string)($postedMedical['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('medical_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/job/servicesimg3.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedMedical['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedMedical['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedMedical['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedMedical['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $pageData['note_text'] = trim((string)($postedMedical['note_text'] ?? ($pageData['note_text'] ?? '')));
                } 
                elseif (str_contains($action, '_card')) {
                    $dlItemsRaw = $postedMedical['download_items'] ?? [];
                    $cleanDlItems = [];
                    if (is_array($dlItemsRaw)) {
                        foreach ($dlItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $subVal   = trim((string)($item['sub'] ?? ''));
                            $typeVal  = strtolower(trim((string)($item['type'] ?? 'pdf')));
                            $typeVal  = in_array($typeVal, ['pdf', 'word'], true) ? $typeVal : 'pdf';
                            $fileVal  = $this->sanitizeLinkUrl((string)($item['file'] ?? '#'));

                            if ($titleVal !== '' || $fileVal !== '#') {
                                $cleanDlItems[] = [
                                    'title' => $titleVal,
                                    'sub'   => $subVal,
                                    'type'  => $typeVal,
                                    'file'  => $fileVal,
                                ];
                            }
                        }
                    }
                    $pageData['download_items'] = $cleanDlItems;
                }
            }

            // 16. معالجة خاصة لصفحة اتفاقيات البحث عن عمل / التدريب (Job Agreements Page)
            elseif ($dbKey === 'job_agreements_page') {
                $postedAgr = is_array($_POST['job_agreements_data'][$targetLang] ?? null)
                    ? $_POST['job_agreements_data'][$targetLang]
                    : (is_array($_POST['job_agreements_data'] ?? null) ? $_POST['job_agreements_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedAgr['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedAgr['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/job/servicesimg4.png');
                    $submittedOldImg = (string)($postedAgr['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('job_agr_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/job/servicesimg4.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                    $posVal = trim((string)($postedAgr['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                    $pageData['hero_position'] = $posVal !== '' ? $posVal : 'center center';
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedAgr['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedAgr['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedAgr['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $pageData['note_text'] = trim((string)($postedAgr['note_text'] ?? ($pageData['note_text'] ?? '')));
                } 
                elseif (str_contains($action, '_card')) {
                    $dlItemsRaw = $postedAgr['download_items'] ?? [];
                    $cleanDlItems = [];
                    if (is_array($dlItemsRaw)) {
                        foreach ($dlItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $subVal   = trim((string)($item['sub'] ?? ''));
                            $typeVal  = strtolower(trim((string)($item['type'] ?? 'pdf')));
                            $typeVal  = in_array($typeVal, ['pdf', 'word'], true) ? $typeVal : 'pdf';
                            $fileVal  = $this->sanitizeLinkUrl((string)($item['file'] ?? '#'));

                            if ($titleVal !== '' || $fileVal !== '#') {
                                $cleanDlItems[] = [
                                    'title' => $titleVal,
                                    'sub'   => $subVal,
                                    'type'  => $typeVal,
                                    'file'  => $fileVal,
                                ];
                            }
                        }
                    }
                    $pageData['download_items'] = $cleanDlItems;
                }
            }

            // 17. تحديث مصفوفة اللغة وحفظها في جدول pages الجديد
            $allLangs[$targetLang] = $pageData;
            $this->savePageData($pdo, $dbKey, $allLangs);

            $this->flushDeletedFiles();
            return true;

        } catch (Throwable $e) {
            error_log('PageContentSettingsService Error: ' . $e->getMessage());
            return false;
        }
    }

    private function resolveDbKey(string $action): ?string
    {
        if (str_starts_with($action, 'update_arrival_')) return 'arrival_page';
        if (str_starts_with($action, 'update_bachelor_')) return 'bachelor_package_page';
        if (str_starts_with($action, 'update_check_')) return 'check_page';
        if (str_starts_with($action, 'update_courses_') || str_starts_with($action, 'update_lang_')) return 'courses_page';
        if (str_starts_with($action, 'update_cover_')) return 'coverletter_page';
        if (str_starts_with($action, 'update_cv_')) return 'cv_page';
        if (str_starts_with($action, 'update_english_')) return 'englishlang_page';
        if (str_starts_with($action, 'update_financial_') || str_starts_with($action, 'update_blocked_')) return 'financial_page';
        if (str_starts_with($action, 'update_stk_') || str_starts_with($action, 'update_foundation_')) return 'foundation_page';
        if (str_starts_with($action, 'update_visa_') || str_starts_with($action, 'update_general_')) return 'general_page';
        if (str_starts_with($action, 'update_german_')) return 'germanlang_page';
        if (str_starts_with($action, 'update_health_')) return 'health_page';
        if (str_starts_with($action, 'update_living_')) return 'living_page';
        if (str_starts_with($action, 'update_medical_')) return 'medical_packages_page';
        if (str_starts_with($action, 'update_job_agr_')) return 'job_agreements_page';
        return null;
    }

    private function resolveTargetLang(array $postKeys): string
    {
        foreach ($postKeys as $key) {
            if (!empty($_POST[$key]) && is_string($_POST[$key])) {
                $candidate = strtolower(trim($_POST[$key]));
                if (in_array($candidate, self::SUPPORTED_LANGS, true)) {
                    return $candidate;
                }
            }
        }
        $sessionLang = strtolower(trim((string)($_SESSION['site_lang'] ?? 'de')));
        return in_array($sessionLang, self::SUPPORTED_LANGS, true) ? $sessionLang : 'de';
    }

    private function fetchPageData(PDO $pdo, string $pageKey): array
    {
        $stmtFetch = $pdo->prepare('SELECT page_data FROM pages WHERE page_key = :k LIMIT 1');
        $stmtFetch->execute(['k' => $pageKey]);
        $dbRaw = $stmtFetch->fetchColumn();

        if (is_string($dbRaw) && $dbRaw !== '') {
            $decoded = json_decode($dbRaw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    private function savePageData(PDO $pdo, string $pageKey, array $data): void
    {
        $jsonVal = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $stmt = $pdo->prepare(
            'INSERT INTO pages (page_key, page_data)
             VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE page_data = :v_update'
        );
        $stmt->execute([
            'k'        => $pageKey,
            'v'        => $jsonVal,
            'v_update' => $jsonVal,
        ]);
    }

    private function handleImageField(string $fileInputKey, string $submittedOldPath, string $dbOldPath): string
    {
        $safeOld = trim($submittedOldPath !== '' ? $submittedOldPath : $dbOldPath);
        if (isset($_FILES[$fileInputKey]) && is_array($_FILES[$fileInputKey]) && ($_FILES[$fileInputKey]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $filename = $this->imageUploader->processAndUploadFile((string)$_FILES[$fileInputKey]['tmp_name']);
            if ($dbOldPath !== '' && str_starts_with(ltrim($dbOldPath, '/'), 'assets/uploads/')) {
                $this->filesToDelete[] = ltrim($dbOldPath, '/');
            }
            return 'assets/uploads/' . $filename;
        }
        return $safeOld;
    }

    private function sanitizeLinkUrl(string $url): string
    {
        $clean = trim($url);
        return ($clean === '' || preg_match('/^(javascript|data|vbscript|file):/i', $clean)) ? '#' : $clean;
    }

    private function flushDeletedFiles(): void
    {
        foreach (array_unique($this->filesToDelete) as $relativePath) {
            if (str_starts_with($relativePath, 'assets/uploads/')) {
                $this->imageUploader->deleteOldImage(basename($relativePath));
            }
        }
        $this->filesToDelete = [];
    }
}
