# Resumen de controladores (excluyendo `Auth`)

Este documento lista los controladores presentes en `app/Http/Controllers` (excluyendo la carpeta `Auth`) y muestra una breve descripción y los métodos públicos principales de cada uno.

---

## `ClassBookingController`
- **Ruta:** `app/Http/Controllers/ClassBookingController.php`
- **Descripción:** Gestiona el flujo público de reservas de clase: formulario, validación, creación de reserva y creación de sesión de pago (Stripe). También expone endpoints para comprobar disponibilidad y para unirse a la videollamada.
- **Métodos públicos:**
  - `create()` — Muestra el formulario de reserva.
  - `store(StoreClassBookingRequest $request)` — Valida y crea una reserva; crea sesión de Stripe Checkout y redirige al pago.
  - `success()` — Vista de éxito tras iniciar reserva/pago.
  - `availability(Request $request)` — Devuelve JSON con horas disponibles para una fecha.
  - `join(Request $request, ClassBooking $booking)` — Permite unirse a la videollamada (firma o propietario/admin).

---

## `BookingAdminController`
- **Ruta:** `app/Http/Controllers/BookingAdminController.php`
- **Descripción:** Panel administrativo para gestionar reservas: ver pendientes/confirmadas/canceladas, confirmar y cancelar reservas; enviar notificaciones al usuario.
- **Métodos públicos:**
  - `index()` — Listado de reservas en distintas categorías para el dashboard admin.
  - `confirm(ClassBooking $booking)` — Marca reserva como `confirmed`, opcionalmente guarda `meeting_url` y notifica.
  - `cancel(ClassBooking $booking)` — Marca reserva como `cancelled` y notifica al usuario.

---

## `AvailabilityAdminController`
- **Ruta:** `app/Http/Controllers/AvailabilityAdminController.php`
- **Descripción:** Gestión de franjas horarias disponibles y bloqueadas para reservas; creación individual y en lote, borrado y toggle.
- **Métodos públicos:**
  - `index()` — Lista paginada de `AvailabilitySlot`.
  - `store(ManageSlotRequest $request)` — Crea o actualiza una franja (upsert) con validaciones.
  - `generate(Request $request)` — Generador en lote de franjas (por rango de fechas/horas).
  - `toggle(AvailabilitySlot $slot)` — Cambia rápidamente entre `available`/`blocked`.
  - `destroy(AvailabilitySlot $slot)` — Elimina una franja (si no hay conflicto con reservas confirmadas).

---

## `UserBookingController`
- **Ruta:** `app/Http/Controllers/UserBookingController.php`
- **Descripción:** Acciones disponibles para usuarios sobre sus propias reservas: listado, edición, cancelación, y vistas de éxito.
- **Métodos públicos:**
  - `__construct()` — Aplica middleware `auth`.
  - `index()` — Lista reservas próximas e históricas del usuario y traducciones vinculadas.
  - `edit(ClassBooking $booking)` — Muestra formulario de edición (si le pertenece).
  - `update(Request $request, ClassBooking $booking)` — Actualiza la reserva con reglas (evita colisiones, límite de ediciones, checks de tiempo).
  - `editSuccess()` — Vista de éxito tras editar.
  - `destroy(ClassBooking $booking)` — Cancela la reserva; intenta reembolso si procede.
  - `authorizeBooking(ClassBooking $booking)` — Método protegido para comprobar propiedad (helper interno, `protected`).

---

## `StripeWebhookController`
- **Ruta:** `app/Http/Controllers/StripeWebhookController.php`
- **Descripción:** Punto de entrada para webhooks de Stripe; valida firma y procesa eventos (`checkout.session.completed`, `payment_intent.succeeded`, etc.) para marcar pagos de reservas y traducciones y enviar notificaciones.
- **Métodos públicos:**
  - `handle(Request $request)` — Verifica firma y procesa distintos tipos de evento de Stripe; marca `paid`, guarda `payment_intent`, notifica a usuario y admin.

---

## `TranslationRequestController`
- **Ruta:** `app/Http/Controllers/TranslationRequestController.php`
- **Descripción:** Flujo público para enviar una solicitud de traducción: mostrar formulario, validar, almacenar archivo y crear el registro.
- **Métodos públicos:**
  - `create()` — Muestra el formulario de solicitud de traducción.
  - `store(StoreTranslationRequest $request)` — Valida, guarda el archivo y crea `TranslationRequest`; notifica a usuario y admin.

---

## `AdminTranslationController`
- **Ruta:** `app/Http/Controllers/AdminTranslationController.php`
- **Descripción:** Acciones de administrador sobre traducciones: generar presupuesto / enlace de pago, marcar entregada y subir el archivo final.
- **Métodos públicos:**
  - `quote(Request $request, TranslationRequest $translation)` — Guarda precio final, crea sesión Stripe Checkout y envía enlace de pago.
  - `deliver(Request $request, TranslationRequest $translation)` — Sube el archivo traducido, marca `delivered` y notifica al usuario.

---

## `ContactController`
- **Ruta:** `app/Http/Controllers/ContactController.php`
- **Descripción:** Muestra y procesa el formulario de contacto público; guarda el mensaje y notifica al usuario y al admin.
- **Métodos públicos:**
  - `create()` — Muestra el formulario de contacto.
  - `store(StoreContactRequest $request)` — Valida y persiste el mensaje; envía notificaciones.

---

## `ProfileController`
- **Ruta:** `app/Http/Controllers/ProfileController.php`
- **Descripción:** Gestión del perfil del usuario: editar, actualizar datos y eliminar cuenta.
- **Métodos públicos:**
  - `edit(Request $request): View` — Muestra formulario para editar perfil.
  - `update(ProfileUpdateRequest $request): RedirectResponse` — Actualiza datos del usuario.
  - `destroy(Request $request): RedirectResponse` — Elimina la cuenta del usuario tras confirmar contraseña.

---

## `AdminController`
- **Ruta:** `app/Http/Controllers/AdminController.php`
- **Descripción:** Panel administrativo general: estadísticas, listados rápidos y acciones administrativas como realizar reembolsos (Stripe).
- **Métodos públicos:**
  - `index()` — Genera estadísticas y listados para el dashboard admin.
  - `refund(ClassBooking $booking)` — Procesa reembolsos vía Stripe y actualiza la reserva.

---

## `Controller` (base)
- **Ruta:** `app/Http/Controllers/Controller.php`
- **Descripción:** Clase base que extiende `Illuminate` y aporta traits comunes (`AuthorizesRequests`, `DispatchesJobs`, `ValidatesRequests`). No contiene rutas de negocio propias.

---

Si quieres, puedo:

- A) Añadir a cada entrada un extracto corto (3–5 líneas) con el cuerpo de cada método público más relevante.  
- B) Generar enlaces directos en formato `vscode://file/...` para abrir cada archivo desde VS Code.  
- C) Crear una versión en HTML con navegación entre controladores.

Dime qué opción prefieres y lo ajusto (o lo dejo tal cual).