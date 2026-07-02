// Auto-cerrar toasts de notificación
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast').forEach(function (el) {
        setTimeout(function () {
            el.classList.remove('show');
        }, 4500);
    });
});

// Confirmación genérica para acciones de eliminación
function confirmarEliminacion(formId, mensaje) {
    if (confirm(mensaje || '¿Estás seguro de que deseas eliminar este registro?')) {
        document.getElementById(formId).submit();
    }
    return false;
}
