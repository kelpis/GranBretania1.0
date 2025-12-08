// Script de reCAPTCHA v3
// Se ejecuta en formularios con data-grecaptcha="v3"

document.addEventListener('DOMContentLoaded', function () {
    const siteKey = window.recaptchaSiteKey;

    if (!siteKey) {
        console.warn('reCAPTCHA site key not set');
        return;
    }

    // Cargar el script de reCAPTCHA si no está cargado
    if (typeof grecaptcha === 'undefined') {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
        script.onload = initRecaptcha;
    } else {
        initRecaptcha();
    }

    function initRecaptcha() {
        document.querySelectorAll('form[data-grecaptcha="v3"]').forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    const action = form.getAttribute('data-recaptcha-action') || 'submit';
                    grecaptcha.execute(siteKey, {action: action}).then(function(token) {
                        let input = form.querySelector('input[name="g-recaptcha-response"]');
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'g-recaptcha-response';
                            form.appendChild(input);
                        }
                        input.value = token;
                        form.submit();
                    }).catch(function(err) {
                        // Evitar "uncaught (in promise) null" y mostrar info útil
                        console.error('reCAPTCHA execute failed', err);
                        let errEl = form.querySelector('.recaptcha-error');
                        if (!errEl) {
                            errEl = document.createElement('p');
                            errEl.className = 'recaptcha-error text-red-600 text-sm mt-2';
                            // intentar insertar antes del botón submit si existe
                            const submit = form.querySelector('[type="submit"]');
                            if (submit && submit.parentNode) {
                                submit.parentNode.insertBefore(errEl, submit.nextSibling);
                            } else {
                                form.appendChild(errEl);
                            }
                        }
                        errEl.textContent = 'No se pudo verificar reCAPTCHA en tu navegador. Prueba en una ventana privada o desactiva extensiones que bloqueen scripts.';
                    });
                });
            });
        });
    }
});

// Debug en entorno local
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    console.log('DEBUG: reCAPTCHA site key present?', !!window.recaptchaSiteKey);
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DEBUG: grecaptcha defined?', (typeof grecaptcha !== 'undefined'));
        if (typeof grecaptcha !== 'undefined') {
            try {
                grecaptcha.ready(function() {
                    console.log('DEBUG: grecaptcha.ready executed');
                });
            } catch (e) {
                console.error('DEBUG: grecaptcha.ready error', e);
            }
        }
    });
}