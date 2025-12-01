# Flujo de una reserva de clase (resumen sencillo)

Este documento describe, con lenguaje claro y sin tecnicismos, qué ocurre desde que un usuario solicita una reserva hasta que la clase queda confirmada o, en su caso, se cancela y se reembolsa.

1) Acceso a la página de reserva
- El usuario accede a la página para reservar en `/reservar`.
- La vista del formulario está en `resources/views/user/bookings/create.blade.php`.
- El formulario pide: fecha, hora, notas (opcional), y requiere aceptación de la política (GDPR). Incluye protección reCAPTCHA y el token CSRF para seguridad.

2) Comprobación previa de disponibilidad (opcional)
- Antes o durante el llenado, el frontend puede preguntar al servidor si una hora determinada está libre.
- Esto se hace vía una petición JSON a `/reservar/disponibilidad`, que devuelve las horas disponibles para la fecha solicitada.
- El código que responde a esa consulta está en `app/Http/Controllers/ClassBookingController.php` (método `availability`).

3) Envío del formulario
- Cuando el usuario pulsa “Reservar”, el navegador envía el formulario al servidor (`POST /reservar`).
- La ruta está protegida: solo usuarios autenticados pueden enviar reservas.

4) Validación en el servidor
- El servidor valida los datos con reglas definidas (fecha válida, hora en el rango permitido, reCAPTCHA, no reservar fines de semana, etc.).
- Las reglas están centralizadas para que la lógica sea consistente y segura (`app/Http/Requests/StoreClassBookingRequest.php`).
- También se comprueba que la franja no esté ocupada por otra reserva pagada o no bloqueada por el administrador.

5) Creación provisional de la reserva
- Si todo es correcto, se crea un registro de reserva en la base de datos con estado provisional (por ejemplo `pending`).
- Se asocia la reserva al usuario que la solicitó y se guarda información de control (metadatos para pagos, por ejemplo).
- El código que crea la reserva y prepara el pago está en `app/Http/Controllers/ClassBookingController.php` (método `store`).

6) Inicio del pago (Stripe Checkout)
- Para completar la reserva se crea una sesión de pago en Stripe. El servidor construye la sesión y añade una referencia a la reserva.
- El usuario es redirigido a la página segura de pago de Stripe (Checkout) para introducir sus datos de pago.

7) Pago y retorno
- En la interfaz de Stripe el usuario paga con tarjeta. Stripe gestiona los datos de pago; el servidor no recibe la tarjeta.
- Tras el pago, Stripe redirige al usuario a la URL de éxito o de cancelación definida en la sesión de pago.

8) Confirmación del pago por webhook
- Stripe envía automáticamente una notificación al servidor (webhook) para confirmar que el pago fue exitoso.
- El servidor recibe ese aviso y marca la reserva como `paid` (pagada). Importante: después del pago la reserva queda pagada, pero sigue en estado `pending` para que un admin la confirme manualmente.
- El controlador que procesa esas notificaciones es `app/Http/Controllers/StripeWebhookController.php`.
- Tras procesar el pago el sistema envía un email de acuse al usuario (confirmando que el pago se ha recibido) y un aviso al administrador.

9) Intervención del administrador
- El administrador revisa las reservas pendientes en su panel (`/admin/reservas`).
- Desde el panel puede confirmar la clase, añadir el enlace de videollamada (por ejemplo Google Meet) y asignar un profesor si hace falta.
- La confirmación (y el envío del enlace) la gestiona `app/Http/Controllers/BookingAdminController.php`.
- Hasta que el admin confirme y añada el enlace, la clase no se considera oficialmente programada.

10) Envío del enlace y notificación al usuario
- Cuando el admin confirma la reserva y añade el enlace de la videollamada, el sistema envía automáticamente un email al usuario con:
  - Confirmación de la clase
  - Fecha y hora
  - Enlace de reunión (Google Meet u otro)
- También pueden enviarse avisos al administrador o a otras direcciones según la configuración.

11) Unirse a la videollamada
- El usuario puede acceder al enlace desde su panel (`/mis-reservas`) o mediante un enlace firmado que permite entrar sin autenticarse durante un tiempo limitado.
- La ruta que redirige a la videollamada está en `ClassBookingController@join` y comprueba la firma o que el usuario es el propietario de la reserva.

12) Edición y cancelación por el usuario
- En su panel el usuario puede editar o cancelar la reserva antes de la clase, siguiendo las reglas del sistema (por ejemplo límite de número de ediciones o plazo mínimo para reembolso).
- Si la reserva es reembolsable (según tiempo restante y condiciones), el sistema intentará crear un reembolso vía Stripe y marcar la reserva como `cancelled` y `refunded` cuando corresponda.
- El código para editar/cancelar y gestionar la lógica está en `app/Http/Controllers/UserBookingController.php`.

13) Reembolso desde el panel administrador
- El administrador puede iniciar un reembolso manual desde el panel si procede.
- El reembolso se realiza contra la API de Stripe; si tiene éxito, se actualiza la reserva y se notifica al usuario por email.
- La acción de reembolso está implementada en `app/Http/Controllers/AdminController.php` (método `refund`).

14) Notificaciones y trazabilidad
- En puntos críticos (pago recibido, reserva confirmada, cancelación, reembolso) el sistema envía correos o notificaciones.
- Las plantillas y clases de notificación se encuentran en `app/Notifications/`.
- También hay registros en log para seguir eventos importantes (creación de sesión de pago, webhooks, errores de notificación), lo que ayuda a auditar y depurar.

15) Datos guardados en la reserva
- La entidad `ClassBooking` almacena datos como: fecha, hora, usuario, estado (`pending`, `confirmed`, `cancelled`), si está pagada, identificadores de Stripe (`stripe_session_id`, `payment_intent`), enlace de la reunión y marcas de reembolso.
- Puedes ver la definición y auxiliares en `app/Models/ClassBooking.php`.

Estados comunes (simplificado)
- `pending` → reserva creada y pendiente de pago o confirmación administrativa
- `paid` → pago recibido (pero aún pendiente de confirmación del admin)
- `confirmed` → admin confirmó y se envió enlace
- `cancelled` → la reserva fue cancelada (posible `refunded` si se reembolsó)

Notas prácticas y seguridad
- El formulario utiliza reCAPTCHA para evitar envíos automáticos y `@csrf` para proteger contra peticiones externas no autorizadas.
- Las rutas administrativas están protegidas por un middleware `admin` que sólo permite el acceso a usuarios con rol de administrador.
- Los archivos de configuración sensibles (claves de Stripe, reCAPTCHA) están en `.env` y nunca se exponen en el código.

¿Quieres que guarde este texto en `docs/booking-flow.md`? Puedo:
- A) Guardarlo tal cual (archivo nuevo ya creado). 
- B) Añadir referencias de línea a cada archivo mencionado para enviarlo al cliente.
- C) Generar un diagrama visual (SVG) con el mismo contenido.

Dime cuál prefieres y lo dejo listo.