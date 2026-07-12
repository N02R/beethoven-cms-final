<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item active" aria-current="page">تحقق من شهاداتك التعليمية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg13.png'); background-position: center -9rem;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">
          إفحص و تحقق من شهاداتك التعليمية السابقة
        </h2>
        <p class="par-text">
          إذا كنت تخطط للتقديم إلى برنامج دراسي جامعي أو دراسات عليا في ألمانيا، فإن أول خطوة أساسية هي التحقق
          من اعتراف ألمانيا بمؤهلك العلمي، سواء كانت: شهادة ثانوية عامة شهادة بكالوريوس أو ماجستير
        </p>
      </div>
      <div class="advice-stars my-5">
        <h5 class=" mb-4 note-text"> ملاحظات هامة !!</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2"/>شهادات التعليم الثانوي من بعض الدول لا تؤهلك مباشرة للالتحاق بالجامعات الألمانية</p>
          </li>
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2"/>يجب عليك إكمال السنة التحضيرية أو اجتياز شهادات لغة معترف بها مثل DSH أو TestDaF</p>
          </li>
        </ul>
      </div>
      <div class="links">
        <p class="mt-5">لفحص الإعتراف بشهادتك ومتطلبات القبول لدى الجامعات الألمانية، وهل ماتحمله من شهادة
          علمية يؤهلك لدخول الجامعات الألمانية، يمكنك إستخدام الروابط التالية:</p>
        <a href="https://anabin.kmk.org/anabin.html" target="_blank" rel="noopener">https://anabin.kmk.org/anabin.html</a>
        <a href="https://www.uni-assist.de" target="_blank" rel="noopener">https://www.uni-assist.de</a>
        <p class="mt-5">أو يمكنك التواصل مباشرةً مع الجامعة التي ترغب الدراسة بِها لمعرفة الشرطين الأساسيين
          لقبول مؤهلك العلمي وإمكانِية تقديم طلب التسجيل في الجامعة وهما:</p>
        <span class="span-list">1. الإعتراف بمؤهلاتك العلمية السابقة في ألمانيا.</span>
        <span class="span-list">2. شروط القبول لدى الجامعات الألمانية أي: (ما يسبُق عليك إنجازه لبدء دراستك في
          الجامعات الألمانية مثل الدورة التأسيسية (السنة التحضيرية) أم شهادات لغة ألمانية مثل DSH, TestDaf
          وغيرها</span>
        <p class="mt-5">في حال تم الإعتراف بمؤهلاتك العلمية السابقة في ألمانيا، فمن المرجح أنك (وفي معظم
          الحالات) ستقوم بالإلتحاق إما <span style="color: #66aeee;"> بالدورة التأسيسية (السنة التحضيرية)
            “Studienkolleg” </span>او <span style="color: #66aeee;">بالدورة التحضيرية لشهادة اللغة
            الألمانية، مثل DSH ،TestD</span>، أو غيرها. مما يجعل شهادتك الدراسية مُؤهلة (مُحققة لشروط)
          للإلتحاق في الجامعات الألمانية.
        </p>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
