document.addEventListener('DOMContentLoaded', () => {
    // التحقق من السيرفر إذا كان المستخدم مديراً
    fetch('/admin/check-auth')
        .then(response => response.json())
        .then(data => {
            if (data.is_admin) {
                enableEditing();
            }
        });
});

function enableEditing() {
    document.querySelectorAll('.editable').forEach(el => {
        el.setAttribute('contenteditable', 'true');
        el.classList.add('edit-active');
        
        el.addEventListener('blur', function() {
            const newValue = this.innerText;
            const section = this.dataset.section;
            const key = this.dataset.key;
            
            // إرسال البيانات عبر Fetch API
            fetch('/admin/save-all', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        [section + '_' + key]: newValue })
                })
                .then(response => response.text())
                .then(data => console.log('تم حفظ التعديل:', data))
                .catch(error => console.error('خطأ في الحفظ:', error));
        });
    });
}