<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
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
   /* تنسيق الفوتر الموحد */

    /* Inputs */
    .custom-modal .form-control, .custom-modal .form-select { border-radius: 12px; border: 1px solid var(--border-color); height: 48px; padding: 0 16px; transition: 0.2s; width: 100%; }
    .custom-modal .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
    
    /* File Upload - الموحد */
    .custom-modal input[type="file"].form-control { padding: 10px 16px; background: #fff; cursor: pointer; width: 100%; }

/* تنسيق الفوتر الموحد */
.custom-modal .modal-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px; /* المسافة بين الزرين */
    padding: 16px 24px;
    background: #f8fafc;
}

/* توزيع الأزرار لتأخذ 50% من العرض لكل منهما */
.custom-modal .modal-footer button {
    flex: 1; /* هذا هو السر: كل زر يأخذ مساحة متساوية */
    height: 48px; /* ارتفاع مريح */
    font-size: 15px;
}

/* تنسيق زر الإلغاء */
.btn-cancel {
    background-color: #cbd5e1; /* الدرجة الأغمق المطلوبة */
    color: #334155;   
    border-radius: 12px;
    border: none;
    transition: 0.3s;
}
.btn-cancel:hover {
    background-color: #cbd5e1;
    color: #1e293b;
}

/* تنسيق زر الحفظ */
.btn-premium { 
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)); 
    color: white; 
    border-radius: 12px; 
    border: none; 
    transition: 0.3s; 
}

    .btn-icon-trash { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #ef4444; border: none; }
    .btn-icon-trash:hover { background: #fecaca; }
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
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="row_<?php echo $index; ?>">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-2 border bg-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? '') . '?' . time(); ?>" style="width: 28px; height: 28px; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="الاسم"></div>
                                        <div class="col-md-8"><input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط"></div>
                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col"><input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>"></div>
                                        <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('row_<?php echo $index; ?>')">
    <i class="bi bi-trash"></i>
</button>
</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addSocialRow()"><i class="bi bi-plus-circle me-1"></i> إضافة منصة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
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
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-image text-primary"></i> تغيير شعار الموقع</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 text-center">
                <form id="logoEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_logo">
                    <div class="mb-4"><div class="p-3 bg-white rounded border d-inline-block"><img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" style="max-height: 100px;"></div></div>
                    <input type="file" class="form-control w-100" name="logo_img" required>
                </form>
            </div>
<div class="modal-footer">
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="logoEditForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<!-- 3. المودل المحدث للإعلان (UX محسّن) -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary"></i> إعدادات لوحة الإعلانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    
                    <!-- المجموعة 1: حالة الإعلان -->
                    <div class="card p-3 mb-4 border-0" style="background: #f1f5f9;">
                        <div class="section-label"><i class="bi bi-gear"></i> حالة الإعلان والتوقيت</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold">حالة العرض</label>
                                <select class="form-select" name="status">
                                    <option value="Draft" <?php echo (($announcement['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مخفي (مسودة)</option>
                                    <option value="Published" <?php echo (($announcement['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشط (يظهر للزوار)</option>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="small fw-bold">تاريخ البدء</label><input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['start_date'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="small fw-bold">تاريخ الانتهاء</label><input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['end_date'] ?? ''); ?>"></div>
                        </div>
                    </div>

                    <!-- المجموعة 2: محتوى الإعلان -->
                    <div class="card p-3 mb-4 border" style="border-color: var(--border-color);">
                        <div class="section-label"><i class="bi bi-pencil-square"></i> محتوى الإعلان</div>
                        <label class="small mb-2">نوع الإعلان:</label>
                        <select class="form-select mb-3" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك (اختر هذا لنص سريع)</option>
                            <option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (بانر دعائي كامل)</option>
                        </select>

                        <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                            <label class="small mb-1">نص الإعلان (الرسالة التي ستظهر للزوار):</label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4"><label class="small">لون الخلفية</label><input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo $announcement['announcement']['bg_color'] ?? '#f1f5f9'; ?>"></div>
                                <div class="col-4"><label class="small">لون الخط</label><input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo $announcement['announcement']['text_color'] ?? '#1e293b'; ?>"></div>
                                <div class="col-4"><label class="small">حجم الخط</label><input type="number" class="form-control" name="font_size" value="<?php echo $announcement['announcement']['font_size'] ?? '16'; ?>"></div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                            <label class="small mb-1">ارفع صورة الإعلان (يُفضل صيغة WebP أو PNG):</label>
                            <input type="file" class="form-control" name="ad_image">
                        </div>
                    </div>

                    <!-- المجموعة 3: التوجيه -->
                    <div class="section-label"><i class="bi bi-link-45deg"></i> رابط التوجيه (اختياري)</div>
                    <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['announcement']['link'] ?? ''); ?>" placeholder="https://">
                </form>
            </div>
            <div class="modal-footer">
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="announcementEditForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<!-- 4. مودل إدارة القائمة الرئيسية (Menu Edit Modal) -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header px-4 py-3 border-bottom border-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-nested text-primary"></i> إدارة القائمة الرئيسية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="menuLinksForm">
                    <input type="hidden" name="action" value="update_menu">
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($menu_links as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="menu_row_<?php echo $index; ?>">
                            <div class="row align-items-center g-2">
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($link['title']); ?>" placeholder="عنوان الرابط">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="الرابط (URL)">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control form-control-sm" name="menu[<?php echo $index; ?>][order]" value="<?php echo ($link['order'] ?? $index); ?>" placeholder="الترتيب">
                                </div>
                                <div class="col-auto">
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
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="menuLinksForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<!-- مودل إدارة اللغات (Lang Edit Modal) -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-translate text-primary"></i> إدارة اللغات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="langEditForm">
                    <input type="hidden" name="action" value="update_languages">
                    
                    <div id="langRowsContainer" class="d-flex flex-column gap-2">
                        <!-- جلب اللغات الموجودة حالياً -->
                        <?php if (!empty($data['languages'])): ?>
                            <?php foreach ($data['languages'] as $index => $lang): ?>
                                <div class="row g-2" id="lang_row_<?php echo $index; ?>">
                                    <div class="col-5">
                                        <input type="text" class="form-control" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($lang['name']); ?>" placeholder="اسم اللغة (مثلاً العربية)">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="lang[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($lang['url']); ?>" placeholder="الرابط (مثلاً index.php)">
                                    </div>
                                    <div class="col-1">
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
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="langEditForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<!-- Hero Edit Modal -->
<div class="modal fade custom-modal" id="heroEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear text-primary"></i> تعديل قسم البداية (Hero)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="heroEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <?php 
                        $h = $data['hero'] ?? ['title'=>'', 'desc'=>'', 'btn_text'=>'', 'btn_url'=>'', 'img'=>'assets/img/hero-bg.jpg']; 
                    ?>
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
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="hero_btn_url" value="<?php echo htmlspecialchars($h['btn_url'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">صورة الخلفية</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                            <input type="hidden" name="old_hero_img" value="<?php echo $h['img'] ?? 'assets/img/hero-bg.jpg'; ?>">
                        </div>
                    </div>
                </form>
            </div>
<div class="modal-footer">
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="heroEditForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<!-- Services Edit Modal -->
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
                    <div class="mb-3">
    <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
    <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة'); ?>">
</div>
<div class="mb-3">
    <label class="form-label fw-bold">وصف القسم (اختياري)</label>
    <textarea class="form-control" name="services_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء"><?php echo htmlspecialchars($data['services_section_desc'] ?? ''); ?></textarea>
</div>
                    <div id="servicesRowsContainer">
                        <?php foreach (($data['services'] ?? []) as $index => $service): ?>
                            <div class="card p-3 border-0 mb-2" id="service_row_<?php echo $index; ?>" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($service['title']); ?>" placeholder="العنوان"></div>
                                    <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($service['url']); ?>" placeholder="الرابط"></div>
                                    <div class="col-md-4"><input type="file" class="form-control form-control-sm" name="service_img_<?php echo $index; ?>"></div>
                                    <input type="hidden" name="services[<?php echo $index; ?>][old_img]" value="<?php echo $service['img']; ?>">
                                    <div class="col-md-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('service_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addServiceRow()">+ إضافة خدمة جديدة</button>
                </form>
            </div>
<div class="modal-footer">
    <!-- دائماً زر إلغاء بجانب زر الحفظ -->
    <button type="submit" form="servicesEditForm" class="btn-premium">حفظ التغييرات</button>
    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
</div>

        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="chooseEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل المميزات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="chooseForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_choose">
                    
                    <!-- عنوان القسم -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="choose_title" value="<?php echo htmlspecialchars($data['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي'); ?>">
                    </div>

                   <div class="mb-4">
    <label class="form-label fw-bold">وصف القسم (اختياري)</label>
    <textarea class="form-control" name="choose_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء"><?php echo htmlspecialchars($data['choose_section_desc'] ?? ''); ?></textarea>
</div>
<hr>

                    <!-- حاوية الكاردات -->
                    <div id="chooseRowsContainer">
                        <?php if (!empty($data['choose_items'])): ?>
                            <?php foreach ($data['choose_items'] as $index => $item): ?>
                                <div class="card p-3 border-0 mb-3" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;" id="choose_row_<?php echo $index; ?>">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-3">
                                            <label class="small text-muted">العنوان</label>
                                            <input type="text" class="form-control form-control-sm" name="choose[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">الوصف</label>
                                            <input type="text" class="form-control form-control-sm" name="choose[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">الأيقونة (صورة)</label>
                                            <input type="file" class="form-control form-control-sm" name="choose_img_<?php echo $index; ?>">
                                            <input type="hidden" name="choose[<?php echo $index; ?>][old_img]" value="<?php echo $item['img']; ?>">
                                        </div>
                                        <div class="col-md-auto mt-4">
                                            <button type="button" class="btn-icon-trash" onclick="removeRow('choose_row_<?php echo $index; ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addChooseRow()">
                        <i class="bi bi-plus-circle"></i> إضافة ميزة جديدة
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

<div class="modal fade custom-modal" id="reviewsEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-play-btn text-primary"></i> إدارة فيديوهات العملاء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="reviewsForm">
                    <input type="hidden" name="action" value="update_reviews">
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="reviews_title" value="<?php echo htmlspecialchars($data['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا'); ?>">
                    </div>
                    <div id="reviewsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['reviews_items'] ?? []) as $index => $review): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="rev_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control form-control-sm" name="reviews[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($review['url'] ?? ''); ?>" placeholder="رابط اليوتيوب (Embed URL)">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('rev_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addReviewRow()"><i class="bi bi-plus-circle"></i> إضافة فيديو جديد</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reviewsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Guide Edit Modal -->
<div class="modal fade custom-modal" id="guideEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-book text-primary"></i> إدارة الدليل الشامل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="guide_title" value="<?php echo htmlspecialchars($data['guide_title'] ?? 'دليل بيتهوفن الشامل'); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="guide_desc" rows="2"><?php echo htmlspecialchars($data['guide_desc'] ?? ''); ?></textarea>
                    </div>
                    <hr>

                    <div id="guideRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['guide_items'] ?? []) as $index => $item): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;" id="guide_row_<?php echo $index; ?>">
                                <div class="row g-2">
                                    <div class="col-6"><input type="text" class="form-control form-control-sm" name="guide[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" placeholder="عنوان المقال"></div>
                                    <div class="col-6"><input type="text" class="form-control form-control-sm" name="guide[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" placeholder="رابط الصفحة"></div>
                                    <div class="col-6"><input type="file" class="form-control form-control-sm" name="guide_img_<?php echo $index; ?>"></div>
                                    <div class="col-5"><textarea class="form-control form-control-sm" name="guide[<?php echo $index; ?>][desc]" rows="1" placeholder="الوصف"><?php echo htmlspecialchars($item['desc'] ?? ''); ?></textarea></div>
                                    <input type="hidden" name="guide[<?php echo $index; ?>][old_img]" value="<?php echo $item['img'] ?? ''; ?>">
                                    <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('guide_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addGuideRow()">
                        <i class="bi bi-plus-circle"></i> إضافة مقال جديد
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

<div class="modal fade custom-modal" id="faqEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-question-circle text-primary"></i> إدارة الأسئلة الشائعة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="faqForm">
                    <input type="hidden" name="action" value="update_faq">
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="faq_title" value="<?php echo htmlspecialchars($data['faq_title'] ?? 'الأسئلة الشائعة'); ?>">
                    </div>
                    <div id="faqRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['faq_items'] ?? []) as $index => $item): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="faq_row_<?php echo $index; ?>">
                                <div class="row g-2">
                                    <div class="col-5"><input type="text" class="form-control form-control-sm" name="faq[<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars($item['question']); ?>" placeholder="السؤال"></div>
                                    <div class="col-6"><input type="text" class="form-control form-control-sm" name="faq[<?php echo $index; ?>][answer]" value="<?php echo htmlspecialchars($item['answer']); ?>" placeholder="الإجابة"></div>
                                    <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('faq_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addFaqRow()"><i class="bi bi-plus-circle"></i> إضافة سؤال جديد</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="faqForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="footerEditModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إدارة الفوتر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="footerForm">
                    <input type="hidden" name="action" value="update_footer">
                    
                    <!-- استشارة -->
                    <div class="card p-3 mb-3 border-0" style="background:#eff6ff">
                        <label class="fw-bold">عنوان الاستشارة</label>
                        <input type="text" class="form-control mb-2" name="consult_title" value="<?php echo htmlspecialchars($data['consult_title'] ?? ''); ?>">
                        <label class="fw-bold">وصف الاستشارة</label>
                        <textarea class="form-control" name="consult_desc" rows="2"><?php echo htmlspecialchars($data['consult_desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <!-- عمود 1: اللوجو والوصف -->
                        <div class="col-md-4">
                            <h6>العمود الأول (الوصف)</h6>
                            <p class="small text-muted">اللوجو يُسحب تلقائياً من إعدادات الموقع</p>
                            <textarea class="form-control" name="footer_desc" rows="4"><?php echo htmlspecialchars($data['footer_desc'] ?? ''); ?></textarea>
                        </div>
                        
                        <!-- عمود 2: الروابط السريعة -->
                        <div class="col-md-4">
                            <h6>العمود الثاني (الروابط)</h6>
                            <input type="text" class="form-control mb-2" name="footer_col2_title" value="<?php echo htmlspecialchars($data['footer_col2_title'] ?? 'روابط سريعة'); ?>">
                            <div id="col2LinksContainer">
                                <?php foreach(($data['footer_col2_links'] ?? []) as $i => $link): ?>
                                    <div class="row g-1 mb-2" id="col2_<?php echo $i; ?>">
                                        <div class="col-5"><input type="text" name="col2[<?php echo $i; ?>][title]" class="form-control form-control-sm" value="<?php echo $link['title']; ?>"></div>
                                        <div class="col-6"><input type="text" name="col2[<?php echo $i; ?>][url]" class="form-control form-control-sm" value="<?php echo $link['url']; ?>"></div>
                                        <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('col2_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCol2Link()">+ رابط</button>
                        </div>

                        <!-- عمود 3: التواصل -->
                        <div class="col-md-4">
                            <h6>العمود الثالث (التواصل)</h6>
                            <input type="text" class="form-control mb-2" name="footer_col3_title" value="<?php echo htmlspecialchars($data['footer_col3_title'] ?? 'تواصل معنا'); ?>">
                            <input type="text" class="form-control mb-1" name="footer_phone" value="<?php echo htmlspecialchars($data['footer_phone'] ?? ''); ?>" placeholder="الهاتف">
                            <input type="email" class="form-control mb-1" name="footer_email" value="<?php echo htmlspecialchars($data['footer_email'] ?? ''); ?>" placeholder="البريد">
                            <input type="text" class="form-control" name="footer_address" value="<?php echo htmlspecialchars($data['footer_address'] ?? ''); ?>" placeholder="العنوان">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="footerForm" class="btn-premium">حفظ التغييرات</button>
            </div>
        </div>
    </div>
</div>


<script>
    // 1. الدالة العامة للحذف (تعمل مع أي صف يُمرر لها الـ ID)
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. منطق إضافة الصفوف (Dynamic Rows Adders)
    
    // إضافة صف للسوشيال ميديا
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
                    <div class="col"><input type="file" class="form-control form-control-sm" name="social_img_${socialCount}"></div>
                    <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('row_${socialCount}')"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>
        </div>`;
        container.appendChild(div);
        socialCount++;
    }

    // إضافة صف للقائمة الرئيسية
    let menuCount = <?php echo count($data['menu_links'] ?? []); ?>;
    function addMenuRow() {
        const container = document.getElementById('menuRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'menu_row_' + menuCount;
        div.innerHTML = `
            <div class="row align-items-center g-2">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="menu[${menuCount}][title]" placeholder="عنوان الرابط"></div>
                <div class="col-md-5"><input type="text" class="form-control form-control-sm" name="menu[${menuCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-2"><input type="number" class="form-control form-control-sm" name="menu[${menuCount}][order]" value="${menuCount}"></div>
                <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('menu_row_${menuCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        menuCount++;
    }

    // إضافة صف للغات
    let langCount = <?php echo count($data['languages'] ?? []); ?>;
    function addLangRow() {
        const container = document.getElementById('langRowsContainer');
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2';
        div.id = 'lang_row_' + langCount;
        div.innerHTML = `
            <div class="col-5"><input type="text" class="form-control" name="lang[${langCount}][name]" placeholder="اسم اللغة"></div>
            <div class="col-6"><input type="text" class="form-control" name="lang[${langCount}][url]" placeholder="الرابط"></div>
            <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('lang_row_${langCount}')"><i class="bi bi-trash"></i></button></div>`;
        container.appendChild(div);
        langCount++;
    }

    // إضافة صف للخدمات
    let serviceCount = <?php echo count($data['services'] ?? []); ?>;
    function addServiceRow() {
        const container = document.getElementById('servicesRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'service_row_' + serviceCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[${serviceCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[${serviceCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-4"><input type="file" class="form-control form-control-sm" name="service_img_${serviceCount}"></div>
                <div class="col-md-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('service_row_${serviceCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        serviceCount++;
    }

    // إضافة صف للمميزات (Choose)
    let chooseCount = <?php echo count($data['choose_items'] ?? []); ?>;
    function addChooseRow() {
        const container = document.getElementById('chooseRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'choose_row_' + chooseCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="choose[${chooseCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="choose[${chooseCount}][desc]" placeholder="الوصف"></div>
                <div class="col-md-3"><input type="file" class="form-control form-control-sm" name="choose_img_${chooseCount}"></div>
                <div class="col-md-auto"><button type="button" class="btn-icon-trash" onclick="removeRow('choose_row_${chooseCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        chooseCount++;
    }

    // إضافة صف للتقييمات (Reviews)
    let reviewCount = <?php echo count($data['reviews_items'] ?? []); ?>;
    function addReviewRow() {
        const container = document.getElementById('reviewsRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'rev_row_' + reviewCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-10"><input type="text" class="form-control form-control-sm" name="reviews[${reviewCount}][url]" placeholder="رابط اليوتيوب"></div>
                <div class="col-md-2 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('rev_row_${reviewCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        reviewCount++;
    }

    // 3. معالج النماذج الموحد (Unified Form Handler)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('admin/api/save_config.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم الحفظ بنجاح');
                    location.reload();
                } else {
                    alert('خطأ: ' + data.message);
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالسيرفر');
            });
        });
    });

    // 4. دالة تبديل الإعلان
    function toggleAdContent(val) { 
        const textEditor = document.getElementById('textEditor');
        const imageEditor = document.getElementById('imageEditor');
        if(textEditor) textEditor.classList.toggle('d-none', val !== 'text'); 
        if(imageEditor) imageEditor.classList.toggle('d-none', val !== 'image'); 
    }
        // إضافة صف للدليل الشامل (Guide)
    let guideCount = <?php echo count($data['guide_items'] ?? []); ?>;
function addGuideRow() {
    const container = document.getElementById('guideRowsContainer');
    const div = document.createElement('div');
    div.className = 'card p-3 border-0';
    div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
    div.id = 'guide_row_' + guideCount;
    div.innerHTML = `
        <div class="row g-2">
            <div class="col-6"><input type="text" class="form-control form-control-sm" name="guide[${guideCount}][title]" placeholder="عنوان المقال"></div>
            <div class="col-6"><input type="text" class="form-control form-control-sm" name="guide[${guideCount}][url]" placeholder="رابط الصفحة"></div>
            <div class="col-6"><input type="file" class="form-control form-control-sm" name="guide_img_${guideCount}"></div>
            <div class="col-5"><textarea class="form-control form-control-sm" name="guide[${guideCount}][desc]" rows="1" placeholder="الوصف"></textarea></div>
            <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('guide_row_${guideCount}')"><i class="bi bi-trash"></i></button></div>
        </div>`;
    container.appendChild(div);
    guideCount++;
}
let faqCount = <?php echo count($data['faq_items'] ?? []); ?>;
function addFaqRow() {
    const container = document.getElementById('faqRowsContainer');
    const div = document.createElement('div');
    div.className = 'card p-3 border-0';
    div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
    div.id = 'faq_row_' + faqCount;
    div.innerHTML = `
        <div class="row g-2">
            <div class="col-5"><input type="text" class="form-control form-control-sm" name="faq[${faqCount}][question]" placeholder="السؤال"></div>
            <div class="col-6"><input type="text" class="form-control form-control-sm" name="faq[${faqCount}][answer]" placeholder="الإجابة"></div>
            <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('faq_row_${faqCount}')"><i class="bi bi-trash"></i></button></div>
        </div>
`;
    container.appendChild(div);
    faqCount++;
}

let col2Count = <?php echo count($data['footer_col2_links'] ?? []); ?>;
function addCol2Link() {
    const container = document.getElementById('col2LinksContainer');
    const div = document.createElement('div');
    div.className = 'row g-1 mb-2';
    div.id = 'col2_' + col2Count;
    div.innerHTML = `
        <div class="col-5"><input type="text" name="col2[${col2Count}][title]" class="form-control form-control-sm" placeholder="الاسم"></div>
        <div class="col-6"><input type="text" name="col2[${col2Count}][url]" class="form-control form-control-sm" placeholder="الرابط"></div>
        <div class="col-1"><button type="button" class="btn-icon-trash" onclick="removeRow('col2_${col2Count}')"><i class="bi bi-trash"></i></button></div>`;
    container.appendChild(div);
    col2Count++;
}

</script>




