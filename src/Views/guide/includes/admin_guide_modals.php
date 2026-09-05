<!-- Modal: تعديل قسم لماذا الدراسة (Why Study) -->
<div class="modal fade" id="guideWhyStudyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideWhyStudyForm" method="POST">
                <input type="hidden" name="action" value="update_guide_whystudy">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل قسم "لماذا الدراسة"</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4" style="max-height: 75vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">عنوان القسم الرئيسي:</label>
                        <input type="text" class="form-control" name="why_study_title" value="<?php echo htmlspecialchars($guide_data['why_study_title'] ?? 'لماذا الدراسة في ألمانيا؟'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">وصف القسم:</label>
                        <textarea class="form-control" name="why_study_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? ''); ?></textarea>
                    </div>
                    <hr>
                    <label class="form-label fw-bold text-dark mb-2">عناصر الكروت (المميزات):</label>
                    <div id="guideWhyStudyContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['content_sections']) && is_array($guide_data['content_sections'])): ?>
                            <?php foreach ($guide_data['content_sections'] as $i => $section): ?>
                                <div class="p-3 shadow-sm d-flex flex-column gap-2" id="whystudy_row_<?php echo $i; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="content_sections[<?php echo $i; ?>][heading]" value="<?php echo htmlspecialchars($section['heading'] ?? ''); ?>" placeholder="عنوان الكارت">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('whystudy_row_<?php echo $i; ?>')" title="حذف العنصر">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="content_sections[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($section['icon'] ?? ''); ?>" placeholder="مسار الأيقونة (اختياري)">
                                    <textarea class="form-control" name="content_sections[<?php echo $i; ?>][body]" rows="2" placeholder="وصف الكارت..." style="height: auto;"><?php echo htmlspecialchars($section['body'] ?? ''); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold" style="border-radius: 12px;" onclick="addWhyStudyRow()">
                            <i class="bi bi-plus-lg me-1"></i> إضافة كارت جديد
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4" style="border-radius: 10px;" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: تعديل قسم الخطوات الزمنية (Timeline) -->
<div class="modal fade" id="guideTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideTimelineForm" method="POST">
                <input type="hidden" name="action" value="update_guide_timeline">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل خطوات الرحلة (Timeline)</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4" style="max-height: 75vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">عنوان القسم الرئيسي:</label>
                        <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_data['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">وصف القسم:</label>
                        <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? ''); ?></textarea>
                    </div>
                    <hr>
                    <label class="form-label fw-bold text-dark mb-2">الخطوات:</label>
                    <div id="guideTimelineContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
                            <?php foreach ($guide_data['timeline_steps'] as $i => $step): ?>
                                <div class="p-3 shadow-sm d-flex flex-column gap-2" id="timeline_row_<?php echo $i; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="timeline_steps[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>" placeholder="عنوان الخطوة الرئيسي">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('timeline_row_<?php echo $i; ?>')" title="حذف العنصر">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? ''); ?>" placeholder="العنوان الفرعي">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][dot_class]" value="<?php echo htmlspecialchars($step['dot_class'] ?? 'bg-blue'); ?>" placeholder="لون النقطة (مثال: bg-blue)">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($step['icon'] ?? ''); ?>" placeholder="مسار الأيقونة (اختياري)">
                                    <textarea class="form-control" name="timeline_steps[<?php echo $i; ?>][desc]" rows="2" placeholder="تفاصيل الخطوة..." style="height: auto;"><?php echo htmlspecialchars($step['desc'] ?? ''); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold" style="border-radius: 12px;" onclick="addTimelineRow()">
                            <i class="bi bi-plus-lg me-1"></i> إضافة خطوة جديدة
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4" style="border-radius: 10px;" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine for Guide Page Modals & Forms -->
<script>
    // 1. دالة عامة لحذف أي صف ديناميكي
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية
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

    // 3. إضافة صف كارت جديد (Why Study)
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

    // 4. إضافة خطوة زمنية جديدة (Timeline)
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

    // 5. تفعيل فتح المودلز برمجياً ومعالجة الحفظ عبر AJAX عند تحميل الصفحة بالكامل
    document.addEventListener('DOMContentLoaded', function() {
        
        // تفعيل أزرار القلم لفتح المودلز حصرياً وبرمجياً لضمان عدم تعارض الـ DOM
        document.querySelectorAll('.edit-pen').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSelector = this.getAttribute('data-bs-target');
                if (targetSelector) {
                    const modalEl = document.querySelector(targetSelector);
                    if (modalEl) {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (!modalInstance) {
                                modalInstance = new bootstrap.Modal(modalEl);
                            }
                            modalInstance.show();
                        } else {
                            // بديل احتياطي في حال تأخر تحميل كلاسات بوتستراب
                            modalEl.classList.add('show');
                            modalEl.style.display = 'block';
                            document.body.classList.add('modal-open');
                        }
                    }
                }
            });
        });

        // معالجة إرسال النماذج عبر AJAX
        document.querySelectorAll('#guideBreadcrumbForm, #guideHeroForm, #guideMainForm, #guideNotesForm, #guideWhyStudyForm, #guideTimelineForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('index.php?url=admin/settings/save', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('فشل الحفظ: ' + (data.message || 'خطأ غير معروف'), 'danger');
                        }
                    } catch (e) {
                        showNotification('خطأ استجابة السيرفر: ' + text, 'danger');
                    }
                })
                .catch(err => {
                    showNotification('حدث خطأ أثناء الاتصال بالسيرفر.', 'danger');
                });
            });
        });
    });
</script>


