<?php
if (!defined('ALLOWED_ACCESS')) {
    exit('Direct access not permitted.');
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الموقع - لوحة التحكم</title>
    <!-- استدعي ملفات الـ CSS الخاصة بلوحة التحكم لديك هنا -->
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
    </style>
</head>
<body>

<div class="settings-container">
    <h2>إعدادات الموقع العامة</h2>
    <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

    <!-- رسائل التنبيه الديناميكية -->
    <div id="alertBox" class="alert"></div>

    <form id="settingsForm" enctype="multipart/form-data">
        <!-- حقل حماية الـ CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <!-- مثال 1: حقل عنوان الموقع (Text) -->
        <div class="form-group">
            <label for="site_title">عنوان الموقع (Site Title):</label>
            <input type="text" id="site_title" name="settings[site_title]" class="form-control" value="<?= htmlspecialchars($settingsData['site_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <!-- مثال 2: حقل البريد الإلكتروني (Text) -->
        <div class="form-group">
            <label for="site_email">البريد الإلكتروني للإدارة (Site Email):</label>
            <input type="email" id="site_email" name="settings[site_email]" class="form-control" value="<?= htmlspecialchars($settingsData['site_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <!-- مثال 3: حقل رفع شعار الموقع (Image) -->
        <div class="form-group">
            <label for="site_logo_path">شعار الموقع (Site Logo):</label>
            <?php if (!empty($settingsData['site_logo_path'])): ?>
                <img src="/<?= htmlspecialchars($settingsData['site_logo_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Logo" class="preview-img">
            <?php endif; ?>
            <input type="file" id="site_logo_path" name="images[site_logo_path]" class="form-control" accept="image/*">
            <small style="color: #666;">يُفضل رفع صورة بصيغة WebP أو PNG أو SVG.</small>
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
            throw new Error(data.message || 'حدث خطأ أثناء تنفيذ الطلب.');
        }
        
        return data;
    })
    .then(data => {
        alertBox.style.display = 'block';
        if (data.success) {
            alertBox.className = 'alert alert-success';
            alertBox.textContent = data.message;
            setTimeout(() => {
                location.reload(); // تحديث الصفحة لتظهر الصور أو التعديلات الجديدة
            }, 1200);
        } else {
            alertBox.className = 'alert alert-error';
            alertBox.textContent = data.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'حفظ التغييرات';
        }
    })
    .catch(err => {
        alertBox.style.display = 'block';
        alertBox.className = 'alert alert-error';
        alertBox.textContent = err.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'حفظ التغييرات؛';
    });
});
</script>

</body>
</html>
