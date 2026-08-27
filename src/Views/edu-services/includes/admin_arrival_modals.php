<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="arrivalBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="arrivalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($arrival_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($arrival_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. Modal 1: تعديل العنوان والوصف الرئيسي فقط -->
<div class="modal fade custom-modal" id="arrivalMainTitleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalMainTitleForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_main_title">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($arrival_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($arrival_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalMainTitleForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal 2: تعديل النصائح والإرشادات (Tips) فقط -->
<div class="modal fade custom-modal" id="arrivalTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-square text-primary"></i> تعديل التوصيات والنصائح قبل السفر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_tips">
                    
                    <!-- حاوية معلومات القسم بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                                <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($arrival_data['advice_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">الوصف الفرعي</label>
                                <input type="text" class="form-control" name="advice_desc" value="<?php echo htmlspecialchars($arrival_data['advice_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / حذف / إضافة)</label>
                    <div id="tipsRowsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($arrival_data['tips'])): ?>
                            <?php foreach ($arrival_data['tips'] as $index => $tip): ?>
                                <div class="p-3 shadow-sm tip-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="tip_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips[]" value="<?php echo htmlspecialchars($tip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('tip_row_<?php echo $index; ?>')" title="حذف النصيحة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addTipRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal 3: تعديل الملاحظات الهامة (Notes) فقط -->
<div class="modal fade custom-modal" id="arrivalNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل صندوق الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_notes">
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان صندوق الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($arrival_data['note_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملاحظات (تعديل / حذف / إضافة)</label>
                    <div id="notesRowsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($arrival_data['notes'])): ?>
                            <?php foreach ($arrival_data['notes'] as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="note_row_<?php echo $index; ?>">
                                    <!-- استخدام input بمسافات منسقة ومتطابقة مع باقي المودلات -->
                                    <input type="text" class="form-control" name="notes[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الملاحظة هنا...">
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeRow('note_row_<?php echo $index; ?>')" title="حذف الملاحظة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملاحظة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- Dynamic JS Engine -->
<script>
    // 1. دالة عامة لحذف أي صف
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية الموحدة
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

    // 3. إضافة صف نصيحة جديدة بالستايل الموحد
    let tipIndex = <?php echo count($arrival_data['tips'] ?? []); ?>;
    function addTipRow() {
        const container = document.getElementById('tipsRowsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm tip-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'tip_row_' + tipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('tip_row_${tipIndex}')" title="حذف النصيحة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        tipIndex++;
    }

    // 4. إضافة صف ملاحظة جديدة بالستايل الموحد
    let noteIndex = <?php echo count($arrival_data['notes'] ?? []); ?>;
    function addNoteRow() {
        const container = document.getElementById('notesRowsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'note_row_' + noteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="notes[]" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب الملاحظة هنا..."></textarea>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('note_row_${noteIndex}')" title="حذف الملاحظة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // 5. معالج الحفظ الموحد عبر AJAX (مع فحص استجابة السيرفر وتفادي أخطاء 404)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#arrivalBreadcrumbForm, #arrivalHeroForm, #arrivalMainTitleForm, #arrivalTipsForm, #arrivalNotesForm').forEach(form => {
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

