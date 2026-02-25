<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'cover_image',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_story'); // tabla pivote: genre_story
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
