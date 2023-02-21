<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'content',
        'title',
        'slug',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function warrant()
    {
        return $this->hasOne(Warrant::class);
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class);
    }
}
