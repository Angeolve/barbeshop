<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientDailyReminderMail extends Mailable
{
  use Queueable, SerializesModels;

  public $client;
  public $pdfData;
  public $date;
  public $appointment;

  /**
   * Create a new message instance.
   */
  public function __construct($client, $pdfData, $date, $appointment)
  {
    $this->client = $client;
    $this->pdfData = $pdfData;
    $this->date = $date;
    $this->appointment = $appointment;
  }

  /**
   * Comentario: Email diario para cliente con comprobante PDF.
   * - `pdfData` es el contenido binario del PDF generado sobre la cita.
   * - La vista `emails.client_daily_reminder` recibe `client`, `appointment`
   *   y `date`. Si amplías la plantilla, asegúrate de pasar las variables
   *   necesarias desde el comando que lo construye.
   */

  /**
   * Build the message.
   */
  public function build()
  {
    $fileName = 'Comprobante_Cita_' . ($this->appointment->id ?? '0') . '.pdf';

    return $this->subject('Recordatorio de cita para el día ' . $this->date)
      ->view('emails.client_daily_reminder', ['client' => $this->client, 'appointment' => $this->appointment, 'date' => $this->date])
      ->attachData($this->pdfData, $fileName, ['mime' => 'application/pdf']);
  }
}
