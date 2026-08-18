<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="cvBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvBreadcrumbForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($cv_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($cv_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="cvHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvHeroForm" method="POST" action="index.php?url=admin/settings/save" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($cv_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($cv_data['hero_img'])): ?>
                            <div class="mb-3 p-3 bg-light rounded-3 border text-center">
                                <span class="d-block small text-muted mb-2">معاينة الصورة الحالية:</span>
                                <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center shadow-sm">
                                    <img src="<?php echo htmlspecialchars(get_image_url($cv_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain;" class="rounded-2" alt="Hero Preview">
                                </div>
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
                <button type="submit" form="cvHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="cvMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvMainForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($cv_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($cv_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Points Modal -->
<div class="modal fade custom-modal" id="cvAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نصائح كتابة الـ CV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvAdviceForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_advice">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($cv_data['advice_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-2">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="cvAdviceContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cv_data['advice_points'])): ?>
                            <?php foreach ($cv_data['advice_points'] as $index => $point): ?>
                                <div class="p-3 shadow-sm advice-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cv_advice_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="advice_points[]" value="<?php echo htmlspecialchars($point, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cv_advice_<?php echo $index; ?>')" title="حذف النصيحة"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCvAdviceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Important Notes Modal -->
<div class="modal fade custom-modal" id="cvNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvNotesForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_notes">
                    
                    <!-- حاوية عنوان الملاحظات -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($cv_data['note_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <label class="form-label fw-semibold small text-secondary mb-2">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="cvNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cv_data['notes'])): ?>
                            <?php foreach ($cv_data['notes'] as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cv_note_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="نص الملاحظة">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cv_note_<?php echo $index; ?>')" title="حذف الملاحظة"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملاحظة جديدة -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCvNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Download Files Modal -->
<div class="modal fade custom-modal" id="cvDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة نماذج وملفات الـ CV المتاحة للتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvDownloadForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cv_downloads">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    <div id="cvDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cv_data['download_items'])): ?>
                            <?php foreach ($cv_data['download_items'] as $index => $item): ?>
                                <div class="p-4 shadow-sm position-relative download-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cv_download_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                                            <select class="form-select" name="download_types[]">
                                                <option value="pdf" <?php echo (strtolower($item['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($item['type'] ?? '') === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                                            <input type="text" class="form-control" name="download_titles[]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان البطاقة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">النوع الفرعي (Sub)</label>
                                            <input type="text" class="form-control" name="download_subs[]" value="<?php echo htmlspecialchars($item['sub'] ?? 'Example', ENT_QUOTES, 'UTF-8'); ?>" placeholder="النوع الفرعي">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">مسار الملف (URL)</label>
                                            <input type="text" class="form-control" name="download_files[]" value="<?php echo htmlspecialchars($item['file'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مسار الملف">
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="removeRow('cv_download_<?php echo $index; ?>')">
                                            <i class="bi bi-trash me-1"></i> حذف هذا النموذج
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نموذج تحميل جديد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCvDownloadRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // 1. دالة عامة لحذف أي صف
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية (موحدة)
    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = (type === 'danger') ? 'alert-danger' : (type === 'warning') ? 'alert-warning' : 'alert-success';
        let icon = (type === 'danger') ? 'bi-x-circle-fill' : (type === 'warning') ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill';
        let title = (type === 'danger') ? 'عذراً، حدث خطأ!' : (type === 'warning') ? 'تنبيه هام' : 'تم بنجاح!';

        const alertDiv = document.createElement('div');
        alertDiv.id = 'customNotificationAlert';
        alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
        alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon} fs-4"></i>
                <div><strong>${title}</strong><div class="small">${message}</div></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => { if (alertDiv) { alertDiv.classList.remove('show'); setTimeout(() => alertDiv.remove(), 300); } }, 4000);
    }

    // 3. وظائف إضافة الصفوف (CV Modals)
    function addCvAdviceRow() {
        const container = document.getElementById('cvAdviceContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm advice-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cv_advice_' + Date.now();
        div.innerHTML = `
            <input type="text" class="form-control" name="advice_points[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف النصيحة"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function addCvNoteRow() {
        const container = document.getElementById('cvNotesContainer');
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cv_note_' + Date.now();
        div.innerHTML = `
            <input type="text" class="form-control" name="notes[]" placeholder="اكتب الملاحظة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الملاحظة"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function addCvDownloadRow() {
        const container = document.getElementById('cvDownloadContainer');
        const div = document.createElement('div');
        div.className = 'p-4 shadow-sm position-relative download-item-box';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cv_download_' + Date.now();
        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                    <select class="form-select" name="download_types[]">
                        <option value="pdf">PDF</option>
                        <option value="word" selected>Word</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                    <input type="text" class="form-control" name="download_titles[]" value="السيرة الذاتية \"CV\"" placeholder="عنوان البطاقة">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">النوع الفرعي (Sub)</label>
                    <input type="text" class="form-control" name="download_subs[]" value="Example" placeholder="النوع الفرعي">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">مسار الملف (URL)</label>
                    <input type="text" class="form-control" name="download_files[]" value="#" placeholder="مسار الملف">
                </div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="removeRow('${div.id}')">
                    <i class="bi bi-trash me-1"></i> حذف هذا النموذج
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    // 4. معالج الحفظ الموحد لكل الفورمات عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>';
                
                fetch(this.getAttribute('action') || 'index.php?url=admin/settings/save', {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('تم حفظ التعديلات بنجاح...', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'عذراً، لم يتم الحفظ', 'danger');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('خطأ في الاتصال بالسيرفر، يرجى المحاولة لاحقاً.', 'danger');
                });
            });
        });
    });
</script>
