<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$is_admin) { header("HTTP/1.1 403 Forbidden"); exit("Access Denied"); }

$offers_data = $global_data['offers_page'] ?? [];
?>

<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="offersBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_offers_breadcrumb">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الصفحة في المسار</label>
                        <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($offers_data['page_breadcrumb'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رابط الصفحة (URL)</label>
                        <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($offers_data['page_breadcrumb_url'] ?? '#'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="offersHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_offers_hero">
                    <?php if (!empty($offers_data['hero_img'])): ?>
                        <div class="mb-3 p-2 border rounded bg-light text-center">
                            <img src="<?php echo $path_prefix . htmlspecialchars($offers_data['hero_img']); ?>" style="max-height: 120px; object-fit: contain;" alt="Hero Preview">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($offers_data['hero_img'] ?? ''); ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">رفع صورة جديدة</label>
                        <input type="file" class="form-control" name="hero_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">موضع الخلفية (Background Position)</label>
                        <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($offers_data['hero_position'] ?? 'center center'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="offersMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersMainForm" method="POST">
                    <input type="hidden" name="action" value="update_offers_main">
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان الرئيسي</label>
                        <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($offers_data['main_title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف التفصيلي</label>
                        <textarea class="form-control" name="main_desc" rows="4" required><?php echo htmlspecialchars($offers_data['main_desc'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Important Notes Modal (Updated to Dynamic List) -->
<div class="modal fade custom-modal" id="offersNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-journal-text text-primary"></i> تعديل الملاحظات الهامة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersNotesForm" method="POST">
                    <input type="hidden" name="action" value="update_offers_notes">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الملاحظات</label>
                        <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($offers_data['note_title'] ?? ''); ?>" required>
                    </div>
                    <label class="form-label fw-bold">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="offersNotesContainer" class="d-flex flex-column gap-2 mb-3">
                        <?php 
                        // دعم النظام القديم (نص عادي) أو الجديد (مصفوفة)
                        $notes_list = $offers_data['notes_list'] ?? [];
                        if (empty($notes_list) && !empty($offers_data['note_text'])) {
                            $notes_list = [$offers_data['note_text']];
                        }
                        ?>
                        <?php if (!empty($notes_list)): ?>
                            <?php foreach ($notes_list as $index => $note): ?>
                                <div class="input-group note-item" id="offer_note_<?php echo $index; ?>">
                                    <textarea class="form-control" name="note_texts[]" rows="2" required><?php echo htmlspecialchars($note); ?></textarea>
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOfferNoteRow('offer_note_<?php echo $index; ?>')"><i class="bi bi-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addOfferNoteRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة ملاحظة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersNotesForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Cards Modal (Updated with File Type Selection) -->
<div class="modal fade custom-modal" id="offersCardsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-pdf text-primary"></i> إدارة حزم واتفاقيات العروض المتاحة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersCardsForm" method="POST">
                    <input type="hidden" name="action" value="update_offers_cards">
                    <label class="form-label fw-bold">قائمة الحزم والاتفاقيات (تعديل / إضافة / حذف)</label>
                    <div id="offersCardsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($offers_data['download_cards'])): ?>
                            <?php foreach ($offers_data['download_cards'] as $index => $card): ?>
                                <div class="p-3 border rounded bg-light position-relative card-item-box" id="offer_card_<?php echo $index; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">نوع الملف</label>
                                            <select class="form-select form-select-sm" name="card_types[]">
                                                <option value="pdf" <?php echo (strtolower($card['type'] ?? 'pdf') === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($card['type'] ?? '') === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">عنوان الكرت</label>
                                            <input type="text" class="form-control form-control-sm" name="card_titles[]" value="<?php echo htmlspecialchars($card['title'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">مسار الملف</label>
                                            <input type="text" class="form-control form-control-sm" name="card_files[]" value="<?php echo htmlspecialchars($card['file'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">الوصف الفرعي</label>
                                            <input type="text" class="form-control form-control-sm" name="card_subs[]" value="<?php echo htmlspecialchars($card['sub'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input card-active-checkbox" type="checkbox" name="card_actives[<?php echo $index; ?>]" value="1" <?php echo (!empty($card['active'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label fw-bold small">
                                                    اجعل هذا الكرت نشطاً (Active - يظهر بلون مميز)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeOfferCardRow('offer_card_<?php echo $index; ?>')"><i class="bi bi-trash"></i> حذف هذه الحزمة</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="addOfferCardRow()">
                        <i class="bi bi-plus-circle me-1"></i> إضافة حزمة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersCardsForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // حذف صف الملاحظة
    function removeOfferNoteRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let noteIndex = <?php echo count($notes_list ?? []); ?>;
    function addOfferNoteRow() {
        const container = document.getElementById('offersNotesContainer');
        const div = document.createElement('div');
        div.className = 'input-group note-item';
        div.id = 'offer_note_' + noteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="note_texts[]" rows="2" placeholder="اكتب الملاحظة هنا (تدعم HTML)..." required></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeOfferNoteRow('offer_note_${noteIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // حذف صف كرت التحميل
    function removeOfferCardRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    let cardIndex = <?php echo count($offers_data['download_cards'] ?? []); ?>;
    function addOfferCardRow() {
        const container = document.getElementById('offersCardsContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative card-item-box';
        div.id = 'offer_card_' + cardIndex;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">نوع الملف</label>
                    <select class="form-select form-select-sm" name="card_types[]">
                        <option value="pdf" selected>PDF</option>
                        <option value="word">Word</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">عنوان الكرت</label>
                    <input type="text" class="form-control form-control-sm" name="card_titles[]" placeholder="مثال: البكالوريوس" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">مسار الملف</label>
                    <input type="text" class="form-control form-control-sm" name="card_files[]" placeholder="assets/files/..." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">الوصف الفرعي</label>
                    <input type="text" class="form-control form-control-sm" name="card_subs[]" placeholder="حزمة واتفاقية..." required>
                </div>
                <div class="col-md-12">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="card_actives[${cardIndex}]" value="1">
                        <label class="form-check-label fw-bold small">
                            اجعل هذا الكرت نشطاً (Active - يظهر بلون مميز)
                        </label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeOfferCardRow('offer_card_${cardIndex}')"><i class="bi bi-trash"></i> حذف هذه الحزمة</button>
        `;
        container.appendChild(div);
        cardIndex++;
    }

    // ربط كافة النماذج عبر AJAX
    document.querySelectorAll('#offersBreadcrumbForm, #offersHeroForm, #offersMainForm, #offersNotesForm, #offersCardsForm').forEach(form => {
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
