# 💈 BarberShop Management System

Un sistema premium de gestión y automatización de flujos de trabajo desarrollado para barberías modernas. Esta aplicación web permite administrar barberos, clientes, servicios, citas y horarios de atención, integrando un motor de automatización en segundo plano para el envío de notificaciones y reportes analíticos diarios.

## 📋 Requisitos Técnicos Mínimos Cumplidos

El proyecto ha sido desarrollado bajo estrictos estándares de ingeniería de software, cumpliendo y superando la totalidad de los requisitos técnicos exigidos:

- Autenticación y Gestión de Roles: Implementación de control de acceso jerárquico y protección de rutas mediante middleware para tres roles diferenciados (Admin, Staff/Barber, Client).

- Integración Documental Pro: Generación automatizada de comprobantes de citas (Ticket) y hojas de ruta diarias (Agenda) en formato PDF con un diseño visual corporativo de alto estándar.

- Notificaciones en Tiempo Real: Integración con servicios SMTP / Mailtrap para el disparo de correos electrónicos automáticos basados en eventos del sistema (ej. confirmación inmediata al agendar).

- Automatización (Task Scheduling): Configuración de tareas programadas (Cron Jobs) en el Kernel del framework que se ejecutan de manera autónoma a las 10:00 AM para procesar recordatorios y despachar agendas.

- Lógica de Negocio Profesional:
    - Uso de Soft Deletes (Borrado Lógico) en el módulo principal para preservar la integridad del historial operativo ante eliminaciones accidentales.

    - Esquema de validaciones estrictas en el Backend para asegurar la consistencia y sanitización de los datos.

## 🏗️ Arquitectura del Sistema y Módulos

El ecosistema de la aplicación se encuentra segmentado de manera modular para garantizar la escalabilidad:

1. Base de Datos y Persistencia
    - Migraciones: Estructura de tablas relacionales completamente normalizada con restricciones de integridad referencial, índices operativos y columnas de rastreo temporal (timestamps, deleted_at).

    - Seeders y Factories: Scripts de automatización listos para poblar la base de datos con registros de prueba consistentes (usuarios, servicios, barberos y citas iniciales).

2. Módulo de Usuarios y Roles
    - Control absoluto de accesos. Las interfaces administrativas (como la gestión global de usuarios) se aíslan completamente de los clientes a través de la directiva @if (Auth::user()->role === 'admin') y políticas de seguridad del lado del servidor.

3. Módulo de Barberos y Horarios (Staff)
    - Registro de personal técnico, asignación de especialidades y parametrización de sus jornadas laborales semanales (horas de entrada, salida y días de descanso).

4. Módulo de Servicios y Catálogo
    - Gestión CRUD completa para los servicios ofrecidos (cortes de cabello, perfilado de barba, tratamientos premium), incluyendo control de precios, duraciones estimadas y estados activos.

5. Módulo de Citas y Reservaciones (Appointments)
    - El núcleo transaccional de la aplicación. Cuenta con lógica de redirección dinámica basada en el rol del usuario conectado (client_index vs index), control de estados de la cita (Pendiente, Confirmada, Completada, Cancelada) y validación de colisiones horarias.

## 🎨 Guía de Estilos de la Interfaz

La aplicación implementa un tema visual Soft Luxury Dark Gray & Gold, diseñado específicamente para evocar una experiencia premium de barbería clásica/moderna:

- Paleta de Colores: Fondo de interfaz oscuro profundo (#18181b, #121214), bordes de alta definición en oro atenuado (border-amber-500/10) y acentos dinámicos en degradado oro-naranja (from-amber-500 to-orange-500).

- Componentes Reactivos: Menú lateral (Sidebar) responsivo que se transforma en un encabezado compacto con menú desplegable en dispositivos móviles a través de AlpineJS.

- Consistencia Visual: Uso extensivo de Tailwind CSS enfocado en la usabilidad, tipografías con espaciado expandido (tracking-wider, tracking-widest) y retroalimentación de estado mediante variaciones cromáticas.

## 🤖 Automatización y Flujos de Trabajo (Backend)

El sistema opera de forma autónoma mediante tareas en segundo plano programadas en `app/Console/Kernel.php`:

PHP

```php
// Ejemplo conceptual del flujo de automatización programado a las 10:00 AM
$schedule->command('barbershop:send-daily-reminders')->dailyAt('10:00');
$schedule->command('barbershop:dispatch-barber-agendas')->dailyAt('10:00');
```

Evento de Registro (Sincrónico): Al guardar una nueva cita en `AppointmentController`, el sistema despacha inmediatamente el Mailable `AppointmentConfirmedMail` al cliente e imprime su ticket en PDF.

Ciclo Diario (Asincrónico): Todos los días a las 10:00 AM, el servidor despierta los comandos de automatización:

- Recopila las citas del día siguiente y envía el correo `AppointmentReminderMail` a cada cliente afectado.

- Compila la hoja de ruta en PDF de cada barbero con sus bloques horarios del día y se la envía adjunta mediante el Mailable `BarberDailyAgendaMail`.

## 🚀 Instrucciones de Instalación y Despliegue

Sigue estos pasos para clonar y ejecutar el proyecto localmente en tu entorno de desarrollo:

1. Clonar el repositorio
   Bash

```bash
git clone https://github.com/tu-usuario/barbershop-management.git
cd barbershop-management
```

2. Instalar dependencias del backend y frontend
   Bash

```bash
composer install
npm install && npm run dev
```

3. Configurar el entorno
   Duplica el archivo de configuración base y edita las variables de conexión a la base de datos y tu servidor SMTP (Mailtrap):

Bash

```bash
cp .env.example .env
php artisan key:generate
```

Ajusta las credenciales en tu archivo .env:

Fragmento de código

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
```

4. Ejecutar Migraciones y Seeders

```bash
php artisan migrate --seed
```

5. Levantar el servidor de desarrollo

```bash
php artisan serve
```

6. Probar las Tareas Programadas (Opcional)
   Para simular de forma manual la ejecución del planificador de tareas y forzar el envío de las agendas y recordatorios de las 10:00 AM sin esperar el ciclo del servidor, ejecuta:

```bash
php artisan schedule:run
```

## 📁 Estructura de Archivos Clave del Proyecto

Para facilitar el mantenimiento técnico, ubica los componentes principales en las siguientes rutas:

- Controladores Principales: `app/Http/Controllers/` (AppointmentController.php, StaffController.php, ClientController.php)

- Comandos de Automatización: `app/Console/Commands/`

- Mailables (Correos): `app/Mail/` (AppointmentConfirmedMail.php, AppointmentReminderMail.php, BarberDailyAgendaMail.php, ClientDailyReminderMail.php)

- Modelos y SoftDeletes: `app/Models/` (Appointment.php, Service.php)

- Vistas del Sistema (Blade): `resources/views/`

- Modulo Citas: `appointments/index.blade.php`, `create.blade.php`, `edit.blade.php`

- Plantillas de PDF: `pdf/appointment_ticket.blade.php`, `pdf/barber_agenda.blade.php`

- Menú de Navegación Principal: `resources/views/layouts/sidebar.blade.php`
  <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
