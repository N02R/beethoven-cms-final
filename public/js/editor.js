document.addEventListener('DOMContentLoaded', () => {
    // التحقق هل نحن في وضع التحرير؟
    const isEditMode = localStorage.getItem('editMode') === 'true';
    
    if (isEditMode) {
        document.querySelectorAll('.editable').forEach(el => {
            el.setAttribute('contenteditable', 'true');
            el.style.border = "1px dashed #0d6efd"; // حدّ أزرق ليعرف المدير أنه قابل للتعديل
            
            // عند الخروج من العنصر بعد تعديله (Blur)
            el.addEventListener('blur', function() {
                const newValue = this.innerText;
                const section = this.dataset.section;
                const key = this.dataset.key;
                
                // إرسال البيانات للسيرفر
                saveData(section, key, newValue);
            });
        });
    }
});

function saveData(section, key, value) {
    const formData = new FormData();
    formData.append(section + '_' + key, value);
    
    fetch('/admin/save-all', {
        method: 'POST',
        body: formData
    }).then(response => {
        console.log('تم حفظ التعديل بنجاح!');
    });
}
