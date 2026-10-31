<?php
// /src/Views/admin/admin_header_modals.php
?>
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
if (!function_exists('safe_admin_string')) {
    function safe_admin_string($val, $current_lang = 'ar') {
        if (is_array($val)) {
            return $val[$current_lang] ?? $val['ar'] ?? $val['de'] ?? reset($val) ?? '';
        }
        return (string)($val ?? '');
    }
}

$current_lang = $_SESSION['site_lang'] ?? 'de';

$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$lang = file_exists($lang_file) ? require $lang_file : [];
if (!is_array($lang)) {
    $lang = [];
}

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
                    <?php echo $lang['manage_social_platforms'] ?? 'إدارة منصات التواصل'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $rawSocial = $currentSettings['social_links'] ?? ($data['social_links'] ?? []);
                        $socialLinksData = is_array($rawSocial) ? $rawSocial : [];

                        foreach ($socialLinksData as $index => $link): 
                        ?>
                        <div class="p-3 shadow-sm social-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['platform_name'] ?? 'اسم المنصة'; ?></label>
                                    <input type="text" class="form-control social-name" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($link['name'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['name_placeholder'] ?? 'الاسم'; ?>">
                                </div>
                                <div class="col-md-8 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['platform_url'] ?? 'رابط المنصة'; ?></label>
                                    <input type="url" class="form-control social-url" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['url_placeholder'] ?? 'الرابط'; ?>">
                                </div>
                                <div class="col-md-11 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['platform_icon_image'] ?? 'أيقونة / صورة المنصة'; ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($link['img'])): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($link['img']) : $link['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Social Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control social-file" name="social_img_<?php echo $index; ?>" accept="image/*">
                                    </div>
                                </div>
                                <input type="hidden" class="social-old-img" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($link['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="<?php echo $lang['delete_platform'] ?? 'حذف المنصة'; ?>"><i class="bi bi-trash"></i></button>
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
                    <?php echo $lang['manage_site_logo'] ?? 'تغيير شعار الموقع'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <form id="logoEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? $_SESSION['csrf_token'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_logo">
                    <input type="hidden" name="logo_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="mb-4">
                        <div class="p-3 shadow-sm d-inline-block" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <?php 
                            // جلب آمن ومحصن ضد اختلاف أنواع البيانات (String / Array) لمنع خطأ json_decode
                            $rawLogoSetting = $currentSettings['site_logo_path'] ?? ($data['site_logo_path'] ?? ($currentSettings['site_logo'] ?? ''));
                            
                            if (is_string($rawLogoSetting)) {
                                $logosDecoded = json_decode($rawLogoSetting, true);
                            } elseif (is_array($rawLogoSetting)) {
                                $logosDecoded = $rawLogoSetting;
                            } else {
                                $logosDecoded = [];
                            }

                            $activeLogo = '';
                            if (is_array($logosDecoded)) {
                                $activeLogo = $logosDecoded[$current_lang] ?? ($logosDecoded['ar'] ?? ($logosDecoded['de'] ?? reset($logosDecoded) ?? ''));
                            } else {
                                $activeLogo = is_string($rawLogoSetting) ? $rawLogoSetting : '';
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($activeLogo) : $activeLogo, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Site Logo" style="max-height: 90px; object-fit: contain;">
                        </div>
                    </div>
                    
                    <div class="mb-3 text-start" dir="<?php echo $modal_dir; ?>">
                        <label class="small fw-bold mb-2 text-secondary d-block <?php echo $modal_align; ?>">
                            <?php echo $lang['upload_ad_image'] ?? 'اختر صورة الشعار:'; ?>
                        </label>
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
                    <?php echo $lang['announcement_settings'] ?? 'إعدادات لوحة الإعلانات'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? $_SESSION['csrf_token'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_announcement">
                    <input type="hidden" name="announcement_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <?php 
                        // فحص وتحصين آمن للنص الخام القادم من قاعدة البيانات للإعلانات متعددة اللغات
                        $rawAdSetting = $currentSettings['announcement'] ?? ($data['announcement'] ?? '');
                        $decodedAds = is_string($rawAdSetting) ? json_decode($rawAdSetting, true) : (is_array($rawAdSetting) ? $rawAdSetting : []);
                        
                        $currentAd = [];
                        if (is_array($decodedAds)) {
                            $currentAd = $decodedAds[$current_lang] ?? ($decodedAds['ar'] ?? (isset($decodedAds['status']) ? $decodedAds : []));
                        }
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
                            <?php echo $lang['announcement_content'] ?? 'محتوى الإعلان'; ?> (<?php echo strtoupper($current_lang); ?>)
                        </div>
                        <label class="small fw-bold mb-1"><?php echo $lang['ad_type'] ?? 'نوع الإعلان:'; ?></label>
                        <select class="form-select mb-3" name="type" id="adTypeSelect" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'text' ? 'selected' : ''); ?>><?php echo $lang['ad_type_text'] ?? 'نص متحرك'; ?></option>
                            <option value="image" <?php echo ((safe_admin_string($currentAd['type'] ?? 'text', $current_lang)) == 'image' ? 'selected' : ''); ?>><?php echo $lang['ad_type_image'] ?? 'صورة (بانر دعائي)'; ?></option>
                        </select>

                        <?php $adTypeVal = safe_admin_string($currentAd['type'] ?? 'text', $current_lang); ?>
                        <div id="textEditor" class="<?php echo ($adTypeVal === 'text' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo $lang['announcement_text_label'] ?? 'نص الإعلان:'; ?></label>
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

                        <div id="imageEditor" class="<?php echo ($adTypeVal === 'image' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo $lang['upload_ad_image'] ?? 'ارفع صورة الإعلان:'; ?></label>
                            <?php if (!empty($currentAd['image_path']) && $currentAd['image_path'] !== 'assets/img/default-ad.png'): ?>
                                <div class="mb-2 p-2 bg-light rounded border d-inline-block">
                                    <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($currentAd['image_path']) : $currentAd['image_path'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Ad Preview" class="img-thumbnail border-0 bg-transparent" style="max-height: 80px; object-fit: contain;">
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
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-list-nested text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo function_exists('__') ? (__('manage_main_menu') ?: 'إدارة القائمة الرئيسية') : 'إدارة القائمة الرئيسية'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="menuLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_menu">
                    <input type="hidden" name="menu_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $menuLinksData = is_array($menu_links ?? null) ? $menu_links : [];
                        foreach ($menuLinksData as $index => $link): 
                            $title_val = '';
                            if (isset($link[$current_lang]['title'])) {
                                $title_val = $link[$current_lang]['title'];
                            } elseif (isset($link['title'])) {
                                $title_val = is_array($link['title']) ? ($link['title'][$current_lang] ?? reset($link['title'])) : $link['title'];
                            } elseif (isset($link['ar']['title'])) {
                                $title_val = $link['ar']['title'];
                            }

                            $url_val   = $link['url'] ?? '';
                            $order_val = $link['order'] ?? $index;
                        ?>
                        <div class="p-3 shadow-sm menu-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="menu_row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['title'] ?? 'عنوان الرابط'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                    <input type="text" class="form-control" name="menu[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars((string)$title_val, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['title'] ?? 'العنوان'; ?>">
                                </div>
                                <div class="col-md-5 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['url'] ?? 'الرابط (URL)'; ?></label>
                                    <input type="text" class="form-control" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars((string)$url_val, ENT_QUOTES, 'UTF-8'); ?>" placeholder="url">
                                </div>
                                <div class="col-md-1 text-center pt-3">
                                    <input type="hidden" name="menu[<?php echo $index; ?>][order]" value="<?php echo $order_val; ?>">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('menu_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMenuRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo $lang['add_new_link'] ?? 'إضافة رابط جديد'; ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="menuLinksForm" class="btn-premium"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Lang Edit Modal -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-translate text-primary"></i> 
                    <?php echo htmlspecialchars($lang['manage_languages'] ?? ($current_lang === 'ar' ? 'إدارة اللغات' : ($current_lang === 'de' ? 'Sprachen verwalten' : 'Manage Languages')), ENT_QUOTES, 'UTF-8'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="langEditForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_languages">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="langRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $languagesRawData = $data['languages'] ?? (function_exists('get_setting') ? get_setting('languages', []) : []);
                        if (is_string($languagesRawData)) {
                            $languagesRawData = json_decode($languagesRawData, true);
                        }
                        $languagesData = is_array($languagesRawData) ? $languagesRawData : [];

                        $t_code   = $lang['lang_code'] ?? ($current_lang === 'ar' ? 'رمز اللغة (Code)' : ($current_lang === 'de' ? 'Sprachcode' : 'Language Code'));
                        $t_name   = $lang['lang_name'] ?? ($current_lang === 'ar' ? 'اسم اللغة' : ($current_lang === 'de' ? 'Sprachname' : 'Language Name'));
                        $t_status = $lang['status'] ?? ($current_lang === 'ar' ? 'الحالة' : ($current_lang === 'de' ? 'Status' : 'Status'));
                        $t_delete = $lang['delete_lang'] ?? ($current_lang === 'ar' ? 'حذف اللغة' : ($current_lang === 'de' ? 'Sprache löschen' : 'Delete Language'));

                        if (!empty($languagesData)): 
                            foreach ($languagesData as $index => $langItem): 
                                $code_val   = $langItem['code'] ?? '';
                                $name_val   = $langItem['name'] ?? '';
                                $active_val = isset($langItem['active']) ? (bool)$langItem['active'] : true;
                        ?>
                                <div class="p-3 shadow-sm lang-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-4">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($t_code, ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control lang-code" name="lang[<?php echo $index; ?>][code]" value="<?php echo htmlspecialchars(safe_admin_string($code_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: fr">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($t_name, ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control lang-name" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($name_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: Français">
                                        </div>
                                        <div class="col-md-2 text-center pt-3">
                                            <label class="small fw-bold mb-1 text-secondary d-block"><?php echo htmlspecialchars($t_status, ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="hidden" name="lang[<?php echo $index; ?>][active]" value="0">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input lang-active" type="checkbox" name="lang[<?php echo $index; ?>][active]" value="1" <?php echo $active_val ? 'checked' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('lang_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($t_delete, ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        endif; 
                        ?>
                    </div>

                    <?php 
                    $t_add_btn = $lang['add_new_language'] ?? ($current_lang === 'ar' ? 'إضافة لغة جديدة' : ($current_lang === 'de' ? 'Neue Sprache hinzufügen' : 'Add New Language'));
                    ?>
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($t_add_btn, ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="langEditForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? ($current_lang === 'ar' ? 'حفظ التغييرات' : ($current_lang === 'de' ? 'Änderungen speichern' : 'Save Changes')), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? ($current_lang === 'ar' ? 'إلغاء' : ($current_lang === 'de' ? 'Abbrechen' : 'Cancel')), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Hero Edit Modal -->
<div class="modal fade custom-modal" id="heroEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo $lang['edit_hero_section'] ?? 'تعديل قسم البداية (Hero)'; ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="heroEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="hero_lang" value="<?php echo htmlspecialchars($current_lang); ?>">
                    
                    <?php 
                    $hero_raw = $currentSettings['hero'] ?? ($data['hero'] ?? '');
                    if (is_string($hero_raw)) {
                        $hero_all = json_decode($hero_raw, true) ?? [];
                    } else {
                        $hero_all = is_array($hero_raw) ? $hero_raw : [];
                    }
                    
                    $h = isset($hero_all[$current_lang]) && is_array($hero_all[$current_lang]) ? $hero_all[$current_lang] : [];
                    
                    $val_title   = $h['title'] ?? ($hero_all['title'] ?? '');
                    $val_desc    = $h['desc'] ?? ($hero_all['desc'] ?? '');
                    $val_btn_txt = $h['btn_text'] ?? ($hero_all['btn_text'] ?? '');
                    $val_btn_url = $h['btn_url'] ?? ($hero_all['btn_url'] ?? '#');
                    
                    $current_img_val = $h['img'] ?? ($hero_all['img'] ?? 'assets/img/hero-bg.jpg');
                    ?>
                    
                    <input type="hidden" name="old_hero_img" value="<?php echo htmlspecialchars($current_img_val, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo $lang['hero_title'] ?? $lang['title'] ?? 'العنوان'; ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($val_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo $lang['hero_desc'] ?? $lang['description'] ?? 'النص الوصفي'; ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="hero[<?php echo $current_lang; ?>][desc]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($val_desc, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo $lang['hero_btn_text'] ?? $lang['button_text'] ?? 'نص الزر'; ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars(safe_admin_string($val_btn_txt, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo $lang['hero_btn_url'] ?? $lang['button_url'] ?? 'رابط الزر'; ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars(safe_admin_string($val_btn_url, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo $lang['hero_bg_image'] ?? $lang['background_image'] ?? 'صورة الخلفية'; ?>
                                </label>
                                
                                <?php if (!empty($current_img_val) && $current_img_val !== 'assets/img/hero-bg.jpg'): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <span class="d-block small text-muted mb-2"><?php echo $lang['current_image'] ?? 'الصورة الحالية:'; ?></span>
                                        <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($current_img_val) : $current_img_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Current Hero Image" 
                                             class="img-thumbnail rounded-3 border-0 bg-transparent" 
                                             style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="form-control" name="hero_img" accept="image/*">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="heroEditForm" class="btn-premium"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 7. Services Edit Modal -->
<div class="modal fade custom-modal" id="servicesEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-briefcase text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['edit_services_section'] ?? ($current_lang === 'ar' ? 'تعديل قسم الخدمات' : ($current_lang === 'de' ? 'Dienste-Schnittstelle bearbeiten' : 'Edit Services Section')), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $services_raw = $currentSettings['services'] ?? ($data['services'] ?? '');
                $services_all = is_string($services_raw) ? (json_decode($services_raw, true) ?? []) : (is_array($services_raw) ? $services_raw : []);
                
                $s = isset($services_all[$current_lang]) && is_array($services_all[$current_lang]) ? $services_all[$current_lang] : [];
                
                $sec_title     = $s['section_title'] ?? ($s['title'] ?? ($services_all['section_title'] ?? ''));
                $sec_subtitle  = $s['section_subtitle'] ?? ($s['desc'] ?? ($services_all['section_subtitle'] ?? ''));
                $service_items = $s['items'] ?? ($services_all['items'] ?? (is_array($s) && isset($s[0]) ? $s : []));
                if (!is_array($service_items)) $service_items = [];
                ?>

                <form id="servicesEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="services_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['services_section_title'] ?? 'عنوان القسم الرئيسي'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <input type="text" class="form-control" name="services[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars(safe_admin_string($sec_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['services_section_title'] ?? 'عنوان القسم الرئيسي'; ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['services_section_desc'] ?? 'العنوان الفرعي / الوصف'; ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <textarea class="form-control" name="services[<?php echo $current_lang; ?>][section_subtitle]" rows="2" style="height: auto; padding: 12px 16px;" placeholder="<?php echo $lang['services_section_desc'] ?? 'العنوان الفرعي / الوصف'; ?>"><?php echo htmlspecialchars(safe_admin_string($sec_subtitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo $lang['added_services'] ?? 'الخدمات المضافة'; ?></h6>
                    <div id="servicesRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($service_items as $index => $item): ?>
                            <div class="p-3 shadow-sm service-row-item" id="service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['service_title'] ?? 'عنوان الخدمة'; ?></label>
                                        <input type="text" class="form-control service-title" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['service_title'] ?? 'عنوان الخدمة'; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['service_url'] ?? 'رابط الخدمة'; ?></label>
                                        <input type="text" class="form-control service-url" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $lang['service_url'] ?? 'رابط الخدمة'; ?>">
                                    </div>

                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo $lang['image_or_icon'] ?? 'الصورة / الأيقونة'; ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control service-file" name="service_img_<?php echo $index; ?>" accept="image/*">
                                            <?php if (!empty($item['img'])): ?>
                                                <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($item['img']) : $item['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Service Preview" class="rounded border" style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <input type="hidden" class="service-old-img" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? $item['old_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('service_row_<?php echo $index; ?>')" title="<?php echo $lang['delete_service'] ?? 'حذف الخدمة'; ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo $lang['add_new_service'] ?? 'إضافة خدمة جديدة'; ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="servicesEditForm" class="btn-premium"><?php echo $lang['save_changes'] ?? 'حفظ التغييرات'; ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo $lang['cancel'] ?? 'إلغاء'; ?></button>
            </div>
        </div>
    </div>
</div>

                <?php 
                // جلب آمن ومباشر للبيانات مع البرمجة الدفاعية
                $choose_data_modal = $choose_section ?? [];
                
                if (empty($choose_data_modal)) {
                    if (isset($getLocalizedData) && is_callable($getLocalizedData)) {
                        $choose_data_modal = $getLocalizedData('choose', []);
                    } elseif (function_exists('get_setting')) {
                        $raw = get_setting('choose', '{}');
                        // تطبيق البرمجة الدفاعية: فك الـ JSON فقط إذا كان نصاً، وإلا فخذه كما هو
                        $decodedChoose = is_string($raw) ? (json_decode($raw, true) ?? []) : (is_array($raw) ? $raw : []);
                        $curLang = $current_lang ?? 'ar';
                        $choose_data_modal = $decodedChoose[$curLang] ?? ($decodedChoose['ar'] ?? []);
                    }
                }
                
                $choose_sec_title    = $choose_data_modal['section_title'] ?? ($choose_data_modal['title'] ?? '');
                $choose_sec_subtitle = $choose_data_modal['section_subtitle'] ?? ($choose_data_modal['desc'] ?? '');
                $choose_itemsList    = $choose_data_modal['items'] ?? [];
                
                if (!is_array($choose_itemsList)) {
                    $choose_itemsList = [];
                }
                ?>

<!-- 9. Reviews Edit Modal -->
<div class="modal fade custom-modal" id="reviewsEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir ?? ($is_rtl ? 'rtl' : 'ltr'); ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-play-btn text-primary <?php echo !empty($is_rtl) ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_customer_videos'] ?? 'إدارة فيديوهات العملاء', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات التقييمات للغة الحالية مباشرة عبر الدالة المركزية المعتمدة
                $rModalData = isset($getLocalizedData) ? $getLocalizedData('reviews_items', []) : [];
                
                $curTitle  = $rModalData['title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
                $curVideos = $rModalData['items'] ?? [];
                if (!is_array($curVideos)) {
                    $curVideos = [];
                }
                ?>

                <form id="reviewsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_reviews">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang ?? 'ar'), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="reviews_lang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان قسم التقييمات -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="<?php echo $modal_align ?? ''; ?>">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['section_main_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                            </label>
                            <input type="text" class="form-control" name="reviews[<?php echo $current_lang ?? 'ar'; ?>][title]" value="<?php echo htmlspecialchars((string)$curTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <!-- الفيديوهات -->
                    <h6 class="fw-bold mb-3 <?php echo $modal_align ?? ''; ?>">
                        <?php echo htmlspecialchars($lang['added_videos'] ?? 'الفيديوهات المضافة', ENT_QUOTES, 'UTF-8'); ?>
                    </h6>
                    <div id="reviewsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($curVideos as $index => $item): 
                            $videoUrl = $item['url'] ?? '';
                        ?>
                            <div class="p-3 shadow-sm review-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="rev_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-11 <?php echo $modal_align ?? ''; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['review_video_url'] ?? 'رابط الفيديو (Embed URL)', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control review-url" name="reviews[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($videoUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://www.youtube.com/embed/...">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('rev_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_video'] ?? 'حذف الفيديو', ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addReviewRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo (!empty($is_rtl)) ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_video'] ?? 'إضافة فيديو جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reviewsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 10. Guide Edit Modal -->
<div class="modal fade custom-modal" id="guideEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir ?? 'rtl'; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-book text-primary"></i> 
                    <?php echo htmlspecialchars($lang['manage_guide'] ?? 'إدارة الدليل الشامل', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات الدليل الموحدة مع أقصى درجات المرونة والاحتياط
                $guideRawSetting = $data['guide'] ?? ($currentSettings['guide'] ?? null);
                if (empty($guideRawSetting)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtGuide = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'guide'");
                        $stmtGuide->execute();
                        $guideRawSetting = $stmtGuide->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $guideRawSetting = '{}';
                    }
                }

                $guideAllLangs = is_string($guideRawSetting) ? json_decode($guideRawSetting, true) : (is_array($guideRawSetting) ? $guideRawSetting : []);
                if (!is_array($guideAllLangs)) {
                    $guideAllLangs = [];
                }
                
                $langKey = $current_lang ?? 'ar';
                $curLangGuideData = $guideAllLangs[$langKey] ?? ($guideAllLangs['ar'] ?? []);

                $modalGuideTitle   = $curLangGuideData['title'] ?? 'دليل بيتهوفن الشامل';
                $modalGuideDesc    = $curLangGuideData['desc'] ?? '';
                $modalGuideBtn     = $curLangGuideData['read_more_btn'] ?? 'قراءة المزيد';
                $modalGuideItems   = $curLangGuideData['items'] ?? [];
                if (!is_array($modalGuideItems)) {
                    $modalGuideItems = [];
                }
                ?>
                
                <form id="guideForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang ?? 'ar'), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="guide_lang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['main_section_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                            </label>
                            <input type="text" class="form-control" name="guide[<?php echo $current_lang ?? 'ar'; ?>][title]" value="<?php echo htmlspecialchars((string)$modalGuideTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['section_desc'] ?? 'وصف القسم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                            </label>
                            <textarea class="form-control" name="guide[<?php echo $current_lang ?? 'ar'; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalGuideDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div>
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['read_more'] ?? 'نص زر قراءة المزيد', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                            </label>
                            <input type="text" class="form-control" name="guide[<?php echo $current_lang ?? 'ar'; ?>][read_more_btn]" value="<?php echo htmlspecialchars((string)$modalGuideBtn, ENT_QUOTES, 'UTF-8'); ?>" placeholder="قراءة المزيد / Read More">
                        </div>
                    </div>

                    <div id="guideRowsContainer" class="d-flex flex-column gap-3">
                        <?php if (is_array($modalGuideItems)): ?>
                            <?php foreach ($modalGuideItems as $index => $item): ?>
                                <div class="p-3 shadow-sm guide-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="guide_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['article_title'] ?? 'عنوان المقال', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control guide-title" name="guide[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['article_title'] ?? 'عنوان المقال', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['page_url'] ?? 'رابط الصفحة', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control guide-url" name="guide[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['page_url'] ?? 'رابط الصفحة', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control guide-desc" name="guide[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <!-- قسم الصورة الحالية وزر الرفع في نفس السطر تماماً -->
                                        <div class="col-md-5">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['image'] ?? 'الصورة', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($item['img'])): ?>
                                                    <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 42px; height: 42px;">
                                                        <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($item['img']) : $item['img'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                             alt="Guide Item Image" 
                                                             class="rounded-2 w-100 h-100" 
                                                             style="object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control guide-file w-100" name="guide_img_<?php echo $index; ?>" accept="image/*">
                                            </div>
                                            <input type="hidden" class="guide-old-img" name="guide[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_article'] ?? 'حذف المقال', ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_article'] ?? 'إضافة مقال جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 11. FAQ Edit Modal -->
<div class="modal fade custom-modal" id="faqEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir ?? 'rtl'; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle text-primary"></i> 
                    <?php echo htmlspecialchars($lang['manage_faq'] ?? 'إدارة الأسئلة الشائعة', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات الأسئلة الشائعة الموحدة مع أقصى درجات المرونة والاحتياط
                $faqRawSetting = $data['faq'] ?? ($currentSettings['faq'] ?? null);
                if (empty($faqRawSetting)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtFaq = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'faq'");
                        $stmtFaq->execute();
                        $faqRawSetting = $stmtFaq->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $faqRawSetting = '{}';
                    }
                }

                $faqAllLangs = is_string($faqRawSetting) ? json_decode($faqRawSetting, true) : (is_array($faqRawSetting) ? $faqRawSetting : []);
                if (!is_array($faqAllLangs)) {
                    $faqAllLangs = [];
                }
                
                $langKey = $current_lang ?? 'ar';
                $curLangFaqData = $faqAllLangs[$langKey] ?? ($faqAllLangs['ar'] ?? []);

                $modalFaqTitle = $curLangFaqData['title'] ?? ($data['faq_title'] ?? 'الأسئلة الشائعة');
                $modalFaqItems = $curLangFaqData['items'] ?? ($data['faq_items'] ?? []);
                if (!is_array($modalFaqItems)) {
                    $modalFaqItems = [];
                }
                ?>
                
                <form id="faqForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_faq">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang ?? 'ar'), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="faq_lang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">
                            <?php echo htmlspecialchars($lang['main_section_title'] ?? 'عنوان القسم', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                        </label>
                        <input type="text" class="form-control" name="faq[<?php echo $current_lang ?? 'ar'; ?>][title]" value="<?php echo htmlspecialchars((string)$modalFaqTitle, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="faqRowsContainer" class="d-flex flex-column gap-3">
                        <?php if (is_array($modalFaqItems)): ?>
                            <?php foreach ($modalFaqItems as $index => $item): ?>
                                <div class="p-3 shadow-sm faq-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="faq_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['faq_question'] ?? 'السؤال', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control faq-question" name="faq[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars($item['question'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['faq_question'] ?? 'السؤال', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['faq_answer'] ?? 'الإجابة', ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control faq-answer" name="faq[<?php echo $current_lang ?? 'ar'; ?>][items][<?php echo $index; ?>][answer]" value="<?php echo htmlspecialchars($item['answer'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lang['faq_answer'] ?? 'الإجابة', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('faq_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($lang['delete_faq'] ?? 'حذف السؤال', ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addFaqRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_faq'] ?? 'إضافة سؤال جديد', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="faqForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 12. Footer Edit Modal -->
<div class="modal fade custom-modal" id="footerEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir ?? 'rtl'; ?>">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-layout-wtf text-primary"></i> 
                    <?php echo htmlspecialchars($lang['manage_footer'] ?? 'إدارة الفوتر بالكامل', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب بيانات الفوتر الموحدة مباشرة من قاعدة البيانات إن لم تكن محملة بالكامل
                $footerRawSetting = $data['footer'] ?? ($currentSettings['footer'] ?? null);
                if (empty($footerRawSetting)) {
                    try {
                        $pdoConn = \App\Config\Database::getConnection();
                        $stmtFooter = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'footer'");
                        $stmtFooter->execute();
                        $footerRawSetting = $stmtFooter->fetchColumn() ?: '{}';
                    } catch (\Exception $e) {
                        $footerRawSetting = '{}';
                    }
                }

                $footerAllLangs = is_string($footerRawSetting) ? json_decode($footerRawSetting, true) : (is_array($footerRawSetting) ? $footerRawSetting : []);
                if (!is_array($footerAllLangs)) {
                    $footerAllLangs = [];
                }
                
                $langKey = $current_lang ?? 'ar';
                $curLangFooterData = $footerAllLangs[$langKey] ?? ($footerAllLangs['ar'] ?? []);

                $modalConsultTitle = $curLangFooterData['consult_title'] ?? ($data['consult_title'] ?? 'احصل على استشارة مجانية');
                $modalConsultDesc  = $curLangFooterData['consult_desc'] ?? ($data['consult_desc'] ?? '');
                $modalFooterDesc   = $curLangFooterData['footer_desc'] ?? ($data['footer_desc'] ?? '');
                $modalCol2Title    = $curLangFooterData['col2_title'] ?? ($data['footer_col2_title'] ?? 'روابط سريعة');
                $modalCol3Title    = $curLangFooterData['col3_title'] ?? ($data['footer_col3_title'] ?? 'تواصل معنا');
                ?>

                <form id="footerForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_footer">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang ?? 'ar'), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="footer_lang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><i class="bi bi-chat-dots me-1"></i> إعدادات قسم الاستشارة (<?php echo strtoupper($current_lang ?? 'ar'); ?>)</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary">عنوان الاستشارة</label>
                                        <input type="text" class="form-control" name="footer[<?php echo $current_lang ?? 'ar'; ?>][consult_title]" value="<?php echo htmlspecialchars((string)$modalConsultTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small fw-bold mb-1 text-secondary">وصف الاستشارة</label>
                                        <input type="text" class="form-control" name="footer[<?php echo $current_lang ?? 'ar'; ?>][consult_desc]" value="<?php echo htmlspecialchars((string)$modalConsultDesc, ENT_QUOTES, 'UTF-8'); ?>">
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
                                <textarea class="form-control" name="footer[<?php echo $current_lang ?? 'ar'; ?>][footer_desc]" rows="6" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalFooterDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الثاني</h6>
                                <label class="small fw-bold mb-1 text-secondary">عنوان العمود</label>
                                <input type="text" class="form-control mb-3" name="footer[<?php echo $current_lang ?? 'ar'; ?>][col2_title]" value="<?php echo htmlspecialchars((string)$modalCol2Title, ENT_QUOTES, 'UTF-8'); ?>">
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
                                    <input type="text" class="form-control" name="footer[<?php echo $current_lang ?? 'ar'; ?>][col3_title]" value="<?php echo htmlspecialchars((string)$modalCol3Title, ENT_QUOTES, 'UTF-8'); ?>">
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
                                                                <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($link['img']) : $link['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
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
                <button type="submit" form="footerForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>


<!-- Dynamic Rows JS Engine & AJAX Handlers -->
<script>
    function removeRow(target) {
        if (typeof target === 'string') {
            const el = document.getElementById(target);
            if (el) el.remove();
        } else if (target && target.nodeType) {
            const row = target.closest('.social-row-item, .menu-row-item, .choose-row-item, .review-row-item, .guide-row-item, .faq-row-item, .footer-col3-item, .lang-row-item, .service-row-item, .team-row-item, .count-row-item, .partner-row-item');
            if (row) row.remove();
        }
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

    // 🌟 دالة إضافة فيديو جديد لقسم reviews (متوافقة مع اللغة الحالية)
    function addReviewRow() {
        const container = document.getElementById('reviewsRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.review-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        
        const langVideoUrl   = "<?php echo $lang['review_video_url'] ?? 'رابط الفيديو (Embed URL)'; ?>";
        const langDeleteText = "<?php echo $lang['delete_video'] ?? 'حذف الفيديو'; ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 review-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">${langVideoUrl}</label>
                    <input type="text" class="form-control review-url" name="reviews[${currentLang}][items][${count}][url]" placeholder="https://www.youtube.com/embed/...">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDeleteText}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // 🌟 دالة إضافة سؤال وجواب جديد لقسم FAQ (مترجمة بالكامل)
    function addFaqRow() {
        const container = document.getElementById('faqRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.faq-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';

        const langQuestion = "<?php echo htmlspecialchars($lang['faq_question'] ?? 'السؤال', ENT_QUOTES, 'UTF-8'); ?>";
        const langAnswer   = "<?php echo htmlspecialchars($lang['faq_answer'] ?? 'الإجابة', ENT_QUOTES, 'UTF-8'); ?>";
        const langDelete   = "<?php echo htmlspecialchars($lang['delete_faq'] ?? 'حذف السؤال', ENT_QUOTES, 'UTF-8'); ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm faq-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langQuestion}</label>
                    <input type="text" class="form-control faq-question" name="faq[${currentLang}][items][${count}][question]" placeholder="${langQuestion}">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">${langAnswer}</label>
                    <input type="text" class="form-control faq-answer" name="faq[${currentLang}][items][${count}][answer]" placeholder="${langAnswer}">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDelete}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // 🌟 دالة إضافة مقال جديد (الهيكل الأصلي المستقر والمجرب)
    function addGuideRow() {
        const container = document.getElementById('guideRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.guide-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';

        // جلب الترجمات الديناميكية من PHP
        const langArticleTitle = "<?php echo htmlspecialchars($lang['article_title'] ?? 'عنوان المقال', ENT_QUOTES, 'UTF-8'); ?>";
        const langPageUrl      = "<?php echo htmlspecialchars($lang['page_url'] ?? 'رابط الصفحة', ENT_QUOTES, 'UTF-8'); ?>";
        const langDesc         = "<?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?>";
        const langImage        = "<?php echo htmlspecialchars($lang['image'] ?? 'الصورة', ENT_QUOTES, 'UTF-8'); ?>";
        const langDelete       = "<?php echo htmlspecialchars($lang['delete_article'] ?? 'حذف المقال', ENT_QUOTES, 'UTF-8'); ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm guide-row-item mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langArticleTitle}</label>
                    <input type="text" class="form-control guide-title" name="guide[${currentLang}][items][${count}][title]" placeholder="${langArticleTitle}">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langPageUrl}</label>
                    <input type="text" class="form-control guide-url" name="guide[${currentLang}][items][${count}][url]" placeholder="${langPageUrl}">
                </div>
                
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langDesc}</label>
                    <input type="text" class="form-control guide-desc" name="guide[${currentLang}][items][${count}][desc]" placeholder="${langDesc}">
                </div>

                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">${langImage}</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control guide-file w-100" name="guide_img_${count}" accept="image/*">
                    </div>
                    <input type="hidden" class="guide-old-img" name="guide[${currentLang}][items][${count}][old_img]" value="">
                </div>
                
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDelete}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';

                // 🌟 إعادة ترتيب وتسمية حقول review بدقة عند الـ submit لتتوافق مع الباك إند
                form.querySelectorAll('.review-row-item').forEach((row, index) => {
                    const urlInput = row.querySelector('.review-url');
                    if (urlInput) {
                        urlInput.name = `reviews[${currentLang}][items][${index}][url]`;
                    }
                });

                // 🌟 إعادة ترتيب حقول FAQ بدقة عند الـ submit
                form.querySelectorAll('.faq-row-item').forEach((row, index) => {
                    const q = row.querySelector('.faq-question');
                    const a = row.querySelector('.faq-answer');
                    if (q) q.name = `faq[${currentLang}][items][${index}][question]`;
                    if (a) a.name = `faq[${currentLang}][items][${index}][answer]`;
                });

                // إعادة ترتيب حقول الخدمات
                form.querySelectorAll('.service-row-item').forEach((row, index) => {
                    const title = row.querySelector('.service-title');
                    const url = row.querySelector('.service-url');
                    const file = row.querySelector('.service-file');
                    const oldImg = row.querySelector('.service-old-img');
                    if (title) title.name = `services[${currentLang}][items][${index}][title]`;
                    if (url) url.name = `services[${currentLang}][items][${index}][url]`;
                    if (file) file.name = `service_img_${index}`;
                    if (oldImg) oldImg.name = `services[${currentLang}][items][${index}][old_img]`;
                });

                // إعادة ترتيب حقول choose
                form.querySelectorAll('.choose-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.choose-title');
                    const descInput = row.querySelector('.choose-desc');
                    const fileInput = row.querySelector('.choose-file');
                    const oldImgInput = row.querySelector('.choose-old-img');
                    
                    if (titleInput) titleInput.name = `choose[${currentLang}][items][${index}][title]`;
                    if (descInput) descInput.name = `choose[${currentLang}][items][${index}][desc]`;
                    if (fileInput) fileInput.name = `choose_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `choose[${currentLang}][items][${index}][old_img]`;
                });

                // 🌟 إعادة ترتيب حقول الدليل الشامل (Guide) بدقة عند الـ submit
                form.querySelectorAll('.guide-row-item').forEach((row, index) => {
                    const title = row.querySelector('.guide-title');
                    const url = row.querySelector('.guide-url');
                    const desc = row.querySelector('.guide-desc');
                    const file = row.querySelector('.guide-file');
                    const oldImg = row.querySelector('.guide-old-img');
                    
                    if (title) title.name = `guide[${currentLang}][items][${index}][title]`;
                    if (url) url.name = `guide[${currentLang}][items][${index}][url]`;
                    if (desc) desc.name = `guide[${currentLang}][items][${index}][desc]`;
                    if (file) file.name = `guide_img_${index}`;
                    if (oldImg) oldImg.name = `guide[${currentLang}][items][${index}][old_img]`;
                });

                // 🌟 إعادة ترتيب حقول تواصل الفوتر (Footer Col3 Links) بدقة عند الـ submit
                form.querySelectorAll('.footer-col3-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.footer-col3-title-input');
                    const urlInput = row.querySelector('.footer-col3-url-input');
                    const fileInput = row.querySelector('.footer-col3-file');
                    const oldImgInput = row.querySelector('.footer-col3-old-img');
                    
                    if (titleInput) titleInput.name = `col3[${index}][title]`;
                    if (urlInput) urlInput.name = `col3[${index}][url]`;
                    if (fileInput) fileInput.name = `col3_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `col3[${index}][old_img]`;
                });

                const formId = this.getAttribute('id');
                const submitBtn = (formId ? document.querySelector(`button[form="${formId}"]`) : null) || this.querySelector('button[type="submit"]');
                let originalText = '';
                if (submitBtn) {
                    originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> جاري الحفظ...';
                }

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
                    try {
                        const data = JSON.parse(text);
                        if (data.success || data.status === 'success') {
                            showNotification(data.message || 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'يرجى التأكد من البيانات المدخلة'), 'danger');
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        }
                    } catch (e) {
                        showNotification('خطأ في إستجابة الخادم: ' + text, 'danger');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            });
        });
    });
</script>
