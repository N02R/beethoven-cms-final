<?php 
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// جلب بيانات الصفحة من ملف الـ JSON العام (افتراض أن المتغير $data أو الدالة المسؤولة عن الجلب متاحين)
$german_data = $data['germanlang_page'] ?? [];
$is_admin = $is_admin ?? false; // التأكد من حالة المدير
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($german_data['page_breadcrumb'] ?? 'دورات اللغة الألمانية'); ?></li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container position-relative">
      <?php if ($is_admin): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanHeroModal" style="position: absolute; top: 10px; right: 10px; z-index: 10;" title="تعديل صورة الهيرو والعنوان">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <div class="germanlang-hero custom-hero" style="background-image: url('<?php echo $path_prefix . htmlspecialchars($german_data['hero_img'] ?? 'assets/img/education/servicesimg4.png'); ?>'); background-position: <?php echo htmlspecialchars($german_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- القسم الرئيسي (العنوان والوصف) -->
      <div class="head-info position-relative">
        <?php if ($is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($german_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo htmlspecialchars($german_data['main_desc'] ?? ''); ?></p>
      </div>
      
      <!-- 1. المستويات المتوفرة -->
      <div class="advice-stars my-5 position-relative">
        <?php if ($is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanLevelsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المستويات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $levels_sec = $german_data['levels_section'] ?? [];
          $levels_title = $levels_sec['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)';
          $levels_list = $levels_sec['levels_list'] ?? [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($levels_title); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php if (!empty($levels_list)): ?>
              <?php foreach ($levels_list as $level): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                  <li>
                    <p class="mb-0">
                      <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>
                      <?php echo htmlspecialchars($level); ?>
                    </p>
                  </li>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </ul>
      </div>

      <!-- 2. المميزات والنصائح -->
      <div class="advice-check position-relative">
        <?php if ($is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanFeaturesTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المميزات والنصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <!-- مميزات دوراتنا -->
        <?php 
          $feat_sec = $german_data['features_section'] ?? [];
          $feat_title = $feat_sec['title'] ?? 'مميزات دوراتنا';
          $feat_list = $feat_sec['features_list'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($feat_title); ?></h5>
        <div class="row mb-5">
          <?php if (!empty($feat_list)): ?>
            <?php foreach ($feat_list as $feature): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($feature); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- نصائح للنجاح -->
        <?php 
          $tips_sec = $german_data['tips_section'] ?? [];
          $tips_title = $tips_sec['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية';
          $tips_list = $tips_sec['tips_list'] ?? [];
        ?>
        <h5 class="mt-5 mb-4 advice-text"><?php echo htmlspecialchars($tips_title); ?></h5>
        <div class="row">
          <?php if (!empty($tips_list)): ?>
            <?php foreach ($tips_list as $tip): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($tip); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// تضمين ملفات المودالات الخاصة بالمدير إذا كان مسجلاً لديه صلاحيات
if ($is_admin && file_exists(__DIR__ . '/includes/admin_german_modals.php')) {
    include __DIR__ . '/includes/admin_german_modals.php';
}
?>
