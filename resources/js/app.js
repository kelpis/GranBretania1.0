// Punto de entrada principal de JavaScript para la aplicación.
// - Aquí se importan las configuraciones globales (bootstrap), se inicializa Alpine.js
//   y se cargan los módulos específicos de cada vista/funcionalidad.
// - No se cambia la lógica: solo se añaden comentarios explicativos.
//
// Archivo gestionado por Vite: edita módulos en `resources/js/` y usa `npm run dev` o
// `npm run build` para generar los artefactos que se sirven al navegador.

import './bootstrap'; // configuración global (axios, tokens, helpers)

import Alpine from 'alpinejs';

// Inicialización de Alpine.js (micro framework para comportamiento declarativo)
window.Alpine = Alpine;
Alpine.start();

// Theme handling moved to `resources/js/theme.js` (keeps DOM painting free of inline scripts)
import './theme';

// Módulos específicos de la aplicación — cada uno encapsula la lógica de una página
// o conjunto de vistas:
// - `bookings` : lógica para crear reservas (relleno de fechas, carga de horas, validaciones)
// - `user-bookings` : lógica para editar reservas desde el panel de usuario
// - `translation` : scripts para el formulario de solicitud de traducción
// - `admin-translation-calculator` : mini-calculadora usada en el panel admin
// - `opiniones` : manejos de la sección de opiniones/reviews
import './bookings';
import './user-bookings';
import './translation';
import './admin-translation-calculator';
import './opiniones';
