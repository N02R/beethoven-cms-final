<?php
// التأكد من عدم الوصول المباشر أو التحقق من صلاحيات الأدمن
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$medical_data = $global_data['medical_package_page'] ?? [];
?>

<!-- 1. Modal تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade custom-modal" id="medicalBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="medicalBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_medical_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص المسار الأخير</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($medical_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط المسار الأخير</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($medical_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="medicalBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal تعديل صورة الهيرو (Hero) -->
<div class="modal fade custom-modal" id="medicalHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="medicalHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_medical_hero">
                    <?php if (!empty($medical_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($medical_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($medical_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">توضع الصورة (Hero Position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($medical_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="medicalHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Modal تعديل العنوان والوصف الرئيسي (Main Section) -->
<div class="modal fade custom-modal" id="medicalMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="medicalMainForm" method="POST">
                    <input type="hidden" name="action" value="update_medical_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($medical_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($medical_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="medicalMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal تعديل الملاحظات الهامة -->
<div class="modal fade custom-modal" id="medicalNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="medicalNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_medical_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص الملاحظة</label>
                        <textarea class="form-control" name="note_text" rows="3" required><?php echo htmlspecialchars($medical_data['note_text'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="medicalNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Modal تعديل كرت ورابط التحميل (Download Card) -->
<div class="modal fade custom-modal" id="medicalCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> تعديل ملف التحميل واتفاقية التدريب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="medicalCardForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_medical_card">
                    <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($medical_data['download_item']['file'] ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملف الظاهر</label>
                        <input type="text" class="form-control" name="item_title" value="<?php echo htmlspecialchars($medical_data['download_item']['title'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">النص الفرعي (Sub)</label>
                        <input type="text" class="form-control" name="item_sub" value="<?php echo htmlspecialchars($medical_data['download_item']['sub'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">نوع الملف</label>
                        <select class="form-select" name="item_type">
                            <option value="pdf" <?php echo (($medical_data['download_item']['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>ملف PDF</option>
                            <option value="word" <?php echo (in_array($medical_data['download_item']['type'] ?? '', ['word', 'docx'])) ? 'selected' : ''; ?>>ملف Word (docx)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع ملف جديد (PDF أو Word)</label>
                        <input type="file" class="form-control" name="item_file" accept=".pdf,.doc,.docx">
                        <div class="form-text text-muted mt-1">الملف الحالي: <?php echo htmlspecialchars($medical_data['download_item']['file'] ?? 'لا يوجد'); ?></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="medicalCardForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // ربط كافة النماذج الخاصة بصفحة التدريب الطبي عبر AJAX للإرسال الفوري وتحديث الصفحة
    document.querySelectorAll('#medicalBreadcrumbForm, #medicalHeroForm, #medicalMainForm, #medicalNotesForm, #medicalCardForm').forEach(form => {
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
