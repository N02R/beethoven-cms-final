<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>

<!-- 1. Modal: Edit Contact Hero -->
<div class="modal fade custom-modal" id="contactHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactHeroForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_hero">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">صورة الهيرو الحالية / اختيار صورة جديدة</label>
                        <?php if (!empty($data['contact_hero_img'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars('../../' . $data['contact_hero_img']); ?>" class="thumb-preview" style="max-height: 100px; border-radius: 8px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="contact_hero_img" accept="image/*">
                        <input type="hidden" name="old_contact_hero_img" value="<?php echo htmlspecialchars($data['contact_hero_img'] ?? ''); ?>">
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

<!-- 2. Modal: Edit Contact Info -->
<div class="modal fade custom-modal" id="contactInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle text-primary"></i> تعديل معلومات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactInfoForm">
                    <input type="hidden" name="action" value="update_contact_info">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">العنوان</label>
                        <input type="text" class="form-control" name="contact_address" value="<?php echo htmlspecialchars($data['contact_address'] ?? 'Rheinweg 140 ,53129 Bonn,Germany'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($data['contact_email'] ?? 'info@Beethoven-City-Services.com'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">رقم الهاتف</label>
                        <input type="text" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($data['contact_phone'] ?? '666-230-71 176 (0) 49+'); ?>">
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

<!-- 3. Modal: Edit WhatsApp Section -->
<div class="modal fade custom-modal" id="whatsappSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-whatsapp text-success"></i> تعديل قسم الواتساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="whatsappForm">
                    <input type="hidden" name="action" value="update_whatsapp_section">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">النص الترويجي</label>
                        <textarea class="form-control" name="whatsapp_text" rows="3"><?php echo htmlspecialchars($data['whatsapp_text'] ?? 'نحن في Beethoven City نؤمن أن التواصل المباشر هو الأفضل.. لذلك نوفر لك قنوات تواصل واضحة وآمنة بدون أي نماذج أو جمع بيانات'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">رابط الواتساب (URL)</label>
                        <input type="text" class="form-control" name="whatsapp_url" value="<?php echo htmlspecialchars($data['whatsapp_url'] ?? 'https://wa.me/4917671230666'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">نص الزر</label>
                        <input type="text" class="form-control" name="whatsapp_btn_txt" value="<?php echo htmlspecialchars($data['whatsapp_btn_txt'] ?? 'تواصل معنا عبر واتساب'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="whatsappForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاءون</button>
            </div>
        </div>
    </div>
</div>

document.addEventListener('DOMContentLoaded', function () {
    // التقاط إرسال أي فورم داخل مودلات الإدارة
    const adminForms = document.querySelectorAll('.custom-modal form');
    
    adminForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.querySelector(`[form="${this.id}"]`) || this.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'جاري الحفظ...';
            }

            fetch('save_config.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' || data.success) {
                    // إغلاق المودل المفتوح حالياً
                    const activeModal = bootstrap.Modal.getInstance(this.closest('.modal'));
                    if (activeModal) {
                        activeModal.hide();
                    }
                    
                    // إظهار رسالة النجاح (تأكد من وجود مكتبة SweetAlert2 أو استبدالها بـ alert عادي)
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث بنجاح',
                            text: 'جاري تحديث الصفحة...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert('تم التحديث بنجاح!');
                        location.reload();
                    }
                } else {
                    alert('حدث خطأ ما: ' + (data.message || 'يرجى المحاولة مجدداً'));
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'حفظ التغييرات';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'حفظ التغييرات';
                }
            });
        });
    });
});
