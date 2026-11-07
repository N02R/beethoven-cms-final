<?php
/**
 * Beethoven CMS - Contact Admin Modals (Fully Localized & AJAX Enabled)
 * النمط القياسي الذهبي لنوافذ التحكم الخاصة بصفحة التواصل (مطابق تماماً لمعيار admin_edu_modals.php)
 */

// 🌟 التحصين الأقوى: جلب اللغة حصراً من الجلسة أو الـ URL لضمان عدم ثباتها على لغة واحدة
$current_lang = $_SESSION['site_lang'] ?? (isset($_GET['lang']) ? htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8') : 'de');
$is_rtl = ($current_lang === 'ar');
$modal_dir = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

// تحميل ملف اللغة النشطة بدقة مطلقة
$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$lang = file_exists($lang_file) ? require $lang_file : [];
if (!is_array($lang)) $lang = [];

// 🌟 جلب بيانات أقسام التواصل مباشرة من قاعدة البيانات إن لم تكن متوفرة في النطاق (Scope) لضمان عدم حدوث أي خطأ
if (!isset($contact_hero) || empty($contact_hero)) {
    try {
        $pdoConn = \App\Config\Database::getConnection();
        
        $fetchSetting = function(string $key) use ($pdoConn, $current_lang) {
            $stmt = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = :k");
            $stmt->execute(['k' => $key]);
            $raw = $stmt->fetchColumn();
            if ($raw && is_string($raw)) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    if (isset($decoded[$current_lang])) return $decoded[$current_lang];
                    if (isset($decoded['ar'])) return $decoded['ar'];
                    return $decoded;
                }
            }
            return [];
        };

        $contact_hero = $fetchSetting('contact_hero');
        $contact_hero_img = $contact_hero['img'] ?? 'assets/img/contacthero.png';

        $contact_info = $fetchSetting('contact_info');
        $contact_info_title = $contact_info['title'] ?? 'معلومات التواصل';
        $contact_info_desc  = $contact_info['desc'] ?? '';
        $contact_address    = $contact_info['address'] ?? '';
        $contact_email      = $contact_info['email'] ?? '';
        $contact_phone      = $contact_info['phone'] ?? '';
        $contact_address_icon = $contact_info['address_icon'] ?? 'assets/img/Location.svg';
        $contact_email_icon   = $contact_info['email_icon'] ?? 'assets/img/Mail.svg';
        $contact_phone_icon   = $contact_info['phone_icon'] ?? 'assets/img/Call.svg';

        $whatsapp_section = $fetchSetting('whatsapp_section');
        $whatsapp_text    = $whatsapp_section['text'] ?? '';
        $whatsapp_url     = $whatsapp_section['url'] ?? '#';
        $whatsapp_btn_txt = $whatsapp_section['btn_text'] ?? 'تواصل عبر الواتساب';

    } catch (\Exception $e) {
        $contact_hero_img = 'assets/img/contacthero.png';
        $contact_info_title = 'معلومات التواصل';
        $contact_info_desc = '';
        $contact_address = '';
        $contact_email = '';
        $contact_phone = '';
        $contact_address_icon = 'assets/img/Location.svg';
        $contact_email_icon = 'assets/img/Mail.svg';
        $contact_phone_icon = 'assets/img/Call.svg';
        $whatsapp_text = '';
        $whatsapp_url = '#';
        $whatsapp_btn_txt = 'تواصل عبر الواتساب';
    }
}
?>

<!-- 1. Contact Hero Modal -->
<div class="modal fade custom-modal" id="contactHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-image-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_contact_hero'] ?? 'تعديل هيرو صفحة التواصل', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary d-block mb-3">
                                <?php echo htmlspecialchars($lang['current_image'] ?? 'الصورة الحالية:', ENT_QUOTES, 'UTF-8'); ?>
                            </label>

                            <?php if (!empty($contact_hero_img)): ?>
                                <div class="mb-3 p-3 bg-light rounded-3 border text-center">
                                    <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center shadow-sm">
                                        <img src="<?php echo htmlspecialchars(get_image_url($contact_hero_img), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Preview" class="rounded-2" style="max-height: 120px; object-fit: contain;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <label for="contact_hero_file_input" class="form-label fw-semibold small text-secondary mt-2">
                                <?php echo htmlspecialchars($lang['choose_file'] ?? 'رفع صورة جديدة', ENT_QUOTES, 'UTF-8'); ?>
                            </label>
                            <input type="file" id="contact_hero_file_input" class="form-control" name="contact_hero_img" accept="image/*">
                            <input type="hidden" name="old_contact_hero_img" value="<?php echo htmlspecialchars($contact_hero_img ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Contact Info Modal (معلومات والأيقونات الثلاث) -->
<div class="modal fade custom-modal" id="contactInfoModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_contact_info'] ?? 'تعديل معلومات وأيقونات التواصل', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactInfoForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_info">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- عنوان ووصف القسم الرئيسي -->
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary">عنوان قسم التواصل</label>
                                <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($contact_info_title ?? 'معلومات التواصل', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان القسم">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary">الوصف الفرعي لقسم التواصل</label>
                                <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][desc]" value="<?php echo htmlspecialchars($contact_info_desc ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الوصف الفرعي للقسم">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- 1. العنوان -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-geo-alt text-danger <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> خانة العنوان</h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">نص العنوان</label>
                                    <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][address]" value="<?php echo htmlspecialchars($contact_address ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="العنوان">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">أيقونة العنوان الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_address_icon)): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_address_icon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_address_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_address_icon]" value="<?php echo htmlspecialchars($contact_address_icon ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 2. البريد الإلكتروني -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-envelope text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> خانة البريد الإلكتروني</h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" name="contact_info[<?php echo $current_lang; ?>][email]" value="<?php echo htmlspecialchars($contact_email ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="البريد الإلكتروني">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">أيقونة البريد الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_email_icon)): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_email_icon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_email_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_email_icon]" value="<?php echo htmlspecialchars($contact_email_icon ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 3. الهاتف -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-telephone text-success <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> خانة الهاتف</h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">رقم الهاتف</label>
                                    <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][phone]" value="<?php echo htmlspecialchars($contact_phone ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="رقم الهاتف">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1">أيقونة الهاتف الحالية / الجديدة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($contact_phone_icon)): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($contact_phone_icon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_phone_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_phone_icon]" value="<?php echo htmlspecialchars($contact_phone_icon ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactInfoForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. WhatsApp Section Modal -->
<div class="modal fade custom-modal" id="whatsappSectionModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-whatsapp text-success <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_whatsapp_section'] ?? 'تعديل قسم الواتساب والتواصل المباشر', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="whatsappForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_whatsapp_section">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(safe_admin_string($csrf_token ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label for="whatsapp_text_input" class="form-label fw-semibold small text-secondary">النص الترويجي للواتساب</label>
                                <textarea id="whatsapp_text_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][text]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($whatsapp_text ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label for="whatsapp_url_input" class="form-label fw-semibold small text-secondary">رابط المحادثة (URL)</label>
                                <input type="text" id="whatsapp_url_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][url]" value="<?php echo htmlspecialchars($whatsapp_url ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://wa.me/...">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label for="whatsapp_btn_txt_input" class="form-label fw-semibold small text-secondary">نص زر الواتساب</label>
                                <input type="text" id="whatsapp_btn_txt_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($whatsapp_btn_txt ?? 'تواصل عبر الواتساب', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="whatsappForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'حفظ التغييرات', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'إلغاء', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Submission Engine الموحد الشامل -->
<script>
    document.querySelectorAll('#contactHeroModal form, #contactInfoModal form, #whatsappSectionModal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="csrf_token"]')?.value || '<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>';
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
            .then(response => response.text())
            .then(text => {
                console.log("Raw Server Response:", text);
                try {
                    const data = JSON.parse(text);
                    if (data.success || data.status === 'success') {
                        if (typeof showNotification === 'function') {
                            showNotification('تم حفظ التغييرات بنجاح، جاري تحديث الصفحة...', 'success');
                        } else {
                            alert(data.message || 'تم حفظ التغييرات بنجاح');
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        const errorMsg = data.message || data.error || 'يرجى التأكد من البيانات المدخلة';
                        if (typeof showNotification === 'function') {
                            showNotification('عذراً، لم يتم الحفظ: ' + errorMsg, 'danger');
                        } else {
                            alert('خطأ: ' + errorMsg);
                        }
                    }
                } catch (e) {
                    if (typeof showNotification === 'function') {
                        showNotification('خطأ في استجابة السيرفر (انظر الـ Console)', 'danger');
                    } else {
                        alert('حدث خطأ غير متوقع.');
                    }
                    console.error('JSON Parse Error:', text);
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                if (typeof showNotification === 'function') {
                    showNotification('حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
                } else {
                    alert('حدث خطأ في الاتصال بالشبكة.');
                }
            });
        });
    });
</script>
