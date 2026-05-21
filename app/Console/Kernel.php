<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    \App\Console\Commands\SendAppointmentReminders::class,
    \App\Console\Commands\SendBarberDailyAgenda::class,
    \App\Console\Commands\SendClientDailyReminder::class,
  ];

  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule)
  {
    // Recordatorios a las 10:00 cada día
    $schedule->command('appointments:reminders')->dailyAt('10:00');

    // Agenda diaria para barberos a las 07:00 cada día
    $schedule->command('barbers:daily-agenda')->dailyAt('07:00');
    // Recordatorio diario para clientes con cita hoy a las 10:00
    $schedule->command('clients:daily-reminder')->dailyAt('10:00');
  }

  /**
   * Register the commands for the application.
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
