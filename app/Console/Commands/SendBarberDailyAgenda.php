<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\BarberDailyAgendaMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBarberDailyAgenda extends Command
{
  protected $signature = 'barbers:daily-agenda';
  protected $description = 'Enviar agenda diaria en PDF a cada barbero con las citas del día';

  /**
   * Comando: barbers:daily-agenda
   * - Extrae citas del día y genera un PDF por barbero con su agenda.
   * - Puntos a revisar si editas:
   *   - La vista `pdf.barber_agenda` debe aceptar `barber` y `appointments`.
   *   - Se pasa `$barberAppointments` al Mailable como 4º parámetro.
   *   - Para escalabilidad usa `chunkById()` y `queue()` para envíos.
   */

  public function handle()
  {
    $today = Carbon::today()->toDateString();
    $this->info("Buscando citas para la fecha de hoy: {$today}...");

    // 1. Traemos las citas de hoy con sus relaciones directas (Esto NO falla)
    $appointmentsToday = Appointment::with(['client', 'service', 'staff'])
      ->whereDate('appointment_time', $today)
      ->get();

    if ($appointmentsToday->isEmpty()) {
      $this->warn("No se encontraron citas registradas para el día de hoy ({$today}).");
      return 0;
    }

    // 2. Obtenemos los IDs únicos de los barberos (staff_id) que sí tienen trabajo hoy
    $barberIds = $appointmentsToday->pluck('staff_id')->unique()->filter();

    $this->info("Se encontraron " . $barberIds->count() . " barberos con agenda hoy. Procesando...");

    foreach ($barberIds as $staffId) {
      $barber = User::find($staffId);

      if (!$barber) {
        $this->warn("No se encontró el usuario con ID: {$staffId}. Saltando...");
        continue;
      }

      if (!$barber->email) {
        $this->error("El barbero {$barber->name} no tiene correo configurado. Saltando...");
        continue;
      }

      // 3. Filtramos las citas de hoy que le pertenecen a este barbero en específico
      $barberAppointments = $appointmentsToday->where('staff_id', $staffId)->sortBy('appointment_time');

      try {
        $this->info("Generando PDF para: {$barber->name}...");

        // Generamos el PDF
        $pdf = Pdf::loadView('pdf.barber_agenda', [
          'barber' => $barber,
          'appointments' => $barberAppointments
        ])->setPaper('a4', 'portrait');

        $this->info("Enviando reporte a Mailtrap ({$barber->email})...");

        // CORRECCIÓN: Agregamos '$barberAppointments' como 4to parámetro
        Mail::to($barber->email)->send(new BarberDailyAgendaMail($barber, $pdf->output(), $today, $barberAppointments));

        $this->info("✅ Agenda enviada con éxito al barbero: {$barber->name}");

      } catch (\Exception $e) {
        $this->error("❌ Error al procesar la agenda de {$barber->name}: " . $e->getMessage());
        Log::error("Error en comando de barberos para {$barber->email}: " . $e->getMessage());
      }
    }

    $this->info('🏁 Proceso de agendas diarias finalizado.');
    return 0;
  }
}