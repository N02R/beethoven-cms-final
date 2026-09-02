<!-- 1. Modal تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade custom-modal" id="jobAgrBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobAgrBreadcrumbForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_job_agreements_breadcrumb">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">نص المسار الأخير</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($job_agreements_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط المسار الأخير</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($job_agreements_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobAgrBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal تعديل صورة الهيرو (Hero) -->
<div class="modal fade custom-modal" id="jobAgrHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobAgrHeroForm" method="POST" action="index.php?url=admin/settings/save" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_job_agreements_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($job_agreements_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($job_agreements_data['hero_img'])): ?>
                            <div class="mb-3 p-3 bg-light rounded-3 border text-center">
                                <span class="d-block small text-muted mb-2">معاينة الصورة الحالية:</span>
                                <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center shadow-sm">
                                    <img src="<?php echo htmlspecialchars(get_image_url($job_agreements_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain;" class="rounded-2" alt="Hero Preview">
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">توضع الصورة (Hero Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($job_agreements_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobAgrHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal تعديل العنوان والوصف الرئيسي (Main Section) -->
<div class="modal fade custom-modal" id="jobAgrMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobAgrMainForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_job_agreements_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($job_agreements_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($job_agreements_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobAgrMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal تعديل الملاحظات الهامة -->
<div class="modal fade custom-modal" id="jobAgrNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobAgrNotesForm" method="POST" action="index.php?url=admin/settings/save">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_job_agreements_notes">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">نص الملاحظة</label>
                            <textarea class="form-control" name="note_text" rows="3" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($job_agreements_data['note_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobAgrNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal تعديل ملف وربط التحميل (Download Card - يدعم العناصر المتعددة مع التوافقية العكسية) -->
<div class="modal fade custom-modal" id="jobAgrCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> تعديل ملفات الاتفاقيات والتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobAgrCardForm" method="POST" action="index.php?url=admin/settings/save" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="update_job_agreements_card">
                    
                    <?php 
                        $download_items = $job_agreements_data['download_items'] ?? [];
                        if (empty($download_items) && !empty($job_agreements_data['download_item'])) {
                            $download_items = [$job_agreements_data['download_item']];
                        }
                        if (empty($download_items)) {
                            $download_items = [[
                                'type' => 'pdf',
                                'title' => 'عرض واتفاقيات العمل',
                                'sub' => 'Example',
                                'file' => 'assets/files/job_search_agreement.pdf'
                            ]];
                        }
                        $single_item = $download_items[0];
                    ?>
                    <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($single_item['file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                                <select class="form-select" name="item_type">
                                    <option value="pdf" <?php echo (($single_item['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>ملف PDF</option>
                                    <option value="word" <?php echo (in_array($single_item['type'] ?? '', ['word', 'docx'])) ? 'selected' : ''; ?>>ملف Word (docx)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small text-secondary">عنوان الملف الظاهر</label>
                                <input type="text" class="form-control" name="item_title" value="<?php echo htmlspecialchars($single_item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">النص الفرعي (Sub)</label>
                                <input type="text" class="form-control" name="item_sub" value="<?php echo htmlspecialchars($single_item['sub'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">رفع ملف جديد (PDF أو Word)</label>
                                <input type="file" class="form-control" name="item_file" accept=".pdf,.doc,.docx">
                                <div class="form-text text-muted mt-2 small">الملف الحالي: <?php echo htmlspecialchars($single_item['file'] ?? 'لا يوجد', ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobAgrCardForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    if (typeof showNotification !== 'function') {
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
    }

    document.addEventListener('DOMContentLoaded', function() {
        const jobForms = document.querySelectorAll('#jobAgrBreadcrumbForm, #jobAgrHeroForm, #jobAgrMainForm, #jobAgrNotesForm, #jobAgrCardForm');
        
        jobForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                const csrfTokenInput = this.querySelector('input[name="csrf_token"]');
                const csrfToken = csrfTokenInput ? csrfTokenInput.value : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                
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
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || data.error || 'فشل الحفظ'), 'danger');
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
