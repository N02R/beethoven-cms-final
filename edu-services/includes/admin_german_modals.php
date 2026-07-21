<!-- 1. Hero Modal -->
<div class="modal fade custom-modal" id="germanHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الغلاف (الهيرو)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanHeroForm" method="POST">
                    <input type="hidden" name="action" value="update_german_hero">
                    <div class="mb-3">
                        <label class="form-label fw-bold">مسار الصورة (URL)</label>
                        <input type="text" class="form-control" name="hero_img" value="<?php echo htmlspecialchars($german_data['hero_img'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">موضع الصورة (Position)</label>
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

<!-- 2. Main Info Modal -->
<div class="modal fade custom-modal" id="germanMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-text-paragraph text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanMainForm" method="POST">
                    <input type="hidden" name="action" value="update_german_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($german_data['main_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التمهيدي</label>
                        <textarea class="form-control" name="main_desc" rows="4"><?php echo htmlspecialchars($german_data['main_desc'] ?? ''); ?></textarea>
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

<!-- 3. Levels Modal (ديناميكي) -->
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
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="levels_title" value="<?php echo htmlspecialchars($german_data['levels_section']['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة المستويات</label>
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

<!-- 4. Features & Tips Modal (ديناميكي للمميزات والنصائح) -->
<div class="modal fade custom-modal" id="germanFeaturesTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-all text-primary"></i> إدارة المميزات والنصائح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="germanFeaturesTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_german_features_tips">
                    
                    <!-- قسم المميزات -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم المميزات</label>
                        <input type="text" class="form-control mb-2" name="features_title" value="<?php echo htmlspecialchars($german_data['features_section']['title'] ?? 'مميزات دوراتنا'); ?>">
                        <label class="form-label small text-muted">قائمة المميزات</label>
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
                    </div>

                    <hr class="my-4">

                    <!-- قسم النصائح -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم النصائح</label>
                        <input type="text" class="form-control mb-2" name="tips_title" value="<?php echo htmlspecialchars($german_data['tips_section']['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية'); ?>">
                        <label class="form-label small text-muted">قائمة النصائح</label>
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
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="germanFeaturesTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Script Engine for Dynamic Rows & AJAX -->
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
        div.className = 'input-group level-item';
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
        div.className = 'input-group feat-item';
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
        div.className = 'input-group tip-item';
        div.id = 'german_tip_' + germanTipIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="tips_list[]" placeholder="أدخل النصيحة الجديدة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeGermanRow('german_tip_${germanTipIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        germanTipIndex++;
    }

    // ربط كافة نماذج صفحة اللغة الألمانية عبر AJAX
    document.querySelectorAll('#germanHeroForm, #germanMainForm, #germanLevelsForm, #germanFeaturesTipsForm').forEach(form => {
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
