<?php
/**
 * Beethoven CMS - Education Admin Modals (Fully Localized & AJAX Enabled)
 * النمط القياسي الذهبي لنوافذ التحكم الخاصة بصفحة التعليم (مطابق تماماً لمعيار admin_about_modals.php)
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

// 🌟 جلب بيانات أقسام التعليم مباشرة من قاعدة البيانات إن لم تكن متوفرة في النطاق (Scope) لضمان عدم حدوث أي خطأ
if (!isset($edu_hero) || empty($edu_hero)) {
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

        $edu_hero = $fetchSetting('edu_hero');
        $edu_why_data = $fetchSetting('edu_why');
        $edu_why_title = $edu_why_data['title'] ?? 'لماذا الدراسة في ألمانيا؟';
        $edu_why_desc  = $edu_why_data['desc'] ?? '';
        $edu_why_items = $edu_why_data['items'] ?? [];

        $edu_timeline_data = $fetchSetting('edu_timeline');
        $edu_timeline_title = $edu_timeline_data['title'] ?? 'خطوات الدراسة في ألمانيا';
        $edu_timeline_desc  = $edu_timeline_data['desc'] ?? '';
        $edu_timeline_steps = $edu_timeline_data['steps'] ?? ($edu_timeline_data['items'] ?? []);

        $edu_services_data = $fetchSetting('edu_services');
        $edu_services_title = $edu_services_data['title'] ?? ($edu_services_data['section_title'] ?? 'خدمات التعليم');
        $edu_services_desc  = $edu_services_data['desc'] ?? ($edu_services_data['section_subtitle'] ?? '');
        $edu_services_items = $edu_services_data['items'] ?? [];

    } catch (\Exception $e) {
        // قيم احتياطية عند حدوث أي استثناء
        $edu_hero = [];
        $edu_why_title = 'لماذا الدراسة في ألمانيا؟';
        $edu_why_desc = '';
        $edu_why_items = [];
        $edu_timeline_title = 'خطوات الدراسة في ألمانيا';
        $edu_timeline_desc = '';
        $edu_timeline_steps = [];
        $edu_services_title = 'خدمات التعليم';
        $edu_services_desc = '';
        $edu_services_items = [];
    }
}
?>

<!-- 1. Edu Hero Modal (قسم الهيرو) -->
<div class="modal fade custom-modal" id="eduHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-mortarboard text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_hero'] ?? 'تعديل هيرو التعليم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_title'] ?? 'العنوان الرئيسي', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($edu_hero['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_desc'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                <textarea class="form-control" name="edu_hero[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_hero['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_btn_text'] ?? 'نص الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($edu_hero['btn_text'] ?? 'ابدأ الآن', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_url'] ?? 'رابط الزر', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars($edu_hero['btn_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_image'] ?? 'الصورة الرئيسية', ENT_QUOTES, 'UTF-8'); ?></label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($edu_hero['img'])): ?>
                                        <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                            <img src="<?php echo htmlspecialchars(get_image_url($edu_hero['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="edu_hero_img" accept="image/*">
                                </div>
                                <input type="hidden" name="old_edu_hero_img" value="<?php echo htmlspecialchars($edu_hero['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Edu Why Modal (قسم لماذا الدراسة) -->
<div class="modal fade custom-modal" id="eduWhyModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-patch-question text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_why'] ?? 'إدارة لماذا الدراسة', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <script>
                    window.CMS_LANG = window.CMS_LANG || {};
                    window.CMS_LANG.edu_why_title = '<?php echo addslashes($lang['title'] ?? 'العنوان'); ?>';
                    window.CMS_LANG.edu_why_desc = '<?php echo addslashes($lang['description'] ?? 'الوصف المختصر'); ?>';
                    window.CMS_LANG.edu_why_image = '<?php echo addslashes($lang['icon_image'] ?? 'الأيقونة / الصورة'); ?>';
                    window.CMS_LANG.delete_item = '<?php echo addslashes($lang['delete'] ?? 'حذف'); ?>';
                </script>

                <form id="eduWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_why">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_why[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($edu_why_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_why[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_why_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة الأسباب -->
                    <div id="eduWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_why_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm edu-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="why_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-why-title" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: جودة التعليم">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف المختصر', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-why-desc" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="شرح بسيط للسبب">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'الأيقونة / الصورة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control edu-why-file" name="edu_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-why-old-img" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('why_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduWhyRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> إضافة سبب جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduWhyForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Edu Timeline Modal (قسم خطوات الرحلة) -->
<div class="modal fade custom-modal" id="eduTimelineModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3 text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_timeline'] ?? 'إدارة خطوات الرحلة', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_timeline">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_timeline[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($edu_timeline_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_timeline[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_timeline_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة خطوات الرحلة -->
                    <div id="eduTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_timeline_steps ?? []) as $index => $step): ?>
                            <div class="p-3 shadow-sm edu-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="step_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                                        <input type="text" class="form-control edu-step-title" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                        <input type="text" class="form-control edu-step-subtitle" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان الفرعي">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-9 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                                        <input type="text" class="form-control edu-step-desc" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="التفاصيل">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">الترتيب</label>
                                        <input type="number" class="form-control edu-step-order" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($step['order'] ?? $index, ENT_QUOTES, 'UTF-8'); ?>" placeholder="الترتيب">
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
                                            <input type="file" class="form-control edu-step-file" name="edu_timeline_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-step-old-icon" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('step_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduStepRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduTimelineForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Edu Services Modal (قسم خدمات التعليم العالي) -->
<div class="modal fade custom-modal" id="eduServicesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_services'] ?? 'إدارة خدمات التعليم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_services">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_services[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($edu_services_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_services[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_services_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة خدمات التعليم -->
                    <div id="eduServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_services_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm edu-service-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="edu_srv_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                                        <input type="text" class="form-control edu-srv-title" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخدمة">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary">رابط الخدمة (اسم الصفحة فقط)</label>
                                        <input type="text" class="form-control edu-srv-url" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: bachelor-package">
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
                                            <input type="file" class="form-control edu-srv-file" name="edu_service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-srv-old-img" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('edu_srv_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> إضافة خدمة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. دالة عامة لحذف أي صف بناءً على الـ ID
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية فوق المودال
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

    // 3. دالة إضافة صف جديد لـ "لماذا الدراسة"
    function addEduWhyRow() {
        const container = document.getElementById('eduWhyContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        const eduWhyCount = container.querySelectorAll('.edu-why-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-why-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'why_row_' + Date.now() + '_' + eduWhyCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">العنوان</label>
                    <input type="text" class="form-control edu-why-title" name="edu_why[${currentLang}][items][${eduWhyCount}][title]" placeholder="مثال: جودة التعليم">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                    <input type="text" class="form-control edu-why-desc" name="edu_why[${currentLang}][items][${eduWhyCount}][desc]" placeholder="شرح بسيط للسبب">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control edu-why-file" name="edu_why_img_${eduWhyCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-why-old-img" name="edu_why[${currentLang}][items][${eduWhyCount}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 4. دالة إضافة صف جديد لـ "خطوات الرحلة التعليمية" مع قيد الـ 6 عناصر
    function addEduStepRow() {
        const container = document.getElementById('eduTimelineContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        
        const currentRows = container.querySelectorAll('.edu-timeline-row-item').length;
        if (currentRows >= 6) {
            showNotification('عذراً، لا يمكن إضافة أكثر من 6 عناصر في خط الزمن (Timeline).', 'warning');
            return;
        }

        const eduStepCount = currentRows;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-timeline-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'step_row_' + Date.now() + '_' + eduStepCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                    <input type="text" class="form-control edu-step-title" name="edu_timeline[${currentLang}][steps][${eduStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                    <input type="text" class="form-control edu-step-subtitle" name="edu_timeline[${currentLang}][steps][${eduStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-9">
                    <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                    <input type="text" class="form-control edu-step-desc" name="edu_timeline[${currentLang}][steps][${eduStepCount}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">الترتيب</label>
                    <input type="number" class="form-control edu-step-order" name="edu_timeline[${currentLang}][steps][${eduStepCount}][order]" value="${eduStepCount}" placeholder="الترتيب">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">الأيقونة الحالية / الجديدة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control edu-step-file" name="edu_timeline_icon_${eduStepCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-step-old-icon" name="edu_timeline[${currentLang}][steps][${eduStepCount}][old_icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 5. دالة إضافة صف جديد لـ "خدمات التعليم"
    function addEduServiceRow() {
        const container = document.getElementById('eduServicesContainer');
        if (!container) return;
        const currentLang = document.querySelector('input[name="targetLang"]')?.value || 'ar';
        const eduSrvCount = container.querySelectorAll('.edu-service-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-service-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'edu_srv_row_' + Date.now() + '_' + eduSrvCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                    <input type="text" class="form-control edu-srv-title" name="edu_services[${currentLang}][items][${eduSrvCount}][title]" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">رابط الخدمة</label>
                    <input type="text" class="form-control edu-srv-url" name="edu_services[${currentLang}][items][${eduSrvCount}][url]" placeholder="مثال: coverletter أو bachelor-package">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">صورة الخلفية الحالية / الجديدة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control edu-srv-file" name="edu_service_img_${eduSrvCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-srv-old-img" name="edu_services[${currentLang}][items][${eduSrvCount}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 6. معالج الحفظ وإعادة الترقيم التلقائي لجميع الأقسام عند الحفظ عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentLang = this.querySelector('input[name="targetLang"]')?.value || 'ar';

                // إعادة ترقيم "لماذا الدراسة"
                form.querySelectorAll('.edu-why-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-why-title');
                    const descInput = row.querySelector('.edu-why-desc');
                    const fileInput = row.querySelector('.edu-why-file');
                    const oldImgInput = row.querySelector('.edu-why-old-img');

                    if (titleInput) titleInput.name = `edu_why[${currentLang}][items][${index}][title]`;
                    if (descInput) descInput.name = `edu_why[${currentLang}][items][${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_why_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_why[${currentLang}][items][${index}][old_img]`;
                });

                // إعادة ترقيم "خطوات الرحلة التعليمية"
                form.querySelectorAll('.edu-timeline-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-step-title');
                    const subtitleInput = row.querySelector('.edu-step-subtitle');
                    const orderInput = row.querySelector('.edu-step-order');
                    const descInput = row.querySelector('.edu-step-desc');
                    const fileInput = row.querySelector('.edu-step-file');
                    const oldIconInput = row.querySelector('.edu-step-old-icon');

                    if (titleInput) titleInput.name = `edu_timeline[${currentLang}][steps][${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `edu_timeline[${currentLang}][steps][${index}][subtitle]`;
                    if (orderInput) orderInput.name = `edu_timeline[${currentLang}][steps][${index}][order]`;
                    if (descInput) descInput.name = `edu_timeline[${currentLang}][steps][${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_timeline_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `edu_timeline[${currentLang}][steps][${index}][old_icon]`;
                });

                // إعادة ترقيم "خدمات التعليم" وتعديل الرابط تلقائياً ليتوافق مع الـ Router
                form.querySelectorAll('.edu-service-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-srv-title');
                    const urlInput = row.querySelector('.edu-srv-url');
                    const fileInput = row.querySelector('.edu-srv-file');
                    const oldImgInput = row.querySelector('.edu-srv-old-img');

                    if (titleInput) titleInput.name = `edu_services[${currentLang}][items][${index}][title]`;
                    
                    if (urlInput) {
                        let val = urlInput.value.trim();
                        if (val && !val.startsWith('http') && !val.includes('edu-services/') && !val.includes('job-services/')) {
                            val = 'edu-services/' + val;
                        }
                        urlInput.value = val;
                        urlInput.name = `edu_services[${currentLang}][items][${index}][url]`;
                    }

                    if (fileInput) fileInput.name = `edu_service_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_services[${currentLang}][items][${index}][old_img]`;
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
                    console.log("Raw Server Response:", text);
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
