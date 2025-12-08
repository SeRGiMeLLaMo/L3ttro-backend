<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'description',
        'email',
        'password',
        'rol',
        'photo',
        'follows',
        'stories',
        'follow_button',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'stories' => 'boolean',
            'follow_button' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
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
