(function () {
  //Elementos del DOM que usa el script
  const dateSelect = document.getElementById('class_date');
  const timeSelect = document.getElementById('class_time');
  const help = document.getElementById('time-help');
  //URL de la API que devuelve horas disponibles (provista en data-attribute)
  const url = dateSelect ? dateSelect.dataset.availabilityUrl : null;
  //Valores auxiliares que pueden venir del servidor cuando se edita una reserva
  const oldDate = dateSelect ? dateSelect.dataset.oldDate : '';
  const except = dateSelect ? dateSelect.dataset.except : null;

  //Helpers de formato de fecha
  function pad(n) { return n < 10 ? '0' + n : n }
  function formatYMD(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
  function formatDisplay(d) { return pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear(); }

  //Número de días laborables a mostrar en el select de fechas
  const DAYS = 30;

  //Rellena el select de fecha con los próximos DAYS días laborables
  (function populateDates() {
    if (!dateSelect) return;
    const today = new Date();
    for (let i = 0, added = 0; added < DAYS; i++) {
      const d = new Date(today);
      d.setDate(today.getDate() + i);
      const dow = d.getDay();
      //Saltar fines de semana (domingo=0, sábado=6)
      if (dow === 0 || dow === 6) continue;

      const val = formatYMD(d);
      const opt = document.createElement('option');
      opt.value = val;
      opt.textContent = formatDisplay(d) + ' (' + ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'][dow] + ')';
      //Mantener selección previa si viene desde el servidor
      if (oldDate === val) opt.selected = true;
      dateSelect.appendChild(opt);
      added++;
    }
  })();

  //Carga las horas disponibles para una fecha concreta mediante fetch a la API
  async function loadTimesFor(date) {
    if (!date || !url) return;
    help.textContent = 'Comprobando disponibilidad...';
    try {
      let fetchUrl = url + '?date=' + encodeURIComponent(date);
      if (except) fetchUrl += '&except=' + encodeURIComponent(except);
      const res = await fetch(fetchUrl);
      if (!res.ok) throw new Error('Error');
      const data = await res.json();

      //Reiniciar el select de horas
      timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';

      //Si no hay horas disponibles, avisar al usuario
      if ((data.available || []).length === 0) {
        help.textContent = 'No hay horas disponibles para esta fecha.';
        return;
      }
      help.textContent = '';

      //Preservar la selección anterior (en formularios de edición)
      const prevSelected = timeSelect ? (timeSelect.dataset.oldTime || timeSelect.value) : null;
      const available = Array.isArray(data.available) ? data.available.slice() : [];
      if (prevSelected && !available.includes(prevSelected)) {
        //Insertar la selección previa al inicio para que el usuario no la pierda
        available.unshift(prevSelected);
      }

      //Añadir las opciones de hora al select
      available.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        if (prevSelected && prevSelected === t) opt.selected = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      //Mensaje genérico en caso de fallo de red o error en la API
      help.textContent = 'No se pudo comprobar disponibilidad.';
    }
  }

  // Eventos: al cambiar la fecha, recargar horas; también cargar si ya había un valor
  if (dateSelect) dateSelect.addEventListener('change', function () { loadTimesFor(this.value); });
  if (dateSelect && dateSelect.value) loadTimesFor(dateSelect.value);

  // VALIDACIÓN DE TELÉFONO (cliente)
  (function () {
    const form = document.getElementById('booking-form');
    if (!form) return;
    const phone = form.querySelector('input[name="phone"]');
    // Permitimos dígitos, espacios, '+', paréntesis y guiones
    const phonePattern = /^[0-9+\s\-()]+$/;

    // Muestra un error inline junto al campo
    function showError(el, msg) {
      let node = el.nextElementSibling;
      if (!node || !node.classList || !node.classList.contains('client-error')) {
        node = document.createElement('p');
        node.className = 'client-error text-red-600 text-sm mt-1';
        el.parentNode.insertBefore(node, el.nextSibling);
      }
      node.textContent = msg;
    }
    // Quita el error inline
    function clearError(el) {
      const node = el.nextElementSibling;
      if (node && node.classList && node.classList.contains('client-error')) node.remove();
    }

    // Validación en tiempo real mientras el usuario escribe
    if (phone) {
      phone.addEventListener('input', function () {
        const v = phone.value.trim();
        if (v === '') { clearError(phone); return; }
        if (!phonePattern.test(v)) {
          showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        } else {
          clearError(phone);
        }
      });
    }

    // Previene el envío del formulario si el teléfono es inválido
    form.addEventListener('submit', function (e) {
      if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
        e.preventDefault();
        showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        phone.focus();
      }
    });
  })();
})();