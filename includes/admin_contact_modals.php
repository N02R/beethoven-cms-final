<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$path_prefix = $path_prefix ?? '';
?>

<!-- 1. Contact Hero Modal -->
<div class="modal fade custom-modal" id="contactHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image-fill text-primary"></i> تعديل صورة الهيرو (تواصل معنا)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactHeroForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_hero">
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">الصورة الحالية</label>
                        <?php if (!empty($contact_hero_img)): ?>
                            <div class="p-2 border rounded bg-light mb-2 text-center">
                                <img src="<?php echo $path_prefix . htmlspecialchars($contact_hero_img) . '?' . time(); ?>" style="max-height: 120px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <label class="form-label fw-bold">تغيير الصورة</label>
                        <input type="file" class="form-control" name="contact_hero_img" accept="image/*">
                        <input type="hidden" name="old_contact_hero_img" value="<?php echo htmlspecialchars($contact_hero_img ?? ''); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Contact Info Modal -->
<div class="modal fade custom-modal" id="contactInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle-fill text-primary"></i> تعديل معلومات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactInfoForm">
                    <input type="hidden" name="action" value="update_contact_info">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان (المدينة، الدولة)</label>
                        <input type="text" class="form-control" name="contact_address" value="<?php echo htmlspecialchars($contact_address ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($contact_email ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم الهاتف</label>
                        <input type="text" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone ?? ''); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactInfoForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. WhatsApp Section Modal -->
<div class="modal fade custom-modal" id="whatsappSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-whatsapp text-success"></i> تعديل قسم الواتساب والتواصل المباشر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="whatsappForm">
                    <input type="hidden" name="action" value="update_whatsapp_section">
                    <div class="mb-3">
                        <label class="form-label fw-bold">النص الترويجي للواتساب</label>
                        <textarea class="form-control" name="whatsapp_text" rows="3" style="height: auto;"><?php echo htmlspecialchars($whatsapp_text ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط المحادثة (URL)</label>
                        <input type="text" class="form-control" name="whatsapp_url" value="<?php echo htmlspecialchars($whatsapp_url ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص زر الواتساب</label>
                        <input type="text" class="form-control" name="whatsapp_btn_txt" value="<?php echo htmlspecialchars($whatsapp_btn_txt ?? ''); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="whatsappForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Submission Engine -->
<script>
    document.querySelectorAll('#contactHeroModal form, #contactInfoModal form, #whatsappSectionModal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('admin/api/save_config.php', {
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
