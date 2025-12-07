
// Script para la página de edición de reservas (panel de usuario)
// - Carga las horas disponibles para la fecha seleccionada, excluyendo la propia reserva mediante `except`
// - Valida el campo teléfono y evita el envío si no cumple el patrón permitido

(function(){
  const dateInput = document.querySelector('input[name="class_date"][data-availability-url]');
  const timeSelect = document.getElementById('class_time');
  const help = document.getElementById('time-help');
  const url = dateInput ? dateInput.dataset.availabilityUrl : null;
  const except = dateInput ? dateInput.dataset.except : null; // id de la reserva a excluir

  // Solicita al servidor las horas libres para una fecha, pasando `except` para excluir la propia reserva
  async function loadTimesFor(date) {
    if (!date || !url) return;
    if (!help) return;
    help.textContent = 'Comprobando disponibilidad...';
    try {
      const res = await fetch(url + '?date=' + encodeURIComponent(date) + '&except=' + encodeURIComponent(except));
      if (!res.ok) throw new Error('Error');
      const data = await res.json();
      if (!timeSelect) return;
      timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';
      if ((data.available || []).length === 0) {
        help.textContent = 'No hay horas disponibles para esta fecha.';
        return;
      }
      help.textContent = '';
      data.available.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        const oldTime = timeSelect ? timeSelect.dataset.oldTime : null;
        if (oldTime && oldTime === t) opt.selected = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      help.textContent = 'No se pudo comprobar disponibilidad.';
    }
  }

  if (dateInput) dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
  if (dateInput && dateInput.value) loadTimesFor(dateInput.value);

  // Validación cliente del teléfono en el formulario de edición
  (function(){
    const form = document.getElementById('booking-edit-form');
    if (!form) return;
    const phone = form.querySelector('input[name="phone"]');
    const phonePattern = /^[0-9+\s\-()]+$/;

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

    if (phone) {
      // Validación en tiempo real mientras se escribe
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

    // Evita el envío si el teléfono es inválido
    form.addEventListener('submit', function(e){
      if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
        e.preventDefault();
        showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        phone.focus();
      }
    });
  })();
})();