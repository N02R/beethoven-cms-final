<?php 
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك

?>

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container text-center">
      <h2 class="pt-5">BCS Bachelor Package and Agreement Templet</h2>
      <p class="text-center pas-text">هذا المستند محمي بكلمة مرور. يرجى <span style="color: #66aeee;">الاتصال بنا</span> للحصول على كلمة المرور<br>
        هذا المحتوى محمي بكلمة مرور. لإظهار المحتوى يتعين عليك كتابة كلمة المرور في الأدنى:</p>
      <div class="pas-info mt-5">
        <p>كلمة المرور: .............................</p>
        <a href="#" class="btn">ادخال</a>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
// 4. استدعاء الفوتر المشترك

?>
