<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$check_data = $data['check_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="checkBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_check_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($check_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($check_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="checkHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_check_hero">
                    <?php if (!empty($check_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($check_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($check_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="checkMainContentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkMainContentForm" method="POST">
                    <input type="hidden" name="action" value="update_check_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($check_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($check_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkMainContentForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Important Notes Modal -->
<div class="modal fade custom-modal" id="checkNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-star text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_check_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملاحظات</label>
                        <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($check_data['note_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="checkNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($check_data['notes'])): ?>
                            <?php foreach ($check_data['notes'] as $index => $note): ?>
                                <div class="input-group note-item" id="check_note_<?php echo $index; ?>">
                                    <textarea class="form-control" name="notes[]" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeCheckRow('check_note_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addCheckNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Links & Conditions Modal -->
<div class="modal fade custom-modal" id="checkLinksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-link-45deg text-primary"></i> تعديل الروابط والشروط والنتيجة النهائية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkLinksForm" method="POST">
                    <input type="hidden" name="action" value="update_check_links">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">مقدمة الروابط</label>
                        <textarea class="form-control" name="links_intro" rows="2"><?php echo htmlspecialchars($check_data['links_intro'] ?? ''); ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط Anabin</label>
                            <input type="text" class="form-control" name="anabin_url" value="<?php echo htmlspecialchars($check_data['anabin_url'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط Uni-Assist</label>
                            <input type="text" class="form-control" name="uniassist_url" value="<?php echo htmlspecialchars($check_data['uniassist_url'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">مقدمة التواصل مع الجامعة</label>
                        <textarea class="form-control" name="uni_contact_intro" rows="2"><?php echo htmlspecialchars($check_data['uni_contact_intro'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الشرط الأول</label>
                        <input type="text" class="form-control" name="condition_1" value="<?php echo htmlspecialchars($check_data['condition_1'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الشرط الثاني</label>
                        <input type="text" class="form-control" name="condition_2" value="<?php echo htmlspecialchars($check_data['condition_2'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الفقرة الختامية (تدعم HTML و span للتلوين)</label>
                        <textarea class="form-control" name="conclusion_text" rows="3"><?php echo htmlspecialchars($check_data['conclusion_text'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeCheckRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let checkNoteIndex = <?php echo count($check_data['notes'] ?? []); ?>;
    function addCheckNoteRow() {
        const container = document.getElementById('checkNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item';
        div.id = 'check_note_' + checkNoteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="notes[]" rows="2" placeholder="اكتب الملاحظة هنا..."></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeCheckRow('check_note_${checkNoteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        checkNoteIndex++;
    }

    // ربط كافة نماذج صفحة check عبر AJAX
    document.querySelectorAll('#checkBreadcrumbForm, #checkHeroForm, #checkMainContentForm, #checkNotesForm, #checkLinksForm').forEach(form => {
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
