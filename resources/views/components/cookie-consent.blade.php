{{--
  Componente: cookie-consent.blade.php
  Propósito: banner de consentimiento de cookies que muestra opciones "Aceptar" / "Rechazar".
  Comportamiento: guarda la decisión en la cookie `cookies_consent` y dispara eventos
  `cookies:accepted` y `cookies:rejected` para que otros scripts puedan reaccionar.
  
--}}
<div id="cookie-consent" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-[95%] sm:w-auto max-w-lg bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded shadow-md" style="display: none;">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        {{-- Texto explicativo del banner --}}
        <div class="flex-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC] leading-relaxed">
            Usamos cookies propias y de terceros para mejorar tu experiencia y análisis. Puedes aceptar todas las cookies o rechazarlas. Más información en
            <a href="{{ url('/cookies') }}" class="underline text-[#f53003] dark:text-[#FF6A5A]">la política de cookies</a>.
        </div>

        {{-- Botones de acción: aceptar y rechazar --}}
        <div class="flex items-center gap-2">
            <button id="cookie-accept" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">Aceptar</button>
            <button id="cookie-reject" class="px-3 py-2 bg-gray-100 text-gray-800 border border-gray-300 rounded hover:opacity-95 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Rechazar</button>
        </div>
    </div>

    <script>
        (function(){
            // Helpers: set/get cookie simple (no librería)
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }

            function getCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            // Elementos del DOM
            var banner = document.getElementById('cookie-consent');
            var acceptBtn = document.getElementById('cookie-accept');
            var rejectBtn = document.getElementById('cookie-reject');

            // Mostrar / ocultar banner
            function showBanner() {
                if (banner) banner.style.display = 'block';
            }
            function hideBanner() {
                if (banner) banner.style.display = 'none';
            }

            // Lógica: si no hay decisión previa, mostrar banner
            var consent = getCookie('cookies_consent');
            if (!consent) {
                // mostrar banner si no hay decisión previa
                showBanner();
            }

            // Aceptar: guardar cookie, ocultar y emitir evento para que otros scripts carguen analytics
            if (acceptBtn) acceptBtn.addEventListener('click', function(e){
                setCookie('cookies_consent', 'accepted', 365);
                hideBanner();
                // Emite evento global para que otros scripts inicialicen (p. ej. Google Analytics)
                window.dispatchEvent(new CustomEvent('cookies:accepted'));
            });

            // Rechazar: guardar cookie, ocultar y emitir evento opcional
            if (rejectBtn) rejectBtn.addEventListener('click', function(e){
                setCookie('cookies_consent', 'rejected', 365);
                hideBanner();
                window.dispatchEvent(new CustomEvent('cookies:rejected'));
            });
        })();
    </script>
</div>
