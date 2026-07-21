<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$cover_data = $data['coverletter_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="coverBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($cover_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($cover_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="coverHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_cover_hero">
                    <?php if (!empty($cover_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($cover_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($cover_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="coverMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverMainForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($cover_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($cover_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Points Modal -->
<div class="modal fade custom-modal" id="coverAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نقاط النصائح الواجب مراعاتها</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverAdviceForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_advice">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($cover_data['advice_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة النقاط (تعديل / إضافة / حذف)</label>
                    <div id="coverAdviceContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($cover_data['advice_points'])): ?>
                            <?php foreach ($cover_data['advice_points'] as $index => $point): ?>
                                <div class="input-group advice-item" id="cover_advice_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="advice_points[]" value="<?php echo htmlspecialchars($point); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeCoverRow('cover_advice_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addCoverAdviceRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Important Notes Modal -->
<div class="modal fade custom-modal" id="coverNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملاحظات</label>
                        <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($cover_data['note_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="coverNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($cover_data['notes'])): ?>
                            <?php foreach ($cover_data['notes'] as $index => $note): ?>
                                <div class="input-group note-item" id="cover_note_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes[]" value="<?php echo htmlspecialchars($note); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeCoverRow('cover_note_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addCoverNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Download Files Modal -->
<div class="modal fade custom-modal" id="coverDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> تعديل ملفات النماذج والروابط</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverDownloadForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_downloads">
                    
                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-file-pdf"></i> نموذج ملف الـ PDF</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">عنوان البطاقة</label>
                            <input type="text" class="form-control" name="pdf_title" value="<?php echo htmlspecialchars($cover_data['pdf_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">النوع الفرعي (Sub)</label>
                            <input type="text" class="form-control" name="pdf_sub" value="<?php echo htmlspecialchars($cover_data['pdf_sub'] ?? 'Example'); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">مسار ملف الـ PDF</label>
                            <input type="text" class="form-control" name="pdf_file" value="<?php echo htmlspecialchars($cover_data['pdf_file'] ?? 'downloads/cover-letter.pdf'); ?>">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-file-word"></i> نموذج ملف الـ Word</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">عنوان البطاقة</label>
                            <input type="text" class="form-control" name="word_title" value="<?php echo htmlspecialchars($cover_data['word_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">النوع الفرعي (Sub)</label>
                            <input type="text" class="form-control" name="word_sub" value="<?php echo htmlspecialchars($cover_data['word_sub'] ?? 'Example'); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">مسار ملف الـ Word</label>
                            <input type="text" class="form-control" name="word_file" value="<?php echo htmlspecialchars($cover_data['word_file'] ?? 'downloads/cover-letter.docx'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeCoverRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let adviceIndex = <?php echo count($cover_data['advice_points'] ?? []); ?>;
    function addCoverAdviceRow() {
        const container = document.getElementById('coverAdviceContainer');
        const div = document.createElement('div');
        div.className = 'input-group advice-item';
        div.id = 'cover_advice_' + adviceIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="advice_points[]" placeholder="اكتب النقطة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeCoverRow('cover_advice_${adviceIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        adviceIndex++;
    }

    let noteIndex = <?php echo count($cover_data['notes'] ?? []); ?>;
    function addCoverNoteRow() {
        const container = document.getElementById('coverNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item';
        div.id = 'cover_note_' + noteIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="notes[]" placeholder="اكتب الملاحظة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeCoverRow('cover_note_${noteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // ربط كافة النماذج عبر AJAX
    document.querySelectorAll('#coverBreadcrumbForm, #coverHeroForm, #coverMainForm, #coverAdviceForm, #coverNotesForm, #coverDownloadForm').forEach(form => {
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
