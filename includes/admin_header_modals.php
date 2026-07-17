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
<!-- مودل السوشيال ميديا -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إدارة منصات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <div id="socialRowsContainer">
                        <?php 
                        // نستخدم $data['social_links'] لأننا في ملف المودلات نستخدم المتغير القادم من الـ header
                        $social_links = $data['social_links'] ?? [];
                        foreach ($social_links as $index => $link): ?>
                        <div class="card p-3 mb-2 social-item-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? ''); ?>" style="width: 32px; height: 32px;">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="اسم المنصة">
                                </div>
                                <div class="col">
                                    <input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط">
                                </div>
                                <!-- حقل مخفي لإرسال المسار القديم في حال لم يتم رفع صورة جديدة -->
                                <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                                <div class="col-auto" style="width: 140px;">
                                    <input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="socialLinksForm" class="btn btn-primary">حفظ روابط التواصل</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. مودل اللوجو -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">تغيير شعار الموقع</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form id="logoEditForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_logo">
                <div class="mb-3 text-center"><img src="<?php echo $path_prefix . ($announcement['site_logo_path'] ?? 'assets/img/logo.png'); ?>" style="max-width: 150px;"></div>
                <input type="file" class="form-control" name="logo_img" required>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="logoEditForm" class="btn btn-primary">حفظ الشعار</button></div>
    </div></div>
</div>

<!-- 3. مودل الإعلان (تم دمجه بالكامل) -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">إدارة الإعلان</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body p-4">
            <form id="announcementEditForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_announcement">
                <!-- الحقول المخفية للحفاظ على البيانات -->
                <input type="hidden" name="status" value="<?php echo $announcement['announcement']['status'] ?? 'Published'; ?>">
                <input type="hidden" name="start_date" value="<?php echo $announcement['announcement']['start_date'] ?? ''; ?>">
                <input type="hidden" name="end_date" value="<?php echo $announcement['announcement']['end_date'] ?? ''; ?>">

                <div class="mb-3">
                    <label class="form-label">نوع الإعلان</label>
                    <select class="form-select" name="type" onchange="toggleAdContent(this.value)">
                        <option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك</option>
                        <option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (بانر)</option>
                    </select>
                </div>
                
                <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                    <textarea class="form-control mb-2" name="announcement_text" placeholder="نص الإعلان"><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                    <div class="row g-2">
                        <div class="col"><label class="form-label">الخلفية</label><input type="color" class="form-control" name="bg_color" value="<?php echo $announcement['announcement']['bg_color'] ?? '#0056b3'; ?>"></div>
                        <div class="col"><label class="form-label">الخط</label><input type="color" class="form-control" name="text_color" value="<?php echo $announcement['announcement']['text_color'] ?? '#ffffff'; ?>"></div>
                    </div>
                    <input type="number" class="form-control mt-2" name="font_size" value="<?php echo $announcement['announcement']['font_size'] ?? '16'; ?>" placeholder="حجم الخط">
                </div>
                
                <div id="imageEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                    <input type="file" class="form-control mb-2" name="ad_image">
                </div>
                
                <input type="url" class="form-control mt-2" name="link" placeholder="الرابط" value="<?php echo htmlspecialchars($announcement['announcement']['link'] ?? ''); ?>">
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="announcementEditForm" class="btn btn-primary">حفظ الإعلان</button></div>
    </div></div>
</div>

<script>
    function toggleAdContent(val) {
        document.getElementById('textEditor').classList.toggle('d-none', val !== 'text');
        document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image');
    }

    // منطق موحد لجميع الفورمات (يرسل البيانات للملف المركزي save_config.php)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('admin/api/save_config.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    alert('تم الحفظ بنجاح!');
                    location.reload();
                } else {
                    alert('خطأ: ' + (data.message || 'حدثت مشكلة'));
                }
            });
        });
    });
</script>
