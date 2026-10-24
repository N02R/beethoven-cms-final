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
                        $rawSocial = $currentSettings['social_links'] ?? ($data['social_links'] ?? '');
                        $socialLinksData = is_string($rawSocial) ? json_decode($rawSocial, true) : (is_array($rawSocial) ? $rawSocial : []);
                        if (!is_array($socialLinksData)) $socialLinksData = [];

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
                            $rawLogoSetting = $currentSettings['site_logo_path'] ?? ($data['site_logo_path'] ?? ($currentSettings['site_logo'] ?? ''));
                            $logosDecoded = json_decode($rawLogoSetting, true);
                            $activeLogo = '';
                            if (is_array($logosDecoded)) {
                                $activeLogo = $logosDecoded[$current_lang] ?? $logosDecoded['ar'] ?? $logosDecoded['de'] ?? reset($logosDecoded) ?? '';
                            } else {
                                $activeLogo = $rawLogoSetting;
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
                                <label class="small fw-bold mb-1"><?php echo $lang['display_status'] ?? 'حالة العرضة'; ?></label>
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

<!-- 8. Choose Edit Modal (مصحح بالكامل للاحتفاظ بالبطاقات والنصوص والصور) -->
<div class="modal fade custom-modal" id="chooseEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir ?? ($is_rtl ? 'rtl' : 'ltr'); ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-star text-primary <?php echo !empty($is_rtl) ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['edit_features'] ?? 'تعديل المميزات', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang ?? 'ar'); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                // جلب البيانات مباشرة وحياً لضمان عدم وجود أي Cache قديم
                $choose_raw = get_setting('choose', '{}');
                $choose_all = is_string($choose_raw) ? (json_decode($choose_raw, true) ?? []) : (is_array($choose_raw) ? $choose_raw : []);
                
                // عناوين القسم للغة الحالية
                $c = isset($choose_all[$current_lang]) && is_array($choose_all[$current_lang]) ? $choose_all[$current_lang] : ($choose_all['ar'] ?? ($choose_all['de'] ?? []));
                $choose_sec_title    = $c['section_title'] ?? ($c['title'] ?? ($choose_all['section_title'] ?? ''));
                $choose_sec_subtitle = $c['section_subtitle'] ?? ($c['desc'] ?? ($choose_all['section_subtitle'] ?? ''));
                
                // 🌟 حل نهائي وآمن لجلب الitems من أي مكان قد تكون مخزنة فيه
                $choose_itemsList = [];
                if (isset($choose_all['items']) && is_array($choose_all['items'])) {
                    $choose_itemsList = $choose_all['items'];
                } elseif (isset($c['items']) && is_array($c['items'])) {
                    $choose_itemsList = $c['items'];
                } else {
                    // البحث في باقي اللغات إن لم توجد في الجذر
                    foreach ($choose_all as $langKey => $langVal) {
                        if (is_array($langVal) && isset($langVal['items']) && is_array($langVal['items'])) {
                            $choose_itemsList = $langVal['items'];
                            break;
                        }
                    }
                }
                ?>

                <form id="chooseForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_choose">
                    <input type="hidden" name="choose_lang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang ?? 'ar', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang ?? 'ar'), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- الجزء الأول: عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align ?? ''; ?>">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['section_main_title'] ?? 'عنوان القسم الرئيسي', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <input type="text" class="form-control" name="choose[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars(safe_admin_string($choose_sec_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان القسم">
                        </div>
                        <div class="<?php echo $modal_align ?? ''; ?>">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['section_description_optional'] ?? 'وصف القسم (اختياري)', ENT_QUOTES, 'UTF-8'); ?>
                            </label>
                            <textarea class="form-control" name="choose[<?php echo $current_lang; ?>][section_subtitle]" rows="2" placeholder="وصف القسم" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($choose_sec_subtitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- الجزء الثاني: كروت وعناصر المميزات المضافة -->
                    <h6 class="fw-bold mb-3 <?php echo $modal_align ?? ''; ?>"><?php echo htmlspecialchars($lang['edit_features'] ?? 'المميزات المضافة', ENT_QUOTES, 'UTF-8'); ?></h6>
                    <div id="chooseRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($choose_itemsList as $index => $item): 
                            // 🌟 هنا السر: استخراج نصوص اللغة الحالية (عربية، ألمانية، إلخ) بدقة لملء الحقول تلقائياً
                            $langItemData = isset($item[$current_lang]) && is_array($item[$current_lang]) 
                                          ? $item[$current_lang] 
                                          : ($item['ar'] ?? ($item['de'] ?? ($item['en'] ?? [])));
                                          
                            $itemTitle = $langItemData['title'] ?? '';
                            $itemDesc  = $langItemData['desc'] ?? '';
                            $itemImg   = $item['img'] ?? '';
                        ?>
                            <div class="p-3 shadow-sm choose-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="choose_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6 <?php echo $modal_align ?? ''; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'العنوان', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                        <!-- وضع القيمة المحفوظة داخل value لكي يحتفظ بها المودل -->
                                        <input type="text" class="form-control choose-title" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الميزة">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align ?? ''; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'الوصف', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                        <!-- وضع الوصف المحفوظ داخل value لكي يحتفظ به المودل -->
                                        <input type="text" class="form-control choose-desc" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($itemDesc, ENT_QUOTES, 'UTF-8'); ?>" placeholder="وصف الميزة">
                                    </div>

                                    <div class="col-md-11 <?php echo $modal_align ?? ''; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['icon_or_image'] ?? 'الأيقونة / الصورة المشتركة', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control choose-file" name="choose_img_<?php echo $index; ?>" accept="image/*">
                                            <?php if (!empty($itemImg)): ?>
                                                <?php $imgUrl = function_exists('get_image_url') ? get_image_url($itemImg) : $itemImg; ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Choose Item Icon" 
                                                         class="rounded-2" 
                                                         style="width: 38px; height: 38px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- الحقل المخفي للاحتفاظ بمسار الصورة القديمة -->
                                        <input type="hidden" class="choose-old-img" name="choose[items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($itemImg, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('choose_row_<?php echo $index; ?>')" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addChooseRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> <?php echo htmlspecialchars($lang['add_new_feature'] ?? 'إضافة ميزة جديدة', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="chooseForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 9. Reviews Edit Modal -->
<div class="modal fade custom-modal" id="reviewsEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
<div class="modal fade custom-modal" id="guideEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                                                    <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($item['img']) : $item['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
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
<div class="modal fade custom-modal" id="faqEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
<div class="modal fade custom-modal" id="footerEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <button type="submit" form="footerForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
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

    function toggleAdContent(val) {
        const textEditor = document.getElementById('textEditor');
        const imageEditor = document.getElementById('imageEditor');
        if (val === 'image') {
            if (textEditor) textEditor.classList.add('d-none');
            if (imageEditor) imageEditor.classList.remove('d-none');
        } else {
            if (textEditor) textEditor.classList.remove('d-none');
            if (imageEditor) imageEditor.classList.add('d-none');
        }
    }

    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.social-row-item').length;

        const langPlatformName = "<?php echo $lang['platform_name'] ?? 'اسم المنصة'; ?>";
        const langPlatformUrl  = "<?php echo $lang['platform_url'] ?? 'رابط المنصة'; ?>";
        const langIconImage    = "<?php echo $lang['platform_icon_image'] ?? 'أيقونة / صورة المنصة'; ?>";
        const langNamePlace    = "<?php echo $lang['name_placeholder'] ?? 'الاسم'; ?>";
        const langUrlPlace     = "<?php echo $lang['url_placeholder'] ?? 'الرابط'; ?>";
        const langDeleteText   = "<?php echo $lang['delete_platform'] ?? 'حذف المنصة'; ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 social-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">${langPlatformName}</label>
                    <input type="text" class="form-control social-name" name="social[${count}][name]" placeholder="${langNamePlace}">
                </div>
                <div class="col-md-8">
                    <label class="small fw-bold mb-1 text-secondary">${langPlatformUrl}</label>
                    <input type="url" class="form-control social-url" name="social[${count}][url]" placeholder="${langUrlPlace}">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">${langIconImage}</label>
                    <input type="file" class="form-control social-file" name="social_img_${count}" accept="image/*">
                </div>
                <input type="hidden" class="social-old-img" name="social[${count}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDeleteText}"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addMenuRow() {
        const container = document.getElementById('menuRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.menu-row-item').length;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const currentLangUpper = currentLang.toUpperCase();
        const alignClass = '<?php echo htmlspecialchars($modal_align ?? "text-start", ENT_QUOTES, "UTF-8"); ?>';
        
        const langTitleLabel = "<?php echo $lang['title'] ?? 'عنوان الرابط'; ?>";
        const langUrlLabel   = "<?php echo $lang['url'] ?? 'الرابط (URL)'; ?>";
        const langDeleteText = "<?php echo $lang['delete'] ?? 'حذف'; ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 menu-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6 ${alignClass}">
                    <label class="small fw-bold mb-1 text-secondary">${langTitleLabel} (${currentLangUpper})</label>
                    <input type="text" class="form-control menu-title" name="menu[${count}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-5 ${alignClass}">
                    <label class="small fw-bold mb-1 text-secondary">${langUrlLabel}</label>
                    <input type="text" class="form-control menu-url" name="menu[${count}][url]" placeholder="url">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <input type="hidden" class="menu-order" name="menu[${count}][order]" value="${count}">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDeleteText}"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addChooseRow() {
        const container = document.getElementById('chooseRowsContainer');
        if (!container) return;

        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const index = container.querySelectorAll('.choose-row-item').length;

        const langTitleLabel = "<?php echo $lang['title'] ?? 'العنوان'; ?>";
        const langDescLabel  = "<?php echo $lang['description'] ?? 'الوصف'; ?>";
        const langIconLabel  = "<?php echo $lang['icon_or_image'] ?? 'الأيقونة / الصورة المشتركة'; ?>";
        const langTitlePlace = "<?php echo $lang['feature_title_placeholder'] ?? 'عنوان الميزة'; ?>";
        const langDescPlace  = "<?php echo $lang['feature_desc_placeholder'] ?? 'وصف الميزة'; ?>";
        const langDeleteText = "<?php echo $lang['delete_feature'] ?? 'حذف الميزة'; ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 choose-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langTitleLabel}</label>
                    <input type="text" class="form-control choose-title" name="choose[${currentLang}][items][${index}][title]" placeholder="${langTitlePlace}">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${langDescLabel}</label>
                    <input type="text" class="form-control choose-desc" name="choose[${currentLang}][items][${index}][desc]" placeholder="${langDescPlace}">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">${langIconLabel}</label>
                    <input type="file" class="form-control choose-file" name="choose_img_${index}" accept="image/*">
                </div>
                <input type="hidden" class="choose-old-img" name="choose[items][${index}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${langDeleteText}"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addReviewRow() {
        const container = document.getElementById('reviewsRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.review-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 review-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">رابط الفيديو (Embed URL)</label>
                    <input type="text" class="form-control review-url" name="reviews[${count}][url]" placeholder="رابط اليوتيوب (Embed URL)">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="حذف الفيديو"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addGuideRow() {
        const container = document.getElementById('guideRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.guide-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 guide-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">عنوان المقال</label>
                    <input type="text" class="form-control guide-title" name="guide[${count}][title]" placeholder="عنوان المقال">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">رابط الصفحة</label>
                    <input type="text" class="form-control guide-url" name="guide[${count}][url]" placeholder="رابط الصفحة">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                    <input type="text" class="form-control guide-desc" name="guide[${count}][desc]" placeholder="الوصف">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">الصورة</label>
                    <input type="file" class="form-control guide-file" name="guide_img_${count}" accept="image/*">
                    <input type="hidden" class="guide-old-img" name="guide[${count}][old_img]" value="">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="حذف المقال"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addFaqRow() {
        const container = document.getElementById('faqRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.faq-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 faq-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">السؤال</label>
                    <input type="text" class="form-control faq-question" name="faq[${count}][question]" placeholder="السؤال">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">الإجابة</label>
                    <input type="text" class="form-control faq-answer" name="faq[${count}][answer]" placeholder="الإجابة">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="حذف السؤال"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addCol3Link() {
        const container = document.getElementById('col3LinksContainer');
        if (!container) return;
        const count = container.querySelectorAll('.footer-col3-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 footer-col3-item';
        div.style.cssText = 'background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">اسم الوسيلة</label>
                    <input type="text" name="col3[${count}][title]" class="form-control form-control-sm bg-white footer-col3-title-input" placeholder="اسم الوسيلة (مثلاً: واتساب)">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                    <input type="text" name="col3[${count}][url]" class="form-control form-control-sm bg-white footer-col3-url-input" placeholder="الرابط">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1 text-secondary">الأيقونة / الصورة</label>
                    <input type="file" name="col3_img_${count}" class="form-control form-control-sm bg-white footer-col3-file" accept="image/*">
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn btn-outline-danger btn-sm p-2 w-100 mx-auto" onclick="removeRow(this)" style="border-radius: 8px;" title="حذف وسيلة التواصل"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <input type="hidden" class="footer-col3-old-img" name="col3[${count}][old_img]" value="">
        `;
        container.appendChild(div);
    }

    function addLangRow() {
        const container = document.getElementById('langRowsContainer');
        if (!container) return;
        const count = container.querySelectorAll('.lang-row-item').length;
        
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const tCode = currentLang === 'ar' ? 'رمز اللغة (Code)' : (currentLang === 'de' ? 'Sprachcode' : 'Language Code');
        const tName = currentLang === 'ar' ? 'اسم اللغة' : (currentLang === 'de' ? 'Sprachname' : 'Language Name');
        const tStatus = currentLang === 'ar' ? 'الحالة' : (currentLang === 'de' ? 'Status' : 'Status');
        const tDelete = currentLang === 'ar' ? 'حذف اللغة' : (currentLang === 'de' ? 'Sprache löschen' : 'Delete Language');

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 lang-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small fw-bold mb-1 text-secondary">${tCode}</label>
                    <input type="text" class="form-control lang-code" name="lang[${count}][code]" placeholder="مثال: fr">
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold mb-1 text-secondary">${tName}</label>
                    <input type="text" class="form-control lang-name" name="lang[${count}][name]" placeholder="مثال: Français">
                </div>
                <div class="col-md-2 text-center pt-3">
                    <label class="small fw-bold mb-1 text-secondary d-block">${tStatus}</label>
                    <input type="hidden" name="lang[${count}][active]" value="0">
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input lang-active" type="checkbox" name="lang[${count}][active]" value="1" checked>
                    </div>
                </div>
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${tDelete}"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function addServiceRow() {
        const container = document.getElementById('servicesRowsContainer');
        if (!container) return;
        const currentLang = '<?php echo htmlspecialchars($current_lang ?? "ar", ENT_QUOTES, "UTF-8"); ?>';
        const index = container.querySelectorAll('.service-row-item').length;

        const tTitle  = "<?php echo $lang['service_title'] ?? 'عنوان الخدمة'; ?>";
        const tUrl    = "<?php echo $lang['service_url'] ?? 'رابط الخدمة'; ?>";
        const tImage  = "<?php echo $lang['image_or_icon'] ?? 'الصورة / الأيقونة'; ?>";
        const tDelete = "<?php echo $lang['delete_service'] ?? 'حذف الخدمة'; ?>";

        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3 service-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${tTitle}</label>
                    <input type="text" class="form-control service-title" name="services[${currentLang}][items][${index}][title]" placeholder="${tTitle}">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold mb-1 text-secondary">${tUrl}</label>
                    <input type="text" class="form-control service-url" name="services[${currentLang}][items][${index}][url]" value="#" placeholder="${tUrl}">
                </div>
                <div class="col-md-11">
                    <label class="small fw-bold mb-1 text-secondary">${tImage}</label>
                    <input type="file" class="form-control service-file" name="service_img_${index}" accept="image/*">
                </div>
                <input type="hidden" class="service-old-img" name="services[${currentLang}][items][${index}][old_img]" value="">
                <div class="col-md-1 text-center pt-3">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow(this)" title="${tDelete}"><i class="bi bi-trash"></i></button>
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

                // إعادة تسمية حقول الـ choose بالهيكل الهجين الصحيح والمتوافق مع الباك إند
                form.querySelectorAll('.choose-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.choose-title');
                    const descInput = row.querySelector('.choose-desc');
                    const fileInput = row.querySelector('.choose-file');
                    const oldImgInput = row.querySelector('.choose-old-img');
                    
                    if (titleInput) titleInput.name = `choose[${currentLang}][items][${index}][title]`;
                    if (descInput) descInput.name = `choose[${currentLang}][items][${index}][desc]`;
                    if (fileInput) fileInput.name = `choose_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `choose[items][${index}][old_img]`;
                });

                form.querySelectorAll('.social-row-item').forEach((row, index) => {
                    const nameInput = row.querySelector('.social-name');
                    const urlInput = row.querySelector('.social-url');
                    const fileInput = row.querySelector('.social-file');
                    const oldImgInput = row.querySelector('.social-old-img');
                    if (nameInput) nameInput.name = `social[${index}][name]`;
                    if (urlInput) urlInput.name = `social[${index}][url]`;
                    if (fileInput) fileInput.name = `social_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `social[${index}][old_img]`;
                });

                form.querySelectorAll('.menu-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.menu-title');
                    const urlInput = row.querySelector('.menu-url');
                    const orderInput = row.querySelector('.menu-order');
                    if (titleInput) titleInput.name = `menu[${index}][title]`;
                    if (urlInput) urlInput.name = `menu[${index}][url]`;
                    if (orderInput) {
                        orderInput.name = `menu[${index}][order]`;
                        orderInput.value = index;
                    }
                });

                form.querySelectorAll('.lang-row-item').forEach((row, index) => {
                    const codeInput = row.querySelector('.lang-code');
                    const nameInput = row.querySelector('.lang-name');
                    const activeInput = row.querySelector('.lang-active');
                    const hiddenActive = row.querySelector('input[type="hidden"][name*="[active]"]');

                    if (codeInput) codeInput.name = `lang[${index}][code]`;
                    if (nameInput) nameInput.name = `lang[${index}][name]`;
                    if (activeInput) activeInput.name = `lang[${index}][active]`;
                    if (hiddenActive && hiddenActive !== activeInput) hiddenActive.name = `lang[${index}][active]`;
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
