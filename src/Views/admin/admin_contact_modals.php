<!-- 1. Contact Hero Modal -->
<div class="modal fade custom-modal" id="contactHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image-fill text-primary"></i> تعديل صورة الهيرو (تواصل معنا)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="col-12">
                        <label class="form-label fw-bold d-flex justify-content-between">
                            <span>الصورة الرئيسية الحالية</span>
                            <?php if (!empty($contact_hero_img)): ?>
                                <span class="badge bg-light text-dark border">موجودة</span>
                            <?php endif; ?>
                        </label>
                        <?php if (!empty($contact_hero_img)): ?>
                            <div class="mb-2 p-2 border rounded bg-light text-center">
                                <span class="d-block small text-muted mb-1">الصورة الحالية:</span>
                                <img src="<?php echo htmlspecialchars(get_image_url($contact_hero_img)); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                                <div class="small text-muted mt-1 dir-ltr"><?php echo htmlspecialchars($contact_hero_img); ?></div>
                            </div>
                        <?php endif; ?>
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


<!-- 2. Contact Info Modal (معلومات والأيقونات الثلاث) -->
<div class="modal fade custom-modal" id="contactInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle-fill text-primary"></i> تعديل معلومات وأيقونات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactInfoForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_info">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="d-flex flex-column gap-3">
                        <!-- 1. العنوان وأيقونته -->
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt text-danger me-1"></i> خانة العنوان</h6>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">نص العنوان</label>
                                    <input type="text" class="form-control form-control-sm" name="contact_address" value="<?php echo htmlspecialchars($contact_address ?? ''); ?>" placeholder="العنوان">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">أيقونة العنوان الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_address_icon)): ?>
                                            <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_address_icon)); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                                <span class="small text-muted text-truncate" style="font-size: 11px;"><?php echo basename($contact_address_icon); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control form-control-sm" name="contact_address_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="old_contact_address_icon" value="<?php echo htmlspecialchars($contact_address_icon ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 2. البريد الإلكتروني وأيقونته -->
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-envelope text-primary me-1"></i> خانة البريد الإلكتروني</h6>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">البريد الإلكتروني</label>
                                    <input type="email" class="form-control form-control-sm" name="contact_email" value="<?php echo htmlspecialchars($contact_email ?? ''); ?>" placeholder="البريد الإلكتروني">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">أيقونة البريد الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_email_icon)): ?>
                                            <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_email_icon)); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                                <span class="small text-muted text-truncate" style="font-size: 11px;"><?php echo basename($contact_email_icon); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control form-control-sm" name="contact_email_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="old_contact_email_icon" value="<?php echo htmlspecialchars($contact_email_icon ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 3. الهاتف وأيقونته -->
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-telephone text-success me-1"></i> خانة الهاتف</h6>
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">رقم الهاتف</label>
                                    <input type="text" class="form-control form-control-sm" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone ?? ''); ?>" placeholder="رقم الهاتف">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">أيقونة الهاتف الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_phone_icon)): ?>
                                            <div class="d-flex align-items-center gap-2 p-1 bg-white border rounded">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_phone_icon)); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                                <span class="small text-muted text-truncate" style="font-size: 11px;"><?php echo basename($contact_phone_icon); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control form-control-sm" name="contact_phone_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="old_contact_phone_icon" value="<?php echo htmlspecialchars($contact_phone_icon ?? ''); ?>">
                                </div>
                            </div>
                        </div>
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
                <form id="whatsappForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_whatsapp_section">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">النص الترويجي للواتساب</label>
                            <textarea class="form-control" name="whatsapp_text" rows="3"><?php echo htmlspecialchars($whatsapp_text ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط المحادثة (URL)</label>
                            <input type="text" class="form-control" name="whatsapp_url" value="<?php echo htmlspecialchars($whatsapp_url ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص زر الواتساب</label>
                            <input type="text" class="form-control" name="whatsapp_btn_txt" value="<?php echo htmlspecialchars($whatsapp_btn_txt ?? ''); ?>">
                        </div>
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
    // معالج النماذج الموحد الخاص بمودلز التواصل
    document.querySelectorAll('.custom-modal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // جلب الـ CSRF Token من الـ Meta Tag أو البديل الاحتياطي الآمن
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? ''); ?>';
            if (csrfToken && !formData.has('csrf_token')) {
                formData.append('csrf_token', csrfToken);
            }

            fetch('index.php?url=admin/settings/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'تم حفظ التغييرات بنجاح');
                    location.reload();
                } else {
                    console.error('Server Response Error:', data);
                    alert('خطأ: ' + (data.message || data.error || 'فشل الحفظ'));
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالسيرفر، افتح الـ Console للمزيد من التفاصيل.');
            });
        });
    });
</script>

