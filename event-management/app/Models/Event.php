<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'image', 'location',
        'start_date', 'end_date', 'total_seats', 'available_seats',
        'price', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    // კავშირი 3: Event -> User (ორგანიზატორი)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // კავშირი 4: Event -> Categories (Many-to-Many)
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // კავშირი 5: Event -> Bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable()
    {
        return $this->available_seats > 0 && $this->status === 'published';
    }

    public function isFull()
    {
        return $this->available_seats <= 0;
    }
}