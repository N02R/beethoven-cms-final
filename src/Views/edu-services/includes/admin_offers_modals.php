
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
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($offers_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($offers_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
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
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($offers_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($offers_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($offers_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">موضع الخلفية (Background Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($offers_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
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
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($offers_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($offers_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
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
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان الملاحظات</label>
                            <input type="text" class="form-control" name="note_title" value="<?php echo htmlspecialchars($offers_data['note_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملاحظات (تعديل / إضافة / حذف)</label>
                    <div id="offersNotesContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                        // دعم النظام القديم (نص عادي) أو الجديد (مصفوفة)
                        $notes_list = $offers_data['notes_list'] ?? [];
                        if (empty($notes_list) && !empty($offers_data['note_text'])) {
                            $notes_list = [$offers_data['note_text']];
                        }
                        ?>
                        <?php if (!empty($notes_list)): ?>
                            <?php foreach ($notes_list as $index => $note): ?>
                                <div class="p-3 shadow-sm note-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="offer_note_<?php echo $index; ?>">
                                    <!-- استخدام input بمسافات منسقة ومتطابقة -->
                                    <input type="text" class="form-control" name="note_texts[]" value="<?php echo htmlspecialchars($note, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب الملاحظة هنا..." required>
                                    <button type="button" class="btn-icon-trash flex-shrink-0" onclick="removeOfferNoteRow('offer_note_<?php echo $index; ?>')" title="حذف الملاحظة">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة ملاحظة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addOfferNoteRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الحزم والاتفاقيات (تعديل / إضافة / حذف)</label>
                    <div id="offersCardsContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php if (!empty($offers_data['download_cards'])): ?>
                            <?php foreach ($offers_data['download_cards'] as $index => $card): ?>
                                <?php 
                                    $card_type = strtolower($card['type'] ?? 'pdf');
                                ?>
                                <div class="p-4 shadow-sm position-relative card-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="offer_card_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                                            <select class="form-select" name="card_types[]" style="padding: 10px 14px;" required>
                                                <option value="pdf" <?php echo ($card_type === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo ($card_type === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small text-secondary">عنوان الكرت</label>
                                            <input type="text" class="form-control" name="card_titles[]" value="<?php echo htmlspecialchars($card['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مثال: البكالوريوس" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small text-secondary">مسار الملف</label>
                                            <input type="text" class="form-control" name="card_files[]" value="<?php echo htmlspecialchars($card['file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="assets/files/..." required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small text-secondary">الوصف الفرعي</label>
                                            <input type="text" class="form-control" name="card_subs[]" value="<?php echo htmlspecialchars($card['sub'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="وصف الحزمة..." required>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input card-active-checkbox" type="checkbox" name="card_actives[<?php echo $index; ?>]" value="1" id="active_check_<?php echo $index; ?>" <?php echo (!empty($card['active'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label fw-semibold small text-secondary" for="active_check_<?php echo $index; ?>">
                                                    اجعل هذا الكرت نشطاً (Active - يظهر بلون مميز)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeOfferCardRow('offer_card_<?php echo $index; ?>')">
                                            <i class="bi bi-trash"></i> حذف هذه الحزمة
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- زر إضافة حزمة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addOfferCardRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
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
    // 1. دالة عامة لحذف أي صف (ملاحظات أو كروت)
    function removeOfferNoteRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function removeOfferCardRow(id) {
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

    // 3. إدارة صفوف الملاحظات بالستايل الموحد
    let noteIndex = <?php echo count($notes_list ?? []); ?>;
    function addOfferNoteRow() {
        const container = document.getElementById('offersNotesContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-3 shadow-sm note-item d-flex align-items-center gap-2';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'offer_note_' + noteIndex;
        div.innerHTML = `
            <textarea class="form-control" name="note_texts[]" rows="2" style="height: auto; padding: 10px 14px;" placeholder="اكتب الملاحظة هنا (تدعم HTML)..." required></textarea>
            <button type="button" class="btn-icon-trash mx-auto" onclick="removeOfferNoteRow('offer_note_${noteIndex}')" title="حذف الملاحظة">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        noteIndex++;
    }

    // 4. إدارة صفوف كروت التحميل بالستايل الموحد (مع دعم اختيار نوع الملف PDF أو Word)
    let cardIndex = <?php echo count($offers_data['download_cards'] ?? []); ?>;
    function addOfferCardRow() {
        const container = document.getElementById('offersCardsContainer');
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'p-4 shadow-sm position-relative card-item-box';
        div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
        div.id = 'offer_card_' + cardIndex;
        div.innerHTML = `
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                    <select class="form-select" name="card_types[]" style="padding: 10px 14px;" required>
                        <option value="pdf" selected>PDF</option>
                        <option value="word">Word</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">عنوان الكرت</label>
                    <input type="text" class="form-control" name="card_titles[]" placeholder="مثال: البكالوريوس" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">مسار الملف</label>
                    <input type="text" class="form-control" name="card_files[]" placeholder="assets/files/..." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">الوصف الفرعي</label>
                    <input type="text" class="form-control" name="card_subs[]" placeholder="حزمة واتفاقية..." required>
                </div>
                <div class="col-md-12">
                    <div class="form-check mt-1">
                        <input class="form-check-input card-active-checkbox" type="checkbox" name="card_actives[${cardIndex}]" value="1" id="active_check_${cardIndex}">
                        <label class="form-check-label fw-semibold small text-secondary" for="active_check_${cardIndex}">
                            اجعل هذا الكرت نشطاً (Active - يظهر بلون مميز)
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px;" onclick="removeOfferCardRow('offer_card_${cardIndex}')">
                    <i class="bi bi-trash"></i> حذف هذه الحزمة
                </button>
            </div>
        `;
        container.appendChild(div);
        cardIndex++;
    }

    // 5. معالج الحفظ الموحد عبر AJAX لجميع نماذج صفحة العروض والاتفاقيات
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#offersBreadcrumbForm, #offersHeroForm, #offersMainForm, #offersNotesForm, #offersCardsForm').forEach(form => {
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
                            showNotification('عذراً، لم يتم الحفظ: ' + (data.message || 'فشل الحفظ'), 'danger');
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
