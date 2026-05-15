<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    // Esta es la aduana de Laravel. Si no está en esta lista, no pasa a la base de datos.
    protected $fillable = [
        'space_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_open',
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}