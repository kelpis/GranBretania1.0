
// Función para abrir el modal de cancelación de reserva en el admin.

window.openAdminCancelModal = function(formAction) {
    const modal = document.getElementById('adminCancelModal'); // Obtiene el elemento del modal.
    const form = document.getElementById('adminCancelForm'); // Obtiene el formulario dentro del modal.

    form.action = formAction; // Asigna la acción del formulario (URL para cancelar la reserva).

    // Muestra el modal cambiando clases de Tailwind: quita 'hidden' y añade 'flex'.
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

// Función para cerrar el modal de cancelación de reserva en el admin.
window.closeAdminCancelModal = function() {
    const modal = document.getElementById('adminCancelModal'); // Obtiene el elemento del modal.

    // Oculta el modal cambiando clases de Tailwind: añade 'hidden' y quita 'flex'.
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};