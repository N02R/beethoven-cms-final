<?php
// التأكد من عدم الوصول المباشر للملف
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}
?>

<!-- 1. Modal تعديل مسار التنقل (Breadcrumb) -->
<div class="modal fade" id="priceListBreadcrumbModal" tabindex="-1" aria-labelledby="priceListBreadcrumbModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="admin-config-form" data-action="update_pricelist_breadcrumb">
        <div class="modal-header">
          <h5 class="modal-title" id="priceListBreadcrumbModalLabel">تعديل مسار التنقل (Breadcrumb)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_pricelist_breadcrumb">
          <div class="mb-3">
            <label class="form-label">نص المسار الأخير</label>
            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">رابط المسار الأخير</label>
            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb_url'] ?? '#'); ?>">
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

<!-- 2. Modal تعديل صورة الهيرو (Hero) -->
<div class="modal fade" id="priceListHeroModal" tabindex="-1" aria-labelledby="priceListHeroModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="admin-config-form" data-action="update_pricelist_hero" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="priceListHeroModalLabel">تعديل صورة الهيرو</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_pricelist_hero">
          <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($pricelist_data['hero_img'] ?? ''); ?>">
          
          <div class="mb-3 text-center">
            <img src="<?php echo $path_prefix . htmlspecialchars($pricelist_data['hero_img'] ?? ''); ?>" alt="Hero Preview" class="img-fluid rounded mb-2" style="max-height: 150px;">
          </div>
          <div class="mb-3">
            <label class="form-label">رفع صورة جديدة</label>
            <input type="file" class="form-control" name="hero_img" accept="image/*">
          </div>
          <div class="mb-3">
            <label class="form-label">توضع الصورة (Hero Position)</label>
            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($pricelist_data['hero_position'] ?? 'center center'); ?>">
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

<!-- 3. Modal تعديل العنوان والوصف الرئيسي (Main Section) -->
<div class="modal fade" id="priceListMainModal" tabindex="-1" aria-labelledby="priceListMainModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="admin-config-form" data-action="update_pricelist_main">
        <div class="modal-header">
          <h5 class="modal-title" id="priceListMainModalLabel">تعديل العنوان والوصف الرئيسي</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_pricelist_main">
          <div class="mb-3">
            <label class="form-label">العنوان الرئيسي</label>
            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($pricelist_data['main_title'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">الوصف التفصيلي</label>
            <textarea class="form-control" name="main_desc" rows="5" required><?php echo htmlspecialchars($pricelist_data['main_desc'] ?? ''); ?></textarea>
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

<!-- 4. Modal تعديل كرت ورابط التحميل (Download Card) -->
<div class="modal fade" id="priceListCardModal" tabindex="-1" aria-labelledby="priceListCardModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="admin-config-form" data-action="update_pricelist_card" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="priceListCardModalLabel">تعديل ملف التحميل وقائمة الأسعار</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_pricelist_card">
          <input type="hidden" name="old_file" value="<?php echo htmlspecialchars($pricelist_data['download_item']['file'] ?? ''); ?>">

          <div class="mb-3">
            <label class="form-label">عنوان الملف الظاهر</label>
            <input type="text" class="form-control" name="item_title" value="<?php echo htmlspecialchars($pricelist_data['download_item']['title'] ?? ''); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">نوع الملف</label>
            <select class="form-select" name="item_type">
              <option value="pdf" <?php echo (($pricelist_data['download_item']['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>ملف PDF</option>
              <option value="word" <?php echo (in_array($pricelist_data['download_item']['type'] ?? '', ['word', 'docx'])) ? 'selected' : ''; ?>>ملف Word (docx)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">رفع ملف جديد (PDF أو Word)</label>
            <input type="file" class="form-control" name="item_file" accept=".pdf,.doc,.docx">
            <div class="form-text text-muted mt-1">الملف الحالي: <?php echo htmlspecialchars($pricelist_data['download_item']['file'] ?? 'لا يوجد'); ?></div>
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
<script>
    /**
 * admin_pricelist.js - سكربت إدارة النماذج والتحديث الفوري لصفحة قائمة الأسعار
 */
document.addEventListener('DOMContentLoaded', function () {
    const adminForms = document.querySelectorAll('.admin-config-form');

    adminForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // تعطيل الزر وإظهار حالة التحميل
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الحفظ...';

            fetch('save_config.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إغلاق المودال الحالي
                    const modalElement = this.closest('.modal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // إعادة تحميل الصفحة لتطبيق التحديثات فوراً
                    location.reload();
                } else {
                    alert('خطأ أثناء الحفظ: ' + (data.message || 'حدث خطأ غير معروف'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    });
});

</script>