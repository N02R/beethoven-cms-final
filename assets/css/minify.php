<?php
// إعلام المتصفح أن هذا الملف هو ملف CSS وليس صفحة HTML
header("Content-type: text/css; charset: UTF-8");

// تفعيل نظام الكاش (Caching) لكي لا يقوم السيرفر بضغط الملفات في كل زيارة (توفيراً للطاقة)
$offset = 60 * 60 * 24 * 7; // الكاش لمدة أسبوع
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: public, max-age=" . $offset);

// مصفوفة بالملفات الأساسية المشتركة التي نريد دمجها دائماً بترتيب صحيح
$base_files = [
    'bootstrap.min.css',
    'main.css',
    'style.css',
    'header.css',
    'footer.css',
    'responsive-index.css'
];

// استقبال أي ملفات إضافية خاصة بالصفحة عبر الرابط (GET)
$extra_files = isset($_GET['files']) ? explode(',', $_GET['files']) : [];

// دمج القائمتين معاً
$all_files = array_merge($base_files, $extra_files);

$combined_content = "";

// قراءة محتوى كل ملف ودمجه
foreach ($all_files as $file) {
    // تنظيف اسم الملف لحماية السيرفر من الثغرات الأمنية (Directory Traversal)
    $file = basename(trim($file));
    
    // التحقق من امتداد الملف وأنه موجود
    if (!empty($file) && (str_ends_with($file, '.css'))) {
        if (file_exists($file)) {
            $combined_content .= file_get_contents($file) . "\n";
        } elseif (file_exists("../../edu-services/css/" . $file)) { 
            // إذا كان الملف داخل مجلد خدمات التعليم الفرعي
            $combined_content .= file_get_contents("../../edu-services/css/" . $file) . "\n";
        }
    }
}

// دالة ذكية لضغط كود الـ CSS (إزالة التعليقات والمسافات والأسطر)
function minify_css($css) {
    // إزالة التعليقات /* ... */
    $css = preg_replace('!/\*[^*]*\*+([^/*][^*]*\*+)*/!', '', $css);
    // إزالة المسافات المحيطة بالرموز الشائعة
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    $css = preg_replace('/ ?([,:;{}]) ?/', '$1', $css);
    return $css;
}

// طباعة الكود النهائي المضغوط والمدمج
echo minify_css($combined_content);
