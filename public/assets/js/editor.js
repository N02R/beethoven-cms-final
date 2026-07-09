document.addEventListener('DOMContentLoaded', () => {

    // 1. التقاط الضغط على أي أيقونة تعديل في الصفحة
    document.body.addEventListener('click', (e) => {
        let icon = e.target.closest('.edit-icon');
        if (!icon) return;

        e.preventDefault();
        e.stopPropagation();

        let wrapper = icon.closest('.editable-element');
        if (!wrapper) return;

        let field = wrapper.dataset.field;
        let page = wrapper.dataset.page;
        let section = wrapper.dataset.section;

        // --- حالة الشعار (Logo) ---
        if (field === 'logo_path') {
            let imgElement = wrapper.querySelector('img');
            document.getElementById('currentLogo').src = imgElement ? imgElement.src : '';
            document.getElementById('logoModal').style.display = 'block';
        } 
        
        // --- حالة الروابط الاجتماعية (Social Icons Individual) ---
        else if (wrapper.classList.contains('editable-social')) {
            let currentUrl = wrapper.href;
            document.getElementById('newUrl').value = currentUrl;
            document.getElementById('socialModal').style.display = 'block';
            
            // ربط الفورم بالبيانات الحالية للرابط
            window.activeSocialField = field; 
        }

        // --- حالة النصوص والإعلانات (Prompt) ---
        else {
            let contentEl = wrapper.querySelector('.editable-content') || wrapper.firstChild;
            let currentText = contentEl.textContent || "";
            let newValue = prompt("أدخل المحتوى الجديد:", currentText.trim());

            if (newValue !== null && newValue !== currentText.trim()) {
                saveTextData(page, section, field, newValue);
            }
        }
    });

    // 2. معالجة رفع الشعار (Logo)
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.onsubmit = function(e) {
            e.preventDefault(); 
            let formData = new FormData(this);
            fetch('/api/upload_logo.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                alert(data);
                document.getElementById('logoModal').style.display = 'none';
                location.reload(); 
            });
        };
    }

    // 3. معالجة تحديث الروابط الاجتماعية
    const socialForm = document.getElementById('socialUpdateForm');
    if (socialForm) {
        socialForm.onsubmit = function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('field', window.activeSocialField);
            
            fetch('/api/update_social.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        };
    }
});

// 4. دالة حفظ النصوص والإعلانات
function saveTextData(page, section, field, content) {
    fetch('/api/save.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ page, section, field, content })
    })
    .then(res => res.text())
    .then(data => { location.reload(); })
    .catch(error => { alert("خطأ في الاتصال بالسيرفر"); });
}
