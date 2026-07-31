<?php
/**
 * لوحة تحكم الإدارة - مودلات الهيدر والفوتر والإعدادات العامة
 */
?>
<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --bg-soft: #f8fafc;
        --border-color: #dbeafe;
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius: 20px;
    }

    /* Modal Structure */
    .custom-modal .modal-content { border-radius: var(--radius); border: none; box-shadow: var(--shadow-md); overflow: hidden; }
    .custom-modal .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--border-color); }
    .custom-modal .modal-title { margin: 0; display: flex; align-items: center; gap: .5rem; font-weight: 700; color: #1e293b; }
    .custom-modal .btn-close { margin: 0; flex-shrink: 0; }

    /* Inputs */
    .custom-modal .form-control, .custom-modal .form-select { border-radius: 12px; border: 1px solid var(--border-color); height: 48px; padding: 0 16px; transition: 0.2s; width: 100%; }
    .custom-modal .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
    
    /* File Upload */
    .custom-modal input[type="file"].form-control { padding: 10px 16px; background: #fff; cursor: pointer; width: 100%; }

    /* Modal Footer */
    .custom-modal .modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        background: #f8fafc;
    }

    .custom-modal .modal-footer button {
        flex: 1;
        height: 48px;
        font-size: 15px;
    }

    /* Cancel Button */
    .btn-cancel {
        background-color: #cbd5e1;
        color: #334155;   
        border-radius: 12px;
        border: none;
        transition: 0.3s;
    }
    .btn-cancel:hover {
        background-color: #94a3b8;
        color: #1e293b;
    }

    /* Save / Premium Button */
    .btn-premium { 
        background: linear-gradient(135deg, var(--primary), var(--primary-dark)); 
        color: white; 
        border-radius: 12px; 
        border: none; 
        transition: 0.3s; 
    }
    .btn-premium:hover {
        opacity: 0.9;
        color: white;
    }

    .btn-icon-trash { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #ef4444; border: none; transition: 0.2s; }
    .btn-icon-trash:hover { background: #fecaca; }
    
    /* Thumbnails */
    .thumb-preview { width: 40px; height: 40px; object-fit: contain; background: #fff; padding: 2px; border-radius: 8px; border: 1px solid #cbd5e1; flex-shrink: 0; }
</style>

<!-- 1. Social Links Modal -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-share-fill text-primary"></i> إدارة منصات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" action="index.php?url=admin/settings/save" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="row_<?php echo $index; ?>">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-2 border bg-white d-flex align-items-center justify-content-center p-1" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img src="<?php echo !empty($link['img']) ? $path_prefix . htmlspecialchars($link['img']) . '?' . time() : ''; ?>" 
                                         id="preview_img_<?php echo $index; ?>" 
                                         style="width: 32px; height: 32px; object-fit: contain; <?php echo empty($link['img']) ? 'display:none;' : ''; ?>">
                                    <i class="bi bi-image text-muted <?php echo !empty($link['img']) ? 'd-none' : ''; ?>" id="placeholder_icon_<?php echo $index; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="small text-muted mb-1">الاسم</label>
                                            <input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="الاسم">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="small text-muted mb-1">رابط الحساب</label>
                                            <input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col">
                                            <label class="small text-muted mb-1">تغيير صورة الأيقونة</label>
                                            <input type="file" class="form-control form-control-sm" accept="image/*" onchange="uploadSocialImage(this, <?php echo $index; ?>)">
                                            <div id="status_<?php echo $index; ?>" class="small text-primary mt-1" style="display: none;">جاري الرفع...</div>
                                        </div>
                                        <div class="col-auto pt-3">
                                            <button type="button" class="btn-icon-trash" onclick="removeRow('row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="social[<?php echo $index; ?>][old_img]" id="social_img_val_<?php echo $index; ?>" value="<?php echo htmlspecialchars($link['img'] ?? ''); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addSocialRow()"><i class="bi bi-plus-circle me-1"></i> إضافة منصة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="socialLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Logo Modal -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تغيير شعار الموقع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- الفورم يحيط بكل محتوى الـ modal-body -->
            <form id="logoEditForm" action="index.php?url=admin/settings/save" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4 text-center">
                    <input type="hidden" name="action" value="update_general_settings">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <input type="hidden" name="site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? 'Beethoven Services'); ?>">
                    <input type="hidden" name="site_email" value="<?php echo htmlspecialchars($settings['site_email'] ?? 'info@beethoven.de'); ?>">
                    
                    <!-- الحقل المخفي الهام جداً الذي يستقبل مسار الشعار -->
                    <input type="hidden" name="site_logo" id="logoUrlInput" value="<?php echo htmlspecialchars($settings['site_logo'] ?? ''); ?>">


                    <div class="mb-4">
                        <label class="form-label fw-bold d-block text-start mb-2">الشعار الحالي للموقع:</label>
                        <div class="p-3 bg-light rounded border d-inline-block w-100">
                            <img src="<?php echo !empty($settings['site_logo']) ? $path_prefix . htmlspecialchars($settings['site_logo']) . '?' . time() : ''; ?>" id="logoPreviewImg" style="max-height: 90px; object-fit: contain; <?php echo empty($settings['site_logo']) ? 'display:none;' : ''; ?>">
                            <div class="small text-muted mt-2 dir-ltr" id="logoPathText"><?php echo htmlspecialchars($settings['site_logo'] ?? ''); ?></div>
                        </div>
                    </div>
                    
                    <label class="form-label fw-bold text-start w-100 mb-1">رفع شعار جديد:</label>
                    <input type="file" class="form-control w-100" id="logoFileInput" accept="image/*">

                    <div id="logoUploadStatus" class="small text-primary mt-1 text-start" style="display: none;">جاري رفع وتحويل الشعار...</div>
                </div>
            </form>

            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. Announcement Modal -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary"></i> إعدادات لوحة الإعلانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                    $ad_data = $data['announcement'] ?? [];
                    $ad_status = $ad_data['status'] ?? 'Draft';
                    $ad_type = $ad_data['type'] ?? 'text';
                    $ad_text = $ad_data['announcement_text'] ?? '';
                    $ad_img = $ad_data['image_path'] ?? '';
                    $ad_link = $ad_data['link'] ?? '';
                    $ad_start = $ad_data['start_date'] ?? '';
                    $ad_end = $ad_data['end_date'] ?? '';
                    $ad_bg = $ad_data['bg_color'] ?? '#f1f5f9';
                    $ad_color = $ad_data['text_color'] ?? '#1e293b';
                    $ad_size = $ad_data['font_size'] ?? '16';
                ?>
                <form id="announcementEditForm" action="index.php?url=admin/settings/save" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <input type="hidden" name="image_path" id="adImageUrl" value="<?php echo htmlspecialchars($ad_img); ?>">
                    
                    <div class="card p-3 mb-4 border" style="background: #f8fafc; border-color: var(--border-color);">
                        <div class="section-label mb-2 fw-bold text-primary"><i class="bi bi-eye"></i> معاينة الإعلان الحالي</div>
                        <div class="p-3 rounded border bg-white d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge bg-<?php echo ($ad_status == 'Published' ? 'success' : 'secondary'); ?> mb-2">
                                    <?php echo ($ad_status == 'Published' ? 'نشط حالياً' : 'مخفي (مسودة)'); ?>
                                </span>
                                <?php if($ad_type == 'text'): ?>
                                    <p class="mb-1 text-muted small"><strong>النص:</strong> <?php echo htmlspecialchars($ad_text ?: 'لا يوجد نص'); ?></p>
                                <?php else: ?>
                                    <p class="mb-1 text-muted small"><strong>النوع:</strong> بانر صورة</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 mb-4 border-0" style="background: #f1f5f9;">
                        <div class="section-label mb-2 fw-bold"><i class="bi bi-gear"></i> حالة الإعلان والتوقيت</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold">حالة العرض</label>
                                <select class="form-select" name="status">
                                    <option value="Draft" <?php echo ($ad_status == 'Draft' ? 'selected' : ''); ?>>مخفي (مسودة)</option>
                                    <option value="Published" <?php echo ($ad_status == 'Published' ? 'selected' : ''); ?>>نشط</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold">تاريخ البدء</label>
                                <input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $ad_start); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold">تاريخ الانتهاء</label>
                                <input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $ad_end); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 mb-4 border" style="border-color: var(--border-color);">
                        <div class="section-label mb-2 fw-bold"><i class="bi bi-pencil-square"></i> محتوى الإعلان</div>
                        <label class="small mb-2 fw-bold">نوع الإعلان:</label>
                        <select class="form-select mb-3" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo ($ad_type == 'text' ? 'selected' : ''); ?>>نص متحرك</option>
                            <option value="image" <?php echo ($ad_type == 'image' ? 'selected' : ''); ?>>صورة (بانر)</option>
                        </select>

                        <div id="textEditor" class="<?php echo ($ad_type == 'text' ? '' : 'd-none'); ?>">
                            <label class="small mb-1 fw-bold">نص الإعلان:</label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars($ad_text); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="small">لون الخلفية</label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo $ad_bg; ?>">
                                </div>
                                <div class="col-4">
                                    <label class="small">لون الخط</label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo $ad_color; ?>">
                                </div>
                                <div class="col-4">
                                    <label class="small">حجم الخط</label>
                                    <input type="number" class="form-control" name="font_size" value="<?php echo $ad_size; ?>">
                                </div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo ($ad_type == 'image' ? '' : 'd-none'); ?>">
                            <label class="small mb-1 fw-bold">صورة الإعلان الحالية:</label>
                            <div class="mb-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center gap-2">
                                <img src="<?php echo !empty($ad_img) ? htmlspecialchars($path_prefix . $ad_img) : ''; ?>" id="adImagePreview" class="thumb-preview" style="width: 80px; height: 40px; object-fit: contain; <?php echo empty($ad_img) ? 'display:none;' : ''; ?>">
                                <span class="small text-muted dir-ltr" id="adImagePathText"><?php echo htmlspecialchars($ad_img); ?></span>
                            </div>
                            <label class="small mb-1 fw-bold">ارفع صورة جديدة:</label>
                            <input type="file" class="form-control" id="adImageFileInput" accept="image/*">
                            <div id="adUploadStatus" class="small text-primary mt-1" style="display: none;">جاري الرفع...</div>
                        </div>
                    </div>

                    <div class="section-label mb-1 fw-bold"><i class="bi bi-link-45deg"></i> رابط التوجيه</div>
                    <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($ad_link); ?>" placeholder="https://">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="announcementEditForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Menu Edit Modal -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-nested text-primary"></i> إدارة القائمة الرئيسية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="menuLinksForm" action="index.php?url=admin/settings/save" method="POST">
                    <input type="hidden" name="action" value="update_menu">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($menu_links ?? []) as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="menu_row_<?php echo $index; ?>">
                            <div class="row align-items-center g-2">
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">العنوان</label>
                                    <input type="text" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($link['title'] ?? ''); ?>" placeholder="العنوان">
                                </div>
                                <div class="col-md-5">
                                    <label class="small text-muted mb-1">الرابط (URL)</label>
                                    <input type="text" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط">
                                </div>
                                <div class="col-md-2">
                                    <label class="small text-muted mb-1">الترتيب</label>
                                    <input type="number" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][order]" value="<?php echo ($link['order'] ?? $index); ?>">
                                </div>
                                <div class="col-auto pt-3">
                                    <button type="button" class="btn-icon-trash" onclick="removeRow('menu_row_<?php echo $index; ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addMenuRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة رابط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="menuLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Languages Edit Modal -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-translate text-primary"></i> إدارة اللغات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langEditForm" action="index.php?url=admin/settings/save" method="POST">
                    <input type="hidden" name="action" value="update_languages">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <div id="langRowsContainer" class="d-flex flex-column gap-2">
                        <?php if (!empty($data['languages'])): ?>
                            <?php foreach ($data['languages'] as $index => $lang): ?>
                                <div class="row g-2 align-items-center" id="lang_row_<?php echo $index; ?>">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($lang['name'] ?? ''); ?>" placeholder="اسم اللغة">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="lang[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($lang['url'] ?? ''); ?>" placeholder="الرابط">
                                    </div>
                                    <div class="col-1 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('lang_row_<?php echo $index; ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addLangRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة لغة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langEditForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Hero Edit Modal -->
<div class="modal fade custom-modal" id="heroEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear text-primary"></i> تعديل قسم البداية (Hero)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="heroEditForm" action="index.php?url=admin/settings/save" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>">
                    <?php $h = $data['hero'] ?? ['title'=>'', 'desc'=>'', 'btn_text'=>'', 'btn_url'=>'', 'img'=>'assets/img/hero-bg.jpg']; ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">العنوان</label>
                            <input type="text" class="form-control" name="hero_title" value="<?php echo htmlspecialchars($h['title'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">النص الوصفي</label>
                            <textarea class="form-control" name="hero_desc" rows="3"><?php echo htmlspecialchars($h['desc'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="hero_btn_text" value="<?php echo htmlspecialchars($h['btn_text'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط الزر الحالي</label>
                            <input type="text" class="form-control" name="hero_btn_url" value="<?php echo htmlspecialchars($h['btn_url'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold d-block">صورة الخلفية الحالية</label>
                            <?php if (!empty($h['img'])): ?>
                                <div class="mb-2 p-2 border rounded bg-light text-center">
                                    <img src="<?php echo $path_prefix . htmlspecialchars($h['img']) . '?' . time(); ?>" style="max-height: 110px; object-fit: contain;">
                                    <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($h['img']); ?></div>
                                </div>
                            <?php endif; ?>
                            <label class="form-label fw-bold">اختيار صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                            <input type="hidden" name="old_hero_img" value="<?php echo htmlspecialchars($h['img'] ?? 'assets/img/hero-bg.jpg'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="heroEditForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function toggleAdContent(val) { 
        const textEditor = document.getElementById('textEditor');
        const imageEditor = document.getElementById('imageEditor');
        if(textEditor) textEditor.classList.toggle('d-none', val !== 'text'); 
        if(imageEditor) imageEditor.classList.toggle('d-none', val !== 'image'); 
    }

    function uploadSocialImage(inputElement, index) {
        const file = inputElement.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>');

        const statusDiv = document.getElementById('status_' + index);
        const previewImg = document.getElementById('preview_img_' + index);
        const placeholderIcon = document.getElementById('placeholder_icon_' + index);
        const hiddenInput = document.getElementById('social_img_val_' + index);

        if (statusDiv) { statusDiv.style.display = 'block'; statusDiv.innerText = 'جاري الرفع...'; }

        fetch('index.php?url=admin/upload-image', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (statusDiv) statusDiv.style.display = 'none';
            if (data.success || data.status === 'success' || data.url) {
                const imagePath = data.url || data.path || (data.data ? data.data.filename : '');
                if (hiddenInput) hiddenInput.value = imagePath;
                if (previewImg) { previewImg.src = imagePath + '?' + new Date().getTime(); previewImg.style.display = 'block'; }
                if (placeholderIcon) placeholderIcon.classList.add('d-none');
            } else {
                alert('فشل رفع الصورة: ' + (data.message || data.error || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            if (statusDiv) statusDiv.style.display = 'none';
            console.error('Error:', error);
        });
    }

    let socialCount = <?php echo count($data['social_links'] ?? []); ?>;
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'row_' + socialCount;
        div.innerHTML = `<div class="d-flex align-items-start gap-3">
            <div class="flex-grow-1">
                <div class="row g-2 mb-2">
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="social[${socialCount}][name]" placeholder="الاسم"></div>
                    <div class="col-8"><input type="url" class="form-control form-control-sm" name="social[${socialCount}][url]" placeholder="الرابط"></div>
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col"><input type="file" class="form-control form-control-sm" accept="image/*" onchange="uploadSocialImage(this, ${socialCount})"></div>
                    <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('row_${socialCount}')"><i class="bi bi-trash"></i></button></div>
                </div>
                <div id="status_${socialCount}" class="small text-primary mt-1" style="display: none;">جاري الرفع...</div>
            </div>
        </div>
        <input type="hidden" name="social[${socialCount}][old_img]" id="social_img_val_${socialCount}" value="">`;
        container.appendChild(div);
        socialCount++;
    }

    let menuCount = <?php echo count($menu_links ?? []); ?>;
    function addMenuRow() {
        const container = document.getElementById('menuRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'menu_row_' + menuCount;
        div.innerHTML = `
            <div class="row align-items-center g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="menu[${menuCount}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" name="menu[${menuCount}][url]" placeholder="الرابط">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm" name="menu[${menuCount}][order]" value="${menuCount}">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('menu_row_${menuCount}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
        menuCount++;
    }

    let langCount = <?php echo count($data['languages'] ?? []); ?>;
    function addLangRow() {
        const container = document.getElementById('langRowsContainer');
        const div = document.createElement('div');
        div.className = 'row g-2 align-items-center mb-2';
        div.id = 'lang_row_' + langCount;
        div.innerHTML = `
            <div class="col-5">
                <input type="text" class="form-control" name="lang[${langCount}][name]" placeholder="اسم اللغة">
            </div>
            <div class="col-6">
                <input type="text" class="form-control" name="lang[${langCount}][url]" placeholder="الرابط">
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn-icon-trash" onclick="removeRow('lang_row_${langCount}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>`;
        container.appendChild(div);
        langCount++;
    }

    document.getElementById('logoFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>');

        const statusDiv = document.getElementById('logoUploadStatus');
        if (statusDiv) { statusDiv.style.display = 'block'; statusDiv.innerText = 'جاري رفع الشعار...'; }

        fetch('index.php?url=admin/upload-image', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (statusDiv) statusDiv.style.display = 'none';
            if (data.success || data.url || data.path || data.data) {
                const imgPath = data.url || data.path || data.image_path || (data.data ? data.data.filename : '');
                document.getElementById('logoUrlInput').value = imgPath;
                document.getElementById('logoPreviewImg').src = imgPath + '?' + new Date().getTime();
                document.getElementById('logoPreviewImg').style.display = 'block';
                document.getElementById('logoPathText').innerText = imgPath;
            } else {
                alert('فشل رفع الشعار: ' + (data.message || data.error || 'خطأ غير معروف'));
            }
        })
        .catch(err => {
            if (statusDiv) statusDiv.style.display = 'none';
            console.error(err);
        });
    });

    document.getElementById('adImageFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? $_SESSION['settings_csrf'] ?? ''); ?>');

        const statusDiv = document.getElementById('adUploadStatus');
        if (statusDiv) { statusDiv.style.display = 'block'; statusDiv.innerText = 'جاري الرفع...'; }

        fetch('index.php?url=admin/upload-image', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (statusDiv) statusDiv.style.display = 'none';
            if (data.success || data.url || data.path || data.data) {
                const imgPath = data.url || data.path || data.image_path || (data.data ? data.data.filename : '');
                document.getElementById('adImageUrl').value = imgPath;
                const preview = document.getElementById('adImagePreview');
                preview.src = imgPath + '?' + new Date().getTime();
                preview.style.display = 'block';
                document.getElementById('adImagePathText').innerText = imgPath;
            } else {
                alert('فشل رفع الصورة: ' + (data.message || data.error || 'خطأ غير معروف'));
            }
        })
        .catch(err => {
            if (statusDiv) statusDiv.style.display = 'none';
            console.error(err);
        });
    });
</script>
