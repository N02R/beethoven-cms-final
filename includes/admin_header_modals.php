<?php
// جدار حماية لمنع الدخول المباشر
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<!-- التنسيقات -->
<style>
  .custom-modal .modal-content { border: none !important; border-radius: 16px !important; box-shadow: 0 15px 50px rgba(0,0,0,0.2) !important; }
  .custom-modal .modal-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; color: #fff !important; padding: 20px 24px !important; }
  .social-item-row { transition: all 0.3s ease; }
</style>

<!-- 1. مودل إدارة قنوات التواصل الاجتماعي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse">
        <h5 class="modal-title fw-bold">🌐 إدارة قنوات التواصل الاجتماعي</h5>
        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        <div class="text-start mb-4">
          <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm" onclick="addNewSocialRow()">➕ إضافة منصة جديدة</button>
        </div>
        <form id="socialLinksForm">
          <div id="socialRowsContainer" style="direction: rtl;">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #3b82f6;">
                <div class="row align-items-center g-2 text-end">
                  <div class="col-auto">
                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 40px; height: 40px; object-fit: contain;">
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($link['name']); ?>" required>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($link['img']); ?>">
                  </div>
                  <div class="col-md-4">
                    <input type="url" class="form-control form-control-sm" name="url" value="<?php echo htmlspecialchars($link['url']); ?>" dir="ltr" required>
                  </div>
                  <div class="col-md-3">
                    <input type="file" class="form-control form-control-sm" name="new_img" accept="image/*">
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.social-item-row').remove()">🗑️ حذف</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
      <div class="modal-footer p-3 bg-light rounded-bottom flex-row-reverse">
        <button type="submit" form="socialLinksForm" class="btn btn-primary px-4 fw-bold">حفظ التغييرات</button>
      </div>
    </div>
  </div>
</div>

<!-- 2. مودل تعديل الشعار -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">تعديل الشعار</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><p>هنا سيتم وضع نموذج رفع الشعار الجديد.</p></div>
    </div>
  </div>
</div>

<!-- 3. مودل تعديل الإعلان -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">إدارة الإعلان العلوي</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><p>هنا سيتم وضع نموذج تعديل نصوص وألوان الإعلان.</p></div>
    </div>
  </div>
</div>

<!-- 4. مودل إدارة القائمة -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header flex-row-reverse"><h5 class="modal-title">إدارة روابط القائمة</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><p>هنا سيتم وضع نموذج ترتيب وتعديل القائمة.</p></div>
    </div>
  </div>
</div>

<!-- السكربتات -->
<script>
// سكربت السوشيال ميديا الخاص بك
document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    const rows = document.querySelectorAll('.social-item-row');
    rows.forEach((row, index) => {
        formData.append(`social[${index}][name]`, row.querySelector('input[name="name"]').value);
        formData.append(`social[${index}][url]`, row.querySelector('input[name="url"]').value);
        formData.append(`social[${index}][old_img]`, row.querySelector('input[name="old_img"]').value);
        const fileInput = row.querySelector('input[name="new_img"]');
        if (fileInput && fileInput.files.length > 0) {
            formData.append(`social_img_${index}`, fileInput.files[0]);
        }
    });
    fetch('<?php echo $path_prefix; ?>admin/api/update_social_links.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if(data.success) { location.reload(); } else { alert('خطأ: ' + data.error); } });
});

function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 border-0 shadow-sm rounded-3 social-item-row" style="border-right: 4px solid #10b981;">
        <div class="row align-items-center g-2 text-end">
          <div class="col-auto"><div style="width: 40px; height: 40px; background:#eee;"></div></div>
          <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="name" placeholder="الاسم" required></div>
          <div class="col-md-4"><input type="url" class="form-control form-control-sm" name="url" placeholder="الرابط" dir="ltr" required></div>
          <div class="col-md-3"><input type="file" class="form-control form-control-sm" name="new_img" accept="image/*" required></div>
          <input type="hidden" name="old_img" value="assets/img/socialicons/default.png">
          <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.social-item-row').remove()">🗑️ حذف</button></div>
        </div>
      </div>`);
}
</script>
