<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = ['first_name', 'last_name', 'bithdate'];

    public function films()
    {
        return $this->belongsToMany(Film::class, 'actor_film');
    }
}
