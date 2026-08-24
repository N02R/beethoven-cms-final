<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="englishBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_english_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($english_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($english_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="englishHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_english_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($english_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($english_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($english_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="englishHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="englishMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishMainForm" method="POST">
                    <input type="hidden" name="action" value="update_english_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($english_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($english_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 4. Who Can Benefit / Requirements Modal -->
<div class="modal fade custom-modal" id="englishWhoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل شروط الاستفادة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishWhoForm" method="POST">
                    <input type="hidden" name="action" value="update_english_who">
                    
                    <!-- حاوية عناوين القسم بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                                <input type="text" class="form-control" name="who_title" value="<?php echo htmlspecialchars($english_data['who_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                <input type="text" class="form-control" name="who_subtitle" value="<?php echo htmlspecialchars($english_data['who_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الشروط (تعديل / إضافة / حذف)</label>
                    <div id="englishWhoContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($english_data['who_items'])): ?>
                            <?php foreach ($english_data['who_items'] as $index => $item): ?>
                                <div class="p-3 shadow-sm who-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="english_who_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="who_items[]" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الشرط هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeEnglishRow('english_who_<?php echo $index; ?>')" title="حذف الشرط">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة شرط جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEnglishWhoRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شرط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishWhoForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 5. Language Requirements Modal -->
<div class="modal fade custom-modal" id="englishLangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-award text-primary"></i> تعديل متطلبات اللغة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishLangForm" method="POST">
                    <input type="hidden" name="action" value="update_english_lang">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="lang_title" value="<?php echo htmlspecialchars($english_data['lang_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة متطلبات اللغة (تعديل / إضافة / حذف)</label>
                    <div id="englishLangContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($english_data['lang_points'])): ?>
                            <?php foreach ($english_data['lang_points'] as $index => $point): ?>
                                <div class="p-3 shadow-sm lang-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="english_lang_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="lang_points[]" value="<?php echo htmlspecialchars($point, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب متطلب اللغة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeEnglishRow('english_lang_<?php echo $index; ?>')" title="حذف المتطلب">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة متطلب جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEnglishLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة متطلب جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishLangForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Note Modal -->
<div class="modal fade custom-modal" id="englishNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle text-primary"></i> تعديل الملاحظة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishNoteForm" method="POST">
                    <input type="hidden" name="action" value="update_english_note">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">نص التمييز (مثال: ملاحظة:)</label>
                            <input type="text" class="form-control" name="note_highlight" value="<?php echo htmlspecialchars($english_data['note_highlight'] ?? 'ملاحظة:', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">نص الملاحظة</label>
                            <textarea class="form-control" name="note_text" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($english_data['note_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishNoteForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript Engine -->
<script>
    // 1. دالة عامة لحذف أي صف
    function removeEnglishRow(id) {
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

    // 3. إدارة صفوف شروط الاستفادة بالستايل الموحد
    let whoIndex = <?php echo count($english_data['who_items'] ?? []); ?>;
    function addEnglishWhoRow() {
        const container = document.getElementById('englishWhoContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm who-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'english_who_' + whoIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="who_items[]" placeholder="اكتب الشرط هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeEnglishRow('english_who_${whoIndex}')" title="حذف الشرط">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        whoIndex++;
    }

    // 4. إدارة صفوف متطلبات اللغة بالستايل الموحد
    let langIndex = <?php echo count($english_data['lang_points'] ?? []); ?>;
    function addEnglishLangRow() {
        const container = document.getElementById('englishLangContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm lang-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'english_lang_' + langIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="lang_points[]" placeholder="اكتب المتطلب هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeEnglishRow('english_lang_${langIndex}')" title="حذف المتطلب">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        langIndex++;
    }

    // 5. معالج الحفظ الموحد عبر AJAX لجميع نماذج القسم الإنجليزي
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#englishBreadcrumbForm, #englishHeroForm, #englishMainForm, #englishWhoForm, #englishLangForm, #englishNoteForm').forEach(form => {
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

