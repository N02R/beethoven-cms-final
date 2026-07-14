<?php
if (!isset($is_admin) || $is_admin !== true) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
?>

<style>
  :root { --bs-border-radius-lg: 12px; }
  .custom-modal .modal-content { border: none; border-radius: var(--bs-border-radius-lg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
  .custom-modal .modal-header{
    background:#f8fafc;
    border-bottom:1px solid #e2e8f0;
    padding:16px 24px;
    display:flex;
    align-items:center;
}


.custom-modal .btn-close{
    margin-inline-start:auto; /* مسافة جميلة بين العنوان والزر */
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
    <h5 class="modal-title mb-0">
        <i class="bi bi-share"></i>
        إدارة منصات التواصل
    </h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-megaphone"></i> إعدادات الإعلان</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form>
          <label class="form-label">نوع الإعلان</label>
          <select class="form-select mb-3" onchange="toggleAdContent(this.value)">
            <option value="text">نص إعلاني</option>
            <option value="image">صورة إعلانية</option>
          </select>
          <div id="textEditor"><textarea class="form-control" rows="3" placeholder="اكتب نص الإعلان..."></textarea></div>
          <div id="imageEditor" class="d-none"><input type="file" class="form-control"></div>
        </form>
      </div>
      <div class="modal-footer"><button class="btn btn-primary btn-enterprise">حفظ التغييرات</button></div>
    </div>
  </div>
</div>

<!-- 4. مودل القائمة -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        <h5 class="modal-title"><i class="bi bi-list"></i> إدارة القائمة</h5>
      </div>
      <div class="modal-body p-4">
        <table class="table align-middle">
          <thead><tr><th>الاسم</th><th>الرابط</th><th>الترتيب</th><th>حذف</th></tr></thead>
          <tbody><tr><td><input type="text" class="form-control form-control-sm"></td><td><input type="text" class="form-control form-control-sm"></td><td style="width:80px"><input type="number" class="form-control form-control-sm"></td><td><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></td></tr></tbody>
        </table>
        <button class="btn btn-sm btn-outline-primary w-100">+ إضافة رابط</button>
      </div>
    </div>
  </div>
</div>

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
