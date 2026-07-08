// public/js/editor.js
console.log("تم تحميل ملف editor.js");

document.addEventListener('DOMContentLoaded', () => {
    console.log("الصفحة جاهزة، جاري التحقق من وضع التعديل...");
    
    const urlParams = new URLSearchParams(window.location.search);
    const isEditMode = urlParams.get('edit') === 'true';

    console.log("هل هو وضع التعديل؟", isEditMode);

    if (window.location.pathname === '/dashboard' || isEditMode) {
        
        fetch('/admin/check-auth')
            .then(response => response.json())
            .then(data => {
                console.log("رد السيرفر:", data);
                if (data.is_admin) {
                    console.log("تم التأكد من الصلاحية، جاري تفعيل التحرير...");
                    enableEditing();
                } else {
                    console.log("ليس لديك صلاحية تعديل!");
                }
            })
            .catch(err => console.error("خطأ في الاتصال بالسيرفر:", err));
    }
});

function enableEditing() {
    console.log("تم استدعاء دالة التحرير!");
    const elements = document.querySelectorAll('.editable');
    console.log("عدد العناصر القابلة للتعديل:", elements.length);
    
    elements.forEach(el => {
        el.setAttribute('contenteditable', 'true');
        el.classList.add('edit-active');
        console.log("تم تفعيل التعديل للعنصر:", el);
    });
}
