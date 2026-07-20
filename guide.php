<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$path_prefix = ''; 

// 1. تعريف ملفات الـ CSS الخاصة بصفحة الدليل
$page_css = [
    'assets/css/style.css'
];

$page_js = [];

// 2. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// 3. جلب بيانات الدليل من ملف التكوين (JSON) مع القيم الافتراضية المطابقة لتصميمك
$guide_title = $data['guide_title'] ?? 'دليل بيتهوفن الشامل';
$guide_desc  = $data['guide_desc'] ?? 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، حيث يمكنك أن تولد مثل هذا النص من مولد النص العربي.';
$guide_items = $data['guide_items'] ?? [];
?>

  <!-- ===== GUIDE PAGE START ===== -->
  <section class="guide py-5" style="position: relative;">
    <!-- زر التعديل الخاص بالأدمن بنفس ستايل صفحة job -->
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الدليل الشامل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="text-center mb-5">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars($guide_title); ?></h2>
        <p class="main-p mx-auto" style="max-width: 700px;">
          <?php echo nl2br(htmlspecialchars($guide_desc)); ?>
        </p>
      </div>

      <div class="row g-4">
        <?php if (!empty($guide_items)): ?>
          <?php foreach ($guide_items as $item): ?>
            <div class="col-lg-4 col-md-6">
              <div class="card h-100 border-0 shadow-sm">
                <?php if (!empty($item['img'])): ?>
                  <div class="card-img-wrapper">
                    <img src="<?php echo htmlspecialchars($path_prefix . $item['img'] . '?v=' . time()); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'guide image'); ?>" class="card-img-top img-fluid">
                  </div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title fw-bold"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h5>
                  <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item['desc'] ?? ''); ?></p>
                  <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>" class="btn btn-link text-decoration-none fw-bold p-0 mt-3 d-flex align-items-center gap-2">
                    قراءة المزيد
                    <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/home/Arrow..svg'); ?>" alt="arrow" width="18">
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted py-4">
            <p>لا توجد مقالات مضافة في الدليل حالياً.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- ===== GUIDE PAGE END ===== -->

<?php 
// 4. استدعاء الفوتر الأساسي
include_once 'includes/footer.php'; 
?>
