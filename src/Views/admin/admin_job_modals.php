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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($job_hero['title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea class="form-control" name="desc" rows="4" style="height: auto;"><?php echo htmlspecialchars($job_hero['desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="btn_text" value="<?php echo htmlspecialchars($job_hero['btn_text'] ?? ''); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="btn_url" value="<?php echo htmlspecialchars($job_hero['btn_url'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">الصورة الحالية</label>
                        <?php if (!empty($job_hero['img'])): ?>
                            <div class="p-2 border rounded bg-light mb-2 text-center">
                                <img src="<?php echo htmlspecialchars(get_image_url($job_hero['img'])) . '?' . time(); ?>" style="max-height: 100px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <label class="form-label fw-bold">تغيير الصورة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($job_hero['img'] ?? ''); ?>">
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($job_timeline_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">الوصف العام للقسم</label>
                        <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto;"><?php echo htmlspecialchars($job_timeline_desc); ?></textarea>
                    </div>
                    <hr>
                    <div id="jobTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($job_timeline_steps as $i => $step): ?>
                            <div class="card p-3 border-0 job-step-row-item" id="job_step_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">اسم الخطوة</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">العنوان الفرعي</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $i; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted mb-1">الترتيب</label>
                                        <input type="number" class="form-control form-control-sm" name="steps[<?php echo $i; ?>][order]" value="<?php echo ($step['order'] ?? $i); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">أيقونة الخطوة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($step['icon'])); ?>" alt="Icon" class="rounded" style="width: 28px; height: 28px; object-fit: cover;">
                                                    <span class="small text-muted text-truncate" style="max-width: 100px; font-size: 11px;"><?php echo basename($step['icon']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="steps_icon_<?php echo $i; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="steps[<?php echo $i; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('job_step_row_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="small text-muted mb-1">التفاصيل</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $i; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addJobStepRow()">+ إضافة خطوة جديدة</button>
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
    // 1. الدالة العامة للتنبيهات العائمة (لضمان عمل showNotification في أي مكان)
    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-success');
        let icon = type === 'danger' ? 'bi-x-circle-fill' : (type === 'warning' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill');
        let title = type === 'danger' ? 'عذراً، حدث خطأ!' : (type === 'warning' ? 'تنبيه هام' : 'تم بنجاح!');

        const alertDiv = document.createElement('div');
        alertDiv.id = 'customNotificationAlert';
        alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
        alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
        
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon} fs-4"></i>
                <div><strong>${title}</strong><div class="small">${message}</div></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => { if (alertDiv) alertDiv.classList.remove('show'); setTimeout(() => alertDiv.remove(), 300); }, 4000);
    }

    // دوال الحذف العامة
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function removeTimelineRow(id) {
        const el = document.getElementById(id);
        if (el) {
            el.remove();
            toggleTimelineAddButton(); 
        }
    }

    // دالة التحكم في زر الخطوات
    function toggleTimelineAddButton() {
        const container = document.getElementById('jobTimelineContainer');
        if (!container) return;
        const currentRows = container.querySelectorAll('.job-step-row-item').length;
        const addBtn = document.querySelector('#jobTimelineForm button[onclick="addJobStepRow()"]');
        
        if (addBtn) {
            addBtn.style.display = (currentRows >= 6) ? 'none' : 'inline-block';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleTimelineAddButton();
    });

    let jobWhyCount = <?php echo count($job_why_items); ?>;
    function addJobWhyRow() {
        const container = document.getElementById('jobWhyContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-why-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_why_row_' + jobWhyCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">العنوان</label>
                    <input type="text" class="form-control form-control-sm" name="items[${jobWhyCount}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">الوصف</label>
                    <input type="text" class="form-control form-control-sm" name="items[${jobWhyCount}][desc]" placeholder="الوصف">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">الصورة / الأيقونة</label>
                    <input type="file" class="form-control form-control-sm" name="why_img_${jobWhyCount}" accept="image/*">
                    <input type="hidden" name="items[${jobWhyCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('job_why_row_${jobWhyCount}')"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        jobWhyCount++;
    }

    let jobProgCount = <?php echo count($job_program_types); ?>;
    function addJobProgramRow() {
        const container = document.getElementById('jobProgramContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-prog-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'prog_row_' + jobProgCount;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="small text-muted mb-1">اسم البرنامِج</label>
                    <input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][title]" placeholder="اسم البرنامِج">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">نص الزر</label>
                    <input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][btn_text]" value="اطلب الآن" placeholder="نص الزر">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">رابط الزر</label>
                    <input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][btn_url]" value="#" placeholder="رابط الزر">
                </div>
                <div class="col-md-8">
                    <label class="small text-muted mb-1">تفاصيل البرنامِج</label>
                    <textarea class="form-control form-control-sm" name="programs[${jobProgCount}][desc]" rows="2" placeholder="تفاصيل البرنامِج"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">الصورة والألوان</label>
                    <input type="file" class="form-control form-control-sm mb-1" name="prog_img_${jobProgCount}" accept="image/*">
                    <input type="hidden" name="programs[${jobProgCount}][old_img]" value="">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="programs[${jobProgCount}][is_dark]" value="1" id="prog_dark_${jobProgCount}">
                        <label class="form-check-label small text-muted" for="prog_dark_${jobProgCount}">تصميم داكن (Highlight)</label>
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('prog_row_${jobProgCount}')"><i class="bi bi-trash"></i> حذف البرنامج</button>
                </div>
            </div>`;
        container.appendChild(div);
        jobProgCount++;
    }

    let jobStepCount = <?php echo count($job_timeline_steps); ?>;
    function addJobStepRow() {
        const container = document.getElementById('jobTimelineContainer');
        const currentRows = container.querySelectorAll('.job-step-row-item').length;
        
        if (currentRows >= 6) {
            showNotification('عذراً، الحد الأقصى للخطوات هو 6 خطوات فقط.', 'warning');
            return;
        }

        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-step-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_step_row_' + jobStepCount;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">اسم الخطوة</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">العنوان الفرعي</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted mb-1">الترتيب</label>
                    <input type="number" class="form-control form-control-sm" name="steps[${jobStepCount}][order]" value="${currentRows}" placeholder="الترتيب">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">أيقونة الخطوة</label>
                    <input type="file" class="form-control form-control-sm" name="steps_icon_${jobStepCount}" accept="image/*">
                    <input type="hidden" name="steps[${jobStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeTimelineRow('job_step_row_${jobStepCount}')"><i class="bi bi-trash"></i></button>
                </div>
                <div class="col-md-12 mt-2">
                    <label class="small text-muted mb-1">التفاصيل</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][desc]" placeholder="التفاصيل">
                </div>
            </div>`;
        container.appendChild(div);
        jobStepCount++;
        toggleTimelineAddButton();
    }

    let jobSrvCount = <?php echo count($job_services_items); ?>;
    function addJobServiceRow() {
        const container = document.getElementById('jobServicesContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-srv-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_srv_row_' + jobSrvCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">اسم الخدمة</label>
                    <input type="text" class="form-control form-control-sm" name="services[${jobSrvCount}][title]" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">الرابط</label>
                    <input type="text" class="form-control form-control-sm" name="services[${jobSrvCount}][url]" placeholder="الرابط">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">صورة الخلفية</label>
                    <input type="file" class="form-control form-control-sm" name="srv_img_${jobSrvCount}" accept="image/*">
                    <input type="hidden" name="services[${jobSrvCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('job_srv_row_${jobSrvCount}')"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
        jobSrvCount++;
    }

    document.querySelectorAll('.custom-modal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // إعادة ترقيم كافة الصفوف قبل الإرسال مع ضبط أسماء حقول الملفات بشكل دقيق
            form.querySelectorAll('.job-why-row-item').forEach((row, index) => {
                row.querySelector('input[name*="[title]"]')?.setAttribute('name', `items[${index}][title]`);
                row.querySelector('input[name*="[desc]"]')?.setAttribute('name', `items[${index}][desc]`);
                row.querySelector('input[name*="[old_img]"]')?.setAttribute('name', `items[${index}][old_img]`);
                const fileInput = row.querySelector('input[type="file"]');
                if (fileInput) fileInput.setAttribute('name', `why_img_${index}`);
            });

            form.querySelectorAll('.job-prog-row-item').forEach((row, index) => {
                row.querySelector('input[name*="[title]"]')?.setAttribute('name', `programs[${index}][title]`);
                row.querySelector('input[name*="[btn_text]"]')?.setAttribute('name', `programs[${index}][btn_text]`);
                row.querySelector('input[name*="[btn_url]"]')?.setAttribute('name', `programs[${index}][btn_url]`);
                row.querySelector('textarea')?.setAttribute('name', `programs[${index}][desc]`);
                row.querySelector('input[name*="[old_img]"]')?.setAttribute('name', `programs[${index}][old_img]`);
                row.querySelector('input[type="checkbox"]')?.setAttribute('name', `programs[${index}][is_dark]`);
                const fileInput = row.querySelector('input[type="file"]');
                if (fileInput) fileInput.setAttribute('name', `prog_img_${index}`);
            });

            form.querySelectorAll('.job-step-row-item').forEach((row, index) => {
                row.querySelector('input[name*="[title]"]')?.setAttribute('name', `steps[${index}][title]`);
                row.querySelector('input[name*="[subtitle]"]')?.setAttribute('name', `steps[${index}][subtitle]`);
                row.querySelector('input[name*="[order]"]')?.setAttribute('name', `steps[${index}][order]`);
                row.querySelector('input[name*="[desc]"]')?.setAttribute('name', `steps[${index}][desc]`);
                row.querySelector('input[name*="[old_icon]"]')?.setAttribute('name', `steps[${index}][old_icon]`);
                
                const fileInput = row.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.setAttribute('name', `steps_icon_${index}`);
                }
            });

            form.querySelectorAll('.job-srv-row-item').forEach((row, index) => {
                row.querySelector('input[name*="[title]"]')?.setAttribute('name', `services[${index}][title]`);
                row.querySelector('input[name*="[url]"]')?.setAttribute('name', `services[${index}][url]`);
                row.querySelector('input[name*="[old_img]"]')?.setAttribute('name', `services[${index}][old_img]`);
                const fileInput = row.querySelector('input[type="file"]');
                if (fileInput) fileInput.setAttribute('name', `srv_img_${index}`);
            });

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? ""); ?>';
            
            fetch('index.php?url=admin/settings/save', {
                method: 'POST',
                headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم حفظ التعديلات بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء الحفظ', 'danger');
                }
            })
            .catch(() => showNotification('خطأ في الاتصال بالسيرفر', 'danger'));
        });
    });
</script>


