<?php
// 1. إعلام المتصفح بأن الناتج هو كود CSS
header("Content-type: text/css; charset: UTF-8");

// 2. تفعيل الكاش لتوفير طاقة السيرفر وسرعة التصفح
$offset = 60 * 60 * 24 * 7; // أسبوع كامل
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: public, max-age=" . $offset);

// 3. الملفات الأساسية المشتركة في كل صفحات الموقع (بترتيبها الصحيح)
$base_files = [
    'bootstrap.min.css',
    'all.min.css',
    'main.css',
    'style.css',
    'header.css',
    'footer.css',
    'responsive-index.css' // تم ضبط الاسم الصحيح لملف الريسبونسيف الخاص بكِ
];

// 4. استقبال الملفات الإضافية المحقونة من الصفحات
$extra_files = isset($_GET['files']) ? explode(',', $_GET['files']) : [];

// 5. دمج المصفوفات معاً
$all_files = array_merge($base_files, $extra_files);

$combined_content = "";

// 6. قراءة ودمج المحتويات بذكاء وحماية
foreach ($all_files as $file) {
    // تنظيف الاسم لمنع ثغرات مسارات الملفات
    $file = basename(trim($file));
    
    if (!empty($file) && str_ends_with($file, '.css')) {
        // الاحتمال الأول: الملف موجود في نفس المجلد الحالي (assets/css/)
        if (file_exists($file)) {
            $combined_content .= file_get_contents($file) . "\n";
        } 
        // الاحتمال الثاني: الملف موجود في مجلد خدمات التعليم الفرعي (edu-services/css/)
        elseif (file_exists("../../edu-services/css/" . $file)) {
            $combined_content .= file_get_contents("../../edu-services/css/" . $file) . "\n";
        }
    }
}

// 7. دالة تنظيف وضغط الكود (Minification)
function minify_css($css) {
    // حذف التعليقات
    $css = preg_replace('!/\*[^*]*\*+([^/*][^*]*\*+)*/!', '', $css);
    // حذف الأسطر والمسافات الزائدة والتطابقات المحيطة بالرموز
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    $css = preg_replace('/ ?([,:;{}]) ?/', '$1', $css);
    return $css;
}

// 8. طباعة الناتج النهائي المضغوط
echo minify_css($combined_content);
