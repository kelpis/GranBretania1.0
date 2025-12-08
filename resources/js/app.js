// Punto de entrada principal de JavaScript para la aplicación.
//Aquí se importan las configuraciones globales (bootstrap), se inicializa Alpine.js
//y se cargan los módulos específicos de cada vista/funcionalidad.



import './bootstrap'; // configuración global (axios, tokens, helpers)

import Alpine from 'alpinejs';

// Inicialización de Alpine.js (micro framework para comportamiento declarativo)
window.Alpine = Alpine;
Alpine.start();


import './theme';

// Módulos específicos de la aplicación — cada uno encapsula la lógica de una página


import './bookings';

import './user-bookings';

import './translation';

import './admin-translation-calculator';

import './opiniones';

