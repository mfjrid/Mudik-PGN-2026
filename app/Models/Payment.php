<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'external_id',
        'amount',
        'type',
        'status',
        'payment_method',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
