<?php
// تأمين المتغيرات الافتراضية
$path_prefix = '/';

$bachelor_data = $data['bachelor_page'] ?? [
    'page_breadcrumb'     => 'BCS Bachelor Package',
    'page_breadcrumb_url' => '#',
    'main_title'          => 'BCS Bachelor Package and Agreement Templet',
    'main_desc'           => 'هذا المستند محمي بكلمة مرور. يرجى <span style="color: #66aeee;">الاتصال بنا</span> للحصول على كلمة المرور<br>هذا المحتوى محمي بكلمة مرور. لإظهار المحتوى يتعين عليك كتابة كلمة المرور في الأدنى:',
    'password_label'      => 'كلمة المرور:',
    'btn_text'            => 'ادخال'
];
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($bachelor_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($bachelor_data['page_breadcrumb'] ?? 'BCS Bachelor Package'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorContentModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل محتوى الصفحة وحماية كلمة المرور">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container text-center">
      <h2 class="pt-5"><?php echo htmlspecialchars($bachelor_data['main_title'] ?? ''); ?></h2>
      
      <!-- استخدام المتغير المباشر لدعم وسوم الـ HTML والـ span بأمان -->
      <p class="text-center pas-text"><?php echo $bachelor_data['main_desc'] ?? ''; ?></p>
      
      <div class="pas-info mt-5">
        <p><?php echo htmlspecialchars($bachelor_data['password_label'] ?? 'كلمة المرور:'); ?> .............................</p>
        <a href="#" class="btn"><?php echo htmlspecialchars($bachelor_data['btn_text'] ?? 'ادخال'); ?></a>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->
