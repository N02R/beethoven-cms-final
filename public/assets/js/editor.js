document.addEventListener('DOMContentLoaded', () => {
    
    // 1. التعامل مع جميع ضغطات القلم (Event Delegation)
    document.body.addEventListener('click', (e) => {
        let icon = e.target.closest('.edit-icon');
        if (!icon) return;

        let wrapper = icon.closest('.editable-element');
        if (!wrapper) return;

        let field = wrapper.dataset.field;
        let page = wrapper.dataset.page;
        let section = wrapper.dataset.section;

        // --- حالة الشعار (Image Modal) ---
        if (field === 'logo_path') {
            document.getElementById('currentLogo').src = wrapper.querySelector('img').src;
            document.getElementById('logoModal').style.display = 'block';
        } 
        
        // --- حالة الروابط الاجتماعية (Social Links) ---
        else if (field === 'all_links') {
            alert("لإدارة الروابط، يرجى التوجه للوحة تحكم الموقع، أو تحديث الروابط مباشرة في قاعدة البيانات.");
        }

        // --- حالة النصوص العادية (Prompt) ---
        else {
            let contentEl = wrapper.querySelector('.editable-content') || wrapper.firstChild;
            let currentText = contentEl.textContent || "";
            let newValue = prompt("أدخل النص الجديد:", currentText.trim());

            if (newValue !== null && newValue !== currentText.trim()) {
                saveData(page, section, field, newValue);
            }
        }
    });

    // 2. معالجة رفع الشعار (بدون إعادة تحميل الصفحة)
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
                location.reload(); // تحديث الصفحة لرؤية الشعار الجديد
            })
            .catch(err => alert("حدث خطأ في الرفع"));
        };
    }
});

// 3. دالة الحفظ العامة للنصوص
function saveData(page, section, field, content) {
    fetch('/api/save.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ page, section, field, content })
    })
    .then(res => res.text())
    .then(data => {
        location.reload(); 
    })
    .catch(error => alert("خطأ في الاتصال: " + error));
}
