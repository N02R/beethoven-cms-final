<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

if (!isset($path_prefix)) { $path_prefix = ''; }
?>

<!-- 1. مودل تعديل قسم من نحن (About Section Modal) -->
<div class="modal fade custom-modal" id="aboutEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle-fill text-primary"></i> تعديل قسم "من نحن"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="aboutSectionForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_section">
                    <?php $ab = $data['about'] ?? []; ?>
                    
                    <!-- النصوص الرئيسية -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="about_title" value="<?php echo htmlspecialchars($ab['title'] ?? 'من نحن'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التعريفي</label>
                        <textarea class="form-control" name="about_desc" rows="3"><?php echo htmlspecialchars($ab['desc'] ?? ''); ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نص الزر</label>
                            <input type="text" class="form-control" name="about_btn_text" value="<?php echo htmlspecialchars($ab['btn_text'] ?? 'قراءة المزيد'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">رابط الزر</label>
                            <input type="text" class="form-control" name="about_btn_url" value="<?php echo htmlspecialchars($ab['btn_url'] ?? '#'); ?>">
                        </div>
                    </div>

                    <!-- الصور الرئيسية للقسم -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصورة الرئيسية</label>
                            <input type="file" class="form-control" name="about_main_img" accept="image/*">
                            <input type="hidden" name="old_about_main_img" value="<?php echo $ab['main_img'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصورة الفرعية (التراكب)</label>
                            <input type="file" class="form-control" name="about_sub_img" accept="image/*">
                            <input type="hidden" name="old_about_sub_img" value="<?php echo $ab['sub_img'] ?? ''; ?>">
                        </div>
                    </div>

                    <hr>

                    <!-- رؤية ورسالة الشركة -->
                    <div class="row g-3">
                        <!-- الرؤية -->
                        <div class="col-md-6">
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                                <h6 class="fw-bold text-primary mb-2">رؤية الشركة</h6>
                                <div class="mb-2">
                                    <label class="small text-muted">العنوان</label>
                                    <input type="text" class="form-control form-control-sm" name="vision_title" value="<?php echo htmlspecialchars($ab['vision_title'] ?? 'رؤية الشركة'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="small text-muted">النص</label>
                                    <textarea class="form-control form-control-sm" name="vision_desc" rows="2"><?php echo htmlspecialchars($ab['vision_desc'] ?? ''); ?></textarea>
                                </div>
                                <div>
                                    <label class="small text-muted">الأيقونة</label>
                                    <input type="file" class="form-control form-control-sm" name="about_vision_icon" accept="image/*">
                                    <input type="hidden" name="old_vision_icon" value="<?php echo $ab['vision_icon'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- الرسالة -->
                        <div class="col-md-6">
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);">
                                <h6 class="fw-bold text-primary mb-2">رسالة الشركة</h6>
                                <div class="mb-2">
                                    <label class="small text-muted">العنوان</label>
                                    <input type="text" class="form-control form-control-sm" name="message_title" value="<?php echo htmlspecialchars($ab['message_title'] ?? 'رسالة الشركة'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="small text-muted">النص</label>
                                    <textarea class="form-control form-control-sm" name="message_desc" rows="2"><?php echo htmlspecialchars($ab['message_desc'] ?? ''); ?></textarea>
                                </div>
                                <div>
                                    <label class="small text-muted">الأيقونة</label>
                                    <input type="file" class="form-control form-control-sm" name="about_message_icon" accept="image/*">
                                    <input type="hidden" name="old_message_icon" value="<?php echo $ab['message_icon'] ?? ''; ?>">
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

<!-- 2. مودل إدارة فريق العمل (Team Edit Modal) -->
<div class="modal fade custom-modal" id="teamEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-people-fill text-primary"></i> إدارة فريق العمل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="teamForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم الرئيسي</label>
                        <input type="text" class="form-control" name="team_title" value="<?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل'); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">وصف القسم</label>
                        <textarea class="form-control" name="team_desc" rows="2"><?php echo htmlspecialchars($data['team_desc'] ?? ''); ?></textarea>
                    </div>
                    <hr>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['team_members'] ?? []) as $index => $member): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="team_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="team[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" placeholder="الاسم الكامل">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="team[<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($member['role'] ?? ''); ?>" placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" class="form-control form-control-sm" name="team_img_<?php echo $index; ?>">
                                        <input type="hidden" name="team[<?php echo $index; ?>][old_img]" value="<?php echo $member['img'] ?? ''; ?>">
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

<!-- 3. مودل إدارة الإحصائيات والعدادات (Counts Edit Modal) -->
<div class="modal fade custom-modal" id="countsEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-bar-chart-fill text-primary"></i> إدارة العدادات والإحصائيات</h5>
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
                                        <input type="text" class="form-control form-control-sm" name="counts[<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($c['number'] ?? ''); ?>" placeholder="الرقم (مثال: 700K+)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="counts[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($c['title'] ?? ''); ?>" placeholder="الوصف (مثال: برنامج تعليمي)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control form-control-sm" name="count_img_<?php echo $index; ?>">
                                        <input type="hidden" name="counts[<?php echo $index; ?>][old_img]" value="<?php echo $c['img'] ?? ''; ?>">
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

<!-- 4. مودل إدارة الشركاء (Partners Edit Modal) -->
<div class="modal fade custom-modal" id="partnersEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-building-fill text-primary"></i> إدارة شركاء النجاح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="partnersForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_partners">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">عنوان قسم الشركاء</label>
                        <input type="text" class="form-control" name="partners_title" value="<?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?>">
                    </div>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach (($data['partners_items'] ?? []) as $index => $p): ?>
                            <div class="card p-3 border-0" style="background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-10">
                                        <input type="file" class="form-control form-control-sm" name="partner_img_<?php echo $index; ?>">
                                        <input type="hidden" name="partners[<?php echo $index; ?>][old_img]" value="<?php echo $p['img'] ?? ''; ?>">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 mt-3" onclick="addPartnerRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شعار شريك جديد
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

<script>
    // إضافة صف جديد لفريق العمل
    let teamCount = <?php echo count($data['team_members'] ?? []); ?>;
    function addTeamRow() {
        const container = document.getElementById('teamRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'team_row_' + teamCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="team[${teamCount}][name]" placeholder="الاسم الكامل"></div>
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="team[${teamCount}][role]" placeholder="المسمى الوظيفي"></div>
                <div class="col-md-5"><input type="file" class="form-control form-control-sm" name="team_img_${teamCount}"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('team_row_${teamCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        teamCount++;
    }

    // إضافة صف جديد للعدادات
    let countsCount = <?php echo count($data['about_counts'] ?? []); ?>;
    function addCountRow() {
        const container = document.getElementById('countsRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'count_row_' + countsCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="counts[${countsCount}][number]" placeholder="الرقم"></div>
                <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="counts[${countsCount}][title]" placeholder="الوصف"></div>
                <div class="col-md-4"><input type="file" class="form-control form-control-sm" name="count_img_${countsCount}"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('count_row_${countsCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        countsCount++;
    }

    // إضافة صف جديد للشركاء
    let partnersCount = <?php echo count($data['partners_items'] ?? []); ?>;
    function addPartnerRow() {
        const container = document.getElementById('partnersRowsContainer');
        const div = document.createElement('div');
        div.className = 'card p-3 border-0 mb-2';
        div.style.cssText = 'background: var(--bg-soft); border-radius: 12px; border: 1px solid var(--border-color);';
        div.id = 'partner_row_' + partnersCount;
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-10"><input type="file" class="form-control form-control-sm" name="partner_img_${partnersCount}"></div>
                <div class="col-md-2 text-end"><button type="button" class="btn-icon-trash" onclick="removeRow('partner_row_${partnersCount}')"><i class="bi bi-trash"></i></button></div>
            </div>`;
        container.appendChild(div);
        partnersCount++;
    }
</script>
