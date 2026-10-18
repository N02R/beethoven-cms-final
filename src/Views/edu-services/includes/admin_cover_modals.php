
<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="coverBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverBreadcrumbForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($cover_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($cover_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="coverHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverHeroForm" method="POST" action="index.php?url=admin/settings/save" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_hero">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($cover_data['hero_img'])): ?>
                            <div class="mb-3 p-3 bg-light rounded-3 border text-center">
                                <span class="d-block small text-muted mb-2">معاينة الصورة الحالية:</span>
                                <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center shadow-sm">
                                    <img src="<?php echo htmlspecialchars(get_image_url($cover_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Preview" class="rounded-2" style="max-height: 120px; object-fit: contain;">
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($cover_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="coverMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverMainForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($cover_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($cover_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Points Modal -->
<div class="modal fade custom-modal" id="coverAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نقاط النصائح الواجب مراعاتها</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverAdviceForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_advice">
                    
                    <!-- حاوية العنوان الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($cover_data['advice_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <label class="form-label fw-semibold small text-secondary mb-2">قائمة النقاط (تعديل / إضافة / حذف)</label>
                    
                    <div id="coverAdviceContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cover_data['advice_points'])): ?>
                            <?php foreach ($cover_data['advice_points'] as $index => $point): ?>
                                <div class="p-3 shadow-sm advice-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_advice_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="advice_points[]" value="<?php echo htmlspecialchars($point, ENT_QUOTES, 'UTF-8'); ?>" placeholder="نص النقطة">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeCoverRow('cover_advice_<?php echo $index; ?>')" title="حذف النقطة"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نقطة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCoverAdviceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 5. Important Notes Modal -->
<div class="modal fade custom-modal" id="coverNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverNotesForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_notes">
                    
                    <!-- حاوية عنوان الملاحظات -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($cover_data['note_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <label class="form-label fw-semibold small text-secondary mb-2">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    
                    <div id="coverNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cover_data['notes'])): ?>
                            <?php foreach ($cover_data['notes'] as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_note_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="نص الملاحظة">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeCoverRow('cover_note_<?php echo $index; ?>')" title="حذف الملاحظة"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملاحظة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCoverNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 6. Download Files Modal -->
<div class="modal fade custom-modal" id="coverDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة نماذج وملفات خطاب الطلب المتاحة للتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverDownloadForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_cover_downloads">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    
                    <div id="coverDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cover_data['download_items'])): ?>
                            <?php foreach ($cover_data['download_items'] as $index => $item): ?>
                                <div class="p-4 shadow-sm position-relative download-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_download_<?php echo $index; ?>">
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
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="removeCoverRow('cover_download_<?php echo $index; ?>')">
                                            <i class="bi bi-trash me-1"></i> حذف هذا النموذج
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نموذج تحميل جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCoverDownloadRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<!-- Combined JavaScript Engine -->
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

    // 3. وظائف إضافة الصفوف (Cover Modals)
    function addCoverAdviceRow() {
        const container = document.getElementById('coverAdviceContainer');
        if (!container) return;
        const count = container.querySelectorAll('.advice-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm advice-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cover_advice_' + Date.now();
        div.innerHTML = `
            <input type="text" class="form-control" name="advice_points[]" placeholder="اكتب النقطة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function addCoverNoteRow() {
        const container = document.getElementById('coverNotesContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cover_note_' + Date.now();
        div.innerHTML = `
            <input type="text" class="form-control" name="notes[]" placeholder="اكتب الملاحظة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function addCoverDownloadRow() {
        const container = document.getElementById('coverDownloadContainer');
        if (!container) return;
        const count = container.querySelectorAll('.download-item-box').length;
        const div = document.createElement('div');
        div.className = 'p-4 shadow-sm position-relative download-item-box';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'cover_download_' + Date.now();
        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label fw-semibold small text-secondary">نوع الملف</label><select class="form-select" name="download_types[]"><option value="pdf">PDF</option><option value="word" selected>Word</option></select></div>
                <div class="col-md-8"><label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label><input type="text" class="form-control" name="download_titles[]" value="رسالة التعريف/ خطاب الطلب"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-secondary">النوع الفرعي</label><input type="text" class="form-control" name="download_subs[]" value="Example"></div>
                <div class="col-md-6"><label class="form-label fw-semibold small text-secondary">مسار الملف</label><input type="text" class="form-control" name="download_files[]" value="#"></div>
            </div>
            <div class="mt-3 pt-3 border-top d-flex justify-content-end"><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow('${div.id}')"><i class="bi bi-trash"></i> حذف</button></div>
        `;
        container.appendChild(div);
    }

    // 4. معالج الحفظ الموحد لكل الفورمات بالمنطق القياسي الدقيق
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form').forEach(form => {
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

