<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'bus_id',
        'seat_number',
        'status',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function familyMember()
    {
        return $this->hasOne(FamilyMember::class);
    }
}
