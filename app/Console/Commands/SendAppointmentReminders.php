<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminderMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'appointments:reminders';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Enviar recordatorios de cita para las citas de mañana';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $tomorrow = Carbon::tomorrow()->toDateString();

    $appointments = Appointment::with(['client', 'service', 'barber'])
      ->whereDate('appointment_time', $tomorrow)
      ->where('status', 'scheduled')
      ->get();

    foreach ($appointments as $appointment) {
      try {
        if ($appointment->client && $appointment->client->email) {
          Mail::to($appointment->client->email)->send(new AppointmentReminderMail($appointment));
        }
      } catch (\Exception $e) {
        Log::error('Error sending appointment reminder: ' . $e->getMessage());
      }
    }

    $this->info('Appointment reminders sent: ' . $appointments->count());
    return 0;
  }
}
