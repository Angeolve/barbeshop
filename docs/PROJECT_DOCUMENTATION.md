# Documentación del proyecto BarberShop

Este documento centraliza la documentación por módulos del proyecto `barbeshop`. Contiene: descripción de archivos clave, explicación de su función, y guías de modificación técnica para mantener y extender el sistema.

Nota: Antes de aplicar cambios funcionales, sigue las guías descritas en cada sección. Los nombres de archivos están con ruta relativa al proyecto.

---

## Índice

- Módulo: Citas
- Módulo: Clientes
- Módulo: Barberos (Staff)
- Módulo: Servicios
- Módulo: Horarios (StaffSchedule)
- Notificaciones (Mail / PDF / Comandos)
- Base de Datos / Seeders / Migraciones
- Rutas y Scheduling
- Archivos de estilo y configuración (Tailwind, CSS)

---

## Módulo: Citas

Archivos clave:

- `app/Http/Controllers/AppointmentController.php`
- `app/Models/Appointment.php`
- `resources/views/appointments/index.blade.php`
- `resources/views/appointments/create.blade.php`
- `resources/views/pdf/appointment_ticket.blade.php`

Descripción y responsabilidades:

- `AppointmentController.php`: controla la lógica de listado (`index`), creación (`create`), almacenamiento (`store`), actualización de estado (`updateStatus`) y cancelación (`cancel`). Valida entradas y compone la fecha/hora para guardarla en la columna `appointment_time`. También intenta enviar el ticket PDF al cliente después de crear la cita.
- `Appointment.php`: modelo Eloquent que representa la tabla de citas. Contiene relaciones con `client` (User), `barber` (User) y `service` (Service).
- `index.blade.php`: vista que lista citas según rol del usuario.
- `create.blade.php`: formulario compartido para crear citas — incluye selección de `service`, `barber`, `client`, y fecha/hora. Integra datos JSON (`barbersData`) para lógica en JS.
- `appointment_ticket.blade.php`: plantilla PDF para adjuntar al email de confirmación.

Guía de modificación técnica (qué tocar y dónde):

- Añadir un campo nuevo (ej.: `notes`):
    1. Migración: crear nueva migración `php artisan make:migration add_notes_to_appointments --table=appointments` y añadir columna `notes`.
    2. Modelo: en `app/Models/Appointment.php` añadir `notes` a la propiedad `$fillable`.
    3. Formulario: en `resources/views/appointments/create.blade.php` añadir el input (ej. `<textarea name="notes">`), conservar clases Tailwind existentes.
    4. Controlador: en `AppointmentController@store` incluir `notes` en el array pasado a `Appointment::create([...])`.
    5. PDF/email: en `resources/views/pdf/appointment_ticket.blade.php` incluir la variable `{{ $appointment->notes }}` donde desees mostrarla.
    6. Validación: actualizar `$rules` en `AppointmentController@store` para validar `notes` si es necesario.

Líneas específicas a revisar (puntos clave en `AppointmentController.php`):

- `create()`: donde se construyen `$barbersData` (línea que mapea horarios) — si cambias la estructura del horario, actualiza este mapeo.
- `store()`: sección de `$rules` y creación `Appointment::create([...])` (líneas donde se define `appointment_time`). Cambia aquí si alteras nombres de inputs.
- Envío de email: bloque `try { ... Mail::to(...)->send(...) } catch (...)` — si pasas a colas, reemplazar `send` por `Mail::to(...)->queue(...)` y haz que el Mailable implemente `ShouldQueue`.

---

## Módulo: Clientes

Archivos clave:

- `app/Http/Controllers/ClientController.php`
- `app/Models/User.php` (usuarios con `role='client'`)
- `resources/views/clients/index.blade.php`
- `resources/views/clients/create.blade.php`
- `resources/views/clients/edit.blade.php`

Descripción:

- `ClientController.php` maneja CRUD básico para clientes, utiliza el modelo `User` filtrando por `role = 'client'`. Implementa soft deletes y restauración mediante `withTrashed()` y `onlyTrashed()`.

Guía de modificación técnica:

- Para añadir un campo al formulario cliente (ej. `birthdate`):
    1. Migración: añadir columna a `users`.
    2. `ClientController@store` y `update`: añadir validaciones y mapear el campo en `User::create([...])` o `$client->update([...])`.
    3. Vistas: `create.blade.php` y `edit.blade.php` agregar el input correspondiente.
    4. Tests/Seeders: actualizar datos de prueba en `database/seeders/DatabaseSeeder.php` si aplica.

Puntos específicos:

- Rutas: `routes/web.php` registra `Route::resource('clients', \App\Http\Controllers\ClientController::class)` — si cambias métodos, asegúrate de que la ruta sigue existiendo o actualiza la resource.

---

## Módulo: Barberos (Staff)

Archivos clave:

- `app/Http/Controllers/StaffController.php`
- `app/Models/User.php` (usuarios `role='staff'`)
- `app/Models/StaffSchedule.php`
- `app/Http/Controllers/StaffScheduleController.php`
- `resources/views/staff/index.blade.php`
- `resources/views/staff/create.blade.php`
- `resources/views/staff/edit.blade.php`

Descripción:

- `StaffController` controla CRUD de barberos.
- `StaffSchedule` contiene columnas de disponibilidad por día y `shift`.
- `StaffScheduleController` expone la edición de horarios.
- En `AppointmentController@create` se auto-crean `StaffSchedule` para barberos que no tengan horario.

Guía de modificación técnica:

- Si cambias estructura de `StaffSchedule` (ej. añadir `start_time`/`end_time`):
    1. Crear migración para las nuevas columnas.
    2. Actualizar `app/Models/StaffSchedule.php` con `$fillable`.
    3. Revisar `AppointmentController@create` donde se asume la presencia de `->shift` y los booleanos `monday`..`sunday`; actualiza el mapeo de `$barbersData`.
    4. Actualizar vistas de horarios en `resources/views/schedules/index.blade.php`.

---

## Módulo: Servicios

Archivos clave:

- `app/Http/Controllers/ServiceController.php`
- `app/Models/Service.php`
- `resources/views/services/index.blade.php`, `create.blade.php`, `edit.blade.php`

Descripción:

- CRUD básico de los servicios que ofrece la barbería (nombre, duración, precio, activo).

Guía de modificación técnica:

- Para cambiar precio a decimal con 2 decimales: revisar migración de `services` (tipo `decimal(8,2)`), validar en `ServiceController` y formatear en vistas usando `number_format($service->price, 2)`.

---

## Notificaciones, Mailables y Comandos (Scheduler)

Archivos clave:

- `app/Mail/AppointmentConfirmedMail.php`
- `app/Mail/AppointmentReminderMail.php`
- `app/Mail/BarberDailyAgendaMail.php`
- `app/Mail/ClientDailyReminderMail.php`
- `resources/views/emails/*` (plantillas de email)
- `resources/views/pdf/*` (plantillas PDF)
- `app/Console/Commands/SendAppointmentReminders.php`
- `app/Console/Commands/SendBarberDailyAgenda.php`
- `app/Console/Commands/SendClientDailyReminder.php`
- `app/Console/Kernel.php`

Descripción:

- Los Mailables generan el email y adjuntan PDFs con `Pdf::loadView(...)` (facade de `barryvdh/laravel-dompdf`).
- Los comandos recorren la base de datos y envían correos (actualmente usando `Mail::to(...)->send(...)`, envíos síncronos).
- `Kernel.php` registra los comandos y los programa (`dailyAt('07:00')`, `dailyAt('10:00')`, etc.).

Guía de modificación técnica:

- Pasar a colas: hacer que los mailables implementen `ShouldQueue` y en lugar de `send` usar `queue`. Configurar `QUEUE_CONNECTION` en `.env` y ejecutar workers `php artisan queue:work`.
- Si cambias plantillas PDF: editar `resources/views/pdf/*.blade.php`. Al cambiar variables, actualizar la llamada `Pdf::loadView('pdf.X', ['data' => ...])` en el Mailable/Command correspondiente.
- Logs: los `try/catch` alrededor de envíos hacen `Log::error()` — revisa `storage/logs/laravel.log` en caso de fallos.

---

### Arquitectura general

- Flujo típico al crear una cita:
    1. `AppointmentController@store` valida y crea la cita.
    2. Se genera un PDF usando `Pdf::loadView('pdf.appointment_ticket', ['appointment' => $appointment])`.
    3. Se instancia el `AppointmentConfirmedMail` y se adjunta el PDF con `attachData`.
    4. Se envía el correo al cliente (`Mail::to(...)->send(...)` o `->queue(...)` si se usa cola).

### Mailables

- Archivos principales:
    - `app/Mail/AppointmentConfirmedMail.php` — email de confirmación con ticket adjunto.
    - `app/Mail/AppointmentReminderMail.php` — recordatorio de cita.
    - `app/Mail/BarberDailyAgendaMail.php` — agenda diaria para barbero.
    - `app/Mail/ClientDailyReminderMail.php` — recordatorio diario para clientes con cita hoy.

- Buenas prácticas al modificar Mailables:
    - Si cambias las variables que la vista espera, actualiza la llamada a `new XMail($data)` y la vista en `resources/views/emails/*`.
    - Para adjuntar PDF:

```php
$pdf = Pdf::loadView('pdf.appointment_ticket', ['appointment' => $appointment])->output();
$mailable = new AppointmentConfirmedMail($appointment);
$mailable->attachData($pdf, "ticket-{$appointment->id}.pdf", ['mime' => 'application/pdf']);
Mail::to($appointment->client->email)->send($mailable);
```

    - Para pasar a cola, que el Mailable implemente `ShouldQueue` y usar `Mail::to(...)->queue($mailable)`.

### Plantillas PDF

- Ubicación: `resources/views/pdf/*.blade.php`.
- Recomendaciones:
    - Mantén las plantillas simples: evita llamadas a helpers costosos dentro de la vista.
    - Si agregas campos al PDF, verifica que el Mailable/Command pase esas variables.
    - Validar el HTML/CSS para DomPDF (no soporta todas las propiedades CSS modernas).

### Comandos de consola (Console Commands)

- Archivos principales:
    - `app/Console/Commands/SendAppointmentReminders.php` — envía recordatorios puntuales.
    - `app/Console/Commands/SendBarberDailyAgenda.php` — construye y envía la agenda diaria a cada barbero.
    - `app/Console/Commands/SendClientDailyReminder.php` — envía recordatorios a clientes con cita el día.

- Consideraciones al modificar comandos:
    - Los comandos iteran modelos y envían Mailables. Envuelve con `try/catch` para no interrumpir la ejecución por un fallo en un email.
    - Para pruebas locales usa `MAIL_MAILER=smtp` configurado con Mailtrap y ejecuta manualmente:

```bash
php artisan send:client-daily-reminder
php artisan send:barber-daily-agenda
```

    - Si introduces consultas pesadas, agrega `chunk()` para procesar en lotes, evitando OOM.

### Scheduling (`app/Console/Kernel.php`)

- Ejemplos de programación actuales:
    - `dailyAt('07:00')` — envíos tempranos (agendas).
    - `dailyAt('10:00')` — recordatorios a clientes.

- Para cambiar la hora: editar `Kernel.php` y desplegar. Siempre ejecutar `php artisan config:clear` si cambias variables en `.env` relacionadas con scheduling.

### Pasar a colas (recomendado)

- Por qué: evita bloqueos y permite reintentos automáticos en fallos.
- Pasos básicos:
    1. Configurar `QUEUE_CONNECTION` en `.env` (ej. `database`, `redis`).
    2. Hacer que los Mailables implementen `ShouldQueue`.
    3. Reemplazar `Mail::to(...)->send($mailable)` por `->queue($mailable)`.
    4. Ejecutar workers: `php artisan queue:work` (o usar supervisor en producción).

### Logs y depuración

- Errores comunes:
    - Plantilla PDF espera variable inexistente (Undefined variable) — revisar la vista y la llamada `loadView`.
    - `Connection refused` o credenciales SMTP — revisar `MAIL_...` en `.env` y probar con Mailtrap.

- Recomendación: tras cambios importantes en mailables o comandos ejecuta manualmente los comandos y revisa `storage/logs/laravel.log`.

## Base de Datos / Seeders / Migraciones

Archivos clave:

- `database/seeders/DatabaseSeeder.php`
- `database/migrations/*` (migraciones generadas, por ejemplo, `create_services_table`, `create_appointments_table`, `create_staff_schedules_table`)

Guía:

- Para actualizar datos de prueba: editar `DatabaseSeeder.php` o crear seeders específicos `php artisan make:seeder ServicesTableSeeder`.
- Recordar ejecutar `php artisan migrate` y `php artisan db:seed` en orden correcto.

---

## Rutas y Scheduling

Archivos clave:

- `routes/web.php`
- `app/Console/Kernel.php`

Descripción:

- `web.php` define `Route::resource(...)` para `clients`, `staff`, `services`, `users`, y rutas específicas para `appointments` y `schedules`.
- `Kernel.php` registra los comandos y contiene la programación (`dailyAt`) para recordatorios y agendas.

Guía:

- Si añades nuevas rutas, considera agruparlas con `Route::middleware(['auth'])->group(...)` si requieren autenticación.
- Para cambiar horario del scheduler: editar `app/Console/Kernel.php` y cambiar `dailyAt('10:00')` por `dailyAt('09:00')` o `cron()` según necesidad.

---

## Archivos de estilo / Tailwind

Archivos clave:

- `resources/css/app.css` (Tailwind import)
- `tailwind.config.js`
- `postcss.config.js`
- `vite.config.js`

Guía:

- Para cambiar variables de color, editar `tailwind.config.js` en la sección `theme.extend.colors`.
- Para cambios rápidos de CSS, editar `resources/css/app.css` y luego compilar con `npm run dev` o `npm run build`.

---

## Recomendaciones generales de mantenimiento

- Convertir envíos de correo a `ShouldQueue` para evitar bloquear requests y comandos programados.
- Añadir tests unitarios/feature para rutas críticas y comandos (usar `php artisan make:test`).
- Mantener `composer dump-autoload` y `php artisan route:clear` como pasos en el deploy si se actualizan clases o rutas.

---

## Próximos pasos propuestos

1. Revisas este documento y confirmas que comenzamos a insertar comentarios inline en los archivos clave.
2. Inserto comentarios en el código (comprobando que no cambio funcionalidad). Haré commits separados por módulo para que revises.
3. Opcional: generar `docs/CHANGELOG.md` y guías de despliegue.

---

Documentación generada automáticamente por el asistente. Si quieres que empiece a insertar los comentarios inline ahora, confirma y comenzaré por el `Módulo de Citas`.
