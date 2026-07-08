<?php
// admin/index.php
use App\Core\CMS;

// بسيط: نموذج لتعديل عنوان الهيرو
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    CMS::update('home', 'hero', 'title', $_POST['hero_title']);
    echo "<div class='alert alert-success'>تم حفظ التعديلات بنجاح!</div>";
}

$hero_title = CMS::get('home', 'hero', 'title');
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>لوحة التحكم - بيتهوفن</title>
</head>
<body class="p-5">
    <h2>تعديل الصفحة الرئيسية</h2>
    <form method="POST">
        <div class="mb-3">
            <label>عنوان قسم البداية:</label>
            <input type="text" name="hero_title" class="form-control" value="<?php echo htmlspecialchars($hero_title); ?>">
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
</body>
</html>
