<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function stories()
    {
        return $this->belongsToMany(Story::class, 'genre_story');
    }
}
