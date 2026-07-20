<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$ab = $data['about'] ?? [];
?>

<!-- 1. About Section Modal (قسم من نحن) -->
<div class="modal fade custom-modal" id="aboutEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle text-primary"></i> تعديل قسم من نحن</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="aboutSectionForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_section">
                    
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
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="about_btn_url" value="<?php echo htmlspecialchars($ab['btn_url'] ?? '#'); ?>">
                        </div>

                        <!-- الصور الرئيسية -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصورة الرئيسية</label>
                            <input type="file" class="form-control" name="about_main_img" accept="image/*">
                            <input type="hidden" name="old_about_main_img" value="<?php echo htmlspecialchars($ab['main_img'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصورة الفرعية</label>
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
                            <label class="small text-muted">الأيقونة</label>
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
                            <label class="small text-muted">الأيقونة</label>
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
                <form id="teamForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="team_title" value="<?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل'); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="team_desc" rows="2"><?php echo htmlspecialchars($data['team_desc'] ?? ''); ?></textarea>
                    </div>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['team_members'] ?? []) as $index => $member): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="team_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="team[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" placeholder="الاسم">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="team[<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? ''); ?>" placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" class="form-control form-control-sm" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        <input type="hidden" name="team[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($member['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('team_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addTeamRow()">
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
                <form id="countsForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_counts">
                    
                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['about_counts'] ?? []) as $index => $c): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="count_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? ''); ?>" placeholder="الرقم (مثلاً 500+)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? ''); ?>" placeholder="الوصف (مثلاً كورس ألماني)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control form-control-sm" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        <input type="hidden" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($c['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('count_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addCountRow()">
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
                <h5 class="modal-title"><i class="bi bi-handbag text-primary"></i> إدارة شركاء النجاح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="partnersForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_partners">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="partners_title" value="<?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?>">
                    </div>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['partners_items'] ?? []) as $index => $partner): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-10">
                                        <input type="file" class="form-control form-control-sm" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        <input type="hidden" name="partners[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($partner['img'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addPartnerRow()">
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

<!-- Dynamic Rows JS Engine -->
<script>
    // 1. الدالة العامة للحذف (تعمل مع أي صف يُمرر لها الـ ID)
    function removeRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // 2. منطق إضافة الصفوف الديناميكية (Dynamic Rows Adders)

    // إضافة فريق العمل
    let teamCount = <?php echo count($data['team_members'] ?? []); ?>;
    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="team[${teamCount}][name]" placeholder="الاسم"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="team[${teamCount}][role]" placeholder="المسمى الوظيفي"></div>
                <div class="col-md-5"><input type="file" class="form-control form-control-sm" name="team_img_${teamCount}" accept="image/*"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('team_row_${teamCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        teamCount++;
    }

    // إضافة العدادات
    let countsCount = <?php echo count($data['about_counts'] ?? []); ?>;
    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="counts[${countsCount}][number]" placeholder="الرقم"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="counts[${countsCount}][title]" placeholder="الوصف"></div>
                <div class="col-md-4"><input type="file" class="form-control form-control-sm" name="count_img_${countsCount}" accept="image/*"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('count_row_${countsCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        countsCount++;
    }

    // إضافة الشركاء
    let partnerCount = <?php echo count($data['partners_items'] ?? []); ?>;
    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'partner_row_' + partnerCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-10"><input type="file" class="form-control form-control-sm" name="partner_img_${partnerCount}" accept="image/*"></div>
                <div class="col-md-2 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_${partnerCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        partnerCount++;
    }

    // 3. معالج النماذج الموحد لصفحة about (Unified Form Handler)
    document.querySelectorAll('#aboutEditModal form, #teamEditModal form, #countsEditModal form, #partnersEditModal form, #servicesEditModal form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('admin/api/save_config.php', {
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

