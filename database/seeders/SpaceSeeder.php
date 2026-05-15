<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;
use Illuminate\Support\Str;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = [
            [
                'name' => 'Cancha Cristal 1',
                'type' => 'Pádel Techada',
                'capacity' => 4,
                'description' => 'Cancha de cristal techada con iluminación LED profesional.',
                'price_per_hour' => 60000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Cancha Cristal 2',
                'type' => 'Pádel Techada',
                'capacity' => 4,
                'description' => 'Cancha de cristal techada, ideal para torneos.',
                'price_per_hour' => 60000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Cancha Panorámica',
                'type' => 'Pádel Descubierta',
                'capacity' => 4,
                'description' => 'Cancha al aire libre con vista panorámica 360.',
                'price_per_hour' => 45000.00,
                'is_active' => true,
            ]
        ];

        foreach ($spaces as $space) {
            $space['slug'] = Str::slug($space['name']);
            Space::create($space);
        }
    }
}