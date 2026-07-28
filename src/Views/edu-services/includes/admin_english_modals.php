<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$english_data = $data['english_programs_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="englishBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_english_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($english_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($english_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="englishHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_english_hero">
                    <?php if (!empty($english_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($english_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($english_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="englishMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishMainForm" method="POST">
                    <input type="hidden" name="action" value="update_english_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($english_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($english_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Who Can Benefit / Requirements Modal -->
<div class="modal fade custom-modal" id="englishWhoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل شروط الاستفادة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishWhoForm" method="POST">
                    <input type="hidden" name="action" value="update_english_who">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">عنوان القسم</label>
                            <input type="text" class="form-control" name="who_title" value="<?php echo htmlspecialchars($english_data['who_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">العنوان الفرعي</label>
                            <input type="text" class="form-control" name="who_subtitle" value="<?php echo htmlspecialchars($english_data['who_subtitle'] ?? ''); ?>">
                        </div>
                    </div>
                    <label class="form-label fw-bold">قائمة الشروط (تعديل / إضافة / حذف)</label>
                    <div id="englishWhoContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($english_data['who_items'])): ?>
                            <?php foreach ($english_data['who_items'] as $index => $item): ?>
                                <div class="input-group who-item" id="english_who_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="who_items[]" value="<?php echo htmlspecialchars($item); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeEnglishRow('english_who_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addEnglishWhoRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شرط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishWhoForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Language Requirements Modal -->
<div class="modal fade custom-modal" id="englishLangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-award text-primary"></i> تعديل متطلبات اللغة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishLangForm" method="POST">
                    <input type="hidden" name="action" value="update_english_lang">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="lang_title" value="<?php echo htmlspecialchars($english_data['lang_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة متطلبات اللغة (تعديل / إضافة / حذف)</label>
                    <div id="englishLangContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($english_data['lang_points'])): ?>
                            <?php foreach ($english_data['lang_points'] as $index => $point): ?>
                                <div class="input-group lang-item" id="english_lang_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="lang_points[]" value="<?php echo htmlspecialchars($point); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeEnglishRow('english_lang_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addEnglishLangRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة متطلب جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishLangForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Note Modal -->
<div class="modal fade custom-modal" id="englishNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle text-primary"></i> تعديل الملاحظة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="englishNoteForm" method="POST">
                    <input type="hidden" name="action" value="update_english_note">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص التمييز (مثال: ملاحظة:)</label>
                        <input type="text" class="form-control" name="note_highlight" value="<?php echo htmlspecialchars($english_data['note_highlight'] ?? 'ملاحظة:'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص الملاحظة</label>
                        <textarea class="form-control" name="note_text" rows="3"><?php echo htmlspecialchars($english_data['note_text'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="englishNoteForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeEnglishRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let whoIndex = <?php echo count($english_data['who_items'] ?? []); ?>;
    function addEnglishWhoRow() {
        const container = document.getElementById('englishWhoContainer');
        const div = document.createElement('div');
        div.className = 'input-group who-item';
        div.id = 'english_who_' + whoIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="who_items[]" placeholder="اكتب الشرط هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeEnglishRow('english_who_${whoIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        whoIndex++;
    }

    let langIndex = <?php echo count($english_data['lang_points'] ?? []); ?>;
    function addEnglishLangRow() {
        const container = document.getElementById('englishLangContainer');
        const div = document.createElement('div');
        div.className = 'input-group lang-item';
        div.id = 'english_lang_' + langIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="lang_points[]" placeholder="اكتب المتطلب هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeEnglishRow('english_lang_${langIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        langIndex++;
    }

    // ربط كافة النماذج عبر AJAX
    document.querySelectorAll('#englishBreadcrumbForm, #englishHeroForm, #englishMainForm, #englishWhoForm, #englishLangForm, #englishNoteForm').forEach(form => {
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
