
<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="stkBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل (Breadcrumb)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($stk_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($stk_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
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
<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="stkHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو الرئيسية وموضعها</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_stk_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($stk_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($stk_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($stk_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                    </div>

                    <!-- حاوية إضافية لموضع الخلفية بنفس الستايل الموحد تماماً -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">إحداثيات تباعد الخلفية (background-position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($stk_data['hero_position'] ?? 'center -20rem', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: center -20rem">
                        </div>
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
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($stk_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التعريفي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($stk_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="goals_title" value="<?php echo htmlspecialchars($stk_data['goals_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الأهداف (تعديل / إضافة / حذف)</label>
                    <div id="stkGoalsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($stk_data['goals_items'])): ?>
                            <?php foreach ($stk_data['goals_items'] as $i => $goal): ?>
                                <div class="p-3 shadow-sm goal-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_goal_<?php echo $i; ?>">
                                    <input type="text" class="form-control" name="goals_items[]" value="<?php echo htmlspecialchars($goal, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الهدف هنا...">
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeStkRow('stk_goal_<?php echo $i; ?>')" title="حذف الهدف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة هدف جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addStkRow('stkGoalsContainer', 'goals_items[]', 'stk_goal_')" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة هدف جديد
                    </button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-book text-primary"></i> تعديل محتوى ما يدرسه الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkLearningForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_learning">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="learning_title" value="<?php echo htmlspecialchars($stk_data['learning_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">المقدمة التمهيدية</label>
                            <textarea class="form-control" name="learning_intro" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['learning_intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">الفقرة الأولى (اللغة الألمانية)</label>
                            <textarea class="form-control" name="learning_p1" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['learning_p1'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الفقرة الثانية (الناحية التقنية)</label>
                            <textarea class="form-control" name="learning_p2" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['learning_p2'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="courses_title" value="<?php echo htmlspecialchars($stk_data['courses_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الدورات (M-Kurs, T-Kurs...) (تعديل / إضافة / حذف)</label>
                    <div id="stkCoursesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($stk_data['courses_items'])): ?>
                            <?php foreach ($stk_data['courses_items'] as $i => $course): ?>
                                <div class="p-3 shadow-sm course-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_course_<?php echo $i; ?>">
                                    <input type="text" class="form-control" name="courses_items[]" value="<?php echo htmlspecialchars($course, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب اسم الدورة هنا...">
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeStkRow('stk_course_<?php echo $i; ?>')" title="حذف الدورة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة دورة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addStkRow('stkCoursesContainer', 'courses_items[]', 'stk_course_')" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة دورة جديدة
                    </button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-building text-primary"></i> تعديل ارتباط الجامعات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkUniTypeForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_unitype">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="uni_type_title" value="<?php echo htmlspecialchars($stk_data['uni_type_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">المقدمة التعريفية</label>
                            <textarea class="form-control" name="uni_type_intro" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['uni_type_intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">وصف الجامعات العامة</label>
                            <textarea class="form-control" name="uni_public" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['uni_public'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">وصف جامعات العلوم التطبيقية</label>
                            <textarea class="form-control" name="uni_applied" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['uni_applied'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-shaded text-primary"></i> تعديل أنواع السنة التحضيرية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkTypesForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_types">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="types_title" value="<?php echo htmlspecialchars($stk_data['types_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">السنة التحضيرية الحكومية</label>
                            <textarea class="form-control" name="type_public_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['type_public_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">السنة التحضيرية الخاصة</label>
                            <textarea class="form-control" name="type_private_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['type_private_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="notes_title" value="<?php echo htmlspecialchars($stk_data['notes_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="stkNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($stk_data['notes_items'])): ?>
                            <?php foreach ($stk_data['notes_items'] as $i => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_note_<?php echo $i; ?>">
                                    <input type="text" class="form-control" name="notes_items[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الملاحظة هنا...">
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeStkRow('stk_note_<?php echo $i; ?>')" title="حذف الملاحظة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملاحظة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addStkRow('stkNotesContainer', 'notes_items[]', 'stk_note_')" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-check text-primary"></i> تعديل اختبار القبول والـ FSP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkExamFspForm" method="POST">
                    <input type="hidden" name="action" value="update_stk_examfsp">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان قسم اختبار القبول</label>
                            <input type="text" class="form-control" name="exam_title" value="<?php echo htmlspecialchars($stk_data['exam_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">تفاصيل اختبار القبول</label>
                            <textarea class="form-control" name="exam_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['exam_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">عنوان قسم التقييم النهائي (FSP)</label>
                            <input type="text" class="form-control" name="fsp_title" value="<?php echo htmlspecialchars($stk_data['fsp_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">تفاصيل اختبار الـ FSP</label>
                            <textarea class="form-control" name="fsp_desc" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($stk_data['fsp_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="tips_title" value="<?php echo htmlspecialchars($stk_data['tips_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="stkTipsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($stk_data['tips_items'])): ?>
                            <?php foreach ($stk_data['tips_items'] as $i => $tip): ?>
                                <div class="p-3 shadow-sm tip-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_tip_<?php echo $i; ?>">
                                    <input type="text" class="form-control" name="tips_items[]" value="<?php echo htmlspecialchars($tip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeStkRow('stk_tip_<?php echo $i; ?>')" title="حذف النصيحة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addStkRow('stkTipsContainer', 'tips_items[]', 'stk_tip_')" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkTipsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine -->
<script>
    // 1. دالة عامة لحذف أي صف
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. دالة إظهار التنبيهات الاحترافية الموحدة
    function showNotification(message, type = 'success') {
        const existingAlert = document.getElementById('customNotificationAlert');
        if (existingAlert) existingAlert.remove();

        let bgClass = 'alert-success';
        let icon = 'bi-check-circle-fill';
        let title = 'تم بنجاح!';

        if (type === 'danger') {
            bgClass = 'alert-danger';
            icon = 'bi-x-circle-fill';
            title = 'عذراً، حدث خطأ!';
        } else if (type === 'warning') {
            bgClass = 'alert-warning';
            icon = 'bi-exclamation-triangle-fill';
            title = 'تنبيه هام';
        }

        const alertDiv = document.createElement('div');
        alertDiv.id = 'customNotificationAlert';
        alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
        alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; border-radius: 12px; border: none;';
        
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon} fs-4"></i>
                <div>
                    <strong>${title}</strong>
                    <div class="small">${message}</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            if (alertDiv) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 4000);
    }

    // 3. إضافة صف عنصر جديد (أهداف، دورات، ملاحظات، نصائح) بالستايل الموحد
    let stkCounter = 100;
    function addStkRow(containerId, inputName, idPrefix, isTextarea = false) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        const rowId = idPrefix + stkCounter;
        div.id = rowId;
        
        const inputField = isTextarea 
            ? `<textarea class="form-control" name="${inputName}" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب النص هنا..."></textarea>`
            : `<input type="text" class="form-control" name="${inputName}" placeholder="اكتب النص هنا...">`;

        div.innerHTML = `
            ${inputField}
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${rowId}')" title="حذف العنصر">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        stkCounter++;
    }

    // 4. معالج الحفظ الموحد عبر AJAX للـ Studienkolleg Forms
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#stkBreadcrumbForm, #stkHeroForm, #stkMainForm, #stkGoalsForm, #stkLearningForm, #stkCoursesForm, #stkUniTypeForm, #stkTypesForm, #stkNotesForm, #stkExamFspForm, #stkTipsForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (csrfToken && !formData.has('csrf_token')) {
                    formData.append('csrf_token', csrfToken);
                }

                fetch('index.php?url=admin/settings/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log("Raw Server Response:", text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            showNotification('تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || data.error || 'فشل الحفظ'), 'danger');
                        }
                    } catch (e) {
                        showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showNotification('حدث خطأ أثناء الاتصال بالسيرفر، يرجى المحاولة لاحقاً.', 'danger');
                });
            });
        });
    });
</script>


