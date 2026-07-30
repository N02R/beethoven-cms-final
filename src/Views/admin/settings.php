<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'] ?? 'إعدادات النظام') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تحديث إعدادات النظام الأساسية</h5>
                </div>
                <div class="card-body">

                    <!-- تنبيهات الاستجابة -->
                    <div id="alert-box" class="alert d-none" role="alert"></div>

                    <!-- نموذج حفظ الإعدادات -->
                    <form id="configForm" enctype="multipart/form-data">
                        <!-- رمز الحماية CSRF -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

                        <h6 class="border-bottom pb-2 mb-3 text-secondary">إعدادات الموقع العامة والشعار</h6>

                        <div class="mb-3">
                            <label for="site_title" class="form-label">عنوان الموقع</label>
                            <input type="text" class="form-control" id="site_title" name="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="site_email" class="form-label">البريد الإلكتروني للإدارة</label>
                            <input type="email" class="form-control" id="site_email" name="site_email" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="site_logo" class="form-label">شعار الموقع (Logo)</label>
                            <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                            <?php if (!empty($settings['site_logo'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">الشعار الحالي:</small><br>
                                    <img src="uploads/<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" style="max-height: 60px;" class="mt-1 border p-1 bg-white">
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('configForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alert-box');
    
    submitBtn.disabled = true;
    submitBtn.innerText = 'جاري الحفظ...';
    
    alertBox.classList.add('d-none');
    alertBox.className = 'alert d-none';

    const formData = new FormData(this);

    fetch('index.php?url=admin/settings/save', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            throw new Error('استجابة غير صالحة من السيرفر: ' + text);
        }
    })
    .then(data => {
        alertBox.classList.remove('d-none');
        if (data.success) {
            alertBox.classList.add('alert-success');
            alertBox.innerText = data.message || 'تم حفظ الإعدادات بنجاح!';
            setTimeout(() => location.reload(), 1500); // تحديث الصفحة لرؤية التغييرات والشعار الجديد
        } else {
            alertBox.classList.add('alert-danger');
            alertBox.innerText = data.error || 'حدث خطأ أثناء الحفظ.';
        }
    })
    .catch(error => {
        alertBox.classList.remove('d-none');
        alertBox.classList.add('alert-danger');
        alertBox.innerText = error.message || 'تعذر الاتصال بالسيرفر، يرجى المحاولة لاحقاً.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'حفظ التغييرات';
    });
});
</script>

</body>
</html>
