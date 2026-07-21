<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$arrival_data = $data['arrival_page'] ?? [];
?>

<!-- 1. Breadcrumb Edit Modal (تعديل مسار التنقل) -->
<div class="modal fade custom-modal" id="arrivalBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalBreadcrumbForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_breadcrumb">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة الحالي في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
                        <div class="form-text small text-muted">اكتب # إذا كانت الصفحة الحالية ولا تتطلب رابطاً تفاعلياً.</div>
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

<!-- 2. Hero Image Edit Modal (تعديل صورة الهيرو) -->
<div class="modal fade custom-modal" id="arrivalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل قسم الهيرو (الصورة الرئيسية)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_hero">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex justify-content-between">
                            <span>صورة الهيدر الحالية</span>
                            <?php if (!empty($arrival_data['hero_img'])): ?>
                                <span class="badge bg-light text-dark border">موجودة</span>
                            <?php endif; ?>
                        </label>
                        <?php if (!empty($arrival_data['hero_img'])): ?>
                            <div class="mb-3 p-2 border rounded bg-light text-center">
                                <img src="<?php echo $path_prefix . htmlspecialchars($arrival_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                                <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($arrival_data['hero_img']); ?></div>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>">
                        
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

<!-- 3. Content, Tips & Notes Modal (تعديل المحتوى، النصائح، والملاحظات) -->
<div class="modal fade custom-modal" id="arrivalContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text text-primary"></i> إدارة محتوى، نصائح، وملاحظات الصفحة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalContentForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_content">
                    
                    <!-- العنوان الرئيسي والوصف -->
                    <div class="card p-3 mb-4 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">العنوان الرئيسي والوصف التفصيلي</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($arrival_data['main_title'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($arrival_data['main_desc'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- قسم النصائح والإرشادات -->
                    <div class="card p-3 mb-4 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">قسم الإرشادات والتوصيات</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">عنوان التوصيات</label>
                                <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($arrival_data['advice_title'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">الوصف الفرعي للتوصيات</label>
                                <input type="text" class="form-control" name="advice_desc" value="<?php echo htmlspecialchars($arrival_data['advice_desc'] ?? ''); ?>">
                            </div>
                        </div>

                        <label class="form-label fw-bold">قائمة النصائح (تعديل / حذف / إضافة)</label>
                        <div id="tipsRowsContainer" class="d-flex flex-column gap-2 mb-3">
                            <?php foreach (($arrival_data['tips'] ?? []) as $index => $tip): ?>
                                <div class="input-group tip-item" id="tip_row_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips[]" value="<?php echo htmlspecialchars($tip); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeRow('tip_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addTipRow()">
                            <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                        </button>
                    </div>

                    <!-- الملاحظات الهامة -->
                    <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">الملاحظات الهامة</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">عنوان صندوق الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($arrival_data['note_title'] ?? ''); ?>">
                        </div>

                        <label class="form-label fw-bold">قائمة الملاحظات</label>
                        <div id="notesRowsContainer" class="d-flex flex-column gap-2 mb-3">
                            <?php foreach (($arrival_data['notes'] ?? []) as $index => $note): ?>
                                <div class="input-group note-item" id="note_row_<?php echo $index; ?>">
                                    <textarea class="form-control" name="notes[]" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeRow('note_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addNoteRow()">
                            <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalContentForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Rows & AJAX Engine (محرك الحذف، الإضافة، وإرسال البيانات) -->
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$arrival_data = $data['arrival_page'] ?? [];
?>

<!-- 1. Breadcrumb Edit Modal (تعديل مسار التنقل) -->
<div class="modal fade custom-modal" id="arrivalBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalBreadcrumbForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_breadcrumb">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة الحالي في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
                        <div class="form-text small text-muted">اكتب # إذا كانت الصفحة الحالية ولا تتطلب رابطاً تفاعلياً.</div>
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

<!-- 2. Hero Image Edit Modal (تعديل صورة الهيرو) -->
<div class="modal fade custom-modal" id="arrivalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل قسم الهيرو (الصورة الرئيسية)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_hero">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex justify-content-between">
                            <span>الصورة الرئيسية الحالية</span>
                            <?php if (!empty($arrival_data['hero_img'])): ?>
                                <span class="badge bg-light text-dark border">موجودة</span>
                            <?php endif; ?>
                        </label>
                        <?php if (!empty($arrival_data['hero_img'])): ?>
                            <div class="mb-3 p-2 border rounded bg-light text-center">
                                <img src="<?php echo $path_prefix . htmlspecialchars($arrival_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                                <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($arrival_data['hero_img']); ?></div>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>">
                        
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

<!-- 3. Content, Tips & Notes Modal (تعديل المحتوى، النصائح، والملاحظات مع عرض البيانات القديمة) -->
<div class="modal fade custom-modal" id="arrivalContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text text-primary"></i> إدارة محتوى، نصائح، وملاحظات الصفحة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="arrivalContentForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_arrival_content">
                    
                    <!-- العنوان الرئيسي والوصف -->
                    <div class="card p-3 mb-4 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">العنوان الرئيسي والوصف التفصيلي</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($arrival_data['main_title'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($arrival_data['main_desc'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- قسم النصائح والإرشادات -->
                    <div class="card p-3 mb-4 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">قسم الإرشادات والتوصيات</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">عنوان التوصيات</label>
                                <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($arrival_data['advice_title'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">الوصف الفرعي للتوصيات</label>
                                <input type="text" class="form-control" name="advice_desc" value="<?php echo htmlspecialchars($arrival_data['advice_desc'] ?? ''); ?>">
                            </div>
                        </div>

                        <label class="form-label fw-bold">قائمة النصائح الحالية (تعديل / حذف / إضافة)</label>
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
                    </div>

                    <!-- الملاحظات الهامة -->
                    <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                        <h6 class="text-primary fw-bold mb-3">الملاحظات الهامة</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">عنوان صندوق الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($arrival_data['note_title'] ?? ''); ?>">
                        </div>

                        <label class="form-label fw-bold">قائمة الملاحظات الحالية</label>
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="arrivalContentForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Rows & AJAX Engine -->
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

    // معالجة وإرسال النماذج الثلاثة عبر AJAX وبطريقة POST حصرياً
    document.querySelectorAll('#arrivalBreadcrumbForm, #arrivalHeroForm, #arrivalContentForm').forEach(form => {
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

