<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'title',
        'description',
        'genre_id',
        'user_id',
        'cover_image'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
