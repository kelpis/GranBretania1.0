// Validaciones para el formulario de solicitud de traducción
// - Valida en cliente el archivo adjunto (extensión y tamaño máximo)
// - Muestra mensajes de error en línea sin bloquear la UI

(function(){
  const form = document.getElementById('translation-form');
  if (!form) return;
  const fileInput = form.querySelector('input[name="file"]');
  const allowed = ['pdf','doc','docx','odt','txt','rtf'];
  const maxBytes = 10240 * 1024; // 10MB en bytes

  // Muestra un mensaje de error justo después del input (o lo crea si no existe)
  function showError(el, msg) {
    let node = el.nextElementSibling;
    if (!node || !node.classList || !node.classList.contains('client-error')) {
      node = document.createElement('p');
      node.className = 'client-error text-red-600 text-sm mt-1';
      el.parentNode.insertBefore(node, el.nextSibling);
    }
    node.textContent = msg;
  }
  // Limpia el mensaje de error si existe
  function clearError(el) {
    const node = el.nextElementSibling;
    if (node && node.classList && node.classList.contains('client-error')) node.remove();
  }

  // Validación en el evento change del input file
  if (fileInput) {
    fileInput.addEventListener('change', function(){

      // Limpiar cualquier mensaje de error previo para este input
      clearError(fileInput);

      // Obtener el primer archivo seleccionado (si existe)
      const f = this.files && this.files[0];

      if (!f) return; // Si no hay archivo, salir sin hacer nada
      // Extraer la extensión del nombre del archivo, convirtiéndola a minúsculas
      const ext = f.name.split('.').pop().toLowerCase();

      // Verificar si la extensión está en la lista de formatos permitidos
      if (!allowed.includes(ext)) {
        // Mostrar error y resetear el input para forzar nueva selección
        showError(fileInput, 'Formato no permitido. Usa: ' + allowed.join(', ').toUpperCase());
        this.value = '';
        return;
      }

      // Verificar si el tamaño del archivo excede el límite máximo (10MB)
      if (f.size > maxBytes) {
        // Mostrar error y resetear el input
        showError(fileInput, 'El archivo excede el tamaño máximo de 10MB.');
        this.value = '';
        return;
      }
    });
  }

  // Comprobación final al enviar el formulario (por si se desactiva JS o no se ha cambiado el input)
  form.addEventListener('submit', function(e){
    // Limpiar mensajes de error previos
    clearError(fileInput);
    // Obtener el archivo seleccionado
    const f = fileInput.files && fileInput.files[0];
    // Si no hay archivo, mostrar error y prevenir envío
    if (!f) {
      showError(fileInput, 'Selecciona un archivo.');
      e.preventDefault();
      return;
    }
    // Extraer extensión del archivo
    const ext = f.name.split('.').pop().toLowerCase();
    // Verificar si extensión no está permitida o tamaño excede límite
    if (!allowed.includes(ext) || f.size > maxBytes) {
      // Mostrar error consolidado y prevenir envío
      showError(fileInput, 'Archivo no válido. Formatos: ' + allowed.join(', ').toUpperCase() + '. Tamaño máximo: 10MB.');
      e.preventDefault();
    }
  });
})();