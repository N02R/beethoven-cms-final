<?php 
// تعريف البادئة للوصول للمجلد الرئيسي
$path_prefix = '../'; 

// تعريف ملفات الـ CSS الخاصة بهذه الصفحة والموجودة في المجلد الرئيسي
$page_css = [
    $path_prefix . 'css/edu-services.css',
    $path_prefix . 'assets/css/style.css' // في حال كان الستايل الأساسي في assets
];

// استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

<!-- ===== BREADCRUMB START ===== -->
<div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-start">
            <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
            <li class="breadcrumb-item active" aria-current="page">الإستقبال في المطار، المواصلات، الإقامة والسكن</li>
        </ol>
    </nav>
</div>
<!-- ===== BREADCRUMB END ===== -->

<!-- ===== HERO SECTION START ===== -->
<section class="custom-services py-5">
    <div class="custom-container">
        <!-- المسار الآن صحيح بفضل path_prefix -->
        <div class="arrival-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg9.png');"></div>
    </div>
</section>
<!-- ===== HERO SECTION END ===== -->

<!-- ===== INFO SECTION START ===== -->
<section class="custom-services-info py-5">
    <div class="custom-container">
        <div class="head-info">
            <h2 class="main-text">إستمتع برحلتك إلى ألمانيا، وابدأ حياتك الجديدة دون أية مشقة!</h2>
            <p class="par-text">
                كثيرٌ من الطلبة يَصِلون إلى ألمانيا دون ترتيب وحجز مكان السكن والإقامة أو حجز وسيلة نقل مناسبة. 
                سيواجه هؤلاء الطلاب الكثير من الضغط النفسي والمشقة وسينفقون الكثير من المال للمبيت في الفنادق.
            </p>
        </div>

        <div class="advice-check py-5">
            <h5 class="advice-text">ما الذي يجب فعله قبل السفر؟</h5>
            <div class="row mt-4">
                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <p>✅ احجز الاستقبال ووسيلة النقل من المطار مسبقًا</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <p>✅ رتب السكن قبل الوصول بشهر لتفادي التكاليف المرتفعة</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <p>✅ تأكد من موقع السكن لتسهيل الإجراءات الرسمية</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <p>✅ لا تستخدم المواصلات بدون تذكرة لتجنب الغرامات القانونية</p>
                </div>
            </div>
        </div>

        <div class="advice-stars my-5">
            <h5 class="mb-4 note-text">ملاحظات هامة !!</h5>
            <ul class="star-list list-unstyled">
                <li class="mb-3">
                    <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="icon" class="ms-2"> العثور على سكن خلال فترة قصيرة أمر صعب جدًا، لذا ننصح بحجز السكن مسبقًا.</p>
                </li>
                <li>
                    <p>
                        <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="icon" class="ms-2"> 
                        إذا كنت ترغب بحجز هذه الخدمات من خلال شركتنا، يرجى التواصل عبر البريد الإلكتروني الخاص بالشركة قبل أسبوعين من موعد وصولك.
                    </p>
                </li>
            </ul>
        </div>
    </div>
</section>
<!-- ===== INFO SECTION END ===== -->

<?php 
// استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
