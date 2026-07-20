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

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">الضمانات المالية والحساب البنكي المغلق</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="financial-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg7.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">إثبات الضمانات المالية/التمويل المالي/الكفالة المالية/الحساب البنكي المغلق</h2>
        <p class="par-text">تكاليف المعيشة في ألمانيا مرتفعة مقارنة بالدول النامية، لذلك يجب على الطالب الأجنبي توفير مصادر مالية كافية لتغطية نفقات دراسته وحياته اليومية، حيث يحتاج عادةً إلى حوالي 850يورو شهرياً لتلبية احتياجاته. ويعتبر إثبات الضمانات المالية شرطاً أساسياً للحصول على تأشيرة الدراسة، وبدونه تكون فرص قبول الطلب ضئيلة أو شبه معدومة.</p>
        
        <h5 class="advice-text mt-5">أهمية إثبات الضمان المالي</h5>
        <p>يُعد إثبات الضمانات المالية متطلبًا أساسيًا للتقديم على تأشيرة الطالب أو الدراسة في ألمانيا. مع عدم وجود هذه الضمانات، لديك فرصة ضئيلة أو شبه معدومة للحصول على التأشيرة.</p>
      </div>

      <div class="advice-check my-5">
        <h5 class="advice-text">خيارات الضمان المالي للدراسة في ألمانيا</h5>
        <p>✅️الحصول على منحة دراسية من مؤسسة تعليمية ألمانية معترف بها " هناك العديد من المنظمات في ألمانيا تقدم منحًا للطلاب الأجانب لدعم برامج البكالوريس والماجستير والدكتوراه".</p>
        <p>✅️فتح حساب بنكي مغلق بإسم الطالب في إحدى البنوك الألمانية المخصصة لهذا الغرض لإيداع مبلغ مالي وقدره 10236 يورو وذلك قبل الوصول إلى ألمانيا وإثبات ذلك بوثيقة يتم إرفاقها مع طلب التأشيرة.</p>
      </div>

      <div class="advice-stars">
        <h5 class="advice-text">الحساب البنكي المغلق</h5>
        <ul class="star-list">
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>الحساب البنكي المُغلق هو حساب آمن (للإيداع / والضمان) لإثبات أن لديك ما يكفي من المال لتغطية تكاليف المعيشة في ألمانيا.قبل السفر إلى ألمانيا، يتعين على الطالب إيداع مبلغ معين من المال يبلغ حوالي 10236 يورو في حساب بنكي بإسمه، ولا يمكنك سحب الأموال من هذا الحساب البنكي كما تريد أو بقدر ما تشاء، ولكن يمكنك شهريًا الحصول على مبلغ محدود من المال (حوالي 853 يورو) لتغطية تكاليف المعيشة الخاصة بك.
            </p>
          </li>
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>عند وصولِك إلى ألمانيا، يجب أن تقوم بفتح حساب بنكي غير مُغلق (حساب جاري Giro) في البنك الذي تراه مناسباَ لك، ليتم تحويل المبلغ الشهري الذي تحتاجه لمصروفاتك وهو حوالي 853 يورو شهرياً من حسابك البنكي المغلق إليهِ.
            </p>
          </li>
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>يُساعدك موظف البنك في الحصول على البطاقة البنكية EC وإرشادك حول كيفية إستخدام الخدمات المصرفية عبر الإنترنت وكذلك كيفية إستخدام أجهزة الصراف الآلي.
            </p>
          </li>
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>الهدف من الحساب المغلق هو تأمين تكاليف المعيشة للطالب دون الاعتماد على الدولة.
            </p>
          </li>
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>يمنحك وقتًا للتركيز على الدراسة وتعلم اللغة دون القلق من البحث عن عمل لتغطية نفقاتك.
            </p> 
          </li>
        </ul>
      </div>

      <div class="py-5">
        <div class="custom-container">
          <div class="link active">
            <p class="text-center">إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة- Dr.WALTER</p>
          </div>
          <div class="link mt-4">
            <p class="text-center">إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة - FINTIBA </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
// 4. استدعاء الفوتر المشترك

?>
