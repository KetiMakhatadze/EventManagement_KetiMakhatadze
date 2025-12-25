<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'event_id', 'booking_number', 'quantity',
        'total_price', 'status', 'qr_code', 'checked_in_at'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'checked_in_at' => 'datetime',
    ];

    // კავშირი 7: Booking -> User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // კავშირი 8: Booking -> Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // კავშირი 9: Booking -> Participants
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_number = 'BK-' . strtoupper(uniqid());
        });
    }
}