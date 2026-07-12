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
        <li class="breadcrumb-item" aria-current="page">الدورة التأسيسية / السنة التحضيرية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="foundation-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/serviceimg11.png'); background-position: center -20rem">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">الدورة التأسيسيّة/السنة التحضيرية "Studienkolleg"</h2>
        <p class="par-text">السنة التحضيرية أو الدورة التأسيسية هي برنامج أكاديمي يُعدّ جسرًا بين شهادتك الثانوية في بلدك ومتطلبات القبول في الجامعات الألمانية. هذا البرنامج يُعَد إلزاميًا إذا كانت شهادتك لا تعادل الثانوية الألمانية، وهو بمثابة خطوة أساسية لبدء دراستك الجامعية في ألمانيا.</p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text"> أهداف الدورة التأسيسية</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ معادلة المؤهل الدراسي بشروط القبول الجامعي الألماني</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تحسين اللغة الألمانية الأكاديمية</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅  إعدادك بالمواد التخصصية المرتبطة بمجالك الجامعي</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ التأهل لاجتياز امتحان التأهيل الجامعي (FSP)</p>
          </div>
        </div>
      </div>

      <div class="head-info">
        <h5 class="advice-text">ماذا يدرس و يتعلم الطالب خِلال السنة التحضيرية?</h5>
        <p class="par-text" style="font-weight: 500;">سوف تجهز السنه التحضيرية الطالب من خلال فصلين للبرنامج الدراسي “التخصص الأكاديمي” الذي يريد دِراسته في الجامعة وذلك من ناحيتين..</p>
        <p class="par-text"><span style="font-weight: 500;">اولاً:</span> برنامج دراسة اللغة الألمانية، لكي لا يواجه الطالب صعوبات من ناحية اللغة الألمانية خلال الدراسة الجامعيّة.</p>
        <p class="par-text"><span style="font-weight: 500;">ثانياً:</span> الناحية الفنية أو التِقنيّه. من الضروري أن يتقن الطلاب تخصصات العلوم والتربية ذات الصلة بما يريد دراستهُ في الجامعة.</p>
      </div>

      <div class="advice-stars my-5">
        <h5 class="mb-4 advice-text">أنواع دورات السنة التحضيرية</h5>
        <ul class="star-list">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>M‑Kurs: الطب، الأحياء، الصيدلة</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>T‑Kurs: الهندسة، الرياضيات، الفيزياء</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>S‑Kurs: اللغات</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>W‑Kurs: الإدارة، الاقتصاد، العلوم الاجتماعية</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>G‑Kurs: التاريخ، الأدب، الإحصاء</p>
              </li>
            </div>
          </div>
        </ul>
      </div>

      <div class="head-info">
        <h5 class="advice-text">الفرق بين السنة التحضيرية من حيث إرتباطها بالجامعات العامة أو جامعات العلوم التطبيقية؟</h5>
        <p class="par-text" style="font-weight: 500;">ترتبط السنة التحضيرية إما بالجامعات العامة أو بجامعات العلوم التطبيقية، (ونقصَد هنا، أن برنامج السنة التحضيرية يكون من خلال الجامعة العامة المُتعارف عليها و تحت إشرافِها أو يكون تحت إشراف جامعة العلوم التطبيقية).</p>
        <p class="par-text"><span style="font-weight: 500;">الجامعات العامة:</span>تؤهلك للدراسة في جميع الجامعات الألمانية، بما فيها جامعات العلوم التطبيقية، بعد اجتياز امتحان التأهيل الجامعي</p>
        <p class="par-text"><span style="font-weight: 500;">جامعات العلوم التطبيقية:</span>تؤهلك للدراسة في معظم جامعات العلوم التطبيقية فقط، ولا تشمل الجامعات العامة</p>
      </div>

      <div class="head-info py-5">
        <h5 class="advice-text">أنواع السنة التحضيرية في ألمانيا</h5>
        <p class="par-text"><span style="font-weight: 500;">1. السنة التحضيرية الحكومية:</span><br> تُشرف عليها الجامعات، وتُعد شبه مجانية (رسوم فصل دراسي بين 150–300 يورو). القبول يتطلب اجتياز امتحان تنافسي بسبب محدودية المقاعد</p>
        <p class="par-text"><span style="font-weight: 500;"> 2. السنة التحضيرية الخاصة:</span><br>خيار مناسب لمن لم يحالفهم الحظ بالجامعات الحكومية. لا يتطلب امتحان قبول، لكن بتكلفة أعلى (بين 1800–3000 يورو للفصل الواحد)</p>
      </div>

      <div class="advice-stars">
        <h5 class="note-text">ملاحظات هامة !!</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>اختر السنة التحضيرية المناسبة لتخصصك</p>
          </li>
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>تحقق من صلاحية الشهادة للتخصص المطلوب</p>
          </li>
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>تأكد إن كانت الشهادة تؤهلك لجميع الجامعات أو فقط لجامعات العلوم التطبيقية</p>
          </li>
        </ul>
      </div>

      <div class="head-info py-5">
        <h5 class="mb-4 advice-text">اختبار القبول للسنة التحضيرية (Aufnahmeprüfung)</h5>
        <p class="par-text" style="font-weight: 500;">للالتحاق بالسنة التحضيرية الحكومية في ألمانيا، يجب اجتياز اختبار قبول تنافسي، حيث يتقدم عدد كبير من الطلاب لمقاعد محدودة. يُنصح بالاستعداد الجيد، ويتطلب عادة مستوى B2 في اللغة الألمانية، وقد يُقبل B1 في بعض المعاهد. يشمل الامتحان اللغة والرياضيات.</p>
      </div>

      <div class="head-info">
        <h5 class="mb-4 advice-text">المدة والتقييم النهائي (Feststellungsprüfung - FSP)</h5>
        <p class="par-text" style="font-weight: 500;">تستغرق السنة التحضيرية فصلين دراسيين. بنهايتها، يخضع الطالب لاختبار "FSP" الذي يؤهله للالتحاق بالجامعات. تتراوح نتائجه بين 1.0 (ممتاز) و5.0 (راسب). بعض الطلاب قد يجتازونه بعد فصل واحد فقط في حالات استثنائية.</p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text"> نصائح مهمة قبل التقديم</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تأكد أنك مؤهل للدورة المناسبة لتخصصك</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅  تحقق إن كانت الشهادة معترف بها في الجامعات العامة أو التطبيقية</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تأكد أن الشهادة بعد الدورة صالحة للتخصص المطلوب</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ جهّز نفسك جيدًا لاجتياز امتحان القبول</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
