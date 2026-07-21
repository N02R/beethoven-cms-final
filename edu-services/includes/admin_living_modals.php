<?php
// منع الوصول المباشر للملف
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

// معالجة طلبات التعديل عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_living_section'])) {
    $section_type = $_POST['update_living_section'] ?? '';
    
    // مسار ملف الإعدادات المركزي
    $config_file = __DIR__ . '/../announcement_config.json';
    $global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
    
    // التأكد من وجود مصفوفة خاصة بصفحة تكلفة المعيشة
    if (!isset($global_data['living_cost_page'])) {
        $global_data['living_cost_page'] = [];
    }

    // 1. تحديث مسار التنقل (Breadcrumb)
    if ($section_type === 'breadcrumb') {
        $global_data['living_cost_page']['page_breadcrumb'] = trim($_POST['page_breadcrumb'] ?? '');
        $global_data['living_cost_page']['page_breadcrumb_url'] = trim($_POST['page_breadcrumb_url'] ?? '#');
    }
    
    // 2. تحديث صورة الهيرو (Hero Image)
    elseif ($section_type === 'hero') {
        if (isset($_FILES['hero_img_file']) && $_FILES['hero_img_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['hero_img_file']['tmp_name'];
            $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['hero_img_file']['name']));
            $upload_dir = __DIR__ . '/../assets/img/education/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $destination = $upload_dir . $file_name;
            if (move_uploaded_file($file_tmp, $destination)) {
                $global_data['living_cost_page']['hero_img'] = 'assets/img/education/' . $file_name;
            }
        }
        if (!empty($_POST['hero_position'])) {
            $global_data['living_cost_page']['hero_position'] = trim($_POST['hero_position']);
        }
    }
    
    // 3. تحديث العنوان والوصف الرئيسي
    elseif ($section_type === 'main') {
        $global_data['living_cost_page']['main_title'] = trim($_POST['main_title'] ?? '');
        $global_data['living_cost_page']['main_desc'] = trim($_POST['main_desc'] ?? '');
    }
    
    // 4. تحديث قسم نصائح لتقليل النفقات (Tips Section)
    elseif ($section_type === 'tips') {
        $tips_title = trim($_POST['tips_title'] ?? 'نصائح لتقليل النفقات');
        $tips_items = [];
        if (isset($_POST['tips_items']) && is_array($_POST['tips_items'])) {
            foreach ($_POST['tips_items'] as $item) {
                $clean_item = trim($item);
                if (!empty($clean_item)) {
                    $tips_items[] = $clean_item;
                }
            }
        }
        $global_data['living_cost_page']['tips_section'] = [
            'title' => $tips_title,
            'items' => $tips_items
        ];
    }
    
    // 5. تحديث قسم الملاحظات الهامة (Notes Section)
    elseif ($section_type === 'notes') {
        $notes_title = trim($_POST['notes_title'] ?? 'ملاحظات هامة !!');
        $notes_items = [];
        if (isset($_POST['notes_items']) && is_array($_POST['notes_items'])) {
            foreach ($_POST['notes_items'] as $item) {
                $clean_item = trim($item);
                if (!empty($clean_item)) {
                    $notes_items[] = $clean_item;
                }
            }
        }
        $global_data['living_cost_page']['notes_section'] = [
            'title' => $notes_title,
            'items' => $notes_items
        ];
    }

    // حفظ البيانات المحدثة في ملف الـ JSON
    file_put_contents($config_file, json_encode($global_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
    // إعادة توجيه لمنع إعادة إرسال النموذج (POST/Redirect/GET)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// جلب البيانات الحالية لعرضها داخل النماذج
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
$living_data = $global_data['living_cost_page'] ?? [];
?>

<!-- 1. مودال تعديل مسار التنقل (Breadcrumb Modal) -->
<div class="modal fade" id="livingBreadcrumbModal" tabindex="-1" aria-labelledby="livingBreadcrumbModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="update_living_section" value="breadcrumb">
      <div class="modal-header">
        <h5 class="modal-title" id="livingBreadcrumbModalLabel">تعديل مسار التنقل</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-end">
        <div class="mb-3">
          <label class="form-label">نص المسار الأخير:</label>
          <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($living_data['page_breadcrumb'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">رابط المسار الأخير (اختياري):</label>
          <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($living_data['page_breadcrumb_url'] ?? '#'); ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<!-- 2. مودال تعديل صورة الهيرو (Hero Image Modal) -->
<div class="modal fade" id="livingHeroModal" tabindex="-1" aria-labelledby="livingHeroModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="update_living_section" value="hero">
      <div class="modal-header">
        <h5 class="modal-title" id="livingHeroModalLabel">تعديل صورة الهيرو</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-end">
        <div class="mb-3">
          <label class="form-label">اختر صورة جديدة:</label>
          <input type="file" class="form-control" name="hero_img_file" accept="image/*">
          <small class="text-muted">اتركها فارغة إذا لم ترد تغيير الصورة الحالية.</small>
        </div>
        <div class="mb-3">
          <label class="form-label">موضع الخلفية (Background Position):</label>
          <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($living_data['hero_position'] ?? 'center center'); ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<!-- 3. مودال تعديل العنوان والوصف الرئيسي (Main Section Modal) -->
<div class="modal fade" id="livingMainModal" tabindex="-1" aria-labelledby="livingMainModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <input type="hidden" name="update_living_section" value="main">
      <div class="modal-header">
        <h5 class="modal-title" id="livingMainModalLabel">تعديل العنوان والوصف الرئيسي</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-end">
        <div class="mb-3">
          <label class="form-label">العنوان الرئيسي (H2):</label>
          <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($living_data['main_title'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">الفقرة الوصفية:</label>
          <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($living_data['main_desc'] ?? ''); ?></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<!-- 4. مودال تعديل نصائح لتقليل النفقات (Tips Modal) -->
<div class="modal fade" id="livingTipsModal" tabindex="-1" aria-labelledby="livingTipsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <input type="hidden" name="update_living_section" value="tips">
      <div class="modal-header">
        <h5 class="modal-title" id="livingTipsModalLabel">تعديل نصائح تقليل النفقات</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-end">
        <div class="mb-3">
          <label class="form-label">عنوان القسم:</label>
          <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($living_data['tips_section']['title'] ?? 'نصائح لتقليل النفقات'); ?>" required>
        </div>
        <hr>
        <label class="form-label fw-bold">النقاط والنصائح:</label>
        <div id="tips-container">
          <?php 
            $tips = $living_data['tips_section']['items'] ?? [];
            if (empty($tips)) { $tips = ['']; }
            foreach ($tips as $tip):
          ?>
            <div class="input-group mb-2">
              <button type="button" class="btn btn-outline-danger remove-item-btn"><i class="bi bi-trash"></i></button>
              <input type="text" class="form-control" name="tips_items[]" value="<?php echo htmlspecialchars($tip); ?>" placeholder="أدخل نص النصيحة هنا..." required>
            </div>
          <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-sm btn-success mt-2" id="add-tip-btn"><i class="bi bi-plus-lg"></i> إضافة نصيحة جديدة</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<!-- 5. مودال تعديل الملاحظات الهامة (Notes Modal) -->
<div class="modal fade" id="livingNotesModal" tabindex="-1" aria-labelledby="livingNotesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <input type="hidden" name="update_living_section" value="notes">
      <div class="modal-header">
        <h5 class="modal-title" id="livingNotesModalLabel">تعديل الملاحظات الهامة</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-end">
        <div class="mb-3">
          <label class="form-label">عنوان القسم:</label>
          <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($living_data['notes_section']['title'] ?? 'ملاحظات هامة !!'); ?>" required>
        </div>
        <hr>
        <label class="form-label fw-bold">قائمة الملاحظات:</label>
        <div id="notes-container">
          <?php 
            $notes = $living_data['notes_section']['items'] ?? [];
            if (empty($notes)) { $notes = ['']; }
            foreach ($notes as $note):
          ?>
            <div class="input-group mb-2">
              <button type="button" class="btn btn-outline-danger remove-item-btn"><i class="bi bi-trash"></i></button>
              <input type="text" class="form-control" name="notes_items[]" value="<?php echo htmlspecialchars($note); ?>" placeholder="أدخل نص الملاحظة هنا..." required>
            </div>
          <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-sm btn-success mt-2" id="add-note-btn"><i class="bi bi-plus-lg"></i> إضافة ملاحظة جديدة</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<!-- سكربت جافاسكريبت ديناميكي لإضافة وحذف العناصر داخل النماذج (Dynamic List Items) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة نصيحة جديدة
    const addTipBtn = document.getElementById('add-tip-btn');
    if (addTipBtn) {
        addTipBtn.addEventListener('click', function() {
            const container = document.getElementById('tips-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <button type="button" class="btn btn-outline-danger remove-item-btn"><i class="bi bi-trash"></i></button>
                <input type="text" class="form-control" name="tips_items[]" value="" placeholder="أدخل نص النصيحة هنا..." required>
            `;
            container.appendChild(div);
        });
    }

    // إضافة ملاحظة جديدة
    const addNoteBtn = document.getElementById('add-note-btn');
    if (addNoteBtn) {
        addNoteBtn.addEventListener('click', function() {
            const container = document.getElementById('notes-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <button type="button" class="btn btn-outline-danger remove-item-btn"><i class="bi bi-trash"></i></button>
                <input type="text" class="form-control" name="notes_items[]" value="" placeholder="أدخل نص الملاحظة هنا..." required>
            `;
            container.appendChild(div);
        });
    }

    // حذف عنصر عند الضغط على زر الحذف (مع التأكد من بقاء عنصر واحد على الأقل)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            const btn = e.target.closest('.remove-item-btn');
            const inputGroup = btn.closest('.input-group');
            const container = inputGroup.parentElement;
            if (container.querySelectorAll('.input-group').length > 1) {
                inputGroup.remove();
            } else {
                alert('يجب أن يبقى عنصر واحد على الأقل في القائمة.');
            }
        }
    });
});
</script>
