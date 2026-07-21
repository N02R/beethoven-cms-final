<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$arrival = $data['arrival_page'] ?? [];
$path_prefix = $path_prefix ?? '../';
?>

<!-- 1. Arrival Hero Modal (تعديل صورة الهيدر) -->
<div class="modal fade custom-modal" id="arrivalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيدر الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalHeroForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_hero">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex justify-content-between">
                            <span>الصورة الحالية</span>
                            <?php if (!empty($arrival['hero_img'])): ?>
                                <span class="badge bg-light text-dark border">موجودة</span>
                            <?php endif; ?>
                        </label>
                        <?php if (!empty($arrival['hero_img'])): ?>
                            <div class="mb-2 p-2 border rounded bg-light text-center">
                                <img src="<?php echo $path_prefix . htmlspecialchars($arrival['hero_img']); ?>" style="max-height: 100px; object-fit: contain;" alt="Arrival Hero">
                                <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($arrival['hero_img']); ?></div>
                            </div>
                        <?php endif; ?>
                        <label class="form-label fw-bold">اختر صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival['hero_img'] ?? ''); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Arrival Content Modal (تعديل المحتوى، النصائح، والملاحظات) -->
<div class="modal fade custom-modal" id="arrivalContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text text-primary"></i> تعديل المحتوى والإرشادات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalContentForm">
                    <input type="hidden" name="action" value="update_arrival_content">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($arrival['main_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف الرئيسي</label>
                        <textarea class="form-control" name="main_desc" rows="4"><?php echo htmlspecialchars($arrival['main_desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">عنوان قسم النصائح</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($arrival['advice_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">وصف قسم النصائح</label>
                            <input type="text" class="form-control" name="advice_desc" value="<?php echo htmlspecialchars($arrival['advice_desc'] ?? ''); ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- إدارة النصائح (Tips) -->
                    <h6 class="text-primary fw-bold mb-3">قائمة النصائح والإرشادات قبل السفر</h6>
                    <div id="arrivalTipsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($arrival['tips'] ?? []) as $index => $tip): ?>
                            <div class="input-group" id="tip_row_<?php echo $index; ?>">
                                <input type="text" class="form-control form-control-sm" name="tips[]" value="<?php echo htmlspecialchars($tip); ?>">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow('tip_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-4" onclick="addArrivalTipRow()">+ إضافة نصيحة جديدة</button>

                    <hr class="my-2">

                    <!-- إدارة الملاحظات والهامش (Notes) -->
                    <h6 class="text-primary fw-bold mb-3">قائمة الملاحظات الهامة</h6>
                    <div id="arrivalNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($arrival['notes'] ?? []) as $index => $note): ?>
                            <div class="input-group" id="note_row_<?php echo $index; ?>">
                                <textarea class="form-control form-control-sm" name="notes[]" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow('note_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addArrivalNoteRow()">+ إضافة ملاحظة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalContentForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Rows & AJAX Script Engine -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function addArrivalTipRow() {
        const container = document.getElementById('arrivalTipsContainer');
        const div = document.createElement('div');
        div.className = 'input-group';
        div.id = 'tip_row_' + Date.now();
        div.innerHTML = `
            <input type="text" class="form-control form-control-sm" name="tips[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow('${div.id}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    function addArrivalNoteRow() {
        const container = document.getElementById('arrivalNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group';
        div.id = 'note_row_' + Date.now();
        div.innerHTML = `
            <textarea class="form-control form-control-sm" name="notes[]" rows="2" placeholder="اكتب الملاحظة الهامة هنا..."></textarea>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow('${div.id}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
    }

    document.querySelectorAll('#arrivalHeroModal form, #arrivalContentModal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // بما أننا داخل مجلد فرعي edu-services، نضبط مسار الـ API ليعود للخارج نحو مجلد admin
            fetch('../admin/api/save_config.php', {
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
