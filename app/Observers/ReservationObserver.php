<?php

namespace App\Observers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Mail; // 1. Importar la fachada Mail
use App\Mail\ReservationStatusMail; // 2. IMPORTANTE: Pon aquí el nombre de tu Mailable real

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        // Le pasamos la reserva y el estado inicial ('pendiente')
        \Illuminate\Support\Facades\Mail::to($reservation->user_email)
            ->send(new \App\Mail\ReservationStatusMail($reservation, 'pendiente'));
    }

    public function updated(Reservation $reservation): void
    {
        // Verificamos si el estado cambió en la base de datos
        if ($reservation->isDirty('status')) {
            
            // Le pasamos DOS parámetros: la reserva y el texto del nuevo estado ('confirmada', 'rechazada', etc.)
            \Illuminate\Support\Facades\Mail::to($reservation->user_email)
                ->send(new \App\Mail\ReservationStatusMail($reservation, $reservation->status));
                
        }
    }
}