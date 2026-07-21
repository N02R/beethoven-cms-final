<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$lang_data = $data['language_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="langBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_lang_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($lang_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($lang_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="langHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_lang_hero">
                    <?php if (!empty($lang_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($lang_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($lang_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="langMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langMainForm" method="POST">
                    <input type="hidden" name="action" value="update_lang_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($lang_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($lang_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Goals Modal -->
<div class="modal fade custom-modal" id="langGoalsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-square text-primary"></i> تعديل أهداف الدورة التحضيرية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langGoalsForm" method="POST">
                    <input type="hidden" name="action" value="update_lang_goals">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم الأهداف</label>
                        <input type="text" class="form-control" name="goals_title" value="<?php echo htmlspecialchars($lang_data['goals_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الأهداف (تعديل / إضافة / حذف)</label>
                    <div id="langGoalsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($lang_data['goals'])): ?>
                            <?php foreach ($lang_data['goals'] as $index => $goal): ?>
                                <div class="input-group goal-item" id="lang_goal_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="goals[]" value="<?php echo htmlspecialchars($goal); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeLangRow('lang_goal_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addLangGoalRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة هدف جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langGoalsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Warning Text Modal -->
<div class="modal fade custom-modal" id="langWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل نص التنبيه وشروط القبول</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langWarningForm" method="POST">
                    <input type="hidden" name="action" value="update_lang_warning">
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص التنبيه (لون أحمر)</label>
                        <textarea class="form-control" name="warning_text" rows="3" required><?php echo htmlspecialchars($lang_data['warning_text'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langWarningForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Cost & Places Modal -->
<div class="modal fade custom-modal" id="langCostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-stack text-primary"></i> تعديل أماكن الالتحاق والتكاليف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="langCostForm" method="POST">
                    <input type="hidden" name="action" value="update_lang_cost">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="cost_title" value="<?php echo htmlspecialchars($lang_data['cost_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">خيارات الأماكن والتكاليف (تعديل / إضافة / حذف)</label>
                    <div id="langCostContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($lang_data['cost_items'])): ?>
                            <?php foreach ($lang_data['cost_items'] as $index => $item): ?>
                                <div class="p-3 border rounded bg-light position-relative cost-item-box" id="lang_cost_<?php echo $index; ?>">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">اسم الجهة (مثال: جامعات حكومية:)</label>
                                        <input type="text" class="form-control form-control-sm" name="cost_titles[]" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">التفاصيل والتكلفة</label>
                                        <input type="text" class="form-control form-control-sm" name="cost_descs[]" value="<?php echo htmlspecialchars($item['desc'] ?? ''); ?>">
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeLangRow('lang_cost_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف البند</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addLangCostRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة جهة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="langCostForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeLangRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let goalIndex = <?php echo count($lang_data['goals'] ?? []); ?>;
    function addLangGoalRow() {
        const container = document.getElementById('langGoalsContainer');
        const div = document.createElement('div');
        div.className = 'input-group goal-item';
        div.id = 'lang_goal_' + goalIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="goals[]" placeholder="اكتب الهدف هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeLangRow('lang_goal_${goalIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        goalIndex++;
    }

    let costIndex = <?php echo count($lang_data['cost_items'] ?? []); ?>;
    function addLangCostRow() {
        const container = document.getElementById('langCostContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative cost-item-box';
        div.id = 'lang_cost_' + costIndex;
        div.innerHTML = `
            <div class="mb-2">
                <label class="form-label small fw-bold">اسم الجهة</label>
                <input type="text" class="form-control form-control-sm" name="cost_titles[]" placeholder="مثال: معاهد خاصة:">
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">التفاصيل والتكلفة</label>
                <input type="text" class="form-control form-control-sm" name="cost_descs[]" placeholder="مثال: التكلفة الشهرية...">
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeLangRow('lang_cost_${costIndex}')"><i class="bi bi-trash"></i> حذف البند</button>
        `;
        container.appendChild(div);
        costIndex++;
    }

    // ربط النماذج عبر AJAX
    document.querySelectorAll('#langBreadcrumbForm, #langHeroForm, #langMainForm, #langGoalsForm, #langWarningForm, #langCostForm').forEach(form => {
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
