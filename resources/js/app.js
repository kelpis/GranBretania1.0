// Punto de entrada principal de JavaScript para la aplicación.
// - Aquí se importan las configuraciones globales (bootstrap), se inicializa Alpine.js
//   y se cargan los módulos específicos de cada vista/funcionalidad.



import './bootstrap'; // configuración global (axios, tokens, helpers)

import Alpine from 'alpinejs';

// Inicialización de Alpine.js (micro framework para comportamiento declarativo)
window.Alpine = Alpine;
Alpine.start();


import './theme';

// Módulos específicos de la aplicación — cada uno encapsula la lógica de una página
// o conjunto de vistas:


// bookings` : lógica para crear reservas (relleno de fechas, carga de horas, validaciones)
import './bookings';
// user-bookings` : lógica para editar reservas desde el panel de usuario
import './user-bookings';
// translation` : scripts para el formulario de solicitud de traducción
import './translation';
// admin-translation-calculator` : mini-calculadora usada en el panel admin
import './admin-translation-calculator';
// opiniones` : manejos de la sección de opiniones/reviews
import './opiniones';
