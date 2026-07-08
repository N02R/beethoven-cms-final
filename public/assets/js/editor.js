// استخدام delegation لالتقاط الضغط على أي قلم حتى لو تم تحديث الصفحة
document.body.addEventListener('click', function(e) {
    // التحقق إذا كان العنصر المضغوط هو الأيقونة أو داخلها
    let icon = e.target.closest('.edit-icon');
    if (icon) {
        let wrapper = icon.closest('.editable-element');
        if (!wrapper) return;

        let field = wrapper.dataset.field;

        // 1. منطق الصور (Logo)
        if (field === 'logo_path') {
            let img = wrapper.querySelector('img');
            document.getElementById('currentLogo').src = img ? img.src : '';
            document.getElementById('logoModal').style.display = 'block';
        } 
        // 2. منطق النصوص
        else {
            let contentEl = wrapper.querySelector('.editable-content') || wrapper.firstChild;
            let currentText = contentEl ? contentEl.textContent : "";
            let newValue = prompt("أدخل النص الجديد:", currentText.trim());
            
            if (newValue !== null && newValue !== currentText.trim()) {
                saveData(wrapper.dataset.page, wrapper.dataset.section, field, newValue);
            }
        }
    }
});

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
