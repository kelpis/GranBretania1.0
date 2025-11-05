// client-side for translation form
(function(){
  const form = document.getElementById('translation-form');
  if (!form) return;
  const fileInput = form.querySelector('input[name="file"]');
  const allowed = ['pdf','doc','docx','odt','txt','rtf'];
  const maxBytes = 10240 * 1024; // 10MB

  function showError(el, msg) {
    let node = el.nextElementSibling;
    if (!node || !node.classList || !node.classList.contains('client-error')) {
      node = document.createElement('p');
      node.className = 'client-error text-red-600 text-sm mt-1';
      el.parentNode.insertBefore(node, el.nextSibling);
    }
    node.textContent = msg;
  }
  function clearError(el) {
    const node = el.nextElementSibling;
    if (node && node.classList && node.classList.contains('client-error')) node.remove();
  }

  if (fileInput) {
    fileInput.addEventListener('change', function(){
      clearError(fileInput);
      const f = this.files && this.files[0];
      if (!f) return;
      const ext = f.name.split('.').pop().toLowerCase();
      if (!allowed.includes(ext)) {
        showError(fileInput, 'Formato no permitido. Usa: ' + allowed.join(', ').toUpperCase());
        this.value = '';
        return;
      }
      if (f.size > maxBytes) {
        showError(fileInput, 'El archivo excede el tamaño máximo de 10MB.');
        this.value = '';
        return;
      }
    });
  }

  form.addEventListener('submit', function(e){
    clearError(fileInput);
    const f = fileInput.files && fileInput.files[0];
    if (!f) {
      showError(fileInput, 'Selecciona un archivo.');
      e.preventDefault();
      return;
    }
    const ext = f.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext) || f.size > maxBytes) {
      showError(fileInput, 'Archivo no válido. Formatos: ' + allowed.join(', ').toUpperCase() + '. Tamaño máximo: 10MB.');
      e.preventDefault();
    }
  });
})();