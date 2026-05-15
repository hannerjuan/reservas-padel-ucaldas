<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SpaceController extends Controller
{
    // Listado de espacios, filtros por tipo 
    public function publicIndex(Request $request)
    {
        $query = Space::where('is_active', true);
        
        // Filtro opcional por tipo de cancha
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return Inertia::render('Public/Home', [
            'spaces' => $query->get(),
            'filters' => $request->only(['type'])
        ]);
    }

    // Detalle del espacio y próximos horarios disponibles 
    public function publicShow(Request $request, Space $space)
    {
        $date = $request->input('date', \Carbon\Carbon::today()->toDateString());
        $slotMinutes = (int) env('RESERVATION_SLOT_MINUTES', 60);

        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;
        $baseAvailability = $space->availabilities()->where('day_of_week', $dayOfWeek)->first();

        // Traemos las reglas de precios específicas de esta cancha para este día de la semana
        $specialRules = $space->priceRules()->where('day_of_week', $dayOfWeek)->get();

        $availableSlots = [];
        $openTime = '08:00:00';
        $closeTime = '22:00:00';
        $isOpen = true;

        if ($baseAvailability) {
            $isOpen = $baseAvailability->is_open;
            $openTime = $baseAvailability->start_time;
            $closeTime = $baseAvailability->end_time;
        }

        if ($isOpen) {
            $start = \Carbon\Carbon::parse($date . ' ' . $openTime);
            $end = \Carbon\Carbon::parse($date . ' ' . $closeTime);

            $busySlots = \App\Models\Reservation::where('space_id', $space->id)
                ->whereIn('status', ['pendiente', 'confirmada'])
                ->whereDate('start_time', $date)
                ->get();

            $manualBlocks = \App\Models\BlockedSlot::where('space_id', $space->id)
                ->whereDate('start_time', $date)
                ->get();

            while ($start->copy()->addMinutes($slotMinutes) <= $end) {
                $slotEnd = $start->copy()->addMinutes($slotMinutes);
                
                $isBusy = $busySlots->contains(function ($res) use ($start, $slotEnd) {
                    return ($start < $res->end_time && $slotEnd > $res->start_time);
                }) || $manualBlocks->contains(function ($block) use ($start, $slotEnd) {
                    return ($start < $block->end_time && $slotEnd > $block->start_time);
                });

                if (!$isBusy && $start > \Carbon\Carbon::now()) {
                    $currentTimeStr = $start->format('H:i:s');
                    
                    // EVALUACIÓN DE PRECIO DINÁMICO: Verificamos si la franja actual coincide con alguna regla
                    $matchedRule = $specialRules->first(function ($rule) use ($currentTimeStr) {
                        return $currentTimeStr >= $rule->start_time && $currentTimeStr < $rule->end_time;
                    });

                    // Si hay regla aplica ese precio, de lo contrario el base de la cancha
                    $finalPrice = $matchedRule ? $matchedRule->price_per_hour : $space->price_per_hour;

                    $availableSlots[] = [
                        'start' => $start->toDateTimeString(),
                        'display' => $start->format('h:i A'),
                        'price' => (int) $finalPrice, // Pasamos el precio dinámico calculado
                        'label' => $matchedRule ? $matchedRule->label : null // Ejem: "Hora Pico"
                    ];
                }

                $start->addMinutes($slotMinutes);
            }
        }

        return \Inertia\Inertia::render('Public/SpaceDetail', [
            'space' => $space,
            'availableSlots' => $availableSlots,
            'selectedDate' => $date
        ]);
    }

    // Vista de calendario semanal (Panel Administrativo)
    public function calendar(Request $request)
    {
        // 1. Determinar el espacio (por defecto tomamos el primero activo si no hay filtro)
        $spaceId = $request->input('space_id');
        $space = $spaceId ? Space::findOrFail($spaceId) : Space::where('is_active', true)->first();

        // 2. Determinar la semana actual basada en la fecha solicitada
        $date = $request->input('date', Carbon::today()->toDateString());
        $startOfWeek = Carbon::parse($date)->startOfWeek(); // Lunes
        $endOfWeek = Carbon::parse($date)->endOfWeek();   // Domingo

        // 3. Obtener reservas (solo pendientes y confirmadas)
        $reservations = Reservation::where('space_id', $space->id)
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        // 4. Obtener bloqueos manuales (ej. mantenimiento de la cancha)
        $blockedSlots = BlockedSlot::where('space_id', $space->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->get();

        return Inertia::render('Admin/Calendar', [
            'selectedSpace' => $space,
            'spaces' => Space::where('is_active', true)->get(['id', 'name']), // Para el select del filtro
            'currentDate' => $date,
            'startOfWeek' => $startOfWeek->toDateString(),
            'endOfWeek' => $endOfWeek->toDateString(),
            'reservations' => $reservations,
            'blockedSlots' => $blockedSlots
        ]);
    }
    
    // ==========================================
    // MÉTODOS DEL PANEL DE ADMINISTRACIÓN (CRUD)
    // ==========================================

    // Listar espacios en el panel admin
    public function index()
    {
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => Space::all()
        ]);
    }

    // Mostrar formulario de creación
    public function create()
    {
        return Inertia::render('Admin/Spaces/Create');
    }

    // Guardar nuevo espacio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spaces',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        Space::create($validated);

        return redirect()->route('spaces.index')->with('success', 'Cancha de pádel creada exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit(Space $space)
    {
        return Inertia::render('Admin/Spaces/Edit', [
            'space' => $space
        ]);
    }

    // Actualizar espacio existente
    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spaces,name,' . $space->id,
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        // Actualizamos el slug si el nombre cambió
        if ($request->name !== $space->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $space->update($validated);

        return redirect()->route('spaces.index')->with('success', 'Cancha actualizada correctamente.');
    }

    // Eliminar espacio
    public function destroy(Space $space)
    {
        // Validación opcional: Evitar eliminar si tiene reservas confirmadas
        if ($space->reservations()->where('status', 'confirmada')->exists()) {
            return back()->with('error', 'No puedes eliminar una cancha que tiene reservas activas.');
        }

        $space->delete();
        return redirect()->route('spaces.index')->with('success', 'Cancha eliminada.');
    }

}