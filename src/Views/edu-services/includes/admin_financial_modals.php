<!-- Modal: تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade" id="blockedBreadcrumbModal" tabindex="-1" aria-labelledby="blockedBreadcrumbModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedBreadcrumbModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i> تعديل مسار التنقل (Breadcrumb)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedBreadcrumbForm">
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">نص مسار التنقل (العنوان)</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb'] ?? ''); ?>" placeholder="مثال: الضمانات المالية والحساب البنكي المغلق" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">رابط مسار التنقل (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb_url'] ?? ''); ?>" placeholder="#">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: تعديل صورة الهيرو -->
<div class="modal fade" id="blockedHeroModal" tabindex="-1" aria-labelledby="blockedHeroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedHeroModalLabel">
                    <i class="bi bi-image text-primary me-2"></i> تعديل صورة الهيرو (Hero Section)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedHeroForm" enctype="multipart/form-data">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">اختر صورة جديدة للهيرو</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                        <div class="form-text small text-muted mt-1">يُفضل أن تكون الصورة بأبعاد مناسبة وواضحة.</div>
                    </div>
                    <?php if (!empty($blocked_data['hero_img'])): ?>
                        <div class="mt-3">
                            <label class="form-label fw-semibold small text-secondary d-block">الصورة الحالية:</label>
                            <img src="<?php echo htmlspecialchars(get_image_url($blocked_data['hero_img'])); ?>" alt="Current Hero" class="img-thumbnail shadow-sm" style="max-height: 150px; border-radius: 12px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 1: تعديل العنوان والوصف الرئيسي والأهمية -->
<div class="modal fade" id="blockedMainModal" tabindex="-1" aria-labelledby="blockedMainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedMainModalLabel">
                    <i class="bi bi-file-text text-primary me-2"></i> تعديل العنوان والوصف الرئيسي والأهمية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedMainForm">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي (Main Title)</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($blocked_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">الوصف الرئيسي</label>
                        <textarea class="form-control" name="main_desc" rows="3" style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($blocked_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">عنوان الأهمية</label>
                        <input type="text" class="form-control" name="importance_title" value="<?php echo htmlspecialchars($blocked_data['importance_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small text-secondary">وصف الأهمية</label>
                        <textarea class="form-control" name="importance_desc" rows="3" style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($blocked_data['importance_desc'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: تعديل خيارات الضمان المالي -->
<div class="modal fade" id="blockedOptionsModal" tabindex="-1" aria-labelledby="blockedOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedOptionsModalLabel">
                    <i class="bi bi-check2-square text-primary me-2"></i> تعديل خيارات الضمان المالي
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedOptionsForm">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="options_title" value="<?php echo htmlspecialchars($blocked_data['options_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label class="form-label fw-semibold small text-secondary mb-0">خيارات الضمان المالي (قائمة)</label>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;" onclick="addBlockedOptionRow()">
                            <i class="bi bi-plus-lg"></i> إضافة خيار جديد
                        </button>
                    </div>
                    
                    <div id="blockedOptionsContainer" class="d-flex flex-column gap-2 mt-2">
                        <?php if (!empty($blocked_data['options_items'])): ?>
                            <?php foreach ($blocked_data['options_items'] as $index => $opt): ?>
                                <div class="p-3 shadow-sm d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="opt_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="options_items[]" value="<?php echo htmlspecialchars($opt); ?>" required>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('opt_row_<?php echo $index; ?>')" title="حذف الخيار">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: تعديل نقاط الحساب المغلق -->
<div class="modal fade" id="blockedAccountModal" tabindex="-1" aria-labelledby="blockedAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedAccountModalLabel">
                    <i class="bi bi-star text-primary me-2"></i> تعديل نقاط الحساب المغلق
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedAccountForm">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="account_title" value="<?php echo htmlspecialchars($blocked_data['account_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label class="form-label fw-semibold small text-secondary mb-0">نقاط الحساب المغلق (قائمة)</label>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;" onclick="addBlockedPointRow()">
                            <i class="bi bi-plus-lg"></i> إضافة نقطة جديدة
                        </button>
                    </div>
                    
                    <div id="blockedPointsContainer" class="d-flex flex-column gap-2 mt-2">
                        <?php if (!empty($blocked_data['account_points'])): ?>
                            <?php foreach ($blocked_data['account_points'] as $index => $point): ?>
                                <div class="p-3 shadow-sm d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="point_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="account_points[]" value="<?php echo htmlspecialchars($point); ?>" required>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('point_row_<?php echo $index; ?>')" title="حذف النقطة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 4: تعديل الروابط والشركات -->
<div class="modal fade" id="blockedLinksModal" tabindex="-1" aria-labelledby="blockedLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-light px-4 py-3 border-0">
                <h5 class="modal-title fw-bold text-dark" id="blockedLinksModalLabel">
                    <i class="bi bi-link-45deg text-primary me-2"></i> تعديل الروابط والشركات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="blockedLinksForm">
                <div class="modal-body p-4 bg-white">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label class="form-label fw-semibold small text-secondary mb-0">قائمة الروابط والشركات</label>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;" onclick="addBlockedLinkRow()">
                            <i class="bi bi-plus-lg"></i> إضافة رابط جديد
                        </button>
                    </div>
                    
                    <div id="blockedLinksContainer" class="d-flex flex-column gap-3 mt-2">
                        <?php if (!empty($blocked_data['service_links'])): ?>
                            <?php foreach ($blocked_data['service_links'] as $index => $link): ?>
                                <div class="p-4 shadow-sm position-relative link-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="link_row_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">نص الرابط / الزر</label>
                                            <input type="text" class="form-control" name="link_texts[]" value="<?php echo htmlspecialchars($link['text'] ?? ''); ?>" placeholder="مثال: Fintiba..." required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">رابط الـ URL</label>
                                            <input type="text" class="form-control" name="link_urls[]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="https://...">
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
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
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

    // 3. إدارة صفوف خيارات الضمان المالي
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

    // 4. إدارة صفوف نقاط الحساب المغلق
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

    // 5. إدارة صفوف الروابط والشركات
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

    // 6. ربط كافة نماذج الصفحة عبر AJAX (مع فحص استجابة السيرفر وتفادي أخطاء 404)
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
