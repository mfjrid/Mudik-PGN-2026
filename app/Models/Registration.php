<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'bus_id',
        'status',
        'total_members',
        'deposit_amount',
        'payment_status',
        'etiket_path',
        'qr_code',
        'cancellation_reason',
        'departure_location',
        'checked_in_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
