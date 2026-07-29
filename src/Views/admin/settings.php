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
                    <h5 class="mb-0">تحديث إعدادات النظام وتصاريح العمل</h5>
                </div>
                <div class="card-body">

                    <!-- تنبيهات الاستجابة -->
                    <div id="alert-box" class="alert d-none" role="alert"></div>

                    <!-- نموذج حفظ الإعدادات -->
                    <form id="configForm" enctype="multipart/form-data">
                        <!-- رمز الحماية CSRF -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                        
                        <!-- تحديد الإجراء الخاص بالبيانات -->
                        <input type="hidden" name="action" value="update_job_agreements_card">

                        <h6 class="border-bottom pb-2 mb-3 text-secondary">إعدادات بطاقة اتفاقيات البحث عن عمل</h6>

                        <div class="mb-3">
                            <label for="item_title" class="form-label">عنوان البطاقة</label>
                            <input type="text" class="form-control" id="item_title" name="item_title" value="عرض واتفاقيات العمل" required>
                        </div>

                        <div class="mb-3">
                            <label for="item_sub" class="form-label">الوصف الفرعي</label>
                            <input type="text" class="form-control" id="item_sub" name="item_sub" value="تحميل ملف الاتفاقية الخاص بالخدمة">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_type" class="form-label">نوع الملف</label>
                                <select class="form-select" id="item_type" name="item_type">
                                    <option value="pdf" selected>PDF</option>
                                    <option value="word">Word (DOCX)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="item_file" class="form-label">رفع ملف جديد</label>
                                <input type="file" class="form-control" id="item_file" name="item_file">
                                <input type="hidden" name="old_file" value="assets/files/job_search_agreement.pdf">
                            </div>
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
    
    // تعطيل الزر أثناء المعالجة
    submitBtn.disabled = true;
    submitBtn.innerText = 'جاري الحفظ...';
    
    alertBox.classList.add('d-none');
    alertBox.className = 'alert d-none';

    const formData = new FormData(this);

    // 🔗 الربط المباشر مع المسار الجديد في MVC
    fetch('index.php?url=admin/config/save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alertBox.classList.remove('d-none');
        if (data.success) {
            alertBox.classList.add('alert-success');
            alertBox.innerText = data.message || 'تم حفظ الإعدادات بنجاح!';
        } else {
            alertBox.classList.add('alert-danger');
            alertBox.innerText = data.message || 'حدث خطأ أثناء الحفظ.';
        }
    })
    .catch(error => {
        alertBox.classList.remove('d-none');
        alertBox.classList.add('alert-danger');
        alertBox.innerText = 'تعذر الاتصال بالسيرفر، يرجى المحاولة لاحقاً.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'حفظ التغييرات';
    });
});
</script>

</body>
</html>
