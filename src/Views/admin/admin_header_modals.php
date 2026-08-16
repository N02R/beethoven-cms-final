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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="p-3 shadow-sm social-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="small fw-bold mb-1 text-secondary">اسم المنصة</label>
                                    <input type="text" class="form-control social-name" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الاسم">
                                </div>
                                <div class="col-md-8">
                                    <label class="small fw-bold mb-1 text-secondary">رابط المنصة</label>
                                    <input type="url" class="form-control social-url" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                </div>
                                <div class="col-md-11">
                                    <label class="small fw-bold mb-1 text-secondary">أيقونة / صورة المنصة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($link['img'])): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($link['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Social Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control social-file" name="social_img_<?php echo $index; ?>" accept="image/*">
                                    </div>
                                </div>
                                <input type="hidden" class="social-old-img" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($link['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('row_<?php echo $index; ?>')" title="حذف المنصة"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addSocialRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة منصة جديدة
                    </button>
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
            <div class="modal-body p-4 text-center">
                <form id="logoEditForm" enctype="multipart/form-data">
                    <!-- حقل الحماية ضد ثغرات CSRF (مهم جداً ليتطابق مع ما يشترطه الكنترولر) -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- متغير الإجراء الذي سيلتقطه الـ Controller -->
                    <input type="hidden" name="action" value="update_logo">
                    
                    <div class="mb-4">
                        <div class="p-3 shadow-sm d-inline-block" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <img src="<?php echo htmlspecialchars(get_image_url($site_logo_path ?? ''), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 90px; object-fit: contain;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <input type="file" class="form-control w-100" name="logo_img" accept="image/png, image/jpeg, image/webp" required>
                    </div>

                    <!-- صندوق لطباعة رسائل الخطأ أو النجاح ديناميكياً -->
                    <div id="logoAlertBox" class="alert d-none mt-2 rounded-3 border-0"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium" id="saveLogoBtn">حفظ التغييرات</button>
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
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    <input type="hidden" name="old_ad_image" value="<?php echo htmlspecialchars($data['announcement']['image_path'] ?? 'assets/img/default-ad.png', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-3 mb-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary" style="font-size: 0.95rem;"><i class="bi bi-gear text-primary me-1"></i> حالة الإعلان والتوقيت</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold mb-1">حالة العرض</label>
                                <select class="form-select" name="status">
                                    <option value="Draft" <?php echo (($data['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مخفي (مسودة)</option>
                                    <option value="Published" <?php echo (($data['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشط (يظهر للزوار)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold mb-1">تاريخ البدء</label>
                                <input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $data['announcement']['start_date'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold mb-1">تاريخ الانتهاء</label>
                                <input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $data['announcement']['end_date'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mb-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary" style="font-size: 0.95rem;"><i class="bi bi-pencil-square text-primary me-1"></i> محتوى الإعلان</div>
                        <label class="small fw-bold mb-1">نوع الإعلان:</label>
                        <select class="form-select mb-3" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo (($data['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك (اختر هذا لنص سريع)</option>
                            <option value="image" <?php echo (($data['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (بانر دعائي كامل)</option>
                        </select>

                        <div id="textEditor" class="<?php echo (($data['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                            <label class="small fw-bold mb-1">نص الإعلان (الرسالة التي ستظهر للزوار):</label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars($data['announcement']['announcement_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="small fw-bold mb-1">لون الخلفية</label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo htmlspecialchars($data['announcement']['bg_color'] ?? '#f1f5f9', ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1">لون الخط</label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo htmlspecialchars($data['announcement']['text_color'] ?? '#1e293b', ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1">حجم الخط</label>
                                    <input type="number" class="form-control" name="font_size" value="<?php echo htmlspecialchars($data['announcement']['font_size'] ?? '16', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo (($data['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                            <label class="small fw-bold mb-1">ارفع صورة الإعلان (يُفضل صيغة WebP أو PNG):</label>
                            <?php if (!empty($data['announcement']['image_path'])): ?>
                                <div class="mb-2 p-2 bg-light rounded border d-inline-block">
                                    <img src="<?php echo htmlspecialchars(get_image_url($data['announcement']['image_path']), ENT_QUOTES, 'UTF-8'); ?>" alt="معاينة الإعلان" class="img-thumbnail border-0 bg-transparent" style="max-height: 80px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="ad_image" style="height: auto; padding: 10px 16px;">
                        </div>
                    </div>

                    <div class="p-3 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-2 text-secondary" style="font-size: 0.95rem;"><i class="bi bi-link-45deg text-primary me-1"></i> رابط التوجيه (اختياري)</div>
                        <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($data['announcement']['link'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://">
                    </div>
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
                <h5 class="modal-title">
                    <i class="bi bi-list-nested text-primary"></i> إدارة القائمة الرئيسية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="menuLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_menu">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($menu_links ?? []) as $index => $link): ?>
                        <div class="p-3 shadow-sm menu-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="menu_row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-5">
                                    <label class="small fw-bold mb-1 text-secondary">عنوان الرابط</label>
                                    <input type="text" class="form-control menu-title" name="menu[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($link['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الرابط">
                                </div>
                                <div class="col-md-4">
                                    <label class="small fw-bold mb-1 text-secondary">الرابط (URL)</label>
                                    <input type="text" class="form-control menu-url" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط (URL)">
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-bold mb-1 text-secondary">الترتيب</label>
                                    <input type="number" class="form-control menu-order" name="menu[<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($link['order'] ?? $index, ENT_QUOTES, 'UTF-8'); ?>" placeholder="الترتيب">
                                </div>
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('menu_row_<?php echo $index; ?>')" title="حذف الرابط"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMenuRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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

<!-- 5. Lang Edit Modal -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-translate text-primary"></i> إدارة اللغات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="langEditForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_languages">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="langRowsContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($data['languages'])): ?>
                            <?php foreach ($data['languages'] as $index => $lang): ?>
                                <div class="p-3 shadow-sm lang-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <label class="small fw-bold mb-1 text-secondary">اسم اللغة</label>
                                            <input type="text" class="form-control lang-name" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($lang['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم اللغة">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                                            <input type="text" class="form-control lang-url" name="lang[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($lang['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('lang_row_<?php echo $index; ?>')" title="حذف اللغة"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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
                <form id="heroEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <?php $h = $data['hero'] ?? ['title'=>'', 'desc'=>'', 'btn_text'=>'', 'btn_url'=>'', 'img'=>'assets/img/hero-bg.jpg']; ?>
                    
                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                <input type="text" class="form-control" name="hero_title" value="<?php echo htmlspecialchars($h['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">النص الوصفي</label>
                                <textarea class="form-control" name="hero_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($h['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">نص الزر</label>
                                <input type="text" class="form-control" name="hero_btn_text" value="<?php echo htmlspecialchars($h['btn_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط الزر</label>
                                <input type="text" class="form-control" name="hero_btn_url" value="<?php echo htmlspecialchars($h['btn_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">صورة الخلفية</label>
                                
                                <!-- معاينة الصورة الحالية -->
                                <?php if (!empty($h['img'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <span class="d-block small text-muted mb-2">الصورة الحالية:</span>
                                        <img src="<?php echo htmlspecialchars(get_image_url($h['img']), ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Current Hero Image" 
                                             class="img-thumbnail rounded-3 border-0 bg-transparent" 
                                             style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="form-control" name="hero_img" accept="image/*">
                                <input type="hidden" name="old_hero_img" value="<?php echo htmlspecialchars($h['img'] ?? 'assets/img/hero-bg.jpg', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
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
                            <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم (اختياري)</label>
                            <textarea class="form-control" name="services_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['services_section_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="servicesRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['services'] ?? []) as $index => $service): ?>
                            <div class="p-3 shadow-sm service-row-item" id="service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                        <input type="text" class="form-control service-title" name="services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($service['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">الرابط</label>
                                        <input type="text" class="form-control service-url" name="services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($service['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                    </div>
                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary">الصورة / الأيقونة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- معاينة الصورة فقط إن وجدت -->
                                            <?php if (!empty($service['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($service['img']), ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Service Image" 
                                                         class="rounded-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control service-file" name="service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="service-old-img" name="services[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($service['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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
                <form id="chooseForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_choose">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="choose_title" value="<?php echo htmlspecialchars($data['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم (اختياري)</label>
                            <textarea class="form-control" name="choose_desc" rows="2" placeholder="أضف وصفاً هنا أو اتركه فارغاً للإخفاء" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['choose_section_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="chooseRowsContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($data['choose_items'])): ?>
                            <?php foreach ($data['choose_items'] as $index => $item): ?>
                                <div class="p-3 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="choose_row_<?php echo $index; ?>">
                                    <!-- الصف الأول: حقول العنوان والوصف -->
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="choose[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="choose[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                        </div>
                                    </div>

                                    <!-- الصف الثاني: الصورة الحالية، حقل الرفع، وزر الحذف -->
                                    <div class="row g-2 align-items-center">
                                        <div class="col">
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- معاينة الصورة الحالية إن وجدت -->
                                                <?php if (!empty($item['img'])): ?>
                                                    <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                        <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" 
                                                             alt="Choose Item Icon" 
                                                             class="rounded-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>

                                                <input type="file" class="form-control" name="choose_img_<?php echo $index; ?>">
                                            </div>
                                            <input type="hidden" name="choose[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn-icon-trash" onclick="removeRow('choose_row_<?php echo $index; ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                <form id="reviewsForm">
                    <input type="hidden" name="action" value="update_reviews">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="reviews_title" value="<?php echo htmlspecialchars($data['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="reviewsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['reviews_items'] ?? []) as $index => $review): ?>
                            <div class="p-3 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="rev_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col">
                                        <input type="text" class="form-control" name="reviews[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($review['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="رابط اليوتيوب (Embed URL)">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('rev_row_<?php echo $index; ?>')">
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
                <form id="guideForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="guide_title" value="<?php echo htmlspecialchars($data['guide_title'] ?? 'دليل بيتهوفن الشامل', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="guide_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['guide_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['guide_items'] ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="guide_row_<?php echo $index; ?>">
                                <!-- الصف الأول: العنوان والرابط -->
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="guide[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان المقال">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="guide[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="رابط الصفحة">
                                    </div>
                                </div>
                                
                                <!-- الصف الثاني: الصورة، الوصف، وزر الحذف -->
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Guide Item Image" 
                                                         class="rounded-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="guide_img_<?php echo $index; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="guide[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                    </div>
                                    
                                    <input type="hidden" name="guide[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_row_<?php echo $index; ?>')">
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
                <form id="faqForm">
                    <input type="hidden" name="action" value="update_faq">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="faq_title" value="<?php echo htmlspecialchars($data['faq_title'] ?? 'الأسئلة الشائعة', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="faqRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['faq_items'] ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="faq_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="faq[<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars($item['question'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="السؤال">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="faq[<?php echo $index; ?>][answer]" value="<?php echo htmlspecialchars($item['answer'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الإجابة">
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('faq_row_<?php echo $index; ?>')">
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
                <form id="footerForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_footer">
                    
                    <!-- قسم الاستشارة في الأعلى -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><i class="bi bi-chat-dots me-1"></i> إعدادات قسم الاستشارة</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary">عنوان الاستشارة</label>
                                        <input type="text" class="form-control" name="consult_title" value="<?php echo htmlspecialchars($data['consult_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small fw-bold mb-1 text-secondary">وصف الاستشارة</label>
                                        <input type="text" class="form-control" name="consult_desc" value="<?php echo htmlspecialchars($data['consult_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الصف الأول: العمود الأول (الوصف) والعمود الثاني (الروابط السريعة) -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الأول</h6>
                                <label class="small fw-bold mb-1 text-secondary">وصف الفوتر:</label>
                                <textarea class="form-control" name="footer_desc" rows="6" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['footer_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الثاني</h6>
                                <label class="small fw-bold mb-1 text-secondary">عنوان العمود</label>
                                <input type="text" class="form-control mb-3" name="footer_col2_title" value="<?php echo htmlspecialchars($data['footer_col2_title'] ?? 'روابط سريعة', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="p-3 bg-light rounded-3 text-muted small border" style="border-color: #e2e8f0 !important;">
                                    <i class="bi bi-info-circle me-1"></i> يتم جلب الروابط تلقائياً من <b>القائمة الرئيسية (Menu)</b>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الصف الثاني: العمود الثالث (التواصل) بعرض كامل ومريح جداً -->
                    <div class="row">
                        <div class="col-12">
                            <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">العمود الثالث (التواصل)</h6>
                                <div class="mb-3" style="max-width: 400px;">
                                    <label class="small fw-bold mb-1 text-secondary">عنوان العمود</label>
                                    <input type="text" class="form-control" name="footer_col3_title" value="<?php echo htmlspecialchars($data['footer_col3_title'] ?? 'تواصل معنا', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                
                                <div id="col3LinksContainer" class="d-flex flex-column gap-3">
                                    <?php foreach(($data['footer_col3_links'] ?? []) as $i => $link): ?>
                                        <div class="p-3 shadow-sm" id="col3_<?php echo $i; ?>" style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;">
                                            <div class="row g-3 align-items-center">
                                                
                                                <!-- الأيقونة وحقل الرفع -->
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if (!empty($link['img'])): ?>
                                                            <div class="p-1 bg-white rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                                <img src="<?php echo htmlspecialchars(get_image_url($link['img']), ENT_QUOTES, 'UTF-8'); ?>" 
                                                                     alt="Contact Icon" 
                                                                     class="rounded-2" 
                                                                     style="width: 36px; height: 36px; object-fit: cover;">
                                                            </div>
                                                        <?php endif; ?>
                                                        <input type="file" name="col3_img_<?php echo $i; ?>" class="form-control form-control-sm bg-white">
                                                    </div>
                                                </div>

                                                <!-- الاسم -->
                                                <div class="col-md-3">
                                                    <input type="text" name="col3[<?php echo $i; ?>][title]" class="form-control form-control-sm bg-white" value="<?php echo htmlspecialchars($link['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الوسيلة (مثلاً: واتساب)">
                                                </div>

                                                <!-- الرابط -->
                                                <div class="col-md-4">
                                                    <input type="text" name="col3[<?php echo $i; ?>][url]" class="form-control form-control-sm bg-white" value="<?php echo htmlspecialchars($link['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الرابط">
                                                </div>

                                                <!-- زر الحذف -->
                                                <div class="col-md-1 text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('col3_<?php echo $i; ?>')" style="border-radius: 8px;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="col3[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars($link['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <button type="button" class="btn w-100 mt-3 py-2" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 12px; transition: 0.2s; font-size: 13px;" onclick="addCol3Link()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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

    // دالة لإنشاء وعرض التنبيهات بطريقة عصرية باستخدام Bootstrap Toasts/Alerts
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

    let socialCount = <?php echo count($data['social_links'] ?? []); ?>;
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'row_' + socialCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control" name="social[${socialCount}][name]" placeholder="الاسم"></div>
                <div class="col-md-5"><input type="url" class="form-control" name="social[${socialCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-3"><input type="file" class="form-control" name="social_img_${socialCount}"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('row_${socialCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        socialCount++;
    }

    let menuCount = <?php echo count($menu_links ?? []); ?>;
    function addMenuRow() {
        const container = document.getElementById('menuRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'menu_row_' + menuCount;
        div.innerHTML = `
            <div class="row align-items-center g-2">
                <div class="col-md-4"><input type="text" class="form-control" name="menu[${menuCount}][title]" placeholder="عنوان الرابط"></div>
                <div class="col-md-5"><input type="text" class="form-control" name="menu[${menuCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-2"><input type="number" class="form-control" name="menu[${menuCount}][order]" value="${menuCount}" placeholder="الترتيب"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('menu_row_${menuCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        menuCount++;
    }

    let langCount = <?php echo count($data['languages'] ?? []); ?>;
    function addLangRow() {
        const container = document.getElementById('langRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'lang_row_' + langCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-5"><input type="text" class="form-control" name="lang[${langCount}][name]" placeholder="اسم اللغة"></div>
                <div class="col-md-6"><input type="text" class="form-control" name="lang[${langCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('lang_row_${langCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        langCount++;
    }

    let serviceCount = <?php echo count($data['services'] ?? []); ?>;
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

    let chooseCount = <?php echo count($data['choose_items'] ?? []); ?>;
    function addChooseRow() {
        const container = document.getElementById('chooseRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'choose_row_' + chooseCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control" name="choose[${chooseCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-4"><input type="text" class="form-control" name="choose[${chooseCount}][desc]" placeholder="الوصف"></div>
                <div class="col-md-4"><input type="file" class="form-control" name="choose_img_${chooseCount}"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('choose_row_${chooseCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        chooseCount++;
    }

    let reviewCount = <?php echo count($data['reviews_items'] ?? []); ?>;
    function addReviewRow() {
        const container = document.getElementById('reviewsRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'rev_row_' + reviewCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-11"><input type="text" class="form-control" name="reviews[${reviewCount}][url]" placeholder="رابط اليوتيوب"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('rev_row_${reviewCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        reviewCount++;
    }

    let guideCount = <?php echo count($data['guide_items'] ?? []); ?>;
    function addGuideRow() {
        const container = document.getElementById('guideRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'guide_row_' + guideCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-6 mb-2"><input type="text" class="form-control" name="guide[${guideCount}][title]" placeholder="عنوان المقال"></div>
                <div class="col-md-6 mb-2"><input type="text" class="form-control" name="guide[${guideCount}][url]" placeholder="رابط الصفحة"></div>
                <div class="col-md-6"><input type="file" class="form-control" name="guide_img_${guideCount}"></div>
                <div class="col-md-5"><textarea class="form-control" name="guide[${guideCount}][desc]" rows="1" placeholder="الوصف"></textarea></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('guide_row_${guideCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        guideCount++;
    }

    let faqCount = <?php echo count($data['faq_items'] ?? []); ?>;
    function addFaqRow() {
        const container = document.getElementById('faqRowsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'faq_row_' + faqCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-5"><input type="text" class="form-control" name="faq[${faqCount}][question]" placeholder="السؤال"></div>
                <div class="col-md-6"><input type="text" class="form-control" name="faq[${faqCount}][answer]" placeholder="الإجابة"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('faq_row_${faqCount}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        faqCount++;
    }

    let col3Count = <?php echo count($data['footer_col3_links'] ?? []); ?>;
    function addCol3Link() {
        const container = document.getElementById('col3LinksContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm mb-3';
        div.style.cssText = 'background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;';
        div.id = 'col3_' + col3Count;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4"><input type="file" name="col3_img_${col3Count}" class="form-control form-control-sm bg-white"></div>
                <div class="col-md-3"><input type="text" name="col3[${col3Count}][title]" class="form-control form-control-sm bg-white" placeholder="الاسم"></div>
                <div class="col-md-4"><input type="text" name="col3[${col3Count}][url]" class="form-control form-control-sm bg-white" placeholder="الرابط"></div>
                <div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm p-2 w-100" onclick="removeRow('col3_${col3Count}')" style="border-radius: 8px;"><i class="bi bi-trash"></i></button></div>
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

    // معالج النماذج الموحد مع التنبيهات الجديدة
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