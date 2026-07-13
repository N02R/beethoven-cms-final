<?php
// هنا يمكنكِ مستقبلاً إضافة فحص الصلاحيات (لحماية الصفحة بحيث لا يدخلها إلا المدير)
// session_start();
// if(!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit(); }

// متغير لتحديد مسار الجذور للملفات المستدعاة (تعدل حسب الحاجة)
$path_prefix = "../"; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | إدارة الموقع</title>
    <!-- استدعاء Bootstrap 5 لدعم الاتجاه العربي -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- أيقونات Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #c2c7d0;
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 4px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- القائمة الجانبية لوحة التحكم -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="position-sticky">
                <h5 class="text-center py-3 border-bottom border-secondary text-warning fw-bold">لوحة التحكم</h5>
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-speedometer2 me-2"></i> الرئيسة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-layout-text-window-reverse me-2"></i> إعدادات الهيدر
                        </a>
                    </li>
                    <!-- يمكنك إضافة المزيد من الروابط هنا مستقبلاً (المنتجات، الطلبات...) -->
                </ul>
            </div>
        </nav>

        <!-- منطقة المحتوى الرئيسية -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 fw-bold text-dark">إدارة محتوى الموقع</h1>
            </div>

            <!-- بطاقة إعدادات الإعلانات -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-megaphone-fill text-warning me-2"></i> شريط الإعلانات العلوي (Announcement Bar)
                </div>
                <div class="card-body">
                    <p class="text-muted fs-6">من هنا يمكنك التحكم في الإعلان الذي يظهر في أعلى الهيدر للمستخدمين، وتحديد ما إذا كان نصياً أو صورياً.</p>
                    
                    <!-- زر فتح مودل تعديل الإعلانات المخصص لكِ -->
                    <div class="text-start">
                        <button type="button" class="btn btn-warning fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#announcementModal">
                            📢 إدارة إعلان الهيدر (Header Announcement)
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- ========================================== -->
<!-- مودل تعديل الإعلان (تم وضعه هنا في الأسفل) -->
<!-- ========================================== -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="announcementModalLabel">إدارة وإعدادات إعلان أعلى الموقع</h5>
                <button type="button" class="btn-close btn-close-white ms-0 me-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form id="announcementForm" enctype="multipart/form-data">
                <div class="modal-body" dir="rtl">
                    
                    <!-- 1. الحقول المشتركة الأساسية -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded">
                        <h6 class="fw-bold border-bottom pb-2">📌 الإعدادات العامة للظهور</h6>
                        
                        <div class="col-md-4">
                            <label for="adTypeSelect" class="form-label fw-bold">نوع الإعلان</label>
                            <select class="form-select border-primary" id="adTypeSelect" name="type" required>
                                <option value="text" selected>نصي (Text Banner)</option>
                                <option value="image">صورة (Image Banner)</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="adStatusSelect" class="form-label fw-bold">حالة النشر</label>
                            <select class="form-select" id="adStatusSelect" name="status" required>
                                <option value="Published">منشور (Published)</option>
                                <option value="Draft">مسودة (Draft)</option>
                                <option value="Hidden">مخفي (Hidden)</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="adOpenTab" class="form-label fw-bold">فتح الرابط في نافذة جديدة</label>
                            <select class="form-select" id="adOpenTab" name="open_new_tab">
                                <option value="Yes">نعم (Yes)</option>
                                <option value="No" selected>لا (No)</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="adLink" class="form-label fw-bold">رابط التوجيه (اختياري)</label>
                            <input type="url" class="form-control" id="adLink" name="link" placeholder="https://example.com أو رابط واتساب">
                            <div class="form-text text-muted">يجب أن يبدأ الرابط بـ http:// أو https://</div>
                        </div>

                        <div class="col-md-6">
                            <label for="adStartDate" class="form-label fw-bold">تاريخ ووقت البدء</label>
                            <input type="datetime-local" class="form-control" id="adStartDate" name="start_date">
                        </div>

                        <div class="col-md-6">
                            <label for="adEndDate" class="form-label fw-bold">تاريخ ووقت الانتهاء</label>
                            <input type="datetime-local" class="form-control" id="adEndDate" name="end_date">
                        </div>
                    </div>

                    <!-- 2. حقول الإعلان النصي (تظهر افتراضياً) -->
                    <div id="textTypeSection" class="row g-3 mb-4 p-3 border border-info rounded">
                        <h6 class="fw-bold text-info border-bottom pb-2">✍️ إعدادات الإعلان النصي</h6>
                        
                        <div class="col-md-12">
                            <label for="adText" class="form-label fw-bold">نص الإعلان</label>
                            <textarea class="form-control" id="adText" name="announcement_text" rows="2" placeholder="اكتب نص الإعلان الترويجي هنا..."></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="adBgColor" class="form-label fw-bold">لون الخلفية</label>
                            <input type="color" class="form-control form-control-color w-100" id="adBgColor" name="bg_color" value="#f8f9fa">
                        </div>

                        <div class="col-md-4">
                            <label for="adTextColor" class="form-label fw-bold">لون الخط</label>
                            <input type="color" class="form-control form-control-color w-100" id="adTextColor" name="text_color" value="#0d6efd">
                        </div>

                        <div class="col-md-4">
                            <label for="adFontSize" class="form-label fw-bold">حجم الخط (بالبكسل)</label>
                            <input type="number" class="form-control" id="adFontSize" name="font_size" value="14" min="12" max="24">
                        </div>
                    </div>

                    <!-- 3. حقول إعلان الصور (مخفية افتراضياً) -->
                    <div id="imageTypeSection" class="row g-3 mb-4 p-3 border border-warning rounded d-none">
                        <h6 class="fw-bold text-warning border-bottom pb-2">🖼️ إعدادات إعلان الصور</h6>
                        
                        <div class="col-md-12">
                            <label for="adImageFile" class="form-label fw-bold">رفع بنر الإعلان (JPG, PNG, WebP)</label>
                            <input class="form-control" type="file" id="adImageFile" name="announcement_image" accept="image/png, image/jpeg, image/jpg, image/webp">
                            <div class="form-text text-muted">الحد الأقصى للحجم: 2 ميجابايت. الارتفاع المفضل في الهيدر: 50px إلى 60px.</div>
                        </div>

                        <div class="col-md-12">
                            <label for="adAltText" class="form-label fw-bold">النص البديل للصورة (Alt Text)</label>
                            <input type="text" class="form-control" id="adAltText" name="alt_text" placeholder="مثال: حملة التخفيضات الجديدة">
                        </div>
                    </div>

                    <!-- مؤشر تحميل (Spinner) -->
                    <div class="text-center">
                        <div id="adUploadSpinner" class="spinner-border text-primary d-none my-2" role="status">
                            <span class="visually-hidden">جاري الحفظ والتدقيق...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success fw-bold" id="saveAdBtn">حفظ ونشر الإعلان فوراً</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- استدعاء ملفات جافاسكربت لـ Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- سكربت التحكم الخاص بالإعلان التفاعلي وإرسال الـ AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const adTypeSelect = document.getElementById('adTypeSelect');
    const textTypeSection = document.getElementById('textTypeSection');
    const imageTypeSection = document.getElementById('imageTypeSection');

    // التبديل التفاعلي بين حقول النص وحقول الصور
    adTypeSelect.addEventListener('change', function () {
        if (this.value === 'text') {
            textTypeSection.classList.remove('d-none');
            imageTypeSection.classList.add('d-none');
            document.getElementById('adText').setAttribute('required', 'required');
            document.getElementById('adImageFile').removeAttribute('required');
        } else if (this.value === 'image') {
            imageTypeSection.classList.remove('d-none');
            textTypeSection.classList.add('d-none');
            document.getElementById('adImageFile').setAttribute('required', 'required');
            document.getElementById('adText').removeAttribute('required');
        }
    });

    // ضبط الحالة الافتراضية
    document.getElementById('adText').setAttribute('required', 'required');

    // معالجة الإرسال الآمن عبر AJAX (Fetch)
    document.getElementById('announcementForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const saveAdBtn = document.getElementById('saveAdBtn');
        const adSpinner = document.getElementById('adUploadSpinner');

        saveAdBtn.disabled = true;
        adSpinner.classList.remove('d-none');

        const formData = new FormData(this);

        fetch('<?php echo $path_prefix; ?>admin/save_announcement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('استجابة غير صالحة من السيرفر');
            return response.json();
        })
        .then(data => {
            saveAdBtn.disabled = false;
            adSpinner.classList.add('d-none');

            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('فشل حفظ الإعلان: ' + data.error);
            }
        })
        .catch(error => {
            saveAdBtn.disabled = false;
            adSpinner.classList.add('d-none');
            alert('خطأ في الاتصال بالشبكة: ' + error.message);
        });
    });
});
</script>

</body>
</html>
