انظر هل هذا <!-- 3. Edu Timeline Modal (قسم خطوات الرحلة) -->
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="edu_timeline_title" value="<?php echo htmlspecialchars($edu_timeline_title ?? ''); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="edu_timeline_desc" rows="2"><?php echo htmlspecialchars($edu_timeline_desc ?? ''); ?></textarea>
                    </div>

                    <div id="eduTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_timeline_steps ?? []) as $index => $step): ?>
                            <div class="card p-3 border-0 edu-timeline-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="step_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">اسم الخطوة</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_timeline[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">العنوان الفرعي</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_timeline[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? ''); ?>" placeholder="العنوان الفرعي">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="small text-muted fw-bold mb-1">التفاصيل</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_timeline[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? ''); ?>" placeholder="التفاصيل">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="small text-muted fw-bold mb-1">الترتيب</label>
                                        <input type="number" class="form-control form-control-sm" name="edu_timeline[<?php echo $index; ?>][order]" value="<?php echo ($step['order'] ?? $index); ?>" placeholder="الترتيب">
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
                                            <input type="file" class="form-control form-control-sm" name="edu_timeline_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="edu_timeline[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('step_row_<?php echo $index; ?>')" title="حذف الخطوة">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addEduStepRow()">
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