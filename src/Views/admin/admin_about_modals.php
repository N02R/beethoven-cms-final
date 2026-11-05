<!-- 1. About Section Modal (قسم من نحن الرئيسي) -->
<?php
// 🌟 التحصين الأقوى: جلب اللغة حصراً من الجلسة أو الـ URL لضمان عدم ثباتها على العربية
$current_lang = $_SESSION['site_lang'] ?? (isset($_GET['lang']) ? htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8') : 'ar');
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
                // جلب بيانات من نحن للغة النشطة الحالية مباشرة من القاعدة أو المكدس
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
                if (!is_array($aboutAllLangs)) $aboutAllLangs = [];
                
                // 🌟 التقاط البيانات الخاصة باللغة الحالية بدقة تامة مع تراجع آمن
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
                                    <?php echo htmlspecialchars($lang['title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
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
                                    <?php echo htmlspecialchars($lang['hero_btn_text'] ?? $lang['read_more'] ?? 'نص الزر', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars((string)$modalAbBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['url'] ?? 'رابط الزر الحالي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
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

<!-- 🌟 مودل تعديل خدمات صفحة من نحن (المطابق حرفياً لستايل خدمات الـ Home) -->
<div class="modal fade custom-modal" id="aboutServicesEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['edit_about_services'] ?? 'تعديل خدمات من نحن', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات about_services للغة الحالية من قاعدة البيانات
                $aboutServicesRaw = $data['about_services'] ?? ($currentSettings['about_services'] ?? null);
                if (empty($aboutServicesRaw)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtAS = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'about_services'");
                        $stmtAS->execute();
                        $aboutServicesRaw = $stmtAS->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $aboutServicesRaw = '{}';
                    }
                }

                $asAllLangs = is_string($aboutServicesRaw) ? json_decode($aboutServicesRaw, true) : (is_array($aboutServicesRaw) ? $aboutServicesRaw : []);
                if (!is_array($asAllLangs)) $asAllLangs = [];
                
                $curLangAS = $asAllLangs[$current_lang] ?? ($asAllLangs['ar'] ?? []);
                $asTitle   = $curLangAS['section_title'] ?? ($curLangAS['title'] ?? 'خدماتنا المميزة');
                $asDesc    = $curLangAS['section_subtitle'] ?? ($curLangAS['desc'] ?? '');
                $asItems   = $curLangAS['items'] ?? [];
                if (!is_array($asItems)) $asItems = [];
                ?>

                <form id="aboutServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['services_section_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about_services[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars((string)$asTitle, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['services_section_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['services_section_desc'] ?? 'العنوان الفرعي / الوصف', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="about_services[<?php echo $current_lang; ?>][section_subtitle]" rows="2" style="height: auto; padding: 12px 16px;" placeholder="<?php echo htmlspecialchars($lang['services_section_desc'] ?? 'العنوان الفرعي / الوصف', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$asDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['added_services'] ?? 'الخدمات المضافة', ENT_QUOTES, 'UTF-8'); ?></h6>
                    
                    <div id="aboutServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($asItems as $index => $item): 
                            $itemTitle = $item['title'] ?? '';
                            $itemUrl   = $item['url'] ?? '';
                            $itemImg   = $item['img'] ?? '';
                        ?>
                            <div class="p-3 shadow-sm service-item-row" id="about_service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['service_title'] ?? 'عنوان الخدمة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control about-service-title" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['service_title'] ?? 'عنوان الخدمة', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['service_url'] ?? 'رابط الخدمة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control about-service-url" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['service_url'] ?? 'رابط الخدمة', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['image_or_icon'] ?? 'الصورة / الأيقونة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control about-service-file" name="about_services_img_<?php echo $index; ?>" accept="image/*">
                                            <?php if (!empty($itemImg)): ?>
                                                <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($itemImg) : $itemImg, ENT_QUOTES, 'UTF-8'); ?>" alt="Service Preview" class="rounded border" style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <input type="hidden" class="about-service-old-img" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($itemImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('about_service_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_service'] ?? 'حذف الخدمة', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addAboutServiceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_service'] ?? 'إضافة خدمة جديدة', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="aboutServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Team Edit Modal (قسم فريق العمل) -->
<div class="modal fade custom-modal" id="teamEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-people text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_team'] ?? 'إدارة فريق العمل', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $teamAllRaw = $currentSettings['team'] ?? ($data['team'] ?? null);
                if (empty($teamAllRaw)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtTeam = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'team'");
                        $stmtTeam->execute();
                        $teamAllRaw = $stmtTeam->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $teamAllRaw = '{}';
                    }
                }
                $teamAllLangs = is_string($teamAllRaw) ? json_decode($teamAllRaw, true) : (is_array($teamAllRaw) ? $teamAllRaw : []);
                if (!is_array($teamAllLangs)) $teamAllLangs = [];
                $curLangTeam = $teamAllLangs[$current_lang] ?? ($teamAllLangs['ar'] ?? []);
                $modalTeamTitle = $curLangTeam['title'] ?? ($data['team_title'] ?? 'فريق العمل');
                $modalTeamDesc  = $curLangTeam['desc'] ?? ($data['team_desc'] ?? '');
                $modalTeamItems = $curLangTeam['items'] ?? ($data['team_items'] ?? []);
                if (!is_array($modalTeamItems)) $modalTeamItems = [];
                ?>
                <form id="teamForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="team_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['team_section_title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                            <input type="text" class="form-control" name="team[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$modalTeamTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="<?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['team_section_desc'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                            <textarea class="form-control" name="team[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalTeamDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalTeamItems as $index => $member): ?>
                            <div class="p-3 shadow-sm team-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="team_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['member_name'] ?? 'الاسم الكامل', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control team-name" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['member_name'] ?? 'الاسم', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['member_role'] ?? 'المسمى الوظيفي', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control team-role" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['member_role'] ?? 'المسمى الوظيفي', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['member_image'] ?? 'صورة العضو', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($member['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($member['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Member Image" class="rounded-2 w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control team-file w-100" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="team-old-img" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($member['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_member'] ?? 'حذف العضو', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addTeamRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_member'] ?? 'إضافة عضو جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="teamForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Counts Edit Modal (قسم العدادات والإحصائيات) -->
<div class="modal fade custom-modal" id="countsEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calculator text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_counts'] ?? 'إدارة العدادات والإحصائيات', ENT_QUOTES, 'UTF-8'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="countsForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_counts">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $counts_items = $data['about_counts'] ?? [];
                        if (!empty($counts_items)):
                            foreach ($counts_items as $index => $c): 
                        ?>
                            <div class="p-3 shadow-sm count-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="count_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['count_number'] ?? 'الرقم / القيمة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control count-number" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="+500">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['count_title'] ?? 'عنوان العداد / الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control count-title" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مشروع مكتمل">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['count_icon_image'] ?? 'أيقونة / صورة العداد', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($c['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($c['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Count Image" class="rounded-2 w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control count-file w-100" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="count-old-img" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($c['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_count'] ?? 'حذف العداد', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCountRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_count'] ?? 'إضافة عداد جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="countsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Partners Edit Modal (قسم الشركاء) -->
<div class="modal fade custom-modal" id="partnersEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-handshake text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_partners'] ?? 'إدارة الشركاء', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $partnersRaw = $currentSettings['partners'] ?? ($data['partners'] ?? null);
                if (empty($partnersRaw)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtPart = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'partners'");
                        $stmtPart->execute();
                        $partnersRaw = $stmtPart->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $partnersRaw = '{}';
                    }
                }
                $partnersAllLangs = is_string($partnersRaw) ? json_decode($partnersRaw, true) : (is_array($partnersRaw) ? $partnersRaw : []);
                if (!is_array($partnersAllLangs)) $partnersAllLangs = [];
                $curLangPart = $partnersAllLangs[$current_lang] ?? ($partnersAllLangs['ar'] ?? []);
                $modalPartTitle = $curLangPart['title'] ?? ($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا');
                $modalPartItems = $curLangPart['items'] ?? ($data['partners_items'] ?? []);
                if (!is_array($modalPartItems)) $modalPartItems = [];
                ?>
                <form id="partnersForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_partners">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="partners_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['team_section_title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                            <input type="text" class="form-control" name="partners[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$modalPartTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalPartItems as $index => $partner): ?>
                            <div class="p-3 shadow-sm partner-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-11">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($lang['partner_image'] ?? 'شعار / صورة الشريك', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($partner['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($partner['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Partner Image" class="rounded-2 w-100 h-100" style="object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control partner-file w-100" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="partner-old-img" name="partners[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($partner['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_partner'] ?? 'حذف الشريك', ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addPartnerRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_partner'] ?? 'إضافة شريك جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="partnersForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
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
        alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 320px; border-radius: 12px; border: none;';
        
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

    // 🌟 دالة إضافة صف جديد لخدمات صفحة من نحن (المستقلة) - مطابقة تماماً للستايل وتحتفظ بالبنية
    function addAboutServiceRow() {
        const container = document.getElementById('aboutServicesContainer');
        if (!container) return;
        const itemCount = container.querySelectorAll('.service-item-row').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm service-item-row mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'about_service_row_' + itemCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">عنوان الخدمة</label>
                    <input type="text" class="form-control about-service-title" name="about_services[${currentLang}][items][${itemCount}][title]" value="" placeholder="عنوان الخدمة">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">رابط الخدمة</label>
                    <input type="text" class="form-control about-service-url" name="about_services[${currentLang}][items][${itemCount}][url]" value="" placeholder="رابط الخدمة">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">الصورة / الأيقونة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control about-service-file w-100" name="about_services_img_${itemCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="about-service-old-img" name="about_services[${currentLang}][items][${itemCount}][img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // ربط زر الإضافة بحدث الضغط في المودل
    document.addEventListener('DOMContentLoaded', function() {
        const addBtn = document.getElementById('addAboutServiceBtn');
        if (addBtn) {
            addBtn.addEventListener('click', addAboutServiceRow);
        }
    });

    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        if (!container) return;
        const teamCount = container.querySelectorAll('.team-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm team-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="small fw-bold mb-1 text-secondary">الاسم الكامل</label>
                    <input type="text" class="form-control team-name" name="team[${currentLang}][items][${teamCount}][name]" placeholder="الاسم">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1 text-secondary">المسمى الوظيفي</label>
                    <input type="text" class="form-control team-role" name="team[${currentLang}][items][${teamCount}][role]" placeholder="المسمى الوظيفي">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">صورة العضو</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control team-file w-100" name="team_img_${teamCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="team-old-img" name="team[${currentLang}][items][${teamCount}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        if (!container) return;
        const countsCount = container.querySelectorAll('.count-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm count-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">الرقم / القيمة</label>
                    <input type="text" class="form-control count-number" name="counts[${countsCount}][number]" placeholder="+500">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">عنوان العداد / الوصف</label>
                    <input type="text" class="form-control count-title" name="counts[${countsCount}][title]" placeholder="مشروع مكتمل">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">أيقونة / صورة العداد</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control count-file w-100" name="count_img_${countsCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="count-old-img" name="counts[${countsCount}][old_img]" value="">
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        if (!container) return;
        const partnerCount = container.querySelectorAll('.partner-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm partner-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'partner_row_' + partnerCount;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-11">
                    <label class="form-label fw-semibold small text-secondary">شعار / صورة الشريك</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control partner-file w-100" name="partner_img_${partnerCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="partner-old-img" name="partners[${currentLang}][items][${partnerCount}][old_img]" value="">
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج الحفظ وإعادة الترقيم التلقائي بالـ AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';

                // 🌟 إعادة ترقيم صفوف خدمات صفحة من نحن المستقلة بدقة مطابقة للستايل الجديد
                form.querySelectorAll('.service-item-row').forEach((row, index) => {
                    const titleInput = row.querySelector('.about-service-title');
                    const urlInput = row.querySelector('.about-service-url');
                    const fileInput = row.querySelector('.about-service-file');
                    const oldImgInput = row.querySelector('.about-service-old-img');

                    if(titleInput) titleInput.name = `about_services[${currentLang}][items][${index}][title]`;
                    if(urlInput) urlInput.name = `about_services[${currentLang}][items][${index}][url]`;
                    if(fileInput) fileInput.name = `about_services_img_${index}`;
                    if(oldImgInput) oldImgInput.name = `about_services[${currentLang}][items][${index}][img]`;
                });

                // إعادة ترقيم صفوف الفريق
                form.querySelectorAll('.team-row-item').forEach((row, index) => {
                    const nameInput = row.querySelector('.team-name');
                    const roleInput = row.querySelector('.team-role');
                    const fileInput = row.querySelector('.team-file');
                    const oldImgInput = row.querySelector('.team-old-img');

                    if(nameInput) nameInput.name = `team[${currentLang}][items][${index}][name]`;
                    if(roleInput) roleInput.name = `team[${currentLang}][items][${index}][role]`;
                    if(fileInput) fileInput.name = `team_img_${index}`;
                    if(oldImgInput) oldImgInput.name = `team[${currentLang}][items][${index}][old_img]`;
                });

                // إعادة ترقيم صفوف الإحصائيات
                form.querySelectorAll('.count-row-item').forEach((row, index) => {
                    const numInput = row.querySelector('.count-number');
                    const titleInput = row.querySelector('.count-title');
                    const fileInput = row.querySelector('.count-file');
                    const oldImgInput = row.querySelector('.count-old-img');

                    if(numInput) numInput.name = `counts[${index}][number]`;
                    if(titleInput) titleInput.name = `counts[${index}][title]`;
                    if(fileInput) fileInput.name = `count_img_${index}`;
                    if(oldImgInput) oldImgInput.name = `counts[${index}][old_img]`;
                });

                // إعادة ترقيم صفوف الشركاء
                form.querySelectorAll('.partner-row-item').forEach((row, index) => {
                    const fileInput = row.querySelector('.partner-file');
                    const oldImgInput = row.querySelector('.partner-old-img');

                    if(fileInput) fileInput.name = `partner_img_${index}`;
                    if(oldImgInput) oldImgInput.name = `partners[${currentLang}][items][${index}][old_img]`;
                });

                const formData = new FormData(this);
                let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    csrfToken = formData.get('csrf_token') || '<?php echo htmlspecialchars($csrf_token ?? "", ENT_QUOTES, "UTF-8"); ?>';
                }
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
