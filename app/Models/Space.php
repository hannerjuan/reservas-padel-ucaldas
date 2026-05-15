<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'capacity', 'description', 'price_per_hour', 'is_active'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function blockedSlots()
    {
        return $this->hasMany(BlockedSlot::class);
    }
    public function priceRules()
{
    return $this->hasMany(PriceRule::class);
}
}