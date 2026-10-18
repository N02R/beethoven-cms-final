<!-- 1. Edu Hero Modal (قسم الهيرو) -->
<div class="modal fade custom-modal" id="eduHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-mortarboard text-primary"></i> تعديل هيرو التعليم العالي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">العنوان الرئيسي</label>
                                <input type="text" class="form-control" name="edu_hero_title" value="<?php echo htmlspecialchars($edu_hero['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                                <textarea class="form-control" name="edu_hero_desc" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_hero['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">نص الزر</label>
                                <input type="text" class="form-control" name="edu_hero_btn_text" value="<?php echo htmlspecialchars($edu_hero['btn_text'] ?? 'ابدأ الآن', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط الزر</label>
                                <input type="text" class="form-control" name="edu_hero_btn_url" value="<?php echo htmlspecialchars($edu_hero['btn_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">الصورة الرئيسية</label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($edu_hero['img'])): ?>
                                        <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                            <img src="<?php echo htmlspecialchars(get_image_url($edu_hero['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="edu_hero_img" accept="image/*">
                                </div>
                                <input type="hidden" name="old_edu_hero_img" value="<?php echo htmlspecialchars($edu_hero['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Edu Why Modal (قسم لماذا الدراسة) -->
<div class="modal fade custom-modal" id="eduWhyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-patch-question text-primary"></i> إدارة (لماذا الدراسة في ألمانيا)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_why">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label for="edu_why_title_input" class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" id="edu_why_title_input" class="form-control" name="edu_why_title" value="<?php echo htmlspecialchars($edu_why_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label for="edu_why_desc_input" class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea id="edu_why_desc_input" class="form-control" name="edu_why_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_why_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة الأسباب -->
                    <div id="eduWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_why_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm edu-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="why_row_<?php echo $index; ?>">
                                
                                <!-- السطر الأول: العنوان والوصف -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label for="edu_why_title_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">العنوان</label>
                                        <input type="text" id="edu_why_title_<?php echo $index; ?>" class="form-control edu-why-title" name="edu_why[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: جودة التعليم">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edu_why_desc_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                                        <input type="text" id="edu_why_desc_<?php echo $index; ?>" class="form-control edu-why-desc" name="edu_why[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="شرح بسيط للسبب">
                                    </div>
                                </div>

                                <!-- السطر الثاني: الأيقونة + زر الرفع + زر الحذف -->
                                <div class="row g-2 align-items-end">
                                    <div class="col-11">
                                        <label for="edu_why_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="edu_why_file_<?php echo $index; ?>" class="form-control edu-why-file" name="edu_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-why-old-img" name="edu_why[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('why_row_<?php echo $index; ?>')" title="حذف السبب"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduWhyRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة سبب جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduWhyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Edu Timeline Modal (قسم خطوات الرحلة) -->
<div class="modal fade custom-modal" id="eduTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3 text-primary"></i> إدارة خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_timeline">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label for="edu_timeline_title_input" class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" id="edu_timeline_title_input" class="form-control" name="edu_timeline_title" value="<?php echo htmlspecialchars($edu_timeline_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label for="edu_timeline_desc_input" class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea id="edu_timeline_desc_input" class="form-control" name="edu_timeline_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_timeline_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة خطوات الرحلة -->
                    <div id="eduTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_timeline_steps ?? []) as $index => $step): ?>
                            <div class="p-3 shadow-sm edu-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="step_row_<?php echo $index; ?>">
                                
                                <!-- السطر الأول: اسم الخطوة والعنوان الفرعي -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label for="edu_step_title_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                                        <input type="text" id="edu_step_title_<?php echo $index; ?>" class="form-control edu-step-title" name="edu_timeline[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edu_step_subtitle_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                                        <input type="text" id="edu_step_subtitle_<?php echo $index; ?>" class="form-control edu-step-subtitle" name="edu_timeline[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان الفرعي">
                                    </div>
                                </div>

                                <!-- السطر الثاني: التفاصيل والترتيب -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-9">
                                        <label for="edu_step_desc_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">التفاصيل</label>
                                        <input type="text" id="edu_step_desc_<?php echo $index; ?>" class="form-control edu-step-desc" name="edu_timeline[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="التفاصيل">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="edu_step_order_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الترتيب</label>
                                        <input type="number" id="edu_step_order_<?php echo $index; ?>" class="form-control edu-step-order" name="edu_timeline[<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($step['order'] ?? $index, ENT_QUOTES, 'UTF-8'); ?>" placeholder="الترتيب">
                                    </div>
                                </div>

                                <!-- السطر الثالث: الأيقونة الحالية / الجديدة وزر الحذف -->
                                <div class="row g-2 align-items-end">
                                    <div class="col-11">
                                        <label for="edu_step_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الأيقونة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($step['icon']), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="edu_step_file_<?php echo $index; ?>" class="form-control edu-step-file" name="edu_timeline_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-step-old-icon" name="edu_timeline[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('step_row_<?php echo $index; ?>')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduStepRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduTimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Edu Services Modal (قسم خدمات التعليم العالي) -->
<div class="modal fade custom-modal" id="eduServicesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-grid text-primary"></i> إدارة خدمات التعليم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label for="edu_services_title_input" class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" id="edu_services_title_input" class="form-control" name="edu_services_title" value="<?php echo htmlspecialchars($edu_services_title ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label for="edu_services_desc_input" class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea id="edu_services_desc_input" class="form-control" name="edu_services_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($edu_services_desc ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <!-- قائمة خدمات التعليم -->
                    <div id="eduServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_services_items ?? []) as $index => $item): ?>
                            <div class="p-3 shadow-sm edu-service-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="edu_srv_row_<?php echo $index; ?>">
                                
                                <!-- السطر الأول: اسم الخدمة والرابط -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label for="edu_srv_title_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                                        <input type="text" id="edu_srv_title_<?php echo $index; ?>" class="form-control edu-srv-title" name="edu_services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخدمة">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edu_srv_url_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">رابط الخدمة (اسم الصفحة فقط)</label>
                                        <input type="text" id="edu_srv_url_<?php echo $index; ?>" class="form-control edu-srv-url" name="edu_services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: bachelor-package">
                                    </div>
                                </div>

                                <!-- السطر الثاني: الصورة + زر الرفع + زر الحذف -->
                                <div class="row g-2 align-items-end">
                                    <div class="col-11">
                                        <label for="edu_srv_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">صورة الخلفية الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="service image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="edu_srv_file_<?php echo $index; ?>" class="form-control edu-srv-file" name="edu_service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-srv-old-img" name="edu_services[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('edu_srv_row_<?php echo $index; ?>')" title="حذف الخدمة"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addEduServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خدمة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduServicesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<script>
    // 1. دالة عامة لحذف أي صف بناءً على الـ ID
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية فوق المودال
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
        alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
        
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

    // 3. دالة إضافة صف جديد لـ "لماذا الدراسة"
    function addEduWhyRow() {
        const container = document.getElementById('eduWhyContainer');
        if (!container) return;
        const eduWhyCount = container.querySelectorAll('.edu-why-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-why-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'why_row_' + Date.now() + '_' + eduWhyCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="edu_why_title_${eduWhyCount}" class="form-label fw-semibold small text-secondary">العنوان</label>
                    <input type="text" id="edu_why_title_${eduWhyCount}" class="form-control edu-why-title" name="edu_why[${eduWhyCount}][title]" placeholder="مثال: جودة التعليم">
                </div>
                <div class="col-md-6">
                    <label for="edu_why_desc_${eduWhyCount}" class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                    <input type="text" id="edu_why_desc_${eduWhyCount}" class="form-control edu-why-desc" name="edu_why[${eduWhyCount}][desc]" placeholder="شرح بسيط للسبب">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label for="edu_why_file_${eduWhyCount}" class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="edu_why_file_${eduWhyCount}" class="form-control edu-why-file" name="edu_why_img_${eduWhyCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-why-old-img" name="edu_why[${eduWhyCount}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف السبب">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 4. دالة إضافة صف جديد لـ "خطوات الرحلة التعليمية" مع قيد الـ 6 عناصر
    function addEduStepRow() {
        const container = document.getElementById('eduTimelineContainer');
        if (!container) return;
        
        const currentRows = container.querySelectorAll('.edu-timeline-row-item').length;
        if (currentRows >= 6) {
            showNotification('عذراً، لا يمكن إضافة أكثر من 6 عناصر في خط الزمن (Timeline).', 'warning');
            return;
        }

        const eduStepCount = currentRows;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-timeline-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'step_row_' + Date.now() + '_' + eduStepCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="edu_step_title_${eduStepCount}" class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                    <input type="text" id="edu_step_title_${eduStepCount}" class="form-control edu-step-title" name="edu_timeline[${eduStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label for="edu_step_subtitle_${eduStepCount}" class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                    <input type="text" id="edu_step_subtitle_${eduStepCount}" class="form-control edu-step-subtitle" name="edu_timeline[${eduStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-9">
                    <label for="edu_step_desc_${eduStepCount}" class="form-label fw-semibold small text-secondary">التفاصيل</label>
                    <input type="text" id="edu_step_desc_${eduStepCount}" class="form-control edu-step-desc" name="edu_timeline[${eduStepCount}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-md-3">
                    <label for="edu_step_order_${eduStepCount}" class="form-label fw-semibold small text-secondary">الترتيب</label>
                    <input type="number" id="edu_step_order_${eduStepCount}" class="form-control edu-step-order" name="edu_timeline[${eduStepCount}][order]" value="${eduStepCount}" placeholder="الترتيب">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label for="edu_step_file_${eduStepCount}" class="form-label fw-semibold small text-secondary">الأيقونة الحالية / الجديدة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="edu_step_file_${eduStepCount}" class="form-control edu-step-file" name="edu_timeline_icon_${eduStepCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-step-old-icon" name="edu_timeline[${eduStepCount}][old_icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخطوة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 5. دالة إضافة صف جديد لـ "خدمات التعليم"
    function addEduServiceRow() {
        const container = document.getElementById('eduServicesContainer');
        if (!container) return;
        const eduSrvCount = container.querySelectorAll('.edu-service-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-service-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'edu_srv_row_' + Date.now() + '_' + eduSrvCount;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="edu_srv_title_${eduSrvCount}" class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                    <input type="text" id="edu_srv_title_${eduSrvCount}" class="form-control edu-srv-title" name="edu_services[${eduSrvCount}][title]" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-6">
                    <label for="edu_srv_url_${eduSrvCount}" class="form-label fw-semibold small text-secondary">رابط الخدمة</label>
                    <input type="text" id="edu_srv_url_${eduSrvCount}" class="form-control edu-srv-url" name="edu_services[${eduSrvCount}][url]" placeholder="مثال: coverletter أو bachelor-package">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label for="edu_srv_file_${eduSrvCount}" class="form-label fw-semibold small text-secondary">صورة الخلفية الحالية / الجديدة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="edu_srv_file_${eduSrvCount}" class="form-control edu-srv-file" name="edu_service_img_${eduSrvCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="edu-srv-old-img" name="edu_services[${eduSrvCount}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخدمة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 6. دالة إضافة صف جديد لـ "برامج وأنواع التدريب المهني" (Modal 3)
    function addJobProgramRow() {
        const container = document.getElementById('jobProgramContainer');
        if (!container) return;
        const count = container.querySelectorAll('.job-prog-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-prog-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'prog_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="prog_title_${count}" class="form-label fw-semibold small text-secondary">اسم البرنامج</label>
                    <input type="text" id="prog_title_${count}" class="form-control prog-title" name="programs[${count}][title]" placeholder="اسم البرنامِج">
                </div>
                <div class="col-md-3">
                    <label for="prog_btn_text_${count}" class="form-label fw-semibold small text-secondary">نص الزر</label>
                    <input type="text" id="prog_btn_text_${count}" class="form-control prog-btn-text" name="programs[${count}][btn_text]" value="اطلب الآن" placeholder="اطلب الآن">
                </div>
                <div class="col-md-3">
                    <label for="prog_btn_url_${count}" class="form-label fw-semibold small text-secondary">رابط الزر</label>
                    <input type="text" id="prog_btn_url_${count}" class="form-control prog-btn-url" name="programs[${count}][btn_url]" value="#" placeholder="#">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label for="prog_desc_${count}" class="form-label fw-semibold small text-secondary">تفاصيل البرنامج</label>
                    <textarea id="prog_desc_${count}" class="form-control prog-desc" name="programs[${count}][desc]" rows="2" style="height: auto; padding: 12px 16px;" placeholder="تفاصيل البرنامِج"></textarea>
                </div>
            </div>
            <div class="row g-2 align-items-end mb-3">
                <div class="col-11">
                    <label for="prog_file_${count}" class="form-label fw-semibold small text-secondary">صورة البرنامج</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="prog_file_${count}" class="form-control prog-file" name="prog_img_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="prog-old-img" name="programs[${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف البرنامج">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div class="form-check form-switch p-2 ps-4 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="prog_dark_${count}" name="programs[${count}][is_dark]" value="1">
                        <label class="form-check-label small text-secondary fw-semibold cursor-pointer" for="prog_dark_${count}">تصميم داكن (Highlight)</label>
                    </div>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 7. دالة إضافة صف جديد لـ "خطوات التدريب والتوظيف" (Modal 4)
    function addJobStepRow() {
        const container = document.getElementById('jobTimelineContainer');
        if (!container) return;
        const count = container.querySelectorAll('.job-step-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-step-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'job_step_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="job_step_title_${count}" class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                    <input type="text" id="job_step_title_${count}" class="form-control job-step-title" name="steps[${count}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label for="job_step_subtitle_${count}" class="form-label fw-semibold small text-secondary">العنوان الفرعي</label>
                    <input type="text" id="job_step_subtitle_${count}" class="form-control job-step-subtitle" name="steps[${count}][subtitle]" placeholder="العنوان الفرعي">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-9">
                    <label for="job_step_desc_${count}" class="form-label fw-semibold small text-secondary">التفاصيل</label>
                    <input type="text" id="job_step_desc_${count}" class="form-control job-step-desc" name="steps[${count}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-md-3">
                    <label for="job_step_order_${count}" class="form-label fw-semibold small text-secondary">الترتيب</label>
                    <input type="number" id="job_step_order_${count}" class="form-control job-step-order" name="steps[${count}][order]" value="${count}" placeholder="الترتيب">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label for="job_step_file_${count}" class="form-label fw-semibold small text-secondary">الأيقونة الحالية / الجديدة</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="job_step_file_${count}" class="form-control job-step-file" name="steps_icon_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="job-step-old-icon" name="steps[${count}][old_icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخطوة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 8. دالة إضافة صف جديد لـ "كروت الخدمات المعروضة" (Modal 5)
    function addJobServiceRow() {
        const container = document.getElementById('jobServicesContainer');
        if (!container) return;
        const count = container.querySelectorAll('.job-srv-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm job-srv-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'job_srv_row_' + Date.now() + '_' + count;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="job_srv_title_${count}" class="form-label fw-semibold small text-secondary">اسم الخدمة</label>
                    <input type="text" id="job_srv_title_${count}" class="form-control job-srv-title" name="services[${count}][title]" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-6">
                    <label for="job_srv_url_${count}" class="form-label fw-semibold small text-secondary">الرابط</label>
                    <input type="text" id="job_srv_url_${count}" class="form-control job-srv-url" name="services[${count}][url]" placeholder="الرابط">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label for="job_srv_file_${count}" class="form-label fw-semibold small text-secondary">صورة الخلفية</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="job_srv_file_${count}" class="form-control job-srv-file" name="srv_img_${count}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="job-srv-old-img" name="services[${count}][old_img]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="حذف الخدمة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 9. معالج الحفظ وإعادة الترقيم التلقائي لجميع الأقسام عند الحفظ عبر AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // إعادة ترقيم "لماذا الدراسة"
                form.querySelectorAll('.edu-why-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-why-title') || row.querySelector('input[name*="[title]"]');
                    const descInput = row.querySelector('.edu-why-desc') || row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('.edu-why-file') || row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('.edu-why-old-img') || row.querySelector('input[name*="[old_img]"]');

                    if (titleInput) titleInput.name = `edu_why[${index}][title]`;
                    if (descInput) descInput.name = `edu_why[${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_why_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_why[${index}][old_img]`;
                });

                // إعادة ترقيم "خطوات الرحلة التعليمية"
                form.querySelectorAll('.edu-timeline-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-step-title') || row.querySelector('input[name*="[title]"]');
                    const subtitleInput = row.querySelector('.edu-step-subtitle') || row.querySelector('input[name*="[subtitle]"]');
                    const orderInput = row.querySelector('.edu-step-order') || row.querySelector('input[name*="[order]"]');
                    const descInput = row.querySelector('.edu-step-desc') || row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('.edu-step-file') || row.querySelector('input[type="file"]');
                    const oldIconInput = row.querySelector('.edu-step-old-icon') || row.querySelector('input[name*="[old_icon]"]');

                    if (titleInput) titleInput.name = `edu_timeline[${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `edu_timeline[${index}][subtitle]`;
                    if (orderInput) orderInput.name = `edu_timeline[${index}][order]`;
                    if (descInput) descInput.name = `edu_timeline[${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_timeline_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `edu_timeline[${index}][old_icon]`;
                });

                // إعادة ترقيم "خدمات التعليم" وتعديل الرابط تلقائياً ليتوافق مع الـ Router
                form.querySelectorAll('.edu-service-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.edu-srv-title') || row.querySelector('input[name*="[title]"]');
                    const urlInput = row.querySelector('.edu-srv-url') || row.querySelector('input[name*="[url]"]');
                    const fileInput = row.querySelector('.edu-srv-file') || row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('.edu-srv-old-img') || row.querySelector('input[name*="[old_img]"]');

                    if (titleInput) titleInput.name = `edu_services[${index}][title]`;
                    
                    if (urlInput) {
                        let val = urlInput.value.trim();
                        if (val && !val.startsWith('http') && !val.includes('edu-services/') && !val.includes('job-services/')) {
                            val = 'edu-services/' + val;
                        }
                        urlInput.value = val;
                        urlInput.name = `edu_services[${index}][url]`;
                    }

                    if (fileInput) fileInput.name = `edu_service_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_services[${index}][old_img]`;
                });

                // إعادة ترقيم "برامج وأنواع التدريب المهني" (Modal 3)
                form.querySelectorAll('.job-prog-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.prog-title') || row.querySelector('input[name*="[title]"]');
                    const btnTextInput = row.querySelector('.prog-btn-text') || row.querySelector('input[name*="[btn_text]"]');
                    const btnUrlInput = row.querySelector('.prog-btn-url') || row.querySelector('input[name*="[btn_url]"]');
                    const descInput = row.querySelector('.prog-desc') || row.querySelector('textarea[name*="[desc]"]');
                    const fileInput = row.querySelector('.prog-file') || row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('.prog-old-img') || row.querySelector('input[name*="[old_img]"]');
                    const darkInput = row.querySelector('input[type="checkbox"]');

                    if (titleInput) titleInput.name = `programs[${index}][title]`;
                    if (btnTextInput) btnTextInput.name = `programs[${index}][btn_text]`;
                    if (btnUrlInput) btnUrlInput.name = `programs[${index}][btn_url]`;
                    if (descInput) descInput.name = `programs[${index}][desc]`;
                    if (fileInput) fileInput.name = `prog_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `programs[${index}][old_img]`;
                    if (darkInput) darkInput.name = `programs[${index}][is_dark]`;
                });

                // إعادة ترقيم "خطوات التدريب والتوظيف" (Modal 4)
                form.querySelectorAll('.job-step-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.job-step-title') || row.querySelector('input[name*="[title]"]');
                    const subtitleInput = row.querySelector('.job-step-subtitle') || row.querySelector('input[name*="[subtitle]"]');
                    const orderInput = row.querySelector('.job-step-order') || row.querySelector('input[name*="[order]"]');
                    const descInput = row.querySelector('.job-step-desc') || row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('.job-step-file') || row.querySelector('input[type="file"]');
                    const oldIconInput = row.querySelector('.job-step-old-icon') || row.querySelector('input[name*="[old_icon]"]');

                    if (titleInput) titleInput.name = `steps[${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `steps[${index}][subtitle]`;
                    if (orderInput) orderInput.name = `steps[${index}][order]`;
                    if (descInput) descInput.name = `steps[${index}][desc]`;
                    if (fileInput) fileInput.name = `steps_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `steps[${index}][old_icon]`;
                });

                // إعادة ترقيم "كروت الخدمات المعروضة" (Modal 5)
                form.querySelectorAll('.job-srv-row-item').forEach((row, index) => {
                    const titleInput = row.querySelector('.job-srv-title') || row.querySelector('input[name*="[title]"]');
                    const urlInput = row.querySelector('.job-srv-url') || row.querySelector('input[name*="[url]"]');
                    const fileInput = row.querySelector('.job-srv-file') || row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('.job-srv-old-img') || row.querySelector('input[name*="[old_img]"]');

                    if (titleInput) titleInput.name = `services[${index}][title]`;
                    if (urlInput) urlInput.name = `services[${index}][url]`;
                    if (fileInput) fileInput.name = `srv_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `services[${index}][old_img]`;
                });

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
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'يرجى التأكد من البيانات المدخلة'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
                });
            });
        });
    });
</script>

