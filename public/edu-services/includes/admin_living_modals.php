<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$living_data = $data['living_cost_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="livingBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_living_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($living_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($living_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="livingHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_living_hero">
                    <?php if (!empty($living_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($living_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($living_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">موضع الخلفية (Background Position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($living_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="livingMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingMainForm" method="POST">
                    <input type="hidden" name="action" value="update_living_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($living_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($living_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Tips Section Modal -->
<div class="modal fade custom-modal" id="livingTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نصائح تقليل النفقات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_living_tips">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($living_data['tips_section']['title'] ?? 'نصائح لتقليل النفقات'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="livingTipsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php 
                          $tips_items = $living_data['tips_section']['items'] ?? [];
                          if (!empty($tips_items)): 
                            foreach ($tips_items as $index => $tip): 
                        ?>
                                <div class="input-group tip-item" id="living_tip_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips_items[]" value="<?php echo htmlspecialchars($tip); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeLivingRow('living_tip_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addLivingTipRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Important Notes Modal -->
<div class="modal fade custom-modal" id="livingNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="livingNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_living_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملاحظات</label>
                        <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($living_data['notes_section']['title'] ?? 'ملاحظات هامة !!'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="livingNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php 
                          $notes_items = $living_data['notes_section']['items'] ?? [];
                          if (!empty($notes_items)): 
                            foreach ($notes_items as $index => $note): 
                        ?>
                                <div class="input-group note-item" id="living_note_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="notes_items[]" value="<?php echo htmlspecialchars($note); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeLivingRow('living_note_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addLivingNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="livingNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // دالة عامة لحذف أي صف أو عنصر ديناميكي
    function removeLivingRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // إدارة صفوف نصائح تقليل النفقات
    let livingTipIndex = <?php echo count($living_data['tips_section']['items'] ?? []); ?>;
    function addLivingTipRow() {
        const container = document.getElementById('livingTipsContainer');
        const div = document.createElement('div');
        div.className = 'input-group tip-item';
        div.id = 'living_tip_' + livingTipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips_items[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeLivingRow('living_tip_${livingTipIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        livingTipIndex++;
    }

    // إدارة صفوف الملاحظات الهامة
    let livingNoteIndex = <?php echo count($living_data['notes_section']['items'] ?? []); ?>;
    function addLivingNoteRow() {
        const container = document.getElementById('livingNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item';
        div.id = 'living_note_' + livingNoteIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="notes_items[]" placeholder="اكتب الملاحظة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeLivingRow('living_note_${livingNoteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        livingNoteIndex++;
    }

    // ربط كافة النماذج عبر AJAX للإرسال الفوري وتحديث الصفحة
    document.querySelectorAll('#livingBreadcrumbForm, #livingHeroForm, #livingMainForm, #livingTipsForm, #livingNotesForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../admin/api/save_config.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم الحفظ بنجاح');
                    location.reload();
                } else {
                    alert('خطأ: ' + (data.message || 'فشل الحفظ'));
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالسيرفر');
            });
        });
    });
</script>
