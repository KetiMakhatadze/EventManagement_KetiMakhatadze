<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id', 'first_name', 'last_name', 'email',
        'phone', 'qr_code', 'checked_in', 'checked_in_at'
    ];

    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    // კავშირი 10: Participant -> Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}