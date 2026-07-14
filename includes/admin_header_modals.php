<?php
// جدار حماية لمنع الدخول المباشر
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<!-- التنسيقات العامة للنماذج (Enterprise Design System) -->
<style>
  :root { --bs-border-radius-lg: 12px; }
  .custom-modal .modal-content { border: none !important; border-radius: var(--bs-border-radius-lg) !important; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1) !important; }
  .custom-modal .modal-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; color: #fff !important; padding: 20px 24px !important; }
  .social-item-row { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
  .btn-enterprise { padding: 10px 24px; font-weight: 600; border-radius: 8px; }
  .form-label { font-weight: 600; color: #1e293b; margin-bottom: 8px; }
</style>

<!-- 1. مودل إدارة قنوات التواصل الاجتماعي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse">
        <h5 class="modal-title fw-bold">🌐 إدارة قنوات التواصل الاجتماعي</h5>
        <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        <div class="text-end mb-4">
          <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-2 rounded-3" onclick="addNewSocialRow()">➕ إضافة منصة جديدة</button>
        </div>
        <form id="socialLinksForm">
          <div id="socialRowsContainer" style="direction: rtl;">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #3b82f6;">
                <div class="row align-items-center g-2 text-end">
                  <div class="col-auto"><img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 40px; height: 40px; object-fit: contain;"></div>
                  <div class="col-md-2"><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($link['name']); ?>" required><input type="hidden" name="old_img" value="<?php echo htmlspecialchars($link['img']); ?>"></div>
                  <div class="col-md-4"><input type="url" class="form-control" name="url" value="<?php echo htmlspecialchars($link['url']); ?>" dir="ltr" required></div>
                  <div class="col-md-3"><input type="file" class="form-control" name="new_img" accept="image/*"></div>
                  <div class="col-md-2"><button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.social-item-row').remove()">حذف</button></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
      <div class="modal-footer p-3 bg-light rounded-bottom flex-row-reverse">
        <button type="submit" form="socialLinksForm" class="btn btn-primary btn-enterprise">حفظ التغييرات</button>
      </div>
    </div>
  </div>
</div>

<!-- 2. مودل تعديل الشعار -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">تعديل الشعار</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <form id="logoUpdateForm">
          <label class="form-label">اختر شعاراً جديداً</label>
          <input type="file" class="form-control mb-3" accept="image/png, image/webp">
          <button type="submit" class="btn btn-primary btn-enterprise w-100">تحديث الشعار</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 3. مودل تعديل الإعلان -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">إدارة الإعلان العلوي</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <form id="announcementUpdateForm">
          <div class="row g-3">
            <div class="col-12"><label class="form-label">نص الإعلان</label><textarea class="form-control" rows="3"></textarea></div>
            <div class="col-md-6"><label class="form-label">لون الخلفية</label><input type="color" class="form-control form-control-color w-100" value="#f1f5f9"></div>
            <div class="col-md-6"><label class="form-label">رابط التوجيه</label><input type="url" class="form-control" placeholder="https://..."></div>
          </div>
          <div class="text-end mt-4"><button type="submit" class="btn btn-primary btn-enterprise">حفظ الإعلان</button></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 4. مودل إدارة القائمة -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">إدارة روابط القائمة</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="text-secondary small"><tr><th>اسم الرابط</th><th>المسار</th><th>الترتيب</th><th>إجراء</th></tr></thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" value="الرئيسية"></td>
                <td><input type="text" class="form-control" value="index.php"></td>
                <td style="width: 80px;"><input type="number" class="form-control" value="1"></td>
                <td><button class="btn btn-outline-danger">حذف</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <button class="btn btn-outline-primary w-100 mt-2">+ إضافة رابط جديد</button>
      </div>
    </div>
  </div>
</div>

<script>
// Logic for Social Links
document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    const rows = document.querySelectorAll('.social-item-row');
    rows.forEach((row, index) => {
        formData.append(`social[${index}][name]`, row.querySelector('input[name="name"]').value);
        formData.append(`social[${index}][url]`, row.querySelector('input[name="url"]').value);
        formData.append(`social[${index}][old_img]`, row.querySelector('input[name="old_img"]').value);
        const fileInput = row.querySelector('input[name="new_img"]');
        if (fileInput && fileInput.files.length > 0) formData.append(`social_img_${index}`, fileInput.files[0]);
    });
    fetch('<?php echo $path_prefix; ?>admin/api/update_social_links.php', { method: 'POST', body: formData })
    .then(r => r.json()).then(data => { if(data.success) location.reload(); else alert('Error: ' + data.error); });
});

function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #10b981;">
        <div class="row align-items-center g-2 text-end">
          <div class="col-auto"><div style="width:40px;height:40px;background:#eee;"></div></div>
          <div class="col-md-2"><input type="text" class="form-control" name="name" placeholder="الاسم" required></div>
          <div class="col-md-4"><input type="url" class="form-control" name="url" placeholder="الرابط" dir="ltr" required></div>
          <div class="col-md-3"><input type="file" class="form-control" name="new_img" required></div>
          <div class="col-md-2"><button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.social-item-row').remove()">حذف</button></div>
        </div>
      </div>`);
}
</script>
