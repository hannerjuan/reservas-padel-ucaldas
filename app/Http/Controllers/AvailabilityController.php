<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Availability;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    public function edit($slug)
    {
        $space = Space::where('slug', $slug)->firstOrFail();

        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        foreach ($days as $index => $name) {
            Availability::firstOrCreate(
                ['space_id' => $space->id, 'day_of_week' => $index],
                ['start_time' => '08:00', 'end_time' => '22:00', 'is_open' => true] // Cambiado
            );
        }

        return Inertia::render('Admin/Spaces/Availability', [
            'space' => $space,
            'availabilities' => $space->availabilities()->orderBy('day_of_week')->get()
        ]);
    }

    public function update(Request $request, $slug)
    {
        $space = Space::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.id' => 'required|exists:availabilities,id',
            'availabilities.*.start_time' => 'required', // Cambiado
            'availabilities.*.end_time' => 'required',   // Cambiado
            'availabilities.*.is_open' => 'required|boolean',
        ]);

        foreach ($data['availabilities'] as $item) {
            Availability::where('id', $item['id'])->update([
                'start_time' => $item['start_time'], // Cambiado
                'end_time' => $item['end_time'],     // Cambiado
                'is_open' => $item['is_open'],
            ]);
        }

        return back()->with('success', 'Horarios actualizados correctamente.');
    }
}