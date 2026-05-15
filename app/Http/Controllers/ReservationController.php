<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationStatusMail;
use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ReservationController extends Controller
{
   public function index(Request $request)
    {
        // Iniciamos la consulta base
        $query = Reservation::with('space')->orderBy('start_time', 'desc');

        // 1. Filtro por búsqueda (Nombre o Correo)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('user_name', 'like', $searchTerm)
                  ->orWhere('user_email', 'like', $searchTerm);
            });
        }

        // 2. Filtro por Estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filtro por Espacio/Cancha
        if ($request->filled('space_id')) {
            $query->where('space_id', $request->space_id);
        }

        // 4. Filtro por Fecha Exacta
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        return \Inertia\Inertia::render('Admin/ReservationsIndex', [
            // Enviamos los datos paginados y mantenemos los parámetros en la URL
            'reservations' => $query->paginate(10)->withQueryString(),
            // Retornamos los filtros actuales para que los inputs no se borren en el frontend
            'filters' => $request->only(['search', 'status', 'space_id', 'date']),
            // Enviamos la lista de canchas activas para llenar el menú desplegable
            'spaces' => \App\Models\Space::where('is_active', true)->get(['id', 'name']) 
        ]);
    }


   
   // Mostrar formulario de reserva
    public function create(Request $request)
    {
        $request->validate([
            'space' => 'required|exists:spaces,slug',
            'start' => 'nullable|date'
        ]);

        $space = Space::where('slug', $request->space)->firstOrFail();
        
        // ¡La solución está aquí! Añadimos (int) para asegurar que Carbon reciba un número
        $slotMinutes = (int) env('RESERVATION_SLOT_MINUTES', 60); 
        
        // Si la URL no trae fecha, sugerimos la próxima hora en punto por defecto
        $startTime = $request->has('start') 
            ? Carbon::parse($request->start) 
            : Carbon::now()->addHour()->startOfHour();
            
        $endTime = $startTime->copy()->addMinutes($slotMinutes);

        return Inertia::render('Public/ReservationForm', [
            'space' => $space,
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString(),
        ]);
    }

    // Crea la reserva y valida colisiones 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'notes' => 'nullable|string'
        ],[
            
            'start_time.after' => 'La reserva debe ser para una fecha y hora futura.',
            'end_time.after' => 'La fecha de fin no puede ser anterior a la de inicio.'
        ]);

        // Validación estricta en backend: No permitir reservas que se solapen 
        $hasCollision = Reservation::where('space_id', $validated['space_id'])
            ->whereIn('status', ['pendiente', 'confirmada']) // Ignorar las rechazadas/canceladas
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($hasCollision) {
            return back()->withErrors(['start_time' => 'El horario seleccionado ya fue reservado o está bloqueado.']);
        }

        // El estado inicial siempre es pendiente 
        $validated['status'] = 'pendiente'; 
        
        $reservation = Reservation::create($validated);

        // Enviar correo de creación exitosa
        Mail::to($reservation->user_email)->send(new ReservationStatusMail(
            $reservation, 
            'Hemos recibido tu solicitud de reserva. Actualmente está pendiente de revisión por el administrador.'
        ));

        return redirect()->route('home')
            ->with('success', 'Reserva solicitada correctamente. Pendiente de aprobación.');
    }

    // Aprobar reserva 
    public function accept(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmada']);
        
        Mail::to($reservation->user_email)->send(new ReservationStatusMail(
            $reservation, 
            '¡Buenas noticias! Tu reserva para la cancha de pádel ha sido confirmada.'
        ));
        
        return back()->with('success', 'Reserva confirmada. El usuario ha sido notificado.');
    }

    // Rechazar reserva 
    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'rechazada']);
        
        Mail::to($reservation->user_email)->send(new ReservationStatusMail(
            $reservation, 
            'Lo sentimos, tu solicitud de reserva ha sido rechazada por el administrador debido a falta de disponibilidad u otro evento.'
        ));
        
        return back()->with('success', 'Reserva rechazada. El usuario ha sido notificado.');
    }

    // Cancelar reserva (si ya estaba confirmada)
    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelada']);
        
        Mail::to($reservation->user_email)->send(new ReservationStatusMail(
            $reservation, 
            'Tu reserva confirmada ha sido cancelada. Si tienes dudas, comunícate con nosotros.'
        ));
        
        return back()->with('success', 'Reserva cancelada. El usuario ha sido notificado.');
    }


    public function dashboard()
    {
        $now = Carbon::now();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pendientes' => Reservation::where('status', 'pendiente')->count(),
                'confirmadas' => Reservation::where('status', 'confirmada')->count(),
                'proximas' => Reservation::where('status', 'confirmada')
                    ->where('start_time', '>', $now)
                    ->count(),
            ],
            'recent_reservations' => Reservation::with('space')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ]);
    }

}