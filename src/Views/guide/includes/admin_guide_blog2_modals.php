<?php
// ملف مودلات لوحة التحكم الخاصة بصفحة Guide Blog 2
// يتم تضمينه فقط إذا كان المستخدم المشرف مسجلاً للدخول ($is_admin === true)
?>

<!-- 1. Modal: تعديل مسار الصفحة (Breadcrumb) -->
<div class="modal fade custom-modal" id="guideBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار الصفحة (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_guide_blog2_breadcrumb">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">اسم المسار (Breadcrumb)</label>
                                <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط المسار (URL)</label>
                                <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal: تعديل صورة الغلاف (Hero) -->
<div class="modal fade custom-modal" id="guideHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الغلاف الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">صورة الغلاف (Hero Image)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($guide_data['hero_img'])): ?>
                                        <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                            <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($guide_data['hero_img']) : $guide_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Hero" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="guide_hero_img" accept="image/*">
                                </div>
                                <input type="hidden" name="old_guide_hero_img" value="<?php echo htmlspecialchars($guide_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal: تعديل العنوان الرئيسي والوصف (Main Content) -->
<div class="modal fade custom-modal" id="guideMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان الرئيسي والوصف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_guide_blog2_main">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">العنوان الرئيسي (Main Title)</label>
                                <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">الوصف الرئيسي (Main Description)</label>
                                <textarea class="form-control" name="main_desc" rows="4" style="height: auto;"><?php echo htmlspecialchars($guide_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal: تعديل خدمات بيتهوفن (Services / Advice Points) -->
<div class="modal fade custom-modal" id="guideNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-text text-primary"></i> تعديل قسم خدمات بيتهوفن سيتي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideNotesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_guide_blog2_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان قسم الخدمات</label>
                            <input type="text" class="form-control" name="services_advice_title" value="<?php echo htmlspecialchars($guide_data['services_advice_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        
                        <hr class="my-4">
                        
                        <label class="small fw-bold mb-2 text-secondary">عناصر الخدمات</label>
                        <div id="guideNotesContainer" class="d-flex flex-column gap-3">
                            <?php if (!empty($guide_data['services_advice_points']) && is_array($guide_data['services_advice_points'])): ?>
                                <?php foreach ($guide_data['services_advice_points'] as $index => $point): ?>
                                    <div class="p-3 shadow-sm guide-note-row-item" id="note_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">عنوان الخدمة</label>
                                                <input type="text" class="form-control guide-note-title" name="services_advice_points[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(is_array($point) ? ($point['title'] ?? '') : $point, ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الخدمة">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">وصف الخدمة</label>
                                                <textarea class="form-control guide-note-text" name="services_advice_points[<?php echo $index; ?>][desc]" rows="2" style="height: auto;" placeholder="الوصف"><?php echo htmlspecialchars(is_array($point) ? ($point['desc'] ?? '') : '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('note_row_<?php echo $index; ?>')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 fw-bold" onclick="addGuideNoteRow()">
                                <i class="bi bi-plus-circle me-1"></i> إضافة خدمة جديدة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal: تعديل بطاقات لماذا الدراسة والعمل (Why Cards) -->
<div class="modal fade custom-modal" id="guideWhyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-grid text-primary"></i> تعديل قسم لماذا الدراسة والعمل في ألمانيا؟</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_why">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                                <input type="text" class="form-control" name="why_title" value="<?php echo htmlspecialchars($guide_data['why_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                                <textarea class="form-control" name="why_desc" rows="3" style="height: auto;"><?php echo htmlspecialchars($guide_data['why_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <label class="small fw-bold mb-2 text-secondary">بطاقات الميزات</label>
                        <div id="guideWhyContainer" class="d-flex flex-column gap-3">
                            <?php if (!empty($guide_data['why_cards']) && is_array($guide_data['why_cards'])): ?>
                                <?php foreach ($guide_data['why_cards'] as $index => $card): ?>
                                    <div class="p-3 shadow-sm guide-why-row-item" id="why_card_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                                                <input type="text" class="form-control guide-why-title" name="why_cards[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($card['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">محتوى البطاقة</label>
                                                <input type="text" class="form-control guide-why-text" name="why_cards[<?php echo $index; ?>][text]" value="<?php echo htmlspecialchars($card['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                            </div>
                                        </div>
                                        <div class="row g-2 align-items-end">
                                            <div class="col-11">
                                                <label class="form-label fw-semibold small text-secondary">أيقونة البطاقة</label>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <?php if (!empty($card['img'])): ?>
                                                        <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($card['img']) : $card['img'], ENT_QUOTES, 'UTF-8'); ?>" class="rounded" style="width: 30px; height: 30px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control guide-why-file" name="why_img_<?php echo $index; ?>" accept="image/*">
                                                </div>
                                            </div>
                                            <input type="hidden" class="guide-why-old-img" name="why_cards[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($card['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <div class="col-1 text-center pb-1">
                                                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('why_card_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 fw-bold" onclick="addGuideWhyCardRow()">
                                <i class="bi bi-plus-circle me-1"></i> إضافة بطاقة جديدة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideWhyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Modal: تعديل التايم لاين / الخطوات (Timeline) -->
<div class="modal fade custom-modal" id="guideTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-ol text-primary"></i> تعديل خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_timeline">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                                <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_data['timeline_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                                <textarea class="form-control" name="timeline_desc" rows="3" style="height: auto;"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <label class="small fw-bold mb-2 text-secondary">الخطوات</label>
                        <div id="guideTimelineContainer" class="d-flex flex-column gap-3">
                            <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
                                <?php foreach ($guide_data['timeline_steps'] as $index => $step): ?>
                                    <div class="p-3 shadow-sm guide-timeline-row-item" id="guide_step_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">عنوان الخطوة</label>
                                                <input type="text" class="form-control guide-step-title" name="timeline[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                                <input type="text" class="form-control guide-step-subtitle" name="timeline[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="فرعي">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                                                <input type="text" class="form-control guide-step-desc" name="timeline[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف">
                                            </div>
                                        </div>
                                        <div class="row g-2 align-items-end">
                                            <div class="col-11">
                                                <label class="form-label fw-semibold small text-secondary">أيقونة الخطوة</label>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <?php if (!empty($step['icon'])): ?>
                                                        <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($step['icon']) : $step['icon'], ENT_QUOTES, 'UTF-8'); ?>" class="rounded" style="width: 30px; height: 30px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control guide-step-file" name="timeline_icon_<?php echo $index; ?>" accept="image/*">
                                                </div>
                                            </div>
                                            <input type="hidden" class="guide-step-old-icon" name="timeline[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <div class="col-1 text-center pb-1">
                                                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_step_row_<?php echo $index; ?>')" title="حذف"><i class="bi bi-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 fw-bold" onclick="addGuideTimelineRow()">
                                <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideTimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript الخاص بإدارة المودلات والإرسال الديناميكي -->
<script>
    // حذف صف ديناميكي
    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) row.remove();
    }

    // إضافة خدمة جديدة ديناميكياً
    function addGuideNoteRow() {
        const container = document.getElementById('guideNotesContainer');
        if (!container) return;
        const count = container.querySelectorAll('.guide-note-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm guide-note-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'note_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">عنوان الخدمة</label>
                    <input type="text" class="form-control guide-note-title" name="services_advice_points[${count}][title]" placeholder="عنوان الخدمة">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">وصف الخدمة</label>
                    <textarea class="form-control guide-note-text" name="services_advice_points[${count}][desc]" rows="2" style="height: auto;" placeholder="الوصف"></textarea>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
            </div>`;
        container.appendChild(div);
    }

    // إضافة بطاقة لماذا الدراسة جديدة ديناميكياً
    function addGuideWhyCardRow() {
        const container = document.getElementById('guideWhyContainer');
        if (!container) return;
        const count = container.querySelectorAll('.guide-why-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm guide-why-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'why_card_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                    <input type="text" class="form-control guide-why-title" name="why_cards[${count}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">محتوى البطاقة</label>
                    <input type="text" class="form-control guide-why-text" name="why_cards[${count}][text]" placeholder="الوصف">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">أيقونة البطاقة</label>
                    <input type="file" class="form-control guide-why-file" name="why_img_${count}" accept="image/*">
                </div>
                <input type="hidden" class="guide-why-old-img" name="why_cards[${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // إضافة خطوة تايم لاين جديدة ديناميكياً
    function addGuideTimelineRow() {
        const container = document.getElementById('guideTimelineContainer');
        if (!container) return;
        const count = container.querySelectorAll('.guide-timeline-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm guide-timeline-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'guide_step_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">عنوان الخطوة</label>
                    <input type="text" class="form-control guide-step-title" name="timeline[${count}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                    <input type="text" class="form-control guide-step-subtitle" name="timeline[${count}][subtitle]" placeholder="فرعي">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                    <input type="text" class="form-control guide-step-desc" name="timeline[${count}][desc]" placeholder="الوصف">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">أيقونة الخطوة</label>
                    <input type="file" class="form-control guide-step-file" name="timeline_icon_${count}" accept="image/*">
                </div>
                <input type="hidden" class="guide-step-old-icon" name="timeline[${count}][old_icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج إعادة الترقيم وإرسال البيانات عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('#guideNotesForm, #guideWhyForm, #guideTimelineForm, #guideBreadcrumbForm, #guideHeroForm, #guideMainForm');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // 1. إعادة ترقيم الخدمات (الملاحظات)
                this.querySelectorAll('.guide-note-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.guide-note-title');
                    const textInput = row.querySelector('.guide-note-text');
                    if (titleInput) titleInput.name = `services_advice_points[${index}][title]`;
                    if (textInput) textInput.name = `services_advice_points[${index}][desc]`;
                });

                // 2. إعادة ترقيم بطاقات لماذا الدراسة
                this.querySelectorAll('.guide-why-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.guide-why-title');
                    const textInput = row.querySelector('.guide-why-text');
                    const fileInput = row.querySelector('.guide-why-file');
                    const oldImgInput = row.querySelector('.guide-why-old-img');
                    if (titleInput) titleInput.name = `why_cards[${index}][title]`;
                    if (textInput) textInput.name = `why_cards[${index}][text]`;
                    if (fileInput) fileInput.name = `why_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `why_cards[${index}][old_img]`;
                });

                // 3. إعادة ترقيم التايم لاين
                this.querySelectorAll('.guide-timeline-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.guide-step-title');
                    const subtitleInput = row.querySelector('.guide-step-subtitle');
                    const descInput = row.querySelector('.guide-step-desc');
                    const fileInput = row.querySelector('.guide-step-file');
                    const oldIconInput = row.querySelector('.guide-step-old-icon');
                    if (titleInput) titleInput.name = `timeline[${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `timeline[${index}][subtitle]`;
                    if (descInput) descInput.name = `timeline[${index}][desc]`;
                    if (fileInput) fileInput.name = `timeline_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `timeline[${index}][old_icon]`;
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
                        if (data.success) {
                            if (typeof showNotification === 'function') {
                                showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            }
                            setTimeout(() => {
                                location.reload();
                            }, 800);
                        } else {
                            if (typeof showNotification === 'function') {
                                showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'فشل الحفظ'), 'danger');
                            } else {
                                alert('فشل الحفظ: ' + (data.message || ''));
                            }
                        }
                    } catch (e) {
                        if (typeof showNotification === 'function') {
                            showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                        } else {
                            console.error(text);
                        }
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    if (typeof showNotification === 'function') {
                        showNotification('حدث خطأ أثناء الاتصال بالسيرفر، يرجى المحاولة لاحقاً.', 'danger');
                    }
                });
            });
        });
    });
</script>
