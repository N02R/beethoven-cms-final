document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-icon')) {
        let wrapper = e.target.closest('.editable-element') || e.target.closest('.editable-wrapper');
        let field = wrapper.dataset.field;

        // 1. منطق الصور (Logo)
        if (field === 'logo_path') {
            document.getElementById('currentLogo').src = wrapper.querySelector('img').src;
            document.getElementById('logoModal').style.display = 'block';
            return; // نخرج لأننا لا نريد فتح prompt
        }

        // 2. منطق النصوص العادية
        let contentEl = wrapper.querySelector('.editable-content');
        let currentText = contentEl ? contentEl.innerText : "";
        let newValue = prompt("أدخل النص الجديد:", currentText);
        
        if (newValue !== null && newValue !== currentText) {
            saveData(wrapper.dataset.page, wrapper.dataset.section, field, newValue);
        }
    }
});

// دالة حفظ النصوص
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

// التعامل مع رفع الصورة من الـ Modal
const uploadForm = document.getElementById('uploadForm');
if (uploadForm) {
    uploadForm.onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('/api/upload_logo.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => alert("خطأ في رفع الصورة"));
    };
}
