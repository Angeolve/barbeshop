<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientDailyReminderMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendClientDailyReminder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'clients:daily-reminder';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Enviar recordatorio en PDF a clientes que tienen cita hoy';

  /**
   * Comando: clients:daily-reminder
   * - Agrupa las citas por cliente y envía un PDF con el comprobante.
   * - Si cambias la plantilla del PDF (`pdf.appointment_ticket`) asegúrate
   *   de que la vista siga recibiendo la variable `appointment`.
   * - Para cargas grandes: agrupar con `chunk()` y usar colas para envío.
   */

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $today = Carbon::today()->toDateString();

    $appointments = Appointment::with(['client', 'service', 'barber'])
      ->whereDate('appointment_time', $today)
      ->where('status', 'scheduled')
      ->orderBy('appointment_time', 'asc')
      ->get();

    // Agrupar por cliente
    $grouped = $appointments->groupBy('client_id');

    foreach ($grouped as $clientId => $clientAppointments) {
      // Tomar la primera cita del día para este cliente como referencia
      $appointment = $clientAppointments->first();
      $client = $appointment->client;

      if (!$client || !$client->email) {
        continue;
      }

      try {
        // Generar PDF usando la vista existente de ticket (usa $appointment)
        $pdf = Pdf::loadView('pdf.appointment_ticket', ['appointment' => $appointment])
          ->setPaper('a4', 'portrait');

        $pdfData = $pdf->output();

        Mail::to($client->email)->send(new ClientDailyReminderMail($client, $pdfData, $today, $appointment));
      } catch (\Exception $e) {
        Log::error('Error sending client daily reminder to ' . ($client->email ?? 'unknown') . ': ' . $e->getMessage());
      }
    }

    $this->info('Client daily reminders processed: ' . $grouped->count());
    return 0;
  }
}
