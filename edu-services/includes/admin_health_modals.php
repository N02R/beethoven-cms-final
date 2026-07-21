<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$health_data = $data['health_insurance_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="healthBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_health_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($health_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($health_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Modal -->
<div class="modal fade custom-modal" id="healthHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthHeroForm" method="POST">
                    <input type="hidden" name="action" value="update_health_hero">
                    <?php if (!empty($health_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($health_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">مسار الصورة (URL)</label>
                        <input type="text" class="form-control" name="hero_img" value="<?php echo htmlspecialchars($health_data['hero_img'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تنسيق التموضع (background-position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($health_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="healthMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthMainForm" method="POST">
                    <input type="hidden" name="action" value="update_health_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($health_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التعريفي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($health_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Importance Section Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="healthImportanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check2-circle text-primary"></i> إدارة قسم الأهمية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthImportanceForm" method="POST">
                    <input type="hidden" name="action" value="update_health_importance">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="importance_title" value="<?php echo htmlspecialchars($health_data['importance_section']['title'] ?? 'لماذا التأمين الصحي مهم؟'); ?>">
                    </div>
                    <label class="form-label fw-bold">النقاط (تعديل / إضافة / حذف)</label>
                    <div id="healthImportanceContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($health_data['importance_section']['items'])): ?>
                            <?php foreach ($health_data['importance_section']['items'] as $index => $item): ?>
                                <div class="input-group imp-item" id="health_imp_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="importance_items[]" value="<?php echo htmlspecialchars($item); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeHealthRow('health_imp_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addHealthImportanceRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نقطة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthImportanceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Documents Section Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="healthDocumentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-text text-primary"></i> إدارة الوثائق المكملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthDocumentsForm" method="POST">
                    <input type="hidden" name="action" value="update_health_documents">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان القسم</label>
                        <input type="text" class="form-control" name="documents_title" value="<?php echo htmlspecialchars($health_data['documents_section']['title'] ?? 'الوثائق المكملة'); ?>">
                    </div>
                    <label class="form-label fw-bold">قائمة الوثائق (تعديل / إضافة / حذف)</label>
                    <div id="healthDocumentsContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php if (!empty($health_data['documents_section']['items'])): ?>
                            <?php foreach ($health_data['documents_section']['items'] as $index => $doc): ?>
                                <div class="input-group doc-item" id="health_doc_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="documents_items[]" value="<?php echo htmlspecialchars($doc); ?>">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeHealthRow('health_doc_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addHealthDocumentRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة وثيقة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthDocumentsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Links & Note Modal (ديناميكي) -->
<div class="modal fade custom-modal" id="healthLinksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-link-45deg text-primary"></i> إدارة الملاحظة وروابط الشركات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="healthLinksForm" method="POST">
                    <input type="hidden" name="action" value="update_health_links">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ملاحظة الخبير / الوصف التمهيدي للروابط</label>
                        <textarea class="form-control" name="expert_note" rows="3" required><?php echo htmlspecialchars($health_data['expert_note'] ?? ''); ?></textarea>
                    </div>
                    
                    <label class="form-label fw-bold">روابط الشركات (تعديل / إضافة / حذف)</label>
                    <div id="healthLinksContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($health_data['insurance_links'])): ?>
                            <?php foreach ($health_data['insurance_links'] as $index => $link_item): ?>
                                <div class="border p-3 rounded bg-light link-row-item" id="health_link_row_<?php echo $index; ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-secondary">رابط #<?php echo $index + 1; ?></span>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeHealthRow('health_link_row_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف</button>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">نص الزر / الوصف</label>
                                        <input type="text" class="form-control form-control-sm" name="link_titles[]" value="<?php echo htmlspecialchars($link_item['title'] ?? ''); ?>" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">الرابط (URL)</label>
                                        <input type="text" class="form-control form-control-sm" name="link_urls[]" value="<?php echo htmlspecialchars($link_item['url'] ?? '#'); ?>" required>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="link_actives[]" value="<?php echo $index; ?>" <?php echo (!empty($link_item['active'])) ? 'checked' : ''; ?> id="active_check_<?php echo $index; ?>">
                                        <label class="form-check-label small" for="active_check_<?php echo $index; ?>">تفعيل النمط النشط (Active/Primary Button)</label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addHealthLinkRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة شركة / رابط جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="healthLinksForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    function removeHealthRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // إدارة صفوف الأهمية
    let healthImpIndex = <?php echo count($health_data['importance_section']['items'] ?? []); ?>;
    function addHealthImportanceRow() {
        const container = document.getElementById('healthImportanceContainer');
        const div = document.createElement('div');
        div.className = 'input-group imp-item mb-2';
        div.id = 'health_imp_' + healthImpIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="importance_items[]" placeholder="أدخل النقطة الجديدة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeHealthRow('health_imp_${healthImpIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        healthImpIndex++;
    }

    // إدارة صفوف الوثائق
    let healthDocIndex = <?php echo count($health_data['documents_section']['items'] ?? []); ?>;
    function addHealthDocumentRow() {
        const container = document.getElementById('healthDocumentsContainer');
        const div = document.createElement('div');
        div.className = 'input-group doc-item mb-2';
        div.id = 'health_doc_' + healthDocIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="documents_items[]" placeholder="أدخل الوثيقة الجديدة...">
            <button type="button" class="btn btn-outline-danger" onclick="removeHealthRow('health_doc_${healthDocIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        healthDocIndex++;
    }

    // إدارة روابط الشركات
    let healthLinkIndex = <?php echo count($health_data['insurance_links'] ?? []); ?>;
    function addHealthLinkRow() {
        const container = document.getElementById('healthLinksContainer');
        const div = document.createElement('div');
        div.className = 'border p-3 rounded bg-light link-row-item mb-2';
        div.id = 'health_link_row_' + healthLinkIndex;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-secondary">رابط جديد</span>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeHealthRow('health_link_row_${healthLinkIndex}')"><i class="bi bi-trash"></i> حذف</button>
            </div>
            <div class="mb-2">
                <label class="form-label small">نص الزر / الوصف</label>
                <input type="text" class="form-control form-control-sm" name="link_titles[]" placeholder="عنوان الشركة أو الرابط..." required>
            </div>
            <div class="mb-2">
                <label class="form-label small">الرابط (URL)</label>
                <input type="text" class="form-control form-control-sm" name="link_urls[]" value="https://" required>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="link_actives[]" value="${healthLinkIndex}" id="active_check_${healthLinkIndex}">
                <label class="form-check-label small" for="active_check_${healthLinkIndex}">تفعيل النمط النشط (Active/Primary Button)</label>
            </div>
        `;
        container.appendChild(div);
        healthLinkIndex++;
    }

    // ربط النماذج عبر AJAX
    document.querySelectorAll('#healthBreadcrumbForm, #healthHeroForm, #healthMainForm, #healthImportanceForm, #healthDocumentsForm, #healthLinksForm').forEach(form => {
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
