<?php
// جدار حماية إضافي: منع الدخول المباشر للملف
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<style>
  /* تنسيقات احترافية صارمة لواجهات التحكم لتجنب تداخل الـ Bootstrap الافتراضي */
  .custom-modal .modal-content {
      border: none !important;
      border-radius: 16px !important;
      box-shadow: 0 15px 50px rgba(0,0,0,0.2) !important;
      background-color: #ffffff !important;
  }
  
  /* إجبار ترويسة المودل على أخذ التدرج الداكن الفخم */
  .custom-modal .modal-header {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
      color: #ffffff !important;
      border-bottom: none !important;
      border-top-left-radius: 16px !important;
      border-top-right-radius: 16px !important;
      padding: 20px 24px !important;
  }

  /* تحويل عنوان المودل للون الأبيض الواضح */
  .custom-modal .modal-title {
      color: #ffffff !important;
      font-weight: 700 !important;
  }
  
  /* تحويل زر الإغلاق (X) للون الأبيض ليتناسق مع الخلفية الغامقة */
  .custom-modal .btn-close {
      filter: invert(1) grayscale(100%) brightness(200%) !important;
      opacity: 0.8 !important;
  }
  .custom-modal .btn-close:hover {
      opacity: 1 !important;
  }

  /* منطقة معاينة الصورة الفورية */
  .preview-zone {
      border: 2px dashed #cbd5e1 !important;
      border-radius: 12px !important;
      padding: 20px !important;
      background-color: #f8fafc !important;
      transition: all 0.3s ease !important;
  }
  .preview-zone:hover {
      border-color: #3b82f6 !important;
      background-color: #f1f5f9 !important;
  }
  
  /* أزرار مخصصة متناسقة */
  .custom-modal .btn-primary {
      background-color: #0d6efd !important;
      border-color: #0d6efd !important;
  }
  .custom-modal .btn-light {
      background-color: #f1f5f9 !important;
      border-color: #e2e8f0 !important;
      color: #475569 !important;
  }
</style>

<!-- ========================================== -->
<!-- 1. مودل تعديل اللوجو المطور مع المعاينة الفورية -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-labelledby="logoEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="logoEditModalLabel">✨ إدارة هوية الموقع والشعار</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <form id="logoUploadForm" enctype="multipart/form-data">
        <div class="modal-body p-4">
          
          <!-- منطقة المعاينة الذكية -->
          <div class="mb-4 text-center">
            <label class="form-label d-block text-end fw-semibold text-secondary mb-2">معاينة الشعار:</label>
            <div class="preview-zone">
              <img id="currentLogoPreview" src="<?php echo $path_prefix . $site_logo_path; ?>" style="max-height: 90px; object-fit: contain;" class="img-fluid">
              <div id="newLogoSelectedText" class="small text-success fw-bold mt-2 d-none">✓ تم اختيار صورة جديدة - جاهزة للرفع</div>
            </div>
          </div>

          <div class="mb-3">
            <label for="logoFileInput" class="form-label text-end d-block fw-semibold text-secondary">اختر الشعار الجديد</label>
            <input class="form-control" type="file" id="logoFileInput" name="logo" accept="image/png, image/jpeg, image/svg+xml, image/webp" required>
            <div class="form-text text-end">الصيغ المدعومة لضمان الأداء العالي: PNG, SVG, WebP.</div>
          </div>
          
          <div id="logoUploadSpinner" class="spinner-border text-primary d-none my-2 mx-auto" role="status"></div>
        </div>
        <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
          <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary px-4 fw-bold" id="saveLogoBtn">تحديث الشعار الآن</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 2. مودل تعديل شريط الإعلانات الديناميكي المطور -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-labelledby="announcementEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="announcementEditModalLabel">📢 إدارة شريط الإعلانات والجدولة الزمنية</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <form id="announcementUpdateForm">
        <div class="modal-body p-4">
          <div class="row g-3">
            
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary text-end d-block">حالة النشر الحالية</label>
              <select class="form-select" name="status" id="ad_status">
                <option value="Published" <?php echo ($announcement['status'] ?? '') === 'Published' ? 'selected' : ''; ?>>🟢 منشور ومفعل للطلاب</option>
                <option value="Draft" <?php echo ($announcement['status'] ?? '') === 'Draft' ? 'selected' : ''; ?>>⚪ مسودة (إخفاء من الموقع)</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary text-end d-block">نوع المحتوى الإعلاني</label>
              <select class="form-select" name="type" id="ad_type" onchange="toggleAdFields()">
                <option value="text" <?php echo ($announcement['type'] ?? 'text') === 'text' ? 'selected' : ''; ?>>🔤 نص متحرك ديناميكي</option>
                <option value="image" <?php echo ($announcement['type'] ?? '') === 'image' ? 'selected' : ''; ?>>🖼️ صورة إعلانية (بانر مصمم)</option>
              </select>
            </div>

            <!-- حقول الإعلان النصي -->
            <div class="col-12 ad-text-field">
              <div class="d-flex justify-content-between flex-row-reverse">
                <label class="form-label fw-semibold text-secondary">نص الإعلان الموجه للطلاب</label>
                <span id="charCount" class="small text-muted">0 / 150 حرف</span>
              </div>
              <input type="text" class="form-control text-end" id="ad_text_input" name="announcement_text" max-length="150" value="<?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?>" placeholder="مثال: بدأ التسجيل للفصل الدراسي الثاني! بادر بحجز مقعدك الآن...">
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary text-end d-block">خلفية الشريط</label>
              <div class="color-picker-wrapper">
                <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo htmlspecialchars($announcement['bg_color'] ?? '#000000'); ?>">
                <span class="small text-muted d-block text-end">اختر لوناً متناسقاً</span>
              </div>
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary text-end d-block">لون الخط</label>
              <div class="color-picker-wrapper">
                <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo htmlspecialchars($announcement['text_color'] ?? '#ffffff'); ?>">
                <span class="small text-muted d-block text-end">يفضل لون واضح</span>
              </div>
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary text-end d-block">حجم الخط</label>
              <select class="form-select" name="font_size">
                <option value="14" <?php echo (int)($announcement['font_size'] ?? 16) == 14 ? 'selected' : ''; ?>>صغير (14px)</option>
                <option value="16" <?php echo (int)($announcement['font_size'] ?? 16) == 16 ? 'selected' : ''; ?>>قياسي (16px)</option>
                <option value="18" <?php echo (int)($announcement['font_size'] ?? 16) == 18 ? 'selected' : ''; ?>>كبير (18px)</option>
              </select>
            </div>

            <!-- حقول الإعلان الصوري -->
            <div class="col-12 ad-image-field d-none">
              <label class="form-label fw-semibold text-secondary text-end d-block">رفع البانر الإعلاني</label>
              <input type="file" class="form-control" name="announcement_image" accept="image/*">
              <div class="form-text text-end">يفضل مقاس طولي رفيع ليناسب شريط الإعلانات العلوي.</div>
              
              <label class="form-label mt-3 fw-semibold text-secondary text-end d-block">النص البديل للصورة (مهم لجوجل SEO)</label>
              <input type="text" class="form-control text-end" name="alt_text" value="<?php echo htmlspecialchars($announcement['alt_text'] ?? 'إعلان خدمات بيتهوفن'); ?>">
            </div>

            <!-- الجدولة الزمنية وروابط التوجيه -->
            <div class="col-md-8">
              <label class="form-label fw-semibold text-secondary text-end d-block">رابط التوجيه عند الضغط على الإعلان (اختياري)</label>
              <input type="url" class="form-control text-start" style="direction: ltr;" name="link" value="<?php echo htmlspecialchars($announcement['link'] ?? ''); ?>" placeholder="https://beethoven-city-service.com/register">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold text-secondary text-end d-block">آلية الفتح</label>
              <select class="form-select" name="open_new_tab">
                <option value="0" <?php echo ($announcement['open_new_tab'] ?? 0) == 0 ? 'selected' : ''; ?>>نفس التبويب</option>
                <option value="1" <?php echo ($announcement['open_new_tab'] ?? 0) == 1 ? 'selected' : ''; ?>>تبويب جديد (_blank)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary text-end d-block">📅 بدء العرض تلقائياً (أتمتة)</label>
              <input type="datetime-local" class="form-control text-end" name="start_date" value="<?php echo htmlspecialchars($announcement['start_date'] ?? ''); ?>">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary text-end d-block">⌛ انتهاء العرض تلقائياً</label>
              <input type="datetime-local" class="form-control text-end" name="end_date" value="<?php echo htmlspecialchars($announcement['end_date'] ?? ''); ?>">
            </div>
            
          </div>
          <div id="adUpdateSpinner" class="spinner-border text-primary d-none my-3 d-block mx-auto" role="status"></div>
        </div>
        <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
          <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary px-4 fw-bold" id="saveAdBtn">حفظ ونشر التعديلات</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 3. مودل إدارة أيقونات التواصل الاجتماعي الموحد (الجديد) -->
<!-- ========================================== -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-labelledby="socialLinksEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse">
        <h5 class="modal-title fw-bold" id="socialLinksEditModalLabel">🌐 إدارة قنوات التواصل الاجتماعي للطلاب</h5>
        <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        
        <!-- زر إضافة عنصر جديد محاذى لليمين بشكل احترافي -->
        <div class="text-start mb-4">
          <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm" onclick="addNewSocialRow()">
            ➕ إضافة منصة جديدة
          </button>
        </div>

        <form id="socialLinksForm" enctype="multipart/form-data">
          <!-- حاوية عرض الصفوف تحت بعضها -->
          <div id="socialRowsContainer" style="direction: rtl;">
            
            <?php 
            // فرضية قراءة المصفوفة الحالية (يمكن ربطها بالـ JSON لاحقاً)
            $social_links = $social_links ?? [
                ['id' => 1, 'name' => 'Facebook', 'img' => 'assets/img/socialicons/Facebook.png', 'url' => 'https://www.facebook.com/BeethovenCityService'],
                ['id' => 2, 'name' => 'Instagram', 'img' => 'assets/img/socialicons/Instagram.png', 'url' => 'https://www.instagram.com/beethoven_city_service'],
                ['id' => 3, 'name' => 'WhatsApp', 'img' => 'assets/img/socialicons/whatsapp.png', 'url' => 'https://wa.me/4917671230666'],
                ['id' => 4, 'name' => 'Twitter', 'img' => 'assets/img/socialicons/Twitter.png', 'url' => '#'],
                ['id' => 5, 'name' => 'YouTube', 'img' => 'assets/img/socialicons/youtube.png', 'url' => 'https://youtube.com/@learning_german_language']
            ];

            foreach ($social_links as $index => $link): 
            ?>
              <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" data-id="<?php echo $link['id']; ?>" style="border-right: 4px solid #3b82f6 !important;">
                <div class="row align-items-center g-3 text-end">
                  
                  <!-- معاينة الأيقونة الصغيرة -->
                  <div class="col-auto">
                    <div class="bg-light p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; border: 1px solid #e2e8f0;">
                      <img id="preview_img_<?php echo $index; ?>" src="<?php echo $path_prefix . $link['img']; ?>" style="max-width: 35px; max-height: 35px; object-fit: contain;">
                    </div>
                  </div>

                  <!-- تعديل اسم المنصة والصورة -->
                  <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary mb-1">اسم المنصة / الصورة</label>
                    <input type="text" class="form-control form-control-sm mb-1 fw-semibold text-end" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name']); ?>" placeholder="مثال: فيسبوك">
                    <input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>" accept="image/*" onchange="previewSocialIcon(this, 'preview_img_<?php echo $index; ?>')">
                    <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($link['img']); ?>">
                  </div>

                  <!-- تعديل الرابط الخاص بالمنصة -->
                  <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary mb-1">رابط التوجيه (URL)</label>
                    <input type="url" class="form-control form-control-sm text-start" style="direction: ltr;" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="https://...">
                  </div>

                  <!-- زر الحذف المنسق -->
                  <div class="col-md-2 text-start pt-3 pt-md-0 me-auto">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold py-2 rounded-3" onclick="deleteSocialRow(this)">
                      🗑️ حذف العنصر
                    </button>
                  </div>

                </div>
              </div>
            <?php endforeach; ?>

          </div>
          
          <div id="socialUpdateSpinner" class="spinner-border text-primary d-none my-3 mx-auto d-block" role="status"></div>
        </form>

      </div>
      
      <div class="modal-footer border-0 p-3 bg-light rounded-bottom flex-row-reverse justify-content-start">
        <button type="submit" form="socialLinksForm" class="btn btn-primary px-4 fw-bold ms-2" id="saveSocialBtn">حفظ التغييرات بالكامل</button>
        <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">إلغاء</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 4. سكربتات التحكم التفاعلية الموحدة والمحدثة (JavaScript) -->
<!-- ========================================== -->
<script>
function toggleAdFields() {
    const type = document.getElementById('ad_type').value;
    if (type === 'text') {
        document.querySelectorAll('.ad-text-field').forEach(el => el.classList.remove('d-none'));
        document.querySelectorAll('.ad-image-field').forEach(el => el.classList.add('d-none'));
    } else {
        document.querySelectorAll('.ad-text-field').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.ad-image-field').forEach(el => el.classList.remove('d-none'));
    }
}

// دالات التحكم الحركية لأيقونات التواصل الاجتماعي (مدمجة هنا بذكاء لمنع التشتت)
function previewSocialIcon(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteSocialRow(button) {
    if(confirm('هل أنتِ متأكدة من رغبتك في حذف منصة التواصل هذه بالكامل؟')) {
        const row = button.closest('.social-item-row');
        row.style.opacity = '0';
        setTimeout(() => row.remove(), 300);
    }
}

function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    const index = container.querySelectorAll('.social-item-row').length;
    
    const newRowHTML = `
      <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #10b981 !important; opacity: 0; transform: translateY(10px); transition: all 0.3s ease;">
        <div class="row align-items-center g-3 text-end">
          <div class="col-auto">
            <div class="bg-light p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; border: 1px solid #e2e8f0;">
              <img id="preview_img_new_${index}" src="assets/img/socialicons/default.png" style="max-width: 35px; max-height: 35px; object-fit: contain;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1006/1006771.png'">
            </div>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-secondary mb-1">اسم المنصة / الصورة</label>
            <input type="text" class="form-control form-control-sm mb-1 fw-semibold text-end" name="social[new_${index}][name]" placeholder="مثال: تيك توك" required>
            <input type="file" class="form-control form-control-sm" name="social_img_new_${index}" accept="image/*" required onchange="previewSocialIcon(this, 'preview_img_new_${index}')">
            <input type="hidden" name="social[new_${index}][old_img]" value="">
          </div>
          <div class="col-md-5">
            <label class="form-label small fw-bold text-secondary mb-1">رابط التوجيه (URL)</label>
            <input type="url" class="form-control form-control-sm text-start" style="direction: ltr;" name="social[new_${index}][url]" placeholder="https://..." required>
          </div>
          <div class="col-md-2 text-start me-auto">
            <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold py-2 rounded-3" onclick="deleteSocialRow(this)">
              🗑️ حذف العنصر
            </button>
          </div>
        </div>
      </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newRowHTML);
    const addedRow = container.lastElementChild;
    setTimeout(() => {
        addedRow.style.opacity = '1';
        addedRow.style.transform = 'translateY(0)';
    }, 50);
}

document.addEventListener('DOMContentLoaded', function() {
    toggleAdFields();

    // أولاً: ميزة المعاينة الفورية للشعار المرفوع (Live Preview)
    const logoInput = document.getElementById('logoFileInput');
    const logoPreview = document.getElementById('currentLogoPreview');
    const logoSuccessText = document.getElementById('newLogoSelectedText');

    if(logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoSuccessText.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // ثانياً: عدّاد الحروف التفاعلي لنص الإعلان
    const adTextInput = document.getElementById('ad_text_input');
    const charCountLabel = document.getElementById('charCount');
    
    if(adTextInput && charCountLabel) {
        const updateCount = () => {
            charCountLabel.textContent = `${adTextInput.value.length} / 150 حرف`;
        };
        adTextInput.addEventListener('input', updateCount);
        updateCount();
    }
});

// إرسال كود اللوجو عبر Fetch API
document.getElementById('logoUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveLogoBtn');
    const spinner = document.getElementById('logoUploadSpinner');
    
    saveBtn.disabled = true;
    spinner.classList.remove('d-none');

    fetch('<?php echo $path_prefix; ?>admin/api/upload_logo.php', { 
        method: 'POST', 
        body: new FormData(this) 
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) { 
            location.reload(); 
        } else { 
            alert('خطأ: ' + data.error); 
            saveBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ بالاتصال بالسيرفر.');
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    });
});

// إرسال كود الإعلان عبر Fetch API
document.getElementById('announcementUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveAdBtn');
    const spinner = document.getElementById('adUpdateSpinner');
    
    saveBtn.disabled = true;
    spinner.classList.remove('d-none');

    fetch('<?php echo $path_prefix; ?>admin/api/update_announcement.php', { 
        method: 'POST', 
        body: new FormData(this) 
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) { 
            location.reload(); 
        } else { 
            alert('خطأ: ' + data.error); 
            saveBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ بالاتصال بالسيرفر.');
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    });
});

// ثالثاً: إرسال كود مصفوفة قنوات التواصل الاجتماعي عبر Fetch API
document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveSocialBtn');
    const spinner = document.getElementById('socialUpdateSpinner');
    
    saveBtn.disabled = true;
    spinner.classList.remove('d-none');

    fetch('<?php echo $path_prefix; ?>admin/api/update_social_links.php', { 
        method: 'POST', 
        body: new FormData(this) 
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) { 
            location.reload(); 
        } else { 
            alert('خطأ: ' + data.error); 
            saveBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ بالاتصال بالسيرفر.');
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    });
});
</script>
