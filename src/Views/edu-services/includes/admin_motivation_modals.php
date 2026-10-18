<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="motivationBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($motivation_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($motivation_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="motivationHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_motivation_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($motivation_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($motivation_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($motivation_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">موضع الخلفية (Background Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($motivation_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>" placeholder="center center">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="motivationMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationMainForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($motivation_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($motivation_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Section Modal -->
<div class="modal fade custom-modal" id="motivationAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نصائح كتابة خطاب الدافع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationAdviceForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_advice">
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($motivation_data['advice_section']['title'] ?? 'نصائح سريعة لكتابة خطاب الدافع', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="motivationAdviceContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $advice_items = $motivation_data['advice_section']['items'] ?? [];
                          if (!empty($advice_items)): 
                            foreach ($advice_items as $index => $item): 
                        ?>
                                <div class="p-3 shadow-sm advice-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="motivation_advice_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="advice_items[]" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('motivation_advice_<?php echo $index; ?>')" title="حذف النصيحة"><i class="bi bi-trash"></i></button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMotivationAdviceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Files Modal -->
<div class="modal fade custom-modal" id="motivationDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة ملفات النماذج المتاحة للتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationDownloadForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_downloads">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    <div id="motivationDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $download_items = $motivation_data['download_items'] ?? [];
                          if (!empty($download_items)): 
                            foreach ($download_items as $index => $dl): 
                        ?>
                                <div class="p-4 shadow-sm position-relative download-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="motivation_download_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                                            <select class="form-select" name="download_types[]">
                                                <option value="pdf" <?php echo (strtolower($dl['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($dl['type'] ?? '') === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                                            <input type="text" class="form-control" name="download_titles[]" value="<?php echo htmlspecialchars($dl['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان البطاقة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">النوع الفرعي (Sub)</label>
                                            <input type="text" class="form-control" name="download_subs[]" value="<?php echo htmlspecialchars($dl['sub'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="النوع الفرعي">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">مسار الملف (URL)</label>
                                            <input type="text" class="form-control" name="download_files[]" value="<?php echo htmlspecialchars($dl['file'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مسار الملف">
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="removeRow('motivation_download_<?php echo $index; ?>')">
                                            <i class="bi bi-trash me-1"></i> حذف هذا النموذج
                                        </button>
                                    </div>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <!-- زر إضافة نموذج تحميل جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMotivationDownloadRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // 1. دالة عامة لحذف أي صف بناءً على الـ ID
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

    // 3. دالة إضافة صف جديد لنصائح كتابة خطاب الدافع (تستخدم مصفوفة مرنة advice_items[])
    function addMotivationAdviceRow() {
        const container = document.getElementById('motivationAdviceContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm advice-item-row d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'motivation_advice_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
        div.innerHTML = `
            <input type="text" class="form-control advice-item-input" name="advice_items[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف النصيحة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    // 4. دالة إضافة صف جديد لنماذج التحميل الديناميكية
    function addMotivationDownloadRow() {
        const container = document.getElementById('motivationDownloadContainer');
        if (!container) return;
        const count = container.querySelectorAll('.download-item-box').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm download-item-box position-relative';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'motivation_download_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                    <select class="form-select download-type" name="download_items[${count}][type]">
                        <option value="pdf">PDF</option>
                        <option value="word" selected>Word</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                    <input type="text" class="form-control download-title" name="download_items[${count}][title]" value="خطاب الدافع / التحفيز" placeholder="عنوان البطاقة">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">النوع الفرعي (Sub)</label>
                    <input type="text" class="form-control download-sub" name="download_items[${count}][sub]" value="Example (Word)" placeholder="النوع الفرعي">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-secondary">مسار الملف (URL)</label>
                    <input type="text" class="form-control download-file" name="download_items[${count}][file]" value="assets/files/motivation_letter.docx" placeholder="مسار الملف">
                </div>
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف النموذج">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // 5. معالج الحفظ وإرسال البيانات عبر AJAX بأمان تام
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#motivationBreadcrumbForm, #motivationHeroForm, #motivationMainForm, #motivationAdviceForm, #motivationDownloadForm, .admin-settings-form').forEach(form => {
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


