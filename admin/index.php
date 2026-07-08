<?php
// admin/index.php
use App\Core\CMS;

// معالجة الحفظ (سنطورها لاحقاً لتكون أكثر شمولاً)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        // مفترض أن الأسماء في الفورم تكون بتنسيق section_key
        $parts = explode('_', $key);
        if(count($parts) >= 2) {
            CMS::update('home', $parts[0], $parts[1], $value);
        }
    }
    echo "<div class='alert alert-success'>تم التحديث!</div>";
}
?>

