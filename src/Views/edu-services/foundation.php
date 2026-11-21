<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Foundation Course / Studienkolleg Page View (Gold Standard MVC, Multilingual & Security Hardened)
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

$i18nDefaults = [
    'de' => [
        'home'          => 'Startseite',
        'higher_edu'    => 'Höhere Bildung',
        'voc_training'  => 'Berufsausbildung',
        'default_bc'    => 'Studienkolleg / Vorbereitungskurs',
        'goals_ttl'     => 'Ziele des Studienkollegs',
        'courses_ttl'   => 'Kurstypen des Studienkollegs',
        'types_ttl'     => 'Arten des Studienkollegs in Deutschland',
        'notes_ttl'     => 'Wichtige Hinweise !!',
        'tips_ttl'      => 'Wichtige Tipps vor der Bewerbung',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_goals'    => 'Kursziele bearbeiten',
        'edit_learn'    => 'Studieninhalte bearbeiten',
        'edit_courses'  => 'Kurstypen bearbeiten',
        'edit_uni'      => 'Hochschulbezug bearbeiten',
        'edit_types'    => 'Staatlich & Privat bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'edit_exam'     => 'Aufnahmeprüfung & FSP bearbeiten',
        'edit_tips'     => 'Tipps bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Foundation Course / Studienkolleg',
        'goals_ttl'     => 'Foundation Course Objectives',
        'courses_ttl'   => 'Types of Studienkolleg Courses',
        'types_ttl'     => 'Types of Studienkolleg in Germany',
        'notes_ttl'     => 'Important Notes !!',
        'tips_ttl'      => 'Important Tips Before Applying',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_goals'    => 'Edit Course Objectives',
        'edit_learn'    => 'Edit Learning Content',
        'edit_courses'  => 'Edit Course Types',
        'edit_uni'      => 'Edit University Relationship',
        'edit_types'    => 'Edit Public & Private Types',
        'edit_notes'    => 'Edit Important Notes',
        'edit_exam'     => 'Edit Entrance Exam & FSP',
        'edit_tips'     => 'Edit Tips',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'الدورة التأسيسية / السنة التحضيرية',
        'goals_ttl'     => 'أهداف الدورة التأسيسية',
        'courses_ttl'   => 'أنواع دورات السنة التحضيرية',
        'types_ttl'     => 'أنواع السنة التحضيرية في ألمانيا',
        'notes_ttl'     => 'ملاحظات هامة !!',
        'tips_ttl'      => 'نصائح مهمة قبل التقديم',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان الرئيسي والوصف',
        'edit_goals'    => 'تعديل أهداف الدورة',
        'edit_learn'    => 'تعديل المحتوى الدراسي',
        'edit_courses'  => 'تعديل أنواع الدورات',
        'edit_uni'      => 'تعديل علاقة الجامعات',
        'edit_types'    => 'تعديل الأنواع الحكومية والخاصة',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'edit_exam'     => 'تعديل اختبار القبول والتقييم',
        'edit_tips'     => 'تعديل النصائح',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$stk_data = is_array($stk_data ?? null) ? $stk_data : (is_array($foundation_page ?? null) ? $foundation_page : ($data['stk_data'] ?? []));

$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url  = '/' . $current_lang . '/job';
    $parent_name = $txt['voc_training'];
} else {
    $parent_url  = '/' . $current_lang . '/education';
    $parent_name = $txt['higher_edu'];
}

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $stk_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $stk_data['page_breadcrumb'] ?? $txt['default_bc'];
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
</div>
<!-- Breadcrumb end -->

<!-- ================= CUSTOM HERO SECTION START ================= -->
<section class="custom-services py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($stk_data['hero_img'] ?? null, 'assets/img/education/serviceimg11.png');
    $hero_pos = htmlspecialchars((string)($stk_data['hero_position'] ?? 'center -20rem'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="foundation-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- 1. العنوان الرئيسي -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars((string)($stk_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 2. أهداف الدورة التأسيسية -->
    <div class="advice-check py-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkGoalsModal" title="<?php echo htmlspecialchars($txt['edit_goals'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($stk_data['goals_title'] ?? $txt['goals_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php 
          $goals_items = $stk_data['goals_items'] ?? [];
          if (is_array($goals_items) && !empty($goals_items)):
              foreach ($goals_items as $goal): 
                  if (empty($goal)) continue;
          ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
              <p class="mb-0">✅ <?php echo htmlspecialchars((string)$goal, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php 
              endforeach;
          else:
          ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. ماذا يدرس و يتعلم الطالب -->
    <div class="head-info py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkLearningModal" title="<?php echo htmlspecialchars($txt['edit_learn'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-3"><?php echo htmlspecialchars((string)($stk_data['learning_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo htmlspecialchars((string)($stk_data['learning_intro'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['learning_p1'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['learning_p2'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 4. أنواع دورات السنة التحضيرية -->
    <div class="advice-stars my-5 pb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkCoursesModal" title="<?php echo htmlspecialchars($txt['edit_courses'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars((string)($stk_data['courses_title'] ?? $txt['courses_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="row">
            <?php 
            $courses_items = $stk_data['courses_items'] ?? [];
            if (is_array($courses_items) && !empty($courses_items)):
                foreach ($courses_items as $course): 
                    if (empty($course)) continue;
            ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <li>
                  <p class="mb-0 d-flex align-items-start">
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                    <span><?php echo htmlspecialchars((string)$course, ENT_QUOTES, 'UTF-8'); ?></span>
                  </p>
                </li>
              </div>
            <?php 
                endforeach;
            else:
            ?>
              <div class="col-12 text-muted">
                <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </ul>
      </div>
    </div>

    <!-- 5. ارتباطها بالجامعات -->
    <div class="head-info py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkUniTypeModal" title="<?php echo htmlspecialchars($txt['edit_uni'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-3"><?php echo htmlspecialchars((string)($stk_data['uni_type_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo htmlspecialchars((string)($stk_data['uni_type_intro'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['uni_public'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['uni_applied'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 6. أنواع السنة التحضيرية (حكومية / خاصة) -->
    <div class="head-info py-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkTypesModal" title="<?php echo htmlspecialchars($txt['edit_types'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($stk_data['types_title'] ?? $txt['types_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="par-text mb-4"><?php echo nl2br(htmlspecialchars((string)($stk_data['type_public_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($stk_data['type_private_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 7. ملاحظات هامة -->
    <div class="advice-stars py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkNotesModal" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars((string)($stk_data['notes_title'] ?? $txt['notes_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="d-flex flex-column gap-2">
            <?php 
            $notes_items = $stk_data['notes_items'] ?? [];
            if (is_array($notes_items) && !empty($notes_items)):
                foreach ($notes_items as $note): 
                    if (empty($note)) continue;
            ?>
              <li>
                <p class="mb-0 d-flex align-items-start">
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                  <span><?php echo htmlspecialchars((string)$note, ENT_QUOTES, 'UTF-8'); ?></span>
                </p>
              </li>
            <?php 
                endforeach;
            else:
            ?>
              <li class="text-muted">
                <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
              </li>
            <?php endif; ?>
          </div>
        </ul>
      </div>
    </div>

    <!-- 8. اختبار القبول والـ FSP -->
    <div class="head-info py-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkExamFspModal" title="<?php echo htmlspecialchars($txt['edit_exam'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-3 advice-text"><?php echo htmlspecialchars((string)($stk_data['exam_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="par-text mb-4" style="font-weight: 500;"><?php echo nl2br(htmlspecialchars((string)($stk_data['exam_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>

        <h5 class="mb-3 advice-text mt-5"><?php echo htmlspecialchars((string)($stk_data['fsp_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo nl2br(htmlspecialchars((string)($stk_data['fsp_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 9. نصائح مهمة قبل التقديم -->
    <div class="advice-check py-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkTipsModal" title="<?php echo htmlspecialchars($txt['edit_tips'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($stk_data['tips_title'] ?? $txt['tips_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php 
          $tips_items = $stk_data['tips_items'] ?? [];
          if (is_array($tips_items) && !empty($tips_items)):
              foreach ($tips_items as $tip): 
                  if (empty($tip)) continue;
          ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
              <p class="mb-0">✅ <?php echo htmlspecialchars((string)$tip, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php 
              endforeach;
          else:
          ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$foundation_modals_file = __DIR__ . '/includes/admin_studienkolleg_modals.php';
if ($is_admin_active && file_exists($foundation_modals_file)) { 
    include_once $foundation_modals_file; 
}
?>
