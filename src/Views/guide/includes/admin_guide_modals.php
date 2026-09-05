<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="guideBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_breadcrumb">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
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

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="guideHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($guide_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($guide_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($guide_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">مسار/رابط صورة الهيرو (أو ارفع صورة جديدة)</label>
                            <input type="text" class="form-control mb-2" name="hero_img" value="<?php echo htmlspecialchars($guide_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="file" class="form-control" name="hero_img_file" accept="image/*">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">محاذاة الخلفية (Hero Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>" placeholder="center center">
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

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="guideMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideMainForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
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

<!-- 4. Important Notes Edit Modal -->
<div class="modal fade custom-modal" id="guideNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-text text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_notes">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($guide_data['notes_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">النقطة الأولى (الرئيسية)</label>
                            <input type="text" class="form-control" name="note_1_bold" value="<?php echo htmlspecialchars($guide_data['note_1_bold'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">فصل الشتاء</label>
                                <input type="text" class="form-control" name="note_winter" value="<?php echo htmlspecialchars($guide_data['note_winter'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">فصل الصيف</label>
                                <input type="text" class="form-control" name="note_summer" value="<?php echo htmlspecialchars($guide_data['note_summer'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">نص النقطة الثانية</label>
                            <input type="text" class="form-control" name="note_2_text" value="<?php echo htmlspecialchars($guide_data['note_2_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان النقطة الثالثة</label>
                            <input type="text" class="form-control" name="note_3_title" value="<?php echo htmlspecialchars($guide_data['note_3_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">السؤال / التفريع الأول (FAQ 1)</label>
                            <input type="text" class="form-control" name="faq_1" value="<?php echo htmlspecialchars($guide_data['faq_1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">بادئة الرابط (Prefix)</label>
                                <input type="text" class="form-control" name="faq_2_prefix" value="<?php echo htmlspecialchars($guide_data['faq_2_prefix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">رابط زر التفاصيل (URL)</label>
                                <input type="text" class="form-control" name="faq_2_url" value="<?php echo htmlspecialchars($guide_data['faq_2_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">نص الرابط</label>
                                <input type="text" class="form-control" name="faq_2_link_text" value="<?php echo htmlspecialchars($guide_data['faq_2_link_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
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

<!-- 5. Why Study Edit Modal -->
<div class="modal fade custom-modal" id="guideWhyStudyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-grid text-primary"></i> تعديل قسم لماذا الدراسة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideWhyStudyForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_whystudy">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="why_study_title" value="<?php echo htmlspecialchars($guide_data['why_study_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="why_study_desc" rows="2" style="height: auto;" required><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">الكروت والبطاقات</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWhyStudyRow()">
                            <i class="bi bi-plus-lg"></i> إضافة كارت جديد
                        </button>
                    </div>

                    <div id="guideWhyStudyContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['content_sections']) && is_array($guide_data['content_sections'])): ?>
                            <?php foreach ($guide_data['content_sections'] as $i => $section): ?>
                                <div class="p-3 shadow-sm d-flex flex-column gap-2" id="whystudy_row_<?php echo $i; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="content_sections[<?php echo $i; ?>][heading]" value="<?php echo htmlspecialchars($section['heading'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الكارت">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('whystudy_row_<?php echo $i; ?>')" title="حذف العنصر">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="content_sections[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($section['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مسار الأيقونة (اختياري)">
                                    <textarea class="form-control" name="content_sections[<?php echo $i; ?>][body]" rows="2" placeholder="وصف الكارت..." style="height: auto;"><?php echo htmlspecialchars($section['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideWhyStudyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Timeline Edit Modal -->
<div class="modal fade custom-modal" id="guideTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-clock-history text-primary"></i> تعديل خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideTimelineForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_timeline">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_data['timeline_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto;" required><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">الخطوات الزمنية</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTimelineRow()">
                            <i class="bi bi-plus-lg"></i> إضافة خطوة جديدة
                        </button>
                    </div>

                    <div id="guideTimelineContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
                            <?php foreach ($guide_data['timeline_steps'] as $i => $step): ?>
                                <div class="p-3 shadow-sm d-flex flex-column gap-2" id="timeline_row_<?php echo $i; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="timeline_steps[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الخطوة الرئيسي">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('timeline_row_<?php echo $i; ?>')" title="حذف العنصر">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان الفرعي">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][dot_class]" value="<?php echo htmlspecialchars($step['dot_class'] ?? 'bg-blue', ENT_QUOTES, 'UTF-8'); ?>" placeholder="لون النقطة">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مسار الأيقونة (اختياري)">
                                    <textarea class="form-control" name="timeline_steps[<?php echo $i; ?>][desc]" rows="2" placeholder="تفاصيل الخطوة..." style="height: auto;"><?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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

<!-- Dynamic JS Engine -->
<script>
    // دالة عامة لحذف أي صف ديناميكي
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 1. دالة إظهار التنبيهات الاحترافية الموحدة
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

    // عدادات الصفوف الديناميكية
    let whyStudyCounter = <?php echo count($guide_data['content_sections'] ?? []); ?>;
    function addWhyStudyRow() {
        const container = document.getElementById('guideWhyStudyContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm d-flex flex-column gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'whystudy_row_' + whyStudyCounter;
        div.id = rowId;
        
        div.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control fw-bold" name="content_sections[${whyStudyCounter}][heading]" placeholder="عنوان الكارت">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف العنصر">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <input type="text" class="form-control" name="content_sections[${whyStudyCounter}][icon]" placeholder="مسار الأيقونة (اختياري)">
            <textarea class="form-control" name="content_sections[${whyStudyCounter}][body]" rows="2" placeholder="وصف الكارت..." style="height: auto;"></textarea>
        `;
        container.appendChild(div);
        whyStudyCounter++;
    }

    let timelineCounter = <?php echo count($guide_data['timeline_steps'] ?? []); ?>;
    function addTimelineRow() {
        const container = document.getElementById('guideTimelineContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm d-flex flex-column gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'timeline_row_' + timelineCounter;
        div.id = rowId;
        
        div.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control fw-bold" name="timeline_steps[${timelineCounter}][title]" placeholder="عنوان الخطوة الرئيسي">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف العنصر">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="timeline_steps[${timelineCounter}][subtitle]" placeholder="العنوان الفرعي">
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="timeline_steps[${timelineCounter}][dot_class]" value="bg-blue" placeholder="لون النقطة">
                </div>
            </div>
            <input type="text" class="form-control" name="timeline_steps[${timelineCounter}][icon]" placeholder="مسار الأيقونة (اختياري)">
            <textarea class="form-control" name="timeline_steps[${timelineCounter}][desc]" rows="2" placeholder="تفاصيل الخطوة..." style="height: auto;"></textarea>
        `;
        container.appendChild(div);
        timelineCounter++;
    }

    // 2. معالج الحفظ الموحد عبر AJAX لنماذج صفحة الدليل
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#guideBreadcrumbForm, #guideHeroForm, #guideMainForm, #guideNotesForm, #guideWhyStudyForm, #guideTimelineForm').forEach(form => {
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
