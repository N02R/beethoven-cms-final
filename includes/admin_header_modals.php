<?php
// جدار حماية إضافي: منع الدخول المباشر للملف عبر الرابط إذا لم تكن الجلسة محققة كمسؤول
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<!-- ========================================== -->
<!-- 1. مودل تعديل اللوجو (المطور والمطابق لتصميمكِ) -->
<!-- ========================================== -->
<div class="modal fade" id="logoEditModal" tabindex="-1" aria-labelledby="logoEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoEditModalLabel">تعديل شعار الموقع (Enterprise Storage)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <form id="logoUploadForm" enctype="multipart/form-data">
        <div class="modal-body text-center">
          <div class="mb-3">
            <small class="text-muted d-block mb-2">الشعار الحالي المعروض:</small>
            <img id="currentLogoPreview" src="<?php echo $path_prefix . $site_logo_path; ?>" style="max-height: 100px; background: #f8f9fa; padding: 10px; border-radius: 5px;" class="img-fluid">
          </div>
          <div class="mb-3">
            <input class="form-control" type="file" id="logoFileInput" name="logo" accept="image/*" required>
            <div class="form-text text-start">يفضل استخدام صيغ عالية الجودة والأداء، سيتم معالجة الصورة تلقائياً بالسيرفر.</div>
          </div>
          <div id="logoUploadSpinner" class="spinner-border text-primary d-none my-2" role="status"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary" id="saveLogoBtn">حفظ الشعار الجديد</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 2. مودل تعديل شريط الإعلانات الديناميكي -->
<!-- ========================================== -->
<div class="modal fade" id="announcementEditModal" tabindex="-1" aria-labelledby="announcementEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="announcementEditModalLabel">إدارة شريط الإعلانات والجدولة الزمنية</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <form id="announcementUpdateForm">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">حالة النشر</label>
              <select class="form-select" name="status" id="ad_status">
                <option value="Published" <?php echo ($announcement['status'] ?? '') === 'Published' ? 'selected' : ''; ?>>منشور ومفعل</option>
                <option value="Draft" <?php echo ($announcement['status'] ?? '') === 'Draft' ? 'selected' : ''; ?>>مسودة (إخفاء)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">نوع الإعلان</label>
              <select class="form-select" name="type" id="ad_type" onchange="toggleAdFields()">
                <option value="text" <?php echo ($announcement['type'] ?? 'text') === 'text' ? 'selected' : ''; ?>>نص ديناميكي</option>
                <option value="image" <?php echo ($announcement['type'] ?? '') === 'image' ? 'selected' : ''; ?>>صورة إعلانية (بانر)</option>
              </select>
            </div>

            <!-- حقول النص -->
            <div class="col-12 ad-text-field">
              <label class="form-label fw-bold">نص الإعلان</label>
              <input type="text" class="form-control" name="announcement_text" value="<?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?>">
            </div>
            <div class="col-md-4 ad-text-field">
              <label class="form-label">لون الخلفية</label>
              <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo htmlspecialchars($announcement['bg_color'] ?? '#000000'); ?>">
            </div>
            <div class="col-md-4 ad-text-field">
              <label class="form-label">لون النص</label>
              <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo htmlspecialchars($announcement['text_color'] ?? '#ffffff'); ?>">
            </div>
            <div class="col-md-4 ad-text-field">
              <label class="form-label">حجم الخط (بكسل)</label>
              <input type="number" class="form-control" name="font_size" value="<?php echo (int)($announcement['font_size'] ?? 16); ?>">
            </div>

            <!-- حقول الصورة -->
            <div class="col-12 ad-image-field d-none">
              <label class="form-label fw-bold">رفع بنر الإعلان</label>
              <input type="file" class="form-control" name="announcement_image" accept="image/*">
              <label class="form-label mt-2">النص البديل للصورة (SEO Alt Text)</label>
              <input type="text" class="form-control" name="alt_text" value="<?php echo htmlspecialchars($announcement['alt_text'] ?? 'إعلان'); ?>">
            </div>

            <!-- حقول الرابط والجدولة الزمنية المعقدة (المطلوبة تجارياً) -->
            <div class="col-md-8">
              <label class="form-label fw-bold">رابط التوجيه (اختياري)</label>
              <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['link'] ?? ''); ?>" placeholder="https://example.com">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">طريقة الفتح</label>
              <select class="form-select" name="open_new_tab">
                <option value="0" <?php echo ($announcement['open_new_tab'] ?? 0) == 0 ? 'selected' : ''; ?>>نفس التبويب</option>
                <option value="1" <?php echo ($announcement['open_new_tab'] ?? 0) == 1 ? 'selected' : ''; ?>>تبويب جديد (_blank)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">تاريخ بدء العرض تلقائياً</label>
              <input type="datetime-local" class="form-control" name="start_date" value="<?php echo htmlspecialchars($announcement['start_date'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">تاريخ انتهاء العرض تلقائياً</label>
              <input type="datetime-local" class="form-control" name="end_date" value="<?php echo htmlspecialchars($announcement['end_date'] ?? ''); ?>">
            </div>
          </div>
          <div id="adUpdateSpinner" class="spinner-border text-primary d-none my-2 d-block mx-auto" role="status"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary" id="saveAdBtn">تحديث الإعلان</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 3. سكربتات التحكم والاتصال بالسيرفر عبر AJAX -->
<!-- ========================================== -->
<script>
// دالة التحكم بتبديل حقول الإعلان بناءً على النوع (نص أو صورة)
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

// تشغيل الفحص عند تحميل الصفحة لأول مرة لتجهيز الواجهة
document.addEventListener('DOMContentLoaded', toggleAdFields);

// أولاً: معالجة رفع اللوجو بأمان
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
            alert('خطأ أمني: ' + data.error); 
            saveBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ بالاتصال بالسيرفر الألماني.');
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    });
});

// ثانياً: معالجة تحديث بيانات وجدولة الإعلان
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
            alert('خطأ في معالجة البيانات: ' + data.error); 
            saveBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    })
    .catch(err => {
        console.error(err);
        alert('حدث خطأ أثناء الاتصال بالخادم الرئيسي.');
        saveBtn.disabled = false;
        spinner.classList.add('d-none');
    });
});
</script>
