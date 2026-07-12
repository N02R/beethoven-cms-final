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
        <li class="breadcrumb-item" aria-current="page">السيرة الذاتية CV</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg2.jpg'); background-position: center -30px;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">السيرة الذاتية "CV"</h2>
        <p class="par-text">السيرة الذاتيّة (CV) هي الجزء المكتوب عنك والذي يريد معرفتهُ الطرف الآخر (الشخص الذي سيُجري
          المقابلة معك) وتُكتب بشكل مختصر جداً (صفحة واحدة أو إثنتان كأقصى حد، باللغة الألمانية أو الإنجليزية) . وهي
          أيضاً وثيقة قيمة ومهمة جداً لأنها ستكون أول ما تُعبر به عن نفسك وربما تكون أداة الاتصال الوحيدة المباشرة مع
          الطرف الآخر (في هذه الحالة السفارة / القنصلية الألمانية أو موظف القبول في الشركة أو الأستاذ المشرف في الجامعة،
          الخ).</p>
      </div>
      
      <div class="advice-check pt-5">
        <h5 class="advice-text">نصائح سريعة لكتابة CV فعّال</h5>
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅️ استخدم تنسيق بسيط ومرتب</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ رتب المعلومات من الأحدث إلى الأقدم</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ اجعلها صفحة أو صفحتين بحد أقصى</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ ركز على ما يهم الجهة المستلمة</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ تجنب الزخرفة أو الألوان الغير رسمية.</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ راجع اللغة والإملاء جيداً .</p></div>
        </div>
      </div>

      <!-- Row 1: PDF & Word examples -->
      <div class="row mt-5">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card mb-3">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="PDF Icon" />
              <div class="dl-info">
                <div class="dl-title">السيرة الذاتية "CV"</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none"
                aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="#" download>Download</a>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card mb-3">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Groupword.png" alt="Word Icon" />
              <div class="dl-info">
                <div class="dl-title">السيرة الذاتية "CV"</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none"
                aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="#" download>Download</a>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Groupword.png" alt="Word Icon" />
              <div class="dl-info">
                <div class="dl-title">السيرة الذاتية "CV"</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none"
                aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="#" download>Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
