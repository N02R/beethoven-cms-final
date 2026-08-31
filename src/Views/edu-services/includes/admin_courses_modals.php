<?php
// منع الوصول المباشر للملف
if (!defined('ROOT_PATH') && !isset($lang_data)) {
    // حماية إضافية
}
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="langBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_courses_breadcrumb">
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($lang_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($lang_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="langHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_courses_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($lang_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($lang_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo get_image_url($lang_data['hero_img'] ?? null, 'assets/img/education/servicesimg12.png'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="langHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title Modal -->
<div class="modal fade custom-modal" id="langMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langMainForm" method="POST">
                    <input type="hidden" name="action" value="update_courses_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($lang_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التعريفي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($lang_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Goals Modal -->
<div class="modal fade custom-modal" id="langGoalsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل أهداف الدورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langGoalsForm" method="POST">
                    <input type="hidden" name="action" value="update_courses_goals">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="goals_title" value="<?php echo htmlspecialchars($lang_data['goals_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الأهداف (تعديل / إضافة / حذف)</label>
                    <div id="langGoalsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($lang_data['goals'])): ?>
                            <?php foreach ($lang_data['goals'] as $i => $goal): ?>
                                <div class="p-3 shadow-sm goal-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_goal_<?php echo $i; ?>">
                                    <input type="text" class="form-control" name="goals[]" value="<?php echo htmlspecialchars($goal, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الهدف هنا...">
                                    <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; border-radius: 10px;" onclick="removeRow('lang_goal_<?php echo $i; ?>')" title="حذف الهدف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة هدف جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langGoalsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Warning Modal -->
<div class="modal fade custom-modal" id="langWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل نص التنبيه والشروط</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langWarningForm" method="POST">
                    <input type="hidden" name="action" value="update_courses_warning">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">نص التحذير / الشروط</label>
                            <textarea class="form-control" name="warning_text" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($lang_data['warning_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langWarningForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Cost & Locations Modal -->
<div class="modal fade custom-modal" id="langCostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-geo-alt text-primary"></i> تعديل أماكن الالتحاق والتكاليف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langCostForm" method="POST">
                    <input type="hidden" name="action" value="update_courses_cost">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="cost_title" value="<?php echo htmlspecialchars($lang_data['cost_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة العناصر (العنوان والوصف) (تعديل / إضافة / حذف)</label>
                    <div id="langCostContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($lang_data['cost_items'])): ?>
                            <?php foreach ($lang_data['cost_items'] as $i => $item): ?>
                                <div class="p-3 shadow-sm cost-item d-flex flex-column gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_cost_<?php echo $i; ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold" name="cost_items_title[]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان العنصر (مثال: برلين):">
                                        <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; border-radius: 10px;" onclick="removeRow('lang_cost_<?php echo $i; ?>')" title="حذف العنصر">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <textarea class="form-control" name="cost_items_desc[]" rows="2" placeholder="وصف العنصر أو التكلفة..." style="height: auto; padding: 10px 14px;"><?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangCostRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة مكان/تكلفة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langCostForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine for Language Course Page -->
<script>
    // جعل الدوال معرفة في النطاق العام (Global Scope)
    window.removeRow = function(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    };

    window.showNotification = function(message, type = 'success') {
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
    };

    // دالة إضافة هدف جديد (محدثة لتكون ذكية وتعتمد على الوقت الحالي لمنع أي تداخل IDs)
    window.addLangRow = function() {
        const container = document.getElementById('langGoalsContainer');
        if (!container) {
            console.error('langGoalsContainer not found');
            return;
        }
        
        const uniqueId = 'lang_goal_' + Date.now() + '_' + Math.random().toString(36.substring(2, 7));
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm goal-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = uniqueId;
        
        div.innerHTML = `
            <input type="text" class="form-control" name="goals[]" placeholder="اكتب الهدف هنا..." required>
            <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; border-radius: 10px;" onclick="removeRow('${uniqueId}')" title="حذف الهدف">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    };

    // دالة إضافة مكان/تكلفة جديد
    window.addLangCostRow = function() {
        const container = document.getElementById('langCostContainer');
        if (!container) {
            console.error('langCostContainer not found');
            return;
        }

        const uniqueId = 'lang_cost_' + Date.now() + '_' + Math.random().toString(36.substring(2, 7));
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm cost-item d-flex flex-column gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = uniqueId;
        
        div.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control fw-bold" name="cost_items_title[]" placeholder="عنوان العنصر (مثال: برلين):">
                <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; border-radius: 10px;" onclick="removeRow('${uniqueId}')" title="حذف العنصر">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <textarea class="form-control" name="cost_items_desc[]" rows="2" placeholder="وصف العنصر أو التكلفة..." style="height: auto; padding: 10px 14px;"></textarea>
        `;
        container.appendChild(div);
    };

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('#langBreadcrumbForm, #langHeroForm, #langMainForm, #langGoalsForm, #langWarningForm, #langCostForm');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (csrfToken && !formData.has('csrf_token')) {
                    formData.append('csrf_token', csrfToken);
                }

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
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || data.error || 'فشل الحفظ'), 'danger');
                        }
                    } catch (e) {
                        showNotification('خطأ في استجابة السيرفر، يرجى المحاولة لاحقاً.', 'danger');
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

