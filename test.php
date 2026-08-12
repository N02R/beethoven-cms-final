ادمج لي هذا السكربت المناسب     let eduStepCount = <?php echo count($edu_timeline_steps ?? []); ?>;

    function addEduStepRow() {
        const container = document.getElementById('eduTimelineContainer');
        const currentRows = container.querySelectorAll('.edu-step-row-item').length;
        
        // قيد الـ 6 عناصر
        if (currentRows >= 6) {
            if (typeof showNotification === 'function') {
                showNotification('عذراً، الحد الأقصى لخطوات التعليم هو 6 خطوات فقط.', 'warning');
            } else {
                alert('الحد الأقصى لخطوات التعليم هو 6 خطوات فقط.');
            }
            return;
        }

        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 edu-step-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'edu_step_row_' + eduStepCount;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">اسم الخطوة</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">العنوان الفرعي</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted mb-1">الترتيب</label>
                    <input type="number" class="form-control form-control-sm" name="steps[${eduStepCount}][order]" value="${currentRows}" placeholder="الترتيب">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">أيقونة الخطوة</label>
                    <input type="file" class="form-control form-control-sm" name="steps_icon_${eduStepCount}" accept="image/*">
                    <input type="hidden" name="steps[${eduStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeEduRow('edu_step_row_${eduStepCount}')"><i class="bi bi-trash"></i></button>
                </div>
                <div class="col-md-12 mt-2">
                    <label class="small text-muted mb-1">التفاصيل</label>
                    <input type="text" class="form-control form-control-sm" name="steps[${eduStepCount}][desc]" placeholder="التفاصيل">
                </div>
            </div>`;
        container.appendChild(div);
        eduStepCount++;

        // التحقق وتحديث حالة الزر فور الإضافة
        toggleEduAddButton();
    }

    function removeEduRow(id) {
        const el = document.getElementById(id);
        if (el) {
            el.remove();
            toggleEduAddButton(); // إعادة إظهار زر الإضافة إذا أصبح العدد أقل من 6
        }
    }

    function toggleEduAddButton() {
        const container = document.getElementById('eduTimelineContainer');
        const currentRows = container.querySelectorAll('.edu-step-row-item').length;
        const addBtn = document.getElementById('addEduBtn');
        
        if (addBtn) {
            if (currentRows >= 6) {
                addBtn.style.display = 'none'; // إخفاء الزر عند الوصول لـ 6
            } else {
                addBtn.style.display = 'inline-block'; // إظهار الزر إذا نقص عن 6
            }
        }
    }

    // الفحص التلقائي عند فتح الصفحة للتأكد من حالة الزر بحسب عدد العناصر المحفوظة مسبقاً
    document.addEventListener("DOMContentLoaded", function() {
        toggleEduAddButton();
    });
 داخل هذا السكربت 
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

    // 3. دالة إضافة صف جديد لـ "لماذا الدراسة"
    function addEduWhyRow() {
        const container = document.getElementById('eduWhyContainer');
        if (!container) return;
        const eduWhyCount = container.querySelectorAll('.edu-why-row-item').length;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 edu-why-row-item mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'why_row_' + Date.now() + '_' + eduWhyCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">العنوان</label>
                    <input type="text" class="form-control form-control-sm" name="edu_why[${eduWhyCount}][title]" placeholder="العنوان">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">الوصف</label>
                    <input type="text" class="form-control form-control-sm" name="edu_why[${eduWhyCount}][desc]" placeholder="الوصف">
                </div>
                <div class="col-md-11">
                    <label class="small text-muted fw-bold mb-1">الصورة الجديدة</label>
                    <input type="file" class="form-control form-control-sm" name="edu_why_img_${eduWhyCount}" accept="image/*">
                    <input type="hidden" name="edu_why[${eduWhyCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف السبب">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 4. دالة إضافة صف جديد لـ "خطوات الرحلة"
    function addEduStepRow() {
        const container = document.getElementById('eduTimelineContainer');
        if (!container) return;
        const eduStepCount = container.querySelectorAll('.edu-timeline-row-item').length;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 edu-timeline-row-item mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'step_row_' + Date.now() + '_' + eduStepCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">اسم الخطوة</label>
                    <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][title]" placeholder="اسم الخطوة">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">العنوان الفرعي</label>
                    <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][subtitle]" placeholder="العنوان الفرعي">
                </div>
                <div class="col-md-12">
                    <label class="small text-muted fw-bold mb-1">التفاصيل</label>
                    <input type="text" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][desc]" placeholder="التفاصيل">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted fw-bold mb-1">الترتيب</label>
                    <input type="number" class="form-control form-control-sm" name="edu_timeline[${eduStepCount}][order]" value="${eduStepCount}" placeholder="الترتيب">
                </div>
                <div class="col-md-9">
                    <label class="small text-muted fw-bold mb-1">الأيقونة الجديدة</label>
                    <input type="file" class="form-control form-control-sm" name="edu_timeline_icon_${eduStepCount}" accept="image/*">
                    <input type="hidden" name="edu_timeline[${eduStepCount}][old_icon]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف الخطوة">
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
        div.className = 'card p-3 border-0 edu-service-row-item mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'edu_srv_row_' + Date.now() + '_' + eduSrvCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">اسم الخدمة</label>
                    <input type="text" class="form-control form-control-sm" name="edu_services[${eduSrvCount}][title]" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-6">
                    <label class="small text-muted fw-bold mb-1">رابط الخدمة</label>
                    <input type="text" class="form-control form-control-sm" name="edu_services[${eduSrvCount}][url]" placeholder="الرابط">
                </div>
                <div class="col-md-11">
                    <label class="small text-muted fw-bold mb-1">صورة الخلفية الجديدة</label>
                    <input type="file" class="form-control form-control-sm" name="edu_service_img_${eduSrvCount}" accept="image/*">
                    <input type="hidden" name="edu_services[${eduSrvCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="حذف الخدمة">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // 6. معالج الحفظ وإعادة الترقيم التلقائي عند الضغط على حفظ في أي نموذج
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // إعادة ترقيم "لماذا الدراسة"
                const whyRows = form.querySelectorAll('.edu-why-row-item');
                whyRows.forEach((row, index) => {
                    const titleInput = row.querySelector('input[name*="[title]"]');
                    const descInput = row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                    if (titleInput) titleInput.name = `edu_why[${index}][title]`;
                    if (descInput) descInput.name = `edu_why[${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_why_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_why[${index}][old_img]`;
                });

                // إعادة ترقيم "خطوات الرحلة"
                const stepRows = form.querySelectorAll('.edu-timeline-row-item');
                stepRows.forEach((row, index) => {
                    const titleInput = row.querySelector('input[name*="[title]"]');
                    const subtitleInput = row.querySelector('input[name*="[subtitle]"]');
                    const orderInput = row.querySelector('input[name*="[order]"]');
                    const descInput = row.querySelector('input[name*="[desc]"]');
                    const fileInput = row.querySelector('input[type="file"]');
                    const oldIconInput = row.querySelector('input[name*="[old_icon]"]');

                    if (titleInput) titleInput.name = `edu_timeline[${index}][title]`;
                    if (subtitleInput) subtitleInput.name = `edu_timeline[${index}][subtitle]`;
                    if (orderInput) orderInput.name = `edu_timeline[${index}][order]`;
                    if (descInput) descInput.name = `edu_timeline[${index}][desc]`;
                    if (fileInput) fileInput.name = `edu_timeline_icon_${index}`;
                    if (oldIconInput) oldIconInput.name = `edu_timeline[${index}][old_icon]`;
                });

                // إعادة ترقيم "خدمات التعليم"
                const srvRows = form.querySelectorAll('.edu-service-row-item');
                srvRows.forEach((row, index) => {
                    const titleInput = row.querySelector('input[name*="[title]"]');
                    const urlInput = row.querySelector('input[name*="[url]"]');
                    const fileInput = row.querySelector('input[type="file"]');
                    const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                    if (titleInput) titleInput.name = `edu_services[${index}][title]`;
                    if (urlInput) urlInput.name = `edu_services[${index}][url]`;
                    if (fileInput) fileInput.name = `edu_service_img_${index}`;
                    if (oldImgInput) oldImgInput.name = `edu_services[${index}][old_img]`;
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
واعد ارساله ليركامل التحديث دون كسر اي منطق او تصميم او ستايل