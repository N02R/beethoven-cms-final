<?php
/**
 * نافذة مودلز تعديل قسم الدراسة في ألمانيا - Admin Education Modals
 */
?>

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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="edu_hero_title" value="<?php echo htmlspecialchars($edu_hero['title'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">الوصف</label>
                            <textarea class="form-control" name="edu_hero_desc" rows="4"><?php echo htmlspecialchars($edu_hero['desc'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="edu_hero_btn_text" value="<?php echo htmlspecialchars($edu_hero['btn_text'] ?? 'ابدأ الآن'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="edu_hero_btn_url" value="<?php echo htmlspecialchars($edu_hero['btn_url'] ?? '#'); ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold d-flex justify-content-between">
                                <span>الصورة الرئيسية الحالية</span>
                                <?php if (!empty($edu_hero['img'])): ?>
                                    <span class="badge bg-light text-dark border">موجودة</span>
                                <?php endif; ?>
                            </label>
                            <?php if (!empty($edu_hero['img'])): ?>
                                <div class="mb-2 p-2 border rounded bg-light text-center">
                                    <span class="d-block small text-muted mb-1">الصورة الحالية:</span>
                                    <img src="<?php echo htmlspecialchars(get_image_url($edu_hero['img'])); ?>" style="max-height: 80px; object-fit: contain;" alt="Hero Preview">
                                    <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($edu_hero['img']); ?></div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="edu_hero_img" accept="image/*">
                            <input type="hidden" name="old_edu_hero_img" value="<?php echo htmlspecialchars($edu_hero['img'] ?? ''); ?>">
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="edu_why_title" value="<?php echo htmlspecialchars($edu_why_title ?? ''); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="edu_why_desc" rows="2"><?php echo htmlspecialchars($edu_why_desc ?? ''); ?></textarea>
                    </div>

                    <div id="eduWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_why_items ?? []) as $index => $item): ?>
                            <div class="card p-3 border-0 edu-why-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="why_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <!-- العنوان -->
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">العنوان</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_why[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" placeholder="العنوان">
                                    </div>
                                    <!-- الوصف -->
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">الوصف</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_why[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? ''); ?>" placeholder="الوصف">
                                    </div>

                                    <!-- رفع الصورة وزر الحذف بنفس هيكل الـ Timeline -->
                                    <div class="col-md-11">
                                        <label class="small text-muted fw-bold mb-1">الصورة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img'])); ?>" style="width: 28px; height: 28px; object-fit: contain;" alt="icon">
                                                    <span class="small text-muted text-truncate" style="font-size: 11px; max-width: 120px;"><?php echo basename($item['img']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="edu_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="edu_why[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('why_row_<?php echo $index; ?>')" title="حذف السبب">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addEduWhyRow()">
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="edu_services_title" value="<?php echo htmlspecialchars($edu_services_title ?? ''); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="edu_services_desc" rows="2"><?php echo htmlspecialchars($edu_services_desc ?? ''); ?></textarea>
                    </div>

                    <div id="eduServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($edu_services_items ?? []) as $index => $item): ?>
                            <div class="card p-3 border-0 edu-service-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="edu_srv_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">اسم الخدمة</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" placeholder="اسم الخدمة">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">رابط الخدمة</label>
                                        <input type="text" class="form-control form-control-sm" name="edu_services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" placeholder="الرابط">
                                    </div>
                                    <div class="col-md-11">
                                        <label class="small text-muted fw-bold mb-1">صورة الخلفية الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($item['img'])); ?>" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;" alt="icon">
                                                    <span class="small text-muted text-truncate" style="font-size: 11px; max-width: 150px;"><?php echo basename($item['img']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="edu_service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="edu_services[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('edu_srv_row_<?php echo $index; ?>')" title="حذف الخدمة">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addEduServiceRow()">
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
​// 2. دالة إظهار التنبيهات الاحترافية
function showNotification(message, type = 'success') {
const existingAlert = document.getElementById('customNotificationAlert');
if (existingAlert) existingAlert.remove();
​let bgClass = 'alert-success';
let icon = 'bi-check-circle-fill';
let title = 'تم بنجاح!';
​if (type === 'danger') {
bgClass = 'alert-danger';
icon = 'bi-x-circle-fill';
title = 'عذراً، حدث خطأ!';
} else if (type === 'warning') {
bgClass = 'alert-warning';
icon = 'bi-exclamation-triangle-fill';
title = 'تنبيه هام';
}
​const alertDiv = document.createElement('div');
alertDiv.id = 'customNotificationAlert';
alertDiv.className = alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed;
alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; border-radius: 12px; border: none;';
​alertDiv.innerHTML = <div class="d-flex align-items-center gap-2"> <i class="bi ${icon} fs-4"></i> <div> <strong>${title}</strong> <div class="small">${message}</div> </div> <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button> </div>;
​document.body.appendChild(alertDiv);
​setTimeout(() => {
if (alertDiv) {
alertDiv.classList.remove('show');
setTimeout(() => alertDiv.remove(), 300);
}
}, 4000);
}
​// 3. دالة إضافة صف جديد لـ "لماذا الدراسة"
function addEduWhyRow() {
const container = document.getElementById('eduWhyContainer');
if (!container) return;
const eduWhyCount = container.querySelectorAll('.edu-why-row-item').length;
const div = document.createElement('div');
div.className = 'card p-3 border-0 edu-why-row-item mb-2';
div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
div.id = 'why_row_' + Date.now() + '_' + eduWhyCount;
div.innerHTML =  <div class="row g-3 align-items-center"> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">العنوان</label> <input type="text" class="form-control form-control-sm" name="edu_why[${eduWhyCount}][title]" placeholder="العنوان"> </div> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">الوصف</label> <input type="text" class="form-control form-control-sm" name="edu_why[${eduWhyCount}][desc]" placeholder="الوصف"> </div> <div class="col-md-11"> <label class="small text-muted fw-bold mb-1">الصورة الجديدة</label> <input type="file" class="form-control form-control-sm" name="edu_why_img_${eduWhyCount}" accept="image/*"> <input type="hidden" name="edu_why[${eduWhyCount}][old_img]" value=""> </div> <div class="col-md-1 text-end pt-3"> <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف السبب"> <i class="bi bi-trash"></i> </button> </div> </div>;
container.appendChild(div);
}
​// 4. دالة إضافة صف جديد لـ "خطوات الرحلة" مع قيد الـ 6 عناصر وإظهار تنبيه
function addEduStepRow() {
const container = document.getElementById('eduTimelineContainer');
if (!container) return;
​const currentRows = container.querySelectorAll('.edu-timeline-row-item').length;
​// التحقق إذا وصل العدد إلى 6 عناصر أو أكثر
if (currentRows >= 6) {
showNotification('عذراً، لا يمكن إضافة أكثر من 6 عناصر في خط الزمن (Timeline).', 'warning');
return;
}
​const eduStepCount = currentRows;
const div = document.createElement('div');
div.className = 'card p-3 border-0 edu-timeline-row-item mb-2';
div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
div.id = 'step_row_' + Date.now() + '_' + eduStepCount;
div.innerHTML =  <div class="row g-3 align-items-center"> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">اسم الخطوة</label> <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][title]" placeholder="اسم الخطوة"> </div> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">العنوان الفرعي</label> <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][subtitle]" placeholder="العنوان الفرعي"> </div> <div class="col-md-12"> <label class="small text-muted fw-bold mb-1">التفاصيل</label> <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][desc]" placeholder="التفاصيل"> </div> <div class="col-md-2"> <label class="small text-muted fw-bold mb-1">الترتيب</label> <input type="number" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][order]" value="${eduStepCount}" placeholder="الترتيب"> </div> <div class="col-md-9"> <label class="small text-muted fw-bold mb-1">الأيقونة الجديدة</label> <input type="file" class="form-control form-control-sm" name="edu_timeline_icon_${eduStepCount}" accept="image/*"> <input type="hidden" name="edu_timeline[${eduStepCount}][old_icon]" value=""> </div> <div class="col-md-1 text-end pt-3"> <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف الخطوة"> <i class="bi bi-trash"></i> </button> </div> </div>;
container.appendChild(div);
}
​// 5. دالة إضافة صف جديد لـ "خدمات التعليم"
function addEduServiceRow() {
const container = document.getElementById('eduServicesContainer');
if (!container) return;
const eduSrvCount = container.querySelectorAll('.edu-service-row-item').length;
const div = document.createElement('div');
div.className = 'card p-3 border-0 edu-service-row-item mb-2';
div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
div.id = 'edu_srv_row_' + Date.now() + '_' + eduSrvCount;
div.innerHTML =  <div class="row g-3 align-items-center"> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">اسم الخدمة</label> <input type="text" class="form-control form-control-sm" name="edu_services[${eduSrvCount}][title]" placeholder="اسم الخدمة"> </div> <div class="col-md-6"> <label class="small text-muted fw-bold mb-1">رابط الخدمة</label> <input type="text" class="form-control form-control-sm" name="edu_services[${eduSrvCount}][url]" placeholder="الرابط"> </div> <div class="col-md-11"> <label class="small text-muted fw-bold mb-1">صورة الخلفية الجديدة</label> <input type="file" class="form-control form-control-sm" name="edu_service_img_${eduSrvCount}" accept="image/*"> <input type="hidden" name="edu_services[${eduSrvCount}][old_img]" value=""> </div> <div class="col-md-1 text-end pt-3"> <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف الخدمة"> <i class="bi bi-trash"></i> </button> </div> </div>;
container.appendChild(div);
}
​// 6. معالج الحفظ وإعادة الترقيم التلقائي عند الضغط على حفظ في أي نموذج
document.addEventListener('DOMContentLoaded', function() {
document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
form.addEventListener('submit', function(e) {
e.preventDefault();
​// إعادة ترقيم "لماذا الدراسة"
const whyRows = form.querySelectorAll('.edu-why-row-item');
whyRows.forEach((row, index) => {
const titleInput = row.querySelector('input[name*="[title]"]');
const descInput = row.querySelector('input[name*="[desc]"]');
const fileInput = row.querySelector('input[type="file"]');
const oldImgInput = row.querySelector('input[name*="[old_img]"]');
​if (titleInput) titleInput.name = edu_why[${index}][title];
if (descInput) descInput.name = edu_why[${index}][desc];
if (fileInput) fileInput.name = edu_why_img_${index};
if (oldImgInput) oldImgInput.name = edu_why[${index}][old_img];
});
​// إعادة ترقيم "خطوات الرحلة"
const stepRows = form.querySelectorAll('.edu-timeline-row-item');
stepRows.forEach((row, index) => {
const titleInput = row.querySelector('input[name*="[title]"]');
const subtitleInput = row.querySelector('input[name*="[subtitle]"]');
const orderInput = row.querySelector('input[name*="[order]"]');
const descInput = row.querySelector('input[name*="[desc]"]');
const fileInput = row.querySelector('input[type="file"]');
const oldIconInput = row.querySelector('input[name*="[old_icon]"]');
​if (titleInput) titleInput.name = edu_timeline[${index}][title];
if (subtitleInput) subtitleInput.name = edu_timeline[${index}][subtitle];
if (orderInput) orderInput.name = edu_timeline[${index}][order];
if (descInput) descInput.name = edu_timeline[${index}][desc];
if (fileInput) fileInput.name = edu_timeline_icon_${index};
if (oldIconInput) oldIconInput.name = edu_timeline[${index}][old_icon];
});
​// إعادة ترقيم "خدمات التعليم"
const srvRows = form.querySelectorAll('.edu-service-row-item');
srvRows.forEach((row, index) => {
const titleInput = row.querySelector('input[name*="[title]"]');
const urlInput = row.querySelector('input[name*="[url]"]');
const fileInput = row.querySelector('input[type="file"]');
const oldImgInput = row.querySelector('input[name*="[old_img]"]');
​if (titleInput) titleInput.name = edu_services[${index}][title];
if (urlInput) urlInput.name = edu_services[${index}][url];
if (fileInput) fileInput.name = edu_service_img_${index};
if (oldImgInput) oldImgInput.name = edu_services[${index}][old_img];
});
​const formData = new FormData(this);
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
if (csrfToken && !formData.has('csrf_token')) {
formData.append('csrf_token', csrfToken);
}
​fetch('index.php?url=admin/settings/save', {
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





