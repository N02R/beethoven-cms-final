
<!-- 1. About Section Modal (قسم من نحن) -->
<div class="modal fade custom-modal" id="aboutEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle text-primary"></i> تعديل قسم من نحن</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="aboutSectionForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_section">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="row g-3">
                        <!-- العنوان والوصف -->
                        <div class="col-12">
                            <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                            <input type="text" class="form-control" name="about_title" value="<?php echo htmlspecialchars($ab['title'] ?? 'من نحن'); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">نص الوصف</label>
                            <textarea class="form-control" name="about_desc" rows="4"><?php echo htmlspecialchars($ab['desc'] ?? ''); ?></textarea>
                        </div>

                        <!-- أزرار التوجيه -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="about_btn_text" value="<?php echo htmlspecialchars($ab['btn_text'] ?? 'قراءة المزيد'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط الزر الحالي</label>
                            <input type="text" class="form-control" name="about_btn_url" value="<?php echo htmlspecialchars($ab['btn_url'] ?? '#'); ?>">
                        </div>

                        <!-- الصور الرئيسية والمعاينة -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold d-flex justify-content-between">
                                <span>الصورة الرئيسية الحالية</span>
                                <?php if (!empty($ab['main_img'])): ?>
                                    <span class="badge bg-light text-dark border">موجودة</span>
                                <?php endif; ?>
                            </label>
                            <?php if (!empty($ab['main_img'])): ?>
                                <div class="mb-2 p-1 border rounded bg-white text-center">
                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['main_img'])); ?>" style="max-height: 80px; object-fit: contain;" alt="Main Preview">
                                    <div class="small text-muted mt-1 dir-ltr text-truncate"><?php echo htmlspecialchars($ab['main_img']); ?></div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="about_main_img" accept="image/*">
                            <input type="hidden" name="old_about_main_img" value="<?php echo htmlspecialchars($ab['main_img'] ?? ''); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold d-flex justify-content-between">
                                <span>الصورة الفرعية الحالية</span>
                                <?php if (!empty($ab['sub_img'])): ?>
                                    <span class="badge bg-light text-dark border">موجودة</span>
                                <?php endif; ?>
                            </label>
                            <?php if (!empty($ab['sub_img'])): ?>
                                <div class="mb-2 p-1 border rounded bg-white text-center">
                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['sub_img'])); ?>" style="max-height: 80px; object-fit: contain;" alt="Sub Preview">
                                    <div class="small text-muted mt-1 dir-ltr text-truncate"><?php echo htmlspecialchars($ab['sub_img']); ?></div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="about_sub_img" accept="image/*">
                            <input type="hidden" name="old_about_sub_img" value="<?php echo htmlspecialchars($ab['sub_img'] ?? ''); ?>">
                        </div>

                        <hr class="my-4">

                        <!-- رؤية الشركة -->
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3">رؤية الشركة</h6>
                            <label class="small text-muted">العنوان</label>
                            <input type="text" class="form-control mb-2" name="vision_title" value="<?php echo htmlspecialchars($ab['vision_title'] ?? 'رؤية الشركة'); ?>">
                            <label class="small text-muted">الوصف</label>
                            <textarea class="form-control mb-2" name="vision_desc" rows="2"><?php echo htmlspecialchars($ab['vision_desc'] ?? ''); ?></textarea>
                            
                            <label class="small text-muted fw-bold mt-1">الأيقونة الحالية</label>
                            <?php if (!empty($ab['vision_icon'])): ?>
                                <div class="d-flex align-items-center gap-2 mb-2 p-1 border rounded bg-white">
                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['vision_icon'])); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                    <span class="small text-muted text-truncate dir-ltr"><?php echo htmlspecialchars($ab['vision_icon']); ?></span>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="about_vision_icon" accept="image/*">
                            <input type="hidden" name="old_vision_icon" value="<?php echo htmlspecialchars($ab['vision_icon'] ?? ''); ?>">
                        </div>

                        <!-- رسالة الشركة -->
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3">رسالة الشركة</h6>
                            <label class="small text-muted">العنوان</label>
                            <input type="text" class="form-control mb-2" name="message_title" value="<?php echo htmlspecialchars($ab['message_title'] ?? 'رسالة الشركة'); ?>">
                            <label class="small text-muted">الوصف</label>
                            <textarea class="form-control mb-2" name="message_desc" rows="2"><?php echo htmlspecialchars($ab['message_desc'] ?? ''); ?></textarea>
                            
                            <label class="small text-muted fw-bold mt-1">الأيقونة الحالية</label>
                            <?php if (!empty($ab['message_icon'])): ?>
                                <div class="d-flex align-items-center gap-2 mb-2 p-1 border rounded bg-white">
                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['message_icon'])); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                    <span class="small text-muted text-truncate dir-ltr"><?php echo htmlspecialchars($ab['message_icon']); ?></span>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="about_message_icon" accept="image/*">
                            <input type="hidden" name="old_message_icon" value="<?php echo htmlspecialchars($ab['message_icon'] ?? ''); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="aboutSectionForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Team Edit Modal (قسم فريق العمل) -->
<div class="modal fade custom-modal" id="teamEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-people text-primary"></i> إدارة فريق العمل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="teamForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <!-- إعدادات عنوان ووصف القسم -->
                    <div class="row g-3 mb-4 p-3 border rounded-3" style="background: var(--bg-soft); border-color: var(--border-color) !important;">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">عنوان القسم</label>
                            <input type="text" class="form-control" name="team_title" value="<?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل'); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">وصف القسم</label>
                            <textarea class="form-control" name="team_desc" rows="2"><?php echo htmlspecialchars($data['team_desc'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $team_items = $data['team_items'] ?? [];
                        if (!empty($team_items)):
                            foreach ($team_items as $index => $member): 
                        ?>
                            <div class="card p-3 border team-row-item shadow-sm" style="background: var(--bg-soft, #f8f9fa); border-radius: 12px;" id="team_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold">الاسم</label>
                                        <input type="text" class="form-control form-control-sm team-name" name="team[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" placeholder="الاسم">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">المسمى الوظيفي</label>
                                        <input type="text" class="form-control form-control-sm team-role" name="team[<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? ''); ?>" placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold d-flex justify-content-between">
                                            <span>الصورة الشخصية</span>
                                            <?php if (!empty($member['img'])): ?>
                                                <span class="badge bg-light text-dark border" style="font-size: 10px;">موجودة</span>
                                            <?php endif; ?>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($member['img'])): ?>
                                                <img src="<?php echo htmlspecialchars(get_image_url($member['img'])); ?>" style="width: 30px; height: 30px; object-fit: contain; border-radius: 4px;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm team-file" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="team-old-img" name="team[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($member['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow('team_row_<?php echo $index; ?>')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addTeamRow()">
                        <i class="bi bi-plus-circle me-1">C</i> إضافة عضو جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="teamForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Counts Edit Modal (قسم الإحصائيات/العدادات) -->
<div class="modal fade custom-modal" id="countsEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calculator text-primary"></i> إدارة العدادات والإحصائيات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="countsForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_counts">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $counts_items = $data['about_counts'] ?? [];
                        if (!empty($counts_items)):
                            foreach ($counts_items as $index => $c): 
                        ?>
                            <div class="card p-3 border-0 count-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="count_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small text-muted">الرقم</label>
                                        <input type="text" class="form-control form-control-sm count-number" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? ''); ?>" placeholder="الرقم">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">الوصف</label>
                                        <input type="text" class="form-control form-control-sm count-title" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? ''); ?>" placeholder="الوصف">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted d-flex justify-content-between">
                                            <span>الأيقونة الحالية / الجديدة</span>
                                            <?php if (!empty($c['img'])): ?>
                                                <span class="badge bg-light text-dark border" style="font-size: 10px;">موجودة</span>
                                            <?php endif; ?>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($c['img'])): ?>
                                                <img src="<?php echo htmlspecialchars(get_image_url($c['img'])); ?>" style="width: 30px; height: 30px; object-fit: contain;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm count-file" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="count-old-img" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($c['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('count_row_<?php echo $index; ?>')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addCountRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة عداد جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="countsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Partners Edit Modal (قسم الشركاء) -->
<div class="modal fade custom-modal" id="partnersEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-handshake text-primary"></i> إدارة الشركاء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="partnersForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_partners">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="partners_title" value="<?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?>">
                    </div>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $partners_items = $data['partners_items'] ?? [];
                        if (!empty($partners_items)):
                            foreach ($partners_items as $index => $partner): 
                        ?>
                            <div class="card p-3 border-0 partner-row-item" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-11">
                                        <label class="small text-muted mb-1 d-flex justify-content-between">
                                            <span>صورة الشريك الحالية / اختيار جديدة</span>
                                            <?php if (!empty($partner['img'])): ?>
                                                <span class="badge bg-light text-dark border" style="font-size: 10px;">موجودة</span>
                                            <?php endif; ?>
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($partner['img'])): ?>
                                                <img src="<?php echo htmlspecialchars(get_image_url($partner['img'])); ?>" style="height: 40px; max-width: 80px; object-fit: contain; background: #fff; padding: 2px; border-radius: 4px; border: 1px solid #ddd;">
                                            <?php endif; ?>
                                            <input type="file" class="form-control form-control-sm partner-file" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="partner-old-img" name="partners[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($partner['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end pt-3">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_<?php echo $index; ?>')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn btn-light w-100 mt-3 py-2 border-dashed" style="border: 2px dashed #cbd5e1; color: var(--primary); font-weight: 600;" onclick="addPartnerRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شريك جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="partnersForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<!-- Dynamic Rows JS Engine & AJAX Handlers -->
<script>
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        const teamCount = container.querySelectorAll('.team-row-item').length;
        const div = document.createElement('div');
        div.className = 'card p-3 border team-row-item shadow-sm';
        div.style.cssText = 'background: var(--bg-soft, #f8f9fa); border-radius: 12px;';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">الاسم</label>
                    <input type="text" class="form-control form-control-sm team-name" name="team[${teamCount}][name]" placeholder="الاسم">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted fw-bold">المسمى الوظيفي</label>
                    <input type="text" class="form-control form-control-sm team-role" name="team[${teamCount}][role]" placeholder="المسمى الوظيفي">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted fw-bold">الصورة الشخصية الجديدة</label>
                    <input type="file" class="form-control form-control-sm team-file" name="team_img_${teamCount}" accept="image/*">
                    <input type="hidden" class="team-old-img" name="team[${teamCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow('team_row_${teamCount}')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        const countsCount = container.querySelectorAll('.count-row-item').length;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 count-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label class="small text-muted">الرقم</label>
                    <input type="text" class="form-control form-control-sm count-number" name="counts[${countsCount}][number]" placeholder="الرقم">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted">الوصف</label>
                    <input type="text" class="form-control form-control-sm count-title" name="counts[${countsCount}][title]" placeholder="الوصف">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted">أيقونة جديدة</label>
                    <input type="file" class="form-control form-control-sm count-file" name="count_img_${countsCount}" accept="image/*">
                    <input type="hidden" class="count-old-img" name="counts[${countsCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('count_row_${countsCount}')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        const partnerCount = container.querySelectorAll('.partner-row-item').length;
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2 partner-row-item';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'partner_row_' + partnerCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-11">
                    <label class="small text-muted mb-1">صورة الشريك الجديدة</label>
                    <input type="file" class="form-control form-control-sm partner-file" name="partner_img_${partnerCount}" accept="image/*">
                    <input type="hidden" class="partner-old-img" name="partners[${partnerCount}][old_img]" value="">
                </div>
                <div class="col-md-1 text-end pt-3">
                    <button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_${partnerCount}')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج النماذج الموحد الشامل
    document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // إعادة ترقيم صفوف الفريق بدقة متناهية
            const teamRows = form.querySelectorAll('.team-row-item');
            teamRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                const roleInput = row.querySelector('input[name*="[role]"]');
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                if(nameInput) nameInput.name = `team[${index}][name]`;
                if(roleInput) roleInput.name = `team[${index}][role]`;
                if(fileInput) fileInput.name = `team_img_${index}`;
                if(oldImgInput) oldImgInput.name = `team[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الإحصائيات/العدادات بدقة تامة
            const countRows = form.querySelectorAll('.count-row-item');
            countRows.forEach((row, index) => {
                const numInput = row.querySelector('input[name*="[number]"]');
                const titleInput = row.querySelector('input[name*="[title]"]');
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                if(numInput) numInput.name = `counts[${index}][number]`;
                if(titleInput) titleInput.name = `counts[${index}][title]`;
                if(fileInput) fileInput.name = `count_img_${index}`;
                if(oldImgInput) oldImgInput.name = `counts[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الشركاء بدقة
            const partnerRows = form.querySelectorAll('.partner-row-item');
            partnerRows.forEach((row, index) => {
                const fileInput = row.querySelector('input[type="file"]');
                const oldImgInput = row.querySelector('input[name*="[old_img]"]');

                if(fileInput) fileInput.name = `partner_img_${index}`;
                if(oldImgInput) oldImgInput.name = `partners[${index}][old_img]`;
            });

            const formData = new FormData(this);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? ''); ?>';
            
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'تم حفظ التغييرات بنجاح');
                    location.reload();
                } else {
                    console.error('Server Response Error:', data);
                    alert('خطأ: ' + (data.error || data.message || 'فشل الحفظ'));
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('حدث خطأ أثناء الاتصال بالخادم، افتح الـ Console للمزيد من التفاصيل.');
            });
        });
    });
</script>

