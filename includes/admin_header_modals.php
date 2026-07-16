<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<style>
    .custom-modal .modal-content { border: none; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .custom-modal .modal-header { padding: 18px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .social-item-row { transition: 0.3s; border: 1px solid #e2e8f0; margin-bottom: 10px; }
</style>

<!-- 1. مودل السوشيال ميديا -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">إدارة منصات التواصل</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form id="socialLinksForm" enctype="multipart/form-data">
                <div id="socialRowsContainer">
                    <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
                    <div class="card p-3 social-item-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto"><img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 32px;"></div>
                            <div class="col"><input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name']); ?>"></div>
                            <div class="col"><input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url']); ?>"></div>
                            <div class="col-auto" style="width: 120px;"><input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-sm btn-success mt-2" onclick="addNewSocialRow()"><i class="bi bi-plus-lg"></i> إضافة منصة</button>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn btn-primary">حفظ التغييرات</button></div>
    </div></div>
</div>

<!-- 2. مودل اللوجو -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">تغيير شعار الموقع</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form id="logoEditForm" enctype="multipart/form-data">
                <div class="mb-3 text-center"><img src="<?php echo $path_prefix . ($announcement['site_logo_path'] ?? 'assets/img/logo.png'); ?>" style="max-width: 150px;"></div>
                <input type="file" class="form-control" name="logo_img" required>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="logoEditForm" class="btn btn-primary">حفظ الشعار</button></div>
    </div></div>
</div>

<script>
    // --- منطق السوشيال ميديا ---
    function addNewSocialRow() {
        const index = Date.now();
        document.getElementById('socialRowsContainer').insertAdjacentHTML('beforeend', `<div class="card p-3 social-item-row"><input type="text" class="form-control mb-1" name="social[${index}][name]" placeholder="الاسم"><input type="url" class="form-control mb-1" name="social[${index}][url]" placeholder="الرابط"><input type="file" class="form-control" name="social_img_${index}"></div>`);
    }

    document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin/api/update_social_links.php', { method: 'POST', body: new FormData(this), credentials: 'include' })
        .then(r => r.json()).then(data => { if(data.success) location.reload(); else alert('خطأ'); });
    });

    // --- منطق اللوجو ---
    document.getElementById('logoEditForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin/api/update_logo.php', { method: 'POST', body: new FormData(this), credentials: 'include' })
        .then(r => r.json()).then(data => { if(data.success) location.reload(); else alert(data.error); });
    });
</script>
