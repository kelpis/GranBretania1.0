# Pruebas (tests) - Resumen de tests que pasaron

Este documento lista y describe las pruebas (Pest) que se añadieron al proyecto y que han pasado en la ejecución reciente. Incluye la ubicación de los archivos, una breve descripción de cada prueba y los comandos para ejecutar la suite o pruebas concretas.

--

## Comandos útiles

- Ejecutar toda la suite de tests (Pest):

```powershell
php .\vendor\bin\pest
```

- Ejecutar solo los Feature tests:

```powershell
php .\vendor\bin\pest tests/Feature
```

- Ejecutar un solo archivo de prueba (ejemplo `ContactFlowTest`):

```powershell
php .\vendor\bin\pest tests/Feature/features/ContactFlowTest.php
```

--

## Resumen general

Se añadieron y ejecutaron tests en las siguientes categorías:

- Unit/Compatible: validaciones y utilidades pequeñas.
- Feature/Compatible: comprobaciones de entorno y rutas.
- Feature/unity: tests unitarios adaptados a Pest/Laravel helpers.
- Feature/features (integración): flujos completos (creación de usuario, formulario de contacto con recaptcha falso).

## Tests añadidos y ubicación

- **Unit/Compatible** (unitarios):
  - `tests/Unit/Compatible/EmailValidationTest.php` — validación de formatos de email y casos límite.
  - `tests/Unit/Compatible/PasswordHashTest.php` — verifica que el hash de la contraseña produce el resultado esperado y que `Hash::check` funciona.
  - `tests/Unit/Compatible/NameFormattingTest.php` — utilidades de formateo de nombre (`app/Utils/StringUtils.php`).
  - `tests/Unit/Compatible/UtilityFunctionTest.php` — pruebas a funciones utilitarias añadidas.
  - `tests/Unit/Compatible/ModelFillableTest.php` — comprueba los `fillable` de modelos importantes (ej. `User`, `ContactMessage`).

- **Feature/Compatible** (comprobaciones entorno):
  - `tests/Feature/Compatible/PHPVersionTest.php` — chequeo de versión mínima de PHP requerida.
  - `tests/Feature/Compatible/ExtensionsTest.php` — verifica extensiones PHP necesarias.
  - `tests/Feature/Compatible/ComposerPackagesTest.php` — verifica paquetes clave presentes en `composer.lock`.
  - `tests/Feature/Compatible/ArtisanCommandsTest.php` — test rápido de comandos artisan esenciales.

- **Feature/unity** (tests adaptados a Pest/Laravel):
  - `tests/Feature/unity/EmailValidationTest.php` — versión Pest/Laravel del test de email.
  - `tests/Feature/unity/PasswordHashTest.php` — version Pest del test de hash.
  - `tests/Feature/unity/UtilityFunctionTest.php` — utilidades en contexto de framework.
  - `tests/Feature/unity/ModelFillableTest.php` — pruebas sobre atributos fillable en modelos.

- **Feature/features** (integración / flujos):
  - `tests/Feature/features/CreateUserTest.php` — crea un usuario mediante factory y comprueba la existencia en BD.
  - `tests/Feature/features/ContactFormViewTest.php` — GET del formulario de contacto y comprobación de campos en la vista.
  - `tests/Feature/features/ContactFlowTest.php` — POST al endpoint `route('contact.store')` con:
    - `Notification::fake()` para evitar envíos reales.
    - `Http::fake()` para simular una respuesta exitosa de reCAPTCHA (no hay llamadas externas en el test).
    - `RefreshDatabase` para aislar el estado de la BD.
    - Comprueba que la sesión contiene la clave `ok` y que `ContactMessage` fue persistido.

--

## Notas importantes y consideraciones

- La prueba `ContactFlowTest` simula la verificación de reCAPTCHA usando `Http::fake()` para `www.google.com/*`. Si en el futuro se cambia la URL objetivo del `Recaptcha` rule, actualiza el patrón en la prueba.
- Las pruebas que comprueban la base de datos utilizan `RefreshDatabase` para garantizar un estado limpio.
- Varias pruebas usan el helper de Pest; si prefieres usar los métodos de la clase (`$this->post()`, `$this->get()`), podríamos modificar las pruebas y ajustar el analizador estático. Actualmente alguna prueba usa `app()->handle()` para evitar alertas del analizador.

## Siguientes pasos recomendados

- Añadir aserciones adicionales en `ContactFlowTest` para verificar que se envió una notificación concreta (`Notification::assertSent(...)`).
- Añadir pruebas E2E (Playwright o Dusk) si quieres comprobar interacciones en navegador real; requiere añadir dependencias de Node o configurar Dusk.
- Ejecutar `npm run build` y `php artisan test` en CI para validar integraciones entre assets y tests.

Si quieres, hago alguno de los siguientes ahora: añadir `Notification::assertSent()` al test de contacto, ejecutar la suite aquí y pegar la salida, o integrar E2E con Playwright (necesitará instalar dependencias). Indica qué prefieres.

Fin de documento
