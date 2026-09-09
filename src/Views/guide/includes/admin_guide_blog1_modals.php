<?php
/**
 * موديلات لوحة التحكم الخاصة بصفحة مقال الدليل الأول (لماذا يختار الطلاب الدراسة في ألمانيا)
 * تتبع نفس معمارية وهيكلية الـ Modals في admin_edu_modals.php
 */
?>

<!-- 1. Modal: تعديل مسار وخبزات المقال (Breadcrumb) -->
<div class="modal fade custom-modal" id="guideBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideBreadcrumbForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog1_breadcrumb">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">نص عنوان المقال في المسار</label>
                                <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط المسار (URL)</label>
                                <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
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
                    <input type="hidden" name="action" value="update_guide_blog1_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">صورة الغلاف (Hero Image)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($guide_data['hero_img'])): ?>
                                        <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                            <img src="<?php echo htmlspecialchars(get_image_url($guide_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
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

<!-- 3. Modal: تعديل العنوان والمحتوى الرئيسي -->
<div class="modal fade custom-modal" id="guideMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text text-primary"></i> تعديل المقدمة والعنوان الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideMainForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog1_main">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">العنوان الرئيسي للمقال</label>
                                <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">النص التمهيدي / الوصف</label>
                                <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($guide_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
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

<!-- 4. Modal: تعديل الملاحظات الهامة جداً (Advice Stars) -->
<div class="modal fade custom-modal" id="guideNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> إدارة ملاحظات هامة جداً</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideNotesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog1_notes">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($guide_data['notes_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <div id="guideNotesContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($guide_data['notes_items'] ?? []) as $index => $note): ?>
                            <div class="p-3 shadow-sm guide-note-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="note_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">عنوان الملاحظة (رئيسي)</label>
                                        <input type="text" class="form-control guide-note-title" name="notes[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($note['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">نص الملاحظة التوضيحي</label>
                                        <textarea class="form-control guide-note-text" name="notes[<?php echo $index; ?>][text]" rows="2" style="height: auto;"><?php echo htmlspecialchars($note['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('note_row_<?php echo $index; ?>')" title="حذف الملاحظة"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal: تعديل بطاقات "لماذا الدراسة في ألمانيا" -->
<div class="modal fade custom-modal" id="guideWhyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-grid-3x3-gap text-primary"></i> إدارة بطاقات "لماذا الدراسة في ألمانيا؟"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog1_why">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="why_title" value="<?php echo htmlspecialchars($guide_data['why_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="why_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($guide_data['why_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($guide_data['why_cards'] ?? []) as $index => $card): ?>
                            <div class="p-3 shadow-sm guide-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="why_card_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                                        <input type="text" class="form-control guide-why-title" name="why_cards[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($card['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">محتوى البطاقة</label>
                                        <input type="text" class="form-control guide-why-text" name="why_cards[<?php echo $index; ?>][text]" value="<?php echo htmlspecialchars($card['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-end">
                                    <div class="col-11">
                                        <label class="form-label fw-semibold small text-secondary">أيقونة البطاقة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($card['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($card['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control guide-why-file" name="why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="guide-why-old-img" name="why_cards[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($card['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('why_card_row_<?php echo $index; ?>')" title="حذف البطاقة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideWhyCardRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة بطاقة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideWhyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Modal: تعديل خطوات الرحلة (Timeline) -->
<div class="modal fade custom-modal" id="guideTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3 text-primary"></i> إدارة خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog1_timeline">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_data['timeline_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($guide_data['timeline_steps'] ?? []) as $index => $step): ?>
                            <div class="p-3 shadow-sm guide-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="guide_step_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">عنوان الخطوة الرئيسي</label>
                                        <input type="text" class="form-control guide-step-title" name="timeline[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                        <input type="text" class="form-control guide-step-subtitle" name="timeline[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">وصف تفصيلي للخطوة</label>
                                        <input type="text" class="form-control guide-step-desc" name="timeline[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="row g-2 align-items-end">
                                    <div class="col-11">
                                        <label class="form-label fw-semibold small text-secondary">أيقونة الخطوة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($step['icon']), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control guide-step-file" name="timeline_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="guide-step-old-icon" name="timeline[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_step_row_<?php echo $index; ?>')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideTimelineRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideTimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<script>
    // الدوال الخاصة بإضافة عناصر جديدة ديناميكياً
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
                    <label class="form-label fw-semibold small text-secondary">عنوان الملاحظة</label>
                    <input type="text" class="form-control guide-note-title" name="notes[${count}][title]" placeholder="عنوان الملاحظة">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">نص الملاحظة</label>
                    <textarea class="form-control guide-note-text" name="notes[${count}][text]" rows="2" style="height: auto;" placeholder="النص"></textarea>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الملاحظة"><i class="bi bi-trash"></i></button>
            </div>`;
        container.appendChild(div);
    }

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

    // معالج إعادة الترقيم وإرسال البيانات عبر AJAX بنفس آلية admin_edu_modals.php
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('#guideNotesForm, #guideWhyForm, #guideTimelineForm, #guideBreadcrumbForm, #guideHeroForm, #guideMainForm');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // 1. إعادة ترقيم الملاحظات
                this.querySelectorAll('.guide-note-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.guide-note-title');
                    const textInput = row.querySelector('.guide-note-text');
                    if (titleInput) titleInput.name = `notes[${index}][title]`;
                    if (textInput) textInput.name = `notes[${index}][text]`;
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
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'فشل الحفظ'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('حدث خطأ أثناء الاتصال بالسيرفر، يرجى المحاولة لاحقاً.', 'danger');
                });
            });
        });
    });
</script>
