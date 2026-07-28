<?php
// تأمين المتغيرات الافتراضية
$path_prefix = '/';

$lang_data = $data['language_page'] ?? [
    'page_breadcrumb'     => 'الدورة التحضيرية لشهادات اللغة الألمانية',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg12.png',
    'main_title'          => 'الدورات التحضيرية لشهادات اللغة الألمانية',
    'main_desc'           => '',
    'goals_title'         => 'أهداف الدورة التحضيرية',
    'goals'               => [],
    'warning_text'        => '',
    'cost_title'          => 'اماكن الالتحاق والتكلفة',
    'cost_items'          => []
];
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($lang_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($lang_data['page_breadcrumb'] ?? 'الدورة التحضيرية لشهادات اللغة الألمانية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($path_prefix . ltrim($lang_data['hero_img'] ?? 'assets/img/education/servicesimg12.png', '/') . '?v=' . time()); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($lang_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($lang_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. أهداف الدورة التحضيرية -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langGoalsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أهداف الدورة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($lang_data['goals_title'] ?? 'أهداف الدورة التحضيرية'); ?></h5>
        <div class="row mt-3">
          <?php foreach (($lang_data['goals'] ?? []) as $goal): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p>✅ <?php echo htmlspecialchars($goal); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظة شروط القبول -->
      <div class="my-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langWarningModal" style="position: absolute; top: -5px; right: 0; z-index: 10;" title="تعديل نص التنبيه والشروط">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p class="red-text pt-2"><?php echo htmlspecialchars($lang_data['warning_text'] ?? ''); ?></p>
      </div>

      <!-- 4. أماكن الالتحاق والتكلفة -->
      <div class="advice-list py-4 mt-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langCostModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أماكن الالتحاق والتكاليف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($lang_data['cost_title'] ?? 'اماكن الالتحاق والتكلفة'); ?></h5>
        <ul>
          <div class="row">
            <?php foreach (($lang_data['cost_items'] ?? []) as $item): ?>
              <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                <li><span><?php echo htmlspecialchars($item['title'] ?? ''); ?></span> <?php echo htmlspecialchars($item['desc'] ?? ''); ?></li>
              </div>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->
