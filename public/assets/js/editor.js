document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-icon')) {
        let wrapper = e.target.closest('.editable-wrapper');
        let contentEl = wrapper.querySelector('.editable-content');
        let newText = prompt("تعديل النص:", contentEl.innerText);
        
        if (newText !== null && newText !== contentEl.innerText) {
            fetch('/api/save.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    page: wrapper.dataset.page,
                    section: wrapper.dataset.section,
                    field: wrapper.dataset.field,
                    content: newText
                })
            }).then(res => {
                if (res.ok) location.reload();
            });
        }
    }
});
