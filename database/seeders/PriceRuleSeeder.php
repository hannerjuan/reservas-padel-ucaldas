<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\PriceRule;

class PriceRuleSeeder extends Seeder
{
    public function run(): void
    {
        $cancha1 = Space::where('slug', 'cancha-cristal-1')->first();

        if ($cancha1) {
            // Configurar tarifa de "Hora Pico" de Lunes a Viernes de 6:00 PM a 10:00 PM
            for ($day = 1; $day <= 5; $day++) {
                PriceRule::create([
                    'space_id' => $cancha1->id,
                    'day_of_week' => $day,
                    'start_time' => '18:00:00',
                    'end_time' => '22:00:00',
                    'price_per_hour' => 85000.00,
                    'label' => 'Hora Pico ⚡'
                ]);
            }
        }
    }
}