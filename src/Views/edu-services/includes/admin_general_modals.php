
<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="visaBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($visa_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($visa_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="visaHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_visa_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($visa_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($visa_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($visa_data['hero_img'] ?? null, 'assets/img/education/servicesimg14.png')); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">موضع الصورة (Background Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($visa_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: center center أو top center">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal: تعديل العنوان والوصف الرئيسي -->
<div class="modal fade custom-modal" id="visaMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaMainForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($visa_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($visa_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal: تعديل الملاحظات الهامة (Notes) -->
<div class="modal fade custom-modal" id="visaNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل صندوق الملاحظات والإرشادات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_notes">
                    
                    <?php 
                        $notes_sec = $visa_data['notes_section'] ?? [];
                        $note_title = $notes_sec['title'] ?? 'ملاحظة !!';
                        $notes_list = $notes_sec['notes_list'] ?? [];
                    ?>

                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان صندوق الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($note_title, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملاحظات (تعديل / حذف / إضافة)</label>
                    <div id="notesRowsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($notes_list)): ?>
                            <?php foreach ($notes_list as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="note_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes_list[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الملاحظة هنا...">
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
                <button type="submit" form="visaNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal: إدارة ملفات التحميل (Download Items) -->
<div class="modal fade custom-modal" id="visaDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة ملفات التحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaDownloadForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_visa_downloads">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملفات المتاحة للتحميل (تعديل / حذف / إضافة)</label>
                    <div id="downloadRowsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($visa_data['download_items'])): ?>
                            <?php foreach ($visa_data['download_items'] as $index => $item): ?>
                                <div class="p-3 shadow-sm download-item d-flex flex-column gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="download_row_<?php echo $index; ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="download_titles[]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الملف (مثال: نموذج طلب التأشيرة)">
                                        <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeRow('download_row_<?php echo $index; ?>')" title="حذف الملف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-6">
                                            <select class="form-select" name="download_types[]">
                                                <option value="pdf" <?php echo (($item['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>ملف PDF</option>
                                                <option value="word" <?php echo (($item['type'] ?? '') === 'word') ? 'selected' : ''; ?>>ملف Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="download_files[]" accept=".pdf,.doc,.docx">
                                            <input type="hidden" name="old_download_files[]" value="<?php echo htmlspecialchars($item['file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <?php if (!empty($item['file'])): ?>
                                        <div class="small text-muted text-truncate">الملف الحالي: <?php echo htmlspecialchars($item['file']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملف جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addDownloadRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملف جديد للتحميل
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaDownloadForm" class="btn-premium">حفظ التغييرات</button>
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

    // 3. إضافة صف ملاحظة جديدة بالستايل الموحد
    let noteIndex = <?php echo count($notes_list ?? []); ?>;
    function addNoteRow() {
        const container = document.getElementById('notesRowsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'note_row_' + noteIndex;
        div.id = rowId;
        div.innerHTML = `
            <input type="text" class="form-control" name="notes_list[]" placeholder="اكتب الملاحظة هنا...">
            <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeRow('${rowId}')" title="حذف الملاحظة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // 4. إضافة صف ملف تحميل جديد بالستايل الموحد
    let downloadIndex = <?php echo count($visa_data['download_items'] ?? []); ?>;
    function addDownloadRow() {
        const container = document.getElementById('downloadRowsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm download-item d-flex flex-column gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'download_row_' + downloadIndex;
        div.id = rowId;
        div.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control fw-bold" name="download_titles[]" placeholder="عنوان الملف (مثال: نموذج طلب التأشيرة)">
                <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeRow('${rowId}')" title="حذف الملف">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <select class="form-select" name="download_types[]">
                        <option value="pdf">ملف PDF</option>
                        <option value="word">ملف Word</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="file" class="form-control" name="download_files[]" accept=".pdf,.doc,.docx">
                    <input type="hidden" name="old_download_files[]" value="">
                </div>
            </div>
        `;
        container.appendChild(div);
        downloadIndex++;
    }

    // 5. معالج الحفظ الموحد عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#visaBreadcrumbForm, #visaHeroForm, #visaMainForm, #visaNotesForm, #visaDownloadForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (csrfToken && !formData.has('csrf_token')) {
                    formData.append('csrf_token', csrfToken);
                }

                // إغلاق المودال الحالي إن وجد
                const modalElement = this.closest('.modal');
                if (modalElement && window.bootstrap) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();
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
