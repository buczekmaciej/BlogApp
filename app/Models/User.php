<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Uuids;

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
        'bio',
        'isDisabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthDate' => 'datetime',
        'isSubscribed' => 'boolean',
        'lastSeen' => 'datetime',
        'isDsiabled' => 'boolean'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_uuid');
    }

    public function likes()
    {
        return $this->belongsToMany(Article::class, 'likes');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Article::class, 'bookmarks');
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

    public function following()
    {
        return $this->belongsToMany(User::class, 'following', 'follower_uuid', 'user_uuid');
    }

    public function followedBy()
    {
        return $this->belongsToMany(User::class, 'following', 'user_uuid', 'follower_uuid');
    }

    public function roles()
    {
        return json_decode($this->roles);
    }

    public function getRole()
    {
        $roles = $this->roles();
        return ucfirst(strtolower(array_pop($roles)));
    }

    public function isWriter()
    {
        return in_array('WRITER', $this->roles());
    }

    public function isAdmin()
    {
        return in_array("ADMIN", $this->roles());
    }

    public function getName()
    {
        return $this->displayName ?? "@{$this->username}";
    }
}
