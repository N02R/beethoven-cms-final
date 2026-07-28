<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$stk_data = $data['studienkolleg_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="stkBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($stk_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($stk_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="stkHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو وتنسيقها</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_stk_hero">
                    <?php if (!empty($stk_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($stk_data['hero_img']); ?>" style="max-height: 100px; object-fit: contain;" alt="Hero">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($stk_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">إحداثيات تباعد الخلفية (background-position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($stk_data['hero_position'] ?? 'center -20rem'); ?>" placeholder="مثال: center -20rem">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title Modal -->
<div class="modal fade custom-modal" id="stkMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkMainForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($stk_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التعريفي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($stk_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Goals Modal -->
<div class="modal fade custom-modal" id="stkGoalsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل أهداف الدورة التأسيسية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkGoalsForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_goals">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="goals_title" value="<?php echo htmlspecialchars($stk_data['goals_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الأهداف</label>
                    <div id="stkGoalsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($stk_data['goals_items'] ?? []) as $i => $goal): ?>
                            <div class="input-group" id="stk_goal_<?php echo $i; ?>">
                                <input type="text" class="form-control" name="goals_items[]" value="<?php echo htmlspecialchars($goal); ?>">
                                <button type="button" class="btn btn-outline-danger" onclick="removeStkRow('stk_goal_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addStkRow('stkGoalsContainer', 'goals_items[]', 'stk_goal_')"><i class="bi bi-plus-circle me-1"></i> إضافة هدف جديد</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkGoalsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Learning Content Modal -->
<div class="modal fade custom-modal" id="stkLearningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-book text-primary"></i> تعديل محتوى ما يدرسه الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkLearningForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_learning">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="learning_title" value="<?php echo htmlspecialchars($stk_data['learning_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">المقدمة التمهيدية</label>
                        <textarea class="form-control" name="learning_intro" rows="2"><?php echo htmlspecialchars($stk_data['learning_intro'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الفقرة الأولى (اللغة الألمانية)</label>
                        <textarea class="form-control" name="learning_p1" rows="2"><?php echo htmlspecialchars($stk_data['learning_p1'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الفقرة الثانية (الناحية التقنية)</label>
                        <textarea class="form-control" name="learning_p2" rows="2"><?php echo htmlspecialchars($stk_data['learning_p2'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkLearningForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Courses Modal -->
<div class="modal fade custom-modal" id="stkCoursesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-mortarboard text-primary"></i> تعديل أنواع دورات السنة التحضيرية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkCoursesForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_courses">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="courses_title" value="<?php echo htmlspecialchars($stk_data['courses_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الدورات (M-Kurs, T-Kurs...)</label>
                    <div id="stkCoursesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($stk_data['courses_items'] ?? []) as $i => $course): ?>
                            <div class="input-group" id="stk_course_<?php echo $i; ?>">
                                <input type="text" class="form-control" name="courses_items[]" value="<?php echo htmlspecialchars($course); ?>">
                                <button type="button" class="btn btn-outline-danger" onclick="removeStkRow('stk_course_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addStkRow('stkCoursesContainer', 'courses_items[]', 'stk_course_')"><i class="bi bi-plus-circle me-1"></i> إضافة دورة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkCoursesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 7. University Type Modal -->
<div class="modal fade custom-modal" id="stkUniTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-building text-primary"></i> تعديل ارتباط الجامعات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkUniTypeForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_unitype">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="uni_type_title" value="<?php echo htmlspecialchars($stk_data['uni_type_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">المقدمة التعريفية</label>
                        <textarea class="form-control" name="uni_type_intro" rows="2"><?php echo htmlspecialchars($stk_data['uni_type_intro'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">وصف الجامعات العامة</label>
                        <textarea class="form-control" name="uni_public" rows="2"><?php echo htmlspecialchars($stk_data['uni_public'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">وصف جامعات العلوم التطبيقية</label>
                        <textarea class="form-control" name="uni_applied" rows="2"><?php echo htmlspecialchars($stk_data['uni_applied'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkUniTypeForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 8. Types Modal (Governmental vs Private) -->
<div class="modal fade custom-modal" id="stkTypesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-shaded text-primary"></i> تعديل أنواع السنة التحضيرية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkTypesForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_types">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="types_title" value="<?php echo htmlspecialchars($stk_data['types_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">السنة التحضيرية الحكومية</label>
                        <textarea class="form-control" name="type_public_desc" rows="3"><?php echo htmlspecialchars($stk_data['type_public_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">السنة التحضيرية الخاصة</label>
                        <textarea class="form-control" name="type_private_desc" rows="3"><?php echo htmlspecialchars($stk_data['type_private_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkTypesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 9. Notes Modal -->
<div class="modal fade custom-modal" id="stkNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($stk_data['notes_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات</label>
                    <div id="stkNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($stk_data['notes_items'] ?? []) as $i => $note): ?>
                            <div class="input-group" id="stk_note_<?php echo $i; ?>">
                                <input type="text" class="form-control" name="notes_items[]" value="<?php echo htmlspecialchars($note); ?>">
                                <button type="button" class="btn btn-outline-danger" onclick="removeStkRow('stk_note_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addStkRow('stkNotesContainer', 'notes_items[]', 'stk_note_')"><i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 10. Exam & FSP Modal -->
<div class="modal fade custom-modal" id="stkExamFspModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-check text-primary"></i> تعديل اختبار القبول والـ FSP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkExamFspForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_examfsp">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم اختبار القبول</label>
                        <input type="text" class="form-control" name="exam_title" value="<?php echo htmlspecialchars($stk_data['exam_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تفاصيل اختبار القبول</label>
                        <textarea class="form-control" name="exam_desc" rows="3"><?php echo htmlspecialchars($stk_data['exam_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم التقييم النهائي (FSP)</label>
                        <input type="text" class="form-control" name="fsp_title" value="<?php echo htmlspecialchars($stk_data['fsp_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تفاصيل اختبار الـ FSP</label>
                        <textarea class="form-control" name="fsp_desc" rows="3"><?php echo htmlspecialchars($stk_data['fsp_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkExamFspForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 11. Tips Modal -->
<div class="modal fade custom-modal" id="stkTipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-lightbulb text-primary"></i> تعديل نصائح التقديم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkTipsForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_tips">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($stk_data['tips_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة النصائح</label>
                    <div id="stkTipsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php foreach (($stk_data['tips_items'] ?? []) as $i => $tip): ?>
                            <div class="input-group" id="stk_tip_<?php echo $i; ?>">
                                <input type="text" class="form-control" name="tips_items[]" value="<?php echo htmlspecialchars($tip); ?>">
                                <button type="button" class="btn btn-outline-danger" onclick="removeStkRow('stk_tip_<?php echo $i; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addStkRow('stkTipsContainer', 'tips_items[]', 'stk_tip_')"><i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JS Engine -->
<script>
    function removeStkRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let stkCounter = 100;
    function addStkRow(containerId, inputName, idPrefix) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'input-group';
        div.id = idPrefix + stkCounter;
        div.innerHTML = `
            <input type="text" class="form-control" name="${inputName}" placeholder="اكتب النص هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeStkRow('${idPrefix}${stkCounter}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        stkCounter++;
    }

    // ربط كافة النماذج عبر AJAX
    document.querySelectorAll('#stkBreadcrumbForm, #stkHeroForm, #stkMainForm, #stkGoalsForm, #stkLearningForm, #stkCoursesForm, #stkUniTypeForm, #stkTypesForm, #stkNotesForm, #stkExamFspForm, #stkTipsForm').forEach(form => {
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
