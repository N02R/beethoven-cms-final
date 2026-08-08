
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع - لوحة التحكم</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        .settings-container { max-width: 900px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .btn-save { background: #4f46e5; color: #fff; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-save:hover { background: #4338ca; }
        .alert { padding: 10px 15px; margin-bottom: 20px; border-radius: 4px; display: none; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .preview-img { max-height: 80px; margin-top: 10px; display: block; border-radius: 4px; }
        .section-box { background: #f8fafc; padding: 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="settings-container">
    <h2>إعدادات الموقع العامة وقسم الخدمات</h2>
    <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

    <!-- رسائل التنبيه الديناميكية -->
    <div id="alertBox" class="alert"></div>

    <form id="settingsForm" enctype="multipart/form-data">
        <!-- حقل حماية الـ CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
        
        <!-- تحديد الـ Action الخاص بتحديث الخدمات مثلاً أو يمكنك جعله نموذجاً منفصلاً لكل قسم -->
        <input type="hidden" name="action" value="update_services">

        <!-- 1. الإعدادات العامة (العنوان والبريد) -->
        <div class="form-group">
            <label for="site_title">عنوان الموقع (Site Title):</label>
            <input type="text" id="site_title" name="settings[site_title]" class="form-control" value="<?= htmlspecialchars($settingsData['site_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="site_email">البريد الإلكتروني للإدارة (Site Email):</label>
            <input type="email" id="site_email" name="settings[site_email]" class="form-control" value="<?= htmlspecialchars($settingsData['site_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <!-- 2. قسم الخدمات (Services Section) مع مطابقة أسماء الحقول للـ Controller -->
        <div class="section-box">
            <h3>إدارة الخدمات</h3>
            
            <div class="form-group">
                <label>عنوان قسم الخدمات:</label>
                <input type="text" name="services_title" class="form-control" value="<?= htmlspecialchars($servicesSectionTitle ?? '') ?>">
            </div>

            <div class="form-group">
                <label>وصف قسم الخدمات:</label>
                <textarea name="services_desc" class="form-control"><?= htmlspecialchars($servicesSectionDesc ?? '') ?></textarea>
            </div>

            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #cbd5e1;">

            <?php 
            // افتراض أن الخدمات تأتي في مصفوفة $servicesData
            $servicesData = $servicesData ?? []; 
            foreach ($servicesData as $index => $service): 
            ?>
                <div style="background: #fff; padding: 15px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
                    <h4 style="margin-top: 0; color: #4f46e5;">الخدمة رقم (<?= $index + 1 ?>)</h4>
                    
                    <div class="form-group">
                        <label>عنوان الخدمة:</label>
                        <input type="text" name="services[<?= $index ?>][title]" class="form-control" value="<?= htmlspecialchars($service['title'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>وصف الخدمة:</label>
                        <textarea name="services[<?= $index ?>][desc]" class="form-control"><?= htmlspecialchars($service['desc'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>صورة الخدمة:</label>
                        <?php if (!empty($service['img'])): ?>
                            <img src="/<?= htmlspecialchars($service['img'], ENT_QUOTES, 'UTF-8') ?>" alt="Service Image" class="preview-img">
                        <?php endif; ?>
                        
                        <!-- الحقل المخفي للاحتفاظ بمسار الصورة القديمة في حال لم يتم رفع صورة جديدة -->
                        <input type="hidden" name="services[<?= $index ?>][old_img]" value="<?= htmlspecialchars($service['img'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        
                        <!-- حقل رفع الصورة الجديد بالاسم المتطابق تماماً مع الـ Controller: service_img_$index -->
                        <input type="file" name="service_img_<?= $index ?>" class="form-control" accept="image/*">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn-save" id="saveBtn">حفظ التغييرات</button>
    </form>
</div>

<script>
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let submitBtn = document.getElementById('saveBtn');
    let alertBox = document.getElementById('alertBox');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري الحفظ...';
    
    let formData = new FormData(this);

    fetch('index.php?url=admin/settings/save', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async response => {
        let data;
        try {
            data = await response.json();
        } catch (err) {
            throw new Error('حدث خطأ غير متوقع في استجابة السيرفر.');
        }

        if (!response.ok) {
            throw new Error(data.message || data.error || 'حدث خطأ أثناء تنفيذ الطلب.');
        }
        
        return data;
    })
    .then(data => {
        alertBox.style.display = 'block';
        if (data.success) {
            alertBox.className = 'alert alert-success';
            alertBox.textContent = data.message;
            setTimeout(() => {
                location.reload(); 
            }, 1200);
        } else {
            alertBox.className = 'alert alert-error';
            alertBox.textContent = data.error || data.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'حفظ التغييرات';
        }
    })
    .catch(err => {
        alertBox.style.display = 'block';
        alertBox.className = 'alert alert-error';
        alertBox.textContent = err.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'حفظ التغييرات';
    });
</script>

</body>
</html>
