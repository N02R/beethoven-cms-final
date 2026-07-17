<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }
?>

<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --bg-soft: #f8fafc;
        --border-color: #dbeafe;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.1);
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius: 20px;
    }

    /* تحسين المودل */
    .custom-modal .modal-content { border-radius: var(--radius); border: none; box-shadow: var(--shadow-md); overflow: hidden; }
    .custom-modal .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
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
    .custom-modal .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border-soft); background: #f8fafc; }
    
    /* حقول الإدخال الاحترافية */
    .custom-modal .form-control, .custom-modal .form-select { 
        border-radius: 12px; border: 1px solid var(--border-color); height: 48px; padding: 0 16px; transition: 0.2s; 
    }
    .custom-modal .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
    
    /* الأزرار */
    .btn-premium { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 12px; padding: 12px 24px; font-weight: 600; border: none; transition: 0.3s; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(37,99,235,0.3); color: white; }
    .btn-icon-trash { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #ef4444; border: none; }
    .btn-icon-trash:hover { background: #fecaca; }
    /* تنسيق احترافي لزر رفع الملفات */
.custom-modal .form-control[type="file"] {
    padding: 8px 12px !important;
    /* تقليل الحشو ليتناسب مع زر الرفع */
    background: #f8fafc;
    cursor: pointer;
}

/* لجعل زر الرفع يملأ العرض بالكامل بشكل جذاب */
.file-upload-wrapper {
    position: relative;
    width: 100%;
}
</style>

<!-- 1. مودل السوشيال ميديا -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-share-fill text-primary"></i> إدارة منصات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <div id="socialRowsContainer">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="card p-3 mb-3 border-0 bg-light" id="row_<?php echo $index; ?>">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="rounded border p-1 bg-white" style="width: 50px; height: 50px;">
                                        <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? '') . '?' . time(); ?>" class="img-fluid" style="width:100%; height:100%; object-fit:contain;">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row g-2">
                                        <div class="col-md-4"><input type="text" class="form-control" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="اسم المنصة"></div>
                                        <div class="col-md-8"><input type="url" class="form-control" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط"></div>
                                        <div class="col-12"><input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                                <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeSocialRow('row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary w-100 mt-2" onclick="addSocialRow()"><i class="bi bi-plus-circle"></i> إضافة منصة جديدة</button>
                </form>
            </div>
            <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn-premium w-100">حفظ التغييرات</button></div>
        </div>
    </div>
</div>

<!-- 2. مودل اللوجو (المُعدل) -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تغيير شعار الموقع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="logoEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_logo">
                    <!-- إضافة إطار جذاب للشعار الحالي -->
                    <div class="mb-4 text-center">
                        <div class="p-3 bg-light rounded border d-inline-block">
                            <img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" class="img-fluid" style="max-height: 100px; object-fit: contain;">
                        </div>
                    </div>
                    <!-- حقل الرفع مع كلاس يضمن ملء العرض -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">اختيار شعار جديد:</label>
                        <input type="file" class="form-control w-100" name="logo_img" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium w-100">حفظ الشعار</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. مودل الإعلان -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary"></i> إدارة الإعلان</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><label class="form-label">الحالة</label><select class="form-select" name="status"><option value="Draft" <?php echo (($announcement['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مسودة</option><option value="Published" <?php echo (($announcement['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشر</option></select></div>
                        <div class="col-md-4"><label class="form-label">البدء</label><input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['start_date'] ?? ''); ?>"></div>
                        <div class="col-md-4"><label class="form-label">الانتهاء</label><input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['end_date'] ?? ''); ?>"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">نوع المحتوى</label><select class="form-select" name="type" onchange="toggleAdContent(this.value)"><option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك</option><option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة</option></select></div>
                    <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                        <textarea class="form-control mb-3" name="announcement_text" rows="3"><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                    </div>
                    <div id="imageEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>"><input type="file" class="form-control mb-3" name="ad_image"></div>
                </form>
            </div>
            <div class="modal-footer"><button type="submit" form="announcementEditForm" class="btn-premium w-100">حفظ الإعدادات</button></div>
        </div>
    </div>
</div>

<script>
    function toggleAdContent(val) {
        document.getElementById('textEditor').classList.toggle('d-none', val !== 'text');
        document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image');
    }

    let socialCount = <?php echo count($data['social_links'] ?? []); ?>;
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 mb-3 border-0 bg-light';
        div.id = 'row_' + socialCount;
        div.innerHTML = `<div class="row align-items-center g-3"><div class="col"><input type="text" class="form-control" name="social[${socialCount}][name]" placeholder="اسم المنصة"></div><div class="col"><input type="url" class="form-control" name="social[${socialCount}][url]" placeholder="الرابط"></div><div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeSocialRow('row_${socialCount}')"><i class="bi bi-trash"></i></button></div></div>`;
        container.appendChild(div);
        socialCount++;
    }
    function removeSocialRow(id) { document.getElementById(id).remove(); }

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('admin/api/save_config.php', { method: 'POST', body: new FormData(this) })
            .then(r => r.json()).then(d => { if(d.success) location.reload(); else alert(d.message); });
        });
    });
</script>
