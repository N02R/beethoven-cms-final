<!-- 1. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="guideHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($guide_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($guide_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($guide_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="guideHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="guideMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideMainForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Why Study Edit Modal -->
<div class="modal fade custom-modal" id="guideWhyStudyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-patch-question text-primary"></i> تعديل قسم لماذا الدراسة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideWhyStudyForm" class="admin-settings-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_whystudy">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="why_study_title" value="<?php echo htmlspecialchars($guide_data['why_study_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="why_study_desc" rows="2" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideWhyStudyContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['content_sections']) && is_array($guide_data['content_sections'])): ?>
                            <?php foreach ($guide_data['content_sections'] as $i => $section): ?>
                                <div class="p-3 shadow-sm edu-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="whystudy_row_<?php echo $i; ?>">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">العنوان</label>
                                            <input type="text" class="form-control" name="content_sections[<?php echo $i; ?>][heading]" value="<?php echo htmlspecialchars($section['heading'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الكارت">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                                            <input type="text" class="form-control" name="content_sections[<?php echo $i; ?>][body]" value="<?php echo htmlspecialchars($section['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="وصف الكارت...">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-end">
                                        <div class="col-11">
                                            <label class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($section['icon'])): ?>
                                                    <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                        <img src="<?php echo htmlspecialchars(get_image_url($section['icon']), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="content_sections_img_<?php echo $i; ?>" accept="image/*">
                                            </div>
                                        </div>
                                        <input type="hidden" name="content_sections[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($section['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="col-1 text-center pb-1">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('whystudy_row_<?php echo $i; ?>')" title="حذف الكارت"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px;" onclick="addWhyStudyRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة كارت جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideWhyStudyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice / Services Modal (Blog Two specific) -->
<div class="modal fade custom-modal" id="guideAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل قسم الخدمات / النصائح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideAdviceForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_advice">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($guide_data['advice_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div id="guideAdviceContainer" class="d-flex flex-column gap-2">
                        <?php if (!empty($guide_data['advice_items']) && is_array($guide_data['advice_items'])): ?>
                            <?php foreach ($guide_data['advice_items'] as $j => $item): ?>
                                <div class="input-group mb-2 advice-row-item" id="advice_row_<?php echo $j; ?>">
                                    <input type="text" class="form-control" name="advice_items[]" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" placeholder="نص الخدمة أو النصيحة...">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeRow('advice_row_<?php echo $j; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-2 py-2" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 10px;" onclick="addAdviceRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة بند جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Timeline Edit Modal -->
<div class="modal fade custom-modal" id="guideTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-clock-history text-primary"></i> تعديل خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideTimelineForm" class="admin-settings-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_timeline">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_data['timeline_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideTimelineContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
                            <?php foreach ($guide_data['timeline_steps'] as $i => $step): ?>
                                <div class="p-3 shadow-sm edu-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="timeline_row_<?php echo $i; ?>">
                                    <div class="row g-2 mb-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخطوة">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-end">
                                        <div class="col-11">
                                            <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                                            <input type="text" class="form-control" name="timeline_steps[<?php echo $i; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="التفاصيل">
                                        </div>
                                        <div class="col-1 text-center pb-1">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('timeline_row_<?php echo $i; ?>')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px;" onclick="addTimelineRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideTimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Guarantees Modal (Blog Two specific) -->
<div class="modal fade custom-modal" id="guideNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-check text-primary"></i> تعديل الضمانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_guarantees">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="form-label fw-semibold small text-secondary">عنوان قسم الضمانات</label>
                        <input type="text" class="form-control" name="guarantee_title" value="<?php echo htmlspecialchars($guide_data['guarantee_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div id="guideGuaranteesContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_data['guarantees_list']) && is_array($guide_data['guarantees_list'])): ?>
                            <?php foreach ($guide_data['guarantees_list'] as $k => $gItem): ?>
                                <div class="p-3 shadow-sm guarantee-row-item" style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0;" id="guarantee_row_<?php echo $k; ?>">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">العنوان البارز (Bold)</label>
                                            <input type="text" class="form-control" name="guarantees_list[<?php echo $k; ?>][bold]" value="<?php echo htmlspecialchars($gItem['bold'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label fw-semibold small text-secondary">نص الضمان</label>
                                            <input type="text" class="form-control" name="guarantees_list[<?php echo $k; ?>][text]" value="<?php echo htmlspecialchars($gItem['text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-1 text-center pb-1">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guarantee_row_<?php echo $k; ?>')" title="حذف الضمان"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px;" onclick="addGuaranteeRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ضمان جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JS Engine for Blog Two -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = 'alert-success';
        let icon = 'bi-check-circle-fill';
        let title = 'تم بنجاح!';

        if (type === 'danger') { bgClass = 'alert-danger'; icon = 'bi-x-circle-fill'; title = 'عذراً، حدث خطأ!'; }
        else if (type === 'warning') { bgClass = 'alert-warning'; icon = 'bi-exclamation-triangle-fill'; title = 'تنبيه هام'; }

        const alertDiv = document.createElement('div');
        alertDiv.id = 'customNotificationAlert';
        alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
        alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; border-radius: 12px; border: none;';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon} fs-4"></i>
                <div><strong>${title}</strong><div class="small">${message}</div></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => { if (alertDiv) { alertDiv.classList.remove('show'); setTimeout(() => alertDiv.remove(), 300); } }, 4000);
    }

    let whyStudyCounter = <?php echo count($guide_data['content_sections'] ?? []); ?>;
    function addWhyStudyRow() {
        const container = document.getElementById('guideWhyStudyContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-why-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'whystudy_row_' + whyStudyCounter;
        div.id = rowId;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">العنوان</label>
                    <input type="text" class="form-control" name="content_sections[${whyStudyCounter}][heading]" placeholder="عنوان الكارت">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                    <input type="text" class="form-control" name="content_sections[${whyStudyCounter}][body]" placeholder="وصف الكارت...">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                    <input type="file" class="form-control" name="content_sections_img_${whyStudyCounter}" accept="image/*">
                </div>
                <input type="hidden" name="content_sections[${whyStudyCounter}][icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف الكارت"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
        whyStudyCounter++;
    }

    let adviceCounter = <?php echo count($guide_data['advice_items'] ?? []); ?>;
    function addAdviceRow() {
        const container = document.getElementById('guideAdviceContainer');
        if (!container) return;
        const rowId = 'advice_row_' + adviceCounter;
        const div = document.createElement('div');
        div.className = 'input-group mb-2 advice-row-item';
        div.id = rowId;
        div.innerHTML = `
            <input type="text" class="form-control" name="advice_items[]" placeholder="نص الخدمة أو النصيحة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeRow('${rowId}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        adviceCounter++;
    }

    let timelineCounter = <?php echo count($guide_data['timeline_steps'] ?? []); ?>;
    function addTimelineRow() {
        const container = document.getElementById('guideTimelineContainer');
        if (!container) return;
        const rowId = 'timeline_row_' + timelineCounter;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-timeline-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = rowId;
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                    <input type="text" class="form-control" name="timeline_steps[${timelineCounter}][title]" placeholder="اسم الخطوة">
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                    <input type="text" class="form-control" name="timeline_steps[${timelineCounter}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
        timelineCounter++;
    }

    let guaranteeCounter = <?php echo count($guide_data['guarantees_list'] ?? []); ?>;
    function addGuaranteeRow() {
        const container = document.getElementById('guideGuaranteesContainer');
        if (!container) return;
        const rowId = 'guarantee_row_' + guaranteeCounter;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm guarantee-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0;';
        div.id = rowId;
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-secondary">العنوان البارز (Bold)</label>
                    <input type="text" class="form-control" name="guarantees_list[${guaranteeCounter}][bold]" placeholder="عنوان بارز">
                </div>
                <div class="col-md-7">
                    <label class="form-label fw-semibold small text-secondary">نص الضمان</label>
                    <input type="text" class="form-control" name="guarantees_list[${guaranteeCounter}][text]" placeholder="نص الضمان...">
                </div>
                <div class="col-md-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف الضمان"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
        guaranteeCounter++;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#guideHeroForm, #guideMainForm, #guideWhyStudyForm, #guideAdviceForm, #guideTimelineForm, #guideNotesForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (csrfToken && !formData.has('csrf_token')) formData.append('csrf_token', csrfToken);

                // استخدام رابط مطلق يضمن الوصول لملف التوجيه الرئيسي في الـ public مباشرة دون أخطاء المسارات الفرعية
                const saveUrl = window.location.origin + '/admin/settings/save';
                
                // بديل احتياطي في حال كان المشروع يعمل داخل مجلد فرعي ضمن السيرفر المحلي:
                // يمكنك استبدال السطر أعلاه بـ: window.location.pathname.includes('/guide/') ? '../index.php?url=admin/settings/save' : 'index.php?url=admin/settings/save'

                fetch('index.php?url=admin/settings/save', {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
                    body: formData
                })
                .then(async response => {
                    const text = await response.text();
                    try {
                        const data = JSON.parse(text);
                        if (response.ok && data.success) {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || data.error || 'فشل الحفظ'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ من السيرفر (استجابة غير صالحة): ' + text.substring(0, 150), 'danger');
                    }
                })
                .catch(err => {
                    showNotification('حدث خطأ أثناء الاتصال بالسيرفر.', 'danger');
                });
            });
        });
    });
</script>

