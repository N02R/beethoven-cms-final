<?php
// 1. إعلام المتصفح بأن الناتج هو كود JavaScript
header("Content-type: application/javascript; charset: UTF-8");

// 2. تفعيل الكاش
$offset = 60 * 60 * 24 * 7; // أسبوع كامل
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: public, max-age=" . $offset);

// 3. الملفات الأساسية
$base_files = [
    'bootstrap.bundle.min.js',
    'main.js'
];

// 4. استقبال الملفات الإضافية المحقونة
$extra_files = isset($_GET['files']) ? explode(',', $_GET['files']) : [];

// 5. دمج المصفوفات
$all_files = array_merge($base_files, $extra_files);

$combined_content = "";

// 6. قراءة ودمج المحتويات باستخدام المسارات المطلقة
foreach ($all_files as $file) {
    $file = basename(trim($file));
    
    if (!empty($file) && str_ends_with($file, '.js')) {
        // المسار المطلق للمجلد الحالي الذي يحتوي على ملفات JS
        $js_dir = __DIR__ . '/';
        
        if (file_exists($js_dir . $file)) {
            $combined_content .= file_get_contents($js_dir . $file) . "\n;";
        } 
        // البحث في مسار مجلد التعليم
        elseif (file_exists(__DIR__ . "/../edu-services/js/" . $file)) {
            $combined_content .= file_get_contents(__DIR__ . "/../edu-services/js/" . $file) . "\n;";
        }
    }
}

// 7. دالة تنظيف وضغط كود الـ JS
function minify_js($js) {
    // إزالة تعليقات الـ Block /* ... */
    $js = preg_replace('%/\*.*?\*/%s', '', $js);
    // إزالة السطور والمسافات الزائدة
    $js = preg_replace(array("/\r\n/", "/\r/", "/\n/", "/\t/"), ' ', $js);
    $js = preg_replace("/\s+/", " ", $js);
    return trim($js);
}

// 8. طباعة الناتج النهائي
echo minify_js($combined_content);
