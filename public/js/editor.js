// public/js/editor.js

document.addEventListener('DOMContentLoaded', () => {
    // نتحقق أولاً: هل نحن في صفحة الـ Dashboard؟
    // أو يمكننا إضافة شرط آخر هنا إذا أردتِ التعديل في مكان آخر
    if (window.location.pathname === '/dashboard') {
        
        // التحقق من صلاحية المدير من السيرفر
        fetch('/admin/check-auth')
            .then(response => response.json())
            .then(data => {
                if (data.is_admin) {
                    enableEditing();
                }
            });
    }
});

function enableEditing() {
    document.querySelectorAll('.editable').forEach(el => {
        el.setAttribute('contenteditable', 'true');
        el.classList.add('edit-active');
        
        el.addEventListener('blur', function() {
            const newValue = this.innerText;
            const section = this.dataset.section;
            const key = this.dataset.key;
            
            fetch('/admin/save-all', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        [section + '_' + key]: newValue })
                })
                .then(() => console.log('تم حفظ التعديل'));
        });
    });
}