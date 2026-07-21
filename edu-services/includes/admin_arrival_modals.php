<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$arrival_data = $data['arrival_page'] ?? [];
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="arrivalBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة الحالي</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="arrivalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_hero">
                    <?php if (!empty($arrival_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($arrival_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
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

<!-- 3. Modal 1: تعديل العنوان والوصف الرئيسي فقط -->
<div class="modal fade custom-modal" id="arrivalMainTitleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalMainTitleForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_main_title">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($arrival_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($arrival_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalMainTitleForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal 2: تعديل النصائح والإرشادات (Tips) فقط -->
<div class="modal fade custom-modal" id="arrivalTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-square text-primary"></i> تعديل التوصيات والنصائح قبل السفر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_tips">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($arrival_data['advice_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الوصف الفرعي</label>
                            <input type="text" class="form-control" name="advice_desc" value="<?php echo htmlspecialchars($arrival_data['advice_desc'] ?? ''); ?>">
                        </div>
                    </div>
                    <label class="form-label fw-bold">قائمة النصائح (تعديل / حذف / إضافة)</label>
                    <div id="tipsRowsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($arrival_data['tips'])): ?>
                            <?php foreach ($arrival_data['tips'] as $index => $tip): ?>
                                <div class="input-group tip-item" id="tip_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips[]" value="<?php echo htmlspecialchars($tip); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeRow('tip_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addTipRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal 3: تعديل الملاحظات الهامة (Notes) فقط -->
<div class="modal fade custom-modal" id="arrivalNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل صندوق الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_arrival_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان صندوق الملاحظات</label>
                        <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($arrival_data['note_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / حذف / إضافة)</label>
                    <div id="notesRowsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($arrival_data['notes'])): ?>
                            <?php foreach ($arrival_data['notes'] as $index => $note): ?>
                                <div class="input-group note-item" id="note_row_<?php echo $index; ?>">
                                    <textarea class="form-control" name="notes[]" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeRow('note_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let tipIndex = <?php echo count($arrival_data['tips'] ?? []); ?>;
    function addTipRow() {
        const container = document.getElementById('tipsRowsContainer');
        const div = document.createElement('div');
        div.className = 'input-group tip-item';
        div.id = 'tip_row_' + tipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeRow('tip_row_${tipIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        tipIndex++;
    }

    let noteIndex = <?php echo count($arrival_data['notes'] ?? []); ?>;
    function addNoteRow() {
        const container = document.getElementById('notesRowsContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item';
        div.id = 'note_row_' + noteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="notes[]" rows="2" placeholder="اكتب الملاحظة هنا..."></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeRow('note_row_${noteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // ربط كافة النماذج عبر AJAX بملف المعالجة المركزي
    document.querySelectorAll('#arrivalBreadcrumbForm, #arrivalHeroForm, #arrivalMainTitleForm, #arrivalTipsForm, #arrivalNotesForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

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
