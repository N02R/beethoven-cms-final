<!-- Modal: تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade" id="guideBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideBreadcrumbForm" method="POST">
                <input type="hidden" name="action" value="update_guide_breadcrumb">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل مسار التنقل</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">عنوان مسار الصفحة (Breadcrumb Text):</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? 'دليل الطالب'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">رابط مسار الصفحة:</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#'); ?>">
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

<!-- Modal: تعديل صورة الهيرو (Hero Image) -->
<div class="modal fade" id="guideHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideHeroForm" method="POST">
                <input type="hidden" name="action" value="update_guide_hero">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل صورة الهيرو</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">مسار/رابط صورة الهيرو:</label>
                        <input type="text" class="form-control" name="hero_img" value="<?php echo htmlspecialchars($guide_data['hero_img'] ?? 'assets/img/home/image(0).jpg'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">محاذاة الخلفية (Hero Position):</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center'); ?>">
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

<!-- Modal: تعديل العنوان والوصف الرئيسي (Main Info) -->
<div class="modal fade" id="guideMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideMainForm" method="POST">
                <input type="hidden" name="action" value="update_guide_main">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل العنوان والوصف</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">العنوان الرئيسي (Main Title):</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_data['main_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">الوصف الرئيسي (Main Description):</label>
                        <textarea class="form-control" name="main_desc" rows="4" style="height: auto;"><?php echo htmlspecialchars($guide_data['main_desc'] ?? ''); ?></textarea>
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

<!-- Modal: تعديل الملاحظات الهامة (Important Notes) -->
<div class="modal fade" id="guideNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form id="guideNotesForm" method="POST">
                <input type="hidden" name="action" value="update_guide_notes">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تعديل الملاحظات الهامة</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4" style="max-height: 75vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">عنوان الملاحظات:</label>
                        <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($guide_data['notes_title'] ?? 'ملاحظات هامة جداً'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">النص البارز للملاحظة الأولى:</label>
                        <input type="text" class="form-control" name="note_1_bold" value="<?php echo htmlspecialchars($guide_data['note_1_bold'] ?? ''); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">ملاحظة فصل الشتاء:</label>
                            <input type="text" class="form-control" name="note_winter" value="<?php echo htmlspecialchars($guide_data['note_winter'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">ملاحظة فصل الصيف:</label>
                            <input type="text" class="form-control" name="note_summer" value="<?php echo htmlspecialchars($guide_data['note_summer'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">نص الملاحظة الثانية:</label>
                        <input type="text" class="form-control" name="note_2_text" value="<?php echo htmlspecialchars($guide_data['note_2_text'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">عنوان الملاحظة الثالثة:</label>
                        <input type="text" class="form-control" name="note_3_title" value="<?php echo htmlspecialchars($guide_data['note_3_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">نص السؤال الشائع الأول (FAQ 1):</label>
                        <input type="text" class="form-control" name="faq_1" value="<?php echo htmlspecialchars($guide_data['faq_1'] ?? ''); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">بادئة الرابط (FAQ 2 Prefix):</label>
                            <input type="text" class="form-control" name="faq_2_prefix" value="<?php echo htmlspecialchars($guide_data['faq_2_prefix'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">مسار الرابط (FAQ 2 URL):</label>
                            <input type="text" class="form-control" name="faq_2_url" value="<?php echo htmlspecialchars($guide_data['faq_2_url'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">نص رابط الـ FAQ:</label>
                            <input type="text" class="form-control" name="faq_2_link_text" value="<?php echo htmlspecialchars($guide_data['faq_2_link_text'] ?? ''); ?>">
                        </div>
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

<!-- Modal: تعديل قسم لماذا الدراسة (Why Study) -->
<div class="modal fade" id="guideWhyStudyModal" tabindex="-1" aria-hidden="true">
    <!-- محتوى مودل لماذا الدراسة -->
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
    <!-- محتوى مودل الخطوات الزمنية -->
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

<script>
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

    // 2. دالة عامة لحذف أي صف ديناميكي
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 3. معالج الحفظ الموحد عبر AJAX للأشكال الخاصة بصفحة الدليل (Guide)
    document.addEventListener('DOMContentLoaded', function() {
        
        // تفعيل أزرار القلم لفتح المودلز برمجياً وضمان عدم تعارض الـ DOM
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
