(function(){
  // Edit form phone & availability
  const dateInput = document.querySelector('input[name="class_date"][data-availability-url]');
  const timeSelect = document.getElementById('class_time');
  const help = document.getElementById('time-help');
  const url = dateInput ? dateInput.dataset.availabilityUrl : null;
  const except = dateInput ? dateInput.dataset.except : null;

  async function loadTimesFor(date) {
    if (!date || !url) return;
    help.textContent = 'Comprobando disponibilidad...';
    try {
      const res = await fetch(url + '?date=' + encodeURIComponent(date) + '&except=' + encodeURIComponent(except));
      if (!res.ok) throw new Error('Error');
      const data = await res.json();
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
        if (window.oldClassTime && window.oldClassTime === t) opt.selected = true;
        timeSelect.appendChild(opt);
      });
    } catch (e) {
      help.textContent = 'No se pudo comprobar disponibilidad.';
    }
  }

  if (dateInput) dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
  if (dateInput && dateInput.value) loadTimesFor(dateInput.value);

  // Phone validation for edit form
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

    form.addEventListener('submit', function(e){
      if (phone && phone.value.trim() !== '' && !phonePattern.test(phone.value.trim())) {
        e.preventDefault();
        showError(phone, 'El teléfono solo puede contener dígitos, espacios, +, paréntesis y guiones.');
        phone.focus();
      }
    });
  })();
})();