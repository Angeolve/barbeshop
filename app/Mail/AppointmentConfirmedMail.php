<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentConfirmedMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Mailable: Confirmation de cita con ticket PDF adjunto.
   * - Construye el PDF usando `Pdf::loadView('pdf.appointment_ticket', [...])`.
   * - Adjunta el PDF con `attachData($pdf->output(), $fileName, ['mime' => 'application/pdf'])`.
   * - Recomendación: para producción implementar `ShouldQueue` y usar
   *   `Mail::to(...)->queue($mailable)` para no bloquear la respuesta HTTP.
   */

  public $appointment;

  /**
   * Create a new message instance.
   */
  public function __construct(Appointment $appointment)
  {
    // Aseguramos cargar relaciones relevantes
    $this->appointment = $appointment->load(['client', 'barber', 'service']);
  }

  /**
   * Build the message.
   */
  public function build()
  {
    $appointment = $this->appointment;

    // Generar PDF en memoria
    $pdf = Pdf::loadView('pdf.appointment_ticket', ['appointment' => $appointment])
      ->setPaper('a4', 'portrait');

    $fileName = 'Ticket_Cita_' . $appointment->id . '.pdf';

    return $this->subject('Confirmación de cita #' . $appointment->id)
      ->view('emails.appointment_confirmed', ['appointment' => $appointment])
      ->attachData($pdf->output(), $fileName, [
        'mime' => 'application/pdf',
      ]);
  }
}
