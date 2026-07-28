<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$visa_data = $data['visa_requirements_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="visaBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($visa_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($visa_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="visaHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_visa_hero">
                    <?php if (!empty($visa_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($visa_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($visa_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تنسيق التموضع (background-position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($visa_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="visaMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaMainForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($visa_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التعريفي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($visa_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Notes Modal (ديناميكي لإضافة وتعديل وحذف الملاحظات) -->
<div class="modal fade custom-modal" id="visaNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-circle text-primary"></i> تعديل وإدارة الملاحظات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملاحظات العام</label>
                        <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($visa_data['notes_section']['title'] ?? 'ملاحظة !!'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="visaNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($visa_data['notes_section']['notes_list'])): ?>
                            <?php foreach ($visa_data['notes_section']['notes_list'] as $index => $note): ?>
                                <div class="input-group note-item" id="visa_note_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes_list[]" value="<?php echo htmlspecialchars($note); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeVisaRow('visa_note_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addVisaNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Files Modal (إدارة الملفات مع اختيار النوع PDF/Word وحذف وإضافة عناصر) -->
<div class="modal fade custom-modal" id="visaDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة ملفات ونماذج متطلبات التأشيرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="visaDownloadForm" method="POST">
                    <input type="hidden" name="action" value="update_visa_downloads">
                    <label class="form-label fw-bold">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    <div id="visaDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($visa_data['download_items'])): ?>
                            <?php foreach ($visa_data['download_items'] as $index => $item): ?>
                                <div class="p-3 border rounded bg-light position-relative download-item-box" id="visa_download_<?php echo $index; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">نوع الملف</label>
                                            <select class="form-select form-select-sm" name="download_types[]">
                                                <option value="pdf" <?php echo (strtolower($item['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($item['type'] ?? '') === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold">عنوان البطاقة</label>
                                            <input type="text" class="form-control form-control-sm" name="download_titles[]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">النوع الفرعي (Sub)</label>
                                            <input type="text" class="form-control form-control-sm" name="download_subs[]" value="<?php echo htmlspecialchars($item['sub'] ?? 'Checklist'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">مسار الملف (URL)</label>
                                            <input type="text" class="form-control form-control-sm" name="download_files[]" value="<?php echo htmlspecialchars($item['file'] ?? '#'); ?>">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeVisaRow('visa_download_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف هذا النموذج</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addVisaDownloadRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="visaDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<script>
    // دالة عامة لحذف أي صف (ملاحظة أو ملف تحميل)
    function removeVisaRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 1. إدارة صفوف الملاحظات الديناميكية
    let visaNoteIndex = <?php echo count($visa_data['notes_section']['notes_list'] ?? []); ?>;
    function addVisaNoteRow() {
        const container = document.getElementById('visaNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item mb-2';
        div.id = 'visa_note_' + visaNoteIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="notes_list[]" placeholder="اكتب الملاحظة الجديدة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeVisaRow('visa_note_${visaNoteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        visaNoteIndex++;
    }

    // 2. إدارة صفوف ملفات التحميل الديناميكية
    let visaDownloadIndex = <?php echo count($visa_data['download_items'] ?? []); ?>;
    function addVisaDownloadRow() {
        const container = document.getElementById('visaDownloadContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative download-item-box mb-3';
        div.id = 'visa_download_' + visaDownloadIndex;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">نوع الملف</label>
                    <select class="form-select form-select-sm" name="download_types[]">
                        <option value="pdf" selected>PDF</option>
                        <option value="word">Word</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold">عنوان البطاقة</label>
                    <input type="text" class="form-control form-control-sm" name="download_titles[]" value="قائمة مراجعة متطلبات التأشيرة">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">النوع الفرعي (Sub)</label>
                    <input type="text" class="form-control form-control-sm" name="download_subs[]" value="Checklist">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">مسار الملف (URL)</label>
                    <input type="text" class="form-control form-control-sm" name="download_files[]" value="assets/files/checklist.pdf">
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeVisaRow('visa_download_${visaDownloadIndex}')"><i class="bi bi-trash"></i> حذف هذا النموذج</button>
        `;
        container.appendChild(div);
        visaDownloadIndex++;
    }

    // 3. ربط كافة النماذج (بما فيها نموذج الملاحظات الجديد) عبر AJAX
    document.querySelectorAll('#visaBreadcrumbForm, #visaHeroForm, #visaMainForm, #visaNotesForm, #visaDownloadForm').forEach(form => {
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
