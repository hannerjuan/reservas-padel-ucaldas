<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id', 'start_time', 'end_time', 'status', 'user_name', 'user_email', 'notes'
    ];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}