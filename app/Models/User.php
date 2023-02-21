<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'displayName',
        'username',
        'email',
        'password',
        'timezone',
        'location',
        'birthDate',
        'image',
        'roles',
        'isSubscribed',
        'lastSeen',
        'bio'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthDate' => 'datetime',
        'roles' => 'array',
        'isSubscribed' => 'boolean',
        'lastSeen' => 'datetime'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function warrants()
    {
        return $this->hasMany(Warrant::class);
    }
}
