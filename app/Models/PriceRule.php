<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'day_of_week',
        'start_time',
        'end_time',
        'price_per_hour',
        'label'
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}