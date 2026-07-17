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

<!-- مودل السوشيال ميديا - التصميم المتراص الاحترافي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            
            <!-- Header -->
            <div class="modal-header px-4 py-3 border-bottom border-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-share-fill" style="color: var(--primary);"></i> 
                    إدارة منصات التواصل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <form id="socialLinksForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    
                    <div id="socialRowsContainer" class="d-flex flex-column gap-2">
                        <?php foreach (($data['social_links'] ?? []) as $index => $link): ?>
                        <div class="card p-3 border-0" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;" id="row_<?php echo $index; ?>">
                            <div class="row align-items-center g-3">
                                <!-- الصورة -->
                                <div class="col-auto">
                                    <div class="rounded-2 border d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #fff;">
                                        <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? '') . '?' . time(); ?>" class="img-fluid" style="width: 28px; height: 28px; object-fit: contain;">
                                    </div>
                                </div>
                                
                                <!-- منطقة الإدخال -->
                                <div class="col">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm shadow-none" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="اسم المنصة">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="url" class="form-control form-control-sm shadow-none" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="file" class="form-control form-control-sm shadow-none" name="social_img_<?php echo $index; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- الحذف -->
                                <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                                <div class="col-auto">
                                    <button type="button" class="btn-icon-trash shadow-sm" style="width: 38px; height: 38px;" onclick="removeSocialRow('row_<?php echo $index; ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600; font-size: 0.9rem;" onclick="addSocialRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة منصة جديدة
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer px-4 py-3 border-top border-light bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="socialLinksForm" class="btn-premium px-4 py-2 shadow-sm">
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // التأكد من أن دالة الإضافة تستخدم نفس الستايل الجديد
    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const index = socialCount;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;';
        div.id = 'row_' + index;
        div.innerHTML = `
            <div class="row align-items-center g-3">
                <div class="col-auto"><div class="rounded-2 border bg-white" style="width:50px; height:50px;"></div></div>
                <div class="col">
                    <div class="row g-2">
                        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="social[${index}][name]" placeholder="اسم المنصة"></div>
                        <div class="col-md-6"><input type="url" class="form-control form-control-sm" name="social[${index}][url]" placeholder="الرابط"></div>
                        <div class="col-md-3"><input type="file" class="form-control form-control-sm" name="social_img_${index}"></div>
                    </div>
                </div>
                <div class="col-auto"><button type="button" class="btn-icon-trash" style="width:38px; height:38px;" onclick="removeSocialRow('row_${index}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        socialCount++;
    }
</script>




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
                    <div class="mb-3 overflow-hidden">
                        <label class="form-label text-muted small">اختيار شعار جديد:</label>
                        <input type="file" class="form-control w-100 h-100 overflow-hidden"name="logo_img" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium w-100">حفظ الشعار</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. مودل الإعلان (الإصدار الاحترافي الكامل) -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary"></i> إدارة الإعلان التفاعلي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_announcement">
                    
                    <!-- قسم الحالة والتوقيت -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">الحالة</label>
                            <select class="form-select" name="status">
                                <option value="Draft" <?php echo (($announcement['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مسودة (مخفي)</option>
                                <option value="Published" <?php echo (($announcement['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشر (ظاهر)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">تاريخ البدء</label>
                            <input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['start_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">تاريخ الانتهاء</label>
                            <input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['end_date'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- نوع المحتوى -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">نوع المحتوى</label>
                        <select class="form-select" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك</option>
                            <option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (Banner)</option>
                        </select>
                    </div>

                    <!-- محرر النص -->
                    <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">نص الإعلان</label>
                            <textarea class="form-control" name="announcement_text" rows="3" placeholder="اكتب محتوى الإعلان هنا..."><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small">لون الخلفية</label>
                                <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo $announcement['announcement']['bg_color'] ?? '#f1f5f9'; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">لون النص</label>
                                <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo $announcement['announcement']['text_color'] ?? '#1e293b'; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">حجم الخط (px)</label>
                                <input type="number" class="form-control" name="font_size" value="<?php echo $announcement['announcement']['font_size'] ?? '16'; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- محرر الصورة -->
                    <div id="imageEditor" class="mb-4 <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                        <label class="form-label small fw-bold">صورة الإعلان</label>
                        <input type="file" class="form-control" name="ad_image">
                    </div>

                    <!-- الرابط -->
                    <div class="pt-3 border-top">
                        <label class="form-label small fw-bold">رابط التوجيه (Link)</label>
                        <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['announcement']['link'] ?? ''); ?>" placeholder="https://example.com">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="open_new_tab" value="1" id="newTabCheck" <?php echo (($announcement['announcement']['open_new_tab'] ?? 0) == 1 ? 'checked' : ''); ?>>
                            <label class="form-check-label small" for="newTabCheck">فتح في علامة تبويب جديدة</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="announcementEditForm" class="btn-premium w-100">حفظ الإعدادات</button>
            </div>
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
