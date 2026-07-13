<?php
// 1. إعلام المتصفح بأن الناتج هو كود JavaScript
header("Content-type: application/javascript; charset: UTF-8");

// 2. تفعيل الكاش لزيادة سرعة التحميل وتوفير طاقة السيرفر
$offset = 60 * 60 * 24 * 7; // أسبوع كامل
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: public, max-age=" . $offset);

// 3. الملفات الأساسية المشتركة التي تحتاجها كل صفحات الموقع (بترتيبها الصحيح)
$base_files = [
    'bootstrap.bundle.min.js', // ملف بوتستراب مع Popper.js المدمج
    'main.js'                  // ملف الجافاسكربت الأساسي للموقع
];

// 4. استقبال الملفات الإضافية المحقونة من الصفحات (مثل السلايدر وغيرها)
$extra_files = isset($_GET['files']) ? explode(',', $_GET['files']) : [];

// 5. دمج المصفوفات معاً
$all_files = array_merge($base_files, $extra_files);

$combined_content = "";

// 6. قراءة ودمج المحتويات بذكاء وحماية
foreach ($all_files as $file) {
    // تنظيف الاسم لمنع ثغرات مسارات الملفات
    $file = basename(trim($file));
    
    if (!empty($file) && str_ends_with($file, '.js')) {
        // الاحتمال الأول: الملف موجود في مجلد الـ JS الحالي (assets/js/)
        if (file_exists($file)) {
            $combined_content .= file_get_contents($file) . "\n;"; // إضافة الفاصلة المنقوطة تحمي الأكواد من التداخل عند الدمج
        } 
        // الاحتمال الثاني: الملف موجود في مجلد خدمات التعليم الفرعي إذا لزم الأمر
        elseif (file_exists("../../edu-services/js/" . $file)) {
            $combined_content .= file_get_contents("../../edu-services/js/" . $file) . "\n;";
        }
    }
}

// 7. دالة تنظيف وضغط كود الـ JS (آمنة وبسيطة للأنظمة الحقيقية)
function minify_js($js) {
    // إزالة تعليقات السطر الواحد // ولكن مع الحذر من روابط الـ http://
    $js = preg_replace('%(?<!:)/\*.*?\*/%s', '', $js); // تعليقات الـ Block /* ... */
    
    // إزالة الأسطر الزائدة والفراغات الكبيرة المحيطة بالرموز الأساسية
    $js = preg_replace(array("/\r\n/", "/\r/", "/\n/", "/\t/"), ' ', $js);
    $js = preg_replace("/\s+/", " ", $js);
    
    return trim($js);
}

/*8. طباعة الناتج النهائي المضغوط*/
echo minify_js($combined_content);
