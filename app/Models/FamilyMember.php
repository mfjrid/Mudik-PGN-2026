<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'registration_id',
        'seat_id',
        'name',
        'identity_number',
        'gender',
        'age',
        'is_child',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
