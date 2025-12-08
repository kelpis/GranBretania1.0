//Modal de cancelación de reservas en el panel de usuario.


window.openCancelModal = function(formAction, isRefundable) {
    const modal = document.getElementById('cancelModal'); // Elemento del modal.
    const text = document.getElementById('cancelModalText'); // Texto del modal.
    const form = document.getElementById('cancelModalForm'); // Formulario del modal.

    form.action = formAction; // Asigna la acción del formulario.

    // Ajusta el texto según si es reembolsable.
    if (isRefundable === '1') {
        text.textContent = "¿Seguro que deseas cancelar la clase? Se reembolsará el importe automáticamente.";
    } else {
        text.textContent = "¿Quieres cancelar la clase? Las cancelaciones realizadas con menos de 24 horas de antelación no dan derecho a reembolso.";
    }

    // Muestra el modal cambiando clases CSS.
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

// Función para cerrar el modal de cancelación.
window.closeCancelModal = function() {
    const modal = document.getElementById('cancelModal'); // Elemento del modal.

    // Oculta el modal cambiando clases CSS.
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};