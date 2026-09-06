<!-- 1. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="guideBlog2HeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideBlog2HeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($guide_blog2_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($guide_blog2_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo ($path_prefix ?? '') . htmlspecialchars($guide_blog2_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
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
                <button type="submit" form="guideBlog2HeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="guideBlog2MainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="guideBlog2MainForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_blog2_main">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($guide_blog2_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_blog2_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBlog2MainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Why Study Edit Modal (Content Sections) -->
<div class="modal fade custom-modal" id="guideBlog2WhyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-patch-question text-primary"></i> تعديل قسم لماذا التخصصات التقنية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideBlog2WhyForm" class="admin-settings-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_why">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="why_title" value="<?php echo htmlspecialchars($guide_blog2_data['why_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="why_subtitle" rows="2" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_blog2_data['why_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideBlog2WhyContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_blog2_data['content_sections']) && is_array($guide_blog2_data['content_sections'])): ?>
                            <?php foreach ($guide_blog2_data['content_sections'] as $i => $section): ?>
                                <div class="p-3 shadow-sm edu-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="blog2_whystudy_row_<?php echo $i; ?>">
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">العنوان</label>
                                            <input type="text" class="form-control edu-why-title" name="content_sections[<?php echo $i; ?>][heading]" value="<?php echo htmlspecialchars($section['heading'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان الكارت">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                                            <input type="text" class="form-control edu-why-desc" name="content_sections[<?php echo $i; ?>][body]" value="<?php echo htmlspecialchars($section['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="وصف الكارت...">
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
                                                <input type="file" class="form-control edu-why-file" name="content_sections_img_<?php echo $i; ?>" accept="image/*">
                                            </div>
                                        </div>

                                        <input type="hidden" class="edu-why-old-img" name="content_sections[<?php echo $i; ?>][icon]" value="<?php echo htmlspecialchars($section['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                        <div class="col-1 text-center pb-1">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('blog2_whystudy_row_<?php echo $i; ?>')" title="حذف الكارت"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addBlog2WhyStudyRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة كارت جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBlog2WhyForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Services Check Edit Modal -->
<div class="modal fade custom-modal" id="guideBlog2ServicesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-square text-primary"></i> تعديل الخدمات المقدمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideBlog2ServicesForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_blog2_services">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="form-label fw-semibold small text-secondary">عنوان قسم الخدمات</label>
                        <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars($guide_blog2_data['services_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <h6 class="text-primary fw-bold mb-3 small">قائمة الخدمات (5 خدمات)</h6>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">الخدمة رقم <?php echo $i; ?></label>
                                <input type="text" class="form-control" name="service_<?php echo $i; ?>" value="<?php echo htmlspecialchars($guide_blog2_data["service_$i"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        <?php endfor; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBlog2ServicesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Timeline Edit Modal -->
<div class="modal fade custom-modal" id="guideBlog2TimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-clock-history text-primary"></i> تعديل خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideBlog2TimelineForm" class="admin-settings-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide_blog2_timeline">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($guide_blog2_data['timeline_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="timeline_desc" rows="2" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($guide_blog2_data['timeline_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="guideBlog2TimelineContainer" class="d-flex flex-column gap-3">
                        <?php if (!empty($guide_blog2_data['timeline_steps']) && is_array($guide_blog2_data['timeline_steps'])): ?>
                            <?php foreach ($guide_blog2_data['timeline_steps'] as $i => $step): ?>
                                <div class="p-3 shadow-sm edu-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="blog2_timeline_row_<?php echo $i; ?>">
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                                            <input type="text" class="form-control edu-step-title" name="timeline_steps[<?php echo $i; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="اسم الخطوة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">التفاصيل المختصرة</label>
                                            <input type="text" class="form-control edu-step-desc" name="timeline_steps[<?php echo $i; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="التفاصيل">
                                        </div>
                                    </div>

                                    <div class="row g-2 align-items-end">
                                        <div class="col-11">
                                            <input type="hidden" class="edu-step-old-icon" name="timeline_steps[<?php echo $i; ?>][dot_class]" value="<?php echo htmlspecialchars($step['dot_class'] ?? 'bg-blue', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-1 text-center pb-1">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('blog2_timeline_row_<?php echo $i; ?>')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addBlog2TimelineRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خطوة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBlog2TimelineForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Tips & Guarantees Edit Modal -->
<div class="modal fade custom-modal" id="guideBlog2TipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-lightbulb text-primary"></i> تعديل قسم النصائح والضمانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="guideBlog2TipsForm" method="POST">
                    <input type="hidden" name="action" value="update_guide_blog2_tips">
                    
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                        <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($guide_blog2_data['tips_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="text-primary fw-bold mb-3 small"><i class="bi bi-<?php echo $i; ?>-circle"></i> النصيحة رقم <?php echo $i; ?></h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">النص البولد (المميز)</label>
                                <input type="text" class="form-control" name="tip_<?php echo $i; ?>_bold" value="<?php echo htmlspecialchars($guide_blog2_data["tip_{$i}_bold"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold small text-secondary">باقي نص النصيحة</label>
                                <input type="text" class="form-control" name="tip_<?php echo $i; ?>_text" value="<?php echo htmlspecialchars($guide_blog2_data["tip_{$i}_text"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    <?php endfor; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideBlog2TipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine for Blog 2 Modals -->
<script>
    let blog2WhyCounter = <?php echo count($guide_blog2_data['content_sections'] ?? []); ?>;
    function addBlog2WhyStudyRow() {
        const container = document.getElementById('guideBlog2WhyContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-why-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'blog2_whystudy_row_' + blog2WhyCounter;
        div.id = rowId;
        
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">العنوان</label>
                    <input type="text" class="form-control edu-why-title" name="content_sections[${blog2WhyCounter}][heading]" placeholder="عنوان الكارت">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">الوصف المختصر</label>
                    <input type="text" class="form-control edu-why-desc" name="content_sections[${blog2WhyCounter}][body]" placeholder="وصف الكارت...">
                </div>
            </div>

            <div class="row g-2 align-items-end">
                <div class="col-11">
                    <label class="form-label fw-semibold small text-secondary">الأيقونة / الصورة</label>
                    <input type="file" class="form-control edu-why-file" name="content_sections_img_${blog2WhyCounter}" accept="image/*">
                </div>
                <input type="hidden" class="edu-why-old-img" name="content_sections[${blog2WhyCounter}][icon]" value="">
                <div class="col-1 text-center pb-1">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف الكارت"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
        blog2WhyCounter++;
    }

    let blog2TimelineCounter = <?php echo count($guide_blog2_data['timeline_steps'] ?? []); ?>;
    function addBlog2TimelineRow() {
        const container = document.getElementById('guideBlog2TimelineContainer');
        if (!container) return;
        
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm edu-timeline-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = 'blog2_timeline_row_' + blog2TimelineCounter;
        div.id = rowId;
        
        div.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">اسم الخطوة</label>
                    <input type="text" class="form-control edu-step-title" name="timeline_steps[${blog2TimelineCounter}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">التفاصيل</label>
                    <input type="text" class="form-control edu-step-desc" name="timeline_steps[${blog2TimelineCounter}][desc]" placeholder="التفاصيل">
                </div>
            </div>
            <input type="hidden" name="timeline_steps[${blog2TimelineCounter}][dot_class]" value="bg-blue">
            <div class="text-end">
                <button type="button" class="btn-icon-trash" onclick="removeRow('${rowId}')" title="حذف الخطوة"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(div);
        blog2TimelineCounter++;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#guideBlog2HeroForm, #guideBlog2MainForm, #guideBlog2WhyForm, #guideBlog2ServicesForm, #guideBlog2TimelineForm, #guideBlog2TipsForm').forEach(form => {
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
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            
                            // إغلاق أي modal مفتوح حالياً بشكل برمجي لضمان السلاسة
                            const activeModal = bootstrap.Modal.getInstance(this.closest('.modal'));
                            if (activeModal) {
                                activeModal.hide();
                            }
                            
                            // تأخير بسيط جداً لضمان ظهور الإشعار قبل ريلود الصفحة
                            setTimeout(() => {
                                window.location.reload();
                            }, 800);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'فشل الحفظ'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                    }
                })
</script>