<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
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
                            <div class="col-auto"><img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? ''); ?>" style="width: 32px;"></div>
                            <div class="col"><input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>"></div>
                            <div class="col"><input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>"></div>
                            <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
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

<!-- 3. مودل الإعلان (المحدث ليتناسب مع API الجديد) -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">إدارة الإعلان</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form id="announcementEditForm" enctype="multipart/form-data">
                <!-- حقول مخفية للحفاظ على إعدادات النظام -->
                <input type="hidden" name="status" value="<?php echo $announcement['status'] ?? 'Published'; ?>">
                <input type="hidden" name="font_size" value="<?php echo $announcement['font_size'] ?? '16'; ?>">
                <input type="hidden" name="start_date" value="<?php echo $announcement['start_date'] ?? ''; ?>">
                <input type="hidden" name="end_date" value="<?php echo $announcement['end_date'] ?? ''; ?>">

                <div class="mb-3">
                    <label class="form-label">نوع الإعلان</label>
                    <select class="form-select" name="type" onchange="toggleAdContent(this.value)">
                        <option value="text" <?php echo (($announcement['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك</option>
                        <option value="image" <?php echo (($announcement['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (بانر)</option>
                    </select>
                </div>
                <div id="textEditor" class="<?php echo (($announcement['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                    <textarea class="form-control mb-2" name="announcement_text" placeholder="نص الإعلان"><?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?></textarea>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">الخلفية</label><input type="color" class="form-control" name="bg_color" value="<?php echo $announcement['bg_color'] ?? '#0056b3'; ?>"></div>
                        <div class="col"><label class="form-label">الخط</label><input type="color" class="form-control" name="text_color" value="<?php echo $announcement['text_color'] ?? '#ffffff'; ?>"></div>
                    </div>
                </div>
                <div id="imageEditor" class="<?php echo (($announcement['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                    <input type="file" class="form-control mb-2" name="ad_image">
                </div>
                <div class="mt-2">
                    <label class="form-label">رابط الإعلان</label>
                    <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['link'] ?? ''); ?>">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="open_new_tab" value="1" <?php echo (($announcement['open_new_tab'] ?? 0) == 1 ? 'checked' : ''); ?>>
                        <label class="form-check-label">فتح في تبويب جديد</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="announcementEditForm" class="btn btn-primary">حفظ التغييرات</button></div>
    </div></div>
</div>

<script>
    // تبديل العرض بين النص والصورة
    function toggleAdContent(val) {
        document.getElementById('textEditor').classList.toggle('d-none', val !== 'text');
        document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image');
    }

    // منطق إرسال إعدادات الإعلان
    document.getElementById('announcementEditForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin/api/update_config.php', { method: 'POST', body: new FormData(this) })
        .then(r => r.json()).then(data => {
            if(data.success) {
                alert('تم الحفظ بنجاح!');
                location.reload(); 
            } else { alert('حدث خطأ'); }
        });
    });

    // منطق السوشيال ميديا
    document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin/api/update_config.php', { method: 'POST', body: new FormData(this) })
        .then(r => r.json()).then(data => {
            if(data.success) { location.reload(); } else { alert('خطأ في السوشيال'); }
        });
    });

    // منطق اللوجو
    document.getElementById('logoEditForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin/api/update_logo.php', { method: 'POST', body: new FormData(this) })
        .then(r => r.json()).then(data => {
            if(data.success) { location.reload(); } else { alert('خطأ في اللوجو'); }
        });
    });
</script>
