<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $actionMessage;

    public function __construct(Reservation $reservation, $actionMessage)
    {
        $this->reservation = $reservation;
        $this->actionMessage = $actionMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Actualización de tu Reserva de Pádel - ' . ucfirst($this->reservation->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_status', // Crearemos esta vista a continuación
        );
    }
}