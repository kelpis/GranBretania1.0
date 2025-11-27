// Scripts del cliente para la página de creación de reservas
// - Rellena el select de fechas (solo días laborables) durante los próximos `DAYS` días
// - Consulta la disponibilidad de horas vía endpoint `bookings.availability` y llena el select de horas
// - Valida el teléfono en cliente antes de enviar el formulario

const dateSelect = document.getElementById('class_date');
const timeSelect = document.getElementById('class_time');
const help = document.getElementById('time-help');
const url = dateSelect ? dateSelect.dataset.availabilityUrl : null;
const oldDate = dateSelect ? dateSelect.dataset.oldDate : '';

// Helpers de formato de fecha
function pad(n){ return n < 10 ? '0'+n : n }
function formatYMD(d){ return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()); }
function formatDisplay(d){ return pad(d.getDate()) + '/' + pad(d.getMonth()+1) + '/' + d.getFullYear(); }

const DAYS = 30; // número de días a mostrar en el select

// Rellena el select de fechas excluyendo fines de semana
(function populateDates(){
  if (!dateSelect) return;
  const today = new Date();
  for (let i = 0, added = 0; added < DAYS; i++) {
    const d = new Date(today);
    d.setDate(today.getDate() + i);
    const dow = d.getDay();
    if (dow === 0 || dow === 6) continue; // 0=Dom,6=Sáb

    const val = formatYMD(d);
    const opt = document.createElement('option');
    opt.value = val;
    opt.textContent = formatDisplay(d) + ' (' + ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'][dow] + ')';
    if (oldDate === val) opt.selected = true;
    dateSelect.appendChild(opt);
    added++;
  }
})();

// Consulta al servidor para obtener las horas disponibles de una fecha
async function loadTimesFor(date) {
  if (!date || !url) return;
  if (!help) return;
  help.textContent = 'Comprobando disponibilidad...';
  try {
    const res = await fetch(url + '?date=' + encodeURIComponent(date));
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

    // Filtrado adicional en cliente: si la fecha seleccionada es hoy, quitar
    // las franjas cuya hora de inicio ya ha pasado en el reloj del usuario.
    try {
      const todayStr = formatYMD(new Date());
      if (date === todayStr) {
        const now = new Date();
        const nowMin = now.getHours() * 60 + now.getMinutes();
        // Recorremos las opciones y eliminamos las que sean <= ahora
        Array.from(timeSelect.options).forEach(opt => {
          const v = opt.value;
          if (!v) return; // omitimos la opción vacía
          const parts = v.split(':');
          if (parts.length < 2) return;
          const tMin = parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
          if (tMin <= nowMin) {
            // eliminar opción del select
            try { opt.remove(); } catch (e) { /* soportar navegadores distintos */ }
          }
        });
        // Si tras filtrar no quedan opciones útiles, mostrar mensaje
        if (timeSelect.options.length <= 1) { // solo la opción por defecto
          timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';
          help.textContent = 'No hay horas disponibles para esta fecha.';
        }
      }
    } catch (e) {
      // Si falla el filtrado cliente no hacemos nada; la validación server-side sigue vigente
    }
  } catch (e) {
    help.textContent = 'No se pudo comprobar disponibilidad.';
  }
}

// Eventos para cargar horas cuando cambia la fecha o si la fecha ya viene seleccionada
if (dateSelect) dateSelect.addEventListener('change', function(){ loadTimesFor(this.value); });
if (dateSelect && dateSelect.value) loadTimesFor(dateSelect.value);

// Validación de teléfono en el formulario de creación
(function(){
  const form = document.getElementById('booking-form');
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
    // Validación en tiempo real mientras el usuario escribe
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

  // Bloquear envío si el teléfono no cumple el patrón
  form.addEventListener('submit', function(e){
    if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
      e.preventDefault();
      showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
      phone.focus();
    }
  });
})();
