<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="livingBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_living_breadcrumb">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($living_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($living_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="livingHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_living_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($living_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($living_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($living_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                                <input type="file" class="form-control" name="hero_img" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">موضع الخلفية (Background Position)</label>
                                <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($living_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: center center">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="livingMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingMainForm" method="POST">
                    <input type="hidden" name="action" value="update_living_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($living_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($living_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 4. Tips Section Modal -->
<div class="modal fade custom-modal" id="livingTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نصائح تقليل النفقات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_living_tips">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($living_data['tips_section']['title'] ?? 'نصائح لتقليل النفقات', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / حذف / إضافة)</label>
                    <div id="livingTipsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $tips_items = $living_data['tips_section']['items'] ?? [];
                          if (!empty($tips_items) && is_array($tips_items)): 
                            foreach ($tips_items as $index => $tip): 
                        ?>
                                <div class="p-3 shadow-sm tip-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="living_tip_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips_items[]" value="<?php echo htmlspecialchars($tip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('living_tip_<?php echo $index; ?>')" title="حذف النصيحة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLivingTipRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 5. Important Notes Modal -->
<div class="modal fade custom-modal" id="livingNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_living_notes">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان الملاحظات</label>
                            <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($living_data['notes_section']['title'] ?? 'ملاحظات هامة !!', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملاحظات (تعديل / حذف / إضافة)</label>
                    <div id="livingNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $notes_items = $living_data['notes_section']['items'] ?? [];
                          if (!empty($notes_items) && is_array($notes_items)): 
                            foreach ($notes_items as $index => $note): 
                        ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="living_note_<?php echo $index; ?>">
                                    <textarea class="form-control" name="notes_items[]" rows="2" style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('living_note_<?php echo $index; ?>')" title="حذف الملاحظة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLivingNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingNotesForm" class="btn-premium">حفظ التغييرات</button>
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

    // 3. إدارة صفوف نصائح تقليل النفقات بالستايل الموحد
    let livingTipIndex = <?php echo count($living_data['tips_section']['items'] ?? []); ?>;
    function addLivingTipRow() {
        const container = document.getElementById('livingTipsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm tip-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'living_tip_' + livingTipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips_items[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('living_tip_${livingTipIndex}')" title="حذف النصيحة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        livingTipIndex++;
    }

    // 4. إدارة صفوف الملاحظات الهامة بالستايل الموحد
    let livingNoteIndex = <?php echo count($living_data['notes_section']['items'] ?? []); ?>;
    function addLivingNoteRow() {
        const container = document.getElementById('livingNotesContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'living_note_' + livingNoteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="notes_items[]" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب الملاحظة هنا..."></textarea>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('living_note_${livingNoteIndex}')" title="حذف الملاحظة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        livingNoteIndex++;
    }

    // 5. معالج الحفظ الموحد عبر AJAX لكافة نماذج الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const formSelectors = '#livingBreadcrumbForm, #livingHeroForm, #livingMainForm, #livingTipsForm, #livingNotesForm';
        
        document.querySelectorAll(formSelectors).forEach(form => {
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
