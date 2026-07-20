<?php
// 1. إعلام المتصفح بأن الناتج هو كود CSS
header("Content-type: text/css; charset: UTF-8");

// 2. تفعيل الكاش لتوفير طاقة السيرفر وسرعة التصفح
$offset = 60 * 60 * 24 * 7; // أسبوع كامل
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: public, max-age=" . $offset);

// 3. الملفات الأساسية (تأكدي أنها موجودة بالفعل في مجلد assets/css/)
$base_files = [
    'bootstrap.min.css',
    'swiper-bundle.min.css',
    'main.css'
];


// 4. استقبال الملفات الإضافية المحقونة من الصفحات
$extra_files = isset($_GET['files']) ? explode(',', $_GET['files']) : [];

// 5. دمج المصفوفات
$all_files = array_merge($base_files, $extra_files);

$combined_content = "";

// 6. قراءة ودمج المحتويات باستخدام المسارات المطلقة (الأكثر أماناً)
foreach ($all_files as $file) {
    // تنظيف الاسم لمنع ثغرات traversal
    $file = basename(trim($file));
    
    if (!empty($file) && str_ends_with($file, '.css')) {
        // المسار الأساسي للمجلد الحالي الذي يحتوي على ملفات CSS
        $css_dir = __DIR__ . '/';
        
        if (file_exists($css_dir . $file)) {
            $combined_content .= file_get_contents($css_dir . $file) . "\n";
        } 
        // البحث في مسار آخر إذا لم يوجد في المجلد الحالي (مثل مجلد التعليم)
        elseif (file_exists(__DIR__ . "/../edu-services/css/" . $file)) {
            $combined_content .= file_get_contents(__DIR__ . "/../edu-services/css/" . $file) . "\n";
        }
    }
}

// 7. دالة تنظيف وضغط الكود (Minification)
function minify_css($css) {
    // حذف التعليقات
    $css = preg_replace('!/\*[^*]*\*+([^/*][^*]*\*+)*/!', '', $css);
    // إزالة المسافات والأسطر غير الضرورية
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    // إزالة المسافات حول الرموز (اختياري وآمن)
    $css = preg_replace('/ ?([,:;{}]) ?/', '$1', $css);
    return $css;
}

// 8. طباعة الناتج النهائي المضغوط
echo minify_css($combined_content);
