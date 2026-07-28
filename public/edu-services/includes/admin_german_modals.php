<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$german_data = $data['germanlang_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="germanBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_german_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($german_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($german_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="germanHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanHeroForm" method="POST">
                    <input type="hidden" name="action" value="update_german_hero">
                    <?php if (!empty($german_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($german_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">مسار الصورة (URL)</label>
                        <input type="text" class="form-control" name="hero_img" value="<?php echo htmlspecialchars($german_data['hero_img'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تنسيق التموضع (background-position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($german_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="germanMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanMainForm" method="POST">
                    <input type="hidden" name="action" value="update_german_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($german_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التعريفي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($german_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Levels Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanLevelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-stars text-primary"></i> إدارة المستويات المتوفرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanLevelsForm" method="POST">
                    <input type="hidden" name="action" value="update_german_levels">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم المستويات</label>
                        <input type="text" class="form-control" name="levels_title" value="<?php echo htmlspecialchars($german_data['levels_section']['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة المستويات (تعديل / إضافة / حذف)</label>
                    <div id="germanLevelsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($german_data['levels_section']['levels_list'])): ?>
                            <?php foreach ($german_data['levels_section']['levels_list'] as $index => $level): ?>
                                <div class="input-group level-item" id="german_level_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="levels_list[]" value="<?php echo htmlspecialchars($level); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_level_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addGermanLevelRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة مستوى جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanLevelsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Features Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanFeaturesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-circle text-primary"></i> إدارة مميزات الدورات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanFeaturesForm" method="POST">
                    <input type="hidden" name="action" value="update_german_features">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم المميزات</label>
                        <input type="text" class="form-control" name="features_title" value="<?php echo htmlspecialchars($german_data['features_section']['title'] ?? 'مميزات دوراتنا'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة المميزات (تعديل / إضافة / حذف)</label>
                    <div id="germanFeaturesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($german_data['features_section']['features_list'])): ?>
                            <?php foreach ($german_data['features_section']['features_list'] as $index => $feat): ?>
                                <div class="input-group feat-item" id="german_feat_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="features_list[]" value="<?php echo htmlspecialchars($feat); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_feat_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addGermanFeatureRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ميزة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanFeaturesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Tips Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="germanTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-lightbulb text-primary"></i> إدارة نصائح النجاح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_german_tips">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم النصائح</label>
                        <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($german_data['tips_section']['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="germanTipsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($german_data['tips_section']['tips_list'])): ?>
                            <?php foreach ($german_data['tips_section']['tips_list'] as $index => $tip): ?>
                                <div class="input-group tip-item" id="german_tip_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="tips_list[]" value="<?php echo htmlspecialchars($tip); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_tip_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addGermanTipRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeGermanRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // إدارة صفوف المستويات
    let germanLevelIndex = <?php echo count($german_data['levels_section']['levels_list'] ?? []); ?>;
    function addGermanLevelRow() {
        const container = document.getElementById('germanLevelsContainer');
        const div = document.createElement('div');
        div.className = 'input-group level-item mb-2';
        div.id = 'german_level_' + germanLevelIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="levels_list[]" placeholder="أدخل المستوى الجديد...">
            <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_level_${germanLevelIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        germanLevelIndex++;
    }

    // إدارة صفوف المميزات
    let germanFeatureIndex = <?php echo count($german_data['features_section']['features_list'] ?? []); ?>;
    function addGermanFeatureRow() {
        const container = document.getElementById('germanFeaturesContainer');
        const div = document.createElement('div');
        div.className = 'input-group feat-item mb-2';
        div.id = 'german_feat_' + germanFeatureIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="features_list[]" placeholder="أدخل الميزة الجديدة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_feat_${germanFeatureIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        germanFeatureIndex++;
    }

    // إدارة صفوف النصائح
    let germanTipIndex = <?php echo count($german_data['tips_section']['tips_list'] ?? []); ?>;
    function addGermanTipRow() {
        const container = document.getElementById('germanTipsContainer');
        const div = document.createElement('div');
        div.className = 'input-group tip-item mb-2';
        div.id = 'german_tip_' + germanTipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips_list[]" placeholder="أدخل النصيحة الجديدة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_tip_${germanTipIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        germanTipIndex++;
    }

    // ربط كافة نماذج صفحة اللغة الألمانية عبر AJAX
    document.querySelectorAll('#germanBreadcrumbForm, #germanHeroForm, #germanMainForm, #germanLevelsForm, #germanFeaturesForm, #germanTipsForm').forEach(form => {
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
