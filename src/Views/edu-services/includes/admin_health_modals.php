<!-- Breadcrumb Modal -->
<div class="modal fade custom-modal" id="healthBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_health_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($health_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($health_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Hero Image Modal -->
<div class="modal fade custom-modal" id="healthHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل الهيدر والصورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_health_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($health_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($health_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($health_data['hero_img'], 'assets/img/education/servicesimg6.png'), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="healthHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- 1. Main Title & Description Modal -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="healthMainTitleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthMainTitleForm" method="POST">
                    <input type="hidden" name="action" value="update_health_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($health_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($health_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthMainTitleForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. Tips / Importance Modal (Dynamic List) -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="healthTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-circle text-primary"></i> تعديل أهمية التأمين الصحي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_health_tips">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($health_data['advice_title'] ?? 'لماذا التأمين الصحي مهم؟', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النقاط (تعديل / إضافة / حذف)</label>
                    <div id="healthTipsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($health_data['tips'])): ?>
                            <?php foreach ($health_data['tips'] as $index => $tip): ?>
                                <div class="p-3 shadow-sm tip-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="health_tip_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips[]" value="<?php echo htmlspecialchars($tip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب نقطة الأهمية هنا..." required>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeHealthRow('health_tip_<?php echo $index; ?>')" title="حذف النقطة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addHealthTipRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. Notes / Documents Modal (Dynamic List) -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="healthNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-text text-primary"></i> تعديل الوثائق المكملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_health_notes">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($health_data['note_title'] ?? 'الوثائق المكملة', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الوثائق (تعديل / إضافة / حذف)</label>
                    <div id="healthNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($health_data['notes'])): ?>
                            <?php foreach ($health_data['notes'] as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="health_note_<?php echo $index; ?>">
                                    <textarea class="form-control" name="notes[]" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب الوثيقة هنا (تدعم HTML)..." required><?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeHealthRow('health_note_<?php echo $index; ?>')" title="حذف الوثيقة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addHealthNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة وثيقة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- Combined Intro & Links Modal -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="healthLinksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-link-45deg text-primary"></i> إدارة وصف وروابط الحجز والشركات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthLinksForm" method="POST">
                    <input type="hidden" name="action" value="update_health_intro_and_links">
                    
                    <!-- 1. قسم وصف روابط الحجز التمهيدي -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">وصف روابط الحجز التمهيدي</label>
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <textarea class="form-control" name="intro_desc" rows="3" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($health_data['intro_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- 2. قسم قائمة روابط الشركات والحجز -->
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الروابط (تعديل / إضافة / حذف)</label>
                    <div id="healthLinksContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($health_data['links']) && is_array($health_data['links'])): ?>
                            <?php foreach ($health_data['links'] as $index => $link_item): ?>
                                <div class="p-4 shadow-sm position-relative link-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="health_link_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">نص الزر / الرابط</label>
                                            <input type="text" class="form-control" name="link_texts[]" value="<?php echo htmlspecialchars($link_item['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: رابط التسجيل..." required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">رابط الـ URL (اختياري)</label>
                                            <input type="text" class="form-control" name="link_urls[]" value="<?php echo htmlspecialchars($link_item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://...">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" name="link_actives[<?php echo $index; ?>]" value="1" id="health_active_<?php echo $index; ?>" <?php echo (!empty($link_item['active'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label fw-semibold small text-secondary" for="health_active_<?php echo $index; ?>">
                                                    اجعل هذا الزر نشطاً (Active - يظهر بلون مميز وعريض)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeHealthRow('health_link_<?php echo $index; ?>')">
                                            <i class="bi bi-trash"></i> حذف هذا الرابط
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addHealthLinkRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة رابط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript Engine -->
<script>
    // 1. دالة عامة لحذف أي صف (نصائح، وثائق، روابط)
    function removeHealthRow(id) {
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

    // 3. إدارة صفوف الأهمية والنقاط بالستايل الموحد
    let tipIndex = <?php echo count($health_data['tips'] ?? []); ?>;
    function addHealthTipRow() {
        const container = document.getElementById('healthTipsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm tip-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'health_tip_' + tipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips[]" placeholder="اكتب نقطة الأهمية هنا..." required>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeHealthRow('health_tip_${tipIndex}')" title="حذف النقطة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        tipIndex++;
    }

    // 4. إدارة صفوف الوثائق المكملة بالستايل الموحد
    let noteIndex = <?php echo count($health_data['notes'] ?? []); ?>;
    function addHealthNoteRow() {
        const container = document.getElementById('healthNotesContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'health_note_' + noteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="notes[]" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب الوثيقة هنا (تدعم HTML)..." required></textarea>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeHealthRow('health_note_${noteIndex}')" title="حذف الوثيقة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // 5. إدارة روابط الشركات والحجز بالستايل الموحد
    let linkIndex = <?php echo count($health_data['links'] ?? []); ?>;
    function addHealthLinkRow() {
        const container = document.getElementById('healthLinksContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-4 shadow-sm position-relative link-item-box';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'health_link_' + linkIndex;
        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">نص الزر / الرابط</label>
                    <input type="text" class="form-control" name="link_texts[]" placeholder="مثال: رابط التسجيل..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">رابط الـ URL (اختياري)</label>
                    <input type="text" class="form-control" name="link_urls[]" placeholder="https://...">
                </div>
                <div class="col-md-12">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="link_actives[${linkIndex}]" value="1" id="health_active_${linkIndex}">
                        <label class="form-check-label fw-semibold small text-secondary" for="health_active_${linkIndex}">
                            اجعل هذا الزر نشطاً (Active - يظهر بلون مميز وعريض)
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeHealthRow('health_link_${linkIndex}')">
                    <i class="bi bi-trash"></i> حذف هذا الرابط
                </button>
            </div>
        `;
        container.appendChild(div);
        linkIndex++;
    }

    // 6. ربط كافة نماذج صفحة التأمين الصحي عبر AJAX (تم تحديث النماذج لتشمل النموذج المدمج الجديد)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#healthBreadcrumbForm, #healthHeroForm, #healthMainTitleForm, #healthTipsForm, #healthNotesForm, #healthLinksForm').forEach(form => {
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


