<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$pricelist_data = $global_data['pricelist_page'] ?? [];
$download_item = $pricelist_data['download_item'] ?? [];
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="priceListBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="priceListBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_pricelist_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة الحالي</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="priceListBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="priceListHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="priceListHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_pricelist_hero">
                    <?php if (!empty($pricelist_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($pricelist_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($pricelist_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="priceListHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="priceListMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="priceListMainForm" method="POST">
                    <input type="hidden" name="action" value="update_pricelist_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($pricelist_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($pricelist_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="priceListMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Download Card Edit Modal -->
<div class="modal fade custom-modal" id="priceListCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> تعديل ملف قائمة الأسعار</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="priceListCardForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_pricelist_card">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملف (اسم الكرت)</label>
                        <input type="text" class="form-control" name="item_title" value="<?php echo htmlspecialchars($download_item['title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">نوع الملف (أيقونة العرض)</label>
                        <select class="form-control" name="item_type">
                            <option value="pdf" <?php echo (($download_item['type'] ?? 'pdf') === 'pdf') ? 'selected' : ''; ?>>ملف PDF (Grouppdf.png)</option>
                            <option value="word" <?php echo (($download_item['type'] ?? '') === 'word') ? 'selected' : ''; ?>>ملف Word (Groupword.png)</option>
                        </select>
                    </div>
                    <?php if (!empty($download_item['file'])): ?>
                        <div class="mb-2 text-muted small">الملف الحالي: <code><?php echo htmlspecialchars($download_item['file']); ?></code></div>
                    <?php endif; ?>
                    <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($download_item['file'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع ملف جديد (اختياري)</label>
                        <input type="file" class="form-control" name="item_file" accept=".pdf,.doc,.docx">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="priceListCardForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine for Price List Modals -->
<script>
    // ربط كافة النماذج عبر AJAX بملف المعالجة المركزي
    document.querySelectorAll('#priceListBreadcrumbForm, #priceListHeroForm, #priceListMainForm, #priceListCardForm').forEach(form => {
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
