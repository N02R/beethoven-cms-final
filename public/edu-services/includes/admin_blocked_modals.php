<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$blocked_data = $data['blocked_account_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="blockedBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($blocked_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="blockedHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_blocked_hero">
                    <?php if (!empty($blocked_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($blocked_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($blocked_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Importance Modal -->
<div class="modal fade custom-modal" id="blockedMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان الرئيسي والأهمية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedMainForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($blocked_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="3" required><?php echo htmlspecialchars($blocked_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان قسم الأهمية</label>
                        <input type="text" class="form-control" name="importance_title" value="<?php echo htmlspecialchars($blocked_data['importance_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">وصف الأهمية</label>
                        <textarea class="form-control" name="importance_desc" rows="3"><?php echo htmlspecialchars($blocked_data['importance_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Options Modal -->
<div class="modal fade custom-modal" id="blockedOptionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل خيارات الضمان المالي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedOptionsForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_options">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="options_title" value="<?php echo htmlspecialchars($blocked_data['options_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الخيارات (تعديل / إضافة / حذف)</label>
                    <div id="blockedOptionsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($blocked_data['options_items'])): ?>
                            <?php foreach ($blocked_data['options_items'] as $index => $item): ?>
                                <div class="input-group option-item" id="blocked_opt_<?php echo $index; ?>">
                                    <textarea class="form-control" name="options_items[]" rows="2"><?php echo htmlspecialchars($item); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeBlockedRow('blocked_opt_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addBlockedOptionRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة خيار جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedOptionsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Account Points Modal -->
<div class="modal fade custom-modal" id="blockedAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-lock text-primary"></i> تعديل نقاط الحساب البنكي المغلق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedAccountForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_account">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="account_title" value="<?php echo htmlspecialchars($blocked_data['account_title'] ?? ''); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة النقاط (تعديل / إضافة / حذف)</label>
                    <div id="blockedAccountContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($blocked_data['account_points'])): ?>
                            <?php foreach ($blocked_data['account_points'] as $index => $point): ?>
                                <div class="input-group account-item" id="blocked_acc_<?php echo $index; ?>">
                                    <textarea class="form-control" name="account_points[]" rows="2"><?php echo htmlspecialchars($point); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeBlockedRow('blocked_acc_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addBlockedAccountRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedAccountForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Service Links Modal -->
<div class="modal fade custom-modal" id="blockedLinksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-link-45deg text-primary"></i> إدارة روابط الشركات والخدمات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="blockedLinksForm" method="POST">
                    <input type="hidden" name="action" value="update_blocked_links">
                    <label class="form-label fw-bold">قائمة الروابط (تعديل / إضافة / حذف)</label>
                    <div id="blockedLinksContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($blocked_data['service_links'])): ?>
                            <?php foreach ($blocked_data['service_links'] as $index => $link): ?>
                                <div class="p-3 border rounded bg-light position-relative link-item-box" id="blocked_link_<?php echo $index; ?>">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">نص الرابط</label>
                                        <input type="text" class="form-control form-control-sm" name="link_texts[]" value="<?php echo htmlspecialchars($link['text'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">رابط التوجيه (URL)</label>
                                        <input type="text" class="form-control form-control-sm" name="link_urls[]" value="<?php echo htmlspecialchars($link['url'] ?? '#'); ?>">
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBlockedRow('blocked_link_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف هذا الرابط</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addBlockedLinkRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة رابط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="blockedLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeBlockedRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let optIndex = <?php echo count($blocked_data['options_items'] ?? []); ?>;
    function addBlockedOptionRow() {
        const container = document.getElementById('blockedOptionsContainer');
        const div = document.createElement('div');
        div.className = 'input-group option-item';
        div.id = 'blocked_opt_' + optIndex;
        div.innerHTML = `
            <textarea class="form-control" name="options_items[]" rows="2" placeholder="اكتب الخيار هنا..."></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeBlockedRow('blocked_opt_${optIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        optIndex++;
    }

    let accIndex = <?php echo count($blocked_data['account_points'] ?? []); ?>;
    function addBlockedAccountRow() {
        const container = document.getElementById('blockedAccountContainer');
        const div = document.createElement('div');
        div.className = 'input-group account-item';
        div.id = 'blocked_acc_' + accIndex;
        div.innerHTML = `
            <textarea class="form-control" name="account_points[]" rows="2" placeholder="اكتب النقطة هنا..."></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeBlockedRow('blocked_acc_${accIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        accIndex++;
    }

    let linkIndex = <?php echo count($blocked_data['service_links'] ?? []); ?>;
    function addBlockedLinkRow() {
        const container = document.getElementById('blockedLinksContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative link-item-box';
        div.id = 'blocked_link_' + linkIndex;
        div.innerHTML = `
            <div class="mb-2">
                <label class="form-label small fw-bold">نص الرابط</label>
                <input type="text" class="form-control form-control-sm" name="link_texts[]" value="إحجز خدمة جديدة...">
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">رابط التوجيه (URL)</label>
                <input type="text" class="form-control form-control-sm" name="link_urls[]" value="#">
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBlockedRow('blocked_link_${linkIndex}')"><i class="bi bi-trash"></i> حذف هذا الرابط</button>
        `;
        container.appendChild(div);
        linkIndex++;
    }

    // ربط كافة النماذج عبر AJAX
    document.querySelectorAll('#blockedBreadcrumbForm, #blockedHeroForm, #blockedMainForm, #blockedOptionsForm, #blockedAccountForm, #blockedLinksForm').forEach(form => {
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
