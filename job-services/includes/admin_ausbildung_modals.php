<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$ausbildung_data = $global_data['ausbildung_package_page'] ?? [];
?>

<!-- 1. Modal تعديل مسار التنقل -->
<div class="modal fade custom-modal" id="ausbildungBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="ausbildungBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_ausbildung_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص المسار الأخير</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($ausbildung_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط المسار الأخير</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($ausbildung_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="ausbildungBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal تعديل صورة الهيرو -->
<div class="modal fade custom-modal" id="ausbildungHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="ausbildungHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_ausbildung_hero">
                    <?php if (!empty($ausbildung_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($ausbildung_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($ausbildung_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">توضع الصورة (Hero Position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($ausbildung_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="ausbildungHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal تعديل العنوان والوصف الرئيسي -->
<div class="modal fade custom-modal" id="ausbildungMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="ausbildungMainForm" method="POST">
                    <input type="hidden" name="action" value="update_ausbildung_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($ausbildung_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($ausbildung_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="ausbildungMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal تعديل الملاحظات -->
<div class="modal fade custom-modal" id="ausbildungNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="ausbildungNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_ausbildung_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص الملاحظة</label>
                        <textarea class="form-control" name="note_text" rows="3" required><?php echo htmlspecialchars($ausbildung_data['note_text'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="ausbildungNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal تعديل ملف التحميل -->
<div class="modal fade custom-modal" id="ausbildungCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> تعديل ملف اتفاقية التدريب المهني</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="ausbildungCardForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_ausbildung_card">
                    <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($ausbildung_data['download_item']['file'] ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملف الظاهر</label>
                        <input type="text" class="form-control" name="item_title" value="<?php echo htmlspecialchars($ausbildung_data['download_item']['title'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">النص الفرعي (Sub)</label>
                        <input type="text" class="form-control" name="item_sub" value="<?php echo htmlspecialchars($ausbildung_data['download_item']['sub'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">نوع الملف</label>
                        <select class="form-select" name="item_type">
                            <option value="pdf" <?php echo (($ausbildung_data['download_item']['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>ملف PDF</option>
                            <option value="word" <?php echo (in_array($ausbildung_data['download_item']['type'] ?? '', ['word', 'docx'])) ? 'selected' : ''; ?>>ملف Word (docx)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع ملف جديد (PDF أو Word)</label>
                        <input type="file" class="form-control" name="item_file" accept=".pdf,.doc,.docx">
                        <div class="form-text text-muted mt-1">الملف الحالي: <?php echo htmlspecialchars($ausbildung_data['download_item']['file'] ?? 'لا يوجد'); ?></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="ausbildungCardForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    document.querySelectorAll('#ausbildungBreadcrumbForm, #ausbildungHeroForm, #ausbildungMainForm, #ausbildungNotesForm, #ausbildungCardForm').forEach(form => {
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
