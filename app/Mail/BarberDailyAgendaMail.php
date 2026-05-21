<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BarberDailyAgendaMail extends Mailable
{
  use Queueable, SerializesModels;

  public $barber;
  public $date;
  public $pdfData;
  public $appointments; // <-- Declaramos la variable de las citas

  public function __construct($barber, $pdfData, $date, $appointments = null)
  {
    $this->barber = $barber;
    $this->pdfData = $pdfData;
    $this->date = $date;
    $this->appointments = $appointments; // <-- La asignamos en el constructor
  }

  public function build()
  {
    $fileName = 'Agenda_Barbero_' . ($this->barber->id ?? '0') . '_' . $this->date . '.pdf';

    return $this->subject('📅 Tu Agenda Diaria - ' . $this->date)
      ->view('emails.barber_daily_agenda')
      ->with([
        'barber' => $this->barber,
        'date' => $this->date,
        'appointments' => $this->appointments // <-- Se la enviamos de forma explícita a la vista HTML
      ])
      ->attachData($this->pdfData, $fileName, [
        'mime' => 'application/pdf',
      ]);
  }
}