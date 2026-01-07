document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-sortable-collection="true"]').forEach(collection => {
            Sortable.create(collection, {
                animation: 150,
                handle: '.drag-handle',
                draggable: '.ea-form-group',
                ghostClass: 'sortable-ghost',
            });
        });
    }, 100); // 100ms suffit généralement
});
