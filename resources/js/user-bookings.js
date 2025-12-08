
// Script para la página de edición de reservas (panel de usuario)


(function(){
  // Seleccionar elementos del DOM necesarios para la funcionalidad
  const dateInput = document.querySelector('input[name="class_date"][data-availability-url]');
  const timeSelect = document.getElementById('class_time');
  const help = document.getElementById('time-help');

  // Obtener URL de disponibilidad y ID de reserva a excluir (para edición)
  const url = dateInput ? dateInput.dataset.availabilityUrl : null;
  const except = dateInput ? dateInput.dataset.except : null; // id de la reserva a excluir

  // Función asíncrona para solicitar horas disponibles al servidor
  // Envía la fecha y el ID de reserva a excluir para evitar conflictos
  async function loadTimesFor(date) {
    // Verificar que se tengan los parámetros necesarios
    if (!date || !url) return;
    if (!help) return;
    // Mostrar mensaje de carga mientras se consulta
    help.textContent = 'Comprobando disponibilidad...';
    try {
      // Hacer petición fetch con parámetros de fecha y excepción
      const res = await fetch(url + '?date=' + encodeURIComponent(date) + '&except=' + encodeURIComponent(except));
      if (!res.ok) throw new Error('Error');
      // Parsear respuesta JSON con horas disponibles
      const data = await res.json();
      if (!timeSelect) return;
      // Limpiar y resetear el select de horas
      timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';
      // Si no hay horas disponibles, mostrar mensaje
      if ((data.available || []).length === 0) {
        help.textContent = 'No hay horas disponibles para esta fecha.';
        return;
      }

      // Limpiar mensaje de ayuda si hay opciones
      help.textContent = '';
      // Agregar cada hora disponible como opción en el select
      data.available.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        // Seleccionar la hora anterior si coincide
        const oldTime = timeSelect ? timeSelect.dataset.oldTime : null;
        if (oldTime && oldTime === t) opt.selected = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      // Mostrar error si falla la petición
      help.textContent = 'No se pudo comprobar disponibilidad.';
    }
  }

  // Evento para cargar horas cuando cambia la fecha
  if (dateInput) dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
  // Cargar horas iniciales si ya hay una fecha seleccionada
  if (dateInput && dateInput.value) loadTimesFor(dateInput.value);



  // Validación cliente del teléfono en el formulario de edición
  (function(){
    // Obtener el formulario de edición
    const form = document.getElementById('booking-edit-form');
    if (!form) return;
    // Seleccionar el input de teléfono
    const phone = form.querySelector('input[name="phone"]');
    // Patrón regex para validar teléfono: dígitos, espacios, +, paréntesis, guiones
    const phonePattern = /^[0-9+\s\-()]+$/;



    // Función para mostrar mensaje de error junto al input
    function showError(el, msg) {
      let node = el.nextElementSibling;
      if (!node || !node.classList || !node.classList.contains('client-error')) {
        node = document.createElement('p');
        node.className = 'client-error text-red-600 text-sm mt-1';
        el.parentNode.insertBefore(node, el.nextSibling);
      }
      node.textContent = msg;
    }

    
    // Función para limpiar mensaje de error
    function clearError(el) {
      const node = el.nextElementSibling;
      if (node && node.classList && node.classList.contains('client-error')) node.remove();
    }

    // Si existe el input de teléfono, agregar validación en tiempo real
    if (phone) {
      // Evento input para validar mientras el usuario escribe
      phone.addEventListener('input', function(){
        const v = phone.value.trim();
        if (v === '') { clearError(phone); return; }
        // Si no cumple el patrón, mostrar error
        if (!phonePattern.test(v)) {
          showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        } else {
          clearError(phone);
        }
      });
    }

    // Evento submit para bloquear envío si teléfono es inválido
    form.addEventListener('submit', function(e){
      if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
        e.preventDefault();
        showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        phone.focus();
      }
    });
  })();
})();