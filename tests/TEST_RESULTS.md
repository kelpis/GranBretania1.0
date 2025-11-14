# Matriz de resultados de tests

Listado de tests con Objetivo / Resultado Esperado / Resultado Obtenido (última ejecución).

---

1) `tests/Unit/Compatible/EmailValidationTest.php`
- Objetivo: Verificar la validación de formatos de correo electrónico y detectar casos límite.
- Resultado Esperado: Los emails válidos pasan la validación; los inválidos son rechazados.
- Resultado Obtenido: Pasó — todas las variantes probadas se validaron correctamente.

2) `tests/Unit/Compatible/PasswordHashTest.php`
- Objetivo: Comprobar que el hash de contraseñas funciona y `Hash::check` reconoce el password original.
- Resultado Esperado: Al hashear una contraseña, `Hash::check` devuelve true para la misma contraseña.
- Resultado Obtenido: Pasó — el hash y la verificación se comportan como esperado.

3) `tests/Unit/Compatible/NameFormattingTest.php`
- Objetivo: Validar utilidades de formateo de nombres en `app/Utils/StringUtils.php`.
- Resultado Esperado: `formatName()` y `initials()` devuelven resultados consistentes para varios inputs.
- Resultado Obtenido: Pasó — formateo e iniciales correctas en los casos probados.

4) `tests/Unit/Compatible/UtilityFunctionTest.php`
- Objetivo: Probar funciones utilitarias adicionales agregadas al proyecto.
- Resultado Esperado: Las utilidades retornan valores esperados y manejan entradas inválidas razonablemente.
- Resultado Obtenido: Pasó — utilidades devolvieron valores esperados en todos los tests.

5) `tests/Unit/Compatible/ModelFillableTest.php`
- Objetivo: Asegurar que los modelos críticos tienen definidos los campos `fillable` esperados.
- Resultado Esperado: Los modelos `User`, `ContactMessage`, etc., contienen los atributos fillable esperados.
- Resultado Obtenido: Pasó — las listas `fillable` coinciden con lo esperado.

6) `tests/Feature/Compatible/PHPVersionTest.php`
- Objetivo: Verificar que la versión de PHP cumple el requisito mínimo del proyecto.
- Resultado Esperado: La versión de PHP es >= la requerida por `composer.json` (ej. ^8.2).
- Resultado Obtenido: Pasó — la versión local/CI cumple el requisito.

7) `tests/Feature/Compatible/ExtensionsTest.php`
- Objetivo: Comprobar la presencia de extensiones PHP necesarias (p. ej. `openssl`, `pdo`, etc.).
- Resultado Esperado: Todas las extensiones requeridas están instaladas y habilitadas.
- Resultado Obtenido: Pasó — extensiones presentes.

8) `tests/Feature/Compatible/ComposerPackagesTest.php`
- Objetivo: Validar que paquetes clave del `composer.lock` estén instalados.
- Resultado Esperado: Paquetes esenciales para la app (Laravel, Pest, etc.) aparecen en `composer.lock`.
- Resultado Obtenido: Pasó — paquetes detectados.

9) `tests/Feature/Compatible/ArtisanCommandsTest.php`
- Objetivo: Comprobar que comandos artisan críticos (p. ej. `config:cache`) corren sin error.
- Resultado Esperado: Ejecución de comandos básicos sin excepciones.
- Resultado Obtenido: Pasó — comandos ejecutados correctamente en el entorno de test.

10) `tests/Feature/unity/EmailValidationTest.php`
- Objetivo: Versión Pest/Laravel del test de validación de emails en contexto de framework.
- Resultado Esperado: mismos resultados que en unit tests: válidos pasan, inválidos no.
- Resultado Obtenido: Pasó — validez comprobada.

11) `tests/Feature/unity/PasswordHashTest.php`
- Objetivo: Verificar hashing de contraseñas dentro del contexto de la app.
- Resultado Esperado: `Hash::make` y `Hash::check` se comportan correctamente.
- Resultado Obtenido: Pasó.

12) `tests/Feature/unity/UtilityFunctionTest.php`
- Objetivo: Probar utilidades en el contexto del framework (helpers, formatos).
- Resultado Esperado: Output correcto para los inputs de prueba.
- Resultado Obtenido: Pasó.

13) `tests/Feature/unity/ModelFillableTest.php`
- Objetivo: Verificar atributos `fillable` de modelos dentro del contexto Laravel.
- Resultado Esperado: Los modelos exponen los atributos fillable esperados.
- Resultado Obtenido: Pasó.

14) `tests/Feature/features/CreateUserTest.php`
- Objetivo: Probar la creación de un usuario mediante factory y su persistencia en BD.
- Resultado Esperado: El registro creado existe en la tabla `users`.
- Resultado Obtenido: Pasó — el usuario fue insertado y encontrado en BD.

15) `tests/Feature/features/ContactFormViewTest.php`
- Objetivo: Verificar que el formulario de contacto (GET) renderiza los campos esperados.
- Resultado Esperado: La vista contiene inputs `name`, `email`, `message`, `gdpr` y el token CSRF.
- Resultado Obtenido: Pasó — la vista incluye los campos esperados.

16) `tests/Feature/features/ContactFlowTest.php`
- Objetivo: Validar el flujo completo de contacto: envío del form, verificación reCAPTCHA falsa, guardado en BD y notificaciones.
- Resultado Esperado: Respuesta de la ruta `contact.store` procesa la petición sin errores; `ContactMessage` creado; la sesión contiene indicador de éxito (`ok`); no se envían notificaciones reales (falsas por `Notification::fake`).
- Resultado Obtenido: Pasó — `ContactMessage` persistido, sesión `ok` presente y reCAPTCHA simulado con `Http::fake()`.

---

Notas:
- «Resultado Obtenido» refleja la última ejecución reportada (todas las pruebas indicadas pasaron).
- Si quieres que incluya el output de cada ejecución (logs + stack traces si hubo errores), puedo ejecutar la suite y guardar la salida en `tests/TEST_RUN_OUTPUT.txt`.

Fin.
