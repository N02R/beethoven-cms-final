<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

// تحديد بادئة المسار للملفات والمجلدات الرئيسية
$path_prefix = ''; 

// تمرير ملف الـ CSS الخاص بالخدمات لضمان تطابق التصميم والهوية البصرية
$page_css = [
    'edu-services/css/edu-services.css'
];
$page_js = [];

// استدعاء الهيدر المشترك
include_once $path_prefix . 'includes/header.php';
?>

  <!-- Breadcrumb start -->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item" aria-current="page">سياسة الخصوصية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end -->

  <!-- Privacy Policy Content start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <div class="head-info pb-4 mb-4 border-bottom">
        <h2 class="main-text">سياسة الخصوصية وحماية البيانات (GDPR)</h2>
        <p class="par-text">نحن في بيتهوفن سيتي للخدمات (Beethoven City Services) نولي أهمية بالغة لحماية خصوصيتك ومعلوماتك الشخصية. توضح هذه الوثيقة كيف نجمع ونستخدم ونحمي بياناتك التزاماً باللائحة العامة لحماية البيانات في الاتحاد الأوروبي (GDPR).</p>
      </div>

      <div class="privacy-content-body" style="line-height: 1.8; color: #334155;">
        
        <h4 class="fw-bold text-dark mt-4 mb-3">1. المسؤول عن جمع البيانات (Data Controller)</h4>
        <p>الجهة المسؤولة عن معالجة البيانات الشخصية عبر هذا الموقع الإلكتروني هي:</p>
        <ul class="list-unstyled ps-3 bg-light p-3 rounded border">
          <li><strong>اسم الشركة:</strong> Beethoven City Services (BCS)</li>
          <li><strong>العنوان:</strong> Rheinweg 140, 53129 Bonn, Germany</li>
          <li><strong>البريد الإلكتروني:</strong> info@Beethoven-City-Services.com</li>
        </ul>

        <h4 class="fw-bold text-dark mt-4 mb-3">2. جمع واستخدام البيانات الشخصية</h4>
        <p>نحن نؤمن بالتواصل المباشر والآمن؛ لذلك لا نطلب إنشاء حسابات معقدة أو نماذج جمع بيانات إلكترونية ضخمة. يتم جمع الحد الأدنى فقط من البيانات الضرورية (مثل بيانات الجلسات التقنية Sessions وملفات تعريف الارتباط الأساسية لضمان عمل لوحة التحكم والموقع بكفاءة).</p>

        <h4 class="fw-bold text-dark mt-4 mb-3">3. ملفات تعريف الارتباط (Cookies)</h4>
        <p>يستخدم موقعنا ملفات تعريف الارتباط الضرورية تقنياً لضمان استقرار وتأمين تصفحك وتأمين جلسات المشرفين (Admin Sessions). بناءً على خيارك في شريط الموافقة، يمكنك قبول الملفات الأساسية فقط أو رفض أي تتبعات اختيارية أخرى.</p>

        <h4 class="fw-bold text-dark mt-4 mb-3">4. حقوق المستخدم بموجب اللائحة (GDPR)</h4>
        <p>بصفتك مستخدماً مقيماً في الاتحاد الأوروبي أو تتفاعل مع خدماتنا، توفر لك اللائحة الحقوق التالية:</p>
        <ul>
          <li><strong>حق الوصول:</strong> لك الحق في طلب نسخة من بياناتك الشخصية المحفوظة لدينا.</li>
          <li><strong>حق التصحيح أو الحذف:</strong> لك الحق في تصحيح أي بيانات غير دقيقة أو طلب حذفها متى ما زال الغرض من الاحتفاظ بها منتفياً.</li>
          <li><strong>حق الاعتراض:</strong> لك الحق في الاعتراض على معالجة بياناتك في حالات محددة.</li>
        </ul>
        <p>ل ممارسة أي من هذه الحقوق، يُرجى التواصل معنا مباشرة عبر البريد الإلكتروني الرسمي للشركة.</p>

        <h4 class="fw-bold text-dark mt-4 mb-3">5. أمان وحماية البيانات</h4>
        <p>نطبق أحدث المعايير التقنية والتنظيمية وأعلى بروتوكولات التشفير (HTTPS / SSL) لضمان حماية بياناتك من الوصول غير المصرح به، أو التعديل، أو الفقدان، أو الإفشاء.</p>

      </div>

    </div>
  </section>
  <!-- Privacy Policy Content end -->

<?php 
// استدعاء شريط الموافقة على الكوكيز والفوتر المشترك
if (file_exists(__DIR__ . '/includes/cookie-consent.php')) {
    include_once __DIR__ . '/includes/cookie-consent.php';
}

include_once $path_prefix . 'includes/footer.php'; 
?>
