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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة نماذج وملفات خطاب الطلب المتاحة للتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverDownloadForm" method="POST">
                    <input type="hidden" name="action" value="update_cover_downloads">
                    <label class="form-label fw-bold">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    <div id="coverDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($cover_data['download_items'])): ?>
                            <?php foreach ($cover_data['download_items'] as $index => $item): ?>
                                <div class="p-3 border rounded bg-light position-relative download-item-box" id="cover_download_<?php echo $index; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">نوع الملف</label>
                                            <select class="form-select form-select-sm" name="download_types[]">
                                                <option value="pdf" <?php echo (strtolower($item['type']) === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($item['type']) === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold">عنوان البطاقة</label>
                                            <input type="text" class="form-control form-control-sm" name="download_titles[]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">النوع الفرعي (Sub)</label>
                                            <input type="text" class="form-control form-control-sm" name="download_subs[]" value="<?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">مسار الملف (URL)</label>
                                            <input type="text" class="form-control form-control-sm" name="download_files[]" value="<?php echo htmlspecialchars($item['file'] ?? '#'); ?>">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeCoverRow('cover_download_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف هذا النموذج</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addCoverDownloadRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine Additions for Dynamic Downloads -->
<!-- JavaScript Engine -->
<script>
    // دالة عامة لحذف أي صف أو عنصر ديناميكي
    function removeCoverRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // إدارة صفوف نقاط النصائح
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

    // إدارة صفوف الملاحظات الهامة
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

    // إدارة صفوف نماذج التحميل الديناميكية (PDF & Word)
    let coverDownloadIndex = <?php echo count($cover_data['download_items'] ?? []); ?>;
    function addCoverDownloadRow() {
        const container = document.getElementById('coverDownloadContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative download-item-box';
        div.id = 'cover_download_' + coverDownloadIndex;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">نوع الملف</label>
                    <select class="form-select form-select-sm" name="download_types[]">
                        <option value="pdf">PDF</option>
                        <option value="word" selected>Word</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold">عنوان البطاقة</label>
                    <input type="text" class="form-control form-control-sm" name="download_titles[]" value="رسالة التعريف/ خطاب الطلب">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">النوع الفرعي (Sub)</label>
                    <input type="text" class="form-control form-control-sm" name="download_subs[]" value="Example">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">مسار الملف (URL)</label>
                    <input type="text" class="form-control form-control-sm" name="download_files[]" value="#">
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeCoverRow('cover_download_${coverDownloadIndex}')"><i class="bi bi-trash"></i> حذف هذا النموذج</button>
        `;
        container.appendChild(div);
        coverDownloadIndex++;
    }

    // ربط كافة النماذج عبر AJAX للإرسال الفوري وتحديث الصفحة
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
