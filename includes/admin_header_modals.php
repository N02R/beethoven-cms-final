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
              <label class="form-label fw-semibold text-secondary">حالة النشر الحالية</label>
              <select class="form-select" name="status" id="ad_status">
                <option value="Published" <?php echo ($announcement['status'] ?? '') === 'Published' ? 'selected' : ''; ?>>🟢 منشور ومفعل للطلاب</option>
                <option value="Draft" <?php echo ($announcement['status'] ?? '') === 'Draft' ? 'selected' : ''; ?>>⚪ مسودة (إخفاء من الموقع)</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary">نوع المحتوى الإعلاني</label>
              <select class="form-select" name="type" id="ad_type" onchange="toggleAdFields()">
                <option value="text" <?php echo ($announcement['type'] ?? 'text') === 'text' ? 'selected' : ''; ?>>🔤 نص متحرك ديناميكي</option>
                <option value="image" <?php echo ($announcement['type'] ?? '') === 'image' ? 'selected' : ''; ?>>🖼️ صورة إعلانية (بانر مصمم)</option>
              </select>
            </div>

            <!-- حقول الإعلان النصي -->
            <div class="col-12 ad-text-field">
              <div class="d-flex justify-content-between">
                <label class="form-label fw-semibold text-secondary">نص الإعلان الموجه للطلاب</label>
                <span id="charCount" class="small text-muted">0 / 150 حرف</span>
              </div>
              <input type="text" class="form-control" id="ad_text_input" name="announcement_text" max-length="150" value="<?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?>" placeholder="مثال: بدأ التسجيل للفصل الدراسي الثاني! بادر بحجز مقعدك الآن...">
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary">خلفية الشريط</label>
              <div class="color-picker-wrapper">
                <input type="color" class="form-control form-control-color" name="bg_color" value="<?php echo htmlspecialchars($announcement['bg_color'] ?? '#000000'); ?>">
                <span class="small text-muted">اختر لوناً متناسقاً</span>
              </div>
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary">لون الخط</label>
              <div class="color-picker-wrapper">
                <input type="color" class="form-control form-control-color" name="text_color" value="<?php echo htmlspecialchars($announcement['text_color'] ?? '#ffffff'); ?>">
                <span class="small text-muted">يفضل لون واضح</span>
              </div>
            </div>
            
            <div class="col-md-4 ad-text-field">
              <label class="form-label fw-semibold text-secondary">حجم الخط</label>
              <select class="form-select" name="font_size">
                <option value="14" <?php echo (int)($announcement['font_size'] ?? 16) == 14 ? 'selected' : ''; ?>>صغير (14px)</option>
                <option value="16" <?php echo (int)($announcement['font_size'] ?? 16) == 16 ? 'selected' : ''; ?>>قياسي (16px)</option>
                <option value="18" <?php echo (int)($announcement['font_size'] ?? 16) == 18 ? 'selected' : ''; ?>>كبير (18px)</option>
              </select>
            </div>

            <!-- حقول الإعلان الصوري -->
            <div class="col-12 ad-image-field d-none">
              <label class="form-label fw-semibold text-secondary">رفع البانر الإعلاني</label>
              <input type="file" class="form-control" name="announcement_image" accept="image/*">
              <div class="form-text text-end">يفضل مقاس طولي رفيع ليناسب شريط الإعلانات العلوي.</div>
              
              <label class="form-label mt-3 fw-semibold text-secondary">النص البديل للصورة (مهم جداً للـ SEO لجوجل)</label>
              <input type="text" class="form-control" name="alt_text" value="<?php echo htmlspecialchars($announcement['alt_text'] ?? 'إعلان خدمات بيتهوفن'); ?>">
            </div>

            <!-- الجدولة الزمنية وروابط التوجيه -->
            <div class="col-md-8">
              <label class="form-label fw-semibold text-secondary">رابط التوجيه عند الضغط على الإعلان (اختياري)</label>
              <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['link'] ?? ''); ?>" placeholder="https://beethoven-city-service.com/register">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold text-secondary">آلية الفتح</label>
              <select class="form-select" name="open_new_tab">
                <option value="0" <?php echo ($announcement['open_new_tab'] ?? 0) == 0 ? 'selected' : ''; ?>>نفس التبويب</option>
                <option value="1" <?php echo ($announcement['open_new_tab'] ?? 0) == 1 ? 'selected' : ''; ?>>تبويب جديد (_blank)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary">📅 بدء العرض تلقائياً (أتمتة)</label>
              <input type="datetime-local" class="form-control" name="start_date" value="<?php echo htmlspecialchars($announcement['start_date'] ?? ''); ?>">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold text-secondary">⌛ انتهاء العرض تلقائياً</label>
              <input type="datetime-local" class="form-control" name="end_date" value="<?php echo htmlspecialchars($announcement['end_date'] ?? ''); ?>">
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
<!-- 3. سكربتات التحكم التفاعلية المحدثة (JavaScript) -->
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

document.addEventListener('DOMContentLoaded', function() {
    toggleAdFields();

    // أولاً: ميزة المعاينة الفورية للشعار المرفوع (Live Preview) قبل إرساله
    const logoInput = document.getElementById('logoFileInput');
    const logoPreview = document.getElementById('currentLogoPreview');
    const logoSuccessText = document.getElementById('newLogoSelectedText');

    if(logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result; // تغيير الصورة في الواجهة فوراً
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
        updateCount(); // تشغيل عند التحميل المبدئي
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
</script>
