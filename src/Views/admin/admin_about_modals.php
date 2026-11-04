<!-- 1. About Section Modal (قسم من نحن) -->
<?php
// 🌟 تأكيد وتحصين لغة المودل لتتطابق مع لغة الجلسة الحالية دائماً
$current_lang = $current_lang ?? ($_SESSION['site_lang'] ?? 'ar');
$is_rtl = ($current_lang === 'ar');
$modal_dir = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

// تحميل ملف اللغة النشطة إن لم يكن مححملاً مسبقاً
if (!isset($lang) || !is_array($lang) || empty($lang)) {
    $lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
    if (!file_exists($lang_file)) {
        $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
    }
    $lang = file_exists($lang_file) ? require $lang_file : [];
    if (!is_array($lang)) $lang = [];
}
?>

<div class="modal fade custom-modal" id="aboutEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_about_section'] ?? 'تعديل قسم من نحن', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات من نحن للغة النشطة الحالية بدقة متناهية
                $aboutRawSetting = $data['about_section'] ?? ($currentSettings['about_section'] ?? null);
                if (empty($aboutRawSetting)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtAbout = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'about_section'");
                        $stmtAbout->execute();
                        $aboutRawSetting = $stmtAbout->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $aboutRawSetting = '{}';
                    }
                }

                $aboutAllLangs = is_string($aboutRawSetting) ? json_decode($aboutRawSetting, true) : (is_array($aboutRawSetting) ? $aboutRawSetting : []);
                if (!is_array($aboutAllLangs)) {
                    $aboutAllLangs = [];
                }
                
                $curLangAbout = $aboutAllLangs[$current_lang] ?? ($aboutAllLangs['ar'] ?? []);

                $modalAbTitle       = $curLangAbout['title'] ?? ($ab['title'] ?? 'من نحن');
                $modalAbDesc        = $curLangAbout['desc'] ?? ($ab['desc'] ?? '');
                $modalAbBtnText     = $curLangAbout['btn_text'] ?? ($ab['btn_text'] ?? 'قراءة المزيد');
                $modalAbBtnUrl      = $curLangAbout['btn_url'] ?? ($ab['btn_url'] ?? '#');
                $modalMainImg       = $curLangAbout['main_img'] ?? ($ab['main_img'] ?? '');
                $modalSubImg        = $curLangAbout['sub_img'] ?? ($ab['sub_img'] ?? '');
                $modalVisionTitle   = $curLangAbout['vision_title'] ?? ($ab['vision_title'] ?? 'رؤية الشركة');
                $modalVisionDesc    = $curLangAbout['vision_desc'] ?? ($ab['vision_desc'] ?? '');
                $modalVisionIcon    = $curLangAbout['vision_icon'] ?? ($ab['vision_icon'] ?? '');
                $modalMessageTitle  = $curLangAbout['message_title'] ?? ($ab['message_title'] ?? 'رسالة الشركة');
                $modalMessageDesc   = $curLangAbout['message_desc'] ?? ($ab['message_desc'] ?? '');
                $modalMessageIcon   = $curLangAbout['message_icon'] ?? ($ab['message_icon'] ?? '');
                ?>

                <form id="aboutSectionForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_section">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="about_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <!-- العنوان والوصف -->
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['main_section_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$modalAbTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['description'] ?? 'نص الوصف', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="about[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalAbDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <!-- أزرار التوجيه -->
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['hero_btn_text'] ?? $lang['button_text'] ?? 'نص الزر', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars((string)$modalAbBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['hero_btn_url'] ?? $lang['button_url'] ?? 'رابط الزر الحالي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars((string)$modalAbBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <!-- الصور الرئيسية والمعاينة -->
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['main_image'] ?? 'الصورة الرئيسية الحالية', ENT_QUOTES, 'UTF-8'); ?></label>
                                <?php if (!empty($modalMainImg)): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($modalMainImg) : $modalMainImg, ENT_QUOTES, 'UTF-8'); ?>" alt="Main Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_main_img" accept="image/*">
                                <input type="hidden" name="about[<?php echo $current_lang; ?>][old_main_img]" value="<?php echo htmlspecialchars($modalMainImg, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['sub_image'] ?? 'الصورة الفرعية الحالية', ENT_QUOTES, 'UTF-8'); ?></label>
                                <?php if (!empty($modalSubImg)): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($modalSubImg) : $modalSubImg, ENT_QUOTES, 'UTF-8'); ?>" alt="Sub Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_sub_img" accept="image/*">
                                <input type="hidden" name="about[<?php echo $current_lang; ?>][old_sub_img]" value="<?php echo htmlspecialchars($modalSubImg, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12"><hr class="my-3 text-muted opacity-25"></div>

                            <!-- رؤية الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-eye"></i> <?php echo htmlspecialchars($lang['company_vision'] ?? 'رؤية الشركة', ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][vision_title]" value="<?php echo htmlspecialchars((string)$modalVisionTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'الأيقونة / الصورة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($modalVisionIcon)): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($modalVisionIcon) : $modalVisionIcon, ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_vision_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="about[<?php echo $current_lang; ?>][old_vision_icon]" value="<?php echo htmlspecialchars($modalVisionIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control" name="about[<?php echo $current_lang; ?>][vision_desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalVisionDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- رسالة الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-chat-square-text"></i> <?php echo htmlspecialchars($lang['company_message'] ?? 'رسالة الشركة', ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][message_title]" value="<?php echo htmlspecialchars((string)$modalMessageTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'الأيقونة / الصورة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($modalMessageIcon)): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($modalMessageIcon) : $modalMessageIcon, ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_message_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="about[<?php echo $current_lang; ?>][old_message_icon]" value="<?php echo htmlspecialchars($modalMessageIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control" name="about[<?php echo $current_lang; ?>][message_desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalMessageDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="aboutSectionForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Team Edit Modal (قسم فريق العمل) -->
<div class="modal fade custom-modal" id="teamEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-people text-primary"></i> إدارة فريق العمل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="teamForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="team_title" value="<?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="team_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['team_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $team_items = $data['team_items'] ?? [];
                        if (!empty($team_items)):
                            foreach ($team_items as $index => $member): 
                        ?>
                            <div class="p-3 shadow-sm team-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="team_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary">الاسم</label>
                                        <input type="text" class="form-control team-name" name="team[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الاسم">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary">المسمى الوظيفي</label>
                                        <input type="text" class="form-control team-role" name="team[<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary">الصورة الشخصية</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($member['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($member['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Member Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control team-file" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="team-old-img" name="team[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($member['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_<?php echo $index; ?>')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3 py-2 fw-bold" style="border-radius: 12px; border-style: dashed; border-width: 2px;" onclick="addTeamRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة عضو جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="teamForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Counts Edit Modal (قسم الإحصائيات/العدادات) -->
<div class="modal fade custom-modal" id="countsEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calculator text-primary"></i> إدارة العدادات والإحصائيات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="countsForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_counts">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $counts_items = $data['about_counts'] ?? [];
                        if (!empty($counts_items)):
                            foreach ($counts_items as $index => $c): 
                        ?>
                            <div class="p-3 shadow-sm count-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="count_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    
                                    <!-- الرقم -->
                                    <div class="col-md-3">
                                        <label for="count_number_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الرقم / القيمة</label>
                                        <input type="text" id="count_number_<?php echo $index; ?>" class="form-control count-number" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: +500">
                                    </div>
                                    
                                    <!-- الوصف -->
                                    <div class="col-md-4">
                                        <label for="count_title_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">عنوان العداد / الوصف</label>
                                        <input type="text" id="count_title_<?php echo $index; ?>" class="form-control count-title" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: مشروع مكتمل">
                                    </div>
                                    
                                    <!-- الصورة -->
                                    <div class="col-md-4">
                                        <label for="count_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">أيقونة / صورة العداد</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($c['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($c['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Count Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="count_file_<?php echo $index; ?>" class="form-control count-file" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" class="count-old-img" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($c['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    
                                    <!-- زر الحذف -->
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_<?php echo $index; ?>')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                                    </div>
                                    
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCountRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة عداد جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="countsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Partners Edit Modal (قسم الشركاء) -->
<div class="modal fade custom-modal" id="partnersEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-handshake text-primary"></i> إدارة الشركاء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="partnersForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_partners">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان القسم -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label for="partners_title_input" class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" id="partners_title_input" class="form-control" name="partners_title" value="<?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <!-- قائمة الشركاء -->
                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $partners_items = $data['partners_items'] ?? [];
                        if (!empty($partners_items)):
                            foreach ($partners_items as $index => $partner): 
                        ?>
                            <div class="p-3 shadow-sm partner-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    
                                    <!-- رفع شعار الشريك -->
                                    <div class="col-md-11">
                                        <label for="partner_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">شعار / صورة الشريك</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($partner['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($partner['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Partner Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="partner_file_<?php echo $index; ?>" class="form-control partner-file" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="partner-old-img" name="partners[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($partner['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    
                                    <!-- زر الحذف -->
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_<?php echo $index; ?>')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                                    </div>
                                    
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addPartnerRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شريك جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="partnersForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Rows JS Engine & AJAX Handlers -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // دالة لإنشاء وعرض التنبيهات الاحترافية
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
        alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; border-radius: 12px; border: none;';
        
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

    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        const teamCount = container.querySelectorAll('.team-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm team-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="team_name_${teamCount}" class="form-label fw-semibold small text-secondary">الاسم الكامل</label>
                    <input type="text" id="team_name_${teamCount}" class="form-control team-name" name="team[${teamCount}][name]" placeholder="الاسم">
                </div>
                <div class="col-md-3">
                    <label for="team_role_${teamCount}" class="form-label fw-semibold small text-secondary">المسمى الوظيفي</label>
                    <input type="text" id="team_role_${teamCount}" class="form-control team-role" name="team[${teamCount}][role]" placeholder="المسمى الوظيفي">
                </div>
                <div class="col-md-5">
                    <label for="team_file_${teamCount}" class="form-label fw-semibold small text-secondary">صورة العضو</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="team_file_${teamCount}" class="form-control team-file" name="team_img_${teamCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="team-old-img" name="team[${teamCount}][old_img]" value="">
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_${teamCount}')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        const countsCount = container.querySelectorAll('.count-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm count-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="count_number_${countsCount}" class="form-label fw-semibold small text-secondary">الرقم / القيمة</label>
                    <input type="text" id="count_number_${countsCount}" class="form-control count-number" name="counts[${countsCount}][number]" placeholder="الرقم">
                </div>
                <div class="col-md-4">
                    <label for="count_title_${countsCount}" class="form-label fw-semibold small text-secondary">عنوان العداد / الوصف</label>
                    <input type="text" id="count_title_${countsCount}" class="form-control count-title" name="counts[${countsCount}][title]" placeholder="الوصف">
                </div>
                <div class="col-md-4">
                    <label for="count_file_${countsCount}" class="form-label fw-semibold small text-secondary">أيقونة / صورة العداد</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="count_file_${countsCount}" class="form-control count-file" name="count_img_${countsCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="count-old-img" name="counts[${countsCount}][old_img]" value="">
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_${countsCount}')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        const partnerCount = container.querySelectorAll('.partner-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm partner-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'partner_row_' + partnerCount;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-11">
                    <label for="partner_file_${partnerCount}" class="form-label fw-semibold small text-secondary">شعار / صورة الشريك</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="partner_file_${partnerCount}" class="form-control partner-file" name="partner_img_${partnerCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="partner-old-img" name="partners[${partnerCount}][old_img]" value="">
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_${partnerCount}')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج النماذج الموحد والشامل
    document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // إعادة ترقيم صفوف الفريق بدقة
            const teamRows = form.querySelectorAll('.team-row-item');
            teamRows.forEach((row, index) => {
                const nameInput = row.querySelector('.team-name');
                const roleInput = row.querySelector('.team-role');
                const fileInput = row.querySelector('.team-file');
                const oldImgInput = row.querySelector('.team-old-img');

                if(nameInput) nameInput.name = `team[${index}][name]`;
                if(roleInput) roleInput.name = `team[${index}][role]`;
                if(fileInput) fileInput.name = `team_img_${index}`;
                if(oldImgInput) oldImgInput.name = `team[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الإحصائيات بدقة
            const countRows = form.querySelectorAll('.count-row-item');
            countRows.forEach((row, index) => {
                const numInput = row.querySelector('.count-number');
                const titleInput = row.querySelector('.count-title');
                const fileInput = row.querySelector('.count-file');
                const oldImgInput = row.querySelector('.count-old-img');

                if(numInput) numInput.name = `counts[${index}][number]`;
                if(titleInput) titleInput.name = `counts[${index}][title]`;
                if(fileInput) fileInput.name = `count_img_${index}`;
                if(oldImgInput) oldImgInput.name = `counts[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الشركاء بدقة
            const partnerRows = form.querySelectorAll('.partner-row-item');
            partnerRows.forEach((row, index) => {
                const fileInput = row.querySelector('.partner-file');
                const oldImgInput = row.querySelector('.partner-old-img');

                if(fileInput) fileInput.name = `partner_img_${index}`;
                if(oldImgInput) oldImgInput.name = `partners[${index}][old_img]`;
            });

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>';
            
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
                    if (data.success) {
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
</script>
