// public/js/editor.js

document.addEventListener('DOMContentLoaded', () => {
    // التحقق من الرابط: هل نحن في صفحة الداشبورد أو أضفنا ?edit=true في الرابط؟
    const urlParams = new URLSearchParams(window.location.search);
    const isEditMode = urlParams.get('edit') === 'true';
    
    if (window.location.pathname === '/dashboard' || isEditMode) {
        
        // التحقق من صلاحية المدير عبر السيرفر
        fetch('/admin/check-auth')
            .then(response => response.json())
            .then(data => {
                if (data.is_admin) {
                    enableEditing();
                }
            });
    }
});

// دالة تفعيل التحرير المباشر
function enableEditing() {
    document.querySelectorAll('.editable').forEach(el => {
        el.setAttribute('contenteditable', 'true');
        el.classList.add('edit-active');
        
        // عند الانتهاء من الكتابة والخروج من العنصر (Blur)
        el.addEventListener('blur', function() {
            const newValue = this.innerText;
            const section = this.dataset.section;
            const key = this.dataset.key;
            
            // إرسال التعديل للسيرفر ليحفظه في قاعدة البيانات
            fetch('/admin/save-all', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        [section + '_' + key]: newValue })
                })
                .then(response => response.text())
                .then(data => console.log('تم حفظ التعديل بنجاح'))
                .catch(error => console.error('خطأ في الحفظ:', error));
        });
    });
}