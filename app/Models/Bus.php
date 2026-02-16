<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'bus_number',
        'route_name',
        'capacity',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
