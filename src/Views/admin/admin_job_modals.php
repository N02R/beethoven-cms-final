<?php
/**
 * Beethoven CMS - Job Admin Modals (Fully Localized & AJAX Enabled)
 * النمط القياسي الذهبي لنوافذ التحكم الخاصة بصفحة الوظائف والتدريب (مطابق تماماً لمعيار admin_edu_modals.php)
 */

// 🌟 التحصين الأقوى: جلب اللغة حصراً من الجلسة أو الـ URL لضمان عدم ثباتها على لغة واحدة
$current_lang = $_SESSION['site_lang'] ?? (isset($_GET['lang']) ? htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8') : 'de');
$is_rtl = ($current_lang === 'ar');
$modal_dir = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

// تحميل ملف اللغة النشطة بدقة مطلقة
$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$lang = file_exists($lang_file) ? require $lang_file : [];
if (!is_array($lang)) $lang = [];

// 🌟 جلب بيانات أقسام الوظائف مباشرة من قاعدة البيانات إن لم تكن متوفرة في النطاق (Scope) لضمان عدم حدوث أي خطأ
if (!isset($job_hero) || empty($job_hero)) {
    try {
        $pdoConn = \App\Config\Database::getConnection();
        
        $fetchSetting = function(string $key) use ($pdoConn, $current_lang) {
            $stmt = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = :k");
            $stmt->execute(['k' => $key]);
            $raw = $stmt->fetchColumn();
            if ($raw && is_string($raw)) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    if (isset($decoded[$current_lang])) return $decoded[$current_lang];
                    if (isset($decoded['ar'])) return $decoded['ar'];
                    return $decoded;
                }
            }
            return [];
        };

        $job_hero = $fetchSetting('job_hero');
        
        $job_why_data = $fetchSetting('job_why');
        $job_why_title = $job_why_data['title'] ?? 'لماذا التدريب والتوظيف في ألمانيا؟';
        $job_why_desc  = $job_why_data['desc'] ?? '';
        $job_why_items = $job_why_data['items'] ?? [];

        $job_programs_data = $fetchSetting('job_programs');
        $job_program_title = $job_programs_data['title'] ?? 'برامج التدريب المهني والتوظيف';
        $job_program_desc  = $job_programs_data['desc'] ?? '';
        $job_program_types = $job_programs_data['items'] ?? [];

        $job_timeline_data = $fetchSetting('job_timeline');
        $job_timeline_title = $job_timeline_data['title'] ?? 'خطوات التوظيف والتدريب في ألمانيا';
        $job_timeline_desc  = $job_timeline_data['desc'] ?? '';
        $job_timeline_steps = $job_timeline_data['steps'] ?? ($job_timeline_data['items'] ?? []);

        $job_services_data = $fetchSetting('job_services');
        $job_services_title = $job_services_data['title'] ?? ($job_services_data['section_title'] ?? 'خدمات التوظيف');
        $job_services_desc  = $job_services_data['desc'] ?? ($job_services_data['section_subtitle'] ?? '');
        $job_services_items = $job_services_data['items'] ?? [];

    } catch (\Exception $e) {
        $job_hero = [];
        $job_why_title = 'لماذا التدريب والتوظيف في ألمانيا؟';
        $job_why_desc = '';
        $job_why_items = [];
        $job_program_title = 'برامج التدريب المهني والتوظيف';
        $job_program_desc = '';
        $job_program_types = [];
        $job_timeline_title = 'خطوات التوظيف والتدريب في ألمانيا';
        $job_timeline_desc = '';
        $job_timeline_steps = [];
        $job_services_title = 'خدمات التوظيف';
        $job_services_desc = '';
        $job_services_items = [];
    }
}
?>

<!-- 1. Job Hero Modal -->
<div class="modal fade custom-modal" id="jobHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-briefcase-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_hero'] ?? 'تعديل قسم البداية للتدريب والتوظيف', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($job_hero['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_hero[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_hero['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['hero_btn_text'] ?? 'نص الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($job_hero['btn_text'] ?? 'تواصل معنا', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['url'] ?? 'رابط الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars($job_hero['btn_url'] ?? 'contact', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-2 text-secondary d-block <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['current_image'] ?? 'الصورة الحالية:', ENT_QUOTES, 'UTF-8'); ?></label>
                        <?php if (!empty($job_hero['img'])): ?>
                            <div class="mb-3 p-2 border rounded-3 bg-light text-center" style="max-width: 200px;">
                                <img src="<?php echo htmlspecialchars(get_image_url($job_hero['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Job Hero Image" class="rounded-2" style="max-height: 100px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['choose_file'] ?? 'اختر ملف', ENT_QUOTES, 'UTF-8'); ?></label>
                        <input type="file" class="form-control" name="job_hero_img" accept="image/*">
                        <input type="hidden" name="old_job_hero_img" value="<?php echo htmlspecialchars($job_hero['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Job Why Modal -->
<div class="modal fade custom-modal" id="jobWhyModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_why'] ?? 'إدارة لماذا التدريب معنا', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script>
                    window.CMS_LANG = window.CMS_LANG || {};
                    window.CMS_LANG.job_why_title = '<?php echo addslashes($lang['title'] ?? 'العنوان'); ?>';
                    window.CMS_LANG.job_why_desc = '<?php echo addslashes($lang['description'] ?? 'الوصف'); ?>';
                    window.CMS_LANG.job_why_image = '<?php echo addslashes($lang['icon_image'] ?? 'الصورة / الأيقونة'); ?>';
                    window.CMS_LANG.delete_item = '<?php echo addslashes($lang['delete'] ?? 'حذف'); ?>';
                </script>

                <form id="jobWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_why">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_why[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($job_why_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_why[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_why_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة الأسباب -->
                    <div id="jobWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($job_why_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm job-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_why_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-why-title" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-why-desc" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'الصورة / الأيقونة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-why-file" name="job_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-why-old-img" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_why_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete'] ?? 'حذف', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addJobWhyRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['job_why_add_btn'] ?? 'إضافة سبب جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobWhyForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Job Program Modal (أنواع التدريب) -->
<div class="modal fade custom-modal" id="jobProgramModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-journal-bookmark-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_programs'] ?? 'إدارة برامج وأنواع التدريب المهني', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script>
                    window.CMS_LANG = window.CMS_LANG || {};
                    window.CMS_LANG.prog_title = '<?php echo addslashes($lang['prog_name_lbl'] ?? 'اسم البرنامج'); ?>';
                    window.CMS_LANG.prog_desc = '<?php echo addslashes($lang['prog_desc_lbl'] ?? 'تفاصيل البرنامج'); ?>';
                    window.CMS_LANG.prog_btn_text = '<?php echo addslashes($lang['hero_btn_text'] ?? 'نص الزر'); ?>';
                    window.CMS_LANG.prog_btn_url = '<?php echo addslashes($lang['url'] ?? 'رابط الزر'); ?>';
                    window.CMS_LANG.prog_image = '<?php echo addslashes($lang['prog_img_lbl'] ?? 'صورة البرنامج'); ?>';
                    window.CMS_LANG.prog_dark = '<?php echo addslashes($lang['prog_dark_lbl'] ?? 'تصميم داكن (Highlight)'); ?>';
                    window.CMS_LANG.delete_prog = '<?php echo addslashes($lang['delete'] ?? 'حذف'); ?>';
                </script>

                <form id="jobProgramForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_programs">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_programs[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($job_program_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف العام للقسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_programs[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_program_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة البرامج -->
                    <div id="jobProgramContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($job_program_types ?? []) as $index => $prog): ?>
                            <div class="p-3 shadow-sm job-prog-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="prog_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['prog_name_lbl'] ?? 'اسم البرنامج', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-title" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($prog['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم البرنامِج">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['hero_btn_text'] ?? 'نص الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-btn-text" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][btn_text]" value="<?php echo htmlspecialchars($prog['btn_text'] ?? 'اطلب الآن', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اطلب الآن">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['url'] ?? 'رابط الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-btn-url" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][btn_url]" value="<?php echo htmlspecialchars($prog['btn_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="#">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['prog_desc_lbl'] ?? 'تفاصيل البرنامج', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control prog-desc" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;" placeholder="تفاصيل البرنامِج"><?php echo htmlspecialchars($prog['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end mb-3">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['prog_img_lbl'] ?? 'صورة البرنامج', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($prog['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($prog['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Img" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control prog-file" name="prog_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="prog-old-img" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($prog['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('prog_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete'] ?? 'حذف', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="form-check form-switch p-2 ps-4 bg-light rounded-3 border">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][is_dark]" value="1" <?php echo !empty($prog['is_dark']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small text-secondary fw-semibold cursor-pointer"><?php echo htmlspecialchars($lang['prog_dark_lbl'] ?? 'تصميم داكن (Highlight)', ENT_QUOTES, 'UTF-8'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addJobProgramRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['prog_add_btn'] ?? 'إضافة برنامج جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobProgramForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Job Timeline Modal (خطوات التدريب والتوظيف) -->
<div class="modal fade custom-modal" id="jobTimelineModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_timeline'] ?? 'إدارة خطوات التوظيف', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script>
                    window.CMS_LANG = window.CMS_LANG || {};
                    window.CMS_LANG.job_step_title = '<?php echo addslashes($lang['step_title_lbl'] ?? 'اسم الخطوة'); ?>';
                    window.CMS_LANG.job_step_subtitle = '<?php echo addslashes($lang['step_subtitle_lbl'] ?? 'العنوان الفرعي'); ?>';
                    window.CMS_LANG.job_step_desc = '<?php echo addslashes($lang['step_desc_lbl'] ?? 'التفاصيل'); ?>';
                    window.CMS_LANG.job_step_order = '<?php echo addslashes($lang['step_order_lbl'] ?? 'الترتيب'); ?>';
                    window.CMS_LANG.job_step_icon = '<?php echo addslashes($lang['step_icon_lbl'] ?? 'الأيقونة الحالية / الجديدة'); ?>';
                    window.CMS_LANG.delete_job_step = '<?php echo addslashes($lang['delete_step'] ?? 'حذف الخطوة'); ?>';
                </script>

                <form id="jobTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_timeline">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_timeline[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($job_timeline_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم العام', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_timeline[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_timeline_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة خطوات الرحلة -->
                    <div id="jobTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($job_timeline_steps ?? []) as $index => $step): ?>
                            <div class="p-3 shadow-sm job-step-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_step_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                                        <input type="text" class="form-control job-step-title" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                        <input type="text" class="form-control job-step-subtitle" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان الفرعي">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-9 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                                        <input type="text" class="form-control job-step-desc" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="التفاصيل">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">الترتيب</label>
                                        <input type="number" class="form-control job-step-order" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($step['order'] ?? $index, ENT_QUOTES, 'UTF-8'); ?>" placeholder="الترتيب">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">الأيقونة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($step['icon']), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-step-file" name="steps_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-step-old-icon" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_step_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addJobStepRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobTimelineForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Job Services Modal -->
<div class="modal fade custom-modal" id="jobServicesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_services'] ?? 'إدارة كروت الخدمات', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script>
                    window.CMS_LANG = window.CMS_LANG || {};
                    window.CMS_LANG.job_srv_title = '<?php echo addslashes($lang['service_title'] ?? 'اسم الخدمة'); ?>';
                    window.CMS_LANG.job_srv_url = '<?php echo addslashes($lang['service_url'] ?? 'رابط الخدمة'); ?>';
                    window.CMS_LANG.job_srv_img = '<?php echo addslashes($lang['image_or_icon'] ?? 'صورة الخلفية'); ?>';
                    window.CMS_LANG.delete_job_service = '<?php echo addslashes($lang['delete_service'] ?? 'حذف الخدمة'); ?>';
                </script>

                <form id="jobServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_services">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_services[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($job_services_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم العام', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_services[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_services_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة كروت الخدمات -->
                    <div id="jobServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($job_services_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm job-srv-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_srv_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                                        <input type="text" class="form-control job-srv-title" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخدمة">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">رابط الخدمة (اسم الصفحة فقط)</label>
                                        <input type="text" class="form-control job-srv-url" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: job-package">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">صورة الخلفية الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="service image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-srv-file" name="srv_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-srv-old-img" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_srv_row_<?php echo $index; ?>')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addJobServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> إضافة خدمة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Rows & AJAX JS Engine -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = 'alert-success';
        let icon = 'bi-check-circle-fill';
        let title = 'تم بنجاح!';

        if (type === 'danger') {
            bgClass = 'alert-danger';
            icon = 'bi-x-circle-fill';
            title = 'عذراً، حدث خطأ!';
        } else if (type === 'warning') {
            bgClass = 'alert-warning';
            icon = 'bi-exclamation-triangle-fill';
            title = 'تنبيه هام';
        }

        const alertDiv = document.createElement('div');
        alertDiv.id = 'customNotificationAlert';
        alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
        alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
        
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon} fs-4"></i>
                <div>
                    <strong>${title}</strong>
                    <div class="small">${message}</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            if (alertDiv) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 4000);
    }

    // 1. إضافة سبب لـ "لماذا التدريب معنا"
    function addJobWhyRow() {
        const container = document.getElementById('jobWhyContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        const count = container.querySelectorAll('.job-why-row-item').length;

        const t = window.CMS_LANG || {};
        const tTitle = t.job_why_title || 'العنوان';
        const tDesc = t.job_why_desc || 'الوصف';
        const tImg = t.job_why_image || 'الصورة / الأيقونة';
        const tDelete = t.delete_item || 'حذف';

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-why-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'job_why_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tTitle}</label>
                    <input type="text" class="form-control job-why-title" name="job_why[${currentLang}][items][${count}][title]" placeholder="${tTitle}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tDesc}</label>
                    <input type="text" class="form-control job-why-desc" name="job_why[${currentLang}][items][${count}][desc]" placeholder="${tDesc}">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">${tImg}</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control job-why-file" name="why_img_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="job-why-old-img" name="job_why[${currentLang}][items][${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${tDelete}"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 2. إضافة برنامج تدريبي جديد
    function addJobProgramRow() {
        const container = document.getElementById('jobProgramContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        const count = container.querySelectorAll('.job-prog-row-item').length;

        const t = window.CMS_LANG || {};
        const tTitle = t.prog_title || 'اسم البرنامج';
        const tBtnText = t.prog_btn_text || 'نص الزر';
        const tBtnUrl = t.prog_btn_url || 'رابط الزر';
        const tDesc = t.prog_desc || 'تفاصيل البرنامج';
        const tImg = t.prog_image || 'صورة البرنامج';
        const tDelete = t.delete_prog || 'حذف';

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-prog-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'prog_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tTitle}</label>
                    <input type="text" class="form-control prog-title" name="job_programs[${currentLang}][items][${count}][title]" placeholder="${tTitle}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">${tBtnText}</label>
                    <input type="text" class="form-control prog-btn-text" name="job_programs[${currentLang}][items][${count}][btn_text]" value="اطلب الآن" placeholder="${tBtnText}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">${tBtnUrl}</label>
                    <input type="text" class="form-control prog-btn-url" name="job_programs[${currentLang}][items][${count}][btn_url]" value="#" placeholder="${tBtnUrl}">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">${tDesc}</label>
                    <textarea class="form-control prog-desc" name="job_programs[${currentLang}][items][${count}][desc]" rows="2" style="height: auto; padding: 12px 16px;" placeholder="${tDesc}"></textarea>
                </div>
            </div>
            <div class="row g-2 align-items-end mb-3">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">${tImg}</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control prog-file" name="prog_img_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="prog-old-img" name="job_programs[${currentLang}][items][${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${tDelete}"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div class="form-check form-switch p-2 ps-4 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="job_programs[${currentLang}][items][${count}][is_dark]" value="1">
                        <label class="form-check-label small text-secondary fw-semibold cursor-pointer">تصميم داكن (Highlight)</label>
                    </div>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 3. إضافة خطوة جديدة لـ Timeline
    function addJobStepRow() {
        const container = document.getElementById('jobTimelineContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        
        const currentRows = container.querySelectorAll('.job-step-row-item').length;
        if (currentRows >= 6) {
            showNotification('عذراً، الحد الأقصى المسموح به هو 6 عناصر فقط في خط الزمن (Timeline).', 'warning');
            return;
        }

        const t = window.CMS_LANG || {};
        const tTitle = t.job_step_title || 'اسم الخطوة';
        const tSubtitle = t.job_step_subtitle || 'العنوان الفرعي';
        const tDesc = t.job_step_desc || 'التفاصيل';
        const tOrder = t.job_step_order || 'الترتيب';
        const tIcon = t.job_step_icon || 'الأيقونة الحالية / الجديدة';
        const tDelete = t.delete_job_step || 'حذف الخطوة';

        const count = currentRows;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-step-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'job_step_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tTitle}</label>
                    <input type="text" class="form-control job-step-title" name="job_timeline[${currentLang}][steps][${count}][title]" placeholder="${tTitle}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tSubtitle}</label>
                    <input type="text" class="form-control job-step-subtitle" name="job_timeline[${currentLang}][steps][${count}][subtitle]" placeholder="${tSubtitle}">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-9">
                    <label class="form-label fw-semibold small text-secondary">${tDesc}</label>
                    <input type="text" class="form-control job-step-desc" name="job_timeline[${currentLang}][steps][${count}][desc]" placeholder="${tDesc}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">${tOrder}</label>
                    <input type="number" class="form-control job-step-order" name="job_timeline[${currentLang}][steps][${count}][order]" value="${count}" placeholder="${tOrder}">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">${tIcon}</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control job-step-file" name="steps_icon_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="job-step-old-icon" name="job_timeline[${currentLang}][steps][${count}][old_icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${tDelete}"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 4. إضافة خدمة جديدة
    function addJobServiceRow() {
        const container = document.getElementById('jobServicesContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        const count = container.querySelectorAll('.job-srv-row-item').length;

        const t = window.CMS_LANG || {};
        const tTitle = t.job_srv_title || 'اسم الخدمة';
        const tUrl = t.job_srv_url || 'رابط الخدمة';
        const tImg = t.job_srv_img || 'صورة الخلفية';
        const tDelete = t.delete_job_service || 'حذف الخدمة';

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-srv-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'job_srv_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tTitle}</label>
                    <input type="text" class="form-control job-srv-title" name="job_services[${currentLang}][items][${count}][title]" placeholder="${tTitle}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">${tUrl}</label>
                    <input type="text" class="form-control job-srv-url" name="job_services[${currentLang}][items][${count}][url]" placeholder="مثال: job-package">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">${tImg}</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control job-srv-file" name="srv_img_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="job-srv-old-img" name="job_services[${currentLang}][items][${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${tDelete}"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج الحفظ وإعادة الترقيم التلقائي عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentLang = this.querySelector('input[name="targetLang"]')?.value || 'ar';

                // إعادة ترقيم "لماذا التدريب معنا"
                form.querySelectorAll('.job-why-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.job-why-title');
                    const descInput = row.querySelector('.job-why-desc');
                    const fileInput = row.querySelector('.job-why-file');
                    const oldImgInput = row.querySelector('.job-why-old-img');

                    if (titleInput) titleInput.name = `job_why[${currentLang}][items][${index}][title]`;
                    if (descInput) descInput.name = `job_why[${currentLang}][items][${index}][desc]`;
                    if (fileInput) fileInput.name = `why_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `job_why[${currentLang}][items][${index}][old_img]`;
                });

                // إعادة ترقيم "برامج التدريب"
                form.querySelectorAll('.job-prog-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.prog-title');
                    const btnTextInput = row.querySelector('.prog-btn-text');
                    const btnUrlInput = row.querySelector('.prog-btn-url');
                    const descInput = row.querySelector('.prog-desc');
                    const fileInput = row.querySelector('.prog-file');
                    const oldImgInput = row.querySelector('.prog-old-img');
                    const darkInput = row.querySelector('input[type="checkbox"]');

                    if (titleInput) titleInput.name = `job_programs[${currentLang}][items][${index}][title]`;
                    if (btnTextInput) btnTextInput.name = `job_programs[${currentLang}][items][${index}][btn_text]`;
                    if (btnUrlInput) btnUrlInput.name = `job_programs[${currentLang}][items][${index}][btn_url]`;
                    if (descInput) descInput.name = `job_programs[${currentLang}][items][${index}][desc]`;
                    if (fileInput) fileInput.name = `prog_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `job_programs[${currentLang}][items][${index}][old_img]`;
                    if (darkInput) darkInput.name = `job_programs[${currentLang}][items][${index}][is_dark]`;
                });

                // إعادة ترقيم "خطوات التوظيف" (Timeline)
                form.querySelectorAll('.job-step-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.job-step-title');
                    const subtitleInput = row.querySelector('.job-step-subtitle');
                    const orderInput = row.querySelector('.job-step-order');
                    const descInput = row.querySelector('.job-step-desc');
                    const fileInput = row.querySelector('.job-step-file');
                    const oldIconInput = row.querySelector('.job-step-old-icon');

                    if (titleInput) titleInput.name = `job_timeline[${currentLang}][steps][${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `job_timeline[${currentLang}][steps][${index}][subtitle]`;
                    if (orderInput) orderInput.name = `job_timeline[${currentLang}][steps][${index}][order]`;
                    if (descInput) descInput.name = `job_timeline[${currentLang}][steps][${index}][desc]`;
                    if (fileInput) fileInput.name = `steps_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `job_timeline[${currentLang}][steps][${index}][old_icon]`;
                });

                // إعادة ترقيم "كروت الخدمات"
                form.querySelectorAll('.job-srv-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.job-srv-title');
                    const urlInput = row.querySelector('.job-srv-url');
                    const fileInput = row.querySelector('.job-srv-file');
                    const oldImgInput = row.querySelector('.job-srv-old-img');

                    if (titleInput) titleInput.name = `job_services[${currentLang}][items][${index}][title]`;
                    
                    if (urlInput) {
                        let val = urlInput.value.trim();
                        if (val && !val.startsWith('http') && !val.includes('edu-services/') && !val.includes('job-services/')) {
                            val = 'job-services/' + val;
                        }
                        urlInput.value = val;
                        urlInput.name = `job_services[${currentLang}][items][${index}][url]`;
                    }

                    if (fileInput) fileInput.name = `srv_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `job_services[${currentLang}][items][${index}][old_img]`;
                });

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (csrfToken && !formData.has('csrf_token')) {
                    formData.append('csrf_token', csrfToken);
                }

                fetch('index.php?url=admin/settings/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success || data.status === 'success') {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'يرجى التأكد من البيانات المدخلة'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
                });
            });
        });
    });
</script>
