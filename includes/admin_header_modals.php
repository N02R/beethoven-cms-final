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


<!-- مودل السوشيال ميديا (محدث بأيقونة سلة وحل الكاش) -->
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
                        $social_links = $data['social_links'] ?? [];
                        foreach ($social_links as $index => $link): ?>
                        <div class="card p-3 mb-2 social-item-row" id="row_<?php echo $index; ?>">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <!-- تم إضافة time() لتجاوز الكاش -->
                                    <img src="<?php echo $path_prefix . htmlspecialchars($link['img'] ?? '') . '?' . time(); ?>" style="width: 32px; height: 32px; object-fit: contain;">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name'] ?? ''); ?>" placeholder="اسم المنصة">
                                </div>
                                <div class="col">
                                    <input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" placeholder="الرابط">
                                </div>
                                <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo $link['img'] ?? ''; ?>">
                                <div class="col-auto" style="width: 140px;">
                                    <input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>">
                                </div>
                                <div class="col-auto">
                                    <!-- تم استبدال الـ × بأيقونة سلة مهملات -->
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSocialRow('row_<?php echo $index; ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addSocialRow()">+ إضافة منصة جديدة</button>
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
                <div class="mb-3 text-center"><img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" style="max-width: 100%; height: auto;">

</div>
                <input type="file" class="form-control" name="logo_img" required>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="logoEditForm" class="btn btn-primary">حفظ الشعار</button></div>
    </div></div>
</div>

<!-- مودل الإعلان (الإصدار الفخم) -->
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
                    
                    <!-- الحالة وتوقيت الإعلان -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">حالة الإعلان</label>
                            <select class="form-select" name="status">
                                <option value="Draft" <?php echo (($announcement['announcement']['status'] ?? '') == 'Draft' ? 'selected' : ''); ?>>مسودة (مخفي)</option>
                                <option value="Published" <?php echo (($announcement['announcement']['status'] ?? '') == 'Published' ? 'selected' : ''); ?>>نشر (ظاهر)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تاريخ البدء</label>
                            <input type="datetime-local" class="form-control" name="start_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['start_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تاريخ الانتهاء</label>
                            <input type="datetime-local" class="form-control" name="end_date" value="<?php echo str_replace(' ', 'T', $announcement['announcement']['end_date'] ?? ''); ?>">
                        </div>
                    </div>

                    <hr>

                    <!-- تبديل النوع -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">نوع المحتوى</label>
                        <select class="form-select" name="type" onchange="toggleAdContent(this.value)">
                            <option value="text" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? 'selected' : ''); ?>>نص متحرك (Marquee)</option>
                            <option value="image" <?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? 'selected' : ''); ?>>صورة (Banner)</option>
                        </select>
                    </div>
                    
                    <!-- محرر النص -->
                    <div id="textEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'text' ? '' : 'd-none'); ?>">
                        <textarea class="form-control mb-2" name="announcement_text" rows="3" placeholder="اكتب نص الإعلان هنا..."><?php echo htmlspecialchars($announcement['announcement']['announcement_text'] ?? ''); ?></textarea>
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label">لون الخلفية</label><input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo $announcement['announcement']['bg_color'] ?? '#f1f5f9'; ?>"></div>
                            <div class="col-md-4"><label class="form-label">لون النص</label><input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo $announcement['announcement']['text_color'] ?? '#1e293b'; ?>"></div>
                            <div class="col-md-4"><label class="form-label">حجم الخط (px)</label><input type="number" class="form-control" name="font_size" value="<?php echo $announcement['announcement']['font_size'] ?? '16'; ?>"></div>
                        </div>
                    </div>
                    
                    <!-- محرر الصورة -->
                    <div id="imageEditor" class="<?php echo (($announcement['announcement']['type'] ?? 'text') == 'image' ? '' : 'd-none'); ?>">
                        <label class="form-label">اختر صورة الإعلان</label>
                        <input type="file" class="form-control" name="ad_image">
                        <small class="text-muted">يفضل رفع صورة بمقاسات مناسبة للعرض.</small>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">رابط الإعلان (اختياري)</label>
                        <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars($announcement['announcement']['link'] ?? ''); ?>" placeholder="https://...">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="announcementEditForm" class="btn btn-primary px-4">حفظ الإعدادات</button>
            </div>
        </div>
    </div>
</div>


<script>
    // 1. تبديل محتوى الإعلان
    function toggleAdContent(val) {
        const textEditor = document.getElementById('textEditor');
        const imageEditor = document.getElementById('imageEditor');
        if (textEditor && imageEditor) {
            textEditor.classList.toggle('d-none', val !== 'text');
            imageEditor.classList.toggle('d-none', val !== 'image');
        }
    }

    // 2. منطق إضافة وحذف صفوف السوشيال ميديا
    let socialCount = <?php echo count($data['social_links'] ?? []); ?>;

    function addSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'card p-3 mb-2 social-item-row';
        newRow.id = 'row_' + socialCount;
        
        newRow.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-auto"><div style="width: 32px;"></div></div>
                <div class="col"><input type="text" class="form-control form-control-sm" name="social[${socialCount}][name]" placeholder="اسم المنصة"></div>
                <div class="col"><input type="url" class="form-control form-control-sm" name="social[${socialCount}][url]" placeholder="الرابط"></div>
                <div class="col-auto" style="width: 140px;"><input type="file" class="form-control form-control-sm" name="social_img_${socialCount}"></div>
                <div class="col-auto"><button type="button" class="btn btn-danger btn-sm" onclick="removeSocialRow('row_${socialCount}')">×</button></div>
            </div>
        `;
        container.appendChild(newRow);
        socialCount++;
    }

    function removeSocialRow(rowId) {
        const row = document.getElementById(rowId);
        if(row) row.remove();
    }

    // 3. منطق موحد لإرسال جميع الفورمات
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('admin/api/save_config.php', { 
                method: 'POST', 
                body: formData 
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('تم الحفظ بنجاح!');
                    location.reload();
                } else {
                    alert('خطأ: ' + (data.message || 'حدثت مشكلة'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالسيرفر، يرجى المحاولة لاحقاً');
            });
        });
    });
</script>


