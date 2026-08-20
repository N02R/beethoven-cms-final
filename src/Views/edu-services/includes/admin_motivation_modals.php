<!-- 1. Breadcrumb Modal -->
<div class="modal fade custom-modal" id="motivationBreadcrumbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-signpost-split text-primary"></i> تعديل مسار التنقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationBreadcrumbForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_breadcrumb">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">اسم الصفحة في المسار</label>
                            <input type="text" class="form-control" name="page_breadcrumb" value="<?php echo htmlspecialchars($motivation_data['page_breadcrumb'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">رابط الصفحة (URL)</label>
                            <input type="text" class="form-control" name="page_breadcrumb_url" value="<?php echo htmlspecialchars($motivation_data['page_breadcrumb_url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationBreadcrumbForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Modal -->
<div class="modal fade custom-modal" id="motivationHeroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image text-primary"></i> تعديل صورة الهيرو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationHeroForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_motivation_hero">
                    <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($motivation_data['hero_img'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if (!empty($motivation_data['hero_img'])): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo $path_prefix . htmlspecialchars($motivation_data['hero_img'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">رفع صورة جديدة</label>
                            <input type="file" class="form-control" name="hero_img" accept="image/*">
                        </div>
                        
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">موضع الخلفية (Background Position)</label>
                            <input type="text" class="form-control" name="hero_position" value="<?php echo htmlspecialchars($motivation_data['hero_position'] ?? 'center center', ENT_QUOTES, 'UTF-8'); ?>" placeholder="center center">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationHeroForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Modal -->
<div class="modal fade custom-modal" id="motivationMainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading text-primary"></i> تعديل العنوان والوصف الرئيسي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationMainForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_main">
                    
                    <!-- حاوية منسقة بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">العنوان الرئيسي</label>
                            <input type="text" class="form-control" name="main_title" value="<?php echo htmlspecialchars($motivation_data['main_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">الوصف التفصيلي</label>
                            <textarea class="form-control" name="main_desc" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($motivation_data['main_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationMainForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Section Modal -->
<div class="modal fade custom-modal" id="motivationAdviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-list-check text-primary"></i> تعديل نصائح كتابة خطاب الدافع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationAdviceForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_advice">
                    
                    <!-- حاوية العنوان بنفس الستايل الموحد -->
                    <div class="p-4 shadow-sm mb-3" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-secondary">عنوان القسم</label>
                            <input type="text" class="form-control" name="advice_title" value="<?php echo htmlspecialchars($motivation_data['advice_section']['title'] ?? 'نصائح سريعة لكتابة خطاب الدافع', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة النصائح (تعديل / إضافة / حذف)</label>
                    <div id="motivationAdviceContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $advice_items = $motivation_data['advice_section']['items'] ?? [];
                          if (!empty($advice_items)): 
                            foreach ($advice_items as $index => $item): 
                        ?>
                                <div class="p-3 shadow-sm advice-item d-flex align-items-center gap-2" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="motivation_advice_<?php echo $index; ?>">
                                    <input type="text" class="form-control" name="advice_items[]" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" placeholder="اكتب النصيحة هنا...">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('motivation_advice_<?php echo $index; ?>')" title="حذف النصيحة"><i class="bi bi-trash"></i></button>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <!-- زر إضافة نصيحة جديدة بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMotivationAdviceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نصيحة جديدة
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationAdviceForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Files Modal -->
<div class="modal fade custom-modal" id="motivationDownloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-down text-primary"></i> إدارة ملفات النماذج المتاحة للتحميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="motivationDownloadForm" method="POST">
                    <input type="hidden" name="action" value="update_motivation_downloads">
                    
                    <label class="form-label fw-semibold small text-secondary mb-3">قائمة الملفات (تعديل / إضافة / حذف)</label>
                    <div id="motivationDownloadContainer" class="d-flex flex-column gap-3 mb-3">
                        <?php 
                          $download_items = $motivation_data['download_items'] ?? [];
                          if (!empty($download_items)): 
                            foreach ($download_items as $index => $dl): 
                        ?>
                                <div class="p-4 shadow-sm position-relative download-item-box" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="motivation_download_<?php echo $index; ?>">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">نوع الملف</label>
                                            <select class="form-select" name="download_types[]">
                                                <option value="pdf" <?php echo (strtolower($dl['type'] ?? '') === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                                <option value="word" <?php echo (strtolower($dl['type'] ?? '') === 'word') ? 'selected' : ''; ?>>Word</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold small text-secondary">عنوان البطاقة</label>
                                            <input type="text" class="form-control" name="download_titles[]" value="<?php echo htmlspecialchars($dl['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="عنوان البطاقة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">النوع الفرعي (Sub)</label>
                                            <input type="text" class="form-control" name="download_subs[]" value="<?php echo htmlspecialchars($dl['sub'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="النوع الفرعي">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">مسار الملف (URL)</label>
                                            <input type="text" class="form-control" name="download_files[]" value="<?php echo htmlspecialchars($dl['file'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" placeholder="مسار الملف">
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="removeRow('motivation_download_<?php echo $index; ?>')">
                                            <i class="bi bi-trash me-1"></i> حذف هذا النموذج
                                        </button>
                                    </div>
                                </div>
                        <?php 
                            endforeach; 
                          endif; 
                        ?>
                    </div>

                    <!-- زر إضافة نموذج تحميل جديد بنفس الستايل الموحد -->
                    <button type="button" class="btn w-100 mt-2 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMotivationDownloadRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle me-1"></i> إضافة نموذج تحميل جديد
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="motivationDownloadForm" class="btn-premium">حفظ التغييرات</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Engine -->
<script>
    // دالة عامة لحذف أي صف أو عنصر ديناميكي
    function removeMotivationRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // إدارة صفوف نصائح كتابة خطاب الدافع
    let motivationAdviceIndex = <?php echo count($motivation_data['advice_section']['items'] ?? []); ?>;
    function addMotivationAdviceRow() {
        const container = document.getElementById('motivationAdviceContainer');
        const div = document.createElement('div');
        div.className = 'input-group advice-item';
        div.id = 'motivation_advice_' + motivationAdviceIndex;
        div.innerHTML = `
            <input type="text" class="form-control" name="advice_items[]" placeholder="اكتب النصيحة هنا...">
            <button type="button" class="btn btn-outline-danger" onclick="removeMotivationRow('motivation_advice_${motivationAdviceIndex}')"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(div);
        motivationAdviceIndex++;
    }

    // إدارة صفوف نماذج التحميل الديناميكية
    let motivationDownloadIndex = <?php echo count($motivation_data['download_items'] ?? []); ?>;
    function addMotivationDownloadRow() {
        const container = document.getElementById('motivationDownloadContainer');
        const div = document.createElement('div');
        div.className = 'p-3 border rounded bg-light position-relative download-item-box';
        div.id = 'motivation_download_' + motivationDownloadIndex;
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">نوع الملف</label>
                    <select class="form-select form-select-sm" name="download_types[]">
                        <option value="pdf">PDF</option>
                        <option value="word" selected>Word</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold">عنوان البطاقة</label>
                    <input type="text" class="form-control form-control-sm" name="download_titles[]" value="خطاب الدافع / التحفيز">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">النوع الفرعي (Sub)</label>
                    <input type="text" class="form-control form-control-sm" name="download_subs[]" value="Example (Word)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">مسار الملف (URL)</label>
                    <input type="text" class="form-control form-control-sm" name="download_files[]" value="assets/files/motivation_letter.docx">
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-3" onclick="removeMotivationRow('motivation_download_${motivationDownloadIndex}')"><i class="bi bi-trash"></i> حذف هذا النموذج</button>
        `;
        container.appendChild(div);
        motivationDownloadIndex++;
    }

    // ربط كافة النماذج عبر AJAX للإرسال الفوري وتحديث الصفحة
    document.querySelectorAll('#motivationBreadcrumbForm, #motivationHeroForm, #motivationMainForm, #motivationAdviceForm, #motivationDownloadForm').forEach(form => {
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
