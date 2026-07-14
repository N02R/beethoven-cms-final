<?php
// جدار حماية لمنع الدخول المباشر
if (!isset($is_admin) || $is_admin !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<!-- المودل بستايل أوروبي فاخر -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); border-radius: 24px;">
      
      <div class="modal-header border-0 p-4">
        <h5 class="modal-title fw-bold text-dark" style="letter-spacing: -0.5px;">🌐 إدارة قنوات التواصل الاجتماعي</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <span class="text-muted small">قم بتحديث روابط منصاتك بأسلوب عصري وانسيابي</span>
            <button type="button" class="btn btn-dark btn-sm rounded-pill px-4 py-2" onclick="addNewSocialRow()">➕ إضافة منصة</button>
        </div>

        <form id="socialLinksForm">
          <div id="socialRowsContainer">
            <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
              <div class="card p-3 mb-3 border-0 rounded-4 shadow-sm align-items-center social-item-row" style="background: #f8fafc; border-right: 5px solid #6366f1;">
                <div class="row w-100 align-items-center g-3 text-end">
                  <div class="col-auto">
                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 45px; height: 45px; object-fit: contain; border-radius: 10px;">
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control bg-white border-0 shadow-none" name="name" value="<?php echo htmlspecialchars($link['name']); ?>" placeholder="الاسم" required>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($link['img']); ?>">
                  </div>
                  <div class="col-md-4">
                    <input type="url" class="form-control bg-white border-0 shadow-none" name="url" value="<?php echo htmlspecialchars($link['url']); ?>" dir="ltr" placeholder="الرابط" required>
                  </div>
                  <div class="col-md-3">
                    <input type="file" class="form-control bg-white border-0 shadow-none" name="new_img" accept="image/*">
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-link text-danger text-decoration-none" onclick="this.closest('.social-item-row').remove()">حذف</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
      
      <div class="modal-footer border-0 p-4">
        <button type="submit" form="socialLinksForm" class="btn btn-primary btn-lg rounded-pill px-5">حفظ التغييرات</button>
      </div>
    </div>
  </div>
</div>

<style>
/* لمسات أوروبية إضافية */
.custom-modal .modal-content { transition: transform 0.3s ease; }
.form-control:focus { box-shadow: 0 0 0 2px #6366f1 !important; }
.btn-link:hover { opacity: 0.7; }
</style>

<script>
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
    .then(data => {
        if(data.success) { location.reload(); } 
        else { alert('خطأ: ' + data.error); }
    });
});

function addNewSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    container.insertAdjacentHTML('beforeend', `
      <div class="card p-3 mb-3 border-0 rounded-4 shadow-sm align-items-center social-item-row" style="background: #f8fafc; border-right: 5px solid #10b981;">
        <div class="row w-100 align-items-center g-3 text-end">
          <div class="col-auto"><div style="width: 45px; height: 45px; background:#e2e8f0; border-radius:10px;"></div></div>
          <div class="col-md-2"><input type="text" class="form-control bg-white border-0 shadow-none" name="name" placeholder="الاسم" required></div>
          <div class="col-md-4"><input type="url" class="form-control bg-white border-0 shadow-none" name="url" placeholder="الرابط" dir="ltr" required></div>
          <div class="col-md-3"><input type="file" class="form-control bg-white border-0 shadow-none" name="new_img" accept="image/*" required></div>
          <input type="hidden" name="old_img" value="assets/img/socialicons/default.png">
          <div class="col-md-2"><button type="button" class="btn btn-link text-danger text-decoration-none" onclick="this.closest('.social-item-row').remove()">حذف</button></div>
        </div>
      </div>`);
}
</script>
