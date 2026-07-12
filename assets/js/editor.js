document.addEventListener('DOMContentLoaded', () => {

    // 1. التقاط الضغط على أي أيقونة تعديل
    document.body.addEventListener('click', (e) => {
        let icon = e.target.closest('.edit-icon');
        if (!icon) return;

        e.preventDefault();
        e.stopPropagation();

        let wrapper = icon.closest('.editable-element');
        if (!wrapper) return;

        let { page, section, field } = wrapper.dataset;

        // طلب جلب البيانات من السيرفر (سنقوم بإنشاء هذا الملف لاحقاً)
        fetch(`/api/get_data.php?page=${page}&section=${section}&field=${field}`)
            .then(res => res.json())
            .then(data => {
                buildUniversalForm(data, page, section, field);
                document.getElementById('universalModal').style.display = 'block';
            })
            .catch(err => {
                console.error(err);
                alert("خطأ في جلب البيانات");
            });
    });

    // 2. معالجة إرسال المودال الموحد (حفظ البيانات)
    const universalForm = document.getElementById('universalForm');
    if (universalForm) {
        universalForm.onsubmit = function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            fetch('/api/save_all.php', { method: 'POST', body: formData })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    document.getElementById('universalModal').style.display = 'none';
                    location.reload();
                });
        };
    }
});

// 3. المحرك الذكي لبناء الحقول داخل المودال
function buildUniversalForm(data, page, section, field) {
    let container = document.getElementById('dynamicFields');
    container.innerHTML = ''; // تنظيف المودال
    
    // إضافة بيانات مخفية لتعريف الحقل عند الحفظ
    container.innerHTML += `<input type="hidden" name="page" value="${page}">`;
    container.innerHTML += `<input type="hidden" name="section" value="${section}">`;
    container.innerHTML += `<input type="hidden" name="field" value="${field}">`;

    // بناء الفورم حسب نوع الحقل
    if (field === 'logo_path') {
        container.innerHTML += `
            <img src="${data.value}" width="100" style="margin-bottom:10px;"><br>
            <label>اختر صورة جديدة:</label>
            <input type="file" name="new_file" class="form-control">`;
    } 
    else if (field === 'social_links') {
        // حلقة تكرار لبناء روابط السوشيال
        Object.entries(data).forEach(([key, url]) => {
            container.innerHTML += `
                <div style="margin-bottom:10px;">
                    <label>${key.toUpperCase()}:</label>
                    <input type="text" name="social[${key}]" value="${url}" class="form-control">
                </div>`;
        });
    } 
    else {
        // الافتراضي (نص أو HTML)
        container.innerHTML += `
            <label>المحتوى الحالي:</label>
            <textarea name="content" class="form-control" rows="4">${data.value}</textarea>`;
    }
}
