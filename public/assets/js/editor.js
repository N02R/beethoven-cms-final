document.addEventListener('DOMContentLoaded', () => {

    // 1. التقاط الضغط على أي أيقونة تعديل في الصفحة
    document.body.addEventListener('click', (e) => {
        let icon = e.target.closest('.edit-icon');
        if (!icon) return;

        // منع السلوك الافتراضي
        e.preventDefault();
        e.stopPropagation();

        let wrapper = icon.closest('.editable-element');
        if (!wrapper) return;

        let field = wrapper.dataset.field;
        let page = wrapper.dataset.page;
        let section = wrapper.dataset.section;

        // --- حالة الشعار (Image Modal) ---
        if (field === 'logo_path') {
            let imgElement = wrapper.querySelector('img');
            document.getElementById('currentLogo').src = imgElement ? imgElement.src : '';
            document.getElementById('logoModal').style.display = 'block';
        } 
        
        // --- حالة الروابط الاجتماعية (توجيه للإعدادات) ---
        else if (field === 'all_links') {
            if (confirm("هل تود التوجه لصفحة إعدادات الروابط الاجتماعية لتحديثها؟")) {
                window.location.href = '/admin/settings/social'; 
            }
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

    // 2. معالجة رفع الشعار (داخل المودال)
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
            })
            .catch(err => {
                console.error(err);
                alert("حدث خطأ أثناء الرفع");
            });
        };
    }
});

// 3. دالة حفظ النصوص والإعلانات
function saveTextData(page, section, field, content) {
    fetch('/api/save.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ page, section, field, content })
    })
    .then(res => res.text())
    .then(data => {
        location.reload(); 
    })
    .catch(error => {
        console.error(error);
        alert("خطأ في الاتصال بالسيرفر");
    });
}
