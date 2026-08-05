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
                            <div class="card p-3 border-0 job-program-row-item" id="prog_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
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
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('prog_row_<?php echo $i; ?>')"><i class="bi bi-trash"></i> حذف البرنامج</button>
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
                            <div class="card p-3 border-0 job-timeline-row-item" id="job_step_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
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
                                            <input type="file" class="form-control form-control-sm" name="step_icon_<?php echo $i; ?>" accept="image/*">
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
                            <div class="card p-3 border-0 job-service-row-item" id="job_srv_row_<?php echo $i; ?>" style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 12px;">
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
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let jobWhyCount = <?php echo count($job_why_items); ?>;
    function addJobWhyRow() {
        const container = document.getElementById('jobWhyContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-why-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_why_row_' + jobWhyCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="items[${jobWhyCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="items[${jobWhyCount}][desc]" placeholder="الوصف"></div>
                <div class="col-md-4">
                    <input type="file" class="form-control form-control-sm" name="why_img_${jobWhyCount}" accept="image/*">
                    <input type="hidden" name="items[${jobWhyCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('job_why_row_${jobWhyCount}')"><i class="bi bi-trash"></i></button></div>
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
                <div class="col-md-6"><input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][title]" placeholder="اسم البرنامِج"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][btn_text]" value="اطلب الآن" placeholder="نص الزر"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="programs[${jobProgCount}][btn_url]" value="#" placeholder="رابط الزر"></div>
                <div class="col-md-8"><textarea class="form-control form-control-sm" name="programs[${jobProgCount}][desc]" rows="2" placeholder="تفاصيل البرنامِج"></textarea></div>
                <div class="col-md-4">
                    <input type="file" class="form-control form-control-sm mb-1" name="prog_img_${jobProgCount}" accept="image/*">
                    <input type="hidden" name="programs[${jobProgCount}][old_img]" value="">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="programs[${jobProgCount}][is_dark]" value="1" id="prog_dark_${jobProgCount}">
                        <label class="form-check-label small text-muted" for="prog_dark_${jobProgCount}">تصميم داكن (Highlight)</label>
                    </div>
                </div>
                <div class="col-12 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('prog_row_${jobProgCount}')"><i class="bi bi-trash"></i> حذف البرنامج</button></div>
            </div>`;
        container.appendChild(div);
        jobProgCount++;
    }

    let jobStepCount = <?php echo count($job_timeline_steps); ?>;
    function addJobStepRow() {
        const container = document.getElementById('jobTimelineContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 job-step-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'job_step_row_' + jobStepCount;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][title]" placeholder="اسم الخطوة"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][subtitle]" placeholder="العنوان الفرعي"></div>
                <div class="col-md-2"><input type="number" class="form-control form-control-sm" name="steps[${jobStepCount}][order]" value="${jobStepCount}" placeholder="الترتيب"></div>
                <div class="col-md-3">
                    <input type="file" class="form-control form-control-sm" name="step_icon_${jobStepCount}" accept="image/*">
                    <input type="hidden" name="steps[${jobStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('job_step_row_${jobStepCount}')"><i class="bi bi-trash"></i></button></div>
                <div class="col-md-12 mt-2"><input type="text" class="form-control form-control-sm" name="steps[${jobStepCount}][desc]" placeholder="التفاصيل"></div>
            </div>`;
        container.appendChild(div);
        jobStepCount++;
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
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[${jobSrvCount}][title]" placeholder="اسم الخدمة"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="services[${jobSrvCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-4">
                    <input type="file" class="form-control form-control-sm" name="srv_img_${jobSrvCount}" accept="image/*">
                    <input type="hidden" name="services[${jobSrvCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('job_srv_row_${jobSrvCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        jobSrvCount++;
    }

    // معالج النماذج الموحد الشامل المتوافق مع هيكل المشروع
    document.querySelectorAll('.custom-modal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // إعادة ترقيم صفوف (لماذا التدريب معنا) إن وجدت لتجنب الفراغات
            const whyRows = form.querySelectorAll('.job-why-row-item');
            whyRows.forEach((row, index) => {
                const titleInput = row.querySelector('input[name*="[title]"]');
                const descInput = row.querySelector('input[name*="[desc]"]');
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                if(titleInput) titleInput.name = `items[${index}][title]`;
                if(descInput) descInput.name = `items[${index}][desc]`;
                if(fileInput) fileInput.name = `why_img_${index}`;
                if(oldImgInput) oldImgInput.name = `items[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف البرامج إن وجدت
            const progRows = form.querySelectorAll('.job-prog-row-item');
            progRows.forEach((row, index) => {
                const titleInput = row.querySelector('input[name*="[title]"]');
                const btnTextInput = row.querySelector('input[name*="[btn_text]"]');
                const btnUrlInput = row.querySelector('input[name*="[btn_url]"]');
                const descInput = row.querySelector('textarea');
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');
                const darkInput = row.querySelector('input[type="checkbox"]');

                if(titleInput) titleInput.name = `programs[${index}][title]`;
                if(btnTextInput) btnTextInput.name = `programs[${index}][btn_text]`;
                if(btnUrlInput) btnUrlInput.name = `programs[${index}][btn_url]`;
                if(descInput) descInput.name = `programs[${index}][desc]`;
                if(fileInput) fileInput.name = `prog_img_${index}`;
                if(oldImgInput) oldImgInput.name = `programs[${index}][old_img]`;
                if(darkInput) darkInput.name = `programs[${index}][is_dark]`;
            });

            // إعادة ترقيم صفوف الخطوات إن وجدت
            const stepRows = form.querySelectorAll('.job-step-row-item');
            stepRows.forEach((row, index) => {
                const titleInput = row.querySelector('input[name*="[title]"]');
                const subInput = row.querySelector('input[name*="[subtitle]"]');
                const orderInput = row.querySelector('input[name*="[order]"]');
                const descInput = row.querySelector('input[name*="[desc]"]');
                const fileInput = row.querySelector('input[type="file"]');
                const oldIconInput = row.querySelector('input[name*="[old_icon]"]');

                if(titleInput) titleInput.name = `steps[${index}][title]`;
                if(subInput) subInput.name = `steps[${index}][subtitle]`;
                if(orderInput) orderInput.name = `steps[${index}][order]`;
                if(descInput) descInput.name = `steps[${index}][desc]`;
                if(fileInput) fileInput.name = `step_icon_${index}`;
                if(oldIconInput) oldIconInput.name = `steps[${index}][old_icon]`;
            });

            // إعادة ترقيم صفوف الخدمات إن وجدت
            const srvRows = form.querySelectorAll('.job-srv-row-item');
            srvRows.forEach((row, index) => {
                const titleInput = row.querySelector('input[name*="[title]"]');
                const urlInput = row.querySelector('input[name*="[url]"]');
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                if(titleInput) titleInput.name = `services[${index}][title]`;
                if(urlInput) urlInput.name = `services[${index}][url]`;
                if(fileInput) fileInput.name = `srv_img_${index}`;
                if(oldImgInput) oldImgInput.name = `services[${index}][old_img]`;
            });

            const formData = new FormData(this);
            
            // جلب الـ CSRF Token بأمان تام
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? ''); ?>';
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'تم حفظ التغييرات بنجاح');
                    location.reload();
                } else {
                    console.error('Server Response Error:', data);
                    alert('خطأ: ' + (data.error || data.message || 'فشل الحفظ'));
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالخادم، افتح الـ Console للمزيد من التفاصيل.');
            });
        });
    });
</script>


