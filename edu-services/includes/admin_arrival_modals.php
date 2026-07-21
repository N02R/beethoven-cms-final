<!-- Modal: تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade" id="arrivalBreadcrumbModal" tabindex="-1" aria-labelledby="arrivalBreadcrumbModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="arrivalBreadcrumbForm">
        <input type="hidden" name="action" value="update_arrival_breadcrumb">
        <div class="modal-header">
          <h5 class="modal-title" id="arrivalBreadcrumbModalLabel">تعديل عنوان مسار التنقل (Breadcrumb)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">اسم الصفحة الحالي في المسار:</label>
            <input type="text" name="page_breadcrumb" class="form-control" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">رابط الصفحة (URL):</label>
            <input type="text" name="page_breadcrumb_url" class="form-control" value="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
            <div class="form-text">اكتب `#` إذا كانت الصفحة الحالية ولا تتطلب رابطاً تفاعلياً.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal 1: تعديل الهيرو والصورة ومسار التنقل -->
<div class="modal fade" id="arrivalHeroModal" tabindex="-1" aria-labelledby="arrivalHeroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="arrivalHeroForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_arrival_hero">
        <div class="modal-header">
          <h5 class="modal-title" id="arrivalHeroModalLabel">تعديل القسم العلوي (الهيرو والصورة)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">صورة الهيدر الحالية:</label>
            <div class="mb-2">
              <img src="<?php echo $path_prefix . ($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>" alt="Hero" class="img-thumbnail" style="max-height: 120px;">
            </div>
            <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>">
            <label class="form-label">رفع صورة جديدة:</label>
            <input type="file" name="hero_img" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: تعديل محتوى وإرشادات الصفحة -->
<div class="modal fade" id="arrivalContentModal" tabindex="-1" aria-labelledby="arrivalContentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form id="arrivalContentForm">
        <input type="hidden" name="action" value="update_arrival_content">
        <div class="modal-header">
          <h5 class="modal-title" id="arrivalContentModalLabel">تعديل المحتوى، النصائح، والملاحظات</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          
          <!-- العنوان الرئيسي والوصف -->
          <div class="card p-3 mb-3 border-0 shadow-sm">
            <h6 class="fw-bold mb-3">العنوان الرئيسي والوصف</h6>
            <div class="mb-3">
              <label class="form-label">العنوان الرئيسي (Main Title):</label>
              <input type="text" name="main_title" class="form-control" value="<?php echo htmlspecialchars($arrival_data['main_title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">الوصف التفصيلي (Main Description):</label>
              <textarea name="main_desc" class="form-control" rows="4" required><?php echo htmlspecialchars($arrival_data['main_desc'] ?? ''); ?></textarea>
            </div>
          </div>

          <!-- قسم النصائح والإرشادات -->
          <div class="card p-3 mb-3 border-0 shadow-sm">
            <h6 class="fw-bold mb-3">قسم الإرشادات والنصائح</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">عنوان التوصيات:</label>
                <input type="text" name="advice_title" class="form-control" value="<?php echo htmlspecialchars($arrival_data['advice_title'] ?? ''); ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">وصف التوصيات الفرعي:</label>
                <input type="text" name="advice_desc" class="form-control" value="<?php echo htmlspecialchars($arrival_data['advice_desc'] ?? ''); ?>">
              </div>
            </div>

            <label class="form-label fw-bold">قائمة النصائح (أدخل كل نصيحة في حقل):</label>
            <div id="tips-container">
              <?php if (!empty($arrival_data['tips'])): ?>
                <?php foreach ($arrival_data['tips'] as $tip): ?>
                  <div class="input-group mb-2 tip-item">
                    <input type="text" name="tips[]" class="form-control" value="<?php echo htmlspecialchars($tip); ?>">
                    <button type="button" class="btn btn-outline-danger remove-tip-btn"><i class="bi bi-trash"></i></button>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-tip-btn"><i class="bi bi-plus-lg"></i> إضافة نصيحة جديدة</button>
          </div>

          <!-- الملاحظات الهامة -->
          <div class="card p-3 border-0 shadow-sm">
            <h6 class="fw-bold mb-3">الملاحظات الهامة</h6>
            <div class="mb-3">
              <label class="form-label">عنوان صندوق الملاحظات:</label>
              <input type="text" name="note_title" class="form-control" value="<?php echo htmlspecialchars($arrival_data['note_title'] ?? ''); ?>">
            </div>
            
            <label class="form-label fw-bold">قائمة الملاحظات:</label>
            <div id="notes-container">
              <?php if (!empty($arrival_data['notes'])): ?>
                <?php foreach ($arrival_data['notes'] as $note): ?>
                  <div class="input-group mb-2 note-item">
                    <!-- نستخدم textarea لأن الملاحظات قد تحتوي على وسم HTML مثل span -->
                    <textarea name="notes[]" class="form-control" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                    <button type="button" class="btn btn-outline-danger remove-note-btn"><i class="bi bi-trash"></i></button>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-note-btn"><i class="bi bi-plus-lg"></i> إضافة ملاحظة جديدة</button>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary">حفظ كافة التغييرات</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>

// جافاسكريبت تفاعلي ديناميكي متكامل لإدارة صفحة الاستقبال والخدمات (Arrival Page)
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. إضافة نصيحة جديدة برمجياً
    const addTipBtn = document.getElementById('add-tip-btn');
    if (addTipBtn) {
        addTipBtn.addEventListener('click', function() {
            const container = document.getElementById('tips-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2 tip-item';
            div.innerHTML = `
                <input type="text" name="tips[]" class="form-control" placeholder="اكتب النصيحة هنا...">
                <button type="button" class="btn btn-outline-danger remove-tip-btn"><i class="bi bi-trash"></i></button>
            `;
            container.appendChild(div);
        });
    }

    // 2. إضافة ملاحظة جديدة برمجياً
    const addNoteBtn = document.getElementById('add-note-btn');
    if (addNoteBtn) {
        addNoteBtn.addEventListener('click', function() {
            const container = document.getElementById('notes-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2 note-item';
            div.innerHTML = `
                <textarea name="notes[]" class="form-control" rows="2" placeholder="اكتب الملاحظة هنا..."></textarea>
                <button type="button" class="btn btn-outline-danger remove-note-btn"><i class="bi bi-trash"></i></button>
            `;
            container.appendChild(div);
        });
    }

    // 3. الحذف المفوض (Event Delegation) لأي عنصر نصيحة أو ملاحظة يتم حذفه
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-tip-btn')) {
            const item = e.target.closest('.tip-item');
            if (item) item.remove();
        }
        if (e.target.closest('.remove-note-btn')) {
            const item = e.target.closest('.note-item');
            if (item) item.remove();
        }
    });

    // 4. الدالة الموحدة لمعالجة وإرسال النماذج عبر AJAX
    const handleFormSubmit = (formId) => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('../dashboard/save_config.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // إعادة تحميل الصفحة لتحديث البيانات فوراً
                    } else {
                        alert('حدث خطأ: ' + (data.message || 'يرجى المحاولة لاحقاً'));
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('حدث خطأ في الاتصال بالسيرفر.');
                });
            });
        }
    };

    // تفعيل الإرسال الآلي لكل النماذج الموجودة في الصفحة
    handleFormSubmit('arrivalHeroForm');
    handleFormSubmit('arrivalContentForm');
    handleFormSubmit('arrivalBreadcrumbForm');
});

</script>