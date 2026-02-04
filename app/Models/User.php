<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
