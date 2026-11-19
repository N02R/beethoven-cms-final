<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Bachelor Package Page View (Gold Standard MVC, Multilingual & Security Hardened)
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// نصوص احتياطية مترجمة للغات الثلاث
$i18nDefaults = [
    'de' => [
        'home'          => 'Startseite',
        'higher_edu'    => 'Höhere Bildung',
        'voc_training'  => 'Berufsausbildung',
        'default_bc'    => 'BCS Bachelor Package',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_content'  => 'Inhalt & Passwort bearbeiten',
        'password_lbl'  => 'Passwort:',
        'btn_txt'       => 'Eingeben'
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'BCS Bachelor Package',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_content'  => 'Edit Content & Password',
        'password_lbl'  => 'Password:',
        'btn_txt'       => 'Enter'
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'BCS Bachelor Package',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_content'  => 'تعديل المحتوى وكلمة المرور',
        'password_lbl'  => 'كلمة المرور:',
        'btn_txt'       => 'ادخال'
    ]
];
$txt = $i18nDefaults[$current_lang];

// جلب البيانات بأمان
$data = is_array($data ?? null) ? $data : [];
$bachelor_data = is_array($bachelor_data ?? null) ? $bachelor_data : ($data['bachelor_data'] ?? []);

// تحديد المصدر بناءً على الرابط القادم
$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url = '/' . $current_lang . '/job';
    $parent_name = $txt['voc_training'];
} else {
    $parent_url = '/' . $current_lang . '/education';
    $parent_name = $txt['higher_edu'];
}

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $bachelor_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $bachelor_data['page_breadcrumb'] ?? $txt['default_bc'];
  ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-start">
      <li class="breadcrumb-item"><a href="/<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>/home"><?php echo htmlspecialchars($txt['home'], ENT_QUOTES, 'UTF-8'); ?></a></li>
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($parent_name, ENT_QUOTES, 'UTF-8'); ?></a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="<?php echo htmlspecialchars($bc_url, ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($bc_text, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </li>
    </ol>
  </nav>
</div>
<!-- Breadcrumb end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorContentModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_content'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container text-center">
    <h2 class="pt-5 mb-4"><?php echo htmlspecialchars((string)($bachelor_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
    
    <!-- استخدام المتغير المباشر لدعم وسوم الـ HTML والـ span بأمان -->
    <div class="par-text mb-4"><?php echo $bachelor_data['main_desc'] ?? ''; ?></div>
    
    <div class="pas-info mt-5 d-flex justify-content-center">
      <form action="<?php echo htmlspecialchars((string)($bachelor_data['btn_url'] ?? '#'), ENT_QUOTES, 'UTF-8'); ?>" method="POST" class="pas-form w-100" style="max-width: 460px;">
        <label for="bachelorPackagePassword" class="form-label fw-semibold mb-2">
          <?php echo htmlspecialchars((string)($bachelor_data['password_label'] ?? $txt['password_lbl']), ENT_QUOTES, 'UTF-8'); ?>
        </label>
        <div class="input-group pas-input-group shadow-sm">
          <input
            type="password"
            id="bachelorPackagePassword"
            name="package_password"
            class="form-control pas-input"
            placeholder="••••••••••••"
            required
          >
          <button type="submit" class="btn pas-btn">
            <?php echo htmlspecialchars((string)($bachelor_data['btn_text'] ?? $txt['btn_txt']), ENT_QUOTES, 'UTF-8'); ?>
          </button>
        </div>
      </form>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
// تضمين المودالات الإدارية الخاصة بالصفحة للمشرف فقط
$bachelor_modals_file = __DIR__ . '/includes/admin_bachelor_modals.php';
if ($is_admin_active && file_exists($bachelor_modals_file)) { 
    include_once $bachelor_modals_file; 
}
?>
