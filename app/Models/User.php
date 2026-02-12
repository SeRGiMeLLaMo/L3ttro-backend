<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory; 
    protected $fillable = [ 
        'name', 
        'email', 
        'password', 
        ];

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
