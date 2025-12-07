# Gran Bretania — Plataforma de reservas y traducciones en Laravel

Gran Bretania es una aplicación web desarrollada en **Laravel 12** que permite gestionar clases de inglés mediante reservas online, pagos con Stripe y un sistema de traducciones profesionales. Incluye panel de usuario, panel administrativo, gestión de disponibilidad y envío de correos.

---

## Tecnologías principales

- Laravel 12 (PHP 8.2+)
- MySQL
- Blade + TailwindCSS + Vite
- Stripe Checkout (modo test / producción)
- Laravel Breeze (autenticación)
- SMTP (Ethereal en desarrollo)
- reCAPTCHA v2 (opcional)

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/kelpis/GranBretania1.0.git
cd GranBretania1.0
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias frontend

```bash
npm install
```

---

## Configuración del entorno

Copiar el archivo de entorno:

```bash
cp .env.example .env
```

Generar la clave de la aplicación:

```bash
php artisan key:generate
```

### Configuración base de datos (`.env`)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=granbretania
DB_USERNAME=root
DB_PASSWORD=
```

### Configuración Stripe (modo test)

```
STRIPE_KEY=pk_test_xxxxxx
STRIPE_SECRET=sk_test_xxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxx
```

### Configuración de correo (Ethereal para desarrollo)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.ethereal.email
MAIL_PORT=587
MAIL_USERNAME=xxxxx@ethereal.email
MAIL_PASSWORD=xxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=granbretania@example.com
MAIL_FROM_NAME="Gran Bretania"
```

### reCAPTCHA (opcional)

La aplicación utiliza Google reCAPTCHA v3 para proteger los formularios públicos.

```
RECAPTCHA_SITE_KEY=xxxxxxxxxxxxxxxxxxxx
RECAPTCHA_SECRET_KEY=xxxxxxxxxxxxxxxxxxxx
```

Si no se desea usar reCAPTCHA durante desarrollo, se debe ajustar la validación en las Requests:

```
'g-recaptcha-response' => ['nullable']
```

O bien comentar la regla correspondiente.  
De lo contrario, los formularios no podrán enviarse sin claves válidas.

### APP URL

```
APP_URL=http://localhost:8000
```

---

## Migraciones

```bash
php artisan migrate --seed
```

---

## Ejecución del proyecto

Servidor Laravel:

```bash
php artisan serve
```

Frontend Vite:

```bash
npm run dev
```

Acceso por defecto:

```
http://localhost:8000
```

---

## Compilación para producción

```bash
npm run build
```

---

## Webhooks de Stripe (solo en desarrollo local)

```bash
stripe listen --forward-to http://localhost:8000/api/stripe/webhook
```

---

## Estructura principal del proyecto

```
app/
database/
resources/
routes/
public/
```

---

## Funcionalidades principales

### Usuario
- Reserva de clases con pago online
- Edición/cancelación bajo restricciones
- Solicitud de traducciones con subida de archivos
- Descarga del documento final

### Administrador
- Confirmación de clases y enlace Meet
- Cancelación con reembolso Stripe
- Gestión de disponibilidad
- Gestión de traducciones
- Dashboard con métricas

---

## Despliegue en servidor (resumen)

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

El servidor web debe apuntar a la carpeta `public/`.

---

## Licencia

Proyecto académico — uso educativo.

