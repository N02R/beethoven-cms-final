<?php
// جدار حماية إضافي
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<!-- التنسيقات (تم الاحتفاظ بها كما هي) -->
<style>
  .custom-modal .modal-content { border: none; border-radius: 16px; box-shadow: 0 15px 50px rgba(0,0,0,0.2); }
  .custom-modal .modal-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 20px 24px; }
  .preview-zone { border: 2px dashed #cbd5e1; padding: 20px; background: #f8fafc; }
</style>

<!-- 1. مودل تعديل اللوجو -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">✨ إدارة هوية الموقع</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="logoUploadForm"><div class="modal-body p-4">
        <div class="mb-4 text-center"><div class="preview-zone"><img id="currentLogoPreview" src="<?php echo $path_prefix . $site_logo_path; ?>" class="img-fluid" style="max-height: 90px;"></div></div>
        <input class="form-control" type="file" id="logoFileInput" name="logo" required>
      </div><div class="modal-footer"><button type="submit" class="btn btn-primary">تحديث الشعار</button></div></form>
    </div>
  </div>
</div>

<!-- 2. مودل تعديل الإعلان -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">📢 إدارة الإعلانات</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="announcementUpdateForm"><div class="modal-body p-4">
        <!-- (هنا تبقى حقول الإعلان كما كانت في ملفك السابق لضمان تطابق البيانات) -->
        <input type="text" class="form-control" name="announcement_text" value="<?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?>">
        <div class="mt-3"><button type="submit" class="btn btn-primary" id="saveAdBtn">حفظ التغييرات</button></div>
      </div></form>
    </div>
  </div>
</div>

<!-- 3. مودل إدارة السوشيال ميديا (محدث) -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">🌐 إدارة قنوات التواصل</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        <div class="text-start mb-4"><button type="button" class="btn btn-success btn-sm" onclick="addNewSocialRow()">➕ إضافة منصة</button></div>
        <form id="socialLinksForm">
          <div id="socialRowsContainer">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 social-item-row" style="border-right: 4px solid #3b82f6;">
                <div class="row align-items-center g-3">
                  <div class="col-md-3"><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($link['name']); ?>"></div>
                  <div class="col-md-5"><input type="url" class="form-control" name="url" value="<?php echo htmlspecialchars($link['url']); ?>"></div>
                  <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($link['img']); ?>">
                  <div class="col-md-2"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.social-item-row').remove()">🗑️ حذف</button></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
      <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn btn-primary" id="saveSocialBtn">حفظ بالكامل</button></div>
    </div>
  </div>
</div>

<script>
// السكربت المحدث لإرسال البيانات "النظيفة" فقط
document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    const rows = document.querySelectorAll('.social-item-row');
    
    rows.forEach((row, index) => {
        formData.append(`social[${index}][name]`, row.querySelector('input[name="name"]').value);
        formData.append(`social[${index}][url]`, row.querySelector('input[name="url"]').value);
        formData.append(`social[${index}][old_img]`, row.querySelector('input[name="old_img"]').value);
    });

    fetch('<?php echo $path_prefix; ?>admin/api/update_social_links.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if(data.success) location.reload(); else alert(data.error); });
});

function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 social-item-row" style="border-right: 4px solid #10b981;">
        <div class="row align-items-center g-3">
          <div class="col-md-3"><input type="text" class="form-control" name="name" placeholder="الاسم"></div>
          <div class="col-md-5"><input type="url" class="form-control" name="url" placeholder="الرابط"></div>
          <input type="hidden" name="old_img" value="assets/img/socialicons/default.png">
          <div class="col-md-2"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.social-item-row').remove()">🗑️ حذف</button></div>
        </div>
      </div>`);
}
</script>
