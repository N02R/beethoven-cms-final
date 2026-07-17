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
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius: 20px;
    }

    /* Modal Structure */
    .custom-modal .modal-content { border-radius: var(--radius); border: none; box-shadow: var(--shadow-md); overflow: hidden; }
    .custom-modal .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--border-color); }
    .custom-modal .modal-title { margin: 0; display: flex; align-items: center; gap: .5rem; font-weight: 700; color: #1e293b; }
    .custom-modal .btn-close { margin: 0; flex-shrink: 0; }
    .custom-modal .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border-color); background: var(--bg-soft); }
    
    /* Inputs */
    .custom-modal .form-control, .custom-modal .form-select { border-radius: 12px; border: 1px solid var(--border-color); height: 48px; padding: 0 16px; transition: 0.2s; width: 100%; }
    .custom-modal .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
    
    /* File Upload - الموحد */
    .custom-modal input[type="file"].form-control { padding: 10px 16px; background: #fff; cursor: pointer; width: 100%; }

    /* Buttons */
    .btn-premium { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 12px; padding: 12px 24px; font-weight: 600; border: none; transition: 0.3s; width: 100%; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(37,99,235,0.3); color: white; }
    .btn-icon-trash { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #ef4444; border: none; }
    .btn-icon-trash:hover { background: #fecaca; }
</style>

<!-- 1. Social Links Modal -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-share-fill text-primary"></i> إدارة منصات التواصل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="row_<?php echo $index; ?>">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-2 border bg-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? '') . '?' . time(); ?>" style="width: 28px; height: 28px; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="الاسم"></div>
                                        <div class="col-md-8"><input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط"></div>
                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col"><input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>"></div>
                                        <div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeSocialRow('row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button></div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addSocialRow()"><i class="bi bi-plus-circle me-1"></i> إضافة منصة جديدة</button>
                </form>
            </div>
            <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn-premium">حفظ التغييرات</button></div>
        </div>
    </div>
</div>

<!-- 2. Logo Modal -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-image text-primary"></i> تغيير شعار الموقع</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 text-center">
                <form id="logoEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_logo">
                    <div class="mb-4"><div class="p-3 bg-white rounded border d-inline-block"><img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" style="max-height: 100px;"></div></div>
                    <input type="file" class="form-control w-100" name="logo_img" required>
                </form>
            </div>
            <div class="modal-footer"><button type="submit" form="logoEditForm" class="btn-premium">حفظ الشعار</button></div>
        </div>
    </div>
</div>

<!-- 3. المودل المحدث للإعلان (UX محسّن) -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary"></i> إعدادات لوحة الإعلانات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    
                    <!-- المجموعة 1: حالة الإعلان -->
                    <div class="card p-3 mb-4 border-0" style="background: #f1f5f9;">
                        <div class="section-label"><i class="bi bi-gear"></i> حالة الإعلان والتوقيت</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold">حالة العرض</label>
                                <select class="form-select" name="status">
                                    <option value="Draft" <?php echo (($announcement['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مخفي (مسودة)</option>
                                    <option value="Published" <?php echo (($announcement['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشط (يظهر للزوار)</option>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="small fw-bold">تاريخ البدء</label><input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['start_date'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="small fw-bold">تاريخ الانتهاء</label><input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['end_date'] ?? ''); ?>"></div>
                        </div>
                    </div>

                    <!-- المجموعة 2: محتوى الإعلان -->
                    <div class="card p-3 mb-4 border" style="border-color: var(--border-color);">
                        <div class="section-label"><i class="bi bi-pencil-square"></i> محتوى الإعلان</div>
                        <label class="small mb-2">نوع الإعلان:</label>
                        <select class="form-select mb-3" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك (اختر هذا لنص سريع)</option>
                            <option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (بانر دعائي كامل)</option>
                        </select>

                        <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                            <label class="small mb-1">نص الإعلان (الرسالة التي ستظهر للزوار):</label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4"><label class="small">لون الخلفية</label><input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo $announcement['announcement']['bg_color'] ?? '#f1f5f9'; ?>"></div>
                                <div class="col-4"><label class="small">لون الخط</label><input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo $announcement['announcement']['text_color'] ?? '#1e293b'; ?>"></div>
                                <div class="col-4"><label class="small">حجم الخط</label><input type="number" class="form-control" name="font_size" value="<?php echo $announcement['announcement']['font_size'] ?? '16'; ?>"></div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                            <label class="small mb-1">ارفع صورة الإعلان (يُفضل صيغة WebP أو PNG):</label>
                            <input type="file" class="form-control" name="ad_image">
                        </div>
                    </div>

                    <!-- المجموعة 3: التوجيه -->
                    <div class="section-label"><i class="bi bi-link-45deg"></i> رابط التوجيه (اختياري)</div>
                    <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['announcement']['link'] ?? ''); ?>" placeholder="https://">
                </form>
            </div>
            <div class="modal-footer"><button type="submit" form="announcementEditForm" class="btn-premium">حفظ التغييرات وتحديث الموقع</button></div>
        </div>
    </div>
</div>

<script>
    function toggleAdContent(val) { document.getElementById('textEditor').classList.toggle('d-none', val !== 'text'); document.getElementById('imageEditor').classList.toggle('d-none', val !== 'image'); }
    
    let socialCount = <?php echo count($data['social_links'] ?? []); ?>;
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'row_' + socialCount;
        div.innerHTML = `<div class="d-flex align-items-start gap-3"><div class="rounded-2 border bg-white" style="width:50px; height:50px;"></div><div class="flex-grow-1"><div class="row g-2 mb-2"><div class="col-4"><input type="text" class="form-control form-control-sm" name="social[${socialCount}][name]" placeholder="الاسم"></div><div class="col-8"><input type="url" class="form-control form-control-sm" name="social[${socialCount}][url]" placeholder="الرابط"></div></div><div class="row g-2 align-items-center"><div class="col"><input type="file" class="form-control form-control-sm" name="social_img_${socialCount}"></div><div class="col-auto"><button type="button" class="btn-icon-trash" onclick="removeSocialRow('row_${socialCount}')"><i class="bi bi-trash"></i></button></div></div></div></div>`;
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
