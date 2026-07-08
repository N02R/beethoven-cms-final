document.addEventListener('DOMContentLoaded', () => {
    // التحقق من حالة التعديل من الـ LocalStorage
    const isEditMode = localStorage.getItem('editMode') === 'true';
    
    if (isEditMode) {
        document.querySelectorAll('.editable').forEach(el => {
            el.setAttribute('contenteditable', 'true');
            // إضافة كلاس خاص للتنسيق فقط في وضع التعديل
            el.classList.add('edit-active');
            
            el.addEventListener('blur', function() {
                const newValue = this.innerText;
                const section = this.dataset.section;
                const key = this.dataset.key;
                
                fetch('/admin/save-all', {
                    method: 'POST',
                    body: new URLSearchParams({
                        [section + '_' + key]: newValue })
                }).then(() => console.log('تم الحفظ'));
            });
        });
    }
});