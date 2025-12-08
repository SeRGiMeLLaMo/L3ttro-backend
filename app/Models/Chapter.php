<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'story_id',
        'title',
        'content',
        'order'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
