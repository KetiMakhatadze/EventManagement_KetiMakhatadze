<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description'];

    // კავშირი 6: Category -> Events (Many-to-Many)
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}