<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(t => new bootstrap.Tooltip(t));
    
    // Verificar sidebar
    if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
        console.log('Bootstrap Offcanvas cargado correctamente');
    }
});
</script>