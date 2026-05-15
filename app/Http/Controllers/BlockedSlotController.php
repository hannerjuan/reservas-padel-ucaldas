<?php

namespace App\Http\Controllers;

use App\Models\BlockedSlot;
use App\Models\Space;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia; // Necesario para renderizar las vistas de Vue

class BlockedSlotController extends Controller
{
    // 1. Método para renderizar la interfaz administrativa (NUEVO)
    public function index()
    {
        return Inertia::render('Admin/BlockedSlots/Index', [
            // Traemos los bloqueos con su respectiva cancha asociada
            'blockedSlots' => BlockedSlot::with('space')->orderBy('start_time', 'desc')->get(),
            // Traemos las canchas activas para llenar el select del formulario
            'spaces' => Space::where('is_active', true)->get()
        ]);
    }

    // 2. Tu método original para guardar el bloqueo (con leves mejoras de respuesta)
   public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string|max:255'
        ]);

        // Evitar que el bloqueo pise una reserva ya confirmada
        $hasReservations = \App\Models\Reservation::where('space_id', $validated['space_id'])
            ->where('status', 'confirmada')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($hasReservations) {
            // Usamos withErrors para inyectar el texto rojo debajo del input de fecha
            return back()->withErrors([
                'start_time' => '¡Cruce detectado! No puedes bloquear este periodo porque ya hay reservas confirmadas.'
            ]);
        }

        BlockedSlot::create($validated);

        return back();
    }

    // 3. Tu método original para eliminar
    public function destroy(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();
        return back()->with('success', 'Bloqueo liberado. El horario vuelve a estar disponible.');
    }
}