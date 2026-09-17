<style>
    /* Modal Glassmorphism & Custom Styling */
    .custom-modal .modal-content { 
        border-radius: 24px; 
        border: none; 
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); 
        background: #ffffff;
        overflow: hidden; 
    }
    .custom-modal .modal-header { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 22px 28px; 
        border-bottom: 1px solid #f1f5f9; 
        background: #ffffff;
    }
    .custom-modal .modal-title { 
        margin: 0; 
        display: flex; 
        align-items: center; 
        gap: .75rem; 
        font-weight: 700; 
        color: #0f172a; 
        font-size: 1.2rem;
    }
    .custom-modal .btn-close { 
        margin: 0; 
        flex-shrink: 0; 
    }
    
    .custom-modal .modal-body {
        background: #f8fafc;
        padding: 28px;
    }

    /* Inputs */
    .custom-modal .form-control, .custom-modal .form-select { 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        height: 46px; 
        padding: 0 16px; 
        transition: all 0.2s ease; 
        width: 100%; 
        background-color: #ffffff;
        font-size: 0.95rem;
    }
    .custom-modal .form-control:focus, .custom-modal .form-select:focus { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); 
        background-color: #ffffff;
    }
    
    /* File Upload */
    .custom-modal input[type="file"].form-control { 
        padding: 9px 16px; 
        background: #ffffff; 
        cursor: pointer; 
        width: 100%; 
        height: auto;
    }

    /* تنسيق الفوتر الموحد */
    .custom-modal .modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        padding: 20px 28px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }

    .custom-modal .modal-footer button {
        flex: 1;
        height: 48px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
    }

    /* تنسيق زر الإلغاء */
    .btn-cancel {
        background-color: #f1f5f9;
        color: #475569;   
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    /* تنسيق زر الحفظ */
    .btn-premium { 
        background: linear-gradient(135deg, #2563eb, #3b82f6); 
        color: white; 
        border: none; 
        transition: all 0.3s ease; 
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-premium:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .btn-icon-trash { 
        width: 44px; 
        height: 44px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #fee2e2; 
        color: #dc2626; 
        border: none; 
        transition: all 0.2s ease;
    }
    .btn-icon-trash:hover { 
        background: #fecaca; 
        transform: translateY(-1px);
    }
</style>

<?php
// دالة مساعدة محلية لمنع أخطاء المصفوفات مع htmlspecialchars في المودالات
if (!function_exists('safe_admin_string')) {
    function safe_admin_string($val, $current_lang = 'ar') {
        if (is_array($val)) {
            return $val[$current_lang] ?? $val['ar'] ?? $val['de'] ?? reset($val) ?? '';
        }
        return (string)($val ?? '');
    }
}

// 1. جلب اللغة الحالية مباشرة من الجلسة أو النظام
$current_lang = $_SESSION['site_lang'] ?? 'de';

// 2. ضمان تحميل ملف الترجمة الصحيح هنا حصرياً ليكون مرئياً لكل المودلات
$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$lang = file_exists($lang_file) ? require $lang_file : [];
if (!is_array($lang)) {
    $lang = [];
}

// تحديد الاتجاه بناءً على اللغة الحالية
$is_rtl = ($current_lang === 'ar');
$modal_dir = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';
?>

<!-- 1. Social Links Modal -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-share-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo $lang['manage_social_platforms'] ?? 'إدارة منصات التواصل'; ?>
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $socialLinksData = is_array($data['social_links'] ?? null) ? $data['social_links'] : [];
                        foreach ($socialLinksData as $index => $link): 
                        ?>
                        <div class="p-3 shadow-sm social-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="small fw-bold mb-1 text-secondary <?php echo $modal_align; ?>"><?php echo $lang['platform_name'] ?? 'اسم المنصة'; ?></label>
                                    <input type="text" class="form-control social-name" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($link['name'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['name_placeholder'] ?? 'الاسم'; ?>">
                                </div>
                                <div class="col-md-8">
                                    <label class="small fw-bold mb-1 text-secondary <?php echo $modal_align; ?>"><?php echo $lang['platform_url'] ?? 'رابط المنصة'; ?></label>
                                    <input type="url" class="form-control social-url" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['url_placeholder'] ?? 'الرابط'; ?>">
                                </div>
                                <div class="col-md-11">
                                    <label class="small fw-bold mb-1 text-secondary <?php echo $modal_align; ?>"><?php echo $lang['platform_icon_image'] ?? 'أيقونة / صورة المنصة'; ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($link['img'])): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($link['img']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Social Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control social-file" name="social_img_<?php echo $index; ?>" accept="image/*">
                                    </div>
                                </div>
                                <input type="hidden" class="social-old-img" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($link['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('row_<?php echo $index; ?>')" title="<?php echo $lang['delete_platform'] ?? 'حذف المنصة'; ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addSocialRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo $lang['add_new_platform'] ?? 'إضافة منصة جديدة'; ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="socialLinksForm" class="btn-premium"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Logo Modal -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-image text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo $lang['manage_site_logo'] ?? 'تغيير شعار الموقع'; ?>
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <form id="logoEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? $_SESSION['csrf_token'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_logo">
                    
                    <!-- اختيار اللغة المخصصة للشعار -->
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold small text-secondary">
                            <?php echo $lang['logo_language'] ?? 'لغة الشعار (اللغة المستهدفة)'; ?>
                        </label>
                        <select class="form-select" name="logo_lang">
                            <option value="ar" <?php echo ($current_lang === 'ar') ? 'selected' : ''; ?>>العربية (Arabic)</option>
                            <option value="en" <?php echo ($current_lang === 'en') ? 'selected' : ''; ?>>الإنجليزية (English)</option>
                            <option value="de" <?php echo ($current_lang === 'de') ? 'selected' : ''; ?>>الألمانية (German)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="p-3 shadow-sm d-inline-block" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($site_logo_path ?? ''), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Site Logo" style="max-height: 90px; object-fit: contain;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <input type="file" class="form-control w-100" name="logo_img" accept="image/png, image/jpeg, image/webp" required>
                    </div>

                    <div id="logoAlertBox" class="alert d-none mt-2 rounded-3 border-0"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium" id="saveLogoBtn"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Announcement Modal -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-megaphone-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo $lang['announcement_settings'] ?? 'إعدادات لوحة الإعلانات'; ?>
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? $_SESSION['csrf_token'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_announcement">
                    <input type="hidden" name="announcement_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <?php 
                        // استخراج إعلان اللغة الحالية بأمان من مصفوفة اللغات
                        $currentAd = is_array($data['announcement'] ?? null) ? ($data['announcement'][$current_lang] ?? ($data['announcement'] ?? [])) : [];
                    ?>
                    <input type="hidden" name="old_ad_image" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['image_path'] ?? 'assets/img/default-ad.png', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-3 mb-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary" style="font-size: 0.95rem;">
                            <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo $lang['ad_status_timing'] ?? 'حالة الإعلان والتوقيت'; ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo $lang['display_status'] ?? 'حالة العرض'; ?></label>
                                <select class="form-select" name="status">
                                    <option value="Draft" <?php echo ((safe_admin_string($currentAd['status'] ?? '', $current_lang)) == 'Draft' ? 'selected' : ''); ?>><?php echo $lang['status_draft'] ?? 'مخفي (مسودة)'; ?></option>
                                    <option value="Published" <?php echo ((safe_admin_string($currentAd['status'] ?? '', $current_lang)) == 'Published' ? 'selected' : ''); ?>><?php echo $lang['status_published'] ?? 'نشط (يظهر للزوار)'; ?></option>
                                </select>
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo $lang['start_date'] ?? 'تاريخ البدء'; ?></label>
                                <input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', safe_admin_string($currentAd['start_date'] ?? '', $current_lang)); ?>">
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo $lang['end_date'] ?? 'تاريخ الانتهاء'; ?></label>
                                <input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', safe_admin_string($currentAd['end_date'] ?? '', $current_lang)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mb-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary" style="font-size: 0.95rem;">
                            <i class="bi bi-pencil-square text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo $lang['announcement_content'] ?? 'محتوى الإعلان'; ?>
                        </div>
                        <label class="small fw-bold mb-1"><?php echo $lang['ad_type'] ?? 'نوع الإعلان:'; ?></label>
                        <select class="form-select mb-3" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'text' ? 'selected' : ''); ?>><?php echo $lang['ad_type_text'] ?? 'نص متحرك (اختر هذا لنص سريع)'; ?></option>
                            <option value="image" <?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'image' ? 'selected' : ''); ?>><?php echo $lang['ad_type_image'] ?? 'صورة (بانر دعائي كامل)'; ?></option>
                        </select>

                        <div id="textEditor" class="<?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'text' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo $lang['announcement_text_label'] ?? 'نص الإعلان (الرسالة التي ستظهر للزوار):'; ?></label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars(safe_admin_string($currentAd['announcement_text'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo $lang['bg_color'] ?? 'لون الخلفية'; ?></label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['bg_color'] ?? '#f1f5f9', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo $lang['text_color'] ?? 'لون الخط'; ?></label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['text_color'] ?? '#1e293b', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo $lang['font_size'] ?? 'حجم الخط'; ?></label>
                                    <input type="number" class="form-control" name="font_size" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['font_size'] ?? '16', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'image' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo $lang['upload_ad_image'] ?? 'ارفع صورة الإعلان (يُفضل صيغة WebP أو PNG):'; ?></label>
                            <?php if (!empty($currentAd['image_path'])): ?>
                                <div class="mb-2 p-2 bg-light rounded border d-inline-block">
                                    <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($currentAd['image_path']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="معاينة الإعلان" class="img-thumbnail border-0 bg-transparent" style="max-height: 80px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="ad_image" style="height: auto; padding: 10px 16px;" accept="image/png, image/jpeg, image/webp">
                        </div>
                    </div>

                    <div class="p-3 shadow-sm <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-2 text-secondary" style="font-size: 0.95rem;">
                            <i class="bi bi-link-45deg text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo $lang['redirect_link'] ?? 'رابط التوجيه (اختياري)'; ?>
                        </div>
                        <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['link'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="announcementEditForm" class="btn-premium"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Menu Edit Modal -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list-nested text-primary"></i> <?php echo __('manage_main_menu') ?? 'إدارة القائمة الرئيسية'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="menuLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_menu">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $menuLinksData = is_array($menu_links ?? null) ? $menu_links : [];
                        foreach ($menuLinksData as $index => $link): 
                            $title_val = $link[$current_lang]['title'] ?? $link['title'] ?? '';
                        ?>
                        <div class="p-3 shadow-sm menu-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="menu_row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-5">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo __('link_title') ?? 'عنوان الرابط'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                    <input type="text" class="form-control menu-title" name="menu[<?php echo $index; ?>][<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($title_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo __('link_title_placeholder') ?? 'عنوان الرابط'; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo __('platform_url') ?? 'الرابط (URL)'; ?></label>
                                    <input type="text" class="form-control menu-url" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo __('url_placeholder') ?? 'الرابط (URL)'; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo __('order') ?? 'الترتيب'; ?></label>
                                    <input type="number" class="form-control menu-order" name="menu[<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars(safe_admin_string($link['order'] ?? $index, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo __('order') ?? 'الترتيب'; ?>">
                                </div>
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('menu_row_<?php echo $index; ?>')" title="<?php echo __('delete_link') ?? 'حذف الرابط'; ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMenuRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo __('add_new_link') ?? 'إضافة رابط جديد'; ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="menuLinksForm" class="btn-premium"><?php echo __('save_changes'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Lang Edit Modal -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-translate text-primary"></i> <?php echo __('manage_languages') ?? 'إدارة اللغات'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="langEditForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_languages">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="langRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $languagesData = is_array($data['languages'] ?? null) ? $data['languages'] : [];
                        if (!empty($languagesData)): 
                            foreach ($languagesData as $index => $lang): 
                        ?>
                                <div class="p-3 shadow-sm lang-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo __('language_name') ?? 'اسم اللغة'; ?></label>
                                            <input type="text" class="form-control lang-name" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($lang['name'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo __('language_name_placeholder') ?? 'اسم اللغة'; ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo __('language_url') ?? 'الرابط'; ?></label>
                                            <input type="text" class="form-control lang-url" name="lang[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($lang['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo __('language_url_placeholder') ?? 'الرابط'; ?>">
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('lang_row_<?php echo $index; ?>')" title="<?php echo __('delete_language') ?? 'حذف اللغة'; ?>"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo __('add_new_language') ?? 'إضافة لغة جديدة'; ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="langEditForm" class="btn-premium"><?php echo __('save_changes'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Hero Edit Modal -->
<div class="modal fade custom-modal" id="heroEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-gear text-primary"></i> <?php echo __('edit_hero_section') ?? 'تعديل قسم البداية (Hero)'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="heroEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <input type="hidden" name="current_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <?php 
                    $hero_raw = get_setting('hero', []);
                    if (is_string($hero_raw)) {
                        $hero = json_decode($hero_raw, true) ?? [];
                    } else {
                        $hero = is_array($hero_raw) ? $hero_raw : [];
                    }
                    
                    // جلب قيم اللغة الحالية للعرض في الحقول
                    $h_title    = $hero[$current_lang]['title'] ?? $hero['de']['title'] ?? $hero['title'] ?? '';
                    $h_desc     = $hero[$current_lang]['desc'] ?? $hero['de']['desc'] ?? $hero['desc'] ?? '';
                    $h_btn_text = $hero[$current_lang]['btn_text'] ?? $hero['de']['btn_text'] ?? $hero['btn_text'] ?? '';
                    $h_btn_url  = $hero['btn_url'] ?? '#';
                    $h_img      = $hero['img'] ?? 'assets/img/home/home1.png';
                    ?>
                    
                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo __('hero_title') ?? 'العنوان'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <input type="text" class="form-control" name="hero_title" value="<?php echo htmlspecialchars(safe_admin_string($h_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo __('hero_desc') ?? 'النص الوصفي'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <textarea class="form-control" name="hero_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($h_desc, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo __('hero_btn_text') ?? 'نص الزر'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <input type="text" class="form-control" name="hero_btn_text" value="<?php echo htmlspecialchars(safe_admin_string($h_btn_text, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo __('hero_btn_url') ?? 'رابط الزر'; ?></label>
                                <input type="text" class="form-control" name="hero_btn_url" value="<?php echo htmlspecialchars(safe_admin_string($h_btn_url, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo __('hero_bg_image') ?? 'صورة الخلفية'; ?></label>
                                
                                <?php if (!empty($h_img)): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <span class="d-block small text-muted mb-2"><?php echo __('current_image') ?? 'الصورة الحالية:'; ?></span>
                                        <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($h_img), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Current Hero Image" 
                                             class="img-thumbnail rounded-3 border-0 bg-transparent" 
                                             style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="form-control" name="hero_img" accept="image/*">
                                <input type="hidden" name="old_hero_img" value="<?php echo htmlspecialchars(safe_admin_string($h_img, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="heroEditForm" class="btn-premium"><?php echo __('save_changes'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 7. Services Edit Modal -->
<div class="modal fade custom-modal" id="servicesEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-briefcase text-primary"></i> تعديل الخدمات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="servicesEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_services">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars(safe_admin_string($data['services_section_title'] ?? 'خدماتنا المميزة', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم (اختياري)</label>
                            <textarea class="form-control" name="services_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($data['services_section_desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="servicesRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $servicesData = is_array($data['services'] ?? null) ? $data['services'] : [];
                        foreach ($servicesData as $index => $service): 
                        ?>
                            <div class="p-3 shadow-sm service-row-item" id="service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                        <input type="text" class="form-control service-title" name="services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($service['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                                        <input type="text" class="form-control service-url" name="services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($service['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                    </div>
                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary">الصورة / الأيقونة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($service['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($service['img']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Service Image" 
                                                         class="rounded-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control service-file" name="service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="service-old-img" name="services[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($service['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('service_row_<?php echo $index; ?>')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خدمة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="servicesEditForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 8. Choose Edit Modal -->
<div class="modal fade custom-modal" id="chooseEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل المميزات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="chooseForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_choose">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="choose_title" value="<?php echo htmlspecialchars(safe_admin_string($data['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم (اختياري)</label>
                            <textarea class="form-control" name="choose_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($data['choose_section_desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="chooseRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $chooseItemsData = is_array($data['choose_items'] ?? null) ? $data['choose_items'] : [];
                        if (!empty($chooseItemsData)): 
                            foreach ($chooseItemsData as $index => $item): 
                        ?>
                                <div class="p-3 shadow-sm choose-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="choose_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                            <input type="text" class="form-control choose-title" name="choose[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($item['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                                            <input type="text" class="form-control choose-desc" name="choose[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars(safe_admin_string($item['desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                        </div>

                                        <div class="col-md-11">
                                            <label class="small fw-bold mb-1 text-secondary">الأيقونة / الصورة</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($item['img'])): ?>
                                                    <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                        <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($item['img']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                             alt="Choose Item Icon" 
                                                             class="rounded-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>

                                                <input type="file" class="form-control choose-file" name="choose_img_<?php echo $index; ?>" accept="image/*">
                                            </div>
                                            <input type="hidden" class="choose-old-img" name="choose[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($item['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('choose_row_<?php echo $index; ?>')" title="حذف الميزة">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addChooseRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ميزة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="chooseForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 9. Reviews Edit Modal -->
<div class="modal fade custom-modal" id="reviewsEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-play-btn text-primary"></i> إدارة فيديوهات العملاء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="reviewsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_reviews">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="reviews_title" value="<?php echo htmlspecialchars(safe_admin_string($data['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="reviewsRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $reviewsItemsData = is_array($data['reviews_items'] ?? null) ? $data['reviews_items'] : [];
                        foreach ($reviewsItemsData as $index => $review): 
                        ?>
                            <div class="p-3 shadow-sm review-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="rev_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary">رابط الفيديو (Embed URL)</label>
                                        <input type="text" class="form-control review-url" name="reviews[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($review['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="رابط اليوتيوب (Embed URL)">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('rev_row_<?php echo $index; ?>')" title="حذف الفيديو">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addReviewRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة فيديو جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reviewsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 10. Guide Edit Modal -->
<div class="modal fade custom-modal" id="guideEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-book text-primary"></i> إدارة الدليل الشامل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="guide_title" value="<?php echo htmlspecialchars(safe_admin_string($data['guide_title'] ?? 'دليل بيتهوفن الشامل', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="guide_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($data['guide_desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $guideItemsData = is_array($data['guide_items'] ?? null) ? $data['guide_items'] : [];
                        foreach ($guideItemsData as $index => $item): 
                        ?>
                            <div class="p-3 shadow-sm guide-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="guide_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">عنوان المقال</label>
                                        <input type="text" class="form-control guide-title" name="guide[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($item['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان المقال">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">رابط الصفحة</label>
                                        <input type="text" class="form-control guide-url" name="guide[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($item['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="رابط الصفحة">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                                        <input type="text" class="form-control guide-desc" name="guide[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars(safe_admin_string($item['desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                    </div>

                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary">الصورة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($item['img']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Guide Item Image" 
                                                         class="rounded-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control guide-file" name="guide_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="guide-old-img" name="guide[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($item['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_row_<?php echo $index; ?>')" title="حذف المقال">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة مقال جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 11. FAQ Edit Modal -->
<div class="modal fade custom-modal" id="faqEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-question-circle text-primary"></i> إدارة الأسئلة الشائعة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="faqForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_faq">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="faq_title" value="<?php echo htmlspecialchars(safe_admin_string($data['faq_title'] ?? 'الأسئلة الشائعة', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="faqRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $faqItemsData = is_array($data['faq_items'] ?? null) ? $data['faq_items'] : [];
                        foreach ($faqItemsData as $index => $item): 
                        ?>
                            <div class="p-3 shadow-sm faq-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="faq_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">السؤال</label>
                                        <input type="text" class="form-control faq-question" name="faq[<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars(safe_admin_string($item['question'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="السؤال">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary">الإجابة</label>
                                        <input type="text" class="form-control faq-answer" name="faq[<?php echo $index; ?>][answer]" value="<?php echo htmlspecialchars(safe_admin_string($item['answer'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="الإجابة">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('faq_row_<?php echo $index; ?>')" title="حذف السؤال">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addFaqRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة سؤال جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="faqForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 12. Footer Edit Modal -->
<div class="modal fade custom-modal" id="footerEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-layout-wtf text-primary"></i> إدارة الفوتر بالكامل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="footerForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_footer">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><i class="bi bi-chat-dots me-1"></i> إعدادات قسم الاستشارة</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary">عنوان الاستشارة</label>
                                        <input type="text" class="form-control consult-title" name="consult_title" value="<?php echo htmlspecialchars(safe_admin_string($data['consult_title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small fw-bold mb-1 text-secondary">وصف الاستشارة</label>
                                        <input type="text" class="form-control consult-desc" name="consult_desc" value="<?php echo htmlspecialchars(safe_admin_string($data['consult_desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الأول</h6>
                                <label class="small fw-bold mb-1 text-secondary">وصف الفوتر:</label>
                                <textarea class="form-control footer-desc" name="footer_desc" rows="6" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($data['footer_desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الثاني</h6>
                                <label class="small fw-bold mb-1 text-secondary">عنوان العمود</label>
                                <input type="text" class="form-control mb-3 footer-col2-title" name="footer_col2_title" value="<?php echo htmlspecialchars(safe_admin_string($data['footer_col2_title'] ?? 'روابط سريعة', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="p-3 bg-light rounded-3 text-muted small border" style="border-color: #e2e8f0 !important;">
                                    <i class="bi bi-info-circle me-1"></i> يتم جلب الروابط تلقائياً من <b>القائمة الرئيسية (Menu)</b>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الثالث (التواصل)</h6>
                                <div class="mb-3" style="max-width: 400px;">
                                    <label class="small fw-bold mb-1 text-secondary">عنوان العمود</label>
                                    <input type="text" class="form-control footer-col3-title" name="footer_col3_title" value="<?php echo htmlspecialchars(safe_admin_string($data['footer_col3_title'] ?? 'تواصل معنا', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                
                                <div id="col3LinksContainer" class="d-flex flex-column gap-3">
                                    <?php 
                                    $footerCol3LinksData = is_array($data['footer_col3_links'] ?? null) ? $data['footer_col3_links'] : [];
                                    foreach($footerCol3LinksData as $i => $link): 
                                    ?>
                                        <div class="p-3 shadow-sm footer-col3-item" id="col3_<?php echo $i; ?>" style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;">
                                            <div class="row g-3 align-items-center">
                                                
                                                <div class="col-md-4">
                                                    <label class="small fw-bold mb-1 text-secondary">اسم الوسيلة</label>
                                                    <input type="text" name="col3[<?php echo $i; ?>][title]" class="form-control form-control-sm bg-white footer-col3-title-input" value="<?php echo htmlspecialchars(safe_admin_string($link['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الوسيلة (مثلاً: واتساب)">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                                                    <input type="text" name="col3[<?php echo $i; ?>][url]" class="form-control form-control-sm bg-white footer-col3-url-input" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="small fw-bold mb-1 text-secondary">الأيقونة / الصورة</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if (!empty($link['img'])): ?>
                                                            <div class="p-1 bg-white rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                                <img src="<?php echo htmlspecialchars(safe_admin_string(get_image_url($link['img']), $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                                     alt="Contact Icon" 
                                                                     class="rounded-2" 
                                                                     style="width: 36px; height: 36px; object-fit: cover;">
                                                            </div>
                                                        <?php endif; ?>
                                                        <input type="file" name="col3_img_<?php echo $i; ?>" class="form-control form-control-sm bg-white footer-col3-file" accept="image/*">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 text-center pt-3">
                                                    <button type="button" class="btn btn-outline-danger btn-sm p-2 w-100 mx-auto" onclick="removeRow('col3_<?php echo $i; ?>')" style="border-radius: 8px;" title="حذف وسيلة التواصل">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" class="footer-col3-old-img" name="col3[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($link['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <button type="button" class="btn w-100 mt-3 py-2" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCol3Link()">
                                    <i class="bi bi-plus-circle me-1"></i> إضافة وسيلة تواصل
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="footerForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = 'alert-success', icon = 'bi-check-circle-fill', title = 'تم بنجاح!';
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
                <div><strong>${title}</strong><div class="small">${message}</div></div>
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

    let socialCount = <?php echo is_array($data['social_links'] ?? null) ? count($data['social_links']) : 0; ?>;
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 social-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'row_' + socialCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">اسم المنصة</label>
                    <input type="text" class="form-control social-name" name="social[${socialCount}][name]" placeholder="الاسم">
                </div>
                <div class="col-md-8">
                    <label class="small fw-bold mb-1 text-secondary">رابط المنصة</label>
                    <input type="url" class="form-control social-url" name="social[${socialCount}][url]" placeholder="الرابط">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">أيقونة / صورة المنصة</label>
                    <input type="file" class="form-control social-file" name="social_img_${socialCount}" accept="image/*">
                </div>
                <input type="hidden" class="social-old-img" name="social[${socialCount}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('row_${socialCount}')" title="حذف المنصة"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        socialCount++;
    }

    let menuCount = <?php echo is_array($menu_links ?? null) ? count($menu_links) : 0; ?>;
    function addMenuRow() {
        const container = document.getElementById('menuRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 menu-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'menu_row_' + menuCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">عنوان الرابط</label>
                    <input type="text" class="form-control menu-title" name="menu[${menuCount}][title]" placeholder="عنوان الرابط">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">الرابط (URL)</label>
                    <input type="text" class="form-control menu-url" name="menu[${menuCount}][url]" placeholder="الرابط (URL)">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold mb-1 text-secondary">الترتيب</label>
                    <input type="number" class="form-control menu-order" name="menu[${menuCount}][order]" value="${menuCount}" placeholder="الترتيب">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('menu_row_${menuCount}')" title="حذف الرابط"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        menuCount++;
    }

    let langCount = <?php echo is_array($data['languages'] ?? null) ? count($data['languages']) : 0; ?>;
    function addLangRow() {
        const container = document.getElementById('langRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 lang-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'lang_row_' + langCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">اسم اللغة</label>
                    <input type="text" class="form-control lang-name" name="lang[${langCount}][name]" placeholder="اسم اللغة">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                    <input type="text" class="form-control lang-url" name="lang[${langCount}][url]" placeholder="الرابط">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('lang_row_${langCount}')" title="حذف اللغة"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        langCount++;
    }

    let serviceCount = <?php echo is_array($data['services'] ?? null) ? count($data['services']) : 0; ?>;
    function addServiceRow() {
        const container = document.getElementById('servicesRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'service_row_' + serviceCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control" name="services[${serviceCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-4"><input type="text" class="form-control" name="services[${serviceCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-4"><input type="file" class="form-control" name="service_img_${serviceCount}"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('service_row_${serviceCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        serviceCount++;
    }

    let chooseCount = <?php echo is_array($data['choose_items'] ?? null) ? count($data['choose_items']) : 0; ?>;
    function addChooseRow() {
        const container = document.getElementById('chooseRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 choose-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'choose_row_' + chooseCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                    <input type="text" class="form-control choose-title" name="choose[${chooseCount}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                    <input type="text" class="form-control choose-desc" name="choose[${chooseCount}][desc]" placeholder="الوصف">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">الأيقونة / الصورة</label>
                    <input type="file" class="form-control choose-file" name="choose_img_${chooseCount}" accept="image/*">
                </div>
                <input type="hidden" class="choose-old-img" name="choose[${chooseCount}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('choose_row_${chooseCount}')" title="حذف الميزة"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        chooseCount++;
    }

    let reviewCount = <?php echo is_array($data['reviews_items'] ?? null) ? count($data['reviews_items']) : 0; ?>;
    function addReviewRow() {
        const container = document.getElementById('reviewsRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 review-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'rev_row_' + reviewCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">رابط الفيديو (Embed URL)</label>
                    <input type="text" class="form-control review-url" name="reviews[${reviewCount}][url]" placeholder="رابط اليوتيوب (Embed URL)">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('rev_row_${reviewCount}')" title="حذف الفيديو"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        reviewCount++;
    }

    let guideCount = <?php echo is_array($data['guide_items'] ?? null) ? count($data['guide_items']) : 0; ?>;
    function addGuideRow() {
        const container = document.getElementById('guideRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 guide-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'guide_row_' + guideCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">عنوان المقال</label>
                    <input type="text" class="form-control guide-title" name="guide[${guideCount}][title]" placeholder="عنوان المقال">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">رابط الصفحة</label>
                    <input type="text" class="form-control guide-url" name="guide[${guideCount}][url]" placeholder="رابط الصفحة">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                    <input type="text" class="form-control guide-desc" name="guide[${guideCount}][desc]" placeholder="الوصف">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">الصورة</label>
                    <input type="file" class="form-control guide-file" name="guide_img_${guideCount}" accept="image/*">
                </div>
                <input type="hidden" class="guide-old-img" name="guide[${guideCount}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_row_${guideCount}')" title="حذف المقال"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        guideCount++;
    }

    let faqCount = <?php echo is_array($data['faq_items'] ?? null) ? count($data['faq_items']) : 0; ?>;
    function addFaqRow() {
        const container = document.getElementById('faqRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 faq-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'faq_row_' + faqCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">السؤال</label>
                    <input type="text" class="form-control faq-question" name="faq[${faqCount}][question]" placeholder="السؤال">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">الإجابة</label>
                    <input type="text" class="form-control faq-answer" name="faq[${faqCount}][answer]" placeholder="الإجابة">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('faq_row_${faqCount}')" title="حذف السؤال"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        faqCount++;
    }

    let col3Count = <?php echo is_array($data['footer_col3_links'] ?? null) ? count($data['footer_col3_links']) : 0; ?>;
    function addCol3Link() {
        const container = document.getElementById('col3LinksContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 footer-col3-item';
        div.style.cssText = 'background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;';
        div.id = 'col3_' + col3Count;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">اسم الوسيلة</label>
                    <input type="text" name="col3[${col3Count}][title]" class="form-control form-control-sm bg-white footer-col3-title-input" placeholder="اسم الوسيلة (مثلاً: واتساب)">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                    <input type="text" name="col3[${col3Count}][url]" class="form-control form-control-sm bg-white footer-col3-url-input" placeholder="الرابط">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1 text-secondary">الأيقونة / الصورة</label>
                    <input type="file" name="col3_img_${col3Count}" class="form-control form-control-sm bg-white footer-col3-file" accept="image/*">
                </div>
                <input type="hidden" class="footer-col3-old-img" name="col3[${col3Count}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn btn-outline-danger btn-sm p-2 w-100 mx-auto" onclick="removeRow('col3_${col3Count}')" style="border-radius: 8px;" title="حذف وسيلة التواصل"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        col3Count++;
    }

    function toggleAdContent(val) { 
        const textEditor = document.getElementById('textEditor');
        const imageEditor = document.getElementById('imageEditor');
        if(textEditor) textEditor.classList.toggle('d-none', val !== 'text'); 
        if(imageEditor) imageEditor.classList.toggle('d-none', val !== 'image'); 
    }

    document.querySelectorAll('.custom-modal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
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
