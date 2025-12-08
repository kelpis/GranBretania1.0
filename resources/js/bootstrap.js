
// Configura Axios para solicitudes HTTP, incluyendo headers comunes para CSRF y AJAX.

// Importa Axios y lo hace disponible globalmente.
import axios from 'axios';
window.axios = axios;

// Establece headers comunes para todas las solicitudes Axios.
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
