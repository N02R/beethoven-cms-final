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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <!-- العنوان والوصف -->
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">عنوان القسم الرئيسي</label>
                                <input type="text" class="form-control" name="about_title" value="<?php echo htmlspecialchars($ab['title'] ?? 'من نحن', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-secondary">نص الوصف</label>
                                <textarea class="form-control" name="about_desc" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($ab['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <!-- أزرار التوجيه -->
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">نص الزر</label>
                                <input type="text" class="form-control" name="about_btn_text" value="<?php echo htmlspecialchars($ab['btn_text'] ?? 'قراءة المزيد', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">رابط الزر الحالي</label>
                                <input type="text" class="form-control" name="about_btn_url" value="<?php echo htmlspecialchars($ab['btn_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <!-- الصور الرئيسية والمعاينة -->
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">الصورة الرئيسية الحالية</label>
                                <?php if (!empty($ab['main_img'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(get_image_url($ab['main_img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Main Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_main_img" accept="image/*">
                                <input type="hidden" name="old_about_main_img" value="<?php echo htmlspecialchars($ab['main_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-secondary">الصورة الفرعية الحالية</label>
                                <?php if (!empty($ab['sub_img'])): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(get_image_url($ab['sub_img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Sub Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_sub_img" accept="image/*">
                                <input type="hidden" name="old_about_sub_img" value="<?php echo htmlspecialchars($ab['sub_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12"><hr class="my-3 text-muted opacity-25"></div>

                            <!-- رؤية الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-eye"></i> رؤية الشركة
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                        <input type="text" class="form-control" name="vision_title" value="<?php echo htmlspecialchars($ab['vision_title'] ?? 'رؤية الشركة', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">الأيقونة الحالية ورفع أيقونة جديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($ab['vision_icon'])): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['vision_icon']), ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_vision_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="old_vision_icon" value="<?php echo htmlspecialchars($ab['vision_icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                                        <textarea class="form-control" name="vision_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($ab['vision_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- رسالة الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-chat-square-text"></i> رسالة الشركة
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">العنوان</label>
                                        <input type="text" class="form-control" name="message_title" value="<?php echo htmlspecialchars($ab['message_title'] ?? 'رسالة الشركة', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary">الأيقونة الحالية ورفع أيقونة جديدة</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($ab['message_icon'])): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($ab['message_icon']), ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_message_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="old_message_icon" value="<?php echo htmlspecialchars($ab['message_icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="small fw-bold mb-1 text-secondary">الوصف</label>
                                        <textarea class="form-control" name="message_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($ab['message_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="team_title" value="<?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <label class="small fw-bold mb-1 text-secondary">وصف القسم</label>
                            <textarea class="form-control" name="team_desc" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($data['team_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $team_items = $data['team_items'] ?? [];
                        if (!empty($team_items)):
                            foreach ($team_items as $index => $member): 
                        ?>
                            <div class="p-3 shadow-sm team-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="team_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary">الاسم</label>
                                        <input type="text" class="form-control team-name" name="team[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="الاسم">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary">المسمى الوظيفي</label>
                                        <input type="text" class="form-control team-role" name="team[<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary">الصورة الشخصية</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($member['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($member['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Member Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control team-file" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="team-old-img" name="team[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($member['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_<?php echo $index; ?>')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3 py-2 fw-bold" style="border-radius: 12px; border-style: dashed; border-width: 2px;" onclick="addTeamRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة عضو جديد
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $counts_items = $data['about_counts'] ?? [];
                        if (!empty($counts_items)):
                            foreach ($counts_items as $index => $c): 
                        ?>
                            <div class="p-3 shadow-sm count-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="count_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    
                                    <!-- الرقم -->
                                    <div class="col-md-3">
                                        <label for="count_number_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">الرقم / القيمة</label>
                                        <input type="text" id="count_number_<?php echo $index; ?>" class="form-control count-number" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: +500">
                                    </div>
                                    
                                    <!-- الوصف -->
                                    <div class="col-md-4">
                                        <label for="count_title_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">عنوان العداد / الوصف</label>
                                        <input type="text" id="count_title_<?php echo $index; ?>" class="form-control count-title" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: مشروع مكتمل">
                                    </div>
                                    
                                    <!-- الصورة -->
                                    <div class="col-md-4">
                                        <label for="count_file_<?php echo $index; ?>" class="form-label fw-semibold small text-secondary">أيقونة / صورة العداد</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($c['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($c['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Count Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" id="count_file_<?php echo $index; ?>" class="form-control count-file" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" class="count-old-img" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($c['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    
                                    <!-- زر الحذف -->
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_<?php echo $index; ?>')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                                    </div>
                                    
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCountRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="small fw-bold mb-1 text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="partners_title" value="<?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $partners_items = $data['partners_items'] ?? [];
                        if (!empty($partners_items)):
                            foreach ($partners_items as $index => $partner): 
                        ?>
                            <div class="p-3 shadow-sm partner-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-11">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($partner['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($partner['img']), ENT_QUOTES, 'UTF-8'); ?>" alt="Partner Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control partner-file" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="partner-old-img" name="partners[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($partner['img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_<?php echo $index; ?>')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addPartnerRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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

    // دالة لإنشاء وعرض التنبيهات الاحترافية
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

    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        const teamCount = container.querySelectorAll('.team-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm team-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" class="form-control team-name" name="team[${teamCount}][name]" placeholder="الاسم">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control team-role" name="team[${teamCount}][role]" placeholder="المسمى الوظيفي">
                </div>
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control team-file" name="team_img_${teamCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="team-old-img" name="team[${teamCount}][old_img]" value="">
                <div class="col-md-1 text-center">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_${teamCount}')" title="حذف العضو"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        const countsCount = container.querySelectorAll('.count-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm count-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" class="form-control count-number" name="counts[${countsCount}][number]" placeholder="الرقم">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control count-title" name="counts[${countsCount}][title]" placeholder="الوصف">
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control count-file" name="count_img_${countsCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="count-old-img" name="counts[${countsCount}][old_img]" value="">
                <div class="col-md-1 text-center">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_${countsCount}')" title="حذف العداد"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        const partnerCount = container.querySelectorAll('.partner-row-item').length;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm partner-row-item';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'partner_row_' + partnerCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-11">
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" class="form-control partner-file" name="partner_img_${partnerCount}" accept="image/*">
                    </div>
                </div>
                <input type="hidden" class="partner-old-img" name="partners[${partnerCount}][old_img]" value="">
                <div class="col-md-1 text-center">
                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_${partnerCount}')" title="حذف الشريك"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        container.appendChild(div);
    }

    // معالج النماذج الموحد والشامل
    document.querySelectorAll('.custom-modal form, .admin-settings-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // إعادة ترقيم صفوف الفريق بدقة
            const teamRows = form.querySelectorAll('.team-row-item');
            teamRows.forEach((row, index) => {
                const nameInput = row.querySelector('.team-name');
                const roleInput = row.querySelector('.team-role');
                const fileInput = row.querySelector('.team-file');
                const oldImgInput = row.querySelector('.team-old-img');

                if(nameInput) nameInput.name = `team[${index}][name]`;
                if(roleInput) roleInput.name = `team[${index}][role]`;
                if(fileInput) fileInput.name = `team_img_${index}`;
                if(oldImgInput) oldImgInput.name = `team[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الإحصائيات بدقة
            const countRows = form.querySelectorAll('.count-row-item');
            countRows.forEach((row, index) => {
                const numInput = row.querySelector('.count-number');
                const titleInput = row.querySelector('.count-title');
                const fileInput = row.querySelector('.count-file');
                const oldImgInput = row.querySelector('.count-old-img');

                if(numInput) numInput.name = `counts[${index}][number]`;
                if(titleInput) titleInput.name = `counts[${index}][title]`;
                if(fileInput) fileInput.name = `count_img_${index}`;
                if(oldImgInput) oldImgInput.name = `counts[${index}][old_img]`;
            });

            // إعادة ترقيم صفوف الشركاء بدقة
            const partnerRows = form.querySelectorAll('.partner-row-item');
            partnerRows.forEach((row, index) => {
                const fileInput = row.querySelector('.partner-file');
                const oldImgInput = row.querySelector('.partner-old-img');

                if(fileInput) fileInput.name = `partner_img_${index}`;
                if(oldImgInput) oldImgInput.name = `partners[${index}][old_img]`;
            });

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>';
            
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
                        showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'يرجى التأكد من البيانات المدخلة'), 'danger');
                    }
                } catch (e) {
                    showNotification('الخطأ الحقيقي من السيرفر: ' + text, 'danger');
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                showNotification('حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
            });
        });
    });
</script>