<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="germanBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_german_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($german_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($german_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="germanHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanHeroForm" method="POST">
                    <input type="hidden" name="action" value="update_german_hero">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($german_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($german_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">مسار الصورة (URL)</label>
                            <input type="text" class="form-control" name="hero_img" value="<?php echo htmlspecialchars($german_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">تنسيق التموضع (background-position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($german_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="germanMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanMainForm" method="POST">
                    <input type="hidden" name="action" value="update_german_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($german_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التعريفي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($german_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Levels Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanLevelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-stars text-primary"></i> إدارة المستويات المتوفرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanLevelsForm" method="POST">
                    <input type="hidden" name="action" value="update_german_levels">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان قسم المستويات</label>
                            <input type="text" class="form-control" name="levels_title" value="<?php echo htmlspecialchars($german_data['levels_section']['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة المستويات (تعديل / إضافة / حذف)</label>
                    <div id="germanLevelsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($german_data['levels_section']['levels_list'])): ?>
                            <?php foreach ($german_data['levels_section']['levels_list'] as $index => $level): ?>
                                <div class="p-3 shadow-sm level-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="german_level_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="levels_list[]" value="<?php echo htmlspecialchars($level, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب اسم المستوى هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_level_<?php echo $index; ?>')" title="حذف المستوى">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة مستوى جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGermanLevelRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة مستوى جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanLevelsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Features Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanFeaturesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-circle text-primary"></i> إدارة مميزات الدورات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanFeaturesForm" method="POST">
                    <input type="hidden" name="action" value="update_german_features">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان قسم المميزات</label>
                            <input type="text" class="form-control" name="features_title" value="<?php echo htmlspecialchars($german_data['features_section']['title'] ?? 'مميزات دوراتنا', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة المميزات (تعديل / إضافة / حذف)</label>
                    <div id="germanFeaturesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($german_data['features_section']['features_list'])): ?>
                            <?php foreach ($german_data['features_section']['features_list'] as $index => $feat): ?>
                                <div class="p-3 shadow-sm feat-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="german_feat_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="features_list[]" value="<?php echo htmlspecialchars($feat, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الميزة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_feat_<?php echo $index; ?>')" title="حذف الميزة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ميزة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGermanFeatureRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ميزة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanFeaturesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Tips Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-lightbulb text-primary"></i> إدارة نصائح النجاح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_german_tips">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان قسم النصائح</label>
                            <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($german_data['tips_section']['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="germanTipsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($german_data['tips_section']['tips_list'])): ?>
                            <?php foreach ($german_data['tips_section']['tips_list'] as $index => $tip): ?>
                                <div class="p-3 shadow-sm tip-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="german_tip_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips_list[]" value="<?php echo htmlspecialchars($tip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_tip_<?php echo $index; ?>')" title="حذف النصيحة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGermanTipRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // 1. دالة عامة لحذف أي صف
    function removeGermanRow(id) {
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

    // 3. إدارة صفوف المستويات بالستايل الموحد
    let germanLevelIndex = <?php echo count($german_data['levels_section']['levels_list'] ?? []); ?>;
    function addGermanLevelRow() {
        const container = document.getElementById('germanLevelsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm level-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'german_level_' + germanLevelIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="levels_list[]" placeholder="أدخل المستوى الجديد...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_level_${germanLevelIndex}')" title="حذف المستوى">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        germanLevelIndex++;
    }

    // 4. إدارة صفوف المميزات بالستايل الموحد
    let germanFeatureIndex = <?php echo count($german_data['features_section']['features_list'] ?? []); ?>;
    function addGermanFeatureRow() {
        const container = document.getElementById('germanFeaturesContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm feat-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'german_feat_' + germanFeatureIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="features_list[]" placeholder="أدخل الميزة الجديدة...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_feat_${germanFeatureIndex}')" title="حذف الميزة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        germanFeatureIndex++;
    }

    // 5. إدارة صفوف النصائح بالستايل الموحد
    let germanTipIndex = <?php echo count($german_data['tips_section']['tips_list'] ?? []); ?>;
    function addGermanTipRow() {
        const container = document.getElementById('germanTipsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm tip-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'german_tip_' + germanTipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips_list[]" placeholder="أدخل النصيحة الجديدة...">
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeGermanRow('german_tip_${germanTipIndex}')" title="حذف النصيحة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        germanTipIndex++;
    }

    // 6. معالج الحفظ الموحد عبر AJAX لجميع نماذج صفحة اللغة الألمانية
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#germanBreadcrumbForm, #germanHeroForm, #germanMainForm, #germanLevelsForm, #germanFeaturesForm, #germanTipsForm').forEach(form => {
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

