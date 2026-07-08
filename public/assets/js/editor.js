document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-icon')) {
        let wrapper = e.target.closest('.editable-wrapper');
        let contentEl = wrapper.querySelector('.editable-content');
        let newValue = prompt("أدخل النص الجديد:", contentEl.innerText);
        
        if (newValue !== null) {
            fetch('/api/save.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    page: wrapper.dataset.page,
                    section: wrapper.dataset.section,
                    field: wrapper.dataset.field,
                    content: newValue
                })
            }).then(response => response.text()) // تغيير لـ text لنرى رسالة الخطأ
.then(data => {
    console.log("استجابة السيرفر:", data);
    location.reload(); 
})
.catch(error => console.error("خطأ في الاتصال:", error));

        }
    }
});

