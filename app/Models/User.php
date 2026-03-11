<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserFollow;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'description',
        'photo',
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

    public function followers()
    {
        return $this->hasMany(UserFollow::class, 'followed_id');
    }

    public function following()
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }
}
