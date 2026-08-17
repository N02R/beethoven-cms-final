<!-- 1. Job Hero Modal -->
<div class="modal fade custom-modal" id="jobHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-briefcase-fill text-primary"></i> تعديل قسم البداية للتدريب والتوظيف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($job_hero['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                            <textarea class="form-control" name="desc" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($job_hero['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">نص الزر</label>
                                <input type="text" class="form-control" name="btn_text" value="<?php echo htmlspecialchars($job_hero['btn_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط الزر</label>
                                <input type="text" class="form-control" name="btn_url" value="<?php echo htmlspecialchars($job_hero['btn_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-2 text-secondary d-block">الصورة الحالية</label>
                        <?php if (!empty($job_hero['img'])): ?>
                            <div class="p-2 border rounded-3 bg-light mb-3 text-center" style="max-width: 200px;">
                                <img src="<?php echo htmlspecialchars(get_image_url($job_hero['img']), ENT_QUOTES, 'UTF-8') . '?' . time(); ?>" alt="Job Hero Image" class="rounded-2" style="max-height: 100px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <label class="small fw-bold mb-1 text-secondary">تغيير الصورة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($job_hero['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. Job Why Modal -->
<div class="modal fade custom-modal" id="jobWhyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-question-circle-fill text-primary"></i> تعديل قسم (لماذا التدريب معنا؟)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_why">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="why_title" value="<?php echo htmlspecialchars($job_why_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="why_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($job_why_desc); ?></textarea>
                    </div>
                    <hr>
                    <div id="jobWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($job_why_items as $i => $item): ?>
                            <div class="card p-3 border-0 job-why-row-item" id="job_why_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">العنوان</label>
                                        <input type="text" class="form-control form-control-sm" name="items[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">الوصف</label>
                                        <input type="text" class="form-control form-control-sm" name="items[<?php echo $i; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">الصورة / الأيقونة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img'])); ?>" alt="Icon" class="rounded" style="width: 28px; height: 28px; object-fit: cover;">
                                                    <span class="small text-muted text-truncate" style="max-width: 100px; font-size: 11px;"><?php echo basename($item['img']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="why_img_<?php echo $i; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="items[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('job_why_row_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addJobWhyRow()">+ إضافة سبب جديد</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobWhyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. Job Program Modal (أنواع التدريب) -->
<div class="modal fade custom-modal" id="jobProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-bookmark-fill text-primary"></i> إدارة برامج وأنواع التدريب المهني</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobProgramForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_program">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="program_title" value="<?php echo htmlspecialchars($job_program_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">الوصف العام للقسم</label>
                        <textarea class="form-control" name="program_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($job_program_desc); ?></textarea>
                    </div>
                    <hr>
                    <div id="jobProgramContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($job_program_types as $i => $prog): ?>
                            <div class="card p-3 border-0 job-prog-row-item" id="prog_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">اسم البرنامِج</label>
                                        <input type="text" class="form-control form-control-sm" name="programs[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($prog['title'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">نص الزر</label>
                                        <input type="text" class="form-control form-control-sm" name="programs[<?php echo $i; ?>][btn_text]" value="<?php echo htmlspecialchars($prog['btn_text'] ?? 'اطلب الآن'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">رابط الزر</label>
                                        <input type="text" class="form-control form-control-sm" name="programs[<?php echo $i; ?>][btn_url]" value="<?php echo htmlspecialchars($prog['btn_url'] ?? '#'); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small text-muted mb-1">تفاصيل البرنامِج</label>
                                        <textarea class="form-control form-control-sm" name="programs[<?php echo $i; ?>][desc]" rows="2" style="height: auto;"><?php echo htmlspecialchars($prog['desc'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">الصورة والألوان</label>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <?php if (!empty($prog['img'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($prog['img'])); ?>" alt="Img" class="rounded" style="width: 28px; height: 28px; object-fit: cover;">
                                                    <span class="small text-muted text-truncate" style="max-width: 100px; font-size: 11px;"><?php echo basename($prog['img']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="prog_img_<?php echo $i; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="programs[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars($prog['img'] ?? ''); ?>">
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox" name="programs[<?php echo $i; ?>][is_dark]" value="1" <?php echo !empty($prog['is_dark']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small text-muted">تصميم داكن (Highlight)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('prog_row_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addJobProgramRow()">+ إضافة برنامج جديد</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobProgramForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 4. Job Timeline Modal (خطوات التدريب والتوظيف) -->
<div class="modal fade custom-modal" id="jobTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3-fill text-primary"></i> إدارة خطوات المساعدة والرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_timeline">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($job_timeline_title ?? ''); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">الوصف العام للقسم</label>
                        <textarea class="form-control" name="timeline_desc" rows="2"><?php echo htmlspecialchars($job_timeline_desc ?? ''); ?></textarea>
                    </div>

                    <div id="jobTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($job_timeline_steps ?? []) as $index => $step): ?>
                            <div class="card p-3 border-0 job-step-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="job_step_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">اسم الخطوة</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">العنوان الفرعي</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? ''); ?>" placeholder="العنوان الفرعي">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="small text-muted fw-bold mb-1">التفاصيل</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? ''); ?>" placeholder="التفاصيل">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="small text-muted fw-bold mb-1">الترتيب</label>
                                        <input type="number" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][order]" value="<?php echo ($step['order'] ?? $index); ?>" placeholder="الترتيب">
                                    </div>
                                    <div class="col-md-9">
                                        <label class="small text-muted fw-bold mb-1">الأيقونة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($step['icon'])); ?>" style="width: 28px; height: 28px; object-fit: contain;" alt="icon">
                                                    <span class="small text-muted text-truncate" style="font-size: 11px; max-width: 120px;"><?php echo basename($step['icon']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="steps_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="steps[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeTimelineRow('job_step_row_<?php echo $index; ?>')" title="حذف الخطوة">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addJobStepRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobTimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- 5. Job Services Modal -->
<div class="modal fade custom-modal" id="jobServicesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-grid-fill text-primary"></i> إدارة كروت الخدمات المعروضة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars($job_services_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">الوصف العام للقسم</label>
                        <textarea class="form-control" name="services_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($job_services_desc); ?></textarea>
                    </div>
                    <hr>
                    <div id="jobServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($job_services_items as $i => $item): ?>
                            <div class="card p-3 border-0 job-srv-row-item" id="job_srv_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">اسم الخدمة</label>
                                        <input type="text" class="form-control form-control-sm" name="services[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">الرابط</label>
                                        <input type="text" class="form-control form-control-sm" name="services[<?php echo $i; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">صورة الخلفية</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img'])); ?>" alt="Img" class="rounded" style="width: 28px; height: 28px; object-fit: cover;">
                                                    <span class="small text-muted text-truncate" style="max-width: 100px; font-size: 11px;"><?php echo basename($item['img']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="srv_img_<?php echo $i; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="services[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('job_srv_row_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addJobServiceRow()">+ إضافة خدمة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobServicesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<!-- Dynamic Rows JS Engine -->
<script>
    // 1. دالة عامة لحذف أي صف بناءً على الـ ID
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية
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

    // دالة التحكم في زر الإضافة وإخفائه عند الوصول إلى 6 عناصر
    function toggleTimelineAddButton() {
        const container = document.getElementById('jobTimelineContainer');
        if (!container) return;
        const currentRows = container.querySelectorAll('.job-step-row-item').length;
        const addBtn = document.querySelector('#jobTimelineForm button[onclick="addJobStepRow()"]');
        
        if (addBtn) {
            addBtn.style.display = (currentRows >= 6) ? 'none' : 'block';
        }
    }

    function removeTimelineRow(id) {
        const el = document.getElementById(id);
        if (el) {
            el.remove();
            toggleTimelineAddButton(); 
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleTimelineAddButton();
    });

    // 3. دالة إضافة صف جديد لـ "خطوات التدريب والتوظيف" مع قيد الـ 6 عناصر
    function addJobStepRow() {
        const container = document.getElementById('jobTimelineContainer');
        if (!container) return;
        
        const currentRows = container.querySelectorAll('.job-step-row-item').length;
        
        if (currentRows >= 6) {
            showNotification('عذراً، لا يمكن إضافة أكثر من 6 عناصر في خط الزمن (Timeline).', 'warning');
            return;
        }

        const jobStepCount = currentRows;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 job-step-row-item mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_step_row_' + Date.now() + '_' + jobStepCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">اسم الخطوة</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">العنوان الفرعي</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
                <div class="col-md-12">
                    <label class="small text-muted fw-bold mb-1">التفاصيل</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted fw-bold mb-1">الترتيب</label>
                    <input type="number" class="form-control form-control-sm" name="steps[${jobStepCount}][order]" value="${jobStepCount}" placeholder="الترتيب">
                </div>
                <div class="col-md-9">
                    <label class="small text-muted fw-bold mb-1">الأيقونة الجديدة</label>
                    <input type="file" class="form-control form-control-sm" name="steps_icon_${jobStepCount}" accept="image/*">
                    <input type="hidden" name="steps[${jobStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeTimelineRow('${div.id}')" title="حذف الخطوة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
        toggleTimelineAddButton();
    }

    // 4. معالج الحفظ وإعادة الترقيم التلقائي
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // إعادة ترقيم "خطوات التدريب والتوظيف"
                const stepRows = form.querySelectorAll('.job-step-row-item');
                stepRows.forEach((row, index) => {
                    const titleInput = row.querySelector('input[name*="[title]"]');
                    const subtitleInput = row.querySelector('input[name*="[subtitle]"]');
                    const orderInput = row.querySelector('input[name*="[order]"]');
                    const descInput = row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('input[type="file"]');
                    const oldIconInput = row.querySelector('input[name*="[old_icon]"]');

                    if (titleInput) titleInput.name = `steps[${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `steps[${index}][subtitle]`;
                    if (orderInput) orderInput.name = `steps[${index}][order]`;
                    if (descInput) descInput.name = `steps[${index}][desc]`;
                    if (fileInput) fileInput.name = `steps_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `steps[${index}][old_icon]`;
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


