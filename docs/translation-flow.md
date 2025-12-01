# Flujo de una solicitud de traducción (resumen sencillo)

Este documento explica, de forma clara y sin tecnicismos, qué ocurre desde que un usuario solicita una traducción hasta que la recibe.

1) Página y formulario
- El usuario abre la página de solicitud en `/traduccion`.
- Se muestra el formulario en `resources/views/user/translations/create.blade.php`.
- El formulario pide: nombre, email, idioma origen, idioma destino, archivo a traducir, urgencia, observaciones y aceptación de la política (GDPR). Incluye reCAPTCHA.

2) Envío y verificación
- Al enviar, el navegador manda el formulario al servidor (ruta `translation.store`).
- En el servidor se valida que el archivo tenga un formato y tamaño permitidos, que los idiomas sean distintos, que el reCAPTCHA sea válido y que el usuario esté autenticado.
  - La validación está en `app/Http/Requests/StoreTranslationRequest.php`.

3) Guardar la solicitud y el archivo
- Si todo está correcto, el servidor guarda el archivo en el almacenamiento (`storage/app/translations`) y crea un registro en la base de datos (`TranslationRequest`).
  - El código que hace esto está en `app/Http/Controllers/TranslationRequestController.php` (método `store`).
- También se registra quién lo pidió (`user_id` cuando el usuario está loggeado) y el consentimiento GDPR.

4) Notificaciones iniciales
- Tras crear la solicitud, el sistema envía un email de confirmación al usuario (`TranslationReceived`) y una alerta al administrador (`TranslationAdminAlert`).
  - Las notificaciones viven en `app/Notifications/`.
- Si el envío de emails falla, no se bloquea la creación de la solicitud (el usuario ve el mensaje de confirmación en pantalla igualmente).

5) Revisión por el administrador y presupuesto
- El administrador ve la solicitud en el panel de admin (`/admin/traducciones`).
- El admin calcula un precio y plazo, y guarda el presupuesto en el registro de la solicitud.
  - Esta acción la realiza `AdminTranslationController@quote`.
- El sistema puede generar un enlace de pago (Stripe Checkout) y enviarlo al usuario (`TranslationPaymentLink`).

6) Pago y confirmación
- Cuando el usuario paga, Stripe envía un aviso (webhook) al servidor.
- El webhook actualiza la solicitud: marca fecha de pago, guarda datos del pago y cambia el estado a "pagada".
  - El controlador que procesa esto es `app/Http/Controllers/StripeWebhookController.php`.

7) Entrega de la traducción
- El traductor o el admin sube el archivo final al sistema (entrega). El registro se actualiza con la ruta del archivo final y la fecha de entrega.
  - Esto lo hace `AdminTranslationController@deliver`.
- Se notifica al usuario con un email (`TranslationDelivered`) y puede descargar su traducción desde su zona privada.

8) Descarga y historial
- El usuario puede ver todas sus solicitudes en `/mis-traducciones` y descargar el archivo entregado desde `/mis-traducciones/{id}/archivo`.
- Si no hay archivo final, se permite descargar el archivo que originalmente subió el usuario.

Estados comunes del proceso (simplificado)
- `created` (solicitud recibida)
- `quoted` (presupuesto enviado)
- `paid` (pago confirmado)
- `delivered` (traducción entregada)

Notas prácticas
- Los ficheros subidos y entregados se guardan en el disco local del servidor (carpeta `storage/app/...`).
- El reCAPTCHA protege el formulario contra envíos automatizados.
- Si quieres que haga un diagrama visual (png/svg) o que lo deje en otro formato (p. ej. PDF), puedo generarlo.

---
Archivo creado: `docs/translation-flow.md`

## Correcciones y matices (resumen breve)

He revisado el código y añado aquí tres matices importantes, explicados de forma simple:

- **No existe un `StripeController` independiente.**
  - En el código las sesiones de pago (Stripe Checkout) se crean desde los controladores que realizan la acción (por ejemplo `ClassBookingController` para reservas o `AdminTranslationController` para presupuestos de traducción). El punto que recibe las notificaciones de Stripe y las procesa es `StripeWebhookController`.

- **Rate limiting (throttling) existe pero no es global.**
  - Hay rutas con limitación de peticiones (por ejemplo el envío de solicitudes de traducción tiene `throttle:5,1`), pero no todas las rutas del proyecto están protegidas por throttling de forma automática. Es correcto decir que se usa throttling en puntos sensibles, no que todas las rutas lo tengan.

- **Corrección sobre correos: "Notifications" vs "Mailables".**
  - El proyecto utiliza clases en `app/Notifications/` para enviar emails y avisos (Notifications). Puede haber Mailables en otros proyectos, pero aquí las notificaciones están centralizadas en `app/Notifications`.

Si quieres, incorporo estas notas directamente en el cuerpo del documento (reemplazando las frases originales) o genero una versión lista para enviar al cliente con marcas "Correcto / Parcial / Corregir" junto a cada afirmación. ¿Qué prefieres?
