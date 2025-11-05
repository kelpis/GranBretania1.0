import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// App-specific modules
import './bookings';
import './user-bookings';
import './translation';
import './admin-translation-calculator';
