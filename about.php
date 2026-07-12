<?php 
// تعريف ملفات الـ CSS الخاصة بهذه الصفحة فقط
$page_css = [
    'assets/css/swiper-bundle.min.css',
    'assets/css/about.css',
    'assets/css/responsive-about.css'
];

// تعريف ملفات الـ JS الخاصة بهذه الصفحة فقط
$page_js = [
    'assets/js/swiper-bundle.min.js'
];

// كتابة السكربت المخصص لتشغيل السلايدر
$custom_script = '
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    rtl: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      576: { slidesPerView: 2 },
      992: { slidesPerView: 3 },
      1400: { slidesPerView: 4 },
      1800: { slidesPerView: 5 }
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    }
  });
</script>';

// استدعاء الهيدر
include 'includes/header.php'; 
?>

  <!-- about start -->
  <section class="about py-5">
    <div class="custom-container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 order-2 order-lg-1">
          <h2 class="sec-title mb-3">من نحن</h2>
          <p class="about-par mb-4">شركة بيتهوفن سيتي للخدمات (BCS) تأسست في عام 2014 في بون/ألمانيا.. تُقدم
            شرِكَتُنا أفضل خدمة للعملاء الكِرام بِلُغاتٍ متعددة، بأعلى جودة وبأسعار تنافُسية للأفراد الذين
            يبحثون عن فرص التعليم العالي، التدريب العملي والتوظيف و كذلك فُرصة العلاج الطبي في ألمانيا.</p>
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2">
                    <span class="icon-wrap">
                      <img src="assets/img/About us Icon, image/Company vision.svg" alt="رؤية الشركة">
                    </span>
                    <span>رؤية الشركة</span>
                  </div>
                  <p class="card-text">نطمح لنكون من بين الجهات الرائدة في خدمة الأجانب وتعزيز التفاهم
                    بين الثقافات في ألمانيا.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2">
                    <span class="icon-wrap">
                      <img src="assets/img/About us Icon, image/Company message.svg" alt="رسالة الشركة">
                    </span>
                    <span>رسالة الشركة</span>
                  </div>
                  <p class="card-text">نقدّم خدمات متكاملة ومتعددة اللغات بأسعار منافسة لتحقيق طموحاتك
                    في التعليم، التدريب، والعلاج في ألمانيا.</p>
                </div>
              </div>
            </div>
          </div>
          <a href="#" class="btn btn-about">قراءة المزيد</a>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 position-relative">
          <div class="image-stack">
            <img src="assets/img/about us icon, image/about1.jpg" alt="عمل جماعي" class="img-fluid main-img">
            <div class="sub-img-wrapper">
              <img src="assets/img/about us icon, image/about2.png" alt="Beethoven City" class="img-fluid sub-img">
              <div class="dots-bg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- about end -->

  <!-- services start -->
  <section class="services py-5">
    <div class="custom-container">
      <h2 class="mb-5 sec-title">خدماتنا المميزة</h2>
      <div class="row g-4">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="education.php" class="card-link text-decoration-none">
            <div class="card" style="background: url('assets/img/home/education.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3>التعليم العالي والجامعي</h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="job.php" class="card-link text-decoration-none">
            <div class="card" style="background: url('assets/img/home/traning.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3>التأهيل المهني والعملي</h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- services end -->

  <!-- team start -->
  <section class="team py-5">
    <div class="custom-container">
      <div class="team-text mb-5">
        <h2 class="sec-title">فريق العمل</h2>
        <p class="description main-p">
          يسر فريق العمل تقديم خدمات عالية الجودة لتتناسب مع احتياجات العميل الكريم وتوقعاته.
        </p>
      </div>
      <div class="swiper-container-wrapper">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/4dfcb1defe010736db97eb637f7d9d5dfbb7088a.jpg" alt="أحمد السالم" />
                <div class="info">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/a3ec043b604b16af13ebca0a1c2c3e53cf8b209f.jpg" alt="أحمد السالم" />
                <div class="info active">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/b95db5a85af1ca5e7a9c8a28121a4d22b5bfc36c.jpg" alt="أحمد السالم" />
                <div class="info">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/b371b086e35b526d3fc89c45c2381e0de77a0cb3.jpg" alt="أحمد السالم" />
                <div class="info">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/member1.jpg" alt="أحمد السالم" />
                <div class="info">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="team-card">
                <img src="assets/img/team/member1.jpg" alt="أحمد السالم" />
                <div class="info">
                  <h5>أحمد السالم</h5>
                  <p>مدير المشروع</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-nav-wrapper">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- team end -->

  <!-- count start -->
  <section class="count">
    <div class="custom-container">
      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="count-card">
            <div class="count-img">
              <img src="assets/img/about us icon, image/Program Education.svg" alt="Programs">
            </div>
            <div class="count-info">
              <span>700K+</span>
              <p>برنامج تعليمي</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="count-card">
            <div class="count-img">
              <img src="assets/img/about us icon, image/Course Education.svg" alt="Courses">
            </div>
            <div class="count-info">
              <span>500+</span>
              <p>كورس لغة ألمانية</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="count-card">
            <div class="count-img">
              <img src="assets/img/about us icon, image/Educational institute.svg" alt="Institutes">
            </div>
            <div class="count-info">
              <span>300+</span>
              <p>معهد تعليمي</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="count-card">
            <div class="count-img">
              <img src="assets/img/about us icon, image/Experience Year.svg" alt="Experience">
            </div>
            <div class="count-info">
              <span>6Y+</span>
              <p>سنوات خبرة</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- count end -->

  <!-- partenar start -->
  <section class="partenar py-5">
    <div class="custom-container">
      <h2 class="sec-title mb-5">شركاؤنا داخل وخارج ألمانيا</h2>
      <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 9.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 2.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 3.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 5.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 4.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 6.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 7.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
        <div class="col">
          <div class="partner-item">
            <img src="assets/img/about us icon, image/image 8.jpg" alt="Partner" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- partenar end -->

<?php 
// استدعاء الفوتر
include 'includes/footer.php'; 
?>
