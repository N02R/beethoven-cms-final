<?php
if (!isset($is_admin) || $is_admin !== true) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
?>

<style>
  :root { --bs-border-radius-lg: 12px; }
  .custom-modal .modal-content { border: none; border-radius: var(--bs-border-radius-lg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
.custom-modal .modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 24px;
  background: #f8fafc;
}

.custom-modal .modal-title {
  margin: 0;
  display: flex;
  align-items: center;
  gap: .5rem;
  font-weight: 700;
  color: #1e293b;
}

.custom-modal .btn-close {
  margin: 0;
  flex-shrink: 0;
}
  .social-item-row { transition: 0.3s; border: 1px solid #e2e8f0; }
  .social-item-row:hover { border-color: #3b82f6; }
  .btn-enterprise { padding: 8px 20px; font-weight: 600; }
</style>

<!-- 1. مودل التواصل الاجتماعي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
<div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="إغلاق"></button>

    <h5 class="modal-title">
        <i class="bi bi-share ms-2"></i>
        إدارة منصات التواصل
    </h5>
</div>
      <div class="modal-body p-4">
        <form id="socialLinksForm">
          <div id="socialRowsContainer">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 social-item-row">
                <div class="row g-2 align-items-center">
                  <div class="col-auto"><img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 32px;"></div>
                  <div class="col"><input type="text" class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($link['name']); ?>"></div>
                  <div class="col"><input type="url" class="form-control form-control-sm" name="url" value="<?php echo htmlspecialchars($link['url']); ?>"></div>
                  <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.social-item-row').remove()"><i class="bi bi-trash"></i></button></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn btn-sm btn-success mt-2" onclick="addNewSocialRow()"><i class="bi bi-plus-lg"></i> إضافة منصة</button>
        </form>
      </div>
      <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn btn-primary btn-enterprise">حفظ التغييرات</button></div>
    </div>
  </div>
</div>

<!-- 2. مودل تعديل الشعار -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-image"></i> تحديث شعار الموقع</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <p class="text-muted small">الشعار الحالي:</p>
        <img src="<?php echo $logo_path ?? '#'; ?>" class="mb-4" style="max-height: 60px;">
        <input type="file" class="form-control" name="new_logo">
      </div>
      <div class="modal-footer"><button class="btn btn-primary btn-enterprise">رفع الشعار الجديد</button></div>
    </div>
  </div>
</div>

<!-- 3. مودل الإعلان -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        <h5 class="modal-title"><i class="bi bi-megaphone"></i> إدارة الإعلان العلوي</h5>
      </div>
      
      <div class="modal-body p-4">
        <form id="announcementForm">
          <!-- الحالة والنوع -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">حالة الإعلان</label>
              <select class="form-select" name="status">
                <option value="Published">منشور (Published)</option>
                <option value="Draft">مسودة (Draft)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">نوع المحتوى</label>
              <select class="form-select" name="type" onchange="toggleAdContent(this.value)">
                <option value="text">نصي</option>
                <option value="image">صورة</option>
              </select>
            </div>
          </div>

          <!-- منطقة المحتوى (متغيرة) -->
          <div id="contentSection" class="mb-3">
            <div id="textEditor">
              <label class="form-label">نص الإعلان</label>
              <textarea class="form-control" name="announcement_text" rows="2"></textarea>
            </div>
            <div id="imageEditor" class="d-none">
              <label class="form-label">صورة البانر</label>
              <input type="file" class="form-control" name="announcement_image">
            </div>
          </div>

          <!-- تنسيقات النص (تظهر فقط في حالة النص) -->
          <div class="row g-3 mb-3" id="textStyleSettings">
            <div class="col-md-4">
              <label class="form-label">لون الخلفية</label>
              <input type="color" class="form-control form-control-color w-100" name="bg_color" value="#0056b3">
            </div>
            <div class="col-md-4">
              <label class="form-label">لون النص</label>
              <input type="color" class="form-control form-control-color w-100" name="text_color" value="#ffffff">
            </div>
            <div class="col-md-4">
              <label class="form-label">حجم الخط (px)</label>
              <input type="number" class="form-control" name="font_size" value="16">
            </div>
          </div>

          <!-- روابط وجدولة -->
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label">رابط التوجيه (URL)</label>
              <input type="url" class="form-control" name="link" placeholder="https://...">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="open_new_tab" value="1" id="newTabCheck">
                <label class="form-check-label" for="newTabCheck">فتح في علامة تبويب جديدة</label>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">تاريخ البدء</label>
              <input type="datetime-local" class="form-control" name="start_date">
            </div>
            <div class="col-md-6">
              <label class="form-label">تاريخ الانتهاء</label>
              <input type="datetime-local" class="form-control" name="end_date">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
        <button type="submit" form="announcementForm" class="btn btn-primary btn-enterprise">حفظ الإعلان</button>
      </div>
    </div>
  </div>
</div>

<script>
function toggleAdContent(val) {
    document.getElementById('textEditor').classList.toggle('d-none', val !== 'text');
    document.getElementById('textStyleSettings').classList.toggle('d-none', val !== 'text');
    document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image');
}
</script>


<!-- 4. مودل القائمة -->
<!-- مودل إدارة قنوات التواصل الاجتماعي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        <h5 class="modal-title"><i class="bi bi-share"></i> إدارة منصات التواصل</h5>
      </div>
      
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        <form id="socialLinksForm">
          <div id="socialRowsContainer">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #3b82f6;">
                <div class="row g-2 align-items-center">
                  <!-- معاينة الصورة الحالية -->
                  <div class="col-auto">
                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                  </div>
                  
                  <!-- الحقول -->
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($link['name']); ?>" placeholder="اسم المنصة" required>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($link['img']); ?>">
                  </div>
                  <div class="col-md-4">
                    <input type="url" class="form-control" name="url" value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="الرابط" dir="ltr" required>
                  </div>
                  
                  <!-- رفع صورة جديدة وحذف -->
                  <div class="col-md-3">
                    <input type="file" class="form-control form-control-sm" name="new_img" accept="image/*" title="رفع أيقونة جديدة">
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.social-item-row').remove()" title="حذف المنصة">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
        
        <!-- زر الإضافة -->
        <button type="button" class="btn btn-success mt-2 w-100 fw-bold" onclick="addNewSocialRow()">
          <i class="bi bi-plus-lg"></i> إضافة منصة تواصل جديدة
        </button>
      </div>

      <div class="modal-footer">
        <button type="submit" form="socialLinksForm" class="btn btn-primary btn-enterprise px-4">حفظ كافة التغييرات</button>
      </div>
    </div>
  </div>
</div>

<script>
function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #10b981;">
        <div class="row g-2 align-items-center">
          <div class="col-auto"><div class="bg-light rounded" style="width:40px;height:40px;"></div></div>
          <div class="col-md-3"><input type="text" class="form-control" name="name" placeholder="اسم المنصة" required></div>
          <div class="col-md-4"><input type="url" class="form-control" name="url" placeholder="الرابط" dir="ltr" required></div>
          <div class="col-md-3"><input type="file" class="form-control form-control-sm" name="new_img" required></div>
          <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.social-item-row').remove()"><i class="bi bi-trash"></i></button></div>
        </div>
      </div>`);
}
</script>


<script>
function toggleAdContent(val) {
    document.getElementById('textEditor').classList.toggle('d-none', val !== 'text');
    document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image');
}
function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 social-item-row">
        <div class="row g-2 align-items-center">
          <div class="col-auto"><div style="width:32px;height:32px;background:#eee;border-radius:4px;"></div></div>
          <div class="col"><input type="text" class="form-control form-control-sm" placeholder="الاسم"></div>
          <div class="col"><input type="url" class="form-control form-control-sm" placeholder="الرابط"></div>
          <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.social-item-row').remove()"><i class="bi bi-trash"></i></button></div>
        </div>
      </div>`);
}
</script>
