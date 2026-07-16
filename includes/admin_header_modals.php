<?php
// تأكدي من توافق اسم مفتاح الجلسة مع ما تم تعريفه في login.php
session_start();
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}
?>

<style>
    :root { --bs-border-radius-lg: 12px; }
    .custom-modal .modal-content { border: none; border-radius: var(--bs-border-radius-lg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .custom-modal .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .custom-modal .modal-title { margin: 0; display: flex; align-items: center; gap: .5rem; font-weight: 700; color: #1e293b; }
    .custom-modal .btn-close { margin: 0; flex-shrink: 0; }
    .social-item-row { transition: 0.3s; border: 1px solid #e2e8f0; }
    .social-item-row:hover { border-color: #3b82f6; }
    .btn-enterprise { padding: 8px 20px; font-weight: 600; }
</style>

<!-- 1. مودل التواصل الاجتماعي -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-share"></i> إدارة منصات التواصل</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
            <form id="socialLinksForm" enctype="multipart/form-data">
                <div id="socialRowsContainer">
                    <?php foreach (($announcement['social_links'] ?? []) as $index => $link): ?>
                    <div class="card p-3 mb-3 social-item-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto"><img src="<?php echo $path_prefix . htmlspecialchars($link['img']); ?>" style="width: 32px;"></div>
                            <div class="col"><input type="text" class="form-control form-control-sm" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($link['name']); ?>"></div>
                            <div class="col"><input type="url" class="form-control form-control-sm" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($link['url']); ?>"></div>
                            <div class="col-auto" style="width: 120px;">
                                <input type="file" class="form-control form-control-sm" name="social_img_<?php echo $index; ?>">
                                <input type="hidden" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($link['img']); ?>">
                            </div>
                            <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.social-item-row').remove()"><i class="bi bi-trash"></i></button></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-sm btn-success mt-2" onclick="addNewSocialRow()"><i class="bi bi-plus-lg"></i> إضافة منصة</button>
            </form>
        </div>
        <div class="modal-footer"><button type="submit" form="socialLinksForm" class="btn btn-primary btn-enterprise">حفظ التغييرات</button></div>
    </div></div>
</div>

<script>
    // إضافة صف جديد للسوشيال ميديا
    function addNewSocialRow() {
        const container = document.getElementById('socialRowsContainer');
        const index = Date.now();
        container.insertAdjacentHTML('beforeend', `
            <div class="card p-3 mb-3 social-item-row">
                <div class="row g-2 align-items-center">
                    <div class="col-auto"><div style="width:32px;height:32px;background:#eee;border-radius:4px;"></div></div>
                    <div class="col"><input type="text" class="form-control form-control-sm" name="social[${index}][name]" placeholder="الاسم"></div>
                    <div class="col"><input type="url" class="form-control form-control-sm" name="social[${index}][url]" placeholder="الرابط"></div>
                    <div class="col-auto" style="width: 120px;"><input type="file" class="form-control form-control-sm" name="social_img_${index}"></div>
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.social-item-row').remove()"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>`);
    }

    // إرسال بيانات السوشيال ميديا
    document.getElementById('socialLinksForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // إعادة ترتيب الـ names في الـ inputs قبل الإرسال
        const rows = document.querySelectorAll('.social-item-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                let name = input.getAttribute('name');
                if (name && name.includes('social[')) {
                    name = name.replace(/social\[[^\]]+\]/, `social[${index}]`);
                    input.setAttribute('name', name);
                }
            });
        });

        const formData = new FormData(this);
        
        // التعديل الجوهري: إضافة credentials: 'include'
        fetch('admin/api/update_social_links.php', { 
            method: 'POST', 
            body: formData,
            credentials: 'include' 
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) { 
                alert('تم الحفظ بنجاح!'); 
                location.reload(); 
            } else { 
                alert('خطأ: ' + (data.error || 'غير مصرح')); 
            }
        })
        .catch(err => alert('حدث خطأ في الاتصال بالسيرفر'));
    });
</script>
