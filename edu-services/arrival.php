<?php 
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$path_prefix = '../'; 

$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

$arrival_data = $global_data['arrival_page'] ?? [
    'hero_img'            => 'assets/img/education/servicesimg9.png',
    'main_title'          => 'إستمتع برحلتك إلى ألمانيا، وابدأ حياتك الجديدة دون أية مشقة!',
    'main_desc'           => '',
    'advice_title'        => 'ما الذي يجب فعله قبل السفر؟',
    'advice_desc'         => '',
    'tips'                => [],
    'notes'               => [],
    'note_title'          => 'ملاحظات هامة !!',
    'page_breadcrumb'     => 'الإستقبال في المطار',
    'page_breadcrumb_url' => '#'
];

$data['arrival_page'] = $arrival_data;

$page_css = [
    'edu-services/css/edu-services.css'
];
$page_js = [];

include_once $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? 'الإستقبال في المطار، المواصلات، الإقامة والسكن'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيدر والصورة">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="arrival-hero custom-hero" style="background-image: url('<?php echo $path_prefix . htmlspecialchars($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>?v=<?php echo time(); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. قسم العنوان الرئيسي والوصف (مع قلم تعديل مستقل) -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalMainTitleModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($arrival_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($arrival_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. قسم النصائح والإرشادات (مع قلم تعديل مستقل) -->
      <div class="advice-check py-4 mb-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل التوصيات والنصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($arrival_data['advice_title'] ?? ''); ?></h5>
        <p><?php echo htmlspecialchars($arrival_data['advice_desc'] ?? ''); ?></p>
        <div class="row mt-3">
          <?php foreach (($arrival_data['tips'] ?? []) as $tip): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p>✅ <?php echo htmlspecialchars($tip); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. قسم الملاحظات الهامة بالنجوم (مع قلم تعديل مستقل) -->
      <div class="advice-stars my-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($arrival_data['note_title'] ?? ''); ?></h5>
<ul class="star-list">
  <?php foreach (($arrival_data['notes'] ?? []) as $note): ?>
    <li class="mb-2">
      <!-- لاحظي أننا استبدلنا htmlspecialchars بـ echo العادية ليتم تفعيل الـ span والستايل -->
      <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2"><?php echo $note; ?></p>
    </li>
  <?php endforeach; ?>
</ul>

      </div>

    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_arrival_modals.php')) { 
    include_once __DIR__ . '/includes/admin_arrival_modals.php'; 
}

include_once $path_prefix . 'includes/footer.php'; 
?>
