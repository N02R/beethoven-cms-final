<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$edu_hero = $data['edu_hero'] ?? [];
$edu_why_title = $data['edu_why_title'] ?? 'لماذا الدراسة في ألمانيا؟';
$edu_why_desc = $data['edu_why_desc'] ?? '';
$edu_why_items = $data['edu_why_items'] ?? [];

$edu_timeline_title = $data['edu_timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS';
$edu_timeline_desc = $data['edu_timeline_desc'] ?? '';
$edu_timeline_steps = $data['edu_timeline_steps'] ?? [];

$edu_services_title = $data['edu_services_title'] ?? 'ماذا تقدم في بيتهوفن سيتي؟';
$edu_services_desc = $data['edu_services_desc'] ?? '';
$edu_services_items = $data['edu_services_items'] ?? [];
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
                <form id="eduHeroForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_hero">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($edu_hero['title'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">الوصف</label>
                            <textarea class="form-control" name="desc" rows="4"><?php echo htmlspecialchars($edu_hero['desc'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="btn_text" value="<?php echo htmlspecialchars($edu_hero['btn_text'] ?? 'ابدأ الآن'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="btn_url" value="<?php echo htmlspecialchars($edu_hero['btn_url'] ?? '#'); ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold d-flex justify-content-between">
                                <span>الصورة الرئيسية الحالية</span>
                                <?php if (!empty($edu_hero['img'])): ?>
                                    <span class="badge bg-light text-dark border">موجودة</span>
                                <?php endif; ?>
                            </label>
                            <?php if (!empty($edu_hero['img'])): ?>
                                <div class="mb-2 p-1 border rounded bg-light text-center">
                                    <img src="<?php echo htmlspecialchars($edu_hero['img']); ?>" style="max-height: 80px; object-fit: contain;" alt="Hero Preview">
                                    <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($edu_hero['img']); ?></div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                            <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($edu_hero['img'] ?? ''); ?>">
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
                <form id="eduWhyForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_why">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="why_title" value="<?php echo htmlspecialchars($edu_why_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="why_desc" rows="2"><?php echo htmlspecialchars($edu_why_desc); ?></textarea>
                    </div>

                    <div id="eduWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($edu_why_items as $index => $item): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="why_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted">العنوان</label>
                                        <input type="text" class="form-control form-control-sm" name="items[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" placeholder="العنوان">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">الوصف</label>
                                        <input type="text" class="form-control form-control-sm" name="items[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($item['desc'] ?? ''); ?>" placeholder="الوصف">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">الصورة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['img']); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="items[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('why_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
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

<!-- 3. Edu Timeline Modal (قسم خطوات الرحلة) -->
<div class="modal fade custom-modal" id="eduTimelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3 text-primary"></i> إدارة خطوات الرحلة (Timeline)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduTimelineForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_timeline">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="timeline_title" value="<?php echo htmlspecialchars($edu_timeline_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="timeline_desc" rows="2"><?php echo htmlspecialchars($edu_timeline_desc); ?></textarea>
                    </div>

                    <div id="eduTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($edu_timeline_steps as $index => $step): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="step_row_<?php echo $index; ?>">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="small text-muted">اسم الخطوة</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>" placeholder="اسم الخطوة">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">العنوان الفرعي</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($step['subtitle'] ?? ''); ?>" placeholder="العنوان الفرعي">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted">الترتيب</label>
                                        <input type="number" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][order]" value="<?php echo ($step['order'] ?? $index); ?>" placeholder="الترتيب">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">الأيقونة الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($step['icon'])): ?>
                                                <img src="<?php echo htmlspecialchars($step['icon']); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="step_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="steps[<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($step['icon'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('step_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="small text-muted">التفاصيل</label>
                                        <input type="text" class="form-control form-control-sm" name="steps[<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($step['desc'] ?? ''); ?>" placeholder="التفاصيل">
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
                <form id="eduServicesForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_services">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="services_title" value="<?php echo htmlspecialchars($edu_services_title); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="services_desc" rows="2"><?php echo htmlspecialchars($edu_services_desc); ?></textarea>
                    </div>

                    <div id="eduServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($edu_services_items as $index => $item): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="edu_srv_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted">اسم الخدمة</label>
                                        <input type="text" class="form-control form-control-sm" name="services[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" placeholder="اسم الخدمة">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">رابط الخدمة</label>
                                        <input type="text" class="form-control form-control-sm" name="services[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" placeholder="الرابط">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">صورة الخلفية الحالية / الجديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['img']); ?>" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm" name="srv_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" name="services[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($item['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('edu_srv_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
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

<!-- Dynamic Rows JS Engine -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let eduWhyCount = <?php echo count($edu_why_items); ?>;
    function addEduWhyRow() {
        const container = document.getElementById('eduWhyContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'why_row_' + eduWhyCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="items[${eduWhyCount}][title]" placeholder="العنوان"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="items[${eduWhyCount}][desc]" placeholder="الوصف"></div>
                <div class="col-md-4">
                    <input type="file" class="form-control form-control-sm" name="why_img_${eduWhyCount}" accept="image/*">
                    <input type="hidden" name="items[${eduWhyCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('why_row_${eduWhyCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        eduWhyCount++;
    }

    let eduStepCount = <?php echo count($edu_timeline_steps); ?>;
    function addEduStepRow() {
        const container = document.getElementById('eduTimelineContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'step_row_' + eduStepCount;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][title]" placeholder="اسم الخطوة"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][subtitle]" placeholder="العنوان الفرعي"></div>
                <div class="col-md-2"><input type="number" class="form-control form-control-sm" name="steps[${eduStepCount}][order]" value="${eduStepCount}" placeholder="الترتيب"></div>
                <div class="col-md-3">
                    <input type="file" class="form-control form-control-sm" name="step_icon_${eduStepCount}" accept="image/*">
                    <input type="hidden" name="steps[${eduStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('step_row_${eduStepCount}')"><i class="bi bi-trash"></i></button></div>
                <div class="col-md-12 mt-2"><input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][desc]" placeholder="التفاصيل"></div>
            </div>`;
        container.appendChild(div);
        eduStepCount++;
    }

    let eduSrvCount = <?php echo count($edu_services_items); ?>;
    function addEduServiceRow() {
        const container = document.getElementById('eduServicesContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'edu_srv_row_' + eduSrvCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="services[${eduSrvCount}][title]" placeholder="اسم الخدمة"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="services[${eduSrvCount}][url]" placeholder="الرابط"></div>
                <div class="col-md-4">
                    <input type="file" class="form-control form-control-sm" name="srv_img_${eduSrvCount}" accept="image/*">
                    <input type="hidden" name="services[${eduSrvCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('edu_srv_row_${eduSrvCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        eduSrvCount++;
    }

    document.querySelectorAll('#eduHeroModal form, #eduWhyModal form, #eduTimelineModal form, #eduServicesModal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('admin/api/save_config.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم الحفظ بنجاح');
                    location.reload();
                } else {
                    alert('خطأ: ' + (data.message || 'فشل الحفظ'));
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالسيرفر');
            });
        });
    });
</script>

