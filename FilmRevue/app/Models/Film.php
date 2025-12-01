<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{


    protected $fillable = [
        'title',
        'release_year',
        'length',
        'description',
        'rating', 
        'language_id',
        'special_features',
        'image',
        'created_at',
        'updated_at'   
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

     public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_film');
    }

    public function critics()
    {
        return $this->hasMany(Critic::class);
    }
}
