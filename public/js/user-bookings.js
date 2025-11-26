(function(){
  //Formulario de edición: manejo de disponibilidad y validación de teléfono
  //Selecciona elementos del DOM necesarios
  const dateInput = document.querySelector('input[name="class_date"][data-availability-url]');
  const timeSelect = document.getElementById('class_time');
  const help = document.getElementById('time-help');
  //URL que devuelve horas disponibles 
  const url = dateInput ? dateInput.dataset.availabilityUrl : null;
  //Parámetro opcional para excluir una franja (usado en edición)
  const except = dateInput ? dateInput.dataset.except : null;

  //Llama a la API para obtener horas disponibles para la fecha indicada
  async function loadTimesFor(date) {
    if (!date || !url) return;
    //Mensaje de estado mientras se consulta la API
    help.textContent = 'Comprobando disponibilidad...';
    try {
      const res = await fetch(url + '?date=' + encodeURIComponent(date) + '&except=' + encodeURIComponent(except));
      if (!res.ok) throw new Error('Error');
      const data = await res.json();

      //Reiniciar el select de horas
      timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';

      //Si no hay horas disponibles, mostrar aviso
      if ((data.available || []).length === 0) {
        help.textContent = 'No hay horas disponibles para esta fecha.';
        return;
      }
      //Limpiar mensaje de ayuda
      help.textContent = '';

      //Rellenar opciones con las horas devueltas por la API
      data.available.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        // Si estamos en el formulario de edición, window.oldClassTime contiene la hora previa
        if (window.oldClassTime && window.oldClassTime === t) opt.selected = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      //Error de red o de API
      help.textContent = 'No se pudo comprobar disponibilidad.';
    }
  }

  //Eventos: recargar horas al cambiar la fecha; si hay valor inicial, cargarlo
  if (dateInput) dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
  if (dateInput && dateInput.value) loadTimesFor(dateInput.value);

  //VALIDACIÓN DE TELÉFONO (formulario de edición)
  (function(){
    const form = document.getElementById('booking-edit-form');
    if (!form) return;
    const phone = form.querySelector('input[name="phone"]');
    // Patrón permitido: dígitos, espacios, +, paréntesis y guiones
    const phonePattern = /^[0-9+\s\-()]+$/;

    // Mostrar un error inline junto al campo
    function showError(el, msg) {
      let node = el.nextElementSibling;
      if (!node || !node.classList || !node.classList.contains('client-error')) {
        node = document.createElement('p');
        node.className = 'client-error text-red-600 text-sm mt-1';
        el.parentNode.insertBefore(node, el.nextSibling);
      }
      node.textContent = msg;
    }
    //Quitar el error inline
    function clearError(el) {
      const node = el.nextElementSibling;
      if (node && node.classList && node.classList.contains('client-error')) node.remove();
    }

    //Validación en tiempo real mientras el usuario escribe
    if (phone) {
      phone.addEventListener('input', function(){
        const v = phone.value.trim();
        if (v === '') { clearError(phone); return; }
        if (!phonePattern.test(v)) {
          showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        } else {
          clearError(phone);
        }
      });
    }

    // Validación final en el submit: prevenir envío si teléfono inválido
    form.addEventListener('submit', function(e){
      if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
        e.preventDefault();
        showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        phone.focus();
      }
    });
  })();
})();