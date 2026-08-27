<!-- Modal: تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade custom-modal" id="blockedBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: الضمانات المالية والحساب البنكي المغلق" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="#">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: تعديل صورة الهيرو -->
<div class="modal fade custom-modal" id="blockedHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_blocked_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($blocked_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($blocked_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($blocked_data['hero_img']), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="blockedHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal 1: تعديل العنوان والوصف الرئيسي والأهمية -->
<div class="modal fade custom-modal" id="blockedMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text text-primary"></i> تعديل العنوان والوصف الرئيسي والأهمية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedMainForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_main">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي (Main Title)</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($blocked_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف الرئيسي</label>
                            <textarea class="form-control" name="main_desc" rows="3" style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($blocked_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان الأهمية</label>
                            <input type="text" class="form-control" name="importance_title" value="<?php echo htmlspecialchars($blocked_data['importance_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">وصف الأهمية</label>
                            <textarea class="form-control" name="importance_desc" rows="3" style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($blocked_data['importance_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: تعديل خيارات الضمان المالي -->
<div class="modal fade custom-modal" id="blockedOptionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-square text-primary"></i> تعديل خيارات الضمان المالي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedOptionsForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_options">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="options_title" value="<?php echo htmlspecialchars($blocked_data['options_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">خيارات الضمان المالي (قائمة)</label>
                    <div id="blockedOptionsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($blocked_data['options_items'])): ?>
                            <?php foreach ($blocked_data['options_items'] as $index => $opt): ?>
                                <div class="p-3 shadow-sm d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="opt_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="options_items[]" value="<?php echo htmlspecialchars($opt, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('opt_row_<?php echo $index; ?>')" title="حذف الخيار">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addBlockedOptionRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خيار جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedOptionsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: تعديل نقاط الحساب المغلق -->
<div class="modal fade custom-modal" id="blockedAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل نقاط الحساب المغلق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedAccountForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_account">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="account_title" value="<?php echo htmlspecialchars($blocked_data['account_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">نقاط الحساب المغلق (قائمة)</label>
                    <div id="blockedPointsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($blocked_data['account_points'])): ?>
                            <?php foreach ($blocked_data['account_points'] as $index => $point): ?>
                                <div class="p-3 shadow-sm d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="point_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="account_points[]" value="<?php echo htmlspecialchars($point, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('point_row_<?php echo $index; ?>')" title="حذف النقطة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addBlockedPointRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedAccountForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: تعديل الروابط والشركات -->
<div class="modal fade custom-modal" id="blockedLinksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-link-45deg text-primary"></i> تعديل الروابط والشركات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedLinksForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_links">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الروابط والشركات</label>
                    <div id="blockedLinksContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($blocked_data['service_links'])): ?>
                            <?php foreach ($blocked_data['service_links'] as $index => $link): ?>
                                <div class="p-4 shadow-sm position-relative link-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="link_row_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">نص الرابط / الزر</label>
                                            <input type="text" class="form-control" name="link_texts[]" value="<?php echo htmlspecialchars($link['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: Fintiba..." required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">رابط الـ URL</label>
                                            <input type="text" class="form-control" name="link_urls[]" value="<?php echo htmlspecialchars($link['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://...">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeRow('link_row_<?php echo $index; ?>')">
                                            <i class="bi bi-trash"></i> حذف هذا الرابط
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addBlockedLinkRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة رابط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- Dynamic JS Engine -->
<script>
    // 1. دالة عامة لحذف أي صف (خيارات، نقاط، روابط)
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

    // 3. إدارة صفوف خيارات الضمان المالي بالستايل الموحد
    let optionIndex = <?php echo count($blocked_data['options_items'] ?? []); ?>;
    function addBlockedOptionRow() {
        const container = document.getElementById('blockedOptionsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'opt_row_' + optionIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="options_items[]" placeholder="اكتب خيار الضمان هنا..." required>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('opt_row_${optionIndex}')" title="حذف الخيار">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        optionIndex++;
    }

    // 4. إدارة صفوف نقاط الحساب المغلق بالستايل الموحد
    let pointIndex = <?php echo count($blocked_data['account_points'] ?? []); ?>;
    function addBlockedPointRow() {
        const container = document.getElementById('blockedPointsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'point_row_' + pointIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="account_points[]" placeholder="اكتب النقطة هنا..." required>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('point_row_${pointIndex}')" title="حذف النقطة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        pointIndex++;
    }

    // 5. إدارة صفوف الروابط والشركات بالستايل الموحد
    let linkIndex = <?php echo count($blocked_data['service_links'] ?? []); ?>;
    function addBlockedLinkRow() {
        const container = document.getElementById('blockedLinksContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-4 shadow-sm position-relative link-item-box';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'link_row_' + linkIndex;
        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">نص الرابط / الزر</label>
                    <input type="text" class="form-control" name="link_texts[]" placeholder="مثال: Fintiba..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">رابط الـ URL</label>
                    <input type="text" class="form-control" name="link_urls[]" placeholder="https://...">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeRow('link_row_${linkIndex}')">
                    <i class="bi bi-trash"></i> حذف هذا الرابط
                </button>
            </div>
        `;
        container.appendChild(div);
        linkIndex++;
    }

    // 6. معالج الحفظ الموحد عبر AJAX لكافة نماذج الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const formSelectors = '#blockedBreadcrumbForm, #blockedHeroForm, #blockedMainForm, #blockedOptionsForm, #blockedAccountForm, #blockedLinksForm';
        
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

