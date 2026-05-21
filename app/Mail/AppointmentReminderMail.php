<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
  use Queueable, SerializesModels;

  public $appointment;

  /**
   * Create a new message instance.
   */
  public function __construct(Appointment $appointment)
  {
    $this->appointment = $appointment->load(['client', 'barber', 'service']);
  }

  /**
   * Build the message.
   */
  public function build()
  {
    return $this->subject('Recordatorio de tu cita #' . $this->appointment->id)
      ->view('emails.appointment_reminder', ['appointment' => $this->appointment]);
  }
}
