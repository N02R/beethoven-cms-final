<!-- Modal: تعديل الهيرو -->
<div class="modal fade" id="editHeroModal" tabindex="-1" aria-labelledby="editHeroModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="admin-update-content.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="setting_key" value="guide_blog_one_page">
        <div class="modal-header">
          <h5 class="modal-title" id="editHeroModalLabel">تعديل غلاف الصفحة (Hero)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">رابط صورة الغلاف الحالية</label>
            <input type="text" class="form-control" name="hero_img" value="<?= htmlspecialchars($settings['hero_img'] ?? '') ?>">
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

<!-- Modal: تعديل النصوص والملاحظات -->
<div class="modal fade" id="editContentInfoModal" tabindex="-1" aria-labelledby="editContentInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="admin-update-content.php" method="POST">
        <input type="hidden" name="setting_key" value="guide_blog_one_page">
        <div class="modal-header">
          <h5 class="modal-title" id="editContentInfoModalLabel">تعديل النصوص والملاحظات الرئيسية</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">العنوان الرئيسي (H2)</label>
            <input type="text" class="form-control" name="main_title" value="<?= htmlspecialchars($settings['main_title'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">الوصف التمهيدي</label>
            <textarea class="form-control" name="main_desc" rows="3"><?= htmlspecialchars($settings['main_desc'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">عنوان الملاحظات</label>
            <input type="text" class="form-control" name="notes_title" value="<?= htmlspecialchars($settings['notes_title'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">نص الملاحظة الأولى (مواعيد التقديم)</label>
            <input type="text" class="form-control" name="note_1_bold" value="<?= htmlspecialchars($settings['note_1_bold'] ?? '') ?>">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">فصل الشتاء</label>
              <input type="text" class="form-control" name="note_winter" value="<?= htmlspecialchars($settings['note_winter'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">فصل الصيف</label>
              <input type="text" class="form-control" name="note_summer" value="<?= htmlspecialchars($settings['note_summer'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">الملاحظة الثانية (مدة الإجراءات)</label>
            <textarea class="form-control" name="note_2_text" rows="2"><?= htmlspecialchars($settings['note_2_text'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">متطلبات القبول (FAQ 1)</label>
            <textarea class="form-control" name="faq_1" rows="3"><?= htmlspecialchars($settings['faq_1'] ?? '') ?></textarea>
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

<!-- Modal: تعديل أسباب الدراسة -->
<div class="modal fade" id="editWhyStudyModal" tabindex="-1" aria-labelledby="editWhyStudyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="admin-update-content.php" method="POST">
        <input type="hidden" name="setting_key" value="guide_blog_one_page">
        <div class="modal-header">
          <h5 class="modal-title" id="editWhyStudyModalLabel">تعديل قسم لماذا الدراسة في ألمانيا</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">عنوان القسم</label>
            <input type="text" class="form-control" name="why_study_title" value="<?= htmlspecialchars($settings['why_study_title'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">الوصـف الفرعي</label>
            <input type="text" class="form-control" name="why_study_desc" value="<?= htmlspecialchars($settings['why_study_desc'] ?? '') ?>">
          </div>
          <p class="text-muted small">ملاحظة: البطاقات الاثنتي عشرة تعتمد مصفوفة البيانات الأساسية ويمكن تخصيصها برمجياً عبر الـ Service عند الحاجة المتقدمة.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: تعديل الخط الزمني -->
<div class="modal fade" id="editTimelineModal" tabindex="-1" aria-labelledby="editTimelineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="admin-update-content.php" method="POST">
        <input type="hidden" name="setting_key" value="guide_blog_one_page">
        <div class="modal-header">
          <h5 class="modal-title" id="editTimelineModalLabel">تعديل الخط الزمني للرحلة</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">عنوان الخط الزمني</label>
            <input type="text" class="form-control" name="timeline_title" value="<?= htmlspecialchars($settings['timeline_title'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">الوصف التمهيدي للخط الزمني</label>
            <input type="text" class="form-control" name="timeline_desc" value="<?= htmlspecialchars($settings['timeline_desc'] ?? '') ?>">
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
